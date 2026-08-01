@extends('layouts.app')

@section('title', 'RSS Feeds - Nazaara Circle')

@section('content')
<style>
    .section-title {
        font-size: 32px;
        font-weight: 800;
        margin: 15px 0 25px;
        color: #000;
        text-transform: uppercase;
        letter-spacing: -0.5px;
        font-family: 'Poppins', sans-serif;
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 60%;
        height: 4px;
        background: #E50914;
        margin-top: 5px;
    }
    .dark .section-title {
        color: #fff;
    }
    .content-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }
    .dark .content-card {
        background: #1a1a1a;
        border-color: #333;
    }
    .rss-link {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 6px;
        color: #333;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 10px;
        width: 100%;
    }
    .dark .rss-link {
        background-color: #2d2d2d;
        border-color: #444;
        color: #ddd;
    }
    .rss-link:hover {
        background-color: #E50914;
        border-color: #E50914;
        color: #fff;
    }
    .rss-icon {
        margin-right: 10px;
        color: #E50914;
    }
    .rss-link:hover .rss-icon {
        color: #fff;
    }
    .who-we-are {
        background-color: #000;
        color: #fff;
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        margin-top: 50px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .who-we-are::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #E50914, #ff4d4d);
    }
    .who-title {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 1px;
    }
    .who-text {
        font-size: 16px;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto 25px;
        color: #ccc;
    }
    .who-btn {
        display: inline-block;
        background: #E50914;
        color: #fff;
        padding: 10px 25px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 4px;
        transition: background 0.3s;
    }
    .who-btn:hover {
        background: #ff1f2a;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="section-title">
            RSS Feeds
        </h1>
        <p class="text-gray-600 dark:text-text-secondary text-lg" style="font-family: 'Poppins', sans-serif;">
            Subscribe to Nazaara Circle content using your favorite RSS reader. Stay updated with our latest entertainment news, drama reviews, and celebrity biographies.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Main Feed -->
        <div class="content-card">
            <h2 class="text-2xl font-bold mb-4 dark:text-white font-poppins">Main Feed</h2>
            <p class="mb-6 text-gray-600 dark:text-gray-400">
                The main feed contains all latest entertainment updates, drama reviews, and celebrity news from Nazaara Circle.
            </p>
            <a href="{{ route('feed') }}" target="_blank" class="rss-link">
                <svg class="rss-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 3a1 1 0 000 2c5.523 0 10 4.477 10 10a1 1 0 102 0C17 8.373 11.627 3 5 3z"></path><path d="M4 9a1 1 0 011-1 7 7 0 017 7 1 1 0 11-2 0 5 5 0 00-5-5 1 1 0 01-1-1zM3 15a2 2 0 114 0 2 2 0 01-4 0z"></path></svg>
                Main RSS Feed
            </a>
        </div>

        <!-- Category Feeds -->
        <div class="content-card">
            <h2 class="text-2xl font-bold mb-4 dark:text-white font-poppins">Category Feeds</h2>
            <p class="mb-6 text-gray-600 dark:text-gray-400">
                Subscribe to specific categories to get updates only on topics you care about.
            </p>
            <div class="space-y-2">
                @foreach($categories as $category)
                <a href="{{ route('feed.category', $category->slug) }}" target="_blank" class="rss-link">
                    <svg class="rss-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 3a1 1 0 000 2c5.523 0 10 4.477 10 10a1 1 0 102 0C17 8.373 11.627 3 5 3z"></path><path d="M4 9a1 1 0 011-1 7 7 0 017 7 1 1 0 11-2 0 5 5 0 00-5-5 1 1 0 01-1-1zM3 15a2 2 0 114 0 2 2 0 01-4 0z"></path></svg>
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Who We Are Section -->
    <div class="who-we-are">
        <h2 class="who-title">Who We Are</h2>
        <p class="who-text">
            Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
        </p>
        <a href="{{ route('about') }}" class="who-btn">Read More About Us</a>
    </div>
</div>
@endsection
