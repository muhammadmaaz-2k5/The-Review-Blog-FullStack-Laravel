{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd{{ $hasImages ? ' http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd' : '' }}{{ $hasVideos ? ' http://www.google.com/schemas/sitemap-video/1.1 http://www.google.com/schemas/sitemap-video/1.1/sitemap-video.xsd' : '' }}"
        @if($hasImages) xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" @endif
        @if($hasVideos) xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" @endif>
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        @if(isset($url['lastmod']))
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        @endif
        @if(isset($url['changefreq']))
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        @endif
        @if(isset($url['priority']))
        <priority>{{ $url['priority'] }}</priority>
        @endif
        @if($hasImages && isset($url['images']))
            @foreach($url['images'] as $image)
                <image:image>
                    <image:loc>{{ $image['loc'] }}</image:loc>
                    @if(isset($image['title']))
                        <image:title>{{ $image['title'] }}</image:title>
                    @endif
                    @if(isset($image['caption']))
                        <image:caption>{{ $image['caption'] }}</image:caption>
                    @endif
                </image:image>
            @endforeach
        @endif
        @if($hasVideos && isset($url['videos']))
            @foreach($url['videos'] as $video)
                <video:video>
                    <video:thumbnail_loc>{{ $video['thumbnail_loc'] }}</video:thumbnail_loc>
                    <video:title>{{ $video['title'] }}</video:title>
                    <video:description>{{ $video['description'] }}</video:description>
                    @if(isset($video['player_loc']))
                        <video:player_loc>{{ $video['player_loc'] }}</video:player_loc>
                    @endif
                </video:video>
            @endforeach
        @endif
    </url>
@endforeach
</urlset>