<?php
// app/Services/OpenAIService.php

namespace App\Services;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;


class OpenAIService
{
     protected $apiKey;

   public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

   public function generateText($prompt)
{
   try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                'prompt' => $prompt,
                // Other parameters...
            ]);

            // Log the request and response for debugging
            Log::info('OpenAI API Request:', [
                'url' => 'https://api.openai.com/v1/engines/text-davinci-003/completions',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . substr($this->apiKey, 0, 5) . '...',
                ],
                'data' => [
                    'prompt' => $prompt,
                    // Other parameters...
                ],
            ]);
            Log::info('OpenAI API Response:', $response->json());

            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $errorResponse = $e->response->json();
            
            if ($errorResponse['code'] === 'insufficient_quota') {
                // Handle insufficient quota error
                Log::error('OpenAI API Error: Insufficient Quota', $errorResponse);
                return ['error' => 'Insufficient Quota'];
            }

            // Handle other request exceptions
            Log::error('OpenAI API Request Failed:', [
                'url' => 'https://api.openai.com/v1/engines/text-davinci-003/completions',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . substr($this->apiKey, 0, 5) . '...',
                ],
                'data' => [
                    'prompt' => $prompt,
                    // Other parameters...
                ],
            ]);

            return ['error' => 'Too Many Requests'];
        }
}

}