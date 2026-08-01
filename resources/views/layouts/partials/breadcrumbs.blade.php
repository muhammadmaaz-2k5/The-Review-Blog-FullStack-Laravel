@props(['items'])

@if(!empty($items))
<nav class="flex mb-6 overflow-x-auto pb-2 scrollbar-none" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 whitespace-nowrap">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-400 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Home
            </a>
        </li>
        @foreach($items as $item)
            @if($item['name'] !== 'Home')
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        @if(!$loop->last)
                            <a href="{{ $item['url'] }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-accent md:ml-2 dark:text-gray-400 dark:hover:text-white transition-colors">
                                {{ $item['name'] }}
                            </a>
                        @else
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400 line-clamp-1" aria-current="page">
                                {{ $item['name'] }}
                            </span>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

{{-- JSON-LD Breadcrumb Schema --}}
@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_map(function($item, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }, array_merge([['name' => 'Home', 'url' => route('home')]], array_filter($items, fn($i) => $i['name'] !== 'Home')), array_keys(array_merge([['name' => 'Home', 'url' => route('home')]], array_filter($items, fn($i) => $i['name'] !== 'Home'))))
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
