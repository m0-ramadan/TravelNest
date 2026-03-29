<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackagePrice extends Model
{
    protected $fillable = [
        'package_id', 'label', 'season_name', 'price_type', 'room_type', 'pax_min', 'pax_max',
        'group_size_min', 'group_size_max', 'amount', 'currency_id', 'valid_from', 'valid_to', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
}
