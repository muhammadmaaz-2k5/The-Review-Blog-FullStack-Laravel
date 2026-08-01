@extends('layouts.app')

@section('title', 'Verify Email - Nazaara Circle')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(to bottom right, #1a1a1a, #0d0d0d, #000000);">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-bg-card p-8 rounded-lg shadow-xl">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white" style="font-family: 'Poppins', sans-serif;">
                    Verify Your Email Address
                </h2>
                <p class="mt-4 text-center text-sm text-gray-400">
                    Before proceeding, please check your email for a verification link. If you didn't receive the email, we'll gladly send you another.
                </p>
            </div>

            @if(session('success'))
                <div class="mt-4 bg-green-500/20 border border-green-500 text-green-200 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mt-4 bg-blue-500/20 border border-blue-500 text-blue-200 px-4 py-3 rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            <div class="mt-6">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-accent hover:bg-accent-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-300 shadow-lg">
                        Resend Verification Email
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit" 
                        class="text-sm text-gray-400 hover:text-gray-300 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

