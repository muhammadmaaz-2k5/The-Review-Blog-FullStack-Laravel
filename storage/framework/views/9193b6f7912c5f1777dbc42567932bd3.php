<?php $__env->startSection('title', 'Author Requests - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="<?php echo e(route('admin.authors.index')); ?>" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Authors
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Author Requests
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage requests to become an author
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('admin.authors.index')); ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                All Authors
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <!-- Status Filter -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <div class="flex gap-4">
            <a href="<?php echo e(route('admin.authors.requests', ['status' => 'pending'])); ?>" 
               class="px-4 py-2 rounded-lg transition-colors <?php echo e($status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:!bg-yellow-900/20 dark:!text-yellow-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'); ?>"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Pending
            </a>
            <a href="<?php echo e(route('admin.authors.requests', ['status' => 'approved'])); ?>" 
               class="px-4 py-2 rounded-lg transition-colors <?php echo e($status === 'approved' ? 'bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'); ?>"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Approved
            </a>
            <a href="<?php echo e(route('admin.authors.requests', ['status' => 'rejected'])); ?>" 
               class="px-4 py-2 rounded-lg transition-colors <?php echo e($status === 'rejected' ? 'bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'); ?>"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Rejected
            </a>
            <a href="<?php echo e(route('admin.authors.requests')); ?>" 
               class="px-4 py-2 rounded-lg transition-colors <?php echo e(!$status ? 'bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'); ?>"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                All
            </a>
        </div>
    </div>

    <!-- Requests List -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
        <?php if($requests->count() > 0): ?>
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="flex-shrink-0">
                                <?php if($request->user->avatar): ?>
                                    <img src="<?php echo e($request->user->avatar); ?>" alt="<?php echo e($request->user->name); ?>" class="w-16 h-16 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xl">
                                        <?php echo e(strtoupper(substr($request->user->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        <?php echo e($request->user->name); ?>

                                    </h3>
                                    <span class="px-2 py-1 bg-<?php echo e($request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red')); ?>-100 text-<?php echo e($request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red')); ?>-800 rounded text-xs dark:!bg-<?php echo e($request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red')); ?>-900/20 dark:!text-<?php echo e($request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red')); ?>-400">
                                        <?php echo e(ucfirst($request->status)); ?>

                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <?php echo e($request->user->email); ?>

                                </p>
                                <?php if($request->message): ?>
                                <div class="mb-2 p-3 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                                    <p class="text-sm text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        <strong>Message:</strong> <?php echo e($request->message); ?>

                                    </p>
                                </div>
                                <?php endif; ?>
                                <?php if($request->admin_notes): ?>
                                <div class="mb-2 p-3 bg-yellow-50 dark:!bg-yellow-900/10 rounded-lg">
                                    <p class="text-sm text-yellow-700 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        <strong>Admin Notes:</strong> <?php echo e($request->admin_notes); ?>

                                    </p>
                                </div>
                                <?php endif; ?>
                                <div class="flex items-center gap-4 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span>Submitted: <?php echo e($request->created_at->format('M j, Y g:i A')); ?></span>
                                    <?php if($request->reviewed_at): ?>
                                        <span>Reviewed: <?php echo e($request->reviewed_at->format('M j, Y g:i A')); ?></span>
                                        <?php if($request->reviewer): ?>
                                            <span>by <?php echo e($request->reviewer->name); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if($request->status === 'pending'): ?>
                        <div class="flex gap-2 ml-4">
                            <form action="<?php echo e(route('admin.authors.requests.approve', $request)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-semibold" 
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Approve
                                </button>
                            </form>
                            <button onclick="document.getElementById('reject-form-<?php echo e($request->id); ?>').classList.toggle('hidden')" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-semibold" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Reject
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($request->status === 'pending'): ?>
                    <div id="reject-form-<?php echo e($request->id); ?>" class="hidden mt-4 pt-4 border-t border-gray-200 dark:!border-border-secondary">
                        <form action="<?php echo e(route('admin.authors.requests.reject', $request)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Admin Notes (Optional)
                                </label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                          placeholder="Add notes about why this request was rejected..."></textarea>
                            </div>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-semibold" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Confirm Rejection
                            </button>
                            <button type="button" onclick="document.getElementById('reject-form-<?php echo e($request->id); ?>').classList.add('hidden')" 
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-semibold dark:!bg-bg-card-hover dark:!text-white ml-2" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Cancel
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:!border-border-secondary">
                <?php echo e($requests->links()); ?>

            </div>
        <?php else: ?>
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No <?php echo e($status ?? 'author requests'); ?> found.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/admin/authors/requests.blade.php ENDPATH**/ ?>