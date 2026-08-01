@extends('layouts.app')

@section('title', $user->name . ' - Author Profile')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="bg-[#141414] min-h-screen pb-4">
    <!-- Hero Cover -->
    <div class="relative w-full h-[40vh] min-h-[350px] lg:h-[50vh] overflow-hidden group">
        @if($user->cover_image)
            <img src="{{ $user->cover_image }}" alt="{{ $user->name }}" class="w-full h-full object-cover transition-transform duration-[20s] group-hover:scale-105" onerror="this.style.display='none'">
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-900 via-[#0d0d0d] to-[#1a1a1a]"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        @endif
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-[#141414]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#141414]/30 to-transparent"></div>
    </div>

    <!-- Profile Info Container -->
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-32">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">
            
            <!-- Sidebar / Profile Card -->
            <div class="w-full lg:w-[350px] flex-shrink-0">
                <div class="bg-[#1a1a1a] rounded-3xl border border-white/10 p-6 shadow-2xl relative overflow-hidden">
                    <!-- Glow Effect -->
                    <div class="absolute top-0 right-0 w-2/3 h-2/3 bg-accent/5 blur-3xl rounded-full pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col items-center text-center">
                        <!-- Avatar -->
                        <div class="relative mb-6 group">
                            <div class="w-40 h-40 rounded-full p-1 bg-gradient-to-br from-white/20 to-white/5 shadow-2xl">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover border-4 border-[#1a1a1a] group-hover:scale-105 transition-transform duration-500">
                            </div>
                            @if($isFollowing ?? false)
                                <div class="absolute bottom-2 right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-[#1a1a1a] flex items-center justify-center text-white shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Name & Username -->
                        <h1 class="text-3xl font-black text-white mb-1 leading-tight" style="font-family: 'Poppins', sans-serif;">
                            {{ $user->name }}
                        </h1>
                        @if($user->username)
                            <p class="text-accent font-medium mb-4">@ {{ $user->username }}</p>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-3 w-full mb-8">
                            @auth
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="flex-1 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/5 hover:border-white/20 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </a>
                                @else
                                    <button id="followButton" 
                                            data-user-id="{{ $user->id }}"
                                            data-following="{{ $isFollowing ? 'true' : 'false' }}"
                                            class="flex-1 py-3 font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 {{ $isFollowing ? 'bg-white/10 hover:bg-white/20 text-white' : 'bg-accent hover:bg-red-700 text-white shadow-accent/20' }}">
                                        <span id="followButtonText">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                                    </button>
                                @endauth
                            @else
                                <a href="{{ route('login') }}" class="flex-1 py-3 bg-accent hover:bg-red-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-accent/20 flex items-center justify-center gap-2">
                                    Follow
                                </a>
                            @endauth
                        </div>

                        <!-- Bio -->
                        @if($user->bio)
                            <p class="text-gray-400 text-sm leading-relaxed mb-6 line-clamp-4">
                                {{ $user->bio }}
                            </p>
                        @endif

                        <!-- Meta Info -->
                        <div class="w-full space-y-3 pt-6 border-t border-white/5">
                            @if($user->location)
                                <div class="flex items-center text-gray-400 text-sm">
                                    <svg class="w-4 h-4 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $user->location }}
                                </div>
                            @endif
                            
                            @if($user->website || $user->twitter || $user->github || $user->linkedin)
                                <div class="flex items-center gap-4 pt-2 justify-center">
                                    @if($user->website)
                                        <a href="{{ $user->website }}" target="_blank" class="text-gray-400 hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg></a>
                                    @endif
                                    @if($user->twitter)
                                        <a href="https://twitter.com/{{ ltrim($user->twitter, '@') }}" target="_blank" class="text-gray-400 hover:text-[#1DA1F2] transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg></a>
                                    @endif
                                    <!-- Add other social icons similarly styled -->
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats Grid (Mobile/Sidebar) -->
                <div class="grid grid-cols-3 gap-2 mt-4">
                    <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-3 text-center">
                        <span class="block text-xl font-bold text-white">{{ number_format($stats['views']) }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Views</span>
                    </div>
                    <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-3 text-center">
                        <span class="block text-xl font-bold text-white">{{ number_format($stats['likes']) }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Likes</span>
                    </div>
                    <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-3 text-center">
                        <span class="block text-xl font-bold text-white">{{ number_format($stats['followers']) }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Fans</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 w-full min-w-0 pt-8 lg:pt-0">
                
                <!-- Navigation Tabs -->
                <div class="flex items-center gap-8 border-b border-white/10 mb-10 overflow-x-auto no-scrollbar">
                    <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="pb-4 border-b-2 border-accent text-white font-bold text-sm uppercase tracking-wide whitespace-nowrap">
                        Articles
                    </a>
                </div>

                <!-- Badges Section -->
                @if($badges->count() > 0)
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                            <span class="w-1 h-5 bg-accent rounded-sm"></span>
                            Achievements
                        </h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach($badges as $badge)
                                <div class="flex items-center gap-3 px-4 py-3 bg-[#1a1a1a] border border-white/5 rounded-xl hover:border-accent/30 transition-colors group cursor-default">
                                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                        {{ $badge->icon }}
                                    </div>
                                    <div>
                                        <span class="block text-white font-bold text-sm group-hover:text-accent transition-colors">{{ $badge->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $badge->description }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Articles Grid -->
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                            Latest Releases
                        </h2>
                    </div>

                    @if($recentArticles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($recentArticles as $article)
                                <!-- Custom Card Styling to ensure it matches theme -->
                                <a href="{{ route('articles.show', $article->slug) }}" class="group relative bg-[#1a1a1a] rounded-2xl overflow-hidden border border-white/5 hover:border-white/10 transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-accent/5">
                                    <div class="aspect-video relative overflow-hidden">
                                        <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1a1a] to-transparent opacity-80"></div>
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2 py-1 bg-accent text-white text-[10px] font-bold uppercase rounded shadow-lg">
                                                {{ $article->category->name ?? 'Article' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-accent transition-colors leading-tight" style="font-family: 'Poppins', sans-serif;">
                                            {{ $article->title }}
                                        </h3>
                                        <div class="flex items-center gap-4 text-xs text-gray-400 font-medium mt-4">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $article->reading_time }} min
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                {{ number_format($article->views) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20 bg-[#1a1a1a] rounded-3xl border border-white/5 border-dashed">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-1">No content yet</h3>
                            <p class="text-gray-500 text-sm">This creator hasn't published any articles.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    const followButton = document.getElementById('followButton');
    if (!followButton) return;

    followButton.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        const isFollowing = this.getAttribute('data-following') === 'true';

        this.disabled = true;

        fetch(`/profile/${userId}/toggle-follow`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.setAttribute('data-following', data.following ? 'true' : 'false');
                const buttonText = document.getElementById('followButtonText');
                if (buttonText) {
                    buttonText.textContent = data.following ? 'Following' : 'Follow';
                }
                
                if (data.following) {
                    this.className = "flex-1 py-3 font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white";
                } else {
                    this.className = "flex-1 py-3 font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 bg-accent hover:bg-red-700 text-white shadow-accent/20";
                }
            }
            this.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            this.disabled = false;
        });
    });
});
</script>
@endauth
@endpush
@endsection