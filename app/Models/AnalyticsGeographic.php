<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsGeographic extends Model
{
    protected $table = 'analytics_geographic';

    protected $fillable = [
        'country_code',
        'country_name',
        'region',
        'city',
        'latitude',
        'longitude',
        'visits_count',
        'unique_visitors',
        'page_views',
        'first_seen_at',
        'last_seen_at',
    ];

    protected $casts = [
        'visits_count' => 'integer',
        'unique_visitors' => 'integer',
        'page_views' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}
