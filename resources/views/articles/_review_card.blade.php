<article class="group flex items-start gap-4 pb-6 mb-6 border-b border-gray-200 dark:!border-border-primary last:border-0">
    <a href="{{ route('articles.show', $article->slug) }}" class="flex items-start gap-4 w-full">
        <!-- Image with Rating Badge -->
        <div class="relative flex-shrink-0 w-32 h-32 sm:w-40 sm:h-40 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-800">
            @if($article->featured_image)
                <img src="{{ $article->featured_image_url }}" 
                     alt="{{ $article->featured_image_alt ?: $article->title }}" 
                     title="{{ $article->featured_image_title ?: $article->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}'">
            @else
                <img src="{{ asset('article_image_notfound.png') }}" 
                     alt="{{ $article->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @endif
            
            <!-- Rating Badge (circular green badge with number) -->
            @php
                // Calculate rating from views and likes (0-10 scale)
                $likesCount = $article->likes()->count();
                $viewsCount = $article->views ?? 0;
                // Simple rating calculation: base 7, +1 for every 1000 views, +1 for every 10 likes, max 10
                $rating = min(10, max(7, 7 + floor($viewsCount / 1000) + floor($likesCount / 10)));
            @endphp
            <div class="absolute bottom-2 left-2 w-8 h-8 sm:w-10 sm:h-10 bg-green-500 rounded-full flex items-center justify-center shadow-lg z-10">
                <span class="text-white font-bold text-sm sm:text-base" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $rating }}</span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
            <!-- Title -->
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:!text-white mb-2 group-hover:text-accent transition-colors line-clamp-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $article->title }}
            </h3>
            
            <!-- Author and Date -->
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                @if($article->author)
                    <span>Written by {{ $article->author->name }}</span>
                @endif
                @if($article->published_at)
                    <span>,</span>
                    <span>{{ $article->published_at->format('d F Y') }}</span>
                @endif
            </div>
            
            <!-- Category Tag -->
            {{-- @if($article->category)
            <div class="inline-block">
                <span class="inline-block px-3 py-1 bg-accent text-white text-xs font-semibold rounded-full" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ strtoupper($article->category->name) }}
                </span>
            </div>
            @endif --}}
        </div>
    </a>
</article>

