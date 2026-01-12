<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_item_id',
        'guide_id',
        'assignment_date',
        'start_time',
        'end_time',
        'hours_decimal',
        'rate',
        'total_amount',
        'meeting_point',
        'notes',
        'status',
        'guide_rating',
        'guide_feedback',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'hours_decimal' => 'decimal:2',
        'rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'guide_rating' => 'integer',
    ];

    // العلاقات
    public function bookingItem()
    {
        return $this->belongsTo(BookingItem::class);
    }

    public function guide()
    {
        return $this->belongsTo(TourGuide::class);
    }

    // النطاقات
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('assignment_date', '>=', now())
            ->whereIn('status', ['scheduled', 'confirmed']);
    }

    public function scopeByGuide($query, $guideId)
    {
        return $query->where('guide_id', $guideId);
    }

    // السمات المحسوبة
    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::parse($this->assignment_date . ' ' . $this->start_time);
            $end = Carbon::parse($this->assignment_date . ' ' . $this->end_time);
            return $start->diffInHours($end);
        }
        return $this->hours_decimal;
    }

    public function getIsUpcomingAttribute()
    {
        return $this->assignment_date >= now() && 
               in_array($this->status, ['scheduled', 'confirmed']);
    }
}