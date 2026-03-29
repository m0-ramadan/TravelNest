<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageAttraction extends Model
{
    protected $fillable = ['package_id', 'attraction_id', 'title', 'teaser', 'image', 'sort_order'];

    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function attraction(): BelongsTo { return $this->belongsTo(Attraction::class); }
}
