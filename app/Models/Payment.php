<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_number',
        'amount',
        'currency',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'transaction_status',
        'status',
        'payment_date',
        'due_date',
        'notes',
        'gateway_response',
        'refund_amount',
        'refund_date',
        'refund_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'due_date' => 'date',
        'refund_date' => 'date',
        'gateway_response' => 'array',
        'status' => 'string',
    ];

    // العلاقات
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // النطاقات
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // السمات المحسوبة
    public function getIsSuccessfulAttribute()
    {
        return in_array($this->status, ['completed', 'partially_refunded']);
    }

    public function getIsRefundedAttribute()
    {
        return in_array($this->status, ['refunded', 'partially_refunded']);
    }

    // الأحداث
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->payment_number) {
                $payment->payment_number = 'PAY-' . strtoupper(Str::random(10));
            }
        });

        static::updated(function ($payment) {
            if ($payment->status == 'completed' && $payment->booking) {
                // تحديث حالة الحجز بناءً على الدفع
                $booking = $payment->booking;
                $totalPaid = $booking->payments()->completed()->sum('amount');
                
                if ($totalPaid >= $booking->total_amount) {
                    $booking->status = 'fully_paid';
                } elseif ($totalPaid >= $booking->deposit_amount) {
                    $booking->status = 'deposit_paid';
                }
                
                $booking->save();
            }
        });
    }
}