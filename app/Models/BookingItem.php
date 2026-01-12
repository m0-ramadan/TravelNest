<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'item_type',
        'item_id',
        'date',
        'adults',
        'children',
        'infants',
        'unit_price',
        'total_price',
        'currency',
        'special_notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected $appends = [
        'total_persons',
    ];

    // العلاقات
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function item()
    {
        return $this->morphTo();
    }

    public function excursions()
    {
        return $this->hasMany(BookingExcursion::class);
    }

    public function guideAssignments()
    {
        return $this->hasMany(GuideAssignment::class);
    }

    // النطاقات
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('item_type', $type);
    }

    // السمات المحسوبة
    public function getTotalPersonsAttribute()
    {
        return $this->adults + $this->children + $this->infants;
    }

    public function getItemNameAttribute()
    {
        if ($this->item) {
            return $this->item->name;
        }
        return 'N/A';
    }
}