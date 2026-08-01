<div class="mt-16 bg-white dark:!bg-bg-card rounded-3xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm">
    <div class="p-8 border-b border-gray-100 dark:!border-border-primary">
        <h2 class="text-3xl font-black text-gray-900 dark:!text-white uppercase tracking-tighter" style="font-family: 'Poppins', sans-serif;">
            Frequently Asked <span class="text-blue-600">Questions</span>
        </h2>
    </div>
    
    <div class="p-8 space-y-6" x-data="{ activeFaq: null }">
        <!-- Q1 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Poppins', sans-serif;">What is NAZAARACIRCLE.COM?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 1 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 1" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>NAZAARACIRCLE.COM is a premier destination for entertainment enthusiasts, offering the latest news, movie reviews, celebrity biographies, and a powerful suite of free online tools for content creators and researchers.</p>
            </div>
        </div>

        <!-- Q2 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Poppins', sans-serif;">Is the YouTube Thumbnail Downloader free?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 2 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 2" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>Yes, our tool is 100% free to use. You can download as many thumbnails as you want without any hidden costs, subscriptions, or registration requirements.</p>
            </div>
        </div>

        <!-- Q3 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Poppins', sans-serif;">What thumbnail resolutions can I download?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 3 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 3" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>You can download thumbnails in several resolutions, including:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li><strong>HD Quality:</strong> 1280x720 pixels</li>
                    <li><strong>HQ Quality:</strong> 480x360 pixels</li>
                    <li><strong>Standard Quality:</strong> 320x180 pixels</li>
                    <li><strong>Medium/Small:</strong> Lower resolutions for thumbnails</li>
                </ul>
            </div>
        </div>

        <!-- Q4 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Poppins', sans-serif;">Is it legal to download YouTube thumbnails?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 4 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 4" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>Downloading thumbnails for personal use, research, or inspiration is generally fine. However, since the artwork belongs to the original creator, you should seek permission if you intend to reuse the thumbnail for your own commercial content or public projects.</p>
            </div>
        </div>

        <!-- Q5 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 5 ? null : 5)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Poppins', sans-serif;">Do I need to install any software?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 5 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 5" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>No installation is required. Our tool is entirely web-based and works directly in your browser on desktop, mobile, and tablets.</p>
            </div>
        </div>

        <!-- Q6 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 6 ? null : 6)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Poppins', sans-serif;">Can I download thumbnails from YouTube Shorts?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 6 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 6" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>Yes! Our tool supports YouTube Shorts, standard videos, and even live stream thumbnails. Simply paste the URL and we will extract the images for you.</p>
            </div>
        </div>
    </div>
</div>
