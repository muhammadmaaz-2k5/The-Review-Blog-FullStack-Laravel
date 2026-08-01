@extends('layouts.app')

@section('title', '429 - Too Many Requests')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12" style="background: linear-gradient(to bottom right, #1a1a1a, #0d0d0d, #000000);">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 800; line-height: 1;">
                429
            </h1>
            <div class="w-24 h-1 bg-accent mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Too Many Requests
        </h2>
        
        <p class="text-lg text-gray-300 mb-8 max-w-md mx-auto" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            You've made too many requests in a short period. Please wait a moment before trying again.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="setTimeout(() => window.location.reload(), 5000)" 
                    class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors font-semibold"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Wait & Retry
            </button>
            <a href="{{ route('home') }}" 
               class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go to Homepage
            </a>
        </div>
    </div>
</div>
@endsection

