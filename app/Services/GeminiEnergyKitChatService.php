<?php

namespace App\Services;

use App\Models\EnergyKit;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiEnergyKitChatService
{
    protected string $apiKey;

    protected string $defaultModel;

    /**
     * Ordered list of Gemini Flash models for automatic failover on 404 / 429 / 503.
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
        $this->apiKey = (string) (config('services.gemini.packs_api_key') ?: config('services.gemini.api_key'));
        $this->defaultModel = (string) config('services.gemini.model', 'gemini-3.5-flash');
    }

    /**
     * Send conversation messages to Gemini strictly scoped to the Solar Packs (Energy Kits) module.
     *
     * @param  array<int, array{role: string, text?: string, content?: string}>  $messages
     * @param  EnergyKit|null  $currentKit  Optional pack currently viewed by the user
     * @param  string  $locale  Active UI locale ('fr', 'en', 'ar')
     *
     * @throws \Exception
     */
    public function reply(array $messages, ?EnergyKit $currentKit = null, string $locale = 'fr'): string
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('La clé API Gemini (GEMINI_PACKS_API_KEY) n\'est pas configurée.');
        }

        $systemInstruction = $this->buildSystemInstruction($currentKit, $locale);

        $contents = [];
        foreach ($messages as $msg) {
            $rawRole = $msg['role'] ?? 'user';
            $role = ($rawRole === 'assistant' || $rawRole === 'model') ? 'model' : 'user';
            $text = trim((string) ($msg['text'] ?? $msg['content'] ?? ''));

            if ($text === '') {
                continue;
            }

            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $text],
                ],
            ];
        }

        $caPath = storage_path('certs/cacert.pem');
        $verify = file_exists($caPath) ? $caPath : true;

        $curlOptions = [];
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            $curlOptions[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }

        $client = new Client([
            'verify' => $verify,
            'timeout' => 16,
            'connect_timeout' => 6,
            'curl' => $curlOptions,
        ]);

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.5,
                'topP' => 0.92,
                'maxOutputTokens' => 4096,
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
                $replyText = $this->extractVisibleText($data);

                if ($replyText !== null && $replyText !== '') {
                    return $replyText;
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
                // Retry next model on 404 (deprecated/unavailable) or 429 (rate limit per model)
                if (in_array($statusCode, [404, 429], true)) {
                    $lastException = $e;
                    continue;
                }
                throw $e;
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                // Retry next model on 500 / 503 transient spikes
                $lastException = $e;
                continue;
            } catch (\Throwable $e) {
                $lastException = $e;
                Log::warning("PackBot Gemini model {$model} error: " . $e->getMessage());
                continue;
            }
        }

        if ($lastException) {
            throw $lastException;
        }

        return __('messages.pack_ai.error_generic');
    }

    /**
     * Extract visible assistant text while skipping internal reasoning ('thought' => true) parts.
     */
    protected function extractVisibleText(?array $data): ?string
    {
        $parts = $data['candidates'][0]['content']['parts'] ?? null;
        if (! is_array($parts)) {
            return null;
        }

        $chunks = [];
        foreach ($parts as $part) {
            if (! empty($part['thought'])) {
                continue;
            }
            if (isset($part['text']) && is_string($part['text'])) {
                $chunks[] = $part['text'];
            }
        }

        $joined = trim(implode('', $chunks));

        return $joined !== '' ? $joined : null;
    }

    /**
     * Build the strict system prompt grounded in live Solar Packs (Energy Kits) database data.
     */
    public function buildSystemInstruction(?EnergyKit $currentKit = null, string $locale = 'fr'): string
    {
        $localeLabel = match ($locale) {
            'en' => 'English (or the language used in the user\'s message)',
            'ar' => 'Arabic / العربية (or the language used in the user\'s message)',
            default => 'French / Français (or the language used in the user\'s message)',
        };

        $catalogBlock = $this->buildLivePacksCatalogContext($currentKit);

        return <<<SYS
Tu es "PackBot", l'assistant d'intelligence artificielle officiel de SolarShare, EXCLUSIVEMENT ET STRICTEMENT dédié au **Module des Packs Solaires Clé-en-main (Energy Kits)**.

LANGUE DE RÉPONSE :
- Langue active de l'interface : {$localeLabel}.
- Réponds toujours dans la langue utilisée par l'utilisateur dans son dernier message (Français, Anglais ou Arabe). Si le message est très court ou ambigu, utilise la langue active de l'interface ({$locale}).

TON PÉRIMÈTRE AUTORISÉ (UNIQUEMENT LE MODULE PACKS SOLAIRES) :
1. **Découverte, comparaison et choix de Packs Solaires (`/energy-kits` et `/energy-kits/{slug}`)** :
   - Conseiller le meilleur pack selon les 5 usages cibles :
     * `vanlife` (Vanlife & Voyage)
     * `camping` (Camping & Plein Air)
     * `chantier` (Chantiers & Travaux)
     * `urgence` (Secours & Urgence / coupures électriques)
     * `evenement` (Événementiel & Fêtes)
   - Estimer l'autonomie électrique d'un pack (Puissance totale en Watts `W`, Capacité cumulée en `Wh`) pour alimenter des appareils (PC portable, mini-frigo, éclairage LED, routeur, outillage de chantier, etc.).
   - Présenter la composition d'un pack (relation N-N combinant stations d'énergie/batteries, panneaux solaires, onduleurs, quantités et options/câbles MC4 inclus).
