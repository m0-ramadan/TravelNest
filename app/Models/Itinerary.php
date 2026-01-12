<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'cruise_id',
        'day_number',
        'title',
        'description',
        'activities',
        'meals_included',
        'overnight_location',
        'accommodation_type',
        'included_excursions',
        'optional_excursions',
        'distance_km',
        'duration_hours',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'distance_km' => 'decimal:2',
        'duration_hours' => 'decimal:2',
    ];

    // العلاقات
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function cruise()
    {
        return $this->belongsTo(Cruise::class);
    }

    // النطاقات
    public function scopeForTour($query, $tourId)
    {
        return $query->where('tour_id', $tourId)->orderBy('day_number');
    }

    public function scopeForCruise($query, $cruiseId)
    {
        return $query->where('cruise_id', $cruiseId)->orderBy('day_number');
    }

    // السمات المحسوبة
    public function getMealsTextAttribute()
    {
        $meals = [];
        
        if (strpos($this->meals_included, 'breakfast') !== false) {
            $meals[] = 'Breakfast';
        }
        if (strpos($this->meals_included, 'lunch') !== false) {
            $meals[] = 'Lunch';
        }
        if (strpos($this->meals_included, 'dinner') !== false) {
            $meals[] = 'Dinner';
        }
        
        return empty($meals) ? 'No meals included' : implode(', ', $meals);
    }
}