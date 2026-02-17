<?php

namespace App\Infrastructure\AI;

use App\Domain\Services\AIService;
use App\Domain\ValueObjects\MessageSummary;
use App\Infrastructure\Exceptions\ConfigurationException;
use App\Domain\Exceptions\AIProcessingException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeminiAIService implements AIService
{
    public function generateSummary(string $content): MessageSummary
    {
        $apiKey = config('services.gemini.api_key');
        $baseUrl = config('services.gemini.base_url');

        if (!$apiKey) {
            return new MessageSummary(Str::limit("IA Mock Summary: " . $content, 100));
        }

        if (!$baseUrl) {
            throw ConfigurationException::missingConfig('services.gemini.base_url');
        }

        $url = "{$baseUrl}?key={$apiKey}";

        try {
            $response = Http::timeout(10)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Summarize the following text in maximum 100 characters: " . $content]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                throw AIProcessingException::failedToGenerateSummary($response->body());
            }

            $data = $response->json();
            $summary = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if (empty($summary)) {
                throw AIProcessingException::failedToGenerateSummary("Empty response from AI");
            }

            return new MessageSummary(Str::limit(trim($summary), 100));
        } catch (\Exception $e) {
            if ($e instanceof AIProcessingException) {
                throw $e;
            }
            throw AIProcessingException::failedToGenerateSummary($e->getMessage());
        }
    }
}
