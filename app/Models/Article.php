<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'image_alt',
        'category_id',
        'author_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'views_count',
        'reading_time',
        'published_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'title' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
    ];

    // Accessor للحصول على العنوان حسب اللغة
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = json_decode($value, true) ?? [];
                $lang = app()->getLocale();
                return $data[$lang] ?? $data['ar'] ?? '';
            }
        );
    }

    // Accessor للحصول على المحتوى حسب اللغة
    protected function content(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = json_decode($value, true) ?? [];
                $lang = app()->getLocale();
                return $data[$lang] ?? $data['ar'] ?? '';
            }
        );
    }

    // Accessor للحصول على الملخص حسب اللغة
    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = json_decode($value, true) ?? [];
                $lang = app()->getLocale();
                return $data[$lang] ?? $data['ar'] ?? '';
            }
        );
    }

    // Accessor للحصول على meta_title حسب اللغة
    protected function metaTitle(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = json_decode($value, true) ?? [];
                $lang = app()->getLocale();
                return $data[$lang] ?? $data['ar'] ?? '';
            }
        );
    }

    // Accessor للحصول على meta_description حسب اللغة
    protected function metaDescription(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = json_decode($value, true) ?? [];
                $lang = app()->getLocale();
                return $data[$lang] ?? $data['ar'] ?? '';
            }
        );
    }

    // Accessor للحصول على meta_keywords حسب اللغة
    protected function metaKeywords(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = json_decode($value, true) ?? [];
                $lang = app()->getLocale();
                return $data[$lang] ?? $data['ar'] ?? '';
            }
        );
    }

    // دالة للحصول على ترجمة محددة
    public function getTranslation($field, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        $data = $this->attributes[$field] ?? null;
        
        if ($data) {
            $decoded = json_decode($data, true);
            return $decoded[$lang] ?? $decoded['ar'] ?? null;
        }
        
        return null;
    }

    // دالة لتعيين ترجمة
    public function setTranslation($field, $lang, $value)
    {
        $data = $this->attributes[$field] ?? '{}';
        $decoded = json_decode($data, true) ?? [];
        $decoded[$lang] = $value;
        $this->attributes[$field] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
    }

    // دالة للحصول على جميع الترجمات لحقل معين
    public function getAllTranslations($field)
    {
        $data = $this->attributes[$field] ?? null;
        return $data ? json_decode($data, true) : [];
    }

    // العلاقة مع التصنيف
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    // العلاقة مع المؤلف
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // العلاقة مع التاغات
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // العلاقة مع التعليقات
    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    // Scope للمقالات النشطة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope للمقالات المميزة
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope للمقالات المنشورة
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    // حساب وقت القراءة
    public function calculateReadingTime($content = null)
    {
        $content = $content ?? $this->content;
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200);
        return $readingTime > 0 ? $readingTime : 1;
    }
}