<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'page_type',
        'parent_id',
        'template',
        'featured_image',
        'gallery',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'author_id',
        'status',
        'published_at',
        'views_count',
        'display_order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'display_order' => 'integer',
    ];

    // العلاقات
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // النطاقات
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public scopeBlogPosts($query)
    {
        return $query->where('page_type', 'blog')->published();
    }

    public function scopeByType($query, $type)
    {
        return $query->where('page_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('title');
    }

    // السمات المحسوبة
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }
        return asset('images/default-page.jpg');
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && 
               $this->published_at <= now();
    }

    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200);
        return $minutes . ' min read';
    }
}