@extends('layouts.app')

@section('title', $career->title . ' - Careers - Nazaara Circle')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('careers') }}" class="text-accent hover:text-accent-light font-semibold text-sm flex items-center">
                ← Back to Careers
            </a>
        </div>

        <!-- Job Header -->
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 md:p-8 mb-6">
            @if($career->is_featured)
            <div class="mb-4">
                <span class="inline-block px-3 py-1 bg-accent text-white text-xs font-semibold rounded-full">Featured Position</span>
            </div>
            @endif
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $career->title }}
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @if($career->department)
                <div class="flex items-center text-gray-700 dark:!text-text-secondary">
                    <svg class="w-5 h-5 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <div class="text-xs text-gray-500 dark:!text-text-tertiary">Department</div>
                        <div class="font-semibold">{{ $career->department }}</div>
                    </div>
                </div>
                @endif
                <div class="flex items-center text-gray-700 dark:!text-text-secondary">
                    <svg class="w-5 h-5 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs text-gray-500 dark:!text-text-tertiary">Location</div>
                        <div class="font-semibold">{{ $career->location ?? 'Not specified' }}</div>
                    </div>
                </div>
                <div class="flex items-center text-gray-700 dark:!text-text-secondary">
                    <svg class="w-5 h-5 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs text-gray-500 dark:!text-text-tertiary">Job Type</div>
                        <div class="font-semibold capitalize">{{ str_replace('-', ' ', $career->type) }}</div>
                    </div>
                </div>
                @if($career->experience_level)
                <div class="flex items-center text-gray-700 dark:!text-text-secondary">
                    <svg class="w-5 h-5 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <div>
                        <div class="text-xs text-gray-500 dark:!text-text-tertiary">Experience</div>
                        <div class="font-semibold capitalize">{{ $career->experience_level }}</div>
                    </div>
                </div>
                @endif
                @if($career->salary_range)
                <div class="flex items-center text-gray-700 dark:!text-text-secondary">
                    <svg class="w-5 h-5 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs text-gray-500 dark:!text-text-tertiary">Salary</div>
                        <div class="font-semibold">{{ $career->salary_range }}</div>
                    </div>
                </div>
                @endif
                @if($career->application_deadline)
                <div class="flex items-center text-gray-700 dark:!text-text-secondary">
                    <svg class="w-5 h-5 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <div class="text-xs text-gray-500 dark:!text-text-tertiary">Application Deadline</div>
                        <div class="font-semibold {{ $career->isDeadlinePassed() ? 'text-red-500' : '' }}">
                            {{ $career->application_deadline->format('F d, Y') }}
                            @if($career->isDeadlinePassed())
                            <span class="text-xs">(Expired)</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Job Description -->
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Job Description
            </h2>
            <div class="prose dark:!prose-invert max-w-none text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif;">
                {!! nl2br(e($career->description)) !!}
            </div>
        </div>

        <!-- Requirements -->
        @if($career->requirements)
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Requirements
            </h2>
            <div class="prose dark:!prose-invert max-w-none text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif;">
                {!! nl2br(e($career->requirements)) !!}
            </div>
        </div>
        @endif

        <!-- Apply Section -->
        <div class="bg-gradient-to-r from-accent/10 to-accent/5 border border-accent/20 rounded-lg p-6 md:p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Ready to Apply?
            </h2>
            <p class="text-gray-700 dark:!text-text-secondary mb-6" style="font-family: 'Poppins', sans-serif;">
                Interested in this position? Send us your resume and cover letter.
            </p>
            <a href="{{ route('contact') }}?subject=Application for {{ urlencode($career->title) }}" class="inline-block px-8 py-3 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold transition-all hover:scale-105" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Apply Now
            </a>
        </div>

        <!-- Related Jobs -->
        @if($relatedCareers->count() > 0)
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Related Positions
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($relatedCareers as $related)
                <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <h3 class="font-bold text-gray-900 dark:!text-white mb-2">
                        <a href="{{ route('careers.show', $related->slug) }}" class="hover:text-accent transition-colors">
                            {{ $related->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-600 dark:!text-text-secondary">
                        {{ ucfirst(str_replace('-', ' ', $related->type)) }}
                        @if($related->location) • {{ $related->location }} @endif
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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

