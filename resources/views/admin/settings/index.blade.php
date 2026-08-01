@extends('layouts.app')

@section('title', 'Admin Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Settings
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage application settings and integrations
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
        {{ session('error') }}
    </div>
    @endif

    <!-- Facebook Integration -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Facebook Page Integration
                </h2>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Automatically share articles to your Facebook page when published
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Status:
                </span>
                @if($facebookEnabled && $facebookPageId)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold dark:!bg-green-900/20 dark:!text-green-400">
                        Active
                    </span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold dark:!bg-gray-800 dark:!text-gray-400">
                        Inactive
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.settings.facebook.update') }}" method="POST" id="facebookForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Enable/Disable -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="enabled" value="1" {{ $facebookEnabled ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Enable Facebook Auto-Posting
                        </span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        When enabled, articles will be automatically posted to your Facebook page when published
                    </p>
                </div>

                <!-- Page ID -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Facebook Page ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="page_id" value="{{ old('page_id', $facebookPageId) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="e.g., 123456789012345">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        Your Facebook Page ID. You can find this in your Page Settings or use the Facebook Graph API Explorer.
                    </p>
                </div>

                <!-- Page Access Token -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Page Access Token <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="page_access_token" value="{{ old('page_access_token') }}" 
                           placeholder="{{ config('services.facebook.page_access_token') ? '••••••••••••••••' : 'Enter your Facebook Page Access Token' }}"
                           {{ config('services.facebook.page_access_token') ? '' : 'required' }}
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="Enter your Facebook Page Access Token">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        A long-lived Page Access Token with <code class="bg-gray-100 dark:!bg-gray-800 px-1 rounded">pages_manage_posts</code> permission. 
                        <a href="https://developers.facebook.com/tools/explorer/" target="_blank" class="text-accent hover:underline">Get token from Graph API Explorer</a>
                    </p>
                </div>

                <!-- API Version -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        API Version
                    </label>
                    <input type="text" name="api_version" value="{{ old('api_version', $facebookApiVersion) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="v18.0">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        Facebook Graph API version (default: v18.0)
                    </p>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 dark:!bg-blue-900/10 border border-blue-200 dark:!border-blue-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-900 dark:!text-blue-400 mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        How to Get Facebook Page Access Token:
                    </h3>
                    <ol class="list-decimal list-inside space-y-1 text-xs text-blue-800 dark:!text-blue-300" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        <li>Go to <a href="https://developers.facebook.com/tools/explorer/" target="_blank" class="underline">Facebook Graph API Explorer</a></li>
                        <li>Select your Facebook App (or create one if needed)</li>
                        <li>Get a User Access Token with <code class="bg-blue-100 dark:!bg-blue-900/20 px-1 rounded">pages_manage_posts</code> permission</li>
                        <li>Exchange it for a Page Access Token using: <code class="bg-blue-100 dark:!bg-blue-900/20 px-1 rounded">GET /me/accounts</code></li>
                        <li>Copy the Page Access Token and Page ID from the response</li>
                        <li>For long-lived tokens, use: <code class="bg-blue-100 dark:!bg-blue-900/20 px-1 rounded">GET /oauth/access_token?grant_type=fb_exchange_token</code></li>
                    </ol>
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Save Settings
                </button>
                <button type="button" id="testFacebookBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Test Connection
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Twitter/X Integration -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Twitter/X Integration
                </h2>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Automatically share articles to your Twitter/X account when published
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Status:</span>
                @if($twitterEnabled && config('services.twitter.bearer_token'))
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold dark:!bg-green-900/20 dark:!text-green-400">Active</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold dark:!bg-gray-800 dark:!text-gray-400">Inactive</span>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.settings.twitter.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="enabled" value="1" {{ $twitterEnabled ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Enable Twitter Auto-Posting
                        </span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Bearer Token <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="bearer_token" value="{{ old('bearer_token') }}" 
                           placeholder="{{ config('services.twitter.bearer_token') ? '••••••••••••••••' : 'Enter your Twitter Bearer Token' }}"
                           {{ config('services.twitter.bearer_token') ? '' : 'required' }}
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        Twitter API v2 Bearer Token. Get it from <a href="https://developer.twitter.com/en/portal/dashboard" target="_blank" class="text-accent hover:underline">Twitter Developer Portal</a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        API Version
                    </label>
                    <input type="text" name="api_version" value="{{ old('api_version', $twitterApiVersion) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="2">
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Save Settings</button>
                <button type="button" onclick="testConnection('twitter')" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Test Connection</button>
            </div>
        </form>
    </div>

    <!-- Instagram Integration -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Instagram Integration
                </h2>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Automatically share articles to your Instagram account when published (requires featured image)
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Status:</span>
                @if($instagramEnabled && $instagramPageId)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold dark:!bg-green-900/20 dark:!text-green-400">Active</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold dark:!bg-gray-800 dark:!text-gray-400">Inactive</span>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.settings.instagram.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="enabled" value="1" {{ $instagramEnabled ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Enable Instagram Auto-Posting
                        </span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Instagram Business Account ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="page_id" value="{{ old('page_id', $instagramPageId) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="e.g., 123456789012345">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Access Token <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="access_token" value="{{ old('access_token') }}" 
                           placeholder="{{ config('services.instagram.access_token') ? '••••••••••••••••' : 'Enter your Instagram Access Token' }}"
                           {{ config('services.instagram.access_token') ? '' : 'required' }}
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        Instagram Graph API Access Token with <code class="bg-gray-100 dark:!bg-gray-800 px-1 rounded">instagram_basic</code> and <code class="bg-gray-100 dark:!bg-gray-800 px-1 rounded">pages_show_list</code> permissions
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        API Version
                    </label>
                    <input type="text" name="api_version" value="{{ old('api_version', $instagramApiVersion) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="v18.0">
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Save Settings</button>
                <button type="button" onclick="testConnection('instagram')" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Test Connection</button>
            </div>
        </form>
    </div>

    <!-- Threads Integration -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Threads Integration
                </h2>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Automatically share articles to your Threads account when published
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Status:</span>
                @if($threadsEnabled && $threadsPageId)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold dark:!bg-green-900/20 dark:!text-green-400">Active</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold dark:!bg-gray-800 dark:!text-gray-400">Inactive</span>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.settings.threads.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="enabled" value="1" {{ $threadsEnabled ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Enable Threads Auto-Posting
                        </span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Threads Account ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="page_id" value="{{ old('page_id', $threadsPageId) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="e.g., 123456789012345">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Access Token <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="access_token" value="{{ old('access_token') }}" 
                           placeholder="{{ config('services.threads.access_token') ? '••••••••••••••••' : 'Enter your Threads Access Token' }}"
                           {{ config('services.threads.access_token') ? '' : 'required' }}
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">
                        Threads API Access Token with <code class="bg-gray-100 dark:!bg-gray-800 px-1 rounded">threads_basic</code> and <code class="bg-gray-100 dark:!bg-gray-800 px-1 rounded">threads_content_publish</code> permissions
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        API Version
                    </label>
                    <input type="text" name="api_version" value="{{ old('api_version', $threadsApiVersion) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="v18.0">
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Save Settings</button>
                <button type="button" onclick="testConnection('threads')" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Test Connection</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testBtn = document.getElementById('testFacebookBtn');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            testConnection('facebook', this);
        });
    }
});

function testConnection(platform, button = null) {
    const btn = button || event.target;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Testing...';
    
    const routes = {
        'facebook': '{{ route("admin.settings.facebook.test") }}',
        'twitter': '{{ route("admin.settings.twitter.test") }}',
        'instagram': '{{ route("admin.settings.instagram.test") }}',
        'threads': '{{ route("admin.settings.threads.test") }}',
    };
    
    fetch(routes[platform], {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = originalText;
        
        if (data.success) {
            const platformName = platform.charAt(0).toUpperCase() + platform.slice(1);
            const info = data.page_info?.name || data.user_info?.data?.name || 'Connected';
            alert(`✅ ${platformName} connection successful!\n\n${info}`);
        } else {
            alert(`❌ ${platform.charAt(0).toUpperCase() + platform.slice(1)} connection failed: ${data.message || 'Unknown error'}`);
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.textContent = originalText;
        alert(`❌ Error testing connection: ${error.message}`);
    });
}
</script>
@endsection

