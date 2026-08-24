<?php

namespace App\Services\Translation;

use App\Services\Translation\DTOs\TranslationResult;
use App\Services\Translation\DTOs\TranslationUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslationCacheService
{
    /**
     * Get cached translation if exists.
     */
    public function getCached(TranslationUnit $unit): ?string
    {
        $cached = DB::table('ai_translation_caches')
            ->where('source_hash', $unit->contentHash)
            ->where('source_language', strtolower($unit->sourceLanguage))
            ->where('target_language', strtolower($unit->targetLanguage))
            ->value('translated_text');

        return $cached ?: null;
    }

    /**
     * Store translation in database cache.
     */
    public function storeCache(TranslationUnit $unit, TranslationResult $result): void
    {
        if (!$result->isSuccess || empty($result->translatedText)) {
            return;
        }

        try {
            DB::table('ai_translation_caches')->updateOrInsert(
                [
                    'source_hash' => $unit->contentHash,
                    'source_language' => strtolower($unit->sourceLanguage),
                    'target_language' => strtolower($unit->targetLanguage),
                ],
                [
                    'source_text' => $unit->sourceText,
                    'translated_text' => $result->translatedText,
                    'provider' => $result->provider,
                    'model' => $result->model,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        } catch (\Throwable $e) {
            Log::error("Failed to store AI translation cache: " . $e->getMessage());
        }
    }

    /**
     * Log translation usage metadata for auditing and cost monitoring.
     */
    public function logUsage(TranslationUnit $unit, TranslationResult $result, string $status = 'success'): void
    {
        try {
            DB::table('ai_translation_usages')->insert([
                'provider' => $result->provider,
                'model' => $result->model,
                'entity_type' => $unit->entityType,
                'entity_id' => is_numeric($unit->entityId) ? (int) $unit->entityId : null,
                'field' => $unit->field,
                'source_language' => strtolower($unit->sourceLanguage),
                'target_language' => strtolower($unit->targetLanguage),
                'prompt_tokens' => $result->promptTokens,
                'output_tokens' => $result->outputTokens,
                'total_tokens' => $result->totalTokens,
                'status' => $status,
                'duration_ms' => $result->durationMs,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed to log AI translation usage: " . $e->getMessage());
        }
    }
}
