@php
    $resolvedAd = $ad ?? \App\Models\Ad::getForPlacement($placement);
@endphp

@if($resolvedAd && $resolvedAd->is_active && $resolvedAd->ad_code)
    @if($resolvedAd->type === 'adsterra_popunder' || $resolvedAd->type === 'adsterra_social_bar')
        {!! $resolvedAd->ad_code !!}
    @elseif($resolvedAd->type === 'adsterra_smartlink')
        {!! $resolvedAd->ad_code !!}
    @else
        <div class="{{ $wrapperClass }}">
            {!! $resolvedAd->ad_code !!}
        </div>
    @endif
@endif
