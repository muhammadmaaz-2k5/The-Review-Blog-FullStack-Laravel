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