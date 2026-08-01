@extends('layouts.app')

@section('title', '403 - Forbidden | Access Denied - Nazaara Circle')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gray-50 dark:bg-bg-primary">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <div class="inline-block mb-6">
                <svg class="w-32 h-32 mx-auto text-accent dark:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-9xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 800; line-height: 1;">
                403
            </h1>
            <div class="w-24 h-1 bg-accent mx-auto"></div>
        </div>
        
        <!-- Error Message -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Access Forbidden
        </h2>
        
        <p class="text-lg text-gray-600 dark:text-text-secondary mb-8 max-w-md mx-auto" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            You don't have permission to access this resource on the server. Access to this resource is denied!
        </p>
        
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-8 max-w-md mx-auto">
            <p class="text-sm text-yellow-800 dark:text-yellow-300" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                <strong>Possible reasons:</strong>
            </p>
            <ul class="text-sm text-yellow-700 dark:text-yellow-400 mt-2 text-left list-disc list-inside" style="font-family: 'Poppins', sans-serif;">
                <li>You don't have the required permissions</li>
                <li>The resource requires authentication</li>
                <li>The content has been restricted</li>
                <li>Your IP address may be blocked</li>
            </ul>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('home') }}" 
               class="px-6 py-3 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go to Homepage
            </a>
            <button onclick="window.history.back()" 
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-bg-card dark:hover:bg-bg-card-hover text-gray-900 dark:text-white rounded-lg transition-colors font-semibold"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go Back
            </button>
            @auth
            <a href="{{ route('user.dashboard') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-bg-card dark:hover:bg-bg-card-hover text-gray-900 dark:text-white rounded-lg transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Go to Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" 
               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-bg-card dark:hover:bg-bg-card-hover text-gray-900 dark:text-white rounded-lg transition-colors font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Login
            </a>
            @endauth
        </div>
        
        <!-- Help Section -->
        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-border-primary max-w-md mx-auto">
            <p class="text-sm text-gray-500 dark:text-text-muted mb-4" style="font-family: 'Poppins', sans-serif;">
                If you believe this is an error, please contact our support team.
            </p>
            <a href="{{ route('contact') }}" 
               class="text-accent hover:text-accent-light underline text-sm font-semibold"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection

