<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Excursion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'destination_id',
        'duration_hours',
        'description',
        'highlights',
        'includes',
        'not_includes',
        'requirements',
        'meeting_point',
        'main_image',
        'gallery',
        'featured',
        'active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'featured' => 'boolean',
        'active' => 'boolean',
        'duration_hours' => 'decimal:2',
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

    public function bookingExcursions()
    {
        return $this->hasMany(BookingExcursion::class);
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

    public function scopeByDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    // السمات المحسوبة
    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_hours);
        $minutes = ($this->duration_hours - $hours) * 60;
        
        if ($minutes > 0) {
            return "{$hours}h {$minutes}m";
        }
        return "{$hours}h";
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return Storage::url($this->main_image);
        }
        return asset('images/default-excursion.jpg');
    }
}