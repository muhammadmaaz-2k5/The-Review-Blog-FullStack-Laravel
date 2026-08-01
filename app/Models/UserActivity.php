<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'subject_id',
        'subject_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false; // We only use created_at

    /**
     * Get the user who performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity (polymorphic)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create an activity record
     */
    public static function log(int $userId, string $type, string $description, $subject = null, array $metadata = []): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'subject_id' => $subject ? $subject->id : null,
            'subject_type' => $subject ? get_class($subject) : null,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
