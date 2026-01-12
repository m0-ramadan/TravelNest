<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cruise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cruise_type',
        'stars',
        'length',
        'width',
        'year_built',
        'year_renovated',
        'cabins_total',
        'suites_total',
        'facilities',
        'amenities',
        'main_image',
        'deck_plan_image',
        'gallery',
        'featured',
        'display_order',
        'active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'amenities' => 'array',
        'gallery' => 'array',
        'featured' => 'boolean',
        'active' => 'boolean',
        'stars' => 'integer',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'cabins_total' => 'integer',
        'suites_total' => 'integer',
        'display_order' => 'integer',
        'year_built' => 'integer',
        'year_renovated' => 'integer',
    ];

    // العلاقات
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function schedules()
    {
        return $this->hasMany(CruiseSchedule::class);
    }

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('cruise_type', $type);
    }

    public function scopeByStars($query, $stars)
    {
        return $query->where('stars', $stars);
    }

    public function scopeAvailable($query, $startDate, $endDate)
    {
        return $query->whereHas('schedules', function ($q) use ($startDate, $endDate) {
            $q->where('departure_date', '>=', $startDate)
              ->where('departure_date', '<=', $endDate)
              ->where('available_cabins', '>', 0)
              ->where('is_full', false);
        });
    }

    // السمات المحسوبة
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return Storage::url($this->main_image);
        }
        return asset('images/default-cruise.jpg');
    }

    public function getDeckPlanUrlAttribute()
    {
        if ($this->deck_plan_image) {
            return Storage::url($this->deck_plan_image);
        }
        return null;
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->cabins_total > 0) {
            $bookedCabins = $this->schedules()
                ->where('departure_date', '>=', now())
                ->sum('booked_cabins');
            
            return ($bookedCabins / $this->cabins_total) * 100;
        }
        return 0;
    }
}