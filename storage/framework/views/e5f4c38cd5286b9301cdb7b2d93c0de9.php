

<?php $__env->startSection('title', 'Send a Tip - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen bg-[#141414] text-white overflow-hidden flex flex-col justify-center py-16">
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-[#141414] via-[#0d0d0d] to-[#1a1a1a] opacity-90"></div>
        
        <!-- Animated Glows -->
        <div class="absolute top-0 right-0 w-2/3 h-full bg-gradient-to-l from-accent/10 to-transparent blur-3xl opacity-40"></div>
        <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-purple-900/10 blur-3xl rounded-full opacity-30"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">
            
            <!-- Left Side: Content/Pitch -->
            <div class="lg:sticky lg:top-24">
                <div class="mb-8">
                    <span class="inline-block px-3 py-1 bg-accent/20 text-accent border border-accent/20 rounded-full text-xs font-bold uppercase tracking-wider mb-6 shadow-[0_0_15px_rgba(229,9,20,0.3)]">
                        Confidential & Secure
                    </span>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-none uppercase tracking-tighter" style="font-family: 'Poppins', sans-serif;">
                        Got a <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-500 drop-shadow-lg">Scoop?</span>
                    </h1>
                    <p class="text-xl text-gray-400 max-w-lg font-light leading-relaxed">
                        Be the source. Share breaking news, leaks, or hidden gems with our editorial team.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="group p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:border-accent/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg mb-1" style="font-family: 'Poppins', sans-serif;">Breaking News</h3>
                                <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">First to know? Let the world know through us.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:border-accent/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg mb-1" style="font-family: 'Poppins', sans-serif;">Product Leaks</h3>
                                <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Got specs or photos of unreleased tech?</p>
                            </div>
                        </div>
                    </div>

                    <div class="group p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:border-accent/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-xl bg-purple-500/10 text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg mb-1" style="font-family: 'Poppins', sans-serif;">Multimedia</h3>
                                <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">We accept Imgur links for images and YouTube links for videos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-accent to-purple-600 rounded-[2rem] blur opacity-20"></div>
                <div class="relative bg-[#1a1a1a]/80 backdrop-blur-xl border border-white/10 rounded-[1.5rem] p-8 md:p-10 shadow-2xl">
                    
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                            <span class="w-1.5 h-6 bg-accent rounded-sm shadow-[0_0_10px_#E50914]"></span>
                            Submit Details
                        </h2>
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                    </div>

                    <div id="react-submit-tip-root" 
                         data-captcha-question="<?php echo e($captchaQuestion ?? '42 = ?'); ?>" 
                         data-csrf-token="<?php echo e(csrf_token()); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/tips/create.blade.php ENDPATH**/ ?>