@extends('layouts.app')

@section('title', 'Disclaimer - Nazaara Circle')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumbs -->
        @if(isset($seo['breadcrumbs']))
            @include('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']])
        @endif
        
        <h1 class="font-poppins text-4xl font-extrabold uppercase tracking-tight text-gray-900 dark:text-white relative inline-block mb-6 after:content-[''] after:block after:w-3/5 after:h-1 after:bg-[#E50914] after:mt-2">
            Disclaimer
        </h1>

        <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 dark:border-border-secondary dark:bg-bg-card space-y-6">
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    <strong>Last Updated:</strong> {{ date('F d, Y') }}
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The information provided by Nazaara Circle ("we," "us," or "our") on this website is for general informational purposes only. All information on the site is provided in good faith, however, we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the site.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">External Links Disclaimer</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The site may contain (or you may be sent through the site) links to other websites or content belonging to or originating from third parties or links to websites and features in banners or other advertising. Such external links are not investigated, monitored, or checked for accuracy, adequacy, validity, reliability, availability, or completeness by us.
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We do not warrant, endorse, guarantee, or assume responsibility for the accuracy or reliability of any information offered by third-party websites linked through the site or any website or feature linked in any banner or other advertising. We will not be a party to or in any way be responsible for monitoring any transaction between you and third-party providers of products or services.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Professional Disclaimer</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The site cannot and does not contain professional advice. The entertainment information is provided for general informational and educational purposes only and is not a substitute for professional advice. Accordingly, before taking any actions based upon such information, we encourage you to consult with the appropriate professionals. We do not provide any kind of professional advice. The use or reliance of any information contained on the site is solely at your own risk.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Views Expressed Disclaimer</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The views and opinions expressed on Nazaara Circle are those of the authors and do not necessarily reflect the official policy or position of any other agency, organization, employer, or company, including Nazaara Circle. Comments published by users are their sole responsibility and the users will take full responsibility, liability, and blame for any libel or litigation that results from something written in or as a direct result of something written in a comment. Nazaara Circle is not liable for any comment published by users and reserves the right to delete any comment for any reason whatsoever.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Fair Use Disclaimer</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    The site may contain copyrighted material the use of which has not always been specifically authorized by the copyright owner. We are making such material available for criticism, comment, news reporting, teaching, scholarship, or research. We believe this constitutes a "fair use" of any such copyrighted material as provided for in section 107 of the US Copyright Law.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Contact Us</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    If you have any questions about this Disclaimer, please <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light underline" style="font-weight: 600;">contact us</a>.
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
