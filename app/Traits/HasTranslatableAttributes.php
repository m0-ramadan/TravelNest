<?php

namespace App\Traits;

trait HasTranslatableAttributes
{
    protected function translatedValue(string $field, ?string $locale = null, ?string $fallback = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $fallback = $fallback ?: $this->defaultTranslationLocale();

        $value = $this->getAttribute($field);

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (!is_array($value)) {
            return (string) ($value ?? '');
        }

        return (string) (
            $value[$locale]
            ?? $value[$fallback]
            ?? (isset($value['en']) ? $value['en'] : null)
            ?? (isset($value['ar']) ? $value['ar'] : null)
            ?? (count($value) ? reset($value) : '')
        );
    }

    public function getTranslation(string $field, ?string $locale = null, ?string $fallback = null): string
    {
        return $this->translatedValue($field, $locale, $fallback);
    }

    public function getTranslations(string $field): array
    {
        $value = $this->getAttribute($field);

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    public function setTranslation(string $field, string $locale, mixed $value): static
    {
        $translations = $this->getTranslations($field);
        $translations[$locale] = $value;
        $this->setAttribute($field, $translations);

        return $this;
    }

    protected function defaultTranslationLocale(): string
    {
        if (class_exists(\App\Models\Language::class)) {
            $default = \App\Models\Language::query()
                ->where('is_default', true)
                ->value('code');

            if ($default) {
                return $default;
            }
        }

        return config('app.fallback_locale', 'en');
    }
}
