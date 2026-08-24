<?php

namespace App\Services\Translation;

use App\Contracts\TranslationProviderInterface;
use App\Models\Language;
use App\Models\Package;
use App\Models\Itinerary;
use App\Models\PackageHighlight;
use App\Models\PackageInclusion;
use App\Models\NileCruiseCabin;
use App\Models\NileCruiseItineraryDay;
use App\Services\Translation\DTOs\TranslationOptions;
use App\Services\Translation\DTOs\TranslationResult;
use App\Services\Translation\DTOs\TranslationUnit;
use App\Services\Translation\Providers\GeminiTranslationProvider;
use App\Services\Translation\Providers\DeepSeekTranslationProvider;
use App\Services\Translation\Schemas\PackageTranslationSchema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AiTranslationService
{
    protected GeminiTranslationProvider $geminiProvider;
    protected DeepSeekTranslationProvider $deepseekProvider;
    protected TranslationValidator $validator;
    protected TranslationCacheService $cacheService;
    protected PackageTranslationSchema $packageSchema;

    public function __construct(
        GeminiTranslationProvider $geminiProvider,
        DeepSeekTranslationProvider $deepseekProvider,
        TranslationValidator $validator,
        TranslationCacheService $cacheService,
        PackageTranslationSchema $packageSchema
    ) {
        $this->geminiProvider = $geminiProvider;
        $this->deepseekProvider = $deepseekProvider;
        $this->validator = $validator;
        $this->cacheService = $cacheService;
        $this->packageSchema = $packageSchema;
    }

    /**
     * Get active target languages from the database.
     */
    public function getActiveLanguages(): array
    {
        if (class_exists(Language::class)) {
            $codes = Language::query()
                ->where('is_active', true)
                ->pluck('code')
                ->map(fn($c) => strtolower(trim((string) $c)))
                ->toArray();

            if (!empty($codes)) {
                return array_values(array_unique($codes));
            }
        }

        return ['en', 'ar'];
    }

    /**
     * Translate a single TranslationUnit using Gemini -> DeepSeek Fallback flow.
     */
    public function translateUnit(TranslationUnit $unit, ?TranslationOptions $options = null): TranslationResult
    {
        // 1. Check Database Cache
        $cachedText = $this->cacheService->getCached($unit);
        if ($cachedText !== null && !empty(trim($cachedText))) {
            return new TranslationResult(
                translatedText: $cachedText,
                provider: 'cache',
                model: 'cache',
                isSuccess: true,
                errorMessage: null
            );
        }

        $options = $options ?? new TranslationOptions(structuredType: $unit->structuredType);

        // 2. Check if Google Gemini is temporarily marked as quota exhausted / Circuit open
        $isGeminiBlocked = Cache::has('google_translation_quota_exhausted');

        $result = null;

        if (!$isGeminiBlocked) {
            // Attempt Primary: Gemini 2.5 Flash
            $result = $this->geminiProvider->translate($unit->sourceText, $unit->sourceLanguage, $unit->targetLanguage, $options);

            // Validate Result
            if ($result->isSuccess) {
                $isValid = $this->validator->validate(
                    $unit->sourceText,
                    $result->translatedText,
                    $unit->sourceLanguage,
                    $unit->targetLanguage,
                    $unit->structuredType
                );

                if (!$isValid) {
                    Log::warning("Gemini returned invalid or untranslated response for unit {$unit->entityType}#{$unit->entityId}:{$unit->field}. Falling back to DeepSeek.");
                    $result->isSuccess = false;
                    $result->errorMessage = "Validation failed: output untranslated or malformed";
                }
            } else {
                // If 429 or quota limit hit, trigger circuit breaker
                if (str_contains((string) $result->errorMessage, '429') || str_contains(strtolower((string) $result->errorMessage), 'quota')) {
                    Cache::put('google_translation_quota_exhausted', true, config('translation_ai.circuit_breaker_cooldown', 300));
                    Log::warning("Gemini quota exhausted. Marking Gemini circuit breaker open for 5 minutes.");
                }
            }
        }

        // 3. Fallback: DeepSeek API (if Gemini was skipped or failed)
        $isDeepseekBlocked = Cache::has('deepseek_translation_quota_exhausted');

        if (($result === null || !$result->isSuccess) && !$isDeepseekBlocked) {
            Log::info("Executing DeepSeek fallback translation for {$unit->entityType}#{$unit->entityId}:{$unit->field} ({$unit->sourceLanguage}->{$unit->targetLanguage})");

            $fallbackResult = $this->deepseekProvider->translate($unit->sourceText, $unit->sourceLanguage, $unit->targetLanguage, $options);

            if ($fallbackResult->isSuccess) {
                $isValidFallback = $this->validator->validate(
                    $unit->sourceText,
                    $fallbackResult->translatedText,
                    $unit->sourceLanguage,
                    $unit->targetLanguage,
                    $unit->structuredType
                );

                if ($isValidFallback) {
                    $fallbackResult->translatedText = $this->validator->cleanMarkdownCodeBlocks($fallbackResult->translatedText);
                    $this->cacheService->storeCache($unit, $fallbackResult);
                    $this->cacheService->logUsage($unit, $fallbackResult, 'fallback');
                    return $fallbackResult;
                }
            } else {
                $err = strtolower((string) $fallbackResult->errorMessage);
                if (str_contains($err, '402') || str_contains($err, '429') || str_contains($err, '401') || str_contains($err, 'balance') || str_contains($err, 'quota')) {
                    Cache::put('deepseek_translation_quota_exhausted', true, config('translation_ai.circuit_breaker_cooldown', 300));
                    Log::warning("DeepSeek quota or balance exhausted. Marking DeepSeek circuit breaker open for 5 minutes.");
                }
            }

            // If DeepSeek also failed, log failure and return original result or failure DTO
            $failedResult = $fallbackResult->isSuccess ? TranslationResult::failure('deepseek', 'deepseek-chat', 'DeepSeek validation failed') : $fallbackResult;
            $this->cacheService->logUsage($unit, $failedResult, 'failed');
            return $failedResult;
        }

        // Clean & Store Successful Gemini Result
        $result->translatedText = $this->validator->cleanMarkdownCodeBlocks($result->translatedText);
        $this->cacheService->storeCache($unit, $result);
        $this->cacheService->logUsage($unit, $result, 'success');

        return $result;
    }

    /**
     * Translate a standalone string with Gemini -> DeepSeek -> original source text fallback.
     */
    public function translateString(string $text, string $targetLanguage, string $sourceLanguage = 'en'): string
    {
        $text = trim($text);
        if ($text === '' || strtolower($targetLanguage) === strtolower($sourceLanguage)) {
            return $text;
        }

        $unit = new TranslationUnit(
            entityType: 'general',
            entityId: 0,
            field: 'text',
            sourceLanguage: strtolower($sourceLanguage),
            targetLanguage: strtolower($targetLanguage),
            sourceText: $text,
            structuredType: 'text'
        );

        $result = $this->translateUnit($unit);

        if ($result->isSuccess && !empty(trim((string) $result->translatedText))) {
            return trim($result->translatedText);
        }

        return $text;
    }

    /**
     * Automatically detect source language based on available non-empty content in package.
     */
    public function detectSourceLanguage(Package $package): string
    {
        $titleTrans = $package->getTranslations('title');

        if (!empty($titleTrans['en'])) {
            return 'en';
        }
        if (!empty($titleTrans['ar'])) {
            return 'ar';
        }

        foreach ($titleTrans as $lang => $text) {
            if (!empty(trim((string) $text))) {
                return $lang;
            }
        }

        return config('app.fallback_locale', 'en');
    }

    /**
     * Translate missing or stale package content.
     */
    public function translatePackage(Package $package, ?string $sourceLang = null, bool $missingOnly = true): array
    {
        $sourceLang = $sourceLang ?: $this->detectSourceLanguage($package);
        $activeLangs = $this->getActiveLanguages();
        $targetLangs = array_values(array_filter($activeLangs, fn($l) => strtolower($l) !== strtolower($sourceLang)));

        $units = $this->packageSchema->extractUnits($package, $sourceLang, $targetLangs, $missingOnly);

        $resultsSummary = [
            'total_units' => count($units),
            'success_count' => 0,
            'failed_count' => 0,
            'fallback_count' => 0,
            'cached_count' => 0,
            'details' => [],
        ];

        foreach ($units as $unit) {
            $result = $this->translateUnit($unit);

            if ($result->isSuccess) {
                $resultsSummary['success_count']++;
                if ($result->provider === 'cache') {
                    $resultsSummary['cached_count']++;
                } elseif ($result->provider === 'deepseek') {
                    $resultsSummary['fallback_count']++;
                }

                $this->applyTranslationToModel($package, $unit, $result->translatedText);
            } else {
                $resultsSummary['failed_count']++;
                Log::warning("Failed to translate unit {$unit->entityType}#{$unit->entityId}:{$unit->field} to {$unit->targetLanguage}: {$result->errorMessage}");
            }

            $resultsSummary['details'][] = [
                'entity' => $unit->entityType,
                'id' => $unit->entityId,
                'field' => $unit->field,
                'target_language' => $unit->targetLanguage,
                'provider' => $result->provider,
                'success' => $result->isSuccess,
            ];
        }

        return $resultsSummary;
    }

    /**
     * Apply translated text back to Eloquent models.
     */
    protected function applyTranslationToModel(Package $package, TranslationUnit $unit, string $translatedText): void
    {
        if ($unit->entityType === 'package') {
            if ($unit->structuredType === 'json_array') {
                $decodedTranslated = json_decode($translatedText, true);
                if (is_array($decodedTranslated)) {
                    $existing = $package->{$unit->field} ?: [];
                    foreach ($decodedTranslated as $idx => $val) {
                        if (!isset($existing[$idx]) || !is_array($existing[$idx])) {
                            $existing[$idx] = [];
                        }
                        $existing[$idx][$unit->targetLanguage] = $val;
                    }
                    $package->{$unit->field} = $existing;
                    $package->save();
                }
            } else {
                $package->setTranslation($unit->field, $unit->targetLanguage, $translatedText);
                $package->save();
            }
        } elseif ($unit->entityType === 'package_faq_batch') {
            $decodedBatch = json_decode($translatedText, true);
            if (is_array($decodedBatch)) {
                $existingFaqs = $package->faq_json ?: [];
                $batchIndex = (int) str_replace('faq_json_batch_', '', $unit->field);
                $startIndex = $batchIndex * 5;

                foreach ($decodedBatch as $offset => $faqItem) {
                    $globalIndex = $startIndex + $offset;
                    if (isset($existingFaqs[$globalIndex])) {
                        $q = $existingFaqs[$globalIndex]['question'] ?? $existingFaqs[$globalIndex]['q'] ?? [];
                        $a = $existingFaqs[$globalIndex]['answer'] ?? $existingFaqs[$globalIndex]['a'] ?? [];

                        if (!is_array($q)) {
                            $q = [$unit->sourceLanguage => (string) $q];
                        }
                        if (!is_array($a)) {
                            $a = [$unit->sourceLanguage => (string) $a];
                        }

                        $q[$unit->targetLanguage] = $faqItem['q'] ?? '';
                        $a[$unit->targetLanguage] = $faqItem['a'] ?? '';

                        $existingFaqs[$globalIndex]['question'] = $q;
                        $existingFaqs[$globalIndex]['answer'] = $a;
                    }
                }

                $package->faq_json = $existingFaqs;
                $package->save();
            }
        } elseif ($unit->entityType === 'package_highlight') {
            $highlight = PackageHighlight::find($unit->entityId);
            if ($highlight) {
                $highlight->setTranslation($unit->field, $unit->targetLanguage, $translatedText);
                $highlight->save();
            }
        } elseif ($unit->entityType === 'package_inclusion') {
            $inclusion = PackageInclusion::find($unit->entityId);
            if ($inclusion) {
                $inclusion->setTranslation($unit->field, $unit->targetLanguage, $translatedText);
                $inclusion->save();
            }
        } elseif ($unit->entityType === 'itinerary') {
            $itinerary = Itinerary::find($unit->entityId);
            if ($itinerary) {
                $itinerary->setTranslation($unit->field, $unit->targetLanguage, $translatedText);
                $itinerary->save();
            }
        } elseif ($unit->entityType === 'nile_cruise_cabin') {
            $cabin = NileCruiseCabin::find($unit->entityId);
            if ($cabin) {
                $desc = $cabin->description ?: [];
                if (!is_array($desc)) {
                    $desc = [$unit->sourceLanguage => (string) $desc];
                }
                $desc[$unit->targetLanguage] = $translatedText;
                $cabin->description = $desc;
                $cabin->save();
            }
        } elseif ($unit->entityType === 'nile_cruise_itinerary_day') {
            $ncDay = NileCruiseItineraryDay::find($unit->entityId);
            if ($ncDay) {
                $ncDay->setTranslation($unit->field, $unit->targetLanguage, $translatedText);
                $ncDay->save();
            }
        }
    }
}
