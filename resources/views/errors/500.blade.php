@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12" style="background: linear-gradient(to bottom right, #1a1a1a, #0d0d0d, #000000);">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 800; line-height: 1;">
                500
            </h1>
            <div class="w-24 h-1 bg-accent mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Server Error
        </h2>
        
        <p class="text-lg text-gray-300 mb-8 max-w-md mx-auto" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Something went wrong on our end. We're working to fix the issue. Please try again later.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('home') }}" 
               class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go to Homepage
            </a>
            <button onclick="window.location.reload()" 
                    class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Try Again
            </button>
        </div>
    </div>
</div>
@endsection

