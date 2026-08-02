<?php if(isset($relatedArticle)): ?>
<div class="my-8 relative overflow-hidden rounded-xl border border-gray-200 dark:!border-border-secondary bg-gray-50 dark:!bg-bg-card-hover group shadow-sm transition-all hover:shadow-md">
    <a href="<?php echo e(route('articles.show', $relatedArticle->slug)); ?>" class="flex flex-col sm:flex-row items-stretch text-decoration-none !no-underline">
        <!-- Image Side -->
        <div class="w-full sm:w-1/3 min-h-[120px] relative overflow-hidden flex-shrink-0">
            <?php if($relatedArticle->featured_image): ?>
                <img src="<?php echo e($relatedArticle->featured_image_url); ?>" alt="<?php echo e($relatedArticle->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 !m-0">
            <?php else: ?>
                <img src="<?php echo e(asset('article_image_notfound.png')); ?>" alt="<?php echo e($relatedArticle->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 !m-0">
            <?php endif; ?>
            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
        </div>
        
        <!-- Content Side -->
        <div class="p-5 sm:p-6 flex flex-col justify-center w-full">
            <span class="text-xs font-bold uppercase tracking-wider text-accent mb-2 block">
                Also Read
            </span>
            <h4 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 leading-tight group-hover:text-accent transition-colors !mt-0 !mb-2" style="font-family: 'Poppins', sans-serif;">
                <?php echo e($relatedArticle->title); ?>

            </h4>
            <div class="flex items-center gap-3 text-xs text-gray-500 dark:!text-gray-400 font-semibold uppercase tracking-wide">
                <span><?php echo e($relatedArticle->created_at->format('M d, Y')); ?></span>
                <?php if($relatedArticle->reading_time): ?>
                    <span>&bull;</span>
                    <span><?php echo e($relatedArticle->reading_time); ?> Min Read</span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</div>
<?php endif; ?>
<?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/articles/partials/inline-related.blade.php ENDPATH**/ ?>