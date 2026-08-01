@extends('layouts.app')

@section('title', 'About Us - Nazaara Circle')

@section('content')
<style>
    .section-title {
        font-size: 32px;
        font-weight: 800;
        margin: 15px 0 25px;
        color: #000;
        text-transform: uppercase;
        letter-spacing: -0.5px;
        font-family: 'Poppins', sans-serif;
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 60%;
        height: 4px;
        background: #E50914;
        margin-top: 5px;
    }
    .dark .section-title {
        color: #fff;
    }
    .content-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }
    .dark .content-card {
        background: #1a1a1a;
        border-color: #333;
    }
    .who-we-are {
        background-color: #000;
        color: #fff;
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        margin-top: 50px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .who-we-are::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #E50914, #ff4d4d);
    }
    .who-title {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 1px;
    }
    .who-text {
        font-size: 16px;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto 25px;
        color: #ccc;
    }
    .who-btn {
        display: inline-block;
        background: #E50914;
        color: #fff;
        padding: 10px 25px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 4px;
        transition: background 0.3s;
    }
    .who-btn:hover {
        background: #ff1f2a;
    }
</style>

<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumbs -->
        @if(isset($seo['breadcrumbs']))
            @include('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']])
        @endif
        
        <h1 class="section-title">
            About Us
        </h1>

        <div class="content-card space-y-8">
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4 text-lg" style="font-weight: 400;">
                    Welcome to <strong class="text-accent">Nazaara Circle</strong>! We are passionate about entertainment, cinema, and sharing stories with the world. Our mission is to provide high-quality news, reviews, and insights about the latest trends in movies, TV shows, celebrity biographies, and pop culture.
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-6 text-lg" style="font-weight: 400;">
                    Whether you are a casual viewer looking for the next binge-worthy series, a film buff analyzing cinematic masterpieces, or just curious about the lives of your favorite stars, <strong>Nazaara Circle</strong> is here to keep you informed and entertained.
                </p>
            </section>

            <!-- About Nazaara Circle Section -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">About Nazaara Circle</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    <strong>Nazaara Circle</strong> is a premier entertainment platform dedicated to providing exceptional content and an immersive user experience. Our website is thoughtfully designed to make it easy for movie buffs, TV series enthusiasts, and pop culture fans to discover, read, and engage with valuable entertainment content.
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    At <strong>Nazaara Circle</strong>, we believe that stories have the power to connect us. We strive to create a space where entertainment lovers can find reliable reviews, in-depth explanations, and fascinating biographies. Our goal is to be your go-to source for everything happening in the world of cinema and celebrities.
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed" style="font-weight: 400;">
                    Whether you are here to check the latest movie ratings, understand the ending of a complex series, or learn about the journey of your favorite star, we have something for you. Join our community and stay updated with the ever-evolving world of entertainment.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">What We Do</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We publish in-depth articles, detailed reviews, and fascinating biographies covering a wide range of entertainment topics. From exploring the hidden meanings in your favorite movies to chronicling the rise of global superstars, we aim to satisfy the curiosity of every entertainment enthusiast.
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Our content covers various domains including Hollywood, Bollywood, international cinema, TV series, OTT platforms, and music. Each piece is carefully crafted to provide entertainment, information, and a fresh perspective on the stories that shape our culture.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Our Mission</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Our goal is to create a comprehensive resource for movie lovers, binge-watchers, and anyone interested in the world of entertainment. We believe in making pop culture accessible and engaging for everyone, creating a community where fans can celebrate their passions.
                </p>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We are committed to:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Providing accurate, up-to-date entertainment news</li>
                    <li>Creating content that is engaging and fun to read</li>
                    <li>Supporting the community of fans and creators</li>
                    <li>Promoting diverse voices in cinema and arts</li>
                    <li>Fostering a respectful and inclusive environment</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Why Choose Nazaara Circle?</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Nazaara Circle stands out as a premier entertainment resource platform, offering a seamless experience for readers and contributors alike. Our commitment to quality, storytelling, and community engagement makes us the go-to destination for fans seeking reliable updates and immersive content.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Comprehensive Content</h3>
                            <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">In-depth articles covering a wide range of entertainment topics with insightful analysis and behind-the-scenes details</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Easy Navigation</h3>
                            <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">Intuitive search and categorization system that helps you quickly find the content you need</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Regular Updates</h3>
                            <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">Fresh content published regularly to keep you informed about the latest entertainment trends and developments</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Mobile Friendly</h3>
                            <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">Responsive design that provides an excellent reading experience on all devices, from desktops to smartphones</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Who We Are Section -->
        <div class="who-we-are">
            <h2 class="who-title">Who We Are</h2>
            <p class="who-text">
                Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
            </p>
            <a href="{{ route('about') }}" class="who-btn">Read More About Us</a>
        </div>
    </div>
</div>
@endsection
