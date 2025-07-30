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

        $payload = [
            'messages' => [
                ['role' => 'user', 'content' => $userMessage]
            ],
            'stream' => true
        ];

        try {
            $client = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'text/event-stream',
            ]);

            return response()->stream(function () use ($client, $url, $payload) {
                $response = $client->withOptions(['stream' => true])->post($url, $payload);
                $body = $response->getBody();
                while (!$body->eof()) {
                    $chunk = $body->read(4096);
                    if ($chunk) {
                        // Forward the chunk to the frontend as-is
                        echo $chunk;
                        ob_flush();
                        flush();
                    }
                }
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ]);
        } catch (\Exception $e) {
            \Log::error('DO AI Agent API stream exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
} 