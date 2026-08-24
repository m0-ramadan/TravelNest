<?php

namespace App\Services\Translation\Providers;

use App\Contracts\TranslationProviderInterface;
use App\Services\Translation\DTOs\TranslationOptions;
use App\Services\Translation\DTOs\TranslationResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiTranslationProvider implements TranslationProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected int $timeout;
    protected string $endpoint;

    public function __construct()
    {
        $this->apiKey = (string) config('translation_ai.google.api_key');
        $this->model = (string) config('translation_ai.google.model', 'gemini-2.5-flash');
        $this->timeout = (int) config('translation_ai.google.timeout', 30);
        $this->endpoint = (string) config('translation_ai.google.endpoint', 'https://generativelanguage.googleapis.com/v1beta/models');
    }

    public function getName(): string
    {
        return 'gemini';
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function translate(
        string $text,
        string $sourceLanguage,
        string $targetLanguage,
        ?TranslationOptions $options = null
    ): TranslationResult {
        $startTime = microtime(true);
        $options = $options ?? new TranslationOptions();

        if (empty(trim($this->apiKey))) {
            return TranslationResult::failure(
                $this->getName(),
                $this->getModel(),
                'Google AI API key is missing in environment config.'
            );
        }

        $prompt = $this->buildPrompt($text, $sourceLanguage, $targetLanguage, $options->structuredType);
        $maxOutputTokens = $options->maxOutputTokens ?: $this->estimateOutputTokenLimit($text);

        $url = rtrim($this->endpoint, '/') . "/{$this->model}:generateContent";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'thinkingConfig' => [
                    'thinkingBudget' => 0, // Mandatory 0 to disable thinking tokens
                ],
                'maxOutputTokens' => $maxOutputTokens,
                'temperature' => $options->temperature,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => $this->apiKey,
            ])
            ->timeout($this->timeout)
            ->post($url, $payload);

            $durationMs = (int) (round(microtime(true) - $startTime, 3) * 1000);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();
                Log::warning("Gemini translation HTTP failure ({$status}): {$body}");

                return TranslationResult::failure(
                    $this->getName(),
                    $this->getModel(),
                    "HTTP {$status}: " . substr($body, 0, 200),
                    $durationMs
                );
            }

            $data = $response->json();
            $translatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $finishReason = $data['candidates'][0]['finishReason'] ?? null;

            if ($finishReason === 'MAX_TOKENS') {
                Log::warning("Gemini translation truncated due to max tokens limit.");
            }

            $usage = $data['usageMetadata'] ?? [];
            $promptTokens = $usage['promptTokenCount'] ?? null;
            $outputTokens = $usage['candidatesTokenCount'] ?? null;
            $totalTokens = $usage['totalTokenCount'] ?? null;

            return new TranslationResult(
                translatedText: trim($translatedText),
                provider: $this->getName(),
                model: $this->getModel(),
                isSuccess: !empty(trim($translatedText)),
                errorMessage: empty(trim($translatedText)) ? 'Empty response from Gemini' : null,
                promptTokens: $promptTokens,
                outputTokens: $outputTokens,
                totalTokens: $totalTokens,
                durationMs: $durationMs
            );
        } catch (\Throwable $e) {
            $durationMs = (int) (round(microtime(true) - $startTime, 3) * 1000);
            Log::error("Gemini Translation Provider Exception: " . $e->getMessage());

            return TranslationResult::failure(
                $this->getName(),
                $this->getModel(),
                $e->getMessage(),
                $durationMs
            );
        }
    }

    public function estimateOutputTokenLimit(string $text): int
    {
        $charLen = mb_strlen($text);
        $approxSourceTokens = (int) ceil($charLen / 4.0);
        $limit = (int) ceil($approxSourceTokens * 1.6) + 128;

        return max(256, min($limit, 4096));
    }

    protected function buildPrompt(string $text, string $sourceLang, string $targetLang, string $structuredType): string
    {
        $srcName = strtoupper($sourceLang);
        $tgtName = strtoupper($targetLang);

        if ($structuredType === 'faq_json') {
            return "Translate JSON string values from {$srcName} to {$tgtName}.\n"
                 . "Keep keys and JSON structure unchanged. Preserve placeholders ({var}, :val) and numbers.\n"
                 . "Return valid JSON only without markdown formatting.\n"
                 . "JSON:\n" . $text;
        }

        if ($structuredType === 'json_array') {
            return "Translate string values in JSON array from {$srcName} to {$tgtName}.\n"
                 . "Keep array order and structure. Return valid JSON array only.\n"
                 . "JSON:\n" . $text;
        }

        if ($structuredType === 'html') {
            return "Translate text nodes from {$srcName} to {$tgtName}.\n"
                 . "Preserve all HTML tags, attributes, placeholders and structure. Return translated HTML only.\n"
                 . "TEXT:\n" . $text;
        }

        return "Translate from {$srcName} to {$tgtName}.\n"
             . "Preserve meaning, names, numbers, HTML tags and placeholders. Return only the translation.\n"
             . "TEXT:\n" . $text;
    }
}
