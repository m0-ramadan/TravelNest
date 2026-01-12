<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingExcursion extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_item_id',
        'excursion_id',
        'date',
        'persons',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'persons' => 'integer',
        'price' => 'decimal:2',
    ];

    // العلاقات
    public function bookingItem()
    {
        return $this->belongsTo(BookingItem::class);
    }

    public function excursion()
    {
        return $this->belongsTo(Excursion::class);
    }
}