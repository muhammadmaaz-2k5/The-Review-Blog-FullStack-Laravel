<div class="mt-16 bg-white dark:!bg-bg-card rounded-3xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm">
    <div class="p-8 border-b border-gray-100 dark:!border-border-primary">
        <h2 class="text-3xl font-black text-gray-900 dark:!text-white uppercase tracking-tighter" style="font-family: 'Poppins', sans-serif;">
            Frequently Asked <span class="text-accent">Questions</span>
        </h2>
    </div>
    
    <div class="p-8 space-y-6" x-data="{ activeFaq: null }">
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">What is NAZAARACIRCLE.COM?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 1 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 1" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>NAZAARACIRCLE.COM is a premier destination for entertainment enthusiasts, offering the latest news, movie reviews, celebrity biographies, and a powerful suite of free online tools for content creators and researchers.</p>
            </div>
        </div>

        <!-- Q2 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">How to Install OBB?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 2 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 2" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p class="mb-3">OBB files mostly belong to Android Studio by Google. OBB files are expansion files used by android applications to store additional data that would exceed the size limit of the main APK file.</p>
                <p class="font-bold text-gray-900 dark:!text-white mb-2 italic underline">Installation Steps:</p>
                <ol class="list-decimal list-inside space-y-2">
                    <li>First, Download APK and OBB File from our site.</li>
                    <li>We always provide OBB File into a .zip archive.</li>
                    <li>Extract OBB File using CX File Explorer and Copy OBB folder and paste into Android/obb. (Ex. Android/obb/com.pubg.imobile)</li>
                    <li>Install APK and Run, That’s it.</li>
                </ol>
            </div>
        </div>

        <!-- Q3 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">What is a APK Installer?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 3 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 3" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>Another way of Games installation. APK Installer made with attached OBB file and it’s a simple and fast way of installation of OBB Games.</p>
            </div>
        </div>

        <!-- Q4 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">Download is not Working?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 4 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 4" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>As we are providing a fast cloud storage link for downloading files, sometimes due to an error, the file will be unavailable for download. Please comment about this in our article below and we will fix it soon.</p>
            </div>
        </div>

        <!-- Q5 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 5 ? null : 5)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">What are the OBB Files?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 5 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 5" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>Some games and apps of advanced level come with cutting-edge graphics, simulation, sound, music, outlook, elements, and features. You need to install the OBB files with third-party apps to support those advanced features and elements.</p>
                <p class="mt-2 text-sm italic bg-gray-50 dark:!bg-bg-card-hover p-3 rounded-lg border-l-4 border-accent">When you download any app from the Play Store, then OBB files come integrated with them, so there is no need to install them from a third party.</p>
            </div>
        </div>

        <!-- Q6 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 6 ? null : 6)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">APK not Installing on your device?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 6 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 6" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>This is a common error happening when you have the same Game or APP installed on your device from Other Source.</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Please Uninstall the other same Game or APP from your device.</li>
                    <li>Try again, you will definitely be able to install it.</li>
                </ul>
            </div>
        </div>

        <!-- Q7 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 7 ? null : 7)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">Why is APK not working properly?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 7 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 7" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>There might be chances that the original game got updated, so you need to update its Mod version to work efficiently. Otherwise, it will not work. Download the latest Mod of the same App from NAZAARACIRCLE.COM.</p>
            </div>
        </div>

        <!-- Q8 -->
        <div class="border-b border-gray-100 dark:!border-border-primary pb-6">
            <button @click="activeFaq = (activeFaq === 8 ? null : 8)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">How to Install MODS APK?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 8 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 8" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p class="mb-2">To download and install any of the Mod versions, you need to follow the simple procedure given below:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>First, search the app at NAZAARACIRCLE.COM.</li>
                    <li>Scroll to the download link.</li>
                    <li>Tap on the download button.</li>
                    <li>Wait and then tap on the download link that appeared on the screen.</li>
                    <li>Please wait for it to download.</li>
                    <li>Tap on the install App.</li>
                    <li>Go to Setting > Privacy > Install from an unknown source.</li>
                    <li>Allow installation from an unknown source.</li>
                    <li>Wait for the installation process.</li>
                    <li>Now, enjoy the application or game.</li>
                </ul>
            </div>
        </div>

        <!-- Q9 -->
        <div class="pb-2">
            <button @click="activeFaq = (activeFaq === 9 ? null : 9)" class="flex items-center justify-between w-full text-left group">
                <span class="text-lg font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">Can I get paid Apps for free in Mod?</span>
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="activeFaq === 9 ? 'rotate-180 text-accent' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeFaq === 9" x-collapse x-cloak class="mt-4 text-gray-600 dark:!text-text-secondary leading-relaxed" style="font-family: 'Poppins', sans-serif;">
                <p>Yes, download any of the premium application mods here on our website, and you will get the premium version of the app for free with all the pro benefits and features unlocked.</p>
            </div>
        </div>
    </div>
</div>
