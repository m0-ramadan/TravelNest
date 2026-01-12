<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'status',
        'booking_type',
        'travel_date',
        'number_of_adults',
        'number_of_children',
        'number_of_infants',
        'special_requests',
        'dietary_restrictions',
        'mobility_requirements',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'total_amount',
        'currency',
        'deposit_amount',
        'deposit_due_date',
        'balance_due_date',
        'cancellation_reason',
        'cancellation_date',
        'cancellation_fee',
        'notes',
        'source',
        'assigned_to',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'deposit_due_date' => 'date',
        'balance_due_date' => 'date',
        'cancellation_date' => 'date',
        'number_of_adults' => 'integer',
        'number_of_children' => 'integer',
        'number_of_infants' => 'integer',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'cancellation_fee' => 'decimal:2',
    ];

    protected $appends = [
        'total_persons',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function cruiseBookings()
    {
        return $this->hasMany(CruiseBooking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function excursions()
    {
        return $this->hasManyThrough(BookingExcursion::class, BookingItem::class);
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    // النطاقات
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePaid($query)
    {
        return $query->whereIn('status', ['deposit_paid', 'fully_paid']);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('travel_date', '>=', now())
            ->whereNotIn('status', ['cancelled', 'completed']);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAgent($query, $agentId)
    {
        return $query->where('assigned_to', $agentId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // السمات المحسوبة
    public function getTotalPersonsAttribute()
    {
        return $this->number_of_adults + $this->number_of_children + $this->number_of_infants;
    }

    public function getBalanceDueAttribute()
    {
        $paidAmount = $this->payments()->where('status', 'completed')->sum('amount');
        return max(0, $this->total_amount - $paidAmount);
    }

    public function getDepositDueAttribute()
    {
        $paidAmount = $this->payments()->where('status', 'completed')->sum('amount');
        return max(0, $this->deposit_amount - $paidAmount);
    }

    public function getPaymentStatusAttribute()
    {
        $paidAmount = $this->payments()->where('status', 'completed')->sum('amount');
        
        if ($paidAmount >= $this->total_amount) {
            return 'fully_paid';
        } elseif ($paidAmount >= $this->deposit_amount) {
            return 'deposit_paid';
        } else {
            return 'pending_payment';
        }
    }

    public function getDaysUntilTravelAttribute()
    {
        return now()->diffInDays($this->travel_date, false);
    }

    // الأحداث
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'BK-' . strtoupper(Str::random(8));
            }
        });
    }
}