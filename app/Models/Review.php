<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'client_id', 'package_id', 'rating', 'title', 'content', 'pros', 'cons',
        'travel_date', 'images', 'is_approved', 'helpful_count',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'travel_date' => 'date',
        'images' => 'array',
        'is_approved' => 'boolean',
    ];

    public function client(): BelongsTo { return $this->belongsTo(User::class, 'client_id'); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
}
