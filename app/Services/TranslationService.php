<?php

namespace App\Services;

use App\Models\Language;

class TranslationService
{
    public function __construct(
        protected DeepSeekService $deepSeekService
    ) {}

    public function translateTextToAllLanguages(string|array|null $value): array
    {
        if (is_array($value)) {
            return $this->normalizeTranslations($value);
        }

        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        if ($this->looksLikeJson($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $this->normalizeTranslations($decoded);
            }
        }

        $languages = Language::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('code')
            ->filter()
            ->values()
            ->all();

        if (empty($languages)) {
            return [
                'en' => $value,
                'ar' => $value,
            ];
        }

        $translated = $this->translateUsingAi($value, $languages);

        if (!$translated) {
            return $this->fallbackTranslations($value, $languages);
        }

        return $this->normalizeTranslations($translated, $value, $languages);
    }

    public function translateFields(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data) || blank($data[$field])) {
                continue;
            }

            $data[$field] = $this->translateTextToAllLanguages($data[$field]);
        }

        return $data;
    }

    protected function translateUsingAi(string $text, array $languages): ?array
    {
        $languageCodes = implode(', ', $languages);

        $prompt = <<<PROMPT
Detect the source language of the following text, then translate it to all these language codes: {$languageCodes}.

Text:
{$text}

Return ONLY valid JSON in this exact format:
{
  "translations": {
    "en": "translated text",
    "ar": "translated text"
  }
}
PROMPT;

        $result = $this->deepSeekService->askJson(
            prompt: $prompt,
            systemPrompt: 'You are a multilingual translation assistant. Detect the language and return only valid JSON.',
            temperature: 0.1,
            maxTokens: 2000
        );

        if (!is_array($result)) {
            return null;
        }

        return $result['translations'] ?? null;
    }

    protected function normalizeTranslations(array $translations, ?string $original = null, array $languages = []): array
    {
        $clean = [];

        foreach ($translations as $lang => $value) {
            if (!is_string($lang) || trim($lang) === '') {
                continue;
            }

            $clean[$lang] = is_string($value) ? trim($value) : '';
        }

        foreach ($languages as $lang) {
            if (!array_key_exists($lang, $clean)) {
                $clean[$lang] = $original ?? '';
            }
        }

        return $clean;
    }

    protected function fallbackTranslations(string $text, array $languages): array
    {
        $result = [];

        foreach ($languages as $language) {
            $result[$language] = $text;
        }

        return $result;
    }

    protected function looksLikeJson(string $value): bool
    {
        $value = trim($value);

        return str_starts_with($value, '{') && str_ends_with($value, '}');
    }




    public function translateTextToLanguages(string $text, array $languageCodes): array
    {
        $text = trim($text);

        if ($text === '') {
            return [];
        }

        $translated = $this->translateUsingAi($text, $languageCodes);

        if (!$translated) {
            return $this->fallbackTranslations($text, $languageCodes);
        }

        return $this->normalizeTranslations($translated, $text, $languageCodes);
    }
}
