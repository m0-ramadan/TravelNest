<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // النطاقات
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    public function scopeBySubject($query, $type, $id)
    {
        return $query->where('subject_type', $type)
            ->where('subject_id', $id);
    }

    // الأحداث
    public static function log($activityType, $description, $subject = null, $metadata = [])
    {
        $user = auth()->user();
        
        $log = new self();
        $log->user_id = $user ? $user->id : null;
        $log->activity_type = $activityType;
        $log->description = $description;
        
        if ($subject) {
            $log->subject_type = get_class($subject);
            $log->subject_id = $subject->id;
        }
        
        $log->ip_address = request()->ip();
        $log->user_agent = request()->userAgent();
        $log->metadata = $metadata;
        
        $log->save();
        
        return $log;
    }
}