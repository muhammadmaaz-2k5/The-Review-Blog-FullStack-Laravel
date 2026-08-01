<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AnalyticsEvent extends Model
{
    protected $table = 'analytics_events';

    protected $fillable = [
        'session_id',
        'event_name',
        'event_category',
        'event_action',
        'event_label',
        'eventable_id',
        'eventable_type',
        'user_id',
        'page_path',
        'ip_address',
        'metadata',
        'value',
        'occurred_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'value' => 'integer',
        'occurred_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the eventable model
     */
    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }
}
