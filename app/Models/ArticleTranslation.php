<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'language_code',
        'title',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    // العلاقة مع المقال
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // العلاقة مع اللغة
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }
}