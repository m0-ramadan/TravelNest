<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'inquiry_id',
        'package_id',
        'booking_number',
        'status',
        'total_amount',
        'paid_amount',
        'payment_status',
        'booking_date',
        'travel_date',
        'adults',
        'children',
        'special_requests',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'booking_date' => 'date',
        'travel_date' => 'date',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function communications(): HasMany
    {
        return $this->hasMany(Communication::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->total_amount - (float) $this->paid_amount;
    }
}
