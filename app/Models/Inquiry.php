<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inquiry extends Model
{
    protected $fillable = [
        'client_id', 'package_id', 'form_id', 'first_name', 'last_name', 'email', 'phone', 'country',
        'inquiry_type', 'subject', 'message', 'travel_date', 'adults', 'children', 'budget_min',
        'budget_max', 'currency_code', 'source', 'status', 'assigned_to', 'notes',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
    ];

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function form(): BelongsTo { return $this->belongsTo(Form::class); }
    public function assignedAdmin(): BelongsTo { return $this->belongsTo(Admin::class, 'assigned_to'); }
    public function booking(): HasOne { return $this->hasOne(Booking::class); }
    public function communications(): HasMany { return $this->hasMany(Communication::class); }
}
