<rss version="2.0">
    <channel>
<title>{{ $title ?? (config('app.name') . ' - Latest articles') }}</title> 
<link>{{ $link ?? config('app.url') }}</link> 
<description>{{ $description ?? (config('app.name') . ' is the ultimate resource for latest articles and news. This feed contains the latest articles (news and reviews) in chronological order.') }}</description>
        <language>en-us</language>
<copyright>{{ $copyright ?? ('Copyright ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.') }}</copyright> 
<ttl>10</ttl>

<image>
<url>{{ $logoUrl ?? asset('icon.png') }}</url> 
<title>{{ $title ?? (config('app.name') . ' - Latest articles') }}</title> 
<link>{{ $link ?? config('app.url') }}</link> 
<description>{{ $siteUrl ?? config('app.url') }}</description>
</image>
						
        
        @foreach($articles as $article)
@php
    $articleUrl = route('articles.show', $article->slug);
    $articleImage = $article->featured_image 
        ? (filter_var($article->featured_image, FILTER_VALIDATE_URL) ? $article->featured_image : url($article->featured_image))
        : ($logoUrl ?? asset('icon.png'));
    
    // Extract plain text content and truncate
    $plainContent = strip_tags($article->content);
    $excerpt = $article->excerpt ?: mb_substr($plainContent, 0, 250);
    $excerpt = trim($excerpt);
    if (mb_strlen($plainContent) > 250) {
        $excerpt .= '...';
    }
    
    // Format date in RFC 822 format (like: Fri, 19 Dec 2025 02:01:02 +0100)
    $pubDate = ($article->published_at ?: $article->created_at);
    $timezone = $pubDate->format('O'); // Get timezone offset like +0100
    $rssDate = $pubDate->format('D, d M Y H:i:s') . ' ' . $timezone;
    
    // HTML encode the excerpt
    $encodedExcerpt = htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8');
    
    // Category name or default to 'news'
    $categoryName = $article->category ? $article->category->name : 'news';
@endphp

        <item>
            <title><![CDATA[{{ $article->title }}]]></title>
<link>{{ $articleUrl }}</link>
<guid isPermaLink="true">{{ $articleUrl }}</guid>
<pubDate>{{ $rssDate }}</pubDate>
<description>&lt;img src=&quot;{{ $articleImage }}&quot; width=&quot;184&quot; height=&quot;111&quot; hspace=&quot;3&quot; alt=&quot;&quot; border=&quot;0&quot; align=left style="background:#333333;padding:0px;margin:0px 4px 0px 0px;border-style:solid;border-color:#aaaaaa;border-width:1px" /&gt; &lt;p&gt;<![CDATA[{{ $excerpt }}]]>&lt;/p&gt;</description>
<comments>{{ $articleUrl }}#comments</comments>
<category>{{ $categoryName }}</category>
        </item>

        @endforeach
						

    </channel>
</rss>
