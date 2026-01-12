<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'priceable_type',
        'priceable_id',
        'season_id',
        'price_type',
        'occupancy_type',
        'currency',
        'base_price',
        'discount_price',
        'discount_percentage',
        'discount_valid_until',
        'includes_taxes',
        'taxes_percentage',
        'minimum_persons',
        'maximum_persons',
        'valid_from',
        'valid_until',
        'notes',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'discount_valid_until' => 'date',
        'includes_taxes' => 'boolean',
        'taxes_percentage' => 'decimal:2',
        'minimum_persons' => 'integer',
        'maximum_persons' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    // العلاقات
    public function priceable()
    {
        return $this->morphTo();
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    // النطاقات
    public function scopeValid($query)
    {
        return $query->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function scopeCurrent($query)
    {
        return $this->valid();
    }

    public function scopeForSeason($query, $seasonId)
    {
        return $query->where('season_id', $seasonId);
    }

    public function scopeForItem($query, $type, $id)
    {
        return $query->where('priceable_type', $type)
            ->where('priceable_id', $id);
    }

    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discount_price')
            ->orWhereNotNull('discount_percentage')
            ->where('discount_valid_until', '>=', now());
    }

    // السمات المحسوبة
    public function getFinalPriceAttribute()
    {
        if ($this->discount_price) {
            return $this->discount_price;
        }
        
        if ($this->discount_percentage) {
            return $this->base_price * (1 - ($this->discount_percentage / 100));
        }
        
        return $this->base_price;
    }

    public function getTaxAmountAttribute()
    {
        if ($this->includes_taxes) {
            return 0;
        }
        
        return $this->final_price * ($this->taxes_percentage / 100);
    }

    public function getTotalPriceAttribute()
    {
        return $this->final_price + $this->tax_amount;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->discount_price) || 
               (!is_null($this->discount_percentage) && 
                $this->discount_valid_until >= now());
    }

    public function getIsValidAttribute()
    {
        return now()->between($this->valid_from, $this->valid_until);
    }

    // الأحداث
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($price) {
            if ($price->discount_percentage && !$price->discount_price) {
                $price->discount_price = $price->base_price * (1 - ($price->discount_percentage / 100));
            }
        });
    }
}