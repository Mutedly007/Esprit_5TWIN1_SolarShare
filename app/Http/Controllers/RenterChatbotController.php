<?php

namespace App\Http\Controllers;

use App\Services\GeminiRenterChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RenterChatbotController extends Controller
{
    protected GeminiRenterChatService $chatService;

    public function __construct(GeminiRenterChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Handle incoming chatbot message for borrower / renter assistant.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message'   => ['required', 'string', 'max:1500'],
            'history'   => ['nullable', 'array', 'max:20'],
            'history.*.role' => ['required', 'string', 'in:user,model,assistant'],
            'history.*.text' => ['required', 'string', 'max:2000'],
        ]);

        $messages = $validated['history'] ?? [];
        $messages[] = [
            'role' => 'user',
            'text' => $validated['message'],
        ];

        try {
            $reply = $this->chatService->reply($messages);
            $navigation = $this->detectNavigation($validated['message'], $reply);

            return response()->json([
                'status'     => 'success',
                'reply'      => $reply,
                'navigation' => $navigation,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini Renter Chatbot error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Une erreur est survenue lors de la communication avec SolarBot. Veuillez réessayer dans quelques instants.',
            ], 500);
        }
    }

    /**
     * Detect relevant page navigation from the conversation for the borrower session.
     */
    protected function detectNavigation(string $query, string $reply): ?array
    {
        $routes = [
            '/rentals/create' => [
                'label'    => 'Créer une réservation',
                'keywords' => ['nouvelle location', 'creer une location', 'reserver', 'nouvelle reservation', 'louer un materiel', '/rentals/create'],
            ],
            '/rentals' => [
                'label'    => 'Mes locations & réservations',
                'keywords' => ['mes locations', 'mes reservations', 'suivi location', 'voir location', 'contrat', '/rentals'],
            ],
            '/marketplace' => [
                'label'    => 'Marketplace des Équipements',
                'keywords' => ['marketplace', 'catalogue', 'trouver un panneau', 'chercher equipement', 'batterie', 'materiel', '/marketplace'],
            ],
            '/energy-kits' => [
                'label'    => 'Packs Solaires Clé-en-main',
                'keywords' => ['pack solaire', 'packs solaires', 'kit solaire', 'kits solaires', 'pack', '/energy-kits'],
            ],
            '/hubs' => [
                'label'    => 'Points Relais & Hubs',
                'keywords' => ['point relais', 'points relais', 'hub', 'hubs', 'retrait', 'permanence', '/hubs'],
            ],
            '/profile' => [
                'label'    => 'Mon Profil',
                'keywords' => ['profil', 'mon compte', 'parametres compte', 'changer mot de passe', '/profile'],
            ],
            '/dashboard' => [
                'label'    => 'Tableau de bord',
                'keywords' => ['dashboard', 'tableau de bord', '/dashboard'],
            ],
        ];

        $combined = mb_strtolower($query . ' ' . $reply);

        // Check explicit URLs in reply first
        foreach ($routes as $url => $info) {
            if (str_contains($reply, $url)) {
                return [
                    'url'   => $url,
                    'label' => $info['label'],
                ];
            }
        }

        // Check keywords
        foreach ($routes as $url => $info) {
            foreach ($info['keywords'] as $keyword) {
                if (str_contains($combined, $keyword)) {
                    return [
                        'url'   => $url,
                        'label' => $info['label'],
                    ];
                }
            }
        }

        return null;
    }
}

