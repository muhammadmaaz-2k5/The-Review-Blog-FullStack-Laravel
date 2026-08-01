@extends('layouts.app')

@section('title', '401 - Unauthorized')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12" style="background: linear-gradient(to bottom right, #1a1a1a, #0d0d0d, #000000);">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 800; line-height: 1;">
                401
            </h1>
            <div class="w-24 h-1 bg-accent mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Unauthorized Access
        </h2>
        
        <p class="text-lg text-gray-300 mb-8 max-w-md mx-auto" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            You need to be authenticated to access this resource. Please log in to continue.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('login') }}" 
               class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Log In
            </a>
            <a href="{{ route('home') }}" 
               class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go to Homepage
            </a>
        </div>
    </div>
</div>
@endsection