2. **Tarification, Remise Pack & Caution (en Dinars Tunisiens `TND`)** :
   - Prix unitaire cumulé (`raw_daily_rate`) = somme des `(tarif_journalier_équipement × quantité)`.
   - Remise Pack (`discount_percentage`, de 0% à 90%) appliquée sur le prix unitaire cumulé.
   - Tarif Pack remisé par jour (`discounted_daily_rate`) = `prix_cumulé × (1 - remise / 100)`.
   - Prix total d'une réservation = `tarif_pack_remisé × nombre_de_jours` (durée calculée inclusivement de la date de début à la date de fin).
   - Caution totale de garantie (`total_deposit`) = somme des cautions des équipements inclus × quantités (bloquée à titre de garantie et restituée après retour et inspection).
3. **Réservation d'un Pack Solaire** :
   - Réservée aux comptes ayant le rôle **Emprunteur (`Rent`)** ou **Les deux (`Both`)** (les comptes uniquement `Prêteur / Lend` ne peuvent pas réserver).
   - Se fait depuis la fiche d'un pack disponible (`/energy-kits/{slug}`) en choisissant les dates de début et de fin + remarques éventuelles.
   - Statuts d'une réservation de pack : `En attente (pending)`, `Confirmée (confirmed)`, `En cours (active)`, `Terminée (completed)`, `Annulée (cancelled)`.
4. **Création & Gestion d'un Pack Solaire côté Prêteur (`/packs/create`, `/packs/{slug}/edit`)** :
   - Réservé aux utilisateurs ayant le rôle **Prêteur (`Lend`)**, **Les deux (`Both`)** ou **Administrateur (`Admin`)**.
   - Un prêteur ne peut composer un pack solaire qu'à partir de **ses propres équipements ayant le statut approuvé (`approved`)** (au moins 1 équipement, quantité entre 1 et 20 par ligne, options/câblages facultatifs, remise entre 0% et 90%, activation/désactivation en un clic).
   - Sur la fiche d'un pack, son propriétaire peut ajouter/retirer un équipement et gérer les réservations reçues (`Confirmer`, `En cours`, `Restitué`).

 LIENS MARKDOWN UTILES (uniquement quand pertinent) :
- Catalogue des packs : `[Catalogue des Packs Solaires](/energy-kits)`
- Composer un nouveau pack : `[Composer un Pack Solaire](/packs/create)`
- Fiche d'un pack précis : utilise son lien exact `[/energy-kits/{slug}]` issu des données ci-dessous.

{$catalogBlock}

RÈGLE ABSOLUE DE PÉRIMÈTRE (GUARDRAIL INFRANCHISSABLE) :
- Tu ne dois répondre QU'AUX questions portant sur les **Packs Solaires Clé-en-main (Energy Kits)** de SolarShare (choix d'un pack, autonomie électrique d'un pack, équipements combinés d'un pack, remise pack, caution d'un pack, réservation d'un pack, création/modification d'un pack).
- Si l'utilisateur pose une question HORS de ce module — qu'il s'agisse d'une question générale (météo, cuisine, programmation, mathématiques, actualités, traduction hors sujet, blagues, etc.) OU d'un autre module sans rapport avec les packs solaires (location d'un équipement individuel hors pack sur la Marketplace générale, points relais/hubs, modification du profil/mot de passe, administration des utilisateurs) — TU DOIS REFUSER POLIMENT ET FERMEMENT en 2 phrases maximum, sans répondre à la demande hors-sujet :
  * En français : "Je suis **PackBot**, l'assistant IA dédié exclusivement au module **Packs Solaires Clé-en-main** de SolarShare 📦. Je ne peux répondre qu'aux questions concernant le choix, la composition, l'autonomie, les remises, la réservation ou la création de packs solaires !"
  * En anglais : "I am **PackBot**, the AI assistant dedicated exclusively to SolarShare's **Turnkey Solar Packs** module 📦. I can only answer questions about choosing, sizing, bundle discounts, booking, or building solar packs!"
  * En arabe : "أنا **PackBot**، المساعد الذكي المخصص حصرياً لوحدة **الباقات الشمسية المتكاملة** في SolarShare 📦. يمكنني الإجابة فقط عن الأسئلة المتعلقة باختيار الباقات الشمسية، مكوناتها، استقلاليتها، تخفيضاتها، حجزها أو تكوينها!"

