<!doctype html>
<html ⚡ lang="en">
<head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <title>{{ $seo['title'] }}</title>
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <meta name="description" content="{{ $seo['description'] }}">
    
    <!-- AMP Boilerplate -->
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    
    <!-- No AMP Extensions - Removed custom-element scripts for Google compliance -->
    
    <!-- Structured Data -->
    @if(!empty($seo['schema']))
        @foreach($seo['schema'] as $schema)
        <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        @endforeach
    @endif
    
    <style amp-custom>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        .article-header {
            margin-bottom: 30px;
        }
        .article-title {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }
        .article-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .article-content {
            font-size: 1.1em;
            line-height: 1.8;
        }
        .article-content img {
            max-width: 100%;
            height: auto;
        }
        .article-content h1, .article-content h2, .article-content h3 {
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .article-content p {
            margin-bottom: 15px;
        }
        .article-content code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .article-content pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .category-badge {
            display: inline-block;
            background: #E50914;
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        .featured-image {
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            background: #f0f0f0;
        }
        .tags {
            margin-top: 20px;
        }
        .tag {
            display: inline-block;
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <article>
        <header class="article-header">
            @if($article->category)
                <a href="{{ route('categories.show', $article->category->slug) }}" class="category-badge">
                    {{ $article->category->name }}
                </a>
            @endif
            
            <h1 class="article-title">{{ $article->title }}</h1>
            
            <div class="article-meta">
                @if($article->author)
                    <span>By {{ $article->author->name }}</span>
                @endif
                @if($article->published_at)
                    <span> • {{ $article->published_at->format('M d, Y') }}</span>
                @endif
                @if($article->reading_time)
                    <span> • {{ $article->reading_time }} min read</span>
                @endif
            </div>
            
            @if($article->featured_image)
                @php
                    // Use the model's featured_image_url accessor for consistency
                    $imageUrl = $article->featured_image_url;
                    
                    // For AMP, we need to ensure the image is accessible
                    // Remove cache busting as it can cause issues with AMP caching
                    $imageUrlClean = strtok($imageUrl, '?');
                @endphp
                <img src="{{ $imageUrlClean }}" 
                     alt="{{ $article->title }}"
                     class="featured-image"
                     style="width: 100%; height: auto; max-width: 800px;">
            @else
                <!-- Fallback placeholder if no featured image -->
                <img src="{{ asset('images/placeholder.jpg') }}" 
                     alt="{{ $article->title }}"
                     style="width: 100%; height: auto; max-width: 800px;">
            @endif
            
            @if($article->tags->count() > 0)
                <div class="tags">
                    @foreach($article->tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" class="tag">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </header>
        
        <div class="article-content">
            {!! $article->rendered_content !!}
        </div>
        
        <!-- Social Share - Standard HTML (replaced amp-social-share) -->
        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($article->url) }}&text={{ urlencode($article->title) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               style="display: inline-block; width: 40px; height: 40px; background: #1DA1F2; border-radius: 50%; text-decoration: none;"
               title="Share on Twitter">
                <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: white; margin: 10px;"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($article->url) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               style="display: inline-block; width: 40px; height: 40px; background: #1877F2; border-radius: 50%; text-decoration: none;"
               title="Share on Facebook">
                <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: white; margin: 10px;"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($article->url) }}&title={{ urlencode($article->title) }}" 
               target="_blank" 
               rel="noopener noreferrer"
               style="display: inline-block; width: 40px; height: 40px; background: #0077B5; border-radius: 50%; text-decoration: none;"
               title="Share on LinkedIn">
                <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: white; margin: 10px;"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
        </div>
        
        <footer style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="color: #666; font-size: 0.9em;">
                <a href="{{ route('articles.show', $article->slug) }}" style="color: #E50914;">
                    View original article
                </a>
            </p>
        </footer>
    </article>
</body>
</html>
