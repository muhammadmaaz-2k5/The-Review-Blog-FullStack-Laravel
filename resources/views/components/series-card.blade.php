<a href="{{ route('series.show', $series->slug) }}" class="group flex flex-col bg-white dark:!bg-[#1a1a1a] rounded-2xl overflow-hidden border border-gray-100 dark:!border-white/5 shadow-lg hover:shadow-2xl hover:shadow-accent/10 transition-all duration-300 transform hover:-translate-y-1 h-full">
    <!-- Series Image -->
    <div class="relative aspect-video overflow-hidden">
        @if($series->featured_image)
            @php
                $imageUrl = str_starts_with($series->featured_image, 'http') 
                    ? $series->featured_image 
                    : asset('storage/' . $series->featured_image);
            @endphp
            <img src="{{ $imageUrl }}" 
                 alt="{{ $series->featured_image_alt ?: $series->title }}" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}';">
        @else
            <img src="{{ asset('article_image_notfound.png') }}" 
                 alt="{{ $series->title }}" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        @endif
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
        
        <!-- Top Badge -->
        <div class="absolute top-4 left-4">
            <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded border border-white/10 uppercase tracking-wide">
                Series
            </span>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-6 flex-1 flex flex-col relative bg-white dark:!bg-[#1a1a1a]">
        <!-- Article Count Badge (Floating) -->
        <div class="absolute -top-5 right-4 bg-accent text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
            {{ number_format($series->articles_count ?? 0) }} Parts
        </div>

        <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-3 leading-tight group-hover:text-accent transition-colors line-clamp-2" style="font-family: 'Poppins', sans-serif;">
            {{ $series->title }}
        </h3>
        
        @if($series->description)
            <p class="text-sm text-gray-500 dark:!text-gray-400 line-clamp-3 mb-4 flex-1">
                {{ $series->description }}
            </p>
        @endif
        
        <!-- Meta -->
        <div class="pt-4 border-t border-gray-100 dark:!border-white/5 mt-auto">
            <div class="flex items-center justify-between text-xs font-medium text-gray-400">
                @if($series->author)
                    <span class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-full bg-gray-200 dark:!bg-gray-700 overflow-hidden">
                            @if($series->author->avatar)
                                <img src="{{ $series->author->avatar }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-500">{{ substr($series->author->name, 0, 1) }}</div>
                            @endif
                        </div>
                        {{ $series->author->name }}
                    </span>
                @endif
                <span class="group-hover:translate-x-1 transition-transform text-accent">Explore →</span>
            </div>
        </div>
    </div>
</a>