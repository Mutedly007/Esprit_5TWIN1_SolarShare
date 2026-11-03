<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiHubScheduleService
{
    protected string $apiKey;

    protected string $defaultModel;

    /**
     * Fallback Gemini models for robust error handling.
     *
     * @var array<int, string>
     */
    protected array $fallbackModels = [
        'gemini-3.5-flash',
        'gemini-3.5-flash-lite',
        'gemini-flash-lite-latest',
        'gemini-3.1-flash-lite',
        'gemini-3.6-flash',
        'gemini-3.7-flash',
        'gemini-3.8-flash',
        'gemini-flash-latest',
    ];

    public function __construct()
    {
        $this->apiKey = (string) (config('services.gemini.hubs_api_key') ?: config('services.gemini.api_key'));
        $this->defaultModel = (string) config('services.gemini.model', 'gemini-3.5-flash');
    }

    /**
     * Parse natural language schedule description into structured operating slots.
     *
     * @param string $description E.g. "Ouvert du lundi au vendredi de 9h à 18h avec pause déjeuner de 12h30 à 14h, samedi matin jusqu'à 13h"
     * @return array<int, array{day_of_week: string, open_time: string, close_time: string, notes: string}>
     *
     * @throws \RuntimeException
     */
    public function generateSlots(string $description): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException("La clé API Gemini (GEMINI_HUBS_API_KEY) n'est pas configurée dans le fichier .env.");
        }

        $systemInstruction = <<<TEXT
You are an expert AI schedule assistant for SolarShare collection hubs (Points Relais).
Your role is to accurately convert natural language descriptions of opening hours into a structured list of weekly operating slots.

Strict Rules:
1. Days must be strictly normalized to French lowercase: "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche".
2. Times must be formatted in strict 24-hour "HH:MM" format (e.g. "09:00", "12:30", "14:00", "18:00"). Always include leading zeroes.
3. Lunch breaks / split shifts: If a lunch break or multiple periods are mentioned on a day (e.g., "9h to 18h with lunch break 12:30 to 14:00"), split that day into multiple distinct non-overlapping slots:
   - Slot 1: 09:00 to 12:30 with note "Matinée" or "Avant pause déjeuner"
   - Slot 2: 14:00 to 18:00 with note "Après-midi (pause respectée)"
4. Days explicitly mentioned as closed (e.g. "fermé le dimanche", "closed Sunday") MUST NOT produce any slots.
5. In each slot, close_time MUST strictly be after open_time.
6. Provide a concise, helpful explanation in French in the "notes" field (e.g. "Permanence journée", "Matinée", "Service continu", "Permanence samedi").
7. Output format: You must return ONLY a JSON array of objects. Each object must have exactly these keys:
   - "day_of_week": string (lundi|mardi|mercredi|jeudi|vendredi|samedi|dimanche)
   - "open_time": string ("HH:MM")
   - "close_time": string ("HH:MM")
   - "notes": string

Example Output:
[
  {"day_of_week": "lundi", "open_time": "09:00", "close_time": "12:30", "notes": "Matinée"},
  {"day_of_week": "lundi", "open_time": "14:00", "close_time": "18:00", "notes": "Après-midi (pause déjeuner respectée)"},
  {"day_of_week": "samedi", "open_time": "09:00", "close_time": "13:00", "notes": "Matinée uniquement"}
]
TEXT;

        $prompt = "Voici la description des horaires d'ouverture à analyser et convertir en créneaux structurés :\n\n\"" . trim($description) . "\"";

        $caPath = storage_path('certs/cacert.pem');
        $verify = file_exists($caPath) ? $caPath : true;

        $curlOptions = [];
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            $curlOptions[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }

        $client = new Client([
            'verify' => $verify,
            'timeout' => 20,
            'connect_timeout' => 8,
            'curl' => $curlOptions,
        ]);

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'topP' => 0.9,
                'response_mime_type' => 'application/json',
                'maxOutputTokens' => 2048,
            ],
        ];

        $candidateModels = array_values(array_unique(array_merge([$this->defaultModel], $this->fallbackModels)));
        $lastException = null;

        foreach ($candidateModels as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $this->apiKey;

            try {
                $response = $client->post($url, [
                    'json' => $payload,
                ]);

                $data = json_decode((string) $response->getBody(), true);
                $rawText = $this->extractRawText($data);

                if (!empty($rawText)) {
                    $slots = $this->parseAndSanitizeJson($rawText);
                    if (!empty($slots)) {
                        return $slots;
                    }
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
                if (in_array($statusCode, [404, 429], true)) {
                    $lastException = $e;
                    continue;
                }
                throw $e;
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $lastException = $e;
                continue;
            } catch (\Throwable $e) {
                $lastException = $e;
                Log::warning("AI Schedule Assistant Gemini model {$model} warning: " . $e->getMessage());
                continue;
            }
        }

        if ($lastException) {
            throw new \RuntimeException("Impossible d'obtenir une réponse de Gemini : " . $lastException->getMessage(), 0, $lastException);
        }

        throw new \RuntimeException("Aucune plage horaire n'a pu être interprétée à partir de votre description.");
    }

    /**
     * Extract generated text from Gemini API response structure.
     */
    protected function extractRawText(array $data): ?string
    {
        $parts = $data['candidates'][0]['content']['parts'] ?? [];
        $collected = [];
        foreach ($parts as $part) {
            if (isset($part['text']) && is_string($part['text'])) {
                $collected[] = $part['text'];
            }
        }
        return !empty($collected) ? implode('', $collected) : null;
    }

    /**
     * Clean and parse json array of slots.
     *
     * @return array<int, array{day_of_week: string, open_time: string, close_time: string, notes: string}>
     */
    protected function parseAndSanitizeJson(string $text): array
    {
        // Remove markdown code fences if model enclosed in ```json ... ```
        $clean = trim($text);
        if (str_starts_with($clean, '```')) {
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);
            $clean = trim($clean);
        }

        $decoded = json_decode($clean, true);
        if (!is_array($decoded)) {
            return [];
        }

        // If wrapped in an outer object like {"slots": [...]}
        if (isset($decoded['slots']) && is_array($decoded['slots'])) {
            $decoded = $decoded['slots'];
        }

        $validDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $sanitized = [];

        foreach ($decoded as $item) {
            if (!is_array($item)) {
                continue;
            }

            $day = strtolower(trim((string) ($item['day_of_week'] ?? '')));
            // Map common English day names just in case
            $dayMap = [
                'monday' => 'lundi',
                'tuesday' => 'mardi',
                'wednesday' => 'mercredi',
                'thursday' => 'jeudi',
                'friday' => 'vendredi',
                'saturday' => 'samedi',
                'sunday' => 'dimanche',
            ];
            if (isset($dayMap[$day])) {
                $day = $dayMap[$day];
            }

            if (!in_array($day, $validDays, true)) {
                continue;
            }

            $open = trim((string) ($item['open_time'] ?? ''));
            $close = trim((string) ($item['close_time'] ?? ''));

            // Normalize single digits like 9:00 -> 09:00
            if (preg_match('/^(\d):(\d{2})$/', $open, $m)) {
                $open = '0' . $m[1] . ':' . $m[2];
            }
            if (preg_match('/^(\d):(\d{2})$/', $close, $m)) {
                $close = '0' . $m[1] . ':' . $m[2];
            }

            $notes = trim((string) ($item['notes'] ?? 'Généré par IA'));

            $sanitized[] = [
                'day_of_week' => $day,
                'open_time' => $open,
                'close_time' => $close,
                'notes' => $notes ?: 'Généré par IA',
            ];
        }

        return $sanitized;
    }
}

