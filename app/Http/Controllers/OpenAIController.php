<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Services\OpenAIService;
use Illuminate\Support\Facades\Log;


class OpenAIController extends Controller
{
    
     protected $openaiService;

    public function __construct(OpenAIService $openaiService)
    {
        $this->openaiService = $openaiService;
        
    }

    // public function generateText(Request $request)
    // {
    //     $prompt = $request->input('prompt');
    //     $response = $this->openaiService->generateText($prompt);

    //     return response()->json(['generatedText' => $response['choices'][0]['text']]);
    // }
    public function generateText(Request $request)
{
   // dd(env('OPENAI_API_KEY'));
    $prompt = $request->input('prompt');
   $apiKey = env('OPENAI_API_KEY');

    if (!$apiKey) {
        // Handle the case where the API key is missing
        return response()->json(['error' => 'OpenAI API key is missing'], 401);
    }
  try {
    $result = $this->openaiService->generateText($prompt, $apiKey);

        //$result = $this->openaiService->generateText($prompt);
   \Log::info('OpenAI API Response:', $result);

        return response()->json($result);
         } catch (\Illuminate\Http\Client\RequestException $e) {
        // Handle the case where the OpenAI API request fails
        return response()->json(['error' => 'OpenAI API request failed'], 500);
    }
}
    

}