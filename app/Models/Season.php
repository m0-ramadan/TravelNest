<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'year',
        'description',
        'multiplier',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'year' => 'integer',
        'multiplier' => 'decimal:2',
    ];

    // العلاقات
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    // النطاقات
    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
            ->orderBy('start_date');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now());
    }

    // السمات المحسوبة
    public function getIsActiveAttribute()
    {
        return now()->between($this->start_date, $this->end_date);
    }

    public function getDurationDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // المُعدِّلات
    public function setYearAttribute($value)
    {
        if (!$value && $this->start_date) {
            $this->attributes['year'] = $this->start_date->year;
        } else {
            $this->attributes['year'] = $value;
        }
    }
}