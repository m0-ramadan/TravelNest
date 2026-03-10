<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'transaction_id',
        'notes',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipped_at',
        'delivered_at',
        'status_payment',
        'image',
        'provider', // internal, like4app
        'provider_order_id', // like4app order ID
        'provider_response', // full response from provider
        'serial_codes', // JSON array of serial codes
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'provider_response' => 'array',
        'serial_codes' => 'array',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function getFormattedOrderNumberAttribute()
    {
        return $this->order_number;
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'processing' => 'تحت المعالجة',
            'completed' => 'تم التسليم',
            'cancelled' => 'ملغي',
            'refunded' => 'تم الاسترداد',
            default => 'غير معروف',
        };
    }

    public function getProviderLabelAttribute()
    {
        return match ($this->provider) {
            'like4app' => 'لايك كارد',
            'internal' => 'داخلي',
            default => 'غير معروف',
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . date('Y') . '-' . str_pad(static::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
