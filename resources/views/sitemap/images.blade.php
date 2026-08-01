{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        @if(isset($url['lastmod']))
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        @endif
        @if(isset($url['image']))
        <image:image>
            <image:loc>{{ $url['image']['loc'] }}</image:loc>
            @if(!empty($url['image']['title']))
            <image:title><![CDATA[{{ $url['image']['title'] }}]]></image:title>
            @endif
            @if(!empty($url['image']['caption']))
            <image:caption><![CDATA[{{ $url['image']['caption'] }}]]></image:caption>
            @endif
        </image:image>
        @endif
    </url>
@endforeach
</urlset>


