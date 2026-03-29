<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Faq extends Model
{
    protected $fillable = ['category_id', 'question', 'answer', 'context_type', 'context_id', 'is_active', 'sort_order', 'is_featured'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PackageCategory::class, 'category_id');
    }
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id');
    }
}
