

<?php $__env->startSection('title', 'Admin - Media Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Media Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                View and manage all uploaded images
            </p>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <!-- Media Grid -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group relative rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden bg-gray-50 dark:!bg-bg-card-hover transition-all hover:shadow-lg">
                    <div class="aspect-square relative flex items-center justify-center overflow-hidden">
                        <?php if(in_array(strtolower($file['extension']), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])): ?>
                            <img src="<?php echo e($file['url']); ?>" alt="<?php echo e($file['name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <?php else: ?>
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        <?php endif; ?>
                        
                        <!-- Actions Overlay -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="<?php echo e($file['url']); ?>" target="_blank" class="p-2 bg-white rounded-full text-gray-900 hover:bg-gray-100" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <button onclick="copyToClipboard('<?php echo e($file['url']); ?>')" class="p-2 bg-white rounded-full text-purple-600 hover:bg-gray-100" title="Copy URL">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                            <form action="<?php echo e(route('admin.media.destroy')); ?>" method="POST" onsubmit="return confirm('Delete this file?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="path" value="<?php echo e($file['path']); ?>">
                                <button type="submit" class="p-2 bg-white rounded-full text-red-600 hover:bg-gray-100" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-2">
                        <p class="text-[10px] text-gray-500 truncate" title="<?php echo e($file['name']); ?>"><?php echo e($file['name']); ?></p>
                        <p class="text-[10px] font-bold text-accent"><?php echo e(strtoupper($file['extension'])); ?> • <?php echo e(number_format($file['size'] / 1024, 1)); ?> KB</p>
                        <p class="text-[9px] text-gray-400 capitalize"><?php echo e(str_replace('articles/', '', $file['directory'])); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-12 text-center text-gray-500">
                    No media files found.
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8">
            <?php echo e($files->links()); ?>

        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('URL copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\New folder (2)\resources\views/admin/media/index.blade.php ENDPATH**/ ?>