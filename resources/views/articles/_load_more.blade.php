@foreach($articles as $item)
    <div class="article-card">
        <div class="article-thumb">
            <a href="{{ route('articles.show', $item->slug) }}">
                @if($item->featured_image)
                    <img src="{{ $item->featured_image_url }}" 
                         alt="{{ $item->featured_image_alt ?: $item->title }}" 
                         title="{{ $item->featured_image_title ?: $item->title }}"
                         onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}';">
                @else
                    <img src="{{ asset('article_image_notfound.png') }}" alt="{{ $item->title }}">
                @endif
            </a>
        </div>
        <div class="article-content">
            <h3>
            <a href="{{ route('articles.show', $item->slug) }}">{{ $item->title }}</a>
        </h3>
            <div class="article-meta">
                Written by <strong>{{ $item->author->name ?? 'Admin' }}</strong>, 
                {{ $item->published_at ? $item->published_at->format('d F Y') : $item->created_at->format('d F Y') }}
            </div>
            {{-- @if($item->category)
                <div class="article-category">{{ $item->category->name }}</div>
            @endif --}}
        </div>
    </div>
@endforeach

