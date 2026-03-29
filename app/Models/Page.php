<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'template', 'body', 'featured_image', 'is_home', 'is_active',
        'published_at', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function translations(): MorphMany { return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id'); }
    public function seoMeta(): MorphMany { return $this->morphMany(SeoMeta::class, 'model', 'model_type', 'model_id'); }
}
