<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedVideo extends Model
{
    protected $fillable = [
        'title',
        'youtube_url',
        'is_active',
        'views',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views' => 'integer',
    ];
}
