@extends('layouts.app')

@section('title', 'Advertise With Us - Nazaara Circle')

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

    .ad-package {
        border: 2px solid #e5e5e5;
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s;
        background: white;
    }
    
    .ad-package:hover {
        border-color: #E50914;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(229, 9, 20, 0.15);
    }
    
    .ad-package.featured {
        border-color: #E50914;
        background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
    }
    
    .package-price {
        font-size: 2rem;
        font-weight: 700;
        color: #E50914;
    }
    
    .package-period {
        font-size: 0.875rem;
        color: #666;
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }
    
    .feature-list li {
        padding: 0.5rem 0;
        padding-left: 1.5rem;
        position: relative;
    }
    
    .feature-list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #4caf50;
        font-weight: bold;
    }
    
    .stat-card {
        text-align: center;
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #E50914;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.5rem;
    }
    
    html.dark .ad-package {
        background: #1F1F1F;
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    html.dark .ad-package:hover {
        border-color: #E50914;
    }
    
    html.dark .stat-card {
        background: #2A2A2A;
    }
</style>

<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="section-title">
                Advertise With Us
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary text-lg max-w-3xl" style="font-family: 'Poppins', sans-serif;">
                Reach a highly engaged audience of movie buffs, TV show fans, and pop culture enthusiasts. Partner with Nazaara Circle to promote your brand, products, or services.
            </p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($totalArticles) }}+</div>
                <div class="stat-label">Published Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalCategories }}+</div>
                <div class="stat-label">Content Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">Growing</div>
                <div class="stat-label">Active Community</div>
            </div>
        </div>

        <!-- Why Advertise Section -->
        <div class="content-card mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Why Advertise on Nazaara Circle?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Targeted Audience</h3>
                        <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">Reach movie buffs, entertainment enthusiasts, and pop culture fans actively seeking new content and trends.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">High Engagement</h3>
                        <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">Our readers are highly engaged with quality content, ensuring your ads get maximum visibility.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Cost-Effective</h3>
                        <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">Competitive pricing with flexible packages to suit businesses of all sizes.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Trusted Platform</h3>
                        <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">A reputable entertainment platform trusted by thousands of fans for quality content.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advertising Packages -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-6 text-center" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Advertising Packages
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Package -->
                <div class="ad-package">
                    <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-weight: 700;">Basic</h3>
                    <div class="package-price mb-4">
                        Custom
                        <span class="package-period">/month</span>
                    </div>
                    <ul class="feature-list text-gray-700 dark:!text-text-secondary text-sm">
                        <li>Banner Advertisement</li>
                        <li>Sidebar Placement</li>
                        <li>30 Days Duration</li>
                        <li>Basic Analytics</li>
                        <li>Email Support</li>
                    </ul>
                    <a href="{{ route('contact') }}?subject=Basic Advertising Package" class="block w-full mt-6 px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:!bg-bg-card-hover dark:!hover:bg-bg-card text-gray-900 dark:!text-white rounded-lg font-semibold text-center transition-colors">
                        Contact Us
                    </a>
                </div>

                <!-- Premium Package -->
                <div class="ad-package featured relative">
                    <div class="absolute top-4 right-4 bg-accent text-white px-3 py-1 rounded-full text-xs font-semibold">
                        POPULAR
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-weight: 700;">Premium</h3>
                    <div class="package-price mb-4">
                        Custom
                        <span class="package-period">/month</span>
                    </div>
                    <ul class="feature-list text-gray-700 dark:!text-text-secondary text-sm">
                        <li>Premium Banner Placement</li>
                        <li>Multiple Ad Positions</li>
                        <li>60 Days Duration</li>
                        <li>Advanced Analytics</li>
                        <li>Priority Support</li>
                        <li>Featured Article Integration</li>
                    </ul>
                    <a href="{{ route('contact') }}?subject=Premium Advertising Package" class="block w-full mt-6 px-6 py-3 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold text-center transition-colors">
                        Contact Us
                    </a>
                </div>

                <!-- Enterprise Package -->
                <div class="ad-package">
                    <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-weight: 700;">Enterprise</h3>
                    <div class="package-price mb-4">
                        Custom
                        <span class="package-period">/month</span>
                    </div>
                    <ul class="feature-list text-gray-700 dark:!text-text-secondary text-sm">
                        <li>Custom Ad Solutions</li>
                        <li>All Premium Features</li>
                        <li>90+ Days Duration</li>
                        <li>Dedicated Account Manager</li>
                        <li>24/7 Priority Support</li>
                        <li>Content Partnership Options</li>
                        <li>Custom Campaign Design</li>
                    </ul>
                    <a href="{{ route('contact') }}?subject=Enterprise Advertising Package" class="block w-full mt-6 px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:!bg-bg-card-hover dark:!hover:bg-bg-card text-gray-900 dark:!text-white rounded-lg font-semibold text-center transition-colors">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>

        <!-- Ad Formats -->
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 md:p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Available Ad Formats
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:!text-white mb-3" style="font-weight: 600;">Display Advertising</h3>
                    <ul class="space-y-2 text-gray-600 dark:!text-text-secondary text-sm">
                        <li>• Banner Ads (728x90, 300x250, 320x50)</li>
                        <li>• Sidebar Advertisements</li>
                        <li>• In-Article Placements</li>
                        <li>• Header/Footer Banners</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:!text-white mb-3" style="font-weight: 600;">Content Integration</h3>
                    <ul class="space-y-2 text-gray-600 dark:!text-text-secondary text-sm">
                        <li>• Sponsored Articles</li>
                        <li>• Product Reviews</li>
                        <li>• Guest Posts</li>
                        <li>• Newsletter Sponsorships</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-gradient-to-r from-accent/10 to-accent/5 border border-accent/20 rounded-lg p-6 md:p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Ready to Get Started?
            </h2>
            <p class="text-gray-700 dark:!text-text-secondary mb-6 max-w-2xl mx-auto" style="font-weight: 400;">
                Contact our advertising team to discuss your campaign goals, get custom pricing, and learn how we can help promote your brand to our engaged audience.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}?subject=Advertising Inquiry" class="inline-block px-8 py-3 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold transition-all hover:scale-105" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Contact Advertising Team
                </a>
                <a href="mailto:muhamamdmaaz65@gmail.com?subject=Advertising Inquiry" class="inline-block px-8 py-3 bg-white dark:!bg-bg-card hover:bg-gray-50 dark:!hover:bg-bg-card-hover text-gray-900 dark:!text-white border border-gray-300 dark:!border-border-primary rounded-lg font-semibold transition-all" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Email Us Directly
                </a>
            </div>
            <p class="text-sm text-gray-600 dark:!text-text-secondary mt-6" style="font-weight: 400;">
                <strong>Email:</strong> <a href="mailto:muhamamdmaaz65@gmail.com" class="text-accent hover:underline">muhamamdmaaz65@gmail.com</a><br>
                Please include "Advertising Inquiry" in your subject line for faster response.
            </p>
        </div>

        <!-- Guidelines -->
        <div class="mt-12 bg-gray-50 dark:!bg-bg-card-hover border border-gray-200 dark:!border-border-secondary rounded-lg p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Advertising Guidelines
            </h2>
            <div class="space-y-3 text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                <p>• All advertisements must comply with our editorial standards and content policies</p>
                <p>• We reserve the right to reject any advertisement that doesn't align with our values</p>
                <p>• Advertisements should be relevant to our entertainment-focused audience</p>
                <p>• We do not accept advertisements for illegal products or services</p>
                <p>• All ad creatives must be provided in approved formats and sizes</p>
                <p>• Campaign performance reports are available upon request</p>
            </div>
        </div>
        <div class="who-we-are">
            <div class="who-title">Who We Are</div>
            <p class="who-text">
                NazaaraBox is your premier destination for the latest in movies, TV shows, and celebrity news. 
                We bring you cutting-edge news, in-depth reviews, and engaging stories from around the globe. 
                Our mission is to inform, inspire, and entertain our diverse audience with high-quality content 
                that matters.
            </p>
            <a href="{{ route('about') }}" class="who-btn">Read More</a>
        </div>
    </div>
</div>
@endsection

