<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'key',
        'locale',
        'value'
    ];

    /**
     * Get translation value for a key and locale
     * 
     * @param string $key
     * @param string $locale
     * @return string|null
     */
    public static function getValue(string $key, string $locale = 'ar'): ?string
    {
        return self::where('key', $key)
            ->where('locale', $locale)
            ->value('value');
    }

    /**
     * Get all translations for a specific locale
     * 
     * @param string $locale
     * @return array
     */
    public static function getTranslationsByLocale(string $locale = 'ar'): array
    {
        return self::where('locale', $locale)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get all translations grouped by locale
     * 
     * @return array
     */
    public static function getAllGrouped(): array
    {
        $translations = self::all();
        $grouped = [];

        foreach ($translations as $translation) {
            $grouped[$translation->locale][$translation->key] = $translation->value;
        }

        return $grouped;
    }
}
