<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PackageTag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_tag_items', 'tag_id', 'package_id')->withTimestamps();
    }
}
