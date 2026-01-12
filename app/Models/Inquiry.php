<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'inquiry_type',
        'tour_id',
        'cruise_id',
        'travel_date',
        'number_of_adults',
        'number_of_children',
        'number_of_nights',
        'budget_range',
        'message',
        'source_url',
        'ip_address',
        'user_agent',
        'status',
        'assigned_to',
        'notes',
        'follow_up_date',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'follow_up_date' => 'date',
        'number_of_adults' => 'integer',
        'number_of_children' => 'integer',
        'number_of_nights' => 'integer',
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

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // النطاقات
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    public function scopeRequiresFollowUp($query)
    {
        return $query->where('follow_up_date', '<=', now())
            ->whereIn('status', ['new', 'contacted']);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // السمات المحسوبة
    public function getIsNewAttribute()
    {
        return $this->status === 'new';
    }

    public function getDaysOldAttribute()
    {
        return now()->diffInDays($this->created_at);
    }

    // الأحداث
    protected static function boot()
    {
        parent::boot();

        static::created(function ($inquiry) {
            // إرسال إشعار للإداريين
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                $admin->notify(new NewInquiryNotification($inquiry));
            }
        });
    }
}