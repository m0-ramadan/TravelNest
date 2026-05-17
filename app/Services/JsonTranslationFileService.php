<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class JsonTranslationFileService
{
    public function __construct(
        protected DeepSeekService $deepSeekService
    ) {}

    public function ensureLocaleFile(string $locale, bool $force = false): void
    {
        $locale = trim($locale);

        if ($locale === '') {
            return;
        }

        File::ensureDirectoryExists(lang_path());

        $baseTranslations = $this->baseTranslations();

        if (empty($baseTranslations)) {
            return;
        }

        $path = lang_path($locale . '.json');
        $existing = $this->readJsonFile($path);
        $translations = $existing;
        $missing = [];

        foreach ($baseTranslations as $key => $value) {
            $currentValue = $existing[$key] ?? null;

            if (!$force && is_string($currentValue) && trim($currentValue) !== '') {
                continue;
            }

            if ($locale === 'en') {
                $translations[$key] = $value;
                continue;
            }

            $missing[$key] = $value;
        }

        if (!empty($missing)) {
            $translated = $this->translateMap($missing, $locale);

            foreach ($missing as $key => $fallback) {
                $translations[$key] = $translated[$key] ?? $fallback;
            }
        }

        foreach ($existing as $key => $value) {
            if (!array_key_exists($key, $translations)) {
                $translations[$key] = $value;
            }
        }

        ksort($translations, SORT_NATURAL | SORT_FLAG_CASE);

        File::put(
            $path,
            json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL
        );
    }

    public function renameLocaleFile(string $oldLocale, string $newLocale): void
    {
        $oldLocale = trim($oldLocale);
        $newLocale = trim($newLocale);

        if ($oldLocale === '' || $newLocale === '' || $oldLocale === $newLocale) {
            return;
        }

        $oldPath = lang_path($oldLocale . '.json');
        $newPath = lang_path($newLocale . '.json');

        if (File::exists($oldPath) && !File::exists($newPath)) {
            File::move($oldPath, $newPath);
        }
    }

    public function removeLocaleFile(string $locale): void
    {
        $locale = trim($locale);

        if ($locale === '') {
            return;
        }

        $path = lang_path($locale . '.json');

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    protected function baseTranslations(): array
    {
        $englishPath = lang_path('en.json');
        $english = $this->readJsonFile($englishPath);

        if (!empty($english)) {
            foreach ($english as $key => $value) {
                $english[$key] = is_string($value) && trim($value) !== '' ? trim($value) : $key;
            }

            return $english;
        }

        $translations = [];

        foreach (File::files(lang_path()) as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            foreach ($this->readJsonFile($file->getPathname()) as $key => $value) {
                if (!isset($translations[$key])) {
                    $translations[$key] = is_string($value) && trim($value) !== '' ? trim($value) : $key;
                }
            }
        }

        ksort($translations, SORT_NATURAL | SORT_FLAG_CASE);

        return $translations;
    }

    protected function translateMap(array $items, string $locale): array
    {
        $translated = [];

        foreach (array_chunk($items, 25, true) as $chunk) {
            $translated += $this->translateChunk($chunk, $locale);
        }

        return $translated;
    }

    protected function translateChunk(array $items, string $locale): array
    {
        if (empty($items)) {
            return [];
        }

        $json = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
Translate all JSON values to locale "{$locale}".

Rules:
- Keep the JSON keys exactly unchanged.
- Translate values only.
- Preserve placeholders, numbers, brand names, and symbols where appropriate.
- Return ONLY valid JSON in this exact shape:
{
  "translations": {
    "Original key": "Translated value"
  }
}

Input JSON:
{$json}
PROMPT;

        $response = $this->deepSeekService->askJson(
            prompt: $prompt,
            systemPrompt: 'You are a professional UI localization assistant. Return valid JSON only.',
            temperature: 0.1,
            maxTokens: 4000
        );

        $translations = $response['translations'] ?? null;

        if (!is_array($translations)) {
            Log::warning('JSON language translation fallback used.', ['locale' => $locale, 'keys' => array_keys($items)]);
            return $items;
        }

        $result = [];

        foreach ($items as $key => $fallback) {
            $value = $translations[$key] ?? $fallback;
            $result[$key] = is_string($value) && trim($value) !== '' ? trim($value) : $fallback;
        }

        return $result;
    }

    protected function readJsonFile(string $path): array
    {
        if (!File::exists($path)) {
            return [];
        }

        $decoded = json_decode(File::get($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}
