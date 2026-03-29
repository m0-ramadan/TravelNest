<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Destination extends Model
{
    protected $fillable = [
        'parent_id', 'country_id', 'city_id', 'type', 'slug', 'name', 'short_description',
        'description', 'hero_image', 'featured_image', 'latitude', 'longitude',
        'is_featured', 'is_active', 'sort_order', 'seo_title', 'seo_description', 'schema_json',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'schema_json' => 'array',
    ];

    public function parent(): BelongsTo { return $this->belongsTo(self::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(self::class, 'parent_id'); }
    public function country(): BelongsTo { return $this->belongsTo(Country::class); }
    public function city(): BelongsTo { return $this->belongsTo(City::class); }
    public function attractions(): HasMany { return $this->hasMany(Attraction::class); }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_destinations', 'destination_id', 'package_id')
            ->withPivot(['stop_order', 'is_primary', 'nights'])
            ->withTimestamps();
    }

    public function posts(): HasMany { return $this->hasMany(Post::class); }
    public function translations(): MorphMany { return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id'); }
    public function seoMeta(): MorphMany { return $this->morphMany(SeoMeta::class, 'model', 'model_type', 'model_id'); }
}
