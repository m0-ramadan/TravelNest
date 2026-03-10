<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'image_path',
        'is_active',

        'direction', // إضافة: rtl أو ltr
        'sort_order', // إضافة: ترتيب العرض
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
