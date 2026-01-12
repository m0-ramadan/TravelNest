<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'key_name',
        'value',
        'data_type',
        'is_public',
        'display_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'display_order' => 'integer',
    ];

    // النطاقات
    public function scopeByGroup($query, $group)
    {
        return $query->where('group_name', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // السمات المحسوبة
    public function getValueAttribute($value)
    {
        switch ($this->data_type) {
            case 'integer':
                return intval($value);
            case 'float':
                return floatval($value);
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'array':
                return json_decode($value, true);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        if (in_array($this->data_type, ['array', 'json']) && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } elseif ($this->data_type === 'boolean') {
            $this->attributes['value'] = $value ? '1' : '0';
        } else {
            $this->attributes['value'] = strval($value);
        }
    }
}