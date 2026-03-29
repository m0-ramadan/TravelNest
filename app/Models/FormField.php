<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $fillable = ['form_id', 'name', 'label', 'field_type', 'options_json', 'placeholder', 'is_required', 'sort_order'];

    protected $casts = [
        'options_json' => 'array',
        'is_required' => 'boolean',
    ];

    public function form(): BelongsTo { return $this->belongsTo(Form::class); }
}
