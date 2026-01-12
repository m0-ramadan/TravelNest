<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_booking_amount',
        'applicable_to',
        'specific_items',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_booking_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'per_user_limit' => 'integer',
        'active' => 'boolean',
        'specific_items' => 'array',
    ];

    // العلاقات
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_promo_codes')
            ->withPivot('discount_amount')
            ->withTimestamps();
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    // السمات المحسوبة
    public function getIsAvailableAttribute()
    {
        return $this->active && 
               now()->between($this->valid_from, $this->valid_until) &&
               (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }

    public function getRemainingUsesAttribute()
    {
        if ($this->usage_limit) {
            return max(0, $this->usage_limit - $this->used_count);
        }
        return null;
    }

    // الأساليب
    public function applyDiscount($amount)
    {
        switch ($this->discount_type) {
            case 'percentage':
                return $amount * ($this->discount_value / 100);
            case 'fixed_amount':
                return min($this->discount_value, $amount);
            default:
                return 0;
        }
    }
}