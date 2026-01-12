<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'title',
        'comment',
        'pros',
        'cons',
        'traveler_type',
        'travel_date',
        'helpful_votes',
        'approved',
        'featured',
        'admin_notes',
    ];

    protected $casts = [
        'rating' => 'integer',
        'travel_date' => 'date',
        'helpful_votes' => 'integer',
        'approved' => 'boolean',
        'featured' => 'boolean',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    // النطاقات
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeRecent($query, $days = 90)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // السمات المحسوبة
    public function getIsVerifiedAttribute()
    {
        return !is_null($this->booking_id);
    }

    // الأحداث
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            // تحديث متوسط التقييم للعنصر
            if ($review->reviewable) {
                $averageRating = $review->reviewable->reviews()
                    ->approved()
                    ->avg('rating');
                
                $totalReviews = $review->reviewable->reviews()
                    ->approved()
                    ->count();
                
                // تحديث الحقول في العنصر المرتبط
                $review->reviewable->update([
                    'rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                ]);
            }
        });
    }
}