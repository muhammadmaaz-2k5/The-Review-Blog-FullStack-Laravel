@extends('layouts.app')

@section('title', $pageTitle . ' - Coming Soon - Nazaara Circle')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="section-title">
            {{ $pageTitle }}
        </h1>

        <div class="content-card text-center">
            <!-- Coming Soon Icon -->
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-accent/10 mb-4">
                    <svg class="w-12 h-12 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Coming Soon Badge -->
            <div class="mb-6">
                <span class="inline-block px-4 py-2 bg-accent/20 text-accent rounded-full font-semibold text-sm uppercase tracking-wide">
                    Coming Soon
                </span>
            </div>

            <!-- Description -->
            <p class="text-gray-600 dark:!text-text-secondary text-lg mb-8 max-w-2xl mx-auto" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                {{ $pageDescription }}
            </p>

            <!-- Additional Info -->
            <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-6 mb-8">
                <p class="text-gray-700 dark:!text-text-secondary mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    We're constantly working to improve Nazaara Circle and add new features. This page will be available soon!
                </p>
                <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    In the meantime, feel free to explore our <a href="{{ route('articles.index') }}" class="text-accent hover:text-accent-light font-semibold underline">articles</a>, 
                    <a href="{{ route('categories.index') }}" class="text-accent hover:text-accent-light font-semibold underline">categories</a>, or 
                    <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light font-semibold underline">contact us</a> if you have any questions.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold transition-all hover:scale-105" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Go to Homepage
                </a>
                <a href="{{ route('articles.index') }}" class="inline-block px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:!bg-bg-card-hover dark:!hover:bg-bg-card text-gray-900 dark:!text-white rounded-lg font-semibold transition-all" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Browse Articles
                </a>
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
</div>
@endsection

