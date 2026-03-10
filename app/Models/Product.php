<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Favorite;
use App\Models\Feature;
use App\Models\Image;
use App\Models\Offer;
use App\Models\ProductTextAd;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Language;

class Product extends Model
{
    use SoftDeletes, HasTranslations;

    /**
     * الحقول القابلة للترجمة - تدعم عدد لا نهائي من اللغات
     */
    public array $translatable = [
        'name',
        'description',
        'price_text',
        'meta_title',
        'meta_description',
        'meta_keywords',
        //'instructions',
        // 'terms'
    ];

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'price_text',
        'has_discount',
        'includes_tax',
        'includes_shipping',
        'stock',
        'status_id',
        'image_path',
        'sku',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // 'instructions',
        'terms',
        'created_by'
    ];

    protected $casts = [
        'has_discount' => 'boolean',
        'includes_tax' => 'boolean',
        'includes_shipping' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // =================================================================
    // العلاقات
    // =================================================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }

    public function adsText()
    {
        return $this->hasMany(ProductTextAd::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function favorites()
    {
        return $this->hasMany(Favourite::class, 'product_id');
    }

    public function favouritedBy()
    {
        return $this->belongsToMany(\App\Models\User::class, 'favourites');
    }

    public function primaryImage()
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', 'main');
    }

    // =================================================================
    // Accessors & Mutators
    // =================================================================

    public function getFinalPriceAttribute()
    {
        if ($this->has_discount && $this->discount) {
            if ($this->discount->discount_type === 'percentage') {
                return $this->price - ($this->price * $this->discount->discount_value / 100);
            }
            return $this->price - $this->discount->discount_value;
        }
        return $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * الحصول على قيمة بلغة محددة
     */
    public function getTranslatedValue(string $attribute, ?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        if (!in_array($attribute, $this->translatable)) {
            return $this->attributes[$attribute] ?? null;
        }

        return $this->getTranslation($attribute, $locale);
    }

    /**
     * التحقق من وجود ترجمة للغة محددة
     */
    public function hasTranslation(string $attribute, string $locale): bool
    {
        if (!in_array($attribute, $this->translatable)) {
            return true;
        }

        $translations = $this->getTranslations($attribute);
        return isset($translations[$locale]) && !empty($translations[$locale]);
    }

    /**
     * الحصول على جميع الترجمات لحقل معين
     */
    public function getAllTranslations(string $attribute): array
    {
        if (!in_array($attribute, $this->translatable)) {
            return [$attribute => $this->attributes[$attribute] ?? null];
        }

        return $this->getTranslations($attribute);
    }

    // =================================================================
    // Scopes
    // =================================================================

    public function scopeFiltered($query, Request $request)
    {
        $filters = ['category_id', 'status_id'];
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->get($filter));
            }
        }

        if ($request->boolean('has_discount')) {
            $query->whereHas('discount', fn($q) => $q->where('is_active', true));
        }

        $priceFrom = $request->get('price_from');
        $priceTo = $request->get('price_to');

        if ($priceFrom && $priceTo) {
            $query->whereBetween('price', [$priceFrom, $priceTo]);
        } elseif ($priceFrom) {
            $query->where('price', '>=', $priceFrom);
        } elseif ($priceTo) {
            $query->where('price', '<=', $priceTo);
        }

        return $query;
    }


    public function scopeSearched($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {

            // تنظيف النص
            $search = str_replace("\xC2\xA0", ' ', $search);
            $search = trim(preg_replace('/\s+/u', ' ', $search));
            $search = mb_strtolower($search); // 👈 مهم

            $codes = Cache::remember('active_language_codes', 3600, function () {
                return Language::where('is_active', 1)->pluck('code')->toArray();
            });

            $q->where(function ($qq) use ($search, $codes) {
                foreach ((new static)->translatable as $field) {
                    foreach ($codes as $code) {

                        $qq->orWhereRaw(
                            "LOWER(JSON_UNQUOTE(JSON_EXTRACT(`$field`, ?))) LIKE ?",
                            ['$."' . $code . '"', "%{$search}%"]
                        );
                    }
                }
            });
        });
    }
    public function scopeSorted($query, Request $request)
    {
        $sortableFields = ['id', 'name', 'price', 'stock', 'created_at'];
        $sortBy = $request->get('sort_by', 'id');
        $direction = $request->get('sort_direction', 'desc');

        if (in_array($sortBy, $sortableFields)) {
            return $query->orderBy($sortBy, $direction);
        }

        return $query->orderBy('id', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // =================================================================
    // Route Binding
    // =================================================================

    public function resolveRouteBinding($value, $field = null)
    {
        $product = parent::resolveRouteBinding($value, $field);

        if (!$product || $product->trashed() || $product->status_id != 1) {
            abort(404, 'المنتج غير موجود أو غير متاح');
        }

        return $product;
    }
}
