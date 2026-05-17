<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DeepSeekTranslationService
{
    /**
     * Translate a given key to the specified locale using DeepSeek API and save it.
     */
    public function translateAndSave(string $key, string $locale): string
    {
        $apiKey = config('services.deepseek.api_key') ?? env('DEEPSEEK_API_KEY');
        $baseUrl = config('services.deepseek.base_url') ?? env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com/v1/chat/completions');

        if (!$apiKey) {
            Log::warning('DeepSeek API Key is missing. Returning original key.', ['key' => $key, 'locale' => $locale]);
            return $key;
        }

        try {
            $response = Http::withToken($apiKey)->post($baseUrl, [
                'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
                'messages' => [
                    ['role' => 'system', 'content' => "You are a professional translator. Translate the following text to the locale '{$locale}'. Return ONLY the translated text without quotes or explanations."],
                    ['role' => 'user', 'content' => $key]
                ]
            ]);

            if ($response->successful()) {
                $translated = $response->json('choices.0.message.content');
                $translated = trim($translated);

                $this->saveTranslation($key, $translated, $locale);

                return $translated;
            }

            Log::error('DeepSeek translation API failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('DeepSeek translation exception: ' . $e->getMessage());
        }

        return $key;
    }

    /**
     * Save the translation to the corresponding JSON file in lang directory.
     */
    protected function saveTranslation(string $key, string $translated, string $locale)
    {
        $langDir = base_path('lang');
        if (!File::exists($langDir)) {
            File::ensureDirectoryExists($langDir);
        }

        $path = $langDir . '/' . $locale . '.json';
        $translations = [];

        if (File::exists($path)) {
            $content = File::get($path);
            $translations = json_decode($content, true) ?? [];
        }

        $translations[$key] = $translated;

        File::put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
