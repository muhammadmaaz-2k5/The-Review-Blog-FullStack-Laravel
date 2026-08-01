@extends('layouts.app')

@section('title', 'Login - Nazaara Circle')

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-[#141414]">
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-[#000000] via-[#1a1a1a] to-[#000000] opacity-90"></div>
        
        <!-- Animated Glows -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-accent/20 blur-[100px] rounded-full opacity-50 animate-pulse"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-900/20 blur-[100px] rounded-full opacity-50 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10 flex flex-col items-center">
        <div class="text-center w-full">
            <h2 class="mt-6 text-center text-4xl font-black text-white uppercase tracking-tighter" style="font-family: 'Poppins', sans-serif;">
                Welcome <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-500">Back</span>
            </h2>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-lg shadow-green-900/10 w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-lg shadow-red-900/10 w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div id="clerk-sign-in"></div>
        <div id="clerk-loading" class="text-white text-center py-4">
            <span class="inline-block animate-spin text-accent">⏳</span> Loading authentication...
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('load', async function () {
        if (window.Clerk) {
            await Clerk.load();
            
            const loadingIndicator = document.getElementById('clerk-loading');
            const signInDiv = document.getElementById('clerk-sign-in');
            
            if (loadingIndicator) loadingIndicator.style.display = 'none';

            // Mount Clerk Sign In UI
            Clerk.mountSignIn(signInDiv, {
                afterSignInUrl: window.location.href, // It will reload, we'll intercept it below
                signUpUrl: "{{ route('register') }}"
            });

            // Listen for Clerk state changes
            Clerk.addListener(async (e) => {
                // If a user just signed in and we have a session
                if (e.user && e.session) {
                    try {
                        const token = await e.session.getToken();
                        const email = e.user.primaryEmailAddress ? e.user.primaryEmailAddress.emailAddress : null;
                        const name = e.user.fullName || e.user.firstName || 'User';

                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) throw new Error('CSRF token not found');

                        // Show loading state
                        signInDiv.innerHTML = '<div class="text-white text-center p-8 bg-black/40 rounded-xl border border-white/10"><span class="inline-block animate-spin text-accent text-2xl mb-4">⏳</span><p>Synchronizing your session...</p></div>';

                        const response = await fetch('/auth/clerk', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                id_token: token,
                                email: email,
                                name: name
                            }),
                        });

                        if (!response.ok) {
                            throw new Error('Failed to synchronize session with backend');
                        }

                        const data = await response.json();
                        
                        if (data.success) {
                            window.location.href = data.redirect || '/';
                        } else {
                            throw new Error(data.message || 'Authentication failed');
                        }

                    } catch (error) {
                        console.error('Session sync error:', error);
                        signInDiv.innerHTML = `<div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-center"><p class="font-bold">Error synchronizing session.</p><p class="text-sm mt-2">${error.message}</p><button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Try Again</button></div>`;
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
