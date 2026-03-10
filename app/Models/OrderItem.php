<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'is_sample',
        'note',
        'quantity_id',
        'image_design',
        'voucher_code',
        'provider_data', // Store like4app provider data
        'serial_codes', // Individual serial codes for this item
    ];

    protected $casts = [
        'print_locations' => 'array',
        'embroider_locations' => 'array',
        'selected_options' => 'array',
        'price_per_unit' => 'decimal:4',
        'total_price' => 'decimal:4',
        'is_sample' => 'boolean',
        'provider_data' => 'array',
        'serial_codes' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_price, 2) . ' ج.م';
    }

    public function getProviderAttribute()
    {
        return $this->product && $this->product->external_id ? 'like4app' : 'internal';
    }
}
