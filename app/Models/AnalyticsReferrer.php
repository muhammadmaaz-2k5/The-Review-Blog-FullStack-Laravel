<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsReferrer extends Model
{
    protected $table = 'analytics_referrers';

    protected $fillable = [
        'referrer_url',
        'referrer_domain',
        'referrer_type',
        'search_engine',
        'search_query',
        'social_platform',
        'visits_count',
        'unique_visitors',
        'first_seen_at',
        'last_seen_at',
    ];

    protected $casts = [
        'visits_count' => 'integer',
        'unique_visitors' => 'integer',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}
