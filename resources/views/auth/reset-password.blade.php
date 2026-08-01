@extends('layouts.app')

@section('title', 'Reset Password - Nazaara Circle')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(to bottom right, #1a1a1a, #0d0d0d, #000000);">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white" style="font-family: 'Poppins', sans-serif;">
                Reset your password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Enter your new password below
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form class="mt-8 space-y-6 bg-bg-card p-8 rounded-lg shadow-xl" action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        value="{{ $email ?? old('email') }}"
                        readonly
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-600 bg-bg-secondary/50 text-gray-400 placeholder-gray-500 rounded-lg cursor-not-allowed">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="new-password" 
                        required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-600 bg-bg-secondary text-white placeholder-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                        placeholder="Enter your new password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        autocomplete="new-password" 
                        required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-600 bg-bg-secondary text-white placeholder-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                        placeholder="Confirm your new password">
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-accent hover:bg-accent-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-300 shadow-lg">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

