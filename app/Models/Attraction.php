<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Attraction extends Model
{
    protected $fillable = [
        'destination_id', 'slug', 'name', 'short_description', 'description',
        'image', 'is_active', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function destination(): BelongsTo { return $this->belongsTo(Destination::class); }
    public function packageAttractions(): HasMany { return $this->hasMany(PackageAttraction::class); }
    public function translations(): MorphMany { return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id'); }
    public function seoMeta(): MorphMany { return $this->morphMany(SeoMeta::class, 'model', 'model_type', 'model_id'); }
}