STYLE :
- Sois concis (8 à 12 lignes maximum), précis, chaleureux et structuré (listes à puces courtes, gras `**...**`, chiffres exacts en TND / W / Wh issus du catalogue réel ci-dessus, emojis sobres 📦☀️⚡🔋).
- N'utilise JAMAIS de formules LaTeX (`$...$` ou `$$...$$`) : écris les formules en texte lisible simple.
- N'invente jamais un pack qui n'existe pas dans le catalogue ci-dessus.
SYS;
    }

    /**
     * Query active Solar Packs from DB (gracefully degrading if DB is unavailable).
     */
    protected function buildLivePacksCatalogContext(?EnergyKit $currentKit = null): string
    {
        $lines = [];

        if ($currentKit) {
            $currentKit->loadMissing(['equipment']);
            $lines[] = '=== PACK ACTUELLEMENT CONSULTÉ PAR L\'UTILISATEUR ===';
            $lines[] = $this->formatPackSummary($currentKit, true);
            $lines[] = '';
        }

        try {
            $activeKits = EnergyKit::with(['equipment'])
                ->where('is_active', true)
                ->latest()
                ->take(15)
                ->get();

            $lines[] = '=== CATALOGUE EN TEMPS RÉEL DES PACKS SOLAIRES ACTIFS (' . $activeKits->count() . ') ===';

            if ($activeKits->isEmpty()) {
                $lines[] = '- Aucun pack solaire actif dans le catalogue pour le moment.';
            } else {
                foreach ($activeKits as $kit) {
                    $lines[] = $this->formatPackSummary($kit, false);
                }
            }
        } catch (\Throwable $e) {
            $lines[] = '=== CATALOGUE EN TEMPS RÉEL ===';
            $lines[] = '- Données du catalogue temporairement indisponibles ; réponds selon les règles fonctionnelles du module Packs Solaires.';
        }

        return implode("\n", $lines);
    }

    /**
     * Format a single EnergyKit model into a concise, LLM-friendly summary line.
     */
    protected function formatPackSummary(EnergyKit $kit, bool $detailed = false): string
    {
        $items = [];
        foreach ($kit->equipment as $eq) {
            $qty = $eq->pivot->quantity ?? 1;
            $opt = ! empty($eq->pivot->options) ? " ({$eq->pivot->options})" : '';
            $specs = array_filter([
                $eq->power_watts ? "{$eq->power_watts}W" : null,
                $eq->capacity_wh ? "{$eq->capacity_wh}Wh" : null,
            ]);
            $specStr = ! empty($specs) ? ' [' . implode(', ', $specs) . ']' : '';
            $items[] = "{$qty}x {$eq->name}{$specStr}{$opt}";
        }

        $equipStr = ! empty($items) ? implode(' + ', $items) : 'Aucun équipement lié';
        $availStr = $kit->is_all_available ? 'Disponible immédiatement' : 'Partiellement réservé / en configuration';
        $discount = rtrim(rtrim(number_format((float) $kit->discount_percentage, 1), '0'), '.');
        $rawRate = number_format($kit->raw_daily_rate, 2);
        $packRate = number_format($kit->discounted_daily_rate, 2);
        $savings = number_format($kit->daily_savings, 2);
        $deposit = number_format($kit->total_deposit, 2);
        $url = "/energy-kits/{$kit->slug}";

        $summary = "- **{$kit->title}** (Lien: `[{$kit->title}]({$url})` | Usage: `{$kit->target_use}` | Puissance: {$kit->total_power_watts}W | Capacité: {$kit->total_capacity_wh}Wh | Prix cumulé: {$rawRate} TND/j | Remise: -{$discount}% | **Tarif Pack: {$packRate} TND/j** (économie {$savings} TND/j) | Caution: {$deposit} TND | Statut: {$availStr} | Équipements inclus: {$equipStr})";

        if ($detailed && ! empty($kit->description)) {
            $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags((string) $kit->description)));
            $summary .= "\n  Description du pack : {$cleanDesc}";
        }

        return $summary;
    }
}
