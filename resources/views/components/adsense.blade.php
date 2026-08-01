@if(config('services.adsense.client_id'))
    <div class="adsense-container my-4 text-center">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ config('services.adsense.client_id') }}"
             @if(isset($slot) && !empty($slot))
                 data-ad-slot="{{ $slot }}"
             @else
                 data-ad-slot="{{ config('services.adsense.unit_1') }}" 
                 data-ad-format="auto"
                 data-full-width-responsive="true"
             @endif>
        </ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@endif
