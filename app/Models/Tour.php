<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'tour_type_id',
        'duration_days',
        'duration_nights',
        'difficulty',
        'min_persons',
        'max_persons',
        'description',
        'highlights',
        'inclusions',
        'exclusions',
        'important_notes',
        'cancellation_policy',
        'main_image',
        'gallery',
        'featured',
        'best_seller',
        'special_offer',
        'display_order',
        'active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'featured' => 'boolean',
        'best_seller' => 'boolean',
        'special_offer' => 'boolean',
        'active' => 'boolean',
        'duration_days' => 'integer',
        'duration_nights' => 'integer',
        'min_persons' => 'integer',
        'max_persons' => 'integer',
        'display_order' => 'integer',
    ];

    // العلاقات
    public function tourType()
    {
        return $this->belongsTo(TourType::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
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
        return $this->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('best_seller', true);
    }

    public function scopeSpecialOffer($query)
    {
        return $query->where('special_offer', true);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('tour_type_id', $typeId);
    }

    public function scopeByDuration($query, $minDays, $maxDays = null)
    {
        $query = $query->where('duration_days', '>=', $minDays);
        
        if ($maxDays) {
            $query = $query->where('duration_days', '<=', $maxDays);
        }
        
        return $query;
    }

    // السمات المحسوبة
    public function getDurationAttribute()
    {
        if ($this->duration_nights) {
            return "{$this->duration_days} Days / {$this->duration_nights} Nights";
        }
        return "{$this->duration_days} Days";
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return Storage::url($this->main_image);
        }
        return asset('images/default-tour.jpg');
    }

    public function getCurrentPriceAttribute()
    {
        $season = Season::current()->first();
        
        return $this->prices()
            ->where('season_id', $season->id)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where('occupancy_type', 'double')
            ->orderBy('created_at', 'desc')
            ->first();
    }
}