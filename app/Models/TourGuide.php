<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'languages',
        'specialization',
        'experience_years',
        'license_number',
        'license_expiry',
        'bio',
        'avatar',
        'hourly_rate',
        'daily_rate',
        'destinations',
        'availability_calendar',
        'rating',
        'total_reviews',
        'active',
    ];

    protected $casts = [
        'languages' => 'array',
        'specialization' => 'array',
        'destinations' => 'array',
        'availability_calendar' => 'array',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'experience_years' => 'integer',
        'total_reviews' => 'integer',
        'active' => 'boolean',
        'license_expiry' => 'date',
    ];

    // العلاقات
    public function assignments()
    {
        return $this->hasMany(GuideAssignment::class);
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->whereJsonContains('languages', $language);
    }

    public function scopeByDestination($query, $destinationId)
    {
        return $query->whereJsonContains('destinations', $destinationId);
    }

    public function scopeAvailable($query, $date)
    {
        return $query->where(function ($q) use ($date) {
            $q->whereNull('availability_calendar')
              ->orWhereJsonDoesntContain('availability_calendar->unavailable_dates', $date);
        });
    }

    // السمات المحسوبة
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        return asset('images/default-guide.jpg');
    }

    public function getIsLicensedAttribute()
    {
        return $this->license_number && $this->license_expiry >= now();
    }

    public function getLanguagesListAttribute()
    {
        return $this->languages ? implode(', ', $this->languages) : '';
    }
}