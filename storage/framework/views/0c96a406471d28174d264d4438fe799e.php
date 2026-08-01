

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Admin Dashboard
        </h1>
        <p class="text-sm sm:text-base text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Welcome to the admin panel. Here's an overview of your Nazaarabox Blog management system.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Total Articles
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalArticles)); ?>

                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:!bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 dark:!text-green-400 font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    <?php echo e(number_format($publishedArticles)); ?> Published
                </span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    <?php echo e(number_format($draftArticles)); ?> Draft
                </span>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Categories
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalCategories)); ?>

                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:!bg-purple-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600 dark:!text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Active categories
            </div>
        </div>

        <!-- Tags -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Tags
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalTags)); ?>

                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:!bg-green-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:!text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-600 dark:!text-text-secondary font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    <?php echo e(number_format($totalComments)); ?> Comments
                </span>
            </div>
        </div>

        <!-- Total Views -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Total Views
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        <?php echo e(number_format($totalViews)); ?>

                    </p>
                </div>
                <div class="p-3 bg-orange-100 dark:!bg-orange-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600 dark:!text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                All time views
            </div>
        </div>
    </div>



    <!-- Secondary Statistics -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <!-- Published Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Published</p>
            <p class="text-2xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($publishedArticles)); ?></p>
        </div>

        <!-- Draft Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Draft</p>
            <p class="text-2xl font-bold text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($draftArticles)); ?></p>
        </div>

        <!-- Featured Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Featured</p>
            <p class="text-2xl font-bold text-yellow-600 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($featuredArticles)); ?></p>
        </div>

        <!-- This Month -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">This Month</p>
            <p class="text-2xl font-bold text-blue-600 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($thisMonthArticles)); ?></p>
        </div>

        <!-- Total Authors -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Authors</p>
            <p class="text-2xl font-bold text-teal-600 dark:!text-teal-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($totalAuthors ?? 0)); ?></p>
        </div>

        <!-- Pending Author Requests -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Pending Requests</p>
            <p class="text-2xl font-bold text-orange-600 dark:!text-orange-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($pendingAuthorRequests ?? 0)); ?></p>
            <?php if(($pendingAuthorRequests ?? 0) > 0): ?>
                <a href="<?php echo e(route('admin.authors.requests')); ?>" class="text-xs text-accent hover:underline mt-1 block" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Review Now →
                </a>
            <?php endif; ?>
        </div>

        <!-- Active Careers -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Active Careers</p>
            <p class="text-2xl font-bold text-indigo-600 dark:!text-indigo-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($activeCareers ?? 0)); ?></p>
            <a href="<?php echo e(route('admin.careers.index')); ?>" class="text-xs text-accent hover:underline mt-1 block" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Manage →
            </a>
        </div>


    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Recent Articles -->
        <div class="lg:col-span-2 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Articles
                </h2>
                <a href="<?php echo e(route('admin.articles.index')); ?>" class="text-sm text-accent hover:underline font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    View All →
                </a>
            </div>
            
            <?php if($recentArticles->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded overflow-hidden bg-gray-200 dark:!bg-gray-700 flex-shrink-0">
                                <?php if($article->featured_image): ?>
                                    <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:!text-white mb-1 truncate" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    <?php echo e($article->title); ?>

                                </h3>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <?php if($article->category): ?>
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400"><?php echo e($article->category->name); ?></span>
                                    <?php endif; ?>
                                </p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span><?php echo e($article->created_at->diffForHumans()); ?></span>
                                    <?php if($article->status === 'published'): ?>
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded dark:!bg-green-900/20 dark:!text-green-400">Published</span>
                                    <?php elseif($article->status === 'draft'): ?>
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded dark:!bg-gray-800 dark:!text-gray-400">Draft</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded dark:!bg-blue-900/20 dark:!text-blue-400">Scheduled</span>
                                    <?php endif; ?>
                                    <span><?php echo e(number_format($article->views)); ?> views</span>
                                </div>
                            </div>
                            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                                <a href="<?php echo e(route('admin.articles.edit', $article)); ?>" class="block w-full sm:w-auto text-center px-3 py-1.5 sm:py-1 bg-accent hover:bg-accent-light text-white rounded text-sm transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Edit
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        No articles yet. <a href="<?php echo e(route('admin.articles.create')); ?>" class="text-accent hover:underline">Create your first article</a>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Quick Actions
                </h2>
                <div class="space-y-3">
                    <a href="<?php echo e(route('admin.articles.create')); ?>" class="block w-full px-4 py-3 bg-accent hover:bg-accent-light text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Add New Article
                    </a>
                    <a href="<?php echo e(route('admin.articles.index')); ?>" class="block w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 text-center rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card-hover font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Articles
                    </a>
                    <a href="<?php echo e(route('admin.media.index')); ?>" class="block w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Media / Images
                    </a>
                    <a href="<?php echo e(route('admin.featured-videos.index')); ?>" class="block w-full px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Featured Videos
                    </a>
                    
                    
                    
                    <a href="<?php echo e(route('admin.categories.index')); ?>" class="block w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Categories
                    </a>
                    <a href="<?php echo e(route('admin.tags.index')); ?>" class="block w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Tags
                    </a>
                    <a href="<?php echo e(route('admin.page-seo.index')); ?>" class="block w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Public Pages SEO Management
                    </a>
                    <a href="<?php echo e(route('admin.authors.index')); ?>" class="block w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Authors
                    </a>
                    <a href="<?php echo e(route('admin.authors.requests')); ?>" class="block w-full px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Author Requests
                    </a>
                    <a href="<?php echo e(route('admin.series.index')); ?>" class="block w-full px-4 py-3 bg-pink-600 hover:bg-pink-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Manage Series
                    </a>
                    <a href="<?php echo e(route('admin.analytics.index')); ?>" class="block w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        📊 View Analytics
                    </a>
                    <a href="<?php echo e(route('admin.tips.index')); ?>" class="block w-full px-4 py-3 bg-cyan-600 hover:bg-cyan-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        💡 Tips Management
                    </a>
                    <a href="<?php echo e(route('admin.careers.index')); ?>" class="block w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        💼 Careers Management
                    </a>

                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="block w-full px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white text-center rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        ⚙️ Settings
                    </a>
                </div>
            </div>

            <!-- Articles by Category -->
            <?php if($articlesByCategory->count() > 0): ?>
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Articles by Category
                </h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $articlesByCategory->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;"><?php echo e($category->name); ?></span>
                            <span class="font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;"><?php echo e(number_format($category->articles_count)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Analytics -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        Analytics
                    </h2>
                    <a href="<?php echo e(route('admin.analytics.index')); ?>" class="text-sm text-accent hover:underline font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        View All →
                    </a>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e(number_format($quickAnalytics['realtime']['active_users'] ?? 0)); ?> active users (30min)
                            </p>
                            <p class="text-gray-600 dark:!text-text-secondary text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e(number_format($quickAnalytics['today_unique'] ?? 0)); ?> unique visitors today
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e(number_format($quickAnalytics['today_views'] ?? 0)); ?> page views today
                            </p>
                            <p class="text-gray-600 dark:!text-text-secondary text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e(number_format($quickAnalytics['realtime']['page_views'] ?? 0)); ?> in last 30 minutes
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Activity
                </h2>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-accent rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e($thisWeekArticles); ?> articles added this week
                            </p>
                            <p class="text-gray-600 dark:!text-text-secondary text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e($thisMonthArticles); ?> this month
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e($thisMonthComments); ?> comments this month
                            </p>
                        </div>
                    </div>
                    <?php if($totalSubscriptions > 0): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e(number_format($totalSubscriptions)); ?> newsletter subscribers
                            </p>
                            <p class="text-gray-600 dark:!text-text-secondary text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e($newSubscriptionsThisMonth); ?> new this month
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($unreadMessages > 0): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e($unreadMessages); ?> unread messages
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if(($pendingAuthorRequests ?? 0) > 0): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e($pendingAuthorRequests); ?> pending author requests
                            </p>
                            <a href="<?php echo e(route('admin.authors.requests')); ?>" class="text-xs text-accent hover:underline" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Review now →
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

        <!-- Top Viewed Articles -->
    <?php if($topViewedArticles->count() > 0): ?>
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 sm:p-6 mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:!text-white mb-4 sm:mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Most Viewed Articles
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
            <?php $__currentLoopData = $topViewedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group cursor-pointer">
                    <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-gray-200 dark:!bg-gray-700 mb-2">
                        <?php if($article->featured_image): ?>
                            <?php
                                $imageUrl = str_starts_with($article->featured_image, 'http') 
                                    ? $article->featured_image 
                                    : asset('storage/' . $article->featured_image);
                            ?>
                            <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='https://via.placeholder.com/400x600?text=No+Image'">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                        <?php endif; ?>
                        <div class="absolute top-2 right-2 px-2 py-1 bg-black/70 text-white text-xs font-semibold rounded">
                            <?php echo e(number_format($article->views ?? 0)); ?> views
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:!text-white text-sm truncate group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <?php echo e($article->title); ?>

                    </h3>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Comments -->
    <?php if($recentComments->count() > 0): ?>
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:!text-white mb-4 sm:mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Recent Comments
        </h2>
        <div class="space-y-4">
            <?php $__currentLoopData = $recentComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <?php echo e($comment->user ? $comment->user->name : $comment->name); ?>

                            </p>
                            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                on <a href="<?php echo e(route('articles.show', $comment->article->slug)); ?>" class="text-accent hover:underline"><?php echo e($comment->article->title); ?></a>
                            </p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">
                            <?php echo e($comment->status); ?>

                        </span>
                    </div>
                    <p class="text-gray-700 dark:!text-text-primary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        <?php echo e(Str::limit($comment->content, 150)); ?>

                    </p>
                    <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        <?php echo e($comment->created_at->diffForHumans()); ?>

                    </p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\New folder (2)\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>