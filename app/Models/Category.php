<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    /**
     * الحقول القابلة للترجمة - هذا كل ما تحتاجه!
     */
    public array $translatable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'policies'
    ];

    protected $fillable = [
        'external_id',
        'name',
        'slug',
        'description',
        'parent_id',
        'order',
        'status_id',
        'image',
        'sub_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'appear_in_home',
        'policies'
    ];

    protected $casts = [
        'policies' => 'array', // فقط للسياسات إذا كانت array
    ];

    protected $appends = ['full_slug'];

public static function normalizeOrders(): int
{
    return DB::transaction(function () {

        // هنجمع كل الأقسام مرتبة بحيث نحافظ على ترتيب قديم قدر الإمكان
        $categories = static::query()
            ->orderByRaw("CASE WHEN `order` IS NULL OR `order` = 0 THEN 1 ELSE 0 END") // اللي order=0 في الآخر
            ->orderBy('order')
            ->orderBy('id')
            ->get(['id', 'order']);

        $i = 1;
        $updated = 0;

        foreach ($categories as $cat) {
            if ((int) $cat->order !== $i) {
                static::where('id', $cat->id)->update(['order' => $i]);
                $updated++;
            }
            $i++;
        }

        return $updated;
    });
}
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                // الحصول على الاسم الإنجليزي أو العربي للـ slug
                $name = $category->getTranslation('name', 'en') ?? $category->getTranslation('name', 'ar') ?? 'category';
                $category->slug = Str::slug($name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $name = $category->getTranslation('name', 'en') ?? $category->getTranslation('name', 'ar') ?? 'category';
                $category->slug = Str::slug($name);
            }
        });
    }

    public function categoryBanners()
    {
        return $this->hasMany(BannerItem::class, 'category_id');
    }

    public function getInstructionsAttribute()
    {
        $policies = $this->policies ?? [];
        $lang = app()->getLocale();

        if (isset($policies['instructions'])) {
            if (is_array($policies['instructions'])) {
                return $policies['instructions'][$lang]
                    ?? $policies['instructions']['ar']
                    ?? reset($policies['instructions']);
            }
            return $policies['instructions'];
        }

        return null;
    }

    /**
     * التحقق مما إذا كان القسم رئيسياً
     */
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * العلاقة مع القسم الرئيسي
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * العلاقة مع الأقسام الفرعية
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    /**
     * العلاقة مع المنتجات
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * الحصول على اسم القسم مع الرابط الكامل
     */
    public function getFullPathAttribute()
    {
        $path = [$this->getTranslation('name', 'ar')];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->getTranslation('name', 'ar'));
            $parent = $parent->parent;
        }

        return implode(' → ', $path);
    }

    /**
     * الحصول على الرابط الكامل (slug)
     */
    public function getFullSlugAttribute()
    {
        if ($this->parent) {
            return $this->parent->slug . '/' . $this->slug;
        }
        return $this->slug;
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * الحصول على رابط الصورة الفرعية
     */
    public function getSubImageUrlAttribute()
    {
        return $this->sub_image ? asset('storage/' . $this->sub_image) : null;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeWithChildren($query)
    {
        return $query->with(['children' => function ($q) {
            $q->orderBy('order')->withCount('products');
        }]);
    }
    public function scopeEmpty($query)
{
    return $query->whereDoesntHave('products')
                 ->whereDoesntHave('children');
}
public static function disableEmptyCategories(): int
{
    return static::query()
        ->whereDoesntHave('products')
        ->whereDoesntHave('children')
        ->where('status_id', '!=', 0)
        ->update(['status_id' => 0]);
}
}
