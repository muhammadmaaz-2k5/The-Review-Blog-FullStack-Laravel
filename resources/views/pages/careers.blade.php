@extends('layouts.app')

@section('title', 'Careers - Nazaara Circle')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="font-poppins text-4xl font-extrabold uppercase tracking-tight text-gray-900 dark:text-white relative inline-block mb-6 after:content-[''] after:block after:w-3/5 after:h-1 after:bg-[#E50914] after:mt-2">
                Careers at Nazaara Circle
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary text-lg max-w-3xl" style="font-family: 'Poppins', sans-serif;">
                Join our team and help shape the future of entertainment journalism. We're looking for passionate individuals who share our commitment to quality content and innovation in the world of cinema and pop culture.
            </p>
        </div>

        <!-- Filters -->
        <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 dark:border-border-secondary dark:bg-bg-card">
            <form method="GET" action="{{ route('careers') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Types</option>
                    <option value="full-time" {{ request('type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ request('type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="remote" {{ request('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                    <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
                <select name="department" class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors font-semibold">
                    Filter
                </button>
            </form>
        </div>

        <!-- Careers Grid -->
        @if($careers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($careers as $career)
                    <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 hover:shadow-lg transition-shadow {{ $career->is_featured ? 'ring-2 ring-accent' : '' }}">
                        @if($career->is_featured)
                        <div class="mb-3">
                            <span class="inline-block px-3 py-1 bg-accent text-white text-xs font-semibold rounded-full">Featured</span>
                        </div>
                        @endif
                        <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                            <a href="{{ route('careers.show', $career->slug) }}" class="hover:text-accent transition-colors">
                                {{ $career->title }}
                            </a>
                        </h3>
                        <div class="space-y-2 mb-4">
                            @if($career->department)
                            <div class="flex items-center text-sm text-gray-600 dark:!text-text-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $career->department }}
                            </div>
                            @endif
                            <div class="flex items-center text-sm text-gray-600 dark:!text-text-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $career->location ?? 'Not specified' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:!text-text-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $career->type)) }}
                            </div>
                            @if($career->salary_range)
                            <div class="flex items-center text-sm text-gray-600 dark:!text-text-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $career->salary_range }}
                            </div>
                            @endif
                        </div>
                        <p class="text-gray-700 dark:!text-text-secondary text-sm mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($career->description), 150) }}
                        </p>
                        <div class="flex items-center justify-between">
                            <a href="{{ route('careers.show', $career->slug) }}" class="text-accent hover:text-accent-light font-semibold text-sm">
                                View Details →
                            </a>
                            @if($career->application_deadline)
                            <span class="text-xs text-gray-500 dark:!text-text-secondary">
                                Deadline: {{ $career->application_deadline->format('M d') }}
                            </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $careers->links() }}
            </div>
        @else
            <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:!text-white mb-2">No Openings Found</h3>
                <p class="text-gray-500 dark:!text-text-secondary">
                    We couldn't find any job openings matching your criteria. Please try adjusting your filters or check back later.
                </p>
                <a href="{{ route('careers') }}" class="inline-block mt-4 text-accent hover:text-accent-light font-medium">
                    Clear Filters
                </a>
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
