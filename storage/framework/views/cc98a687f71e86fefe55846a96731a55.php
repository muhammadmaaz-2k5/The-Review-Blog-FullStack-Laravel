<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative w-full h-[60vh] min-h-[500px] overflow-hidden">
    <div class="absolute inset-0 bg-gray-900">
        <!-- Dynamic Background Image -->
        <?php if($series->featured_image): ?>
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[20s] hover:scale-105" 
                 style="background-image: url('<?php echo e($series->featured_image_url); ?>');"></div>
        <?php else: ?>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-30"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#141414] via-[#000000] to-[#1a1a1a]"></div>
        <?php endif; ?>
        
        <!-- Cinematic Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D0D] via-[#0D0D0D]/80 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0D0D0D] via-[#0D0D0D]/60 to-transparent"></div>
        
        <!-- Accent Glow -->
        <div class="absolute top-0 right-0 w-2/3 h-full bg-gradient-to-l from-accent/20 to-transparent opacity-40 blur-3xl"></div>
    </div>
    
    <div class="relative z-10 h-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-16">
        <!-- Breadcrumbs -->
        <div class="mb-8 opacity-80 hover:opacity-100 transition-opacity">
            <?php if(isset($seo['breadcrumbs'])): ?>
                <?php echo $__env->make('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>

        <div class="flex flex-col md:flex-row items-end gap-8">
            <!-- Poster Art (Optional, if we want a poster style layout) -->
            <!-- For now, we keep it simple text-heavy hero like Netflix/Prime detail pages -->
            
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <span class="px-3 py-1 bg-accent text-white text-xs font-bold uppercase tracking-wider rounded shadow-lg shadow-accent/20">
                        Series
                    </span>
                    <?php if($seriesStats['avg_reading_time'] > 0): ?>
                        <span class="flex items-center gap-1 text-gray-300 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?php echo e($seriesStats['avg_reading_time']); ?> min avg
                        </span>
                    <?php endif; ?>
                    <span class="flex items-center gap-1 text-gray-300 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <?php echo e(number_format($seriesStats['total_views'])); ?> Views
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-none uppercase tracking-tighter" style="font-family: 'Poppins', sans-serif; text-shadow: 0 4px 30px rgba(0,0,0,0.5);">
                    <?php echo e($series->title); ?>

                </h1>
                
                <?php if($series->description): ?>
                    <p class="text-lg md:text-xl text-gray-300 max-w-3xl font-light leading-relaxed mb-8 line-clamp-3">
                        <?php echo e($series->description); ?>

                    </p>
                <?php endif; ?>
                
                <div class="flex items-center gap-4">
                    <?php if($series->articles->count() > 0): ?>
                        <a href="#episodes" class="px-8 py-3 bg-white text-black font-bold rounded-full hover:bg-gray-200 transition-all uppercase tracking-wide flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                            Start Reading
                        </a>
                    <?php endif; ?>
                    
                    <?php if($series->author): ?>
                        <div class="flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/10">
                            <span class="text-gray-400 text-xs uppercase tracking-wide">Created by</span>
                            <span class="text-white font-bold"><?php echo e($series->author->name); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Main Content (Episodes/Articles) -->
        <div class="lg:col-span-8" id="episodes">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white uppercase tracking-tight flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-accent rounded-sm"></span>
                    Episodes <span class="text-gray-500 text-lg font-normal">(<?php echo e($series->articles->count()); ?>)</span>
                </h2>
            </div>

            <?php if($series->articles->count() > 0): ?>
                <div class="space-y-6">
                    <?php $__currentLoopData = $series->articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="group flex flex-col md:flex-row gap-6 p-4 rounded-2xl hover:bg-gray-50 dark:!hover:bg-white/5 transition-all border border-transparent hover:border-gray-100 dark:!hover:border-white/5">
                            <!-- Episode Thumbnail -->
                            <div class="relative w-full md:w-64 aspect-video flex-shrink-0 rounded-xl overflow-hidden bg-gray-900 shadow-lg">
                                <?php if($article->featured_image): ?>
                                    <img src="<?php echo e($article->featured_image_url); ?>" 
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         alt="<?php echo e($article->title); ?>">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gray-800 text-gray-600">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                                
                                <!-- Play Button Overlay -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 bg-accent/90 rounded-full flex items-center justify-center shadow-xl backdrop-blur-sm transform scale-90 group-hover:scale-100 transition-transform">
                                        <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                                    </div>
                                </div>

                                <div class="absolute bottom-2 right-2 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded">
                                    Part <?php echo e($article->series_order ?? $loop->iteration); ?>

                                </div>
                            </div>

                            <!-- Episode Info -->
                            <div class="flex-1 py-2">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xl font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                                        <?php echo e($article->title); ?>

                                    </h3>
                                    <?php if($article->reading_time): ?>
                                        <span class="text-xs font-medium text-gray-400 shrink-0"><?php echo e($article->reading_time); ?>m</span>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="text-gray-500 dark:!text-gray-400 text-sm line-clamp-2 mb-4 leading-relaxed">
                                    <?php echo e($article->excerpt); ?>

                                </p>
                                
                                <div class="flex items-center gap-4 text-xs font-medium text-gray-400 uppercase tracking-wide">
                                    <?php if($article->published_at): ?>
                                        <span><?php echo e($article->published_at->format('M j, Y')); ?></span>
                                    <?php endif; ?>
                                    <?php if($article->category): ?>
                                        <span class="text-accent"><?php echo e($article->category->name); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20 bg-gray-50 dark:!bg-white/5 rounded-2xl border border-dashed border-gray-200 dark:!border-white/10">
                    <p class="text-gray-500 dark:!text-gray-400">No episodes available yet.</p>
                </div>
            <?php endif; ?>

            
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Stats Widget -->
            <div class="bg-gray-50 dark:!bg-[#1a1a1a] rounded-2xl p-6 border border-gray-100 dark:!border-white/5">
                <h3 class="text-sm font-bold text-gray-900 dark:!text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Series Stats
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:!bg-black/20 p-4 rounded-xl">
                        <div class="text-2xl font-black text-gray-900 dark:!text-white"><?php echo e(number_format($seriesStats['total_articles'])); ?></div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wide">Episodes</div>
                    </div>
                    <div class="bg-white dark:!bg-black/20 p-4 rounded-xl">
                        <div class="text-2xl font-black text-gray-900 dark:!text-white"><?php echo e(number_format($seriesStats['total_views'])); ?></div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wide">Total Views</div>
                    </div>
                </div>
            </div>

            <!-- Popular in Series -->
            <?php if($popularInSeries->count() > 0): ?>
                <div class="bg-gray-50 dark:!bg-[#1a1a1a] rounded-2xl p-6 border border-gray-100 dark:!border-white/5">
                    <h3 class="text-sm font-bold text-gray-900 dark:!text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Trending Episodes
                    </h3>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $popularInSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('articles.show', $popular->slug)); ?>" class="flex gap-4 group">
                                <div class="w-20 h-28 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200 relative">
                                    <?php if($popular->featured_image): ?>
                                        <img src="<?php echo e($popular->featured_image_url); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <?php endif; ?>
                                    <div class="absolute top-0 left-0 bg-accent text-white text-[9px] font-bold px-1.5 py-0.5 rounded-br">
                                        #<?php echo e($loop->iteration); ?>

                                    </div>
                                </div>
                                <div class="flex-1 py-1">
                                    <h4 class="text-sm font-bold text-gray-900 dark:!text-white group-hover:text-accent transition-colors line-clamp-2 mb-1">
                                        <?php echo e($popular->title); ?>

                                    </h4>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <span><?php echo e(number_format($popular->views)); ?> views</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Related Series -->
            <?php if($relatedSeries->count() > 0): ?>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:!text-white uppercase tracking-wider mb-6 flex items-center gap-2 px-2">
                        You May Also Like
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <?php $__currentLoopData = $relatedSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('series.show', $related->slug)); ?>" class="group relative aspect-[2/1] rounded-xl overflow-hidden shadow-lg">
                                <?php if($related->featured_image): ?>
                                    <?php
                                        $relImg = str_starts_with($related->featured_image, 'http') ? $related->featured_image : asset('storage/' . $related->featured_image);
                                    ?>
                                    <img src="<?php echo e($relImg); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-800"></div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-4">
                                    <h4 class="text-white font-bold leading-tight group-hover:text-accent transition-colors">
                                        <?php echo e($related->title); ?>

                                    </h4>
                                    <span class="text-xs text-gray-300"><?php echo e($related->articles_count ?? 0); ?> Episodes</span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Social Bar Ad -->
<?php $__env->startPush('scripts'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\New folder (2)\resources\views/series/show.blade.php ENDPATH**/ ?>