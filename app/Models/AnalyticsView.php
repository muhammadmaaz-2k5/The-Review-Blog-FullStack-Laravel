<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AnalyticsView extends Model
{
    protected $table = 'analytics_views';

    protected $fillable = [
        'session_id',
        'page_path',
        'page_title',
        'viewable_id',
        'viewable_type',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'screen_resolution',
        'time_on_page',
        'is_bounce',
        'viewed_at',
    ];

    protected $casts = [
        'is_bounce' => 'boolean',
        'time_on_page' => 'integer',
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the viewable model (article, category, etc.)
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
