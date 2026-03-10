<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'icon',
        'is_active',
        'is_payment','is_wallet'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_payment' => 'boolean',
    ];

    protected $appends = [
        'icon_url'
    ];

    /**
     * Get the URL for the icon
     */
    public function getIconUrlAttribute()
    {
        if ($this->icon) {
            return Storage::disk('public')->url('payment-methods/' . $this->icon);
        }

        return asset('images/default-payment.png');
    }

    /**
     * Scope active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope payment methods only (not other methods)
     */
    public function scopePaymentOnly($query)
    {
        return $query->where('is_payment', true);
    }

    /**
     * Scope other methods (not payment methods)
     */
    public function scopeOtherOnly($query)
    {
        return $query->where('is_payment', false);
    }

    /**
     * Generate a unique key for the payment method
     */
    public static function generateUniqueKey($name)
    {
        $key = str_slug($name, '-', 'ar');
        $counter = 1;
        $originalKey = $key;

        while (self::where('key', $key)->exists()) {
            $key = $originalKey . '-' . $counter;
            $counter++;
        }

        return $key;
    }
}
