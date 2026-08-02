@foreach($articles as $article)
    <a href="{{ route('articles.show', $article->slug) }}" class="ent-card">
        <div class="ent-thumb">
            @if($article->featured_image)
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}';">
            @else
                <img src="{{ asset('article_image_notfound.png') }}" alt="{{ $article->title }}" loading="lazy">
            @endif
        </div>
        <div class="ent-content">
            <div class="ent-category">{{ $article->category->name ?? 'Entertainment' }}</div>
            <h3 class="ent-title">
                {{ Str::limit($article->title, 60) }}
            </h3>
            <div class="ent-excerpt">
                {{ Str::limit(strip_tags($article->excerpt ?? $article->content), 100) }}
            </div>
            <div class="ent-meta">
                <span>{{ $article->published_at ? $article->published_at->format('M d') : 'Recent' }}</span>
                <span>•</span>
                <span>{{ $article->reading_time ?? 5 }} min read</span>
            </div>
        </div>
    </a>
@endforeach
