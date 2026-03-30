<?php

namespace App\Models;

use App\Traits\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Itinerary extends Model
{
    use HasTranslatableAttributes;

    protected $fillable = [
        'package_id',
        'day_number',
        'title',
        'description',
        'meals_breakfast',
        'meals_lunch',
        'meals_dinner',
        'overnight_location',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'overnight_location' => 'array',
        'meals_breakfast' => 'boolean',
        'meals_lunch' => 'boolean',
        'meals_dinner' => 'boolean',
        'day_number' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id');
    }

    public function getDisplayTitleAttribute(): string
    {
        return $this->translatedValue('title');
    }
}
