<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory;

    // ✅ فعل SoftDeletes فقط لو فعلاً عندك deleted_at في جدول contact_us
    // use SoftDeletes;

    protected $table = 'contact_us';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'company',
        'message',
        'user_id',
        'status',
    ];

    protected $casts = [
        'user_id'    => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime', // لو فعلت SoftDeletes
    ];

    /* =========================
     | Relationships
     ========================= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ContactUsReply::class, 'contact_us_id');
    }

    public function lastReply()
    {
        // Laravel 8+ : أحدث رد كـ relation
        return $this->hasOne(ContactUsReply::class, 'contact_us_id')->latestOfMany();
    }

    /* =========================
     | Helpers
     ========================= */

    /**
     * Add reply to this contact message.
     *
     * @param  int    $userId
     * @param  string $message
     * @param  string $senderType  admin|user
     */
    public function addReply(int $userId, string $message, string $senderType = 'admin')
    {
        if (!in_array($senderType, ['admin', 'user'], true)) {
            throw new \InvalidArgumentException('senderType must be admin or user');
        }

        return $this->replies()->create([
            'user_id'     => $userId,
            'message'     => $message,
            'sender_type' => $senderType,
        ]);
    }

    /* =========================
     | Accessors (optional)
     ========================= */

    public function getRepliesCountAttribute(): int
    {
        // لو محمّل replies مسبقًا هتكون أسرع
        if ($this->relationLoaded('replies')) {
            return $this->replies->count();
        }

        return $this->replies()->count();
    }
}
