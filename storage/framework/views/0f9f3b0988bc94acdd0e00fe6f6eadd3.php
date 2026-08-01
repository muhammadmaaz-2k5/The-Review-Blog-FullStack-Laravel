<?php $__env->startSection('title', 'Register - Nazaara Circle'); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden bg-[#141414]">
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-[#000000] via-[#1a1a1a] to-[#000000] opacity-90"></div>
        
        <!-- Animated Glows -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-accent/20 blur-[100px] rounded-full opacity-50 animate-pulse"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-900/20 blur-[100px] rounded-full opacity-50 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10 flex flex-col items-center">


        <div id="react-clerk-sign-up"></div>
        <div id="clerk-loading" class="text-white text-center py-4 hidden">
            <span class="inline-block animate-spin text-accent">⏳</span> Loading registration...
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/auth/register.blade.php ENDPATH**/ ?>