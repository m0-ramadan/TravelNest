<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'region',
        'description',
        'short_description',
        'main_image',
        'gallery',
        'map_coordinates',
        'climate_info',
        'best_time_to_visit',
        'featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_text',
        'display_order',
        'active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'featured' => 'boolean',
        'active' => 'boolean',
        'display_order' => 'integer',
    ];

    // العلاقات
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function excursions()
    {
        return $this->hasMany(Excursion::class);
    }

    public function cruises()
    {
        return $this->hasMany(Cruise::class);
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // السمات (Accessors)
    public function getGalleryArrayAttribute()
    {
        return json_decode($this->gallery, true) ?? [];
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return Storage::url($this->main_image);
        }
        return asset('images/default-destination.jpg');
    }

    // المُعدِّلات (Mutators)
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['slug'] = Str::slug($this->name);
        } else {
            $this->attributes['slug'] = $value;
        }
    }
}