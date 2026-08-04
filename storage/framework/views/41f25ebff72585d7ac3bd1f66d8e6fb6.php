

<?php $__env->startSection('content'); ?>

<?php
    // Get hero articles for preload links
    $mainHero = $articles->first(function($article) {
        return Str::contains(Str::lower($article->title), 'jim curtis');
    }) ?? $featuredArticles->first() ?? $articles->first();
    
    $subHeroes = $articles->filter(function($article) use ($mainHero) {
        return $article->id !== ($mainHero->id ?? 0);
    })->take(2);
?>

<?php if(isset($mainHero) && $mainHero->featured_image): ?>
<link rel="preload" as="image" href="<?php echo e($mainHero->featured_image_url); ?>" fetchpriority="high">
<?php endif; ?>
<?php $__currentLoopData = $subHeroes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($sub->featured_image): ?>
<link rel="preload" as="image" href="<?php echo e($sub->featured_image_url); ?>" fetchpriority="high">
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<style>
    /* Nazaara Circle Entertainment Styles */
    .homepage-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 15px;
    }

    /* Hero Grid Layout */
    .hero-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 40px;
        height: 500px;
    }

    .hero-main {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        background-color: #000;
        display: block;
        text-decoration: none;
    }

    .hero-sub {
        display: flex;
        flex-direction: column;
        gap: 20px;
        height: 100%;
    }

    .hero-sub-item {
        position: relative;
        flex: 1;
        border-radius: 12px;
        overflow: hidden;
        background-color: #111;
        display: block;
        text-decoration: none;
    }

    .hero-bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.8;
        transition: transform 0.5s ease;
    }

    .hero-main:hover .hero-bg,
    .hero-sub-item:hover .hero-bg {
        transform: scale(1.05);
    }

    .hero-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 30px;
        background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        color: #fff;
        pointer-events: none;
    }
    
    .hero-sub-item .hero-content {
        padding: 20px;
    }

    .hero-tag {
        background-color: #E50914;
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 8px;
    }

    .hero-title {
        font-weight: 800;
        line-height: 1.2;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 10px;
    }
    
    .hero-main .hero-title {
        font-size: 36px;
    }
    
    .hero-sub-item .hero-title {
        font-size: 18px;
    }

    .hero-title a {
        color: #fff;
        text-decoration: none;
        transition: color 0.3s;
        pointer-events: auto;
    }

    .hero-main:hover .hero-title,
    .hero-sub-item:hover .hero-title {
        color: #ffcccc;
    }

    .hero-meta {
        font-size: 13px;
        color: #ddd;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .hero-wrapper {
            grid-template-columns: 1fr;
            height: auto;
        }
        .hero-main {
            height: 400px;
        }
        .hero-sub {
            flex-direction: row;
            height: 200px;
        }
    }

    /* Section Titles */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 800;
        color: #333;
        text-transform: uppercase;
        margin: 0;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 0;
        width: 60px;
        height: 4px;
        background-color: #E50914;
    }

    /* Grid Layouts */
    .entertainment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .ent-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }

    .ent-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .ent-thumb {
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .ent-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .ent-card:hover .ent-thumb img,
    .ent-card:hover .ent-thumb .placeholder-bg {
        transform: scale(1.1);
    }

    .ent-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .ent-category {
        color: #E50914;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .ent-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.4;
        color: #333;
    }

    .ent-card:hover .ent-title {
        color: #E50914; /* Optional hover effect for title when card is hovered */
    }

    .ent-excerpt {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
        line-height: 1.6;
        flex: 1;
    }

    .ent-meta {
        font-size: 12px;
        color: #999;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Who We Are Section */
    .who-we-are {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: #fff;
        padding: 60px 40px;
        border-radius: 16px;
        margin-top: 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .who-we-are::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #E50914, #ff5f6d);
    }

    .who-title {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .who-text {
        font-size: 18px;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto 30px;
        color: #ccc;
    }

    .who-btn {
        display: inline-block;
        background-color: #E50914;
        color: #fff;
        padding: 12px 30px;
        font-weight: 700;
        border-radius: 30px;
        text-decoration: none;
        transition: background 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .who-btn:hover {
        background-color: #b20710;
    }

    /* Placeholder Styles */
    .placeholder-bg {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.1);
        font-weight: 800;
        font-size: 24px;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        top: 0;
        left: 0;
        transition: transform 0.5s ease;
    }
    
    .placeholder-bg::after {
        content: 'Nazaara';
    }

    .hero-main .placeholder-bg,
    .hero-sub-item .placeholder-bg {
        background: #111;
        opacity: 0.8;
    }
    
    .hero-main .placeholder-bg::after {
        font-size: 48px;
    }

    .ent-thumb .placeholder-bg {
        background: #f5f5f5;
        color: #e0e0e0;
    }
    
    .ent-thumb .placeholder-bg::after {
        content: 'NC';
        font-size: 32px;
    }

    /* Dark Mode Overrides */
    html.dark .section-title,
    body.dark-mode .section-title {
        color: #fff;
    }
    
    html.dark .ent-card,
    body.dark-mode .ent-card {
        background: #1F1F1F;
    }
    
    html.dark .ent-title,
    body.dark-mode .ent-title {
        color: #fff;
    }
    
    html.dark .ent-excerpt,
    body.dark-mode .ent-excerpt {
        color: #aaa;
    }

    @media (max-width: 768px) {
        .hero-main {
            height: 300px;
        }
        .hero-sub {
            flex-direction: column;
            height: auto;
        }
        .hero-sub-item {
            flex: none;
            width: 100%;
            height: 180px;
        }
        .hero-title {
            font-size: 24px;
        }
        .hero-main .hero-title {
            font-size: 24px;
        }
        .hero-content {
            padding: 20px;
        }
        .entertainment-grid {
            grid-template-columns: 1fr;
        }
        .who-title {
            font-size: 28px;
        }
        .who-text {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .hero-main {
            height: 260px;
        }
        .hero-sub-item {
            height: 160px;
        }
        .hero-main .hero-title {
            font-size: 20px;
        }
        .hero-sub-item .hero-title {
            font-size: 16px;
        }
        .hero-content {
            padding: 15px;
        }
        .homepage-container {
            padding: 15px 10px;
        }
    }
</style>

<div class="homepage-container">
    
    <!-- Hero Grid -->
    <?php
        // 1. Jim Curtis (Main Hero)
        $mainHero = $articles->first(function($article) {
            return Str::contains(Str::lower($article->title), 'jim curtis');
        }) ?? $featuredArticles->first() ?? $articles->first();

        // 2. Sub Heroes (Harry Styles, Patrick Dempsey, etc.)
        // Filter out main hero to avoid duplicates
        $subHeroes = $articles->filter(function($article) use ($mainHero) {
            return $article->id !== ($mainHero->id ?? 0);
        })->take(2);
    ?>

    <?php if($mainHero): ?>
    <div class="hero-wrapper">
        <!-- Main Hero (Left) -->
        <a href="<?php echo e(route('articles.show', $mainHero->slug)); ?>" class="hero-main">
            <?php if($mainHero->featured_image): ?>
                <img src="<?php echo e($mainHero->featured_image_url); ?>" alt="<?php echo e($mainHero->title); ?>" class="hero-bg" fetchpriority="high" loading="eager">
            <?php else: ?>
                <div class="hero-bg placeholder-bg"></div>
            <?php endif; ?>
            <div class="hero-content">
                <span class="hero-tag"><?php echo e($mainHero->category->name ?? 'Featured'); ?></span>
                <h1 class="hero-title">
                    <?php echo e($mainHero->title); ?>

                </h1>
                <div class="hero-meta">
                    By <?php echo e($mainHero->author->name ?? 'Nazaara Team'); ?> • <?php echo e($mainHero->published_at ? $mainHero->published_at->format('F d, Y') : 'Just Now'); ?>

                </div>
            </div>
        </a>

        <!-- Sub Heroes (Right) -->
        <div class="hero-sub">
            <?php $__currentLoopData = $subHeroes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('articles.show', $sub->slug)); ?>" class="hero-sub-item">
                <?php if($sub->featured_image): ?>
                    <img src="<?php echo e($sub->featured_image_url); ?>" alt="<?php echo e($sub->title); ?>" class="hero-bg" fetchpriority="high" loading="eager">
                <?php else: ?>
                    <div class="hero-bg placeholder-bg"></div>
                <?php endif; ?>
                <div class="hero-content">
                    <span class="hero-tag"><?php echo e($sub->category->name ?? 'Trending'); ?></span>
                    <h3 class="hero-title">
                        <?php echo e(Str::limit($sub->title, 50)); ?>

                    </h3>
                    <div class="hero-meta">
                        <?php echo e($sub->reading_time ?? 5); ?> min read
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- 728x90 Banner Ad -->
    
    <!-- Static Referral Banner -->
    
    <!-- Latest Reviews Grid -->
    <div class="section-header">
        <h2 class="section-title">Latest Reviews & Stories</h2>
    </div>

    <div class="entertainment-grid" id="home-articles-container">
        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
            <?php if(isset($mainHero) && $article->id === $mainHero->id): ?> <?php continue; ?> <?php endif; ?>
            <?php if($subHeroes->contains('id', $article->id)): ?> <?php continue; ?> <?php endif; ?>
            
            <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="ent-card">
                <div class="ent-thumb">
                    <?php if($article->featured_image): ?>
                        <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->title); ?>" loading="lazy">
                    <?php else: ?>
                        <div class="placeholder-bg"></div>
                    <?php endif; ?>
                </div>
                <div class="ent-content">
                    <div class="ent-category"><?php echo e($article->category->name ?? 'Entertainment'); ?></div>
                    <h3 class="ent-title">
                        <?php echo e(Str::limit($article->title, 60)); ?>

                    </h3>
                    <div class="ent-excerpt">
                        <?php echo e(Str::limit(strip_tags($article->excerpt ?? $article->content), 100)); ?>

                    </div>
                    <div class="ent-meta">
                        <span><?php echo e($article->published_at ? $article->published_at->format('M d') : 'Recent'); ?></span>
                        <span>•</span>
                        <span><?php echo e($article->reading_time ?? 5); ?> min read</span>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-12 text-gray-500">
                <p>No other articles available yet. Stay tuned!</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Auto Load More Spinner -->
    <div id="home-load-more-trigger" class="py-8 text-center" data-page="2" data-url="<?php echo e(route('articles.load-more')); ?>" data-has-more="<?php echo e($articles->hasMorePages() ? 'true' : 'false'); ?>">
        <div class="inline-block animate-spin w-8 h-8 border-4 border-accent border-t-transparent rounded-full hidden" id="home-load-more-spinner"></div>
    </div>

    <!-- Native Banner Ad -->
    


   
    <!-- 320x50 Banner Ad -->
    


     <!-- 160x300 Banner Ad -->
    






    <!-- Top 10 Articles Section -->
    <?php if(isset($top10Category) && $top10Category && isset($top10Articles) && $top10Articles->count() > 0): ?>
    <div class="homepage-container mt-16">
        <div class="section-header mb-8">
            <h2 class="section-title" style="color: <?php echo e($top10Category->color ?? '#E50914'); ?>;">
                <?php echo e($top10Category->name); ?>

            </h2>
            <a href="<?php echo e(route('categories.show', $top10Category->slug)); ?>" class="text-accent hover:text-accent-light font-bold text-sm uppercase tracking-widest flex items-center gap-2 transition-all">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <?php $__currentLoopData = $top10Articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('articles.show', $article->slug)); ?>" 
               class="group bg-white dark:!bg-bg-card rounded-xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 text-decoration-none relative">
                <!-- Ranking Badge -->
                <div class="absolute top-3 left-3 z-10 w-8 h-8 rounded-full flex items-center justify-center font-black text-sm
                             <?php if($index === 0): ?> bg-gradient-to-br from-yellow-400 to-yellow-600 text-white
                             <?php elseif($index === 1): ?> bg-gradient-to-br from-gray-300 to-gray-500 text-white
                             <?php elseif($index === 2): ?> bg-gradient-to-br from-amber-600 to-amber-800 text-white
                             <?php else: ?> bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 <?php endif; ?>">
                    <?php echo e($index + 1); ?>

                </div>

                <div class="aspect-[16/9] overflow-hidden">
                    <?php if($article->featured_image): ?>
                        <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->title); ?>" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-accent to-purple-600 flex items-center justify-center text-white text-2xl font-black">
                            <?php echo e(strtoupper(substr($article->title, 0, 2))); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-4">
                    <div class="text-xs font-bold text-accent mb-2 uppercase tracking-wider">
                        <?php echo e($article->category->name ?? 'Uncategorized'); ?>

                    </div>
                    
                    <h3 class="text-base font-bold text-gray-900 dark:!text-white mb-3 line-clamp-2 group-hover:text-accent transition-colors leading-tight">
                        <?php echo e(Str::limit($article->title, 50)); ?>

                    </h3>

                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="font-bold"><?php echo e(number_format($article->views)); ?></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?php echo e($article->reading_time ?? 5); ?> min</span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>



    <!-- Who We Are Section -->
    <div class="who-we-are">
        <h2 class="who-title">Who We Are</h2>
        <p class="who-text">
            Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
        </p>
        <a href="<?php echo e(route('login')); ?>" class="who-btn">Join the Circle</a>
    </div>



        <!-- Instagram Star Section -->
    <?php if(isset($instagramStarCategory) && $instagramStarCategory && $instagramStarArticles && $instagramStarArticles->count() > 0): ?>
    <div class="mt-16">
        <div class="section-header mb-8">
            <h2 class="section-title" style="color: <?php echo e($instagramStarCategory->color ?? '#E50914'); ?>;">
                <?php echo e($instagramStarCategory->name); ?>

            </h2>
            <a href="<?php echo e(route('categories.show', $instagramStarCategory->slug)); ?>" class="text-accent hover:text-accent-light font-bold text-sm uppercase tracking-widest flex items-center gap-2 transition-all">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $instagramStarArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="block relative aspect-[16/9] overflow-hidden">
                    <?php if($article->featured_image): ?>
                        <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white text-3xl font-black"><?php echo e(strtoupper(substr($article->title, 0, 2))); ?></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                    
                    <?php if($article->is_featured): ?>
                        <span class="absolute top-4 left-4 bg-accent text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-lg">Featured</span>
                    <?php endif; ?>
                </a>
                
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 line-clamp-2 group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>"><?php echo e($article->title); ?></a>
                    </h3>
                    <p class="text-sm text-gray-600 dark:!text-text-secondary mb-4 line-clamp-2" style="font-family: 'Poppins', sans-serif;"><?php echo e(Str::limit($article->excerpt ?: $article->description, 80)); ?></p>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <?php echo e(number_format($article->views)); ?> views
                        </div>
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="text-[10px] font-black text-accent uppercase tracking-widest flex items-center gap-1 group/link">
                            Read <span class="group-hover/link:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

   




    
    <?php if(request()->has('destination') && request('source') === 'nazaarabox'): ?>
        <div id="download-process-wrapper" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 backdrop-blur-md p-4 transition-all duration-500">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-md w-full p-8 text-center border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent to-purple-600"></div>
                
                <h2 class="text-3xl font-black mb-6 text-gray-900 dark:text-white uppercase tracking-tighter">
                    <span class="text-accent">Secure</span> Get Link
                </h2>
                
                <!-- Step 1: Initial Timer -->
                <div id="step-1" class="transition-opacity duration-300">
                    <p class="mb-6 text-gray-600 dark:text-gray-300 text-lg">Generating secure link...</p>
                    
                    <!-- Ad Unit inside Step 1 -->
                    
                    <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90 text-gray-200 dark:text-gray-700" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8"></circle>
                        </svg>
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90 text-accent" viewBox="0 0 100 100">
                            <circle id="progress-circle-1" cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="0" class="transition-all duration-1000 ease-linear"></circle>
                        </svg>
                        <span class="text-4xl font-bold text-gray-900 dark:text-white" id="timer-1">10</span>
                    </div>
                    <p class="text-sm text-gray-400">Please wait while we verify your request.</p>
                </div>

                <!-- Step 2: Scroll Instruction -->
                <div id="step-2" class="hidden transition-opacity duration-300">
                    <div class="animate-bounce mb-6 text-accent">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Scroll Down to Continue</h3>
                    <p class="text-gray-600 dark:text-gray-400">Please explore our homepage content and scroll to the bottom to unlock the final link.</p>
                </div>

                <!-- Step 3: Final Timer -->
                <div id="step-3" class="hidden transition-opacity duration-300">
                    <p class="mb-6 text-gray-600 dark:text-gray-300 text-lg">Finalizing link...</p>
                    <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90 text-gray-200 dark:text-gray-700" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8"></circle>
                        </svg>
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90 text-green-500" viewBox="0 0 100 100">
                            <circle id="progress-circle-2" cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="0" class="transition-all duration-1000 ease-linear"></circle>
                        </svg>
                        <span class="text-4xl font-bold text-gray-900 dark:text-white" id="timer-2">10</span>
                    </div>
                </div>

                <!-- Step 4: Destination Button -->
                <div id="step-4" class="hidden transition-opacity duration-300">
                    <div class="mb-8 text-green-500 bg-green-100 dark:bg-green-900/20 p-4 rounded-full w-20 h-20 mx-auto flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Your link is ready!</p>
                    <a href="<?php echo e(base64_decode(request('destination'))); ?>" target="_blank"
                       class="block w-full py-4 px-6 bg-accent hover:bg-accent-dark text-white font-bold text-lg rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-accent/20">
                        Proceed to Destination
                    </a>
                </div>
            </div>
        </div>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Clean URL parameters immediately
                if (window.history.replaceState) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('destination');
                    url.searchParams.delete('source');
                    window.history.replaceState({}, document.title, url.pathname);
                }

                const wrapper = document.getElementById('download-process-wrapper');
                const step1 = document.getElementById('step-1');
                const step2 = document.getElementById('step-2');
                const step3 = document.getElementById('step-3');
                const step4 = document.getElementById('step-4');
                
                // Lock scroll initially
                document.body.style.overflow = 'hidden';
                
                // Timer 1 Logic
                let timeLeft1 = 10;
                const timer1 = document.getElementById('timer-1');
                const circle1 = document.getElementById('progress-circle-1');
                const circumference = 283; // 2 * pi * 45
                
                const interval1 = setInterval(() => {
                    timeLeft1--;
                    timer1.textContent = timeLeft1;
                    const offset = circumference - (timeLeft1 / 10) * circumference;
                    circle1.style.strokeDashoffset = offset;
                    
                    if (timeLeft1 <= 0) {
                        clearInterval(interval1);
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        
                        // Minimize to bottom right
                        wrapper.classList.remove('fixed', 'inset-0', 'bg-black/95', 'backdrop-blur-md', 'z-50');
                        wrapper.classList.add('fixed', 'bottom-4', 'right-4', 'z-40', 'max-w-sm', 'bg-transparent');
                        
                        // Style inner card for minimized state
                        const card = wrapper.querySelector('div');
                        card.classList.remove('shadow-2xl', 'p-8');
                        card.classList.add('shadow-lg', 'p-4', 'border-accent', 'border-2');
                        
                        // Unlock scroll
                        document.body.style.overflow = '';
                    }
                }, 1000);

                // Scroll Detection
                let scrolled = false;
                window.addEventListener('scroll', () => {
                    if (timeLeft1 > 0) return;
                    if (scrolled) return;

                    // Check if scrolled near bottom (80% of page height)
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight * 0.8) {
                        scrolled = true;
                        
                        // Maximize overlay again
                        wrapper.classList.add('fixed', 'inset-0', 'bg-black/95', 'backdrop-blur-md', 'z-50');
                        wrapper.classList.remove('bottom-4', 'right-4', 'max-w-sm', 'bg-transparent', 'z-40');
                        
                        const card = wrapper.querySelector('div');
                        card.classList.add('shadow-2xl', 'p-8');
                        card.classList.remove('shadow-lg', 'p-4', 'border-accent', 'border-2');
                        
                        step2.classList.add('hidden');
                        step3.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        
                        // Timer 2 Logic
                        let timeLeft2 = 10;
                        const timer2 = document.getElementById('timer-2');
                        const circle2 = document.getElementById('progress-circle-2');
                        
                        const interval2 = setInterval(() => {
                            timeLeft2--;
                            timer2.textContent = timeLeft2;
                            const offset = circumference - (timeLeft2 / 10) * circumference;
                            circle2.style.strokeDashoffset = offset;
                            
                            if (timeLeft2 <= 0) {
                                clearInterval(interval2);
                                step3.classList.add('hidden');
                                step4.classList.remove('hidden');
                            }
                        }, 1000);
                    }
                });
            });
        </script>
    <?php endif; ?>

                 <!-- Hot Celebrities Category Section (Moved to End) -->
    <?php if(isset($hotCelebritiesCategory) && $hotCelebritiesCategory && $hotCelebritiesArticles && $hotCelebritiesArticles->count() > 0): ?>
    <div class="mt-16">
        <div class="section-header mb-8">
            <h2 class="section-title" style="color: <?php echo e($hotCelebritiesCategory->color ?? '#E50914'); ?>;">
                <?php echo e($hotCelebritiesCategory->name); ?>

            </h2>
            <a href="<?php echo e(route('categories.show', $hotCelebritiesCategory->slug)); ?>" class="text-accent hover:text-accent-light font-bold text-sm uppercase tracking-widest flex items-center gap-2 transition-all">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $hotCelebritiesArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="block relative aspect-[16/9] overflow-hidden">
                    <?php if($article->featured_image): ?>
                        <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-pink-600 to-red-600 flex items-center justify-center text-white text-3xl font-black"><?php echo e(strtoupper(substr($article->title, 0, 2))); ?></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                    
                    <?php if($article->is_featured): ?>
                        <span class="absolute top-4 left-4 bg-accent text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-lg">Featured</span>
                    <?php endif; ?>
                </a>
                
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 line-clamp-2 group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>"><?php echo e($article->title); ?></a>
                    </h3>
                    <p class="text-sm text-gray-600 dark:!text-text-secondary mb-4 line-clamp-2" style="font-family: 'Poppins', sans-serif;"><?php echo e(Str::limit($article->excerpt ?: $article->description, 80)); ?></p>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <?php echo e(number_format($article->views)); ?> views
                        </div>
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="text-[10px] font-black text-accent uppercase tracking-widest flex items-center gap-1 group/link">
                            Read <span class="group-hover/link:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('head'); ?>
<!-- Popunder Ad -->
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Social Bar Ad -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.getElementById('home-load-more-trigger');
        const container = document.getElementById('home-articles-container');
        const spinner = document.getElementById('home-load-more-spinner');
        
        if (!trigger || !container || trigger.dataset.hasMore !== 'true') return;
        
        let isLoading = false;
        
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !isLoading && trigger.dataset.hasMore === 'true') {
                loadMoreArticles();
            }
        }, { rootMargin: '200px' });
        
        observer.observe(trigger);
        
        function loadMoreArticles() {
            isLoading = true;
            spinner.classList.remove('hidden');
            
            const page = parseInt(trigger.dataset.page);
            const url = trigger.dataset.url + '?page=' + page;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.html;
                        
                        while (tempDiv.firstChild) {
                            container.appendChild(tempDiv.firstChild);
                        }
                        
                        if (data.hasMore) {
                            trigger.dataset.page = page + 1;
                        } else {
                            trigger.dataset.hasMore = 'false';
                            trigger.remove();
                        }
                    } else {
                        trigger.dataset.hasMore = 'false';
                    }
                })
                .catch(error => {
                    console.error('Error loading more articles:', error);
                })
                .finally(() => {
                    isLoading = false;
                    spinner.classList.add('hidden');
                });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>