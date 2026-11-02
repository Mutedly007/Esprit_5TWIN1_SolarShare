<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiRenterChatService
{
    protected string $apiKey;
    protected string $defaultModel;
    protected array $fallbackModels = [
        'gemini-3.5-flash',
        'gemini-3.7-flash',
        'gemini-3.8-flash',
        'gemini-flash-latest',
    ];

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key');
        $this->defaultModel = (string) config('services.gemini.model', 'gemini-3.5-flash');
    }

    /**
     * Send chat messages to Gemini and get the assistant's reply.
     *
     * @param array $messages Array of ['role' => 'user'|'model', 'text' => string]
     * @return string
     * @throws \Exception
     */
    public function reply(array $messages): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception("La clé API Gemini n'est pas configurée.");
        }

        $systemInstruction = $this->getSystemInstruction();

        // Convert messages to Gemini API format
        $contents = [];
        foreach ($messages as $msg) {
            $role = ($msg['role'] ?? 'user') === 'assistant' || ($msg['role'] ?? 'user') === 'model'
                ? 'model'
                : 'user';

            $contents[] = [
                'role'  => $role,
                'parts' => [
                    ['text' => (string) ($msg['text'] ?? $msg['content'] ?? '')],
                ],
            ];
        }

        // Build CA cert options for SSL verification
        $caPath = storage_path('certs/cacert.pem');
        $verify = file_exists($caPath) ? $caPath : true;

        $client = new Client([
            'verify'  => $verify,
            'timeout' => 25,
        ]);

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature'     => 0.7,
                'topP'            => 0.95,
                'maxOutputTokens' => 1024,
            ],
        ];

        // Try with default model, fallback to others if 503 or transient failure
        $candidateModels = array_unique(array_merge([$this->defaultModel], $this->fallbackModels));
        $lastException = null;

        foreach ($candidateModels as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $this->apiKey;

            try {
                $response = $client->post($url, [
                    'json' => $payload,
                ]);

                $data = json_decode((string) $response->getBody(), true);

                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($replyText) {
                    return trim($replyText);
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // If 404 (model deprecated/unavailable), try next model
                if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                    $lastException = $e;
                    continue;
                }
                throw $e;
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                // If 503 (high demand), try next model
                $lastException = $e;
                continue;
            } catch (\Throwable $e) {
                $lastException = $e;
                Log::warning("Gemini model {$model} error: " . $e->getMessage());
            }
        }

        if ($lastException) {
            throw $lastException;
        }

        return "Désolé, je rencontre une indisponibilité temporaire. Veuillez reformuler votre question.";
    }

    /**
     * Get the strict system instructions restricting the chatbot to the borrower/renter space.
     */
    protected function getSystemInstruction(): string
    {
        return <<<SYS
Tu es "SolarBot", l'assistant d'intelligence artificielle officiel de SolarShare, exclusivement et strictement dédié aux fonctionnalités de la SESSION EMPRUNTEUR (Renter / Borrower).

Ton objectif est d'aider, informer et guider les utilisateurs sur TOUT ce qui concerne l'emprunt et la location de matériel solaire et écologique sur SolarShare :

1. Marketplace & Équipements (/marketplace) :
- Consulter le catalogue des panneaux solaires pliables, batteries nomades de stockage (EcoFlow, Jackery, Bluetti), et micro-éoliennes.
- Filtrer par catégorie, caractéristiques techniques, puissance et tarif journalier.
- Voir la fiche complète d'un équipement.

2. Cycle de Location & Réservation (/rentals) :
- Créer une demande de location (/rentals/create) : choix des dates de début et de fin.
- Règles de calcul du prix total et gestion de la caution de garantie.
- Suivi des statuts d'une location :
  * En attente (pending) : attente de confirmation du prêteur.
  * Confirmée (confirmed) : prêt validé, prêt pour la remise.
  * En cours (ongoing) : matériel actuellement entre les mains de l'emprunteur après état des lieux d'entrée.
  * Terminée (completed) : matériel restitué après état des lieux de sortie.
  * Annulée (cancelled) : annulation autorisée selon les conditions de réservation.

3. Packs Solaires Clé-en-main (/energy-kits) :
- Consulter les packs combinés prêts à l'emploi (Panneau + Batterie + Accessoires) pour le vanlife, camping ou chantier.
- Comment réserver un pack solaire clé-en-main complet.

4. Points Relais & Hubs de Collecte (/hubs) :
- Trouver un point relais de quartier pour retirer ou déposer le matériel.
- Consulter les plages et créneaux horaires d'ouverture (operating slots).

5. Constats Numériques & États des Lieux (/inspections) :
- État des lieux d'entrée (Check-in) : relever l'état de charge de la batterie (%), l'état physique, ajouter des photos et signer.
- État des lieux de sortie (Check-out) : vérifier la batterie et l'intégrité avant restitution pour libérer la caution.

RÈGLE ABSOLUE DE PÉRIMÈTRE (GUARDRAIL INFRANCHISSABLE) :
- Tu ne dois répondre QU'AUX questions relatives aux fonctionnalités d'un emprunteur / locataire sur SolarShare (recherche de matériel, réservation, caution, packs solaires, hubs relais, états des lieux d'entrée et sortie).
- Si l'utilisateur te pose une question hors de ce cadre (par exemple : gestion des annonces par les prêteurs, dashboard administrateur, questions générales, code, politique, météo extérieure, recettes, etc.), TU DOIS POLIMENT ET FERMEMENT REFUSER de répondre au sujet hors-périmètre et rediriger vers ton rôle d'assistant emprunteur :
  "Je suis SolarBot, votre assistant dédié exclusivement à la session Emprunteur de SolarShare. Je ne peux vous assister que sur la recherche d'équipements, la création de réservations, les packs solaires, les points relais et les états des lieux (check-in / check-out). Comment puis-je vous aider pour votre prochaine location ?"

Ton ton doit être chaleureux, concis, clair, professionnel et encourageant. Utilise le vouvoiement et des emojis solaires (☀️, ⚡, 🔋, 📦, 📝).
SYS;
    }
}
