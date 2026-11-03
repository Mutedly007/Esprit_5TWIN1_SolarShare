<?php

namespace App\Http\Controllers;

use App\Models\EnergyKit;
use App\Services\GeminiEnergyKitChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnergyKitChatbotController extends Controller
{
    public function __construct(
        protected GeminiEnergyKitChatService $chatService
    ) {}

    /**
     * Handle incoming chat messages strictly dedicated to the Solar Packs (Energy Kits) module.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1500'],
            'history' => ['nullable', 'array', 'max:20'],
            'history.*.role' => ['required', 'string', 'in:user,model,assistant'],
            'history.*.text' => ['required', 'string', 'max:2500'],
            'kit_id' => ['nullable', 'integer'],
        ]);

        $currentKit = null;
        if (! empty($validated['kit_id'])) {
            try {
                $kit = EnergyKit::with(['equipment'])->find($validated['kit_id']);
                if ($kit && ($kit->is_active || (auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $kit->user_id)))) {
                    $currentKit = $kit;
                }
            } catch (\Throwable $e) {
                // Ignore DB lookup errors cleanly
            }
        }

        $messages = $validated['history'] ?? [];
        $messages[] = [
            'role' => 'user',
            'text' => $validated['message'],
        ];

        $locale = app()->getLocale();

        try {
            $reply = $this->chatService->reply($messages, $currentKit, $locale);
            $navigation = $this->detectPackNavigation($reply, $currentKit);

            return response()->json([
                'status' => 'success',
                'reply' => $reply,
                'navigation' => $navigation,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini EnergyKit Chatbot error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => __('messages.pack_ai.error_generic'),
            ], 500);
        }
    }

    /**
     * Detect navigation links exclusively within the Solar Packs module (/energy-kits*, /packs/create).
     */
    protected function detectPackNavigation(string $reply, ?EnergyKit $currentKit = null): ?array
    {
        // 1. Check if reply links to a specific solar pack: /energy-kits/{slug}
        if (preg_match('#/energy-kits/([a-zA-Z0-9\-_]+)#', $reply, $matches)) {
            $slug = $matches[1];
            if ($slug !== 'chat' && (! $currentKit || $currentKit->slug !== $slug)) {
                $label = $slug;
                try {
                    $matchedKit = EnergyKit::where('slug', $slug)->first();
                    if ($matchedKit) {
                        $label = $matchedKit->title;
                    }
                } catch (\Throwable $e) {
                    // Fallback to slug
                }

                return [
                    'url' => url("/energy-kits/{$slug}"),
                    'label' => $label,
                ];
            }
        }

        // 2. Check if reply points to pack creation (/packs/create) for eligible lenders/admins
        if (str_contains($reply, '/packs/create') && auth()->check() && auth()->user()->isLender()) {
            return [
                'url' => route('energy-kits.create'),
                'label' => __('messages.pack_ai.nav_create_pack'),
            ];
        }

        // 3. Check if reply points to /energy-kits while viewing a single pack
        if ($currentKit && preg_match('#\(/energy-kits\)#', $reply)) {
            return [
                'url' => route('energy-kits.index'),
                'label' => __('messages.pack_ai.nav_all_packs'),
            ];
        }

        return null;
    }
}

