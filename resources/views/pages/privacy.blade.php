@extends('layouts.app')

@section('title', 'Privacy Terms - Nazaara Circle')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumbs -->
        @if(isset($seo['breadcrumbs']))
            @include('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']])
        @endif
        
        <h1 class="font-poppins text-4xl font-extrabold uppercase tracking-tight text-gray-900 dark:text-white relative inline-block mb-6 after:content-[''] after:block after:w-3/5 after:h-1 after:bg-[#E50914] after:mt-2">
            Privacy Policies, Terms, and Conditions
        </h1>

        <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 dark:border-border-secondary dark:bg-bg-card space-y-6">
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    <strong>Published:</strong> 2023-09-18 08:43:15
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Purpose of this website</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The <strong>Articles</strong> section of this page is a blog with posts on different topics. This website contains informative, educational, and entertaining articles. The <strong>Videos</strong> section contains various stock videos of different types. The <strong>Images</strong> section contains various stock high-quality images and textures. The <strong>Sounds</strong> section contains various stock sound effects suitable for filmmaking. The <strong>Music</strong> section contains various stock songs and music tracks suitable as film OST. This website does not offer paid content or paid services and is entirely free.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Author</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The author of this website is a YouTuber <strong>Hitokage</strong> and this website is associated with the YouTube channel.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Cookies</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The website itself does not use cookies to store information about users. No cookies are necessary for the users to interact with the website content. As we no longer display advertisements on this website, there are no advertising-related cookies or tracking systems in use.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Privacy and gathered information</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The website does not contain any registration or login service and does not require any personal information from its visitors. Any user submitting a comment to this website agrees to the storage of the content of the comment, the inserted username, the time of the posting, and the IP address of the browsing device. IP address is stored in order to prevent spam and harmful bots posting unsuitable comments that might be disrespectful or suspicious. The IP address is not used for any other purposes and is stored in a secured database. The users can request removal of their posted comments at any time. No additional fingerprint information about the users is stored. The user location data is not analyzed or stored by this website.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Safety</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Any advice, tips, and information published on this website are based on personal experience of the author and are not professional articles. Following any advice present on this website is voluntary and at one's own risk. The author assumes no responsibility for possible misinterpretation or harm caused by the information on this website. All the information on this website is posted with a good intention, observing generally accepted moral principles. This website does not contain anything related to child abuse, pornography, violence encouragement, illegal actions, dangerous and harmful actions, animal cruelty, or dishonest or deceptive practices. This website does not contain any malicious software or malware.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Copyright</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    All original materials on this website are free to use under <strong>CC0 licence</strong>. The original materials are all files in sections <strong>Videos, Images, Sounds, Music</strong> and most of the materials used in section <strong>Articles</strong>. The only exception is imagery in certain posts at the <strong>Articles</strong> section obtained from existing works under the Fair Use principle. The author of this website does not own the copyright of such materials and any further usage must be carried out according to the original author's guidelines. This website does not intentionally break any copyright rules, follows DMCA statement, and the ownership of the original materials is respected and well referenced.
                </p>
            </section>

            <section class="pt-4 border-t border-gray-100 dark:border-border-secondary">
                <p class="text-sm text-gray-500 dark:text-text-muted" style="font-weight: 400;">
                    <strong>Keywords:</strong> privacy, terms, policies, copyright
                </p>
            </section>
        </div>

        <!-- Who We Are Section -->
        <div class="mt-20 rounded-2xl bg-gradient-to-br from-[#1a1a1a] to-[#2d2d2d] px-10 py-16 text-center text-white shadow-2xl relative overflow-hidden">
            <h2 class="mb-5 font-poppins text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300">Who We Are</h2>
            <p class="mx-auto mb-8 max-w-3xl text-base leading-relaxed text-gray-200">
                Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
            </p>
            <a href="{{ route('about') }}" class="inline-block rounded-full bg-[#E50914] px-8 py-3 font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-[#ff0f1f]">Read More About Us</a>
        </div>
    </div>
</div>
@endsection
