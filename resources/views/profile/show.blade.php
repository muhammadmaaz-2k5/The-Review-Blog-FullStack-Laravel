@extends('layouts.app')

@section('title', $user->name . ' - Author Profile')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- AI/GEO SEO: Structured Data -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $user->name,
    'url' => route('profile.show', $user->username),
    'image' => $user->profile_photo_url,
    'description' => $user->bio ?? $user->name . ' is an author at ' . config('app.name'),
    'jobTitle' => $user->isAuthor() ? 'Author' : 'Member',
    'worksFor' => [
        '@type' => 'Organization',
        'name' => config('app.name')
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
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
                        <div class="flex items-center gap-2 mb-1 justify-center lg:justify-start">
                            <h1 class="text-3xl font-black text-white leading-tight" style="font-family: 'Poppins', sans-serif;">
                                {{ $user->name }}
                            </h1>
                            @if($user->isAuthor())
                                <div class="bg-accent text-white p-1 rounded-full shadow-[0_0_10px_rgba(var(--color-accent),0.5)]" title="Verified Author">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        @if($user->username)
                            <p class="text-accent font-medium mb-4">@ {{ $user->username }}</p>
                        @endif

                        <!-- Social Links (E-E-A-T) -->
                        @if($user->website || $user->twitter || $user->linkedin || $user->github)
                            <div class="flex items-center gap-3 mb-6 justify-center lg:justify-start">
                                @if($user->website)
                                    <a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-white/10 hover:bg-accent flex items-center justify-center text-white transition-colors" title="Personal Website">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    </a>
                                @endif
                                @if($user->twitter)
                                    <a href="{{ $user->twitter }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#1DA1F2] flex items-center justify-center text-white transition-colors" title="Twitter/X">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                    </a>
                                @endif
                                @if($user->linkedin)
                                    <a href="{{ $user->linkedin }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#0077B5] flex items-center justify-center text-white transition-colors" title="LinkedIn">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                @endif
                            </div>
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
                                    <div id="react-follow-button-root" 
                                         data-user-id="{{ $user->id }}" 
                                         data-following="{{ $isFollowing ? 'true' : 'false' }}"
                                         data-logged-in="true"
                                         class="flex-1 flex">
                                    </div>
                                @endif
                            @else
                                <div id="react-follow-button-root" 
                                     data-user-id="{{ $user->id }}" 
                                     data-following="false"
                                     data-logged-in="false"
                                     class="flex-1 flex">
                                </div>
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
                        <span id="followersCount" class="block text-xl font-bold text-white">{{ number_format($stats['followers']) }}</span>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="articles-container">
                            @foreach($recentArticles as $article)
                                @include('profile._article_card', ['article' => $article])
                            @endforeach
                        </div>
                        
                        <!-- Load More Trigger -->
                        <div id="profile-load-more-trigger" class="py-8 text-center" data-page="2" data-url="{{ route('profile.load-more', $user->username ?? $user->id) }}" data-has-more="{{ $recentArticles->hasMorePages() ? 'true' : 'false' }}">
                            <div class="inline-block animate-spin w-8 h-8 border-4 border-accent border-t-transparent rounded-full hidden" id="profile-load-more-spinner"></div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Infinite Scroll Logic
    const trigger = document.getElementById('profile-load-more-trigger');
    const spinner = document.getElementById('profile-load-more-spinner');
    const container = document.getElementById('articles-container');
    
    if (trigger && container) {
        let isLoading = false;
        
        const observer = new IntersectionObserver((entries) => {
            const entry = entries[0];
            if (entry.isIntersecting && !isLoading && trigger.dataset.hasMore === 'true') {
                loadMore();
            }
        }, { rootMargin: '200px' });
        
        observer.observe(trigger);
        
        function loadMore() {
            isLoading = true;
            spinner.classList.remove('hidden');
            
            const url = trigger.dataset.url;
            const page = trigger.dataset.page;
            
            fetch(`${url}?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    container.insertAdjacentHTML('beforeend', data.html);
                }
                
                trigger.dataset.hasMore = data.hasMore ? 'true' : 'false';
                if (data.hasMore) {
                    trigger.dataset.page = parseInt(page) + 1;
                } else {
                    trigger.style.display = 'none';
                    observer.disconnect();
                }
            })
            .catch(error => console.error('Error loading more articles:', error))
            .finally(() => {
                isLoading = false;
                spinner.classList.add('hidden');
            });
        }
    }
});
</script>
@endpush
@endsection