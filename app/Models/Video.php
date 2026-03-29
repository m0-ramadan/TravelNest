<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    protected $fillable = [
        'title', 'description', 'video_type', 'video_id', 'url', 'thumbnail', 'duration',
        'featured', 'published_at', 'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function mediables(): HasMany { return $this->hasMany(VideoMediable::class); }
}
