<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class GeminiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // DigitalOcean AI Agent endpoint and key
        $url = 'https://bzksfgtnbzdoc3zsw2e6alvm.agents.do-ai.run/api/v1/chat/completions';
        $apiKey = 'EH4nhHy9-LKzIQ1tBNCimtDXPkpAj0JQ';
        $userMessage = $request->input('message');

        // If the API expects an OpenAI-style payload:
        $payload = [
            'messages' => [
                ['role' => 'user', 'content' => $userMessage]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                \Log::error('DO AI Agent API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $errorMsg = $response->json('error.message') ?? 'Failed to contact DO AI Agent API';
                return response()->json(['error' => $errorMsg], 500);
            }

            $data = $response->json();
            // Try to extract the reply from OpenAI-style response
            $reply = $data['choices'][0]['message']['content'] ?? $data['reply'] ?? $data['message'] ?? $data['response'] ?? 'No response from AI.';
            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            \Log::error('DO AI Agent API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
} 