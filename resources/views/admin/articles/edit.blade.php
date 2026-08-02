@extends('layouts.app')

@section('title', 'Edit Article - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.articles.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Articles
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Edit Article
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Update article: {{ $article->title }}
            </p>
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
        
        <!-- Easy Import Section -->
        <div class="mb-8 border-b border-gray-200 dark:!border-border-secondary pb-6">
            <button type="button" onclick="document.getElementById('importSection').classList.toggle('hidden')" 
                    class="flex items-center gap-2 text-accent hover:text-accent-dark font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                ⚡ Quick Import (JSON / HTML)
            </button>
            
            <div id="importSection" class="hidden mt-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark:!border-border-primary">
                <div class="flex gap-4 mb-4 border-b border-gray-200 dark:!border-border-secondary">
                    <button type="button" onclick="switchImportTab('json')" id="tab-json" class="px-4 py-2 text-sm font-semibold text-accent border-b-2 border-accent">JSON Import</button>
                    <button type="button" onclick="switchImportTab('html')" id="tab-html" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 dark:!text-text-secondary">HTML Import</button>
                </div>

                <!-- JSON Tab -->
                <div id="content-json">
                    <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-2">Paste your JSON object below. Keys should match form fields (title, slug, excerpt, content, etc.).</p>
                    <textarea id="jsonInput" rows="5" class="w-full font-mono text-xs p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent dark:!bg-bg-card dark:!border-border-primary dark:!text-white" placeholder='{"title": "My Article", "content": "<p>Content...</p>"} చేయండి'></textarea>
                    <div class="mt-2 flex gap-2">
                        <button type="button" onclick="importJson()" class="px-3 py-1.5 bg-accent text-white text-sm rounded hover:bg-accent-dark transition-colors">Import JSON</button>
                        <button type="button" onclick="loadJsonExample()" class="px-3 py-1.5 text-gray-600 hover:bg-gray-200 rounded text-sm transition-colors dark:!text-text-secondary dark:!hover:bg-bg-card">Load Example</button>
                    </div>
                </div>

                <!-- HTML Tab -->
                <div id="content-html" class="hidden">
                    <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-2">Paste HTML structure. Use <code>data-field="fieldname"</code> attributes to map to specific fields, or standard tags (h1 -> title).</p>
                    <textarea id="htmlInput" rows="5" class="w-full font-mono text-xs p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent dark:!bg-bg-card dark:!border-border-primary dark:!text-white" placeholder='<article><h1 data-field="title">Title</h1><div data-field="content">Content...</div></article>'></textarea>
                    <div class="mt-2 flex gap-2">
                        <button type="button" onclick="importHtml()" class="px-3 py-1.5 bg-accent text-white text-sm rounded hover:bg-accent-dark transition-colors">Import HTML</button>
                        <button type="button" onclick="loadHtmlExample()" class="px-3 py-1.5 text-gray-600 hover:bg-gray-200 rounded text-sm transition-colors dark:!text-text-secondary dark:!hover:bg-bg-card">Load Example</button>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               placeholder="Enter article title">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Slug
                        </label>
                        <input type="text" name="slug" value="{{ old('slug', $article->slug) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               placeholder="auto-generated-from-title">
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Leave empty to auto-generate from title</p>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Excerpt
                        </label>
                        <textarea name="excerpt" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                  placeholder="Short description of the article">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>

                    <!-- Quick Answer (GEO/AI SEO) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Quick Answer (GEO/AI SEO)
                        </label>
                        <textarea name="quick_answer" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                  placeholder="2-4 sentence definitive answer for AI search engines">{{ old('quick_answer', $article->quick_answer) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Directly answers the main question of the article for AI answer extraction.</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <button type="button" id="templateDropdownBtn" 
                                        class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card flex items-center gap-2"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    📄 Load Template <span class="text-xs">▼</span>
                                </button>
                                <div id="templateDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-primary rounded-lg shadow-lg z-50" style="max-height: 400px; overflow-y: auto;">
                                    <div class="p-2">
                                        <div class="text-xs font-semibold text-gray-500 dark:!text-text-tertiary px-3 py-2 mb-1">Reviews & Analysis</div>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="review">🎬 Movie/TV Review</button>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="explained">🧐 Ending Explained</button>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="biography">👤 Celebrity Biography</button>
                                        
                                        <div class="text-xs font-semibold text-gray-500 dark:!text-text-tertiary px-3 py-2 mt-3 mb-1">News & Lists</div>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="news">📰 Entertainment News</button>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="listicle">📋 Top 10 List</button>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="shorts">🎬 YouTube Shorts Embed</button>
                                        <button type="button" class="template-option w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:!hover:bg-bg-card-hover rounded" data-template="comparison">🆚 Character/Movie Comparison</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <textarea name="content" id="content" class="tinymce-editor w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                  placeholder="Write your article content here...">{!! old('content', $article->rendered_content) !!}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Select a template from the dropdown to use a structured article template (will replace current content)</p>
                    </div>

                    <!-- Featured Image -->
                        <input type="text" name="featured_image" value="{{ old('featured_image', $article->featured_image) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               placeholder="https://example.com/image.jpg or /storage/image.jpg">
                        
                        @if($article->featured_image)
                            <div class="mt-2">
                                @php
                                    $imageUrl = str_starts_with($article->featured_image, 'http') 
                                        ? $article->featured_image 
                                        : asset($article->featured_image);
                                    // Handle leading slash
                                    if (!str_starts_with($article->featured_image, 'http') && !str_starts_with($article->featured_image, '/')) {
                                        $imageUrl = asset('storage/' . $article->featured_image);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Current featured image" class="w-32 h-32 object-cover rounded" onerror="this.style.display='none'">
                            </div>
                        @endif
                    </div>

                    <!-- Featured Image File -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Upload New Featured Image (Auto-converts to WebP)
                        </label>
                        <input type="file" name="featured_image_file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Recommended: JPEG, PNG, or WebP. It will be converted to optimized WebP.</p>
                    </div>

                    <!-- Featured Image SEO -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Image Alt Text (SEO)
                            </label>
                            <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt', $article->featured_image_alt) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                   placeholder="Describe the image content">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Image Title (SEO)
                            </label>
                            <input type="text" name="featured_image_title" value="{{ old('featured_image_title', $article->featured_image_title) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                   placeholder="Title of the image">
                        </div>
                    </div>

                    <!-- YouTube Short Video Embed -->
                    <div class="bg-gray-50 dark:!bg-bg-card-hover p-4 rounded-lg border border-gray-200 dark:!border-border-primary">
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            YouTube Short Video ID
                        </label>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <input type="text" name="short_video_id" id="short_video_id" value="{{ old('short_video_id', $article->short_video_id) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card dark:!border-border-primary dark:!text-white"
                                       placeholder="e.g. pVRefGmQDJM">
                                <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Paste the video ID from the YouTube URL (e.g. pVRefGmQDJM)</p>
                            </div>
                            <div id="short_preview_container" class="{{ $article->short_video_id ? '' : 'hidden' }}">
                                <div class="relative w-[150px] aspect-[9/16] bg-black rounded-lg overflow-hidden shadow-lg">
                                    <iframe id="short_preview_iframe" class="w-full h-full" src="{{ $article->short_video_id ? 'https://www.youtube.com/embed/' . $article->short_video_id : '' }}" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="scheduled" {{ old('status', $article->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>

                    <!-- Published At -->
                    <div id="published_at_field" class="{{ old('status', $article->status) === 'scheduled' ? 'block' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Publish Date & Time
                        </label>
                        <input type="datetime-local" name="published_at" 
                               value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Category
                        </label>
                        <select name="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                            <option value="">No Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Series -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Series
                        </label>
                        <select name="series_id" id="series_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                            <option value="">No Series</option>
                            @if(isset($series))
                                @foreach($series as $ser)
                                    <option value="{{ $ser->id }}" {{ old('series_id', $article->series_id) == $ser->id ? 'selected' : '' }}>{{ $ser->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Select a series to add this article to</p>
                    </div>

                    <!-- Series Order -->
                    <div id="series_order_field" class="{{ old('series_id', $article->series_id) ? 'block' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Order in Series
                        </label>
                        <input type="number" name="series_order" value="{{ old('series_order', $article->series_order ?? 1) }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               placeholder="1">
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Position of this article in the series</p>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Tags
                        </label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 dark:!bg-bg-card-hover dark:!border-border-primary">
                            @foreach($tags as $tag)
                                <label class="flex items-center gap-2 mb-2 cursor-pointer">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                           {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-accent focus:ring-accent">
                                    <span class="text-sm text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3">
                        <input type="hidden" name="is_featured" value="0">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-accent focus:ring-accent">
                            <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Featured Article</span>
                        </label>
                        <input type="hidden" name="allow_comments" value="0">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', $article->allow_comments) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-accent focus:ring-accent">
                            <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Allow Comments</span>
                        </label>
                    </div>
                    
                    <!-- Social Media Posting Section -->
                    @if(config('services.facebook.enabled', false) || config('services.twitter.enabled', false) || config('services.instagram.enabled', false) || config('services.threads.enabled', false))
                    <div class="bg-blue-50 dark:!bg-blue-900/20 border border-blue-200 dark:!border-blue-800 rounded-lg p-4 mt-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <h3 class="text-sm font-bold text-blue-900 dark:!text-blue-100" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Share to Social Media</h3>
                        </div>
                        <p class="text-xs text-blue-700 dark:!text-blue-300 mb-3" style="font-family: 'Poppins', sans-serif;">Automatically share this article when published</p>
                        <div class="flex flex-wrap gap-3">
                        @if(config('services.facebook.enabled', false))
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:!bg-gray-800 px-3 py-2 rounded-lg border border-blue-200 dark:!border-blue-700 hover:bg-blue-50 dark:!hover:bg-blue-900/30 transition-colors">
                            <input type="checkbox" name="post_to_facebook" value="1" {{ old('post_to_facebook', $article->status === 'published' ? true : false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <svg class="w-4 h-4 text-blue-600 dark:!text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="text-sm font-semibold text-blue-900 dark:!text-blue-100" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Facebook</span>
                        </label>
                        @endif
                        @if(config('services.twitter.enabled', false))
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:!bg-gray-800 px-3 py-2 rounded-lg border border-blue-200 dark:!border-blue-700 hover:bg-blue-50 dark:!hover:bg-blue-900/30 transition-colors">
                            <input type="checkbox" name="post_to_twitter" value="1" {{ old('post_to_twitter', false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <svg class="w-4 h-4 text-blue-600 dark:!text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <span class="text-sm font-semibold text-blue-900 dark:!text-blue-100" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Twitter/X</span>
                        </label>
                        @endif
                        @if(config('services.instagram.enabled', false))
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:!bg-gray-800 px-3 py-2 rounded-lg border border-blue-200 dark:!border-blue-700 hover:bg-blue-50 dark:!hover:bg-blue-900/30 transition-colors">
                            <input type="checkbox" name="post_to_instagram" value="1" {{ old('post_to_instagram', false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <svg class="w-4 h-4 text-blue-600 dark:!text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span class="text-sm font-semibold text-blue-900 dark:!text-blue-100" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Instagram</span>
                        </label>
                        @endif
                        @if(config('services.threads.enabled', false))
                        <label class="flex items-center gap-2 cursor-pointer bg-white dark:!bg-gray-800 px-3 py-2 rounded-lg border border-blue-200 dark:!border-blue-700 hover:bg-blue-50 dark:!hover:bg-blue-900/30 transition-colors">
                            <input type="checkbox" name="post_to_threads" value="1" {{ old('post_to_threads', false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-blue-900 dark:!text-blue-100" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Threads</span>
                        </label>
                        @endif
                        </div>
                    </div>
                    @endif

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $article->sort_order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    </div>
                </div>
            </div>



            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Update Article
                </button>
                <a href="{{ route('admin.articles.revisions', $article) }}" class="px-6 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors dark:!bg-purple-900/20 dark:!text-purple-400 dark:!hover:bg-purple-900/30" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    View Revisions
                </a>
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    const publishedAtField = document.getElementById('published_at_field');
    const form = document.querySelector('form');
    const articleId = '{{ $article->id }}';
    
    const facebookCheckbox = document.querySelector('input[name="post_to_facebook"]');
    
    function togglePublishedAt() {
        if (statusSelect.value === 'scheduled') {
            publishedAtField.classList.remove('hidden');
            publishedAtField.classList.add('block');
        } else {
            publishedAtField.classList.add('hidden');
            publishedAtField.classList.remove('block');
        }
    }
    
    // Auto-check Facebook when status is published
    function toggleFacebookCheckbox() {
        if (facebookCheckbox && statusSelect) {
            if (statusSelect.value === 'published') {
                // Auto-check Facebook when status is published (user can still uncheck if needed)
                if (!facebookCheckbox.hasAttribute('data-user-unchecked')) {
                    facebookCheckbox.checked = true;
                }
            }
        }
    }
    
    // Track if user manually unchecks Facebook
    if (facebookCheckbox) {
        facebookCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                this.setAttribute('data-user-unchecked', 'true');
            } else {
                this.removeAttribute('data-user-unchecked');
            }
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            togglePublishedAt();
            toggleFacebookCheckbox();
        });
    }
    
    togglePublishedAt();
    toggleFacebookCheckbox();
    
    // Series order field toggle
    const seriesSelect = document.querySelector('select[name="series_id"]');
    const seriesOrderField = document.getElementById('series_order_field');
    
    function toggleSeriesOrder() {
        if (seriesSelect && seriesOrderField) {
            if (seriesSelect.value) {
                seriesOrderField.classList.remove('hidden');
                seriesOrderField.classList.add('block');
            } else {
                seriesOrderField.classList.add('hidden');
                seriesOrderField.classList.remove('block');
            }
        }
    }
    
    if (seriesSelect) {
        seriesSelect.addEventListener('change', toggleSeriesOrder);
    }

    // YouTube Short Preview Logic
    const shortVideoIdInput = document.getElementById('short_video_id');
    const shortPreviewContainer = document.getElementById('short_preview_container');
    const shortPreviewIframe = document.getElementById('short_preview_iframe');

    function updateShortPreview() {
        const videoId = shortVideoIdInput.value.trim();
        if (videoId) {
            shortPreviewIframe.src = `https://www.youtube.com/embed/${videoId}`;
            shortPreviewContainer.classList.remove('hidden');
        } else {
            shortPreviewIframe.src = '';
            shortPreviewContainer.classList.add('hidden');
        }
    }

    if (shortVideoIdInput) {
        shortVideoIdInput.addEventListener('input', updateShortPreview);
        updateShortPreview(); // Initialize on page load if there's an old value
    }

    // Auto-save functionality
    let autoSaveTimer;
    let isSaving = false;
    let lastSavedContent = '';
    
    function autoSave() {
        if (isSaving) return;
        
        const formData = new FormData(form);
        const currentContent = tinymce.get('content') ? tinymce.get('content').getContent() : formData.get('content');
        
        // Only save if content has changed
        if (currentContent === lastSavedContent) return;
        
        isSaving = true;
        
        // Get TinyMCE content if available
        if (tinymce.get('content')) {
            formData.set('content', tinymce.get('content').getContent());
        }
        
        fetch(`/admin/articles/${articleId}/auto-save`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            isSaving = false;
            if (data.success) {
                lastSavedContent = currentContent;
                showAutoSaveIndicator('saved');
            }
        })
        .catch(error => {
            isSaving = false;
            console.error('Auto-save error:', error);
        });
    }
    
    function showAutoSaveIndicator(status) {
        let indicator = document.getElementById('auto-save-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'auto-save-indicator';
            indicator.className = 'fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 transition-all';
            document.body.appendChild(indicator);
        }
        
        if (status === 'saving') {
            indicator.className = 'fixed bottom-4 right-4 px-4 py-2 bg-yellow-500 text-white rounded-lg shadow-lg z-50 transition-all';
            indicator.textContent = 'Saving...';
        } else if (status === 'saved') {
            indicator.className = 'fixed bottom-4 right-4 px-4 py-2 bg-green-500 text-white rounded-lg shadow-lg z-50 transition-all';
            indicator.textContent = 'Draft saved';
            setTimeout(() => {
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 2000);
        }
    }
    
    // Auto-save on input (debounced)
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(autoSaveTimer);
            showAutoSaveIndicator('saving');
            autoSaveTimer = setTimeout(autoSave, 3000); // Save after 3 seconds of inactivity
        });
    });
    
    // Auto-save on TinyMCE content change
    if (tinymce.get('content')) {
        tinymce.get('content').on('keyup', () => {
            clearTimeout(autoSaveTimer);
            showAutoSaveIndicator('saving');
            autoSaveTimer = setTimeout(autoSave, 3000);
        });
    }
    
    // Load Template functionality
    const templateDropdownBtn = document.getElementById('templateDropdownBtn');
    const templateDropdown = document.getElementById('templateDropdown');
    const titleInput = document.querySelector('input[name="title"]');
    const categorySelect = document.querySelector('select[name="category_id"]');
    
    // Toggle dropdown
    if (templateDropdownBtn && templateDropdown) {
        templateDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            templateDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!templateDropdown.contains(e.target) && !templateDropdownBtn.contains(e.target)) {
                templateDropdown.classList.add('hidden');
            }
        });
        
        // Handle template selection
        const templateOptions = document.querySelectorAll('.template-option');
        templateOptions.forEach(option => {
            option.addEventListener('click', function() {
                if (!confirm('This will replace your current content with the template. Are you sure?')) {
                    return;
                }
                
                const templateType = this.getAttribute('data-template');
                const title = titleInput.value || 'Your Article Title';
                const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || 'Technology';
                
                // Get the selected template
                const template = getTemplate(templateType, title, categoryName);
                
                // Insert into TinyMCE if available, otherwise into textarea
                if (tinymce.get('content')) {
                    tinymce.get('content').setContent(template);
                } else {
                    document.getElementById('content').value = template;
                }
                
                // Close dropdown
                templateDropdown.classList.add('hidden');
                
                // Show success message
                showNotification('Template loaded successfully!', 'success');
            });
        });
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-4 py-2 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white rounded-lg shadow-lg z-50 transition-all`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }
    
    function getTemplate(templateType, title, categoryName) {
        const templates = {
            review: getReviewTemplate(title, categoryName),
            explained: getExplainedTemplate(title, categoryName),
            biography: getBiographyTemplate(title, categoryName),
            news: getNewsTemplate(title, categoryName),
            listicle: getListTemplate(title, categoryName),
            shorts: getShortsTemplate(title, categoryName),
            comparison: getComparisonTemplate(title, categoryName)
        };
        
        return templates[templateType] || getReviewTemplate(title, categoryName);
    }

    function getReviewTemplate(title, categoryName) {
        return `<h1>${title} Review</h1>

<p><strong>Verdict:</strong> [One sentence summary of your rating]</p>

<figure class="image"><img title="${title}" src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1200&h=630&fit=crop" alt="${title}" width="1200" height="630" />
<figcaption>${title} - Official Poster/Scene</figcaption>
</figure>

<h2>The Plot</h2>

<p>Provide a brief synopsis of the plot without giving away major spoilers. Set the scene and introduce the main characters.</p>

<h2>The Good</h2>

<ul>
<li><strong>Acting:</strong> Standout performances...</li>
<li><strong>Direction:</strong> Visual style and pacing...</li>
<li><strong>Script:</strong> Dialogue and story structure...</li>
</ul>

<h2>The Bad</h2>

<ul>
<li><strong>Pacing:</strong> Does it drag in the middle?</li>
<li><strong>Plot Holes:</strong> Any inconsistencies?</li>
</ul>

<h2>Analysis</h2>

<p>Dive deeper into the themes, cinematography, and overall execution. How does it compare to other works in the genre?</p>

<h2>Conclusion</h2>

<p>Final thoughts and recommendation. Is it worth watching in theaters or waiting for streaming?</p>

<p><strong>Rating:</strong> ⭐⭐⭐⭐☆ (4/5)</p>`;
    }

    function getExplainedTemplate(title, categoryName) {
        return `<h1>${title} Ending Explained</h1>

<p><strong>Warning: Major Spoilers Ahead!</strong></p>

<figure class="image"><img title="${title}" src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1200&h=630&fit=crop" alt="${title}" width="1200" height="630" />
<figcaption>Key scene from ${title}</figcaption>
</figure>

<h2>The Ending Recap</h2>

<p>Walk through the final scenes of the movie/show. What actually happened?</p>

<h2>The Twist Explained</h2>

<p>Break down the plot twist or ambiguous ending. What does it mean?</p>

<h2>Hidden Details & Easter Eggs</h2>

<ul>
<li><strong>Detail 1:</strong> Significance...</li>
<li><strong>Detail 2:</strong> Connection to...</li>
</ul>

<h2>Theories & Speculation</h2>

<p>What are fans saying? What is your interpretation?</p>

<h2>What This Means for a Sequel</h2>

<p>Does the ending set up a sequel? What unresolved plot threads remain?</p>`;
    }

    function getBiographyTemplate(title, categoryName) {
        return `<h1>${title}: Biography & Career</h1>

<p><strong>Occupation:</strong> Actor / Director / Musician<br>
<strong>Born:</strong> [Date], [Place]<br>
<strong>Best Known For:</strong> [Role/Work]</p>

<figure class="image"><img title="${title}" src="https://images.unsplash.com/photo-1493612276216-ee3925520721?w=1200&h=630&fit=crop" alt="${title}" width="1200" height="630" />
<figcaption>${title}</figcaption>
</figure>

<h2>Early Life</h2>

<p>Details about their upbringing, education, and early interests.</p>

<h2>Career Breakthrough</h2>

<p>The role or project that launched their career.</p>

<h2>Notable Works</h2>

<ul>
<li><strong>Movie/Show (Year):</strong> Role</li>
<li><strong>Movie/Show (Year):</strong> Role</li>
</ul>

<h2>Personal Life</h2>

<p>Public information about their personal life, philanthropy, etc.</p>

<h2>Awards & Achievements</h2>

<ul>
<li>Award 1</li>
<li>Award 2</li>
</ul>`;
    }
    
    function getNewsTemplate(title, categoryName) {
        return `<h1>${title}</h1>

<p class="text-gray-600 dark:!text-text-secondary"><em>Published: [Date] | Category: ${categoryName}</em></p>

<figure class="image"><img title="${title}" src="https://images.unsplash.com/photo-1499364615650-ec38552f4f34?w=1200&h=630&fit=crop" alt="${title}" width="1200" height="630" />
<figcaption>Image related to the news</figcaption>
</figure>

<h2>The Scoop</h2>

<p><strong>[Location/Date]</strong> - Breaking news details. Who, what, when, where?</p>

<h2>Why It Matters</h2>

<p>Context about why this news is important for fans/the industry.</p>

<h2>Fan Reactions</h2>

<p>How is the internet reacting? Trending hashtags, general sentiment.</p>

<h2>What's Next?</h2>

<p>What can we expect to see following this announcement/event?</p>`;
    }

    function getListTemplate(title, categoryName) {
        return `<h1>${title}</h1>

<p>Introduction to the list. Why these picks?</p>

<h2>10. [Entry Name]</h2>

<figure class="image"><img title="Entry 10" src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1200&h=630&fit=crop" alt="Entry 10" width="1200" height="630" />
</figure>

<p>Why it made the list.</p>

<h2>9. [Entry Name]</h2>
<p>Description...</p>

<h2>8. [Entry Name]</h2>
<p>Description...</p>

<!-- Continue list... -->

<h2>1. [Entry Name] - Our Top Pick</h2>

<figure class="image"><img title="Top Pick" src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1200&h=630&fit=crop" alt="Top Pick" width="1200" height="630" />
</figure>

<p>Why this is the number one choice.</p>

<h2>Honorable Mentions</h2>
<ul>
<li>Mention 1</li>
<li>Mention 2</li>
</ul>`;
    }

    function getShortsTemplate(title, categoryName) {
        return `<h1>${title}</h1>
<p>Check out this amazing YouTube Short about ${title}!</p>
<div style="display: flex; justify-content: center; margin: 20px 0;">
    <iframe width="315" height="560" src="https://www.youtube.com/embed/pVRefGmQDJM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
</div>
<p>Don't forget to like and subscribe for more content like this!</p>`;
    }

    function getComparisonTemplate(title, categoryName) {
        return `<h1>${title}</h1>

<p>Comparing [Subject A] and [Subject B]. Who does it better?</p>

<h2>The Contenders</h2>

<ul>
<li><strong>[Subject A]:</strong> Brief description.</li>
<li><strong>[Subject B]:</strong> Brief description.</li>
</ul>

<h2>Round 1: [Category, e.g., Story]</h2>

<p>Comparison of story/plot...</p>

<h2>Round 2: [Category, e.g., Visuals]</h2>

<p>Comparison of visuals/style...</p>

<h2>Round 3: [Category, e.g., Acting]</h2>

<p>Comparison of performances...</p>

<h2>The Verdict</h2>

<p>Who wins? Summary of the comparison.</p>`;
    }
});

// Easy Import Functions (Global)
function switchImportTab(tab) {
    document.getElementById('content-json').classList.toggle('hidden', tab !== 'json');
    document.getElementById('content-html').classList.toggle('hidden', tab !== 'html');
    
    document.getElementById('tab-json').classList.toggle('text-accent', tab === 'json');
    document.getElementById('tab-json').classList.toggle('border-b-2', tab === 'json');
    document.getElementById('tab-json').classList.toggle('border-accent', tab === 'json');
    document.getElementById('tab-json').classList.toggle('text-gray-500', tab !== 'json');
    
    document.getElementById('tab-html').classList.toggle('text-accent', tab === 'html');
    document.getElementById('tab-html').classList.toggle('border-b-2', tab === 'html');
    document.getElementById('tab-html').classList.toggle('border-accent', tab === 'html');
    document.getElementById('tab-html').classList.toggle('text-gray-500', tab !== 'html');
}

function loadJsonExample() {
    const example = {
        "title": "My Awesome Article",
        "slug": "my-awesome-article",
        "excerpt": "This is a short summary of the article.",
        "content": "<p>This is the main content of the article.</p><h2>Subtitle</h2><p>More content here.</p>",
        "category_id": "1",
        "tags": ["1", "2"],
        "featured_image": "https://example.com/image.jpg",
        "status": "draft"
    };
    document.getElementById('jsonInput').value = JSON.stringify(example, null, 4);
}

function loadHtmlExample() {
    const example = `<article>
<h1 data-field="title">My Awesome Article</h1>
<div data-field="excerpt">This is a short summary.</div>
<div data-field="content">
    <p>Main content starts here...</p>
    <p>More paragraphs.</p>
</div>
<div data-field="meta">
    <span data-name="slug">my-awesome-article</span>
    <span data-name="category_id">1</span>
    <span data-name="status">draft</span>
</div>
</article>`;
    document.getElementById('htmlInput').value = example;
}

function importJson() {
    try {
        const json = document.getElementById('jsonInput').value;
        const data = JSON.parse(json);
        fillForm(data);
        alert('Imported successfully!');
    } catch (e) {
        alert('Invalid JSON: ' + e.message);
    }
}

function importHtml() {
    const html = document.getElementById('htmlInput').value;
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    
    const data = {};
    
    // Extract fields based on data-field attribute
    const fields = doc.querySelectorAll('[data-field]');
    fields.forEach(el => {
        const field = el.getAttribute('data-field');
        if (field === 'meta') {
            // Handle nested meta tags
            const metas = el.querySelectorAll('[data-name]');
            metas.forEach(meta => {
                data[meta.getAttribute('data-name')] = meta.textContent.trim();
            });
        } else {
            data[field] = el.innerHTML.trim(); // Use innerHTML for content, others might need textContent
            if (field !== 'content') {
                 // Strip tags for non-content fields if needed, or just use textContent
                 if(field === 'title' || field === 'slug' || field === 'excerpt') {
                     data[field] = el.textContent.trim();
                 }
            }
        }
    });

    // Fallback: if no data-fields, try standard tags
    if (!data.title && doc.querySelector('h1')) data.title = doc.querySelector('h1').textContent.trim();
    if (!data.content && doc.body) {
         // Simplest is just use body if content not explicitly defined
         data.content = doc.body.innerHTML;
    }

    fillForm(data);
    alert('Imported successfully!');
}

function fillForm(data) {
    for (const [key, value] of Object.entries(data)) {
        const input = document.querySelector(`[name="${key}"]`);
        if (input) {
            if (input.type === 'checkbox') {
                input.checked = !!value;
            } else if (input.tagName === 'SELECT') {
                 input.value = value;
            } else {
                input.value = value;
            }
        }
        
        // Special handling for content (TinyMCE)
        if (key === 'content') {
            let processedValue = value;
            
            // Auto-convert YouTube Shorts URLs to embeds if they are not already in an iframe
            if (typeof processedValue === 'string' && processedValue.includes('youtube.com/shorts/')) {
                const shortsRegex = /(https?:\/\/www\.youtube\.com\/shorts\/([a-zA-Z0-9_-]+))/g;
                processedValue = processedValue.replace(shortsRegex, (match, url, id) => {
                    return `<div style="display: flex; justify-content: center; margin: 20px 0;">
    <iframe width="315" height="560" src="https://www.youtube.com/embed/${id}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
</div>`;
                });
            }

            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                tinymce.get('content').setContent(processedValue);
            } else {
                const contentArea = document.getElementById('content');
                if(contentArea) contentArea.value = processedValue;
            }
        }
        
        // Special handling for tags (array)
        if (key === 'tags' && Array.isArray(value)) {
            const checkboxes = document.querySelectorAll('input[name="tags[]"]');
            checkboxes.forEach(cb => {
                cb.checked = value.includes(cb.value) || value.includes(parseInt(cb.value));
            });
        }
    }
}
</script>
@endsection

