<?php

namespace App\Models;

use App\Traits\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasTranslatableAttributes;

    protected $fillable = [
        'country_id',
        'name',
        'slug',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->translatedValue('name');
    }
}
