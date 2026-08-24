<?php

namespace App\Services\Translation;

class TranslationValidator
{
    /**
     * Clean up markdown code blocks if the model returned them.
     */
    public function cleanMarkdownCodeBlocks(string $text): string
    {
        $cleaned = trim($text);
        if (preg_match('/^```(?:json|html)?\s*(.*?)\s*```$/s', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
        }
        return $cleaned;
    }

    /**
     * Validate that translated content is valid and actually translated.
     */
    public function validate(
        string $sourceText,
        string $translatedText,
        string $sourceLang,
        string $targetLang,
        string $structuredType = 'text'
    ): bool {
        $translatedText = $this->cleanMarkdownCodeBlocks($translatedText);

        if (empty(trim($translatedText))) {
            return false;
        }

        // 1. Placeholder Preservation Check
        if (!$this->validatePlaceholders($sourceText, $translatedText)) {
            return false;
        }

        // 2. Structured Content Validation
        if ($structuredType === 'json_array') {
            if (!$this->validateJsonArray($sourceText, $translatedText)) {
                return false;
            }
        } elseif ($structuredType === 'faq_json') {
            if (!$this->validateFaqJson($sourceText, $translatedText)) {
                return false;
            }
        }

        // 3. Language Script / Untranslated Detection
        if (!$this->isProperlyTranslated($sourceText, $translatedText, $sourceLang, $targetLang)) {
            return false;
        }

        return true;
    }

    /**
     * Verify all placeholders in source exist in translation.
     */
    public function validatePlaceholders(string $source, string $translated): bool
    {
        // Matches {var}, {{var}}, :var, %var%
        preg_match_all('/(?:\{{1,2}[\w_]+\}{1,2}|:\w+|%\w+%)/u', $source, $sourceMatches);
        $placeholders = array_unique($sourceMatches[0] ?? []);

        foreach ($placeholders as $placeholder) {
            if (!str_contains($translated, $placeholder)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate JSON Array structure and length.
     */
    public function validateJsonArray(string $sourceJson, string $translatedJson): bool
    {
        $srcDecoded = json_decode($sourceJson, true);
        $tgtDecoded = json_decode($translatedJson, true);

        if (!is_array($srcDecoded) || !is_array($tgtDecoded)) {
            return false;
        }

        if (count($srcDecoded) !== count($tgtDecoded)) {
            return false;
        }

        return true;
    }

    /**
     * Validate FAQ JSON structure.
     */
    public function validateFaqJson(string $sourceJson, string $translatedJson): bool
    {
        $srcDecoded = json_decode($sourceJson, true);
        $tgtDecoded = json_decode($translatedJson, true);

        if (!is_array($srcDecoded) || !is_array($tgtDecoded)) {
            return false;
        }

        if (count($srcDecoded) !== count($tgtDecoded)) {
            return false;
        }

        foreach ($tgtDecoded as $item) {
            if (!is_array($item) || (!isset($item['q']) && !isset($item['a']))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Detect if text was untranslated or returned in wrong script.
     */
    public function isProperlyTranslated(string $source, string $translated, string $sourceLang, string $targetLang): bool
    {
        $cleanSource = trim(strip_tags($source));
        $cleanTranslated = trim(strip_tags($translated));

        // Skip script checks for non-alphabetic strings (numbers, URLs, proper codes)
        if (preg_match('/^[\d\s\p{P}]+$/u', $cleanSource) || str_starts_with($cleanSource, 'http')) {
            return true;
        }

        $sourceLang = strtolower($sourceLang);
        $targetLang = strtolower($targetLang);

        if ($sourceLang === $targetLang) {
            return true;
        }

        // Target: Arabic -> MUST contain Arabic characters
        if ($targetLang === 'ar') {
            $hasArabic = preg_match('/\p{Arabic}/u', $cleanTranslated);
            if (!$hasArabic) {
                // Check if source was plain text and translation is completely identical non-Arabic
                if (strtolower($cleanSource) === strtolower($cleanTranslated)) {
                    return false;
                }
                // If it's a long sentence with no Arabic letters, it wasn't translated
                if (mb_strlen($cleanTranslated) > 15) {
                    return false;
                }
            }
        }

        // Target: English (from Arabic) -> Translation should not be identical Arabic
        if ($targetLang === 'en' && $sourceLang === 'ar') {
            $sourceHasArabic = preg_match('/\p{Arabic}/u', $cleanSource);
            $translatedHasArabic = preg_match('/\p{Arabic}/u', $cleanTranslated);

            if ($sourceHasArabic && $translatedHasArabic && strtolower($cleanSource) === strtolower($cleanTranslated)) {
                return false;
            }
        }

        return true;
    }
}
