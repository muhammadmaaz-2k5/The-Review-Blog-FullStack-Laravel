@extends('layouts.app')

@section('title', 'Entertainment News & Reviews - Nazaara Circle')

@section('content')
<!-- Hero Section (Featured Stories) -->
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(isset($featuredArticles) && $featuredArticles->count() > 0)
    <div class="mb-12">
        <h2 class="text-3xl font-black text-gray-900 dark:!text-white mb-6 flex items-center gap-3 uppercase tracking-tight" style="font-family: 'Poppins', sans-serif;">
            <span class="w-2 h-8 bg-accent rounded-full"></span>
            Top Stories
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-auto lg:h-[500px]">
            <!-- Main Featured Story -->
            <div class="lg:col-span-2 relative group rounded-2xl overflow-hidden shadow-2xl h-[300px] lg:h-full">
                @php $mainFeature = $featuredArticles->first(); @endphp
                <a href="{{ route('articles.show', $mainFeature->slug) }}" class="block w-full h-full">
                    @if($mainFeature->featured_image)
                        <img src="{{ $mainFeature->featured_image_url }}" alt="{{ $mainFeature->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gray-800"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-90"></div>
                    <div class="absolute bottom-0 left-0 p-8 w-full">
                        <span class="inline-block px-3 py-1 bg-accent text-white text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
                            {{ $mainFeature->category->name ?? 'Featured' }}
                        </span>
                        <h3 class="text-2xl lg:text-4xl font-black text-white mb-2 leading-tight" style="font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                            {{ $mainFeature->title }}
                        </h3>
                        <div class="flex items-center gap-4 text-white/80 text-sm font-medium">
                            <span>{{ $mainFeature->published_at?->format('M d, Y') ?? 'Just Now' }}</span>
                            <span>•</span>
                            <span>{{ $mainFeature->reading_time ?? '5' }} min read</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Secondary Featured Stories -->
            <div class="flex flex-col gap-6 h-full">
                @foreach($featuredArticles->skip(1)->take(2) as $subFeature)
                <div class="relative flex-1 group rounded-2xl overflow-hidden shadow-lg">
                    <a href="{{ route('articles.show', $subFeature->slug) }}" class="block w-full h-full">
                        @if($subFeature->featured_image)
                            <img src="{{ $subFeature->featured_image_url }}" alt="{{ $subFeature->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gray-700"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90"></div>
                        <div class="absolute bottom-0 left-0 p-6 w-full">
                            <span class="text-xs font-bold text-accent uppercase tracking-wider mb-1 block">
                                {{ $subFeature->category->name ?? 'News' }}
                            </span>
                            <h4 class="text-lg font-bold text-white leading-snug line-clamp-2" style="font-family: 'Poppins', sans-serif;">
                                {{ $subFeature->title }}
                            </h4>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Content (Articles Feed) -->
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:!border-border-secondary">
                <h1 class="text-2xl font-black text-gray-900 dark:!text-white uppercase tracking-tight" style="font-family: 'Poppins', sans-serif;">
                    Latest Updates
                </h1>
                <div class="flex gap-2">
                    <!-- Optional View Toggles could go here -->
                </div>
            </div>

            <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($articles as $article)
                    @include('articles._card', ['article' => $article])
                @empty
                    <div class="col-span-full text-center py-20 bg-gray-50 dark:!bg-bg-card rounded-2xl border border-dashed border-gray-300 dark:!border-border-secondary">
                        <div class="text-6xl mb-4">🎬</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif;">No Stories Yet</h3>
                        <p class="text-gray-500 dark:!text-text-secondary">Check back later for the latest entertainment news.</p>
                    </div>
                @endforelse
            </div>

            
            
            <!-- Auto Load More Spinner -->
            <div id="articles-load-more-trigger" class="py-8 text-center" data-page="2" data-url="{{ route('articles-index.load-more') }}" data-has-more="{{ $articles->hasMorePages() ? 'true' : 'false' }}">
                <div class="inline-block animate-spin w-8 h-8 border-4 border-accent border-t-transparent rounded-full hidden" id="articles-load-more-spinner"></div>
            </div>
        </div>
        
        <!-- Sidebar (Right) -->
        <div class="lg:col-span-4 space-y-10">
            <!-- Trending Widget -->
            @if(isset($trendingArticles) && $trendingArticles->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:!border-border-primary bg-gray-50 dark:!bg-bg-card-hover">
                    <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-6 flex items-center gap-2" style="font-family: 'Poppins', sans-serif;">
                        <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                        Trending Now
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:!divide-border-primary">
                    @foreach($trendingArticles->take(5) as $index => $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="flex gap-4 p-5 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors group items-start">
                        <span class="text-4xl font-black text-gray-200 dark:!text-gray-700 leading-none -mt-2 group-hover:text-accent/20 transition-colors" style="font-family: 'Poppins', sans-serif;">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:!text-white leading-snug line-clamp-2 group-hover:text-accent transition-colors mb-1" style="font-family: 'Poppins', sans-serif;">
                                {{ $article->title }}
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:!text-text-secondary">
                                <span>{{ $article->category->name ?? 'News' }}</span>
                                <span>•</span>
                                <span>{{ $article->views }} views</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Newsletter Widget -->
            <div class="relative rounded-2xl overflow-hidden p-8 text-center group shadow-xl">
                <div class="absolute inset-0 bg-gray-900">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Audience" class="w-full h-full object-cover opacity-20">
                    <div class="absolute inset-0 bg-gradient-to-r from-black via-gray-900/90 to-transparent"></div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-white mb-3" style="font-family: 'Poppins', sans-serif;">
                        We Are <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-400">Nazaara Circle</span>
                    </h3>
                    <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl">
                        Your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg uppercase tracking-wide">Join the Circle</a>
                        <a href="#" class="px-8 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-full hover:bg-white/20 transition-all border border-white/20">Read Our Story</a>
                    </div>
                </div>
            </div>

            <!-- Featured/Must Read Widget -->
            @if(isset($featuredArticles) && $featuredArticles->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-6 flex items-center gap-2" style="font-family: 'Poppins', sans-serif;">
                    <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                    Must Read
                </h3>
                <div class="flex flex-col gap-6">
                    @foreach($featuredArticles->take(4) as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="group block">
                        <div class="aspect-video rounded-xl overflow-hidden mb-3 relative">
                            @if($article->featured_image)
                                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gray-200 dark:!bg-gray-800"></div>
                            @endif
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:!text-white leading-snug group-hover:text-accent transition-colors line-clamp-2" style="font-family: 'Poppins', sans-serif;">
                            {{ $article->title }}
                        </h4>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Who We Are Section (Redesigned) -->
    <div class="mt-20 relative rounded-3xl overflow-hidden">
        <div class="absolute inset-0 bg-gray-900">
            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Audience" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-gray-900/90 to-transparent"></div>
        </div>
        <div class="relative z-10 px-8 py-16 md:p-20 max-w-4xl">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight" style="font-family: 'Poppins', sans-serif;">
                We Are <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-400">Nazaara Circle</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl">
                Your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg uppercase tracking-wide">Join the Circle</a>
                <a href="#" class="px-8 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-full hover:bg-white/20 transition-all border border-white/20">Read Our Story</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.getElementById('articles-load-more-trigger');
        const container = document.getElementById('articles-container');
        const spinner = document.getElementById('articles-load-more-spinner');
        
        if (!trigger || !container || trigger.dataset.hasMore !== 'true') return;
        
        let isLoading = false;
        
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !isLoading && trigger.dataset.hasMore === 'true') {
                loadMoreArticles();
            }
        }, { rootMargin: '200px' });
        
        observer.observe(trigger);
        
        function loadMoreArticles() {
            isLoading = true;
            spinner.classList.remove('hidden');
            
            const page = parseInt(trigger.dataset.page);
            const url = trigger.dataset.url + '?page=' + page;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.html;
                        
                        while (tempDiv.firstChild) {
                            container.appendChild(tempDiv.firstChild);
                        }
                        
                        if (data.hasMore) {
                            trigger.dataset.page = page + 1;
                        } else {
                            trigger.dataset.hasMore = 'false';
                            trigger.remove();
                        }
                    } else {
                        trigger.dataset.hasMore = 'false';
                    }
                })
                .catch(error => {
                    console.error('Error loading more articles:', error);
                })
                .finally(() => {
                    isLoading = false;
                    spinner.classList.add('hidden');
                });
        }
    });
</script>

@push('head')
@endpush

@push('scripts')
@endpush

@endsection
