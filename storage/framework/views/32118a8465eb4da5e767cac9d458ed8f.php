<article class="group relative bg-white overflow-hidden cursor-pointer dark:!bg-bg-card transition-all duration-300 rounded-lg border border-gray-200 dark:!border-border-secondary h-full flex flex-col">
    <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="block h-full flex flex-col">
        <!-- Featured Image -->
        <div class="relative w-full aspect-video bg-gray-200 dark:bg-gray-800 overflow-hidden">
            <?php if($article->featured_image): ?>
                <img src="<?php echo e($article->featured_image_url); ?>" 
                     alt="<?php echo e($article->featured_image_alt ?: $article->title); ?>" 
                     title="<?php echo e($article->featured_image_title ?: $article->title); ?>"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                     style="display: block;"
                     loading="lazy"
                     decoding="async"
                     fetchpriority="low"
                     onerror="this.onerror=null;this.src='<?php echo e(asset('article_image_notfound.png')); ?>'">
            <?php else: ?>
                <img src="<?php echo e(asset('article_image_notfound.png')); ?>" 
                     alt="<?php echo e($article->title); ?>" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            <?php endif; ?>
            
            <!-- Category Badge -->
            
            
            <!-- Featured Badge -->
            <?php if($article->is_featured): ?>
            <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 sm:px-3 py-1 rounded-full text-xs font-semibold z-30" style="font-family: 'Poppins', sans-serif; font-weight: 600; backdrop-filter: blur(4px); background-color: rgba(234, 179, 8, 0.9);">
                ⭐ Featured
            </div>
            <?php endif; ?>
            
            <!-- Meta Information Overlay -->
            <div class="absolute bottom-0 left-0 right-0 z-20">
                <div class="w-full p-3 sm:p-4">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-white" style="font-family: 'Poppins', sans-serif; font-weight: 500; text-shadow: 0 2px 4px rgba(0,0,0,0.7);">
                        <?php if($article->published_at): ?>
                            <span><?php echo e($article->published_at->format('M d, Y')); ?></span>
                        <?php endif; ?>
                        <?php if($article->reading_time): ?>
                            <span>•</span>
                            <span><?php echo e($article->reading_time); ?> min read</span>
                        <?php endif; ?>
                        <span>•</span>
                        <span>👁 <?php echo e(number_format($article->views)); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Article Info -->
        <div class="p-4 sm:p-5 flex-1 flex flex-col">
            <!-- Title -->
            <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 dark:!text-white mb-2 sm:mb-3 line-clamp-2 group-hover:text-accent transition-colors duration-300" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                <?php echo e($article->title); ?>

            </h3>
            
            <!-- Excerpt/Description -->
            <?php if($article->excerpt): ?>
                <p class="text-sm sm:text-base text-gray-600 dark:!text-text-secondary line-clamp-2 mb-3 flex-grow" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    <?php echo e($article->excerpt); ?>

                </p>
            <?php endif; ?>
            
            <!-- Tags -->
            <?php if($article->tags->count() > 0): ?>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $article->tags->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs dark:!bg-bg-card-hover dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            <?php echo e($tag->name); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($article->tags->count() > 3): ?>
                        <span class="px-2 py-1 text-gray-500 text-xs dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            +<?php echo e($article->tags->count() - 3); ?> more
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </a>
</article>

<?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/articles/_card.blade.php ENDPATH**/ ?>