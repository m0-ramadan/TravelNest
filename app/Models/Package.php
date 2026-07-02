<?php

namespace App\Models;

use App\Traits\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Package extends Model
{
    use HasTranslatableAttributes;

    protected $fillable = [
        'category_id',
        'destination_id',
        'primary_country_id',
        'package_type',
        'slug',
        'title',
        'subtitle',
        'short_description',
        'description',
        'duration_days',
        'duration_hours',
        'duration_nights',
        'duration_text',
        'route_text',
        'start_from_price',
        'compare_price',
        'currency_id',
        'schedule_text',
        'pickup_location',
        'dropoff_location',
        'destinations_text',
        'location_summary',
        'tour_type',
        'difficulty_level',
        'booking_mode',
        'rating_avg',
        'reviews_count',
        'is_featured',
        'is_best_seller',
        'is_ultra_luxury',
        'is_active',
        'min_participants',
        'max_participants',
        'booking_lead_days',
        'cancellation_policy',
        'terms_conditions',
        'pricing_information',
        'children_policy',
        'pickup_policy',
        'faq_json',
        'video_url',
        'featured_image',
        'gallery_images',
        'published_at',
        'sort_order',
        'seo_title',
        'seo_description',
        'breadcrumb_title',
        'canonical_url',
        'created_by',
        'updated_by',
        'offer_price',
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'short_description' => 'array',
        'description' => 'array',
        'schedule_text' => 'array',
        'pickup_location' => 'array',
        'dropoff_location' => 'array',
        'destinations_text' => 'array',
        'location_summary' => 'array',
        'cancellation_policy' => 'array',
        'terms_conditions' => 'array',
        'seo_title' => 'array',
        'seo_description' => 'array',
        'breadcrumb_title' => 'array',
        'start_from_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'duration_days' => 'integer',
        'duration_nights' => 'integer',
        'reviews_count' => 'integer',
        'min_participants' => 'integer',
        'max_participants' => 'integer',
        'booking_lead_days' => 'integer',
        'faq_json' => 'array',
        'gallery_images' => 'array',
        'published_at' => 'datetime',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_ultra_luxury' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PackageCategory::class, 'category_id');
    }

    public function primaryCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'primary_country_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Attraction::class, 'destination_id');
    }
    public function facilities(): HasMany
    {
        return $this->hasMany(PackageHighlight::class)->orderBy('sort_order');
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }



    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Attraction::class, 'package_destinations', 'package_id', 'destination_id')
            ->withPivot(['stop_order', 'is_primary', 'nights'])
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PackageTag::class, 'package_tag_items', 'package_id', 'tag_id')->withTimestamps();
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(PackageHighlight::class)->orderBy('sort_order');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    public function inclusions(): HasMany
    {
        return $this->hasMany(PackageInclusion::class)->orderBy('sort_order');
    }

    public function packageAttractions(): HasMany
    {
        return $this->hasMany(PackageAttraction::class)->orderBy('sort_order');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(PackagePrice::class);
    }

    public function priceCalendar(): HasMany
    {
        return $this->hasMany(PriceCalendar::class);
    }

    public function cruise(): HasOne
    {
        return $this->hasOne(Cruise::class);
    }

    public function dealCampaigns(): HasMany
    {
        return $this->hasMany(DealCampaign::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function recommendedBy(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'recommended_package_id');
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable', 'translatable_type', 'translatable_id');
    }

    public function seoMeta(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'model', 'model_type', 'model_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayTitleAttribute(): string
    {
        return $this->translatedValue('title');
    }

    public function getDisplaySubtitleAttribute(): string
    {
        return $this->translatedValue('subtitle');
    }

    public function getDisplayShortDescriptionAttribute(): string
    {
        return $this->translatedValue('short_description');
    }

    public function getDisplayDescriptionAttribute(): string
    {
        return $this->translatedValue('description');
    }

    public function getDisplayScheduleTextAttribute(): string
    {
        return $this->translatedValue('schedule_text');
    }

    public function getDisplayPickupLocationAttribute(): string
    {
        return $this->translatedValue('pickup_location');
    }

    public function getDisplayDropoffLocationAttribute(): string
    {
        return $this->translatedValue('dropoff_location');
    }

    public function getDisplayDestinationsTextAttribute(): string
    {
        return $this->translatedValue('destinations_text');
    }

    public function getDisplayLocationSummaryAttribute(): string
    {
        return $this->translatedValue('location_summary');
    }

    public function getDisplayCancellationPolicyAttribute(): string
    {
        return $this->translatedValue('cancellation_policy');
    }

    public function getDisplayTermsConditionsAttribute(): string
    {
        return $this->translatedValue('terms_conditions');
    }

    public function getDisplaySeoTitleAttribute(): string
    {
        return $this->translatedValue('seo_title');
    }

    public function getDisplaySeoDescriptionAttribute(): string
    {
        return $this->translatedValue('seo_description');
    }

    public function getDisplayBreadcrumbTitleAttribute(): string
    {
        return $this->translatedValue('breadcrumb_title');
    }

    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->start_from_price === null) {
            return null;
        }

        $symbol = $this->currency?->symbol ?? '$';

        return $symbol . number_format((float) $this->start_from_price, 2);
    }
}
