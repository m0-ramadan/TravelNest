<?php

namespace App\Models;

use App\Traits\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentMethod extends Model
{
    use HasFactory, HasTranslatableAttributes;

    protected $fillable = [
        'name',
        'key',
        'icon',
        'is_active',
        'is_payment',
        'is_wallet',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'is_payment' => 'boolean',
        'is_wallet' => 'boolean',
    ];

    protected $appends = [
        'icon_url',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->translatedValue('name');
    }

    public function getIconUrlAttribute(): string
    {
        if ($this->icon) {
            return Storage::disk('public')->url('payment-methods/' . $this->icon);
        }

        return asset('images/default-payment.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePaymentOnly($query)
    {
        return $query->where('is_payment', true);
    }

    public function scopeOtherOnly($query)
    {
        return $query->where('is_payment', false);
    }

    public static function generateUniqueKey(string $name): string
    {
        $key = Str::slug($name, '-');
        $counter = 1;
        $originalKey = $key;

        while (self::where('key', $key)->exists()) {
            $key = $originalKey . '-' . $counter;
            $counter++;
        }

        return $key;
    }
}
