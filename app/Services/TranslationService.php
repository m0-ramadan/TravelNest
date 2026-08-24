<?php

namespace App\Services;

use App\Models\Language;
use App\Services\Translation\AiTranslationService;

class TranslationService
{
    public function __construct(
        protected AiTranslationService $aiTranslationService
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
            ->map(fn($c) => strtolower(trim((string) $c)))
            ->filter()
            ->values()
            ->all();

        $languages = array_values(array_unique(array_merge($languages, ['en', 'ar'])));

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

    protected function translateUsingAi(string $text, array $languages): ?array
    {
        $sourceLang = $this->detectSourceLanguage($text);
        $result = [];

        foreach ($languages as $lang) {
            $langCode = strtolower(trim((string) $lang));
            if ($langCode === $sourceLang) {
                $result[$langCode] = $text;
            } else {
                $result[$langCode] = $this->aiTranslationService->translateString($text, $langCode, $sourceLang);
            }
        }

        return $result;
    }

    protected function detectSourceLanguage(string $text): string
    {
        if (preg_match('/\p{Arabic}/u', $text)) {
            return 'ar';
        }

        return 'en';
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
            if (!array_key_exists($lang, $clean) || $clean[$lang] === '') {
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
}
