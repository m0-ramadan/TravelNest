<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = ['code', 'symbol', 'name', 'rate_to_default', 'is_default', 'is_active'];

    protected $casts = [
        'rate_to_default' => 'decimal:8',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function packages(): HasMany { return $this->hasMany(Package::class); }
    public function packagePrices(): HasMany { return $this->hasMany(PackagePrice::class); }
}
