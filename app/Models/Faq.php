<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'faqable_type',
        'faqable_id',
        'display_order',
        'views_count',
        'helpful_yes',
        'helpful_no',
        'active',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'views_count' => 'integer',
        'helpful_yes' => 'integer',
        'helpful_no' => 'integer',
        'active' => 'boolean',
    ];

    // العلاقات
    public function faqable()
    {
        return $this->morphTo();
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('question');
    }

    // السمات المحسوبة
    public function getHelpfulPercentageAttribute()
    {
        $total = $this->helpful_yes + $this->helpful_no;
        if ($total > 0) {
            return round(($this->helpful_yes / $total) * 100, 1);
        }
        return 0;
    }
}