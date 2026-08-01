@extends('layouts.app')

@section('title', 'Edit Job - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.careers.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Careers
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Edit Job: {{ $career->title }}
            </h1>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <form action="{{ route('admin.careers.update', $career) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                        Job Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $career->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                        Slug
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $career->slug) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                        Job Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">{{ old('description', $career->description) }}</textarea>
                </div>

                <!-- Requirements -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                        Requirements
                    </label>
                    <textarea name="requirements" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">{{ old('requirements', $career->requirements) }}</textarea>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Location
                        </label>
                        <input type="text" name="location" value="{{ old('location', $career->location) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Job Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                            <option value="full-time" {{ old('type', $career->type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="part-time" {{ old('type', $career->type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ old('type', $career->type) == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="remote" {{ old('type', $career->type) == 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="internship" {{ old('type', $career->type) == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Department
                        </label>
                        <input type="text" name="department" value="{{ old('department', $career->department) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>

                    <!-- Experience Level -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Experience Level
                        </label>
                        <select name="experience_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                            <option value="">Select level</option>
                            <option value="entry" {{ old('experience_level', $career->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="mid" {{ old('experience_level', $career->experience_level) == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ old('experience_level', $career->experience_level) == 'senior' ? 'selected' : '' }}>Senior Level</option>
                            <option value="executive" {{ old('experience_level', $career->experience_level) == 'executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                    </div>

                    <!-- Salary Range -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Salary Range
                        </label>
                        <input type="text" name="salary_range" value="{{ old('salary_range', $career->salary_range) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>

                    <!-- Application Deadline -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Application Deadline
                        </label>
                        <input type="date" name="application_deadline" value="{{ old('application_deadline', $career->application_deadline ? $career->application_deadline->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-weight: 600;">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $career->sort_order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>
                </div>

                <!-- Checkboxes -->
                <div class="flex gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $career->is_active) ? 'checked' : '' }}
                               class="mr-2 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm text-gray-700 dark:!text-white" style="font-weight: 600;">Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $career->is_featured) ? 'checked' : '' }}
                               class="mr-2 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm text-gray-700 dark:!text-white" style="font-weight: 600;">Featured</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-weight: 600;">
                        Update Job
                    </button>
                    <a href="{{ route('admin.careers.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

