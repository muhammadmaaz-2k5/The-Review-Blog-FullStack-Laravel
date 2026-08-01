<?php $__env->startSection('title', 'Admin - Article Management'); ?>

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
                Article Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage articles, blog posts, and content
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Dashboard
            </a>
            <a href="<?php echo e(route('admin.articles.create')); ?>" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Add New Article
            </a>
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

    <!-- Filters -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="<?php echo e(route('admin.articles.index')); ?>" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by title..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Status</option>
                    <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>>Published</option>
                    <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Draft</option>
                    <option value="scheduled" <?php echo e(request('status') === 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Category</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Filter
                </button>
                <a href="<?php echo e(route('admin.articles.index')); ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:!divide-gray-700">
                <thead class="bg-gray-50 dark:!bg-bg-card-hover">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Article</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:!bg-bg-card divide-y divide-gray-200 dark:!divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded overflow-hidden bg-gray-200 dark:!bg-gray-700 flex-shrink-0">
                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" target="_blank">
                                    <?php if($article->featured_image): ?>
                                        <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/100?text=No+Image'">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                    <?php endif; ?>
                                </a>
                            </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:!text-white flex items-center gap-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                            <?php echo e($article->title); ?>

                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                        <?php if($article->is_featured): ?>
                                            <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded dark:!bg-yellow-900/20 dark:!text-yellow-400">Featured</span>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($article->category): ?>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                        <?php echo e($article->category->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e($article->author ? $article->author->name : '—'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($article->status === 'published'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">Published</span>
                                <?php elseif($article->status === 'draft'): ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs dark:!bg-gray-800 dark:!text-gray-400">Draft</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400">Scheduled</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e(number_format($article->views)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e($article->created_at->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="postToFacebook('<?php echo e($article->id); ?>', this)" 
                                            class="text-blue-600 hover:text-blue-900 dark:!text-blue-400 dark:!hover:text-blue-300 <?php echo e(($article->status !== 'published' || !config('services.facebook.enabled', false)) ? 'opacity-50 cursor-not-allowed' : ''); ?>" 
                                            title="<?php echo e($article->status !== 'published' ? 'Only published articles can be posted' : (config('services.facebook.enabled', false) ? 'Post to Facebook' : 'Facebook integration not enabled')); ?>"
                                            id="fb-btn-<?php echo e($article->id); ?>"
                                            <?php echo e(($article->status !== 'published' || !config('services.facebook.enabled', false)) ? 'disabled' : ''); ?>>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </button>

                                    <a href="<?php echo e(route('articles.show', $article->slug)); ?>" target="_blank" class="text-blue-600 hover:text-blue-900 dark:!text-blue-400 dark:!hover:text-blue-300" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="<?php echo e(route('admin.articles.edit', $article)); ?>" class="text-accent hover:text-accent-light" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="<?php echo e(route('admin.articles.destroy', $article)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:!text-red-400 dark:!hover:text-red-300" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                No articles found. <a href="<?php echo e(route('admin.articles.create')); ?>" class="text-accent hover:underline">Create your first article</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($articles->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200 dark:!border-border-secondary">
            <?php echo e($articles->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function postToFacebook(articleId, button) {
    // Check if button is disabled
    if (button.disabled) {
        const title = button.getAttribute('title');
        showNotification(title || 'This action is not available for this article.', 'error');
        return;
    }
    
    // Disable button and show loading state
    button.disabled = true;
    const originalHTML = button.innerHTML;
    const originalClasses = button.className;
    button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Make AJAX request
    const url = `/admin/articles/${articleId}/post-to-facebook`;
    
    console.log('Posting to Facebook:', url, 'Article ID:', articleId);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    })
    .then(async response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, read as text to see what we got
            const text = await response.text();
            console.error('Non-JSON response:', text.substring(0, 200));
            throw new Error('Server returned HTML instead of JSON. Check console for details.');
        }
    })
    .then(data => {
        if (data.success) {
            // Show success state
            button.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
            button.classList.remove('text-blue-600', 'hover:text-blue-900', 'dark:!text-blue-400', 'dark:!hover:text-blue-300', 'opacity-50', 'cursor-not-allowed');
            button.classList.add('text-green-600', 'dark:!text-green-400');
            
            // Show success message
            showNotification(data.message || 'Article posted to Facebook successfully!', 'success');
            
            // Reset button after 3 seconds
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.className = originalClasses;
                button.disabled = false;
            }, 3000);
        } else {
            // Show error
            showNotification(data.message || 'Failed to post to Facebook.', 'error');
            button.innerHTML = originalHTML;
            button.className = originalClasses;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error posting to Facebook:', error);
        showNotification('An error occurred while posting to Facebook: ' + error.message, 'error');
        button.innerHTML = originalHTML;
        button.className = originalClasses;
        button.disabled = false;
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' 
            ? 'bg-green-100 border border-green-400 text-green-700 dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400' 
            : 'bg-red-100 border border-red-400 text-red-700 dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400'
    }`;
    notification.style.fontFamily = "'Poppins', sans-serif";
    notification.style.fontWeight = '600';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

function copyTokenUrl(url, button) {
    if (!url) {
        alert('Token URL not available for this article.');
        return;
    }
    
    // Use modern Clipboard API if available
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            showCopySuccess(button);
        }).catch(() => {
            // Fallback to execCommand
            copyToClipboardFallback(url, button);
        });
    } else {
        // Fallback for older browsers
        copyToClipboardFallback(url, button);
    }
}

function copyToClipboardFallback(url, button) {
    const textarea = document.createElement('textarea');
    textarea.value = url;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showCopySuccess(button);
    } catch (err) {
        alert('Failed to copy. Please copy manually: ' + url);
    }
    
    document.body.removeChild(textarea);
}

function showCopySuccess(button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>';
    button.classList.remove('text-purple-600', 'hover:text-purple-900', 'dark:!text-purple-400', 'dark:!hover:text-purple-300');
    button.classList.add('text-green-600', 'dark:!text-green-400');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('text-green-600', 'dark:!text-green-400');
        button.classList.add('text-purple-600', 'hover:text-purple-900', 'dark:!text-purple-400', 'dark:!hover:text-purple-300');
    }, 2000);
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\New folder (2)\resources\views/admin/articles/index.blade.php ENDPATH**/ ?>