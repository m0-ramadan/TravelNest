<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'email', 'first_name', 'last_name', 'phone', 'country_id', 'date_of_birth', 'passport_number',
        'passport_expiry', 'nationality', 'newsletter_subscribed', 'total_bookings', 'total_spent',
        'last_activity', 'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
        'newsletter_subscribed' => 'boolean',
        'total_spent' => 'decimal:2',
        'last_activity' => 'datetime',
    ];

    public function country(): BelongsTo { return $this->belongsTo(Country::class); }
    public function inquiries(): HasMany { return $this->hasMany(Inquiry::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function communications(): HasMany { return $this->hasMany(Communication::class); }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
