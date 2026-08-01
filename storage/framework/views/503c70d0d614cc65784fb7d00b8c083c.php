<?php $__env->startSection('title', 'Article Series - Nazaara Circle'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative w-full h-[50vh] min-h-[400px] overflow-hidden">
    <div class="absolute inset-0 bg-gray-900">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-30"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-[#141414] via-[#000000] to-[#1a1a1a] opacity-90"></div>
        <!-- Abstract Shapes/Glow -->
        <div class="absolute top-0 right-0 w-2/3 h-full bg-gradient-to-l from-accent/20 to-transparent opacity-60 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-purple-900/30 blur-3xl rounded-full"></div>
    </div>
    
    <div class="relative z-10 h-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
        <!-- Breadcrumbs -->
        <div class="mb-6">
            <?php if(isset($seo['breadcrumbs'])): ?>
                <?php echo $__env->make('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>

        <span class="text-accent font-bold tracking-[0.2em] uppercase text-sm mb-4 animate-fade-in-up">Curated Collections</span>
        <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-none uppercase tracking-tighter animate-fade-in-up delay-100" style="font-family: 'Poppins', sans-serif; text-shadow: 0 4px 30px rgba(0,0,0,0.5);">
            Article <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">Series</span>
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl font-light leading-relaxed animate-fade-in-up delay-200">
            Dive deep into our comprehensive multi-part stories. Binge-read your favorite topics from start to finish.
        </p>
    </div>
</div>

<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Featured Series Section -->
    <?php if($featuredSeries->count() > 0): ?>
    <div class="mb-20">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:!text-white uppercase tracking-tight flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                    <span class="w-2 h-8 bg-accent rounded-sm"></span>
                    Featured Collections
                </h2>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__currentLoopData = $featuredSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featured): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('series.show', $featured->slug)); ?>" class="group relative h-[400px] rounded-3xl overflow-hidden shadow-2xl transition-all duration-500 hover:-translate-y-2 hover:shadow-accent/30">
                    <!-- Background Image -->
                    <?php if($featured->featured_image): ?>
                        <img src="<?php echo e($featured->featured_image_url); ?>" 
                             alt="<?php echo e($featured->featured_image_alt ?: $featured->title); ?>" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <?php else: ?>
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900"></div>
                    <?php endif; ?>
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    
                    <!-- Content -->
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="transform transition-transform duration-500 group-hover:-translate-y-2">
                            <span class="inline-block px-3 py-1 bg-accent text-white text-[10px] font-bold uppercase tracking-wider rounded-md mb-3 shadow-lg">
                                Featured Series
                            </span>
                            <h3 class="text-3xl font-black text-white mb-3 leading-tight" style="font-family: 'Poppins', sans-serif;">
                                <?php echo e($featured->title); ?>

                            </h3>
                            <p class="text-gray-300 text-sm line-clamp-2 mb-4 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 h-0 group-hover:h-auto">
                                <?php echo e($featured->description); ?>

                            </p>
                            <div class="flex items-center gap-4 text-xs font-bold text-gray-400 uppercase tracking-wide">
                                <span class="flex items-center gap-1.5 text-white">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <?php echo e(number_format($featured->articles_count)); ?> Parts
                                </span>
                                <span>•</span>
                                <span class="group-hover:text-accent transition-colors">Start Reading →</span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search and Filter Section -->
    <div class="relative md:sticky md:top-20 z-30 bg-white/80 dark:!bg-[#141414]/90 backdrop-blur-xl border border-gray-200 dark:!border-white/10 rounded-2xl p-4 mb-12 shadow-xl">
        <form method="GET" action="<?php echo e(route('series.index')); ?>" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" 
                       name="search" 
                       value="<?php echo e($search); ?>" 
                       placeholder="Find a series..." 
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:!bg-white/5 border border-gray-200 dark:!border-white/10 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent dark:!text-white font-medium transition-all placeholder-gray-500"
                       style="font-family: 'Poppins', sans-serif;">
            </div>
            
            <!-- Sort By -->
            <div class="w-full md:w-48">
                <select name="sort" 
                        class="w-full px-4 py-3 bg-gray-50 dark:!bg-white/5 border border-gray-200 dark:!border-white/10 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent dark:!text-white font-medium cursor-pointer"
                        style="font-family: 'Poppins', sans-serif;"
                        onchange="this.form.submit()">
                    <option value="sort_order" <?php echo e($sort === 'sort_order' ? 'selected' : ''); ?>>Default Order</option>
                    <option value="title" <?php echo e($sort === 'title' ? 'selected' : ''); ?>>Title (A-Z)</option>
                    <option value="articles" <?php echo e($sort === 'articles' ? 'selected' : ''); ?>>Most Articles</option>
                    <option value="latest" <?php echo e($sort === 'latest' ? 'selected' : ''); ?>>Newest First</option>
                </select>
            </div>
            
            <!-- Order -->
            <div class="w-full md:w-40">
                <select name="order" 
                        class="w-full px-4 py-3 bg-gray-50 dark:!bg-white/5 border border-gray-200 dark:!border-white/10 rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent dark:!text-white font-medium cursor-pointer"
                        style="font-family: 'Poppins', sans-serif;"
                        onchange="this.form.submit()">
                    <option value="asc" <?php echo e($order === 'asc' ? 'selected' : ''); ?>>Ascending</option>
                    <option value="desc" <?php echo e($order === 'desc' ? 'selected' : ''); ?>>Descending</option>
                </select>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="px-8 py-3 bg-accent hover:bg-accent-dark text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-accent/50 uppercase tracking-wide">
                Search
            </button>
            
            <?php if($search): ?>
                <a href="<?php echo e(route('series.index')); ?>" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:!bg-white/10 dark:!hover:bg-white/20 text-gray-700 dark:!text-white font-bold rounded-xl transition-all flex items-center justify-center">
                    ✕
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Series Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php $__empty_1 = true; $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('series.show', $ser->slug)); ?>" class="group flex flex-col bg-white dark:!bg-[#1a1a1a] rounded-2xl overflow-hidden border border-gray-100 dark:!border-white/5 shadow-lg hover:shadow-2xl hover:shadow-accent/10 transition-all duration-300 transform hover:-translate-y-1 h-full">
                <!-- Series Image -->
                <div class="relative aspect-[3/4] overflow-hidden">
                    <?php if($ser->featured_image): ?>
                        <?php
                            $imageUrl = str_starts_with($ser->featured_image, 'http') 
                                ? $ser->featured_image 
                                : asset('storage/' . $ser->featured_image);
                        ?>
                        <img src="<?php echo e($imageUrl); ?>" 
                             alt="<?php echo e($ser->featured_image_alt ?: $ser->title); ?>" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             onerror="this.style.display='none'">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                    
                    <!-- Top Badge -->
                    <div class="absolute top-4 left-4">
                        <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded border border-white/10 uppercase tracking-wide">
                            Series
                        </span>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6 flex-1 flex flex-col relative bg-white dark:!bg-[#1a1a1a]">
                    <!-- Article Count Badge (Floating) -->
                    <div class="absolute -top-5 right-4 bg-accent text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                        <?php echo e(number_format($ser->articles_count ?? 0)); ?> Parts
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-3 leading-tight group-hover:text-accent transition-colors line-clamp-2" style="font-family: 'Poppins', sans-serif;">
                        <?php echo e($ser->title); ?>

                    </h3>
                    
                    <?php if($ser->description): ?>
                        <p class="text-sm text-gray-500 dark:!text-gray-400 line-clamp-3 mb-4 flex-1">
                            <?php echo e($ser->description); ?>

                        </p>
                    <?php endif; ?>
                    
                    <!-- Meta -->
                    <div class="pt-4 border-t border-gray-100 dark:!border-white/5 mt-auto">
                        <div class="flex items-center justify-between text-xs font-medium text-gray-400">
                            <?php if($ser->author): ?>
                                <span class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 dark:!bg-gray-700 overflow-hidden">
                                        <?php if($ser->author->avatar): ?>
                                            <img src="<?php echo e($ser->author->avatar); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-500"><?php echo e(substr($ser->author->name, 0, 1)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php echo e($ser->author->name); ?>

                                </span>
                            <?php endif; ?>
                            <span class="group-hover:translate-x-1 transition-transform text-accent">Explore →</span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-24">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:!bg-white/5 mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif;">No Series Found</h3>
                <p class="text-gray-500 dark:!text-gray-400 max-w-md mx-auto">
                    We couldn't find any series matching your search. Try adjusting your filters.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- CTA Section -->
    <div class="mt-24 relative rounded-3xl overflow-hidden">
    
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Cinema" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-gray-900/90 to-transparent"></div>
        </div>
        <div class="relative z-10 px-8 py-20 md:p-24 max-w-4xl">
        </div>
        <div class="relative z-10 px-8 py-20 md:p-24 max-w-4xl">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight" style="font-family: 'Poppins', sans-serif;">
                Binge-Worthy Content <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-500">Curated For You</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl">
                Can't decide what to read next? Our series are hand-picked collections designed to take you on a journey through the most captivating stories in entertainment.
            </p>
            <a href="<?php echo e(route('login')); ?>" class="px-10 py-4 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg hover:shadow-accent/50 text-lg uppercase tracking-wide">
                Start Your Journey
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/series/index.blade.php ENDPATH**/ ?>