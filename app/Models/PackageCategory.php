<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PackageCategory extends Model
{
    protected $fillable = [
        'parent_id', 'country_id', 'slug', 'name', 'description', 'category_type', 'icon', 'image',
        'min_days', 'max_days', 'price_from', 'is_featured', 'is_active', 'sort_order',
        'seo_title', 'seo_description',
    ];

    protected $casts = [
        'price_from' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo { return $this->belongsTo(self::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(self::class, 'parent_id'); }
    public function country(): BelongsTo { return $this->belongsTo(Country::class); }
    public function packages(): HasMany { return $this->hasMany(Package::class, 'category_id'); }
    public function faqs(): HasMany { return $this->hasMany(Faq::class, 'category_id'); }
    public function translations(): MorphMany { return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id'); }
    public function seoMeta(): MorphMany { return $this->morphMany(SeoMeta::class, 'model', 'model_type', 'model_id'); }
}
