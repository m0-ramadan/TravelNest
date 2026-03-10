<?php

namespace App\Models;

use App\Models\ContactUs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUsReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contact_us_replies';

    protected $fillable = [
        'contact_us_id',
        'user_id',
        'message',
        'sender_type', // admin|user
    ];

    protected $casts = [
        'contact_us_id' => 'integer',
        'user_id'       => 'integer',
        'sender_type'   => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /* =========================
     | Relationships
     ========================= */

    public function contactUs()
    {
        return $this->belongsTo(ContactUs::class, 'contact_us_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* =========================
     | Scopes (optional)
     ========================= */

    public function scopeAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }

    public function scopeUser($query)
    {
        return $query->where('sender_type', 'user');
    }
}
