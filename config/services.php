<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'tmdb' => [
        'api_key' => env('TMDB_API_KEY'),
        'access_token' => env('TMDB_ACCESS_TOKEN'),
        'base_url' => 'https://api.themoviedb.org/3',
        'image_base_url' => 'https://image.tmdb.org/t/p',
    ],

    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/firebase-service-account.json')),
        'api_key' => env('FIREBASE_API_KEY'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'app_id' => env('FIREBASE_APP_ID'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID'),
    ],

    'clerk' => [
        'publishable_key' => env('CLERK_PUBLISHABLE_KEY'),
        'secret_key' => env('CLERK_SECRET_KEY'),
        'frontend_api_url' => env('CLERK_FRONTEND_API_URL'),
        'backend_api_url' => env('CLERK_BACKEND_API_URL'),
        'jwks_url' => env('CLERK_JWKS_URL'),
        'jwks_public_key' => env('CLERK_JWKS_PUBLIC_KEY'),
    ],

    'facebook' => [
        'enabled' => env('FACEBOOK_ENABLED', false),
        'page_id' => env('FACEBOOK_PAGE_ID'),
        'page_access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN'),
        'api_version' => env('FACEBOOK_API_VERSION', 'v18.0'),
        'profile_id' => env('FACEBOOK_PROFILE_ID', '61585279116089'),
    ],

    'twitter' => [
        'enabled' => env('TWITTER_ENABLED', false),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
        'api_version' => env('TWITTER_API_VERSION', '2'),
    ],

    'instagram' => [
        'enabled' => env('INSTAGRAM_ENABLED', false),
        'page_id' => env('INSTAGRAM_PAGE_ID'),
        'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),
        'api_version' => env('INSTAGRAM_API_VERSION', 'v18.0'),
        'profile_url' => env('INSTAGRAM_PROFILE_URL', 'https://www.instagram.com/nazaaracircle'),
    ],

    'threads' => [
        'enabled' => env('THREADS_ENABLED', false),
        'page_id' => env('THREADS_PAGE_ID'),
        'access_token' => env('THREADS_ACCESS_TOKEN'),
        'api_version' => env('THREADS_API_VERSION', 'v18.0'),
    ],


    'youtube' => [
        'api_key' => env('YOUTUBE_API_KEY'),
        'channel_id' => env('YOUTUBE_CHANNEL_ID', 'UCOiiIYdcKBeMFDCa42iylmA'),
        'client_id' => env('YOUTUBE_CLIENT_ID', ''),
        'client_secret' => env('YOUTUBE_CLIENT_SECRET', ''),
    ],

    'google' => [
        'analytics_id' => env('GOOGLE_ANALYTICS_ID', 'G-ZK9NE04CFK'),
        'tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID', 'GTM-TV3ZRBXN'),
        'search_console_verification' => env('GOOGLE_SEARCH_CONSOLE_VERIFICATION', ''),
        'site_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    ],

];
