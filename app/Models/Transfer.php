<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'transfer_type',
        'vehicle_type',
        'capacity',
        'from_location',
        'to_location',
        'approximate_duration_minutes',
        'description',
        'included_services',
        'base_price',
        'price_per_person',
        'active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'approximate_duration_minutes' => 'integer',
        'base_price' => 'decimal:2',
        'price_per_person' => 'decimal:2',
        'active' => 'boolean',
    ];

    // العلاقات
    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transfer_type', $type);
    }

    public function scopeByVehicle($query, $vehicleType)
    {
        return $query->where('vehicle_type', $vehicleType);
    }

    public function scopeByRoute($query, $from, $to)
    {
        return $query->where('from_location', $from)
            ->where('to_location', $to);
    }

    // السمات المحسوبة
    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->approximate_duration_minutes / 60);
        $minutes = $this->approximate_duration_minutes % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }
}