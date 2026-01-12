<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'apple_id',
        'phone',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }
    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable');
    }
    public function favourites()
    {
        return $this->hasMany(\App\Models\Favourite::class);
    }


    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // العلاقات
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function assignedBookings()
    {
        return $this->hasMany(Booking::class, 'assigned_to');
    }

    public function assignedInquiries()
    {
        return $this->hasMany(Inquiry::class, 'assigned_to');
    }

    // النطاقات (Scopes)
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeAgents($query)
    {
        return $query->where('role', 'agent');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // الأحداث (Events)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->password)) {
                $user->password = bcrypt(Str::random(10));
            }
        });
    }
}
