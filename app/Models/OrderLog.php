<?php
// app/Models/OrderLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderLog extends Model
{
    use HasFactory;

    protected $table = 'order_logs';

    protected $fillable = [
        'order_id',
        'action',
        'provider',
        'request_data',
        'response_data',
        'error_message',
        'ip_address',
        'user_agent',
        'created_by',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
