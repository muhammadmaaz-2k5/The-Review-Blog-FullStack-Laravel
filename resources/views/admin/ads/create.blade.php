@extends('layouts.app')

@section('title', 'Create New Ad')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.ads.index') }}" class="text-gray-600 hover:text-accent dark!:text-text-secondary dark!:hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                &larr; Back to Ads
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark!:text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Create New Ad
        </h1>
        <p class="text-gray-600 dark!:text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Add a new ad unit to your site
        </p>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark!:bg-red-900/20 dark!:border-red-700 dark!:text-red-400">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark!:bg-bg-card rounded-lg border border-gray-200 dark!:border-border-secondary overflow-hidden">
        <form action="{{ route('admin.ads.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Ad Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. 728x90 Banner After Editor's Picks"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                    <p class="text-xs text-gray-500 mt-1">A descriptive name for this ad unit</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="auto-generated-from-name"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                    <p class="text-xs text-gray-500 mt-1">Auto-generated from name if left empty</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Placement <span class="text-red-500">*</span></label>
                    <select name="placement" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                        <option value="">Select placement...</option>
                        @foreach($placementOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('placement') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Where on the site this ad will appear</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Ad Type <span class="text-red-500">*</span></label>
                    <select name="type" required id="ad_type_select"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                        @foreach($typeOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers shown first for same placement</p>
                </div>
            </div>

            <!-- Ad Code -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Ad Code (HTML/JS)</label>
                <textarea name="ad_code" rows="12"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm font-mono"
                          placeholder="Paste the full ad HTML/JavaScript code here...">{{ old('ad_code') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Paste the complete ad code from Adsterra, AdSense, or any other provider</p>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Description / Notes</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm"
                          placeholder="Internal notes about this ad...">{{ old('description') }}</textarea>
            </div>

            <!-- Active Toggle -->
            <div class="mb-6 flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                <label for="is_active" class="text-sm font-semibold text-gray-700 dark!:text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Active (show this ad on the site)</label>
            </div>

            <!-- Quick Fill Templates -->
            <div class="mb-6 bg-gray-50 dark!:bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark!:border-border-secondary">
                <p class="text-sm font-semibold text-gray-700 dark!:text-white mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Quick Fill Templates</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="fillTemplate('banner')" class="px-3 py-1.5 bg-white dark!:bg-bg-card border border-gray-200 dark!:border-border-secondary rounded-lg text-xs font-semibold text-gray-700 dark!:text-text-secondary hover:border-accent hover:text-accent transition-colors">
                        Adsterra Banner
                    </button>
                    <button type="button" onclick="fillTemplate('native')" class="px-3 py-1.5 bg-white dark!:bg-bg-card border border-gray-200 dark!:border-border-secondary rounded-lg text-xs font-semibold text-gray-700 dark!:text-text-secondary hover:border-accent hover:text-accent transition-colors">
                        Adsterra Native
                    </button>
                    <button type="button" onclick="fillTemplate('popunder')" class="px-3 py-1.5 bg-white dark!:bg-bg-card border border-gray-200 dark!:border-border-secondary rounded-lg text-xs font-semibold text-gray-700 dark!:text-text-secondary hover:border-accent hover:text-accent transition-colors">
                        Adsterra Popunder
                    </button>
                    <button type="button" onclick="fillTemplate('socialbar')" class="px-3 py-1.5 bg-white dark!:bg-bg-card border border-gray-200 dark!:border-border-secondary rounded-lg text-xs font-semibold text-gray-700 dark!:text-text-secondary hover:border-accent hover:text-accent transition-colors">
                        Adsterra Social Bar
                    </button>
                    <button type="button" onclick="fillTemplate('smartlink')" class="px-3 py-1.5 bg-white dark!:bg-bg-card border border-gray-200 dark!:border-border-secondary rounded-lg text-xs font-semibold text-gray-700 dark!:text-text-secondary hover:border-accent hover:text-accent transition-colors">
                        Adsterra Smartlink
                    </button>
                    <button type="button" onclick="fillTemplate('adsense')" class="px-3 py-1.5 bg-white dark!:bg-bg-card border border-gray-200 dark!:border-border-secondary rounded-lg text-xs font-semibold text-gray-700 dark!:text-text-secondary hover:border-accent hover:text-accent transition-colors">
                        Google AdSense
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark!:border-border-secondary">
                <button type="submit" class="px-6 py-2.5 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Create Ad
                </button>
                <a href="{{ route('admin.ads.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark!:bg-bg-card dark!:text-white dark!:hover:bg-bg-card-hover font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const templates = {
    banner: `<div class="ad-container my-8 flex justify-center w-full overflow-hidden">\n    <script>\n        atOptions = {\n            'key' : 'YOUR_AD_KEY',\n            'format' : 'iframe',\n            'height' : 90,\n            'width' : 728,\n            'params' : {}\n        };\n    <\/script>\n    <script src="https://www.highperformanceformat.com/YOUR_AD_KEY/invoke.js"><\/script>\n</div>`,
    native: `<div class="ad-container my-8 flex justify-center w-full overflow-hidden">\n    <script async="async" data-cfasync="false" src="https://plXXXXXXXXXX.profitablecpmratenetwork.com/YOUR_CONTAINER_ID/invoke.js"><\/script>\n    <div id="container-YOUR_CONTAINER_ID"><\/div>\n</div>`,
    popunder: `<script src="https://plXXXXXXXXXX.profitablecpmratenetwork.com/XX/XX/XX/YOUR_SCRIPT_ID.js"><\/script>`,
    socialbar: `<script src="https://plXXXXXXXXXX.profitablecpmratenetwork.com/XX/XX/XX/YOUR_SCRIPT_ID.js"><\/script>`,
    smartlink: `<a href="https://www.profitablecpmratenetwork.com/XXXXXXXXXX?key=YOUR_SMARTLINK_KEY" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg transition-all text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">\n    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"><\/path><\/svg>\n    <span>Sponsored<\/span>\n<\/a>`,
    adsense: `@if(config('services.adsense.client_id'))\n    <div class="mb-8 text-center">\n        <ins class="adsbygoogle" style="display:block"\n            data-ad-client="{{ config('services.adsense.client_id') }}"\n            data-ad-slot="{{ config('services.adsense.unit_1') }}" data-ad-format="auto"\n            data-full-width-responsive="true"><\/ins>\n        <script>\n            (adsbygoogle = window.adsbygoogle || []).push({});\n        <\/script>\n    </div>\n@endif`
};

function fillTemplate(type) {
    const textarea = document.querySelector('textarea[name="ad_code"]');
    if (textarea && templates[type]) {
        textarea.value = templates[type];
        textarea.dispatchEvent(new Event('input'));
    }
}
</script>
@endsection
