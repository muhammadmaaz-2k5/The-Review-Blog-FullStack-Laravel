@php
    $ads = [
        '/images/banner1.png',
        '/images/banner2.png',
        '/images/banner3.png',
        '/images/banner4.png',
        '/images/banner5.png',
    ];
    $randomAd = $ads[array_rand($ads)];
@endphp

<div class="referral-banner-container my-8 flex justify-center w-full px-4">
    <a href="https://beta.publishers.adsterra.com/referral/5KmgXrG9id" rel="nofollow" target="_blank" class="block w-full max-w-[728px] transition-all hover:scale-[1.02] hover:shadow-2xl">
        <img src="{{ $randomAd }}" alt="Special Offer" class="w-full h-auto rounded-2xl shadow-xl border-4 border-white dark:border-gray-800 object-cover" loading="lazy">
    </a>
</div>

<style>
    .referral-banner-container img {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .referral-banner-container a:hover img {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
