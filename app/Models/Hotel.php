<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'destination_id',
        'stars',
        'hotel_chain',
        'description',
        'address',
        'location_coordinates',
        'check_in_time',
        'check_out_time',
        'facilities',
        'room_amenities',
        'main_image',
        'gallery',
        'active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'room_amenities' => 'array',
        'gallery' => 'array',
        'active' => 'boolean',
        'stars' => 'integer',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    // العلاقات
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByStars($query, $stars)
    {
        return $query->where('stars', $stars);
    }

    public function scopeByDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    // السمات المحسوبة
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return Storage::url($this->main_image);
        }
        return asset('images/default-hotel.jpg');
    }

    public function getLocationArrayAttribute()
    {
        if ($this->location_coordinates) {
            $coords = explode(',', $this->location_coordinates);
            return [
                'lat' => floatval($coords[0]),
                'lng' => floatval($coords[1]),
            ];
        }
        return null;
    }
}