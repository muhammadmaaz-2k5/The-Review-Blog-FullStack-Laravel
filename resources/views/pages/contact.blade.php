@extends('layouts.app')

@section('title', 'Contact Us - Nazaara Circle')

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
            Contact Us
        </h1>

        <div class="content-card space-y-6">
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We'd love to hear from you! Whether you have questions, suggestions, feedback, or just want to say hello, feel free to reach out to us.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Get in Touch</h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    You can contact us through the following methods:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 mb-4" style="font-weight: 400;">
                    <li>Email us at: <a href="mailto:muhamamdmaaz65@gmail.com" class="text-accent hover:text-accent-light underline" style="font-weight: 600;">muhamamdmaaz65@gmail.com</a></li>
                    <li>Follow us on social media for updates and announcements</li>
                    <li>Submit article ideas or guest post proposals</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">Contact Form</h2>
                
                <!-- Form Out of Service Notice -->
                <div class="mb-6 p-6 bg-yellow-50 dark:!bg-yellow-900/20 border-2 border-yellow-400 dark:!border-yellow-600 rounded-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-600 dark:!text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-yellow-800 dark:!text-yellow-200 mb-2" style="font-weight: 700;">
                                Contact Form Currently Out of Service
                            </h3>
                            <p class="text-yellow-700 dark:!text-yellow-300 leading-relaxed mb-3" style="font-weight: 400;">
                                We apologize for the inconvenience, but our contact form is currently unavailable. Please reach out to us directly via email at <a href="mailto:muhamamdmaaz65@gmail.com" class="text-yellow-900 dark:!text-yellow-100 underline font-semibold" style="font-weight: 600;">muhamamdmaaz65@gmail.com</a> and we'll get back to you as soon as possible.
                            </p>
                            <p class="text-yellow-700 dark:!text-yellow-300 text-sm" style="font-weight: 400;">
                                We're working to restore the contact form functionality. Thank you for your patience!
                            </p>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:!bg-green-900/20 dark:!border-green-600 dark:!text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:!bg-red-900/20 dark:!border-red-600 dark:!text-red-200">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Disabled Form (Visual Only) -->
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4 opacity-60 pointer-events-none" style="position: relative;">
                    @csrf
                    <div class="absolute inset-0 z-10 bg-gray-100/50 dark:!bg-gray-800/50 rounded-lg flex items-center justify-center">
                        <p class="text-gray-600 dark:!text-gray-400 font-semibold text-lg" style="font-weight: 600;">Form Currently Unavailable</p>
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required disabled
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white cursor-not-allowed"
                               placeholder="Your name">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required disabled
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white cursor-not-allowed"
                               placeholder="your.email@example.com">
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="subject" name="subject" required disabled
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white cursor-not-allowed"
                               placeholder="Subject of your message">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" name="message" rows="5" required disabled
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white cursor-not-allowed"
                                  placeholder="Your message..."></textarea>
                    </div>
                    <button type="submit" disabled
                            class="w-full px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-accent-light transition-colors cursor-not-allowed">
                        Send Message
                    </button>
                </form>
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
