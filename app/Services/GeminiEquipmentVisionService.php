<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiEquipmentVisionService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY', ''));
        $this->model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-2.5-flash'));
    }

    /**
     * Analyze an equipment photo using Gemini Vision API.
     *
     * @param UploadedFile|string $imageFile
     * @return array
     */
    public function analyzePhoto($imageFile): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'error' => 'La clé API Gemini n\'est pas configurée dans les variables d\'environnement (GEMINI_API_KEY).',
            ];
        }

        // Extract base64 and mime type
        if ($imageFile instanceof UploadedFile) {
            $mimeType = $imageFile->getMimeType() ?: 'image/jpeg';
            $base64Data = base64_encode(file_get_contents($imageFile->getRealPath()));
        } elseif (is_string($imageFile) && file_exists($imageFile)) {
            $mimeType = mime_content_type($imageFile) ?: 'image/jpeg';
            $base64Data = base64_encode(file_get_contents($imageFile));
        } else {
            return [
                'success' => false,
                'error' => 'Fichier image invalide ou introuvable.',
            ];
        }

        // Retrieve existing categories to help Gemini accurately classify
        $categories = Category::select('id', 'name')->get();
        $categoriesList = $categories->map(fn($c) => "ID {$c->id}: {$c->name}")->implode(', ');

        $prompt = <<<PROMPT
You are an expert AI inspector for SolarShare, a renewable energy equipment rental platform.
Analyze the provided photograph of an equipment item.

The platform categories available are:
{$categoriesList}

Carefully evaluate the image and return a JSON object with the following fields:
1. "is_valid_equipment": boolean (true IF the image displays renewable energy or electrical equipment like solar panels, portable power stations/batteries, inverters, micro-wind turbines, charge controllers, cables/accessories. Set false if the photo is irrelevant: person, animal, car, food, furniture, document, or completely unrecognizable).
2. "is_blurry_or_low_quality": boolean (true if the photo is significantly blurry, dark, heavily pixelated, or out of focus making identification difficult).
3. "quality_warning": string or null (in French, explanation if photo is blurry, dark, or not clear. null if photo is crisp and clear).
4. "relevance_explanation": string or null (in French, why this photo is rejected if is_valid_equipment is false).
5. "identified_type": string (in French, e.g., "Panneau solaire pliable monocristallin", "Station électrique portable", "Onduleur pur sinus 12V/230V").
6. "name": string (A clean, professional title for the rental listing in French, e.g. "Panneau Solaire Pliable 200W EcoFlow", "Générateur Solaire LiFePO4 1024Wh").
7. "brand": string (Brand or manufacturer detected from logo, markings, or typical design, e.g. "EcoFlow", "Bluetti", "Renogy", "Anker", "Victron Energy", "Jackery", or "Générique" if unbranded).
8. "matched_category_id": integer or null (The ID from the platform categories list above that best matches this equipment).
9. "power_watts": integer or null (Estimated rated power in Watts, e.g. 100, 200, 400, 1000, 2000. null if not applicable).
10. "capacity_wh": integer or null (Estimated battery capacity in Watt-hours if it is a battery/power station, e.g. 512, 1024, 2048. null if it is a solar panel or inverter without battery).
11. "daily_rate": number (Recommended daily rental rate in Tunisian Dinars DT, realistic for Tunisia market, e.g., 10.00 to 45.00 DT depending on value and power).
12. "deposit_amount": number (Recommended security deposit in Tunisian Dinars DT, reflecting equipment replacement value and condition).
13. "damage_detected": boolean (true if any visible signs of damage, scratches, dents, cracked glass/solar cells, torn cables, or heavy wear are observed).
14. "damage_assessment": string (in French, precise details of any visible flaws/scratches, or "Matériel en excellent état visuel, aucun dommage détecté.").
15. "condition_summary": string (in French, overall condition summary: e.g., "Neuf / Comme neuf", "Très bon état", "Bon état avec légères traces d'usure", "Endommagé").
16. "deposit_recommendation_reason": string (in French, brief reason for the suggested deposit, mentioning if it was adjusted for condition/damage).
17. "description": string (in French, an attractive, detailed 2-3 sentence description suitable for the SolarShare marketplace, mentioning key features, connectors, and usage advice).

Answer strictly in valid JSON format.
PROMPT;

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $base64Data,
                                ],
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json',
                ],
            ];

            $response = Http::timeout(35)->post($endpoint, $payload);

            if ($response->failed()) {
                Log::error('Gemini Vision API error: ' . $response->body());
                return [
                    'success' => false,
                    'error' => 'Erreur lors de la communication avec l\'API Gemini (' . $response->status() . ').',
                ];
            }

            $jsonResponse = $response->json();
            $rawText = $jsonResponse['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$rawText) {
                return [
                    'success' => false,
                    'error' => 'Aucune réponse générée par Gemini Vision.',
                ];
            }

            // Clean markdown code blocks if present
            $cleaned = trim($rawText);
            if (str_starts_with($cleaned, '```json')) {
                $cleaned = substr($cleaned, 7);
            } elseif (str_starts_with($cleaned, '```')) {
                $cleaned = substr($cleaned, 3);
            }
            if (str_ends_with($cleaned, '```')) {
                $cleaned = substr($cleaned, 0, -3);
            }

            $data = json_decode(trim($cleaned), true);

            if (!is_array($data)) {
                return [
                    'success' => false,
                    'error' => 'Impossible de décoder l\'analyse de Gemini.',
                ];
            }

            return [
                'success' => true,
                'data' => $data,
            ];

        } catch (\Throwable $e) {
            Log::error('GeminiEquipmentVisionService exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Exception lors de l\'analyse : ' . $e->getMessage(),
            ];
        }
    }
}
