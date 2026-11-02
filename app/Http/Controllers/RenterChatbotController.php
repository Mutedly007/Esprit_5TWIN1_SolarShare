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

            return response()->json([
                'status' => 'success',
                'reply'  => $reply,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini Renter Chatbot error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Une erreur est survenue lors de la communication avec SolarBot. Veuillez réessayer dans quelques instants.',
            ], 500);
        }
    }
}
