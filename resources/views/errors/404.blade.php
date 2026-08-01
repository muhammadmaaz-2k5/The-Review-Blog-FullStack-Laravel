@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12" style="background: linear-gradient(to bottom right, #1a1a1a, #0d0d0d, #000000);">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 800; line-height: 1;">
                404
            </h1>
            <div class="w-24 h-1 bg-accent mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Page Not Found
        </h2>
        
        <p class="text-lg text-gray-300 mb-8 max-w-md mx-auto" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Oops! The page you're looking for doesn't exist. It might have been moved, deleted, or the URL might be incorrect.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('home') }}" 
               class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go to Homepage
            </a>
            <button onclick="window.history.back()" 
                    class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go Back
            </button>
        </div>
        
        <!-- Search -->
        <div class="mt-12 max-w-md mx-auto">
            <p class="text-gray-400 mb-4 text-sm" style="font-family: 'Poppins', sans-serif;">
                Or search for what you're looking for:
            </p>
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search articles..." 
                    class="w-full px-4 py-3 pr-12 text-gray-800 bg-white border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent/50"
                    style="font-family: 'Poppins', sans-serif;"
                    autocomplete="off"
                >
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-gray-600 hover:text-accent">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

