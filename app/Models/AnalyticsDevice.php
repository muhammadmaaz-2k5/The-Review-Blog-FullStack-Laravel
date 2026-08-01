<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsDevice extends Model
{
    protected $table = 'analytics_devices';

    protected $fillable = [
        'device_type',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'screen_resolution',
        'is_mobile',
        'is_tablet',
        'is_desktop',
        'visits_count',
        'unique_visitors',
        'page_views',
        'first_seen_at',
        'last_seen_at',
    ];

    protected $casts = [
        'is_mobile' => 'boolean',
        'is_tablet' => 'boolean',
        'is_desktop' => 'boolean',
        'visits_count' => 'integer',
        'unique_visitors' => 'integer',
        'page_views' => 'integer',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}
