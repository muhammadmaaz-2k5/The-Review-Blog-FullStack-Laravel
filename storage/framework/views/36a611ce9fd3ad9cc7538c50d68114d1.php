<?php $__env->startSection('title', 'My Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            My Dashboard
        </h1>
        <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Welcome back, <?php echo e(auth()->user()->name); ?>! Here's an overview of your activity.
        </p>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:!bg-green-900/20 dark:!border-green-800 dark:!text-green-400" style="font-family: 'Poppins', sans-serif;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:!bg-red-900/20 dark:!border-red-800 dark:!text-red-400" style="font-family: 'Poppins', sans-serif;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Author Request Section -->
    <?php if(!auth()->user()->isAuthor()): ?>
        <?php
            $authorStatus = 'none';
            if (isset($authorRequest) && $authorRequest) {
                if ($authorRequest->isPending()) $authorStatus = 'pending';
                elseif ($authorRequest->isRejected()) $authorStatus = 'rejected';
            }
        ?>
        <div id="react-become-author-root" 
             data-csrf-token="<?php echo e(csrf_token()); ?>"
             data-status="<?php echo e($authorStatus); ?>"
             data-message="<?php echo e(isset($authorRequest) ? $authorRequest->message : ''); ?>"
             data-admin-notes="<?php echo e(isset($authorRequest) ? $authorRequest->admin_notes : ''); ?>"
             data-submitted-date="<?php echo e(isset($authorRequest) && $authorRequest ? $authorRequest->created_at->format('F j, Y \a\t g:i A') : ''); ?>">
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Bookmarks -->
        <a href="<?php echo e(route('bookmarks.index')); ?>" class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Bookmarks
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalBookmarks)); ?>

                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:!bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                <?php echo e($bookmarksThisMonth); ?> added this month
            </div>
            <div class="mt-4 text-sm text-blue-600 dark:!text-blue-400 font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                View All →
            </div>
        </a>

        <!-- Comments -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Comments
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalComments)); ?>

                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:!bg-purple-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600 dark:!text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                <?php echo e($commentsThisMonth); ?> this month
            </div>
        </div>

        <!-- Likes -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Liked Articles
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalLikes)); ?>

                    </p>
                </div>
                <div class="p-3 bg-red-100 dark:!bg-red-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-red-600 dark:!text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Articles you've liked
            </div>
        </div>

        <!-- Reading History -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Reading History
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalReadingHistory)); ?>

                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:!bg-green-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:!text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Articles you've read
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Bookmarks -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Bookmarks
                </h2>
                <a href="<?php echo e(route('bookmarks.index')); ?>" class="text-sm text-accent hover:text-accent-light font-semibold transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    View All →
                </a>
            </div>
            
            <?php if($recentBookmarks->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentBookmarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookmark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="<?php echo e(route('articles.show', $bookmark->article)); ?>" class="hover:text-accent transition-colors">
                                    <?php echo e($bookmark->article->title); ?>

                                </a>
                            </h3>
                            <?php if($bookmark->article->category): ?>
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                    <?php echo e($bookmark->article->category->name); ?>

                                </span>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Bookmarked <?php echo e($bookmark->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No bookmarks yet. Start bookmarking articles you like!
                </p>
            <?php endif; ?>
        </div>

        <!-- Recent Comments -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Comments
                </h2>
            </div>
            
            <?php if($recentComments->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="<?php echo e(route('articles.show', $comment->article)); ?>#comment-<?php echo e($comment->id); ?>" class="hover:text-accent transition-colors">
                                    <?php echo e($comment->article->title); ?>

                                </a>
                            </h3>
                            <p class="text-sm text-gray-700 dark:!text-text-secondary mb-2 line-clamp-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e(Str::limit($comment->content, 100)); ?>

                            </p>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e($comment->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No comments yet. Start engaging with articles!
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Additional Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reading History -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Reading History
                </h2>
            </div>
            
            <?php if($recentReadingHistory->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentReadingHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="<?php echo e(route('articles.show', $history->article)); ?>" class="hover:text-accent transition-colors">
                                    <?php echo e($history->article->title); ?>

                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <?php if($history->article->category): ?>
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                            <?php echo e($history->article->category->name); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($history->progress > 0): ?>
                                        <span class="ml-2 text-xs text-gray-500 dark:!text-text-tertiary">
                                            <?php echo e($history->progress); ?>% read
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <?php echo e($history->last_read_at->diffForHumans()); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No reading history yet.
                </p>
            <?php endif; ?>
        </div>

        <!-- Liked Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Liked Articles
                </h2>
            </div>
            
            <?php if($likedArticles->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $likedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $like): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="<?php echo e(route('articles.show', $like->article)); ?>" class="hover:text-accent transition-colors">
                                    <?php echo e($like->article->title); ?>

                                </a>
                            </h3>
                            <?php if($like->article->category): ?>
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                    <?php echo e($like->article->category->name); ?>

                                </span>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Liked <?php echo e($like->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No liked articles yet. Start liking articles you enjoy!
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/user/dashboard.blade.php ENDPATH**/ ?>