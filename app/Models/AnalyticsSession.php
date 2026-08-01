<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsSession extends Model
{
    protected $table = 'analytics_sessions';

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'entry_page',
        'exit_page',
        'page_views',
        'duration',
        'is_bounce',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'is_bounce' => 'boolean',
        'page_views' => 'integer',
        'duration' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
