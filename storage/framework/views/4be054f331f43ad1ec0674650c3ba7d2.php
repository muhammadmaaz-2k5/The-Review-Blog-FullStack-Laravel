

<?php
    $isHotCelebrity = $article->category && $article->category->slug === 'hot-celebrity';
?>

<?php $__env->startPush('head'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Table of Contents Extraction -->
    <?php
        $headings = [];
        // Decode HTML entities (important if content comes from an editor like TinyMCE)
        $decodedContent = html_entity_decode($article->rendered_content, ENT_QUOTES, 'UTF-8');

        // Match headers h2 to h6
        if (preg_match_all('/<h([2-6])[^>]*>(.*?)<\/h\1>/i', $decodedContent, $matches)) {
            foreach ($matches[2] as $index => $match) {
                $headings[] = [
                    'text' => strip_tags($match),
                    'level' => intval($matches[1][$index]) // h2, h3, etc.
                ];
            }
        }
    ?>

    <!-- Reading Progress Bar -->
    <div id="readingProgress"
        class="fixed top-0 left-0 w-0 h-1 z-[10000] bg-gradient-to-r from-accent to-purple-600 transition-all duration-150 ease-out shadow-[0_0_10px_rgba(59,130,246,0.5)]">
    </div>

    <?php if(request()->has('destination')): ?>
        <?php
            \Illuminate\Support\Facades\Log::info('Download Overlay Debug:', [
                'destination' => request('destination'),
                'source' => request('source'),
                'full_url' => request()->fullUrl(),
                'ip' => request()->ip()
            ]);
        ?>
    <?php endif; ?>

    <?php if(request()->has('destination') && request('source') !== 'nazaarabox'): ?>
        <div class="fixed top-0 left-0 w-full bg-red-600 text-white p-4 z-[10000] text-center font-bold">
            DEBUG: Download overlay blocked. Invalid source parameter.<br>
            Expected: source=nazaarabox<br>
            Got: source=<?php echo e(request('source') ?? 'null'); ?>

        </div>
        <script>console.warn('Download overlay blocked: Invalid source parameter. Expected: nazaarabox, Got: ' + new URLSearchParams(window.location.search).get('source'));</script>
    <?php endif; ?>

    <?php if(request()->has('destination') && request('source') === 'nazaarabox'): ?>
        <div class="fixed inset-0 z-[9999] bg-white dark:bg-gray-900 flex flex-col items-center justify-center transition-opacity duration-500"
            id="redirect-overlay">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-accent mb-4"></div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Verifying secure connection...</p>
            <p class="text-sm text-gray-500 mt-2">Please wait while we redirect you to the download center.</p>
        </div>

        <script>
            window.addEventListener('load', function () {
                // Wait a moment to ensure everything is settled
                setTimeout(function () {
                    const currentUrl = new URL(window.location.href);
                    const destination = currentUrl.searchParams.get('destination');
                    const source = currentUrl.searchParams.get('source');
                    const cache = currentUrl.searchParams.get('cache');

                    // Construct home URL with params
                    const homeUrl = new URL("<?php echo e(route('home')); ?>");
                    homeUrl.searchParams.set('destination', destination);
                    homeUrl.searchParams.set('source', source);
                    if (cache) homeUrl.searchParams.set('cache', cache);

                    window.location.href = homeUrl.toString();
                }, 1500); // 1.5s delay for better UX
            });
        </script>
    <?php endif; ?>


    
    <div data-article-data='{"id":<?php echo e($article->id); ?>,"title":"<?php echo e(addslashes($article->title)); ?>","slug":"<?php echo e($article->slug); ?>","image":"<?php echo e($article->featured_image); ?>","url":"<?php echo e(route('articles.show', $article->slug)); ?>"}'
        class="hidden"></div>

    <article data-viewable-type="<?php echo e(addslashes(get_class($article))); ?>" data-viewable-id="<?php echo e($article->id); ?>"
        class="min-h-screen bg-white dark:!bg-bg-primary pb-16">
        <!-- HERO SECTION -->
        <div class="relative w-full h-[70vh] min-h-[600px] mb-16 group overflow-hidden">
            <div class="absolute inset-0 w-full h-full">
                <?php if($article->featured_image): ?>
                    <img src="<?php echo e($article->featured_image_url); ?>" alt="<?php echo e($article->featured_image_alt ?: $article->title); ?>"
                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0D0D0D] via-[#0D0D0D]/80 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-[#0D0D0D]/60 via-transparent to-transparent"></div>
                <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-gray-900 to-gray-800"></div>
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20">
                    </div>
                <?php endif; ?>
            </div>

            <div
                class="absolute bottom-0 left-0 right-0 p-6 md:p-12 lg:p-20 max-w-[1440px] mx-auto z-10 flex flex-col justify-end h-full pb-16">
                <!-- Breadcrumbs / Category -->
                <div class="flex items-center gap-3 mb-6 text-xs font-bold tracking-widest uppercase text-gray-300">
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-accent transition-colors">Home</a>
                    <span class="text-accent">/</span>
                    <?php if($article->category): ?>
                        <a href="<?php echo e(route('categories.show', $article->category->slug)); ?>"
                            class="hover:text-accent transition-colors">
                            <?php echo e($article->category->name); ?>

                        </a>
                    <?php endif; ?>
                    <span class="text-accent">/</span>
                    <span class="text-white border-b-2 border-accent pb-0.5">Article</span>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-8 leading-[1.1] max-w-5xl uppercase tracking-tighter drop-shadow-lg"
                    style="font-family: 'Poppins', sans-serif;">
                    <?php echo e($article->title); ?>

                </h1>

                <!-- Author & Meta -->
                <div class="flex flex-wrap items-center gap-6 md:gap-8 text-white">
                    <?php if($article->author): ?>
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="relative">
                                <?php if($article->author->avatar): ?>
                                    <img src="<?php echo e($article->author->avatar); ?>"
                                        class="w-14 h-14 rounded-full border-2 border-accent object-cover group-hover:scale-110 transition-transform">
                                <?php else: ?>
                                    <div
                                        class="w-14 h-14 rounded-full bg-accent flex items-center justify-center text-white font-bold border-2 border-white/20 text-xl group-hover:scale-110 transition-transform">
                                        <?php echo e(substr($article->author->name, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="absolute -bottom-1 -right-1 bg-white text-accent rounded-full p-0.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-0.5">Written By</span>
                                <span
                                    class="font-bold text-lg group-hover:text-accent transition-colors"><?php echo e($article->author->name); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="hidden md:block w-px h-10 bg-white/20"></div>

                    <div class="flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-0.5">Published</span>
                            <span
                                class="font-bold text-lg"><?php echo e($article->published_at?->format('M d, Y') ?? 'Draft'); ?></span>
                        </div>

                        <div class="w-px h-10 bg-white/20"></div>

                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-0.5">Read Time</span>
                            <span class="font-bold text-lg"><?php echo e($article->reading_time); ?> Min</span>
                        </div>

                        <div class="w-px h-10 bg-white/20"></div>

                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-0.5">Views</span>
                            <span class="font-bold text-lg"><?php echo e(number_format($article->views)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        
        <!-- MAIN CONTENT LAYOUT -->
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 relative">

                <!-- Left Sidebar (Sticky Share) -->
                <div class="hidden xl:block w-16 flex-shrink-0">
                    <div class="sticky top-32 flex flex-col gap-6">
                        <!-- Like Button (Mini) -->
                        <button id="likeButtonSidebar"
                            class="w-12 h-12 rounded-full bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary flex items-center justify-center text-gray-500 hover:text-red-500 hover:border-red-500 transition-all shadow-sm group"
                            onclick="document.getElementById('likeButton').click()">
                            <svg class="w-6 h-6 group-hover:fill-current transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>

                        <!-- Share Icons -->
                        <div class="w-12 h-[1px] bg-gray-200 dark:!bg-border-secondary mx-auto"></div>

                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(route('articles.show', $article->slug))); ?>"
                            target="_blank"
                            class="w-12 h-12 rounded-full bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary flex items-center justify-center text-gray-500 hover:text-[#1877F2] hover:border-[#1877F2] transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>

                        <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(route('articles.show', $article->slug))); ?>"
                            target="_blank"
                            class="w-12 h-12 rounded-full bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary flex items-center justify-center text-gray-500 hover:text-[#1DA1F2] hover:border-[#1DA1F2] transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Content Column -->
                <div class="flex-1 min-w-0">

                    <!-- Article Content -->
                    <div class="prose prose-lg dark:prose-invert max-w-none mb-8 article-content prose-headings:text-gray-900 prose-p:text-gray-700 prose-strong:text-gray-900 prose-a:text-accent prose-code:text-gray-900 prose-li:text-gray-700 prose-blockquote:text-gray-700 dark:prose-headings:!text-white dark:prose-p:!text-text-primary dark:prose-strong:!text-white dark:prose-a:!text-accent dark:prose-code:!text-white dark:prose-li:!text-text-primary dark:prose-blockquote:!text-text-primary 
                            [&>p:first-of-type]:first-letter:text-5xl [&>p:first-of-type]:first-letter:font-bold [&>p:first-of-type]:first-letter:mr-3 [&>p:first-of-type]:first-letter:float-left [&>p:first-of-type]:first-letter:text-accent [&>p:first-of-type]:first-letter:leading-[0.8]
                            [&>blockquote]:border-l-4 [&>blockquote]:border-accent [&>blockquote]:bg-gray-50 [&>blockquote]:dark:bg-bg-card [&>blockquote]:py-2 [&>blockquote]:px-4 [&>blockquote]:italic [&>blockquote]:rounded-r-lg"
                        style="font-family: 'Poppins', sans-serif;">

                        <?php if($article->short_video_id): ?>
                            <div class="flex justify-center my-8">
                                <div
                                    class="relative w-full max-w-[315px] aspect-[9/16] bg-black rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10">
                                    <iframe width="315" height="560"
                                        src="https://www.youtube.com/embed/<?php echo e($article->short_video_id); ?>"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen class="absolute inset-0 w-full h-full">
                                    </iframe>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div
                            class="article-content space-y-6 [&_iframe]:w-full [&_iframe]:max-w-4xl [&_iframe]:aspect-video [&_iframe]:mx-auto [&_iframe]:rounded-xl [&_iframe]:shadow-lg [&_iframe]:my-6">
                            <?php echo $article->rendered_content; ?>

                        </div>
                    </div>

                    <!-- Article Tags -->
                    <?php if($article->tags->count() > 0): ?>
                        <div class="flex flex-wrap gap-2 mb-8">
                            <?php $__currentLoopData = $article->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('tags.show', $tag->slug)); ?>"
                                    class="px-3 py-1 bg-gray-100 dark:!bg-bg-card-hover text-gray-600 dark:!text-text-secondary rounded-full text-sm font-medium hover:bg-accent hover:text-white transition-colors"
                                    style="font-family: 'Poppins', sans-serif;">
                                    #<?php echo e($tag->name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Author Bio -->
                    <?php if($article->author): ?>
                        <div
                            class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-6 bg-gray-50 dark:!bg-bg-card rounded-xl border border-gray-100 dark:!border-border-secondary mb-8 text-center sm:text-left">
                            <div class="flex-shrink-0">
                                <a href="<?php echo e(route('profile.show', $article->author->username ?? $article->author->id)); ?>">
                                    <?php if($article->author->avatar): ?>
                                        <img src="<?php echo e($article->author->avatar); ?>" alt="<?php echo e($article->author->name); ?>"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-white dark:!border-bg-card shadow-sm hover:scale-105 transition-transform">
                                    <?php else: ?>
                                        <div
                                            class="w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white font-bold text-2xl border-2 border-white dark:!border-bg-card shadow-sm hover:scale-105 transition-transform">
                                            <?php echo e(substr($article->author->name, 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 dark:!text-white text-lg mb-1"
                                    style="font-family: 'Poppins', sans-serif;">
                                    <a href="<?php echo e(route('profile.show', $article->author->username ?? $article->author->id)); ?>"
                                        class="hover:text-accent transition-colors">
                                        <?php echo e($article->author->name); ?>

                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary line-clamp-2 mb-3">
                                    <?php echo e($article->author->bio ?? 'Content Creator at Nazaara Circle. Passionate about entertainment, movies, and pop culture.'); ?>

                                </p>
                                <a href="<?php echo e(route('profile.show', $article->author->username ?? $article->author->id)); ?>"
                                    class="text-xs font-bold text-accent hover:text-accent-light uppercase tracking-wide inline-flex items-center gap-1">
                                    View Profile
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Navigation (Previous/Next) -->
                    <?php if($previousArticle || $nextArticle): ?>
                        <div class="mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200 dark:!border-border-secondary">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                <!-- Previous Article -->
                                <?php if($previousArticle): ?>
                                    <a href="<?php echo e(route('articles.show', $previousArticle->slug)); ?>"
                                        class="group flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 dark:!bg-bg-card-hover dark:!hover:bg-bg-card rounded-lg transition-colors border border-gray-200 dark:!border-border-secondary">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-400 group-hover:text-accent transition-colors" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-500 dark:!text-text-secondary mb-1"
                                                style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                <?php echo e($article->series ? 'Previous in Series' : 'Previous Article'); ?>

                                            </p>
                                            <p class="text-sm font-semibold text-gray-900 dark:!text-white truncate group-hover:text-accent transition-colors"
                                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                <?php echo e($previousArticle->title); ?>

                                            </p>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>

                                <!-- Next Article -->
                                <?php if($nextArticle): ?>
                                    <a href="<?php echo e(route('articles.show', $nextArticle->slug)); ?>"
                                        class="group flex items-center gap-2 sm:gap-4 p-3 sm:p-4 bg-gray-50 hover:bg-gray-100 dark:!bg-bg-card-hover dark:!hover:bg-bg-card rounded-lg transition-colors border border-gray-200 dark:!border-border-secondary text-right">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-500 dark:!text-text-secondary mb-1"
                                                style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                <?php echo e($article->series ? 'Next in Series' : 'Next Article'); ?>

                                            </p>
                                            <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:!text-white truncate group-hover:text-accent transition-colors"
                                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                <?php echo e($nextArticle->title); ?>

                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-accent transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Article Actions (Like & Bookmark Buttons) -->
                    <div
                        class="flex flex-wrap items-center gap-2 sm:gap-4 mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200 dark:!border-border-secondary">
                        <button id="likeButton"
                            class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg transition-all text-sm sm:text-base <?php echo e($isLiked ?? false ? 'bg-red-100 text-red-600 dark:!bg-red-900/20 dark:!text-red-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'); ?>"
                            data-article-slug="<?php echo e($article->slug); ?>"
                            data-liked="<?php echo e($isLiked ?? false ? 'true' : 'false'); ?>">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="<?php echo e($isLiked ?? false ? 'currentColor' : 'none'); ?>"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <span class="font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <span id="likesCount"><?php echo e($article->likes()->count()); ?></span> <span
                                    class="hidden sm:inline">Like<?php echo e($article->likes()->count() !== 1 ? 's' : ''); ?></span>
                            </span>
                        </button>

                        <?php if(auth()->guard()->check()): ?>
                            <button id="bookmarkButton"
                                class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg transition-all text-sm sm:text-base <?php echo e($isBookmarked ?? false ? 'bg-yellow-100 text-yellow-600 dark:!bg-yellow-900/20 dark:!text-yellow-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card'); ?>"
                                data-article-slug="<?php echo e($article->slug); ?>"
                                data-bookmarked="<?php echo e($isBookmarked ?? false ? 'true' : 'false'); ?>">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="<?php echo e($isBookmarked ?? false ? 'currentColor' : 'none'); ?>"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <span class="font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    <span
                                        class="hidden sm:inline"><?php echo e($isBookmarked ?? false ? 'Bookmarked' : 'Bookmark'); ?></span>
                                    <span class="sm:hidden"><?php echo e($isBookmarked ?? false ? 'Saved' : 'Save'); ?></span>
                                </span>
                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>"
                                class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg transition-all bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <span class="font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    <span class="hidden sm:inline">Bookmark</span>
                                    <span class="sm:hidden">Save</span>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Social Sharing Section -->
                    <div class="mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200 dark:!border-border-secondary">
                        <h3 class="text-sm font-semibold text-gray-700 dark:!text-text-secondary mb-3"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Share:
                        </h3>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <?php
                                $articleUrl = route('articles.show', $article->slug);
                                $articleTitle = urlencode($article->title);
                                $articleDescription = urlencode($article->excerpt ?? $article->title);
                                $articleImage = $article->featured_image_url ?? '';
                            ?>

                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode($articleUrl)); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#1877F2] hover:bg-[#1565C0] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                <span class="hidden sm:inline">Facebook</span>
                            </a>

                            <!-- Twitter -->
                            <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode($articleUrl)); ?>&text=<?php echo e($articleTitle); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#1DA1F2] hover:bg-[#0d8bd9] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                                <span class="hidden sm:inline">Twitter</span>
                            </a>

                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text=<?php echo e(urlencode($article->title . ' ' . $articleUrl)); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#25D366] hover:bg-[#20ba5a] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                <span class="hidden sm:inline">WhatsApp</span>
                            </a>

                            <!-- Instagram -->
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-[#833AB4] via-[#FD1D1D] to-[#FCB045] hover:opacity-90 text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                                <span class="hidden sm:inline">Instagram</span>
                            </a>

                            <!-- Threads -->
                            <a href="https://www.threads.net/intent/post?text=<?php echo e(urlencode($article->title . ' ' . $articleUrl)); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm4.5 8.25c0 .414-.336.75-.75.75h-7.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h7.5c.414 0 .75.336.75.75zm0 3c0 .414-.336.75-.75.75h-7.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h7.5c.414 0 .75.336.75.75zm0 3c0 .414-.336.75-.75.75h-7.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h7.5c.414 0 .75.336.75.75z" />
                                </svg>
                                <span class="hidden sm:inline">Threads</span>
                            </a>

                            <!-- TikTok -->
                            <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z" />
                                </svg>
                                <span class="hidden sm:inline">TikTok</span>
                            </a>

                            <!-- Telegram -->
                            <a href="https://t.me/share/url?url=<?php echo e(urlencode($articleUrl)); ?>&text=<?php echo e($articleTitle); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#0088cc] hover:bg-[#0077b5] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                </svg>
                                <span class="hidden sm:inline">Telegram</span>
                            </a>

                            <!-- Reddit -->
                            <a href="https://reddit.com/submit?url=<?php echo e(urlencode($articleUrl)); ?>&title=<?php echo e($articleTitle); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#FF4500] hover:bg-[#e63900] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-5.247a1.25 1.25 0 0 1 2.635.2l.464.938a1.216 1.216 0 0 1 .196-.016zm-9.999 0c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056L3.752 1.053a1.25 1.25 0 0 1 2.635.2l.464.938a1.216 1.216 0 0 1 .196-.016zm-4.25 3.5c-.414 0-.75.336-.75.75v8.5c0 .414.336.75.75.75h8.5c.414 0 .75-.336.75-.75v-8.5c0-.414-.336-.75-.75-.75h-8.5zm10.5 0c-.414 0-.75.336-.75.75v8.5c0 .414.336.75.75.75h8.5c.414 0 .75-.336.75-.75v-8.5c0-.414-.336-.75-.75-.75h-8.5z" />
                                </svg>
                                <span class="hidden sm:inline">Reddit</span>
                            </a>

                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo e(urlencode($articleUrl)); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#0077B5] hover:bg-[#006399] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                                <span class="hidden sm:inline">LinkedIn</span>
                            </a>

                            <!-- Pinterest -->
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo e(urlencode($articleUrl)); ?>&description=<?php echo e($articleTitle); ?>&media=<?php echo e(urlencode($articleImage)); ?>"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#BD081C] hover:bg-[#a00718] text-white rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 19c-.721 0-1.418-.109-2.073-.312.286-.465.713-1.227.95-1.878.097-.325.393-1.231.393-1.897 0-1.833-1.551-3.105-3.296-3.105-2.207 0-3.621 1.701-3.621 3.965 0 2.201 1.394 4.05 3.456 4.05.721 0 1.418-.109 2.073-.312-.286-.465-.713-1.227-.95-1.878-.097-.325-.393-1.231-.393-1.897 0-1.833 1.551-3.105 3.296-3.105 2.207 0 3.621 1.701 3.621 3.965 0 2.201-1.394 4.05-3.456 4.05z" />
                                </svg>
                                <span class="hidden sm:inline">Pinterest</span>
                            </a>

                            <!-- Copy Link -->
                            <button id="copyLinkBtn"
                                class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card rounded-lg transition-all text-sm sm:text-base font-semibold"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;" data-url="<?php echo e($articleUrl); ?>">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline" id="copyLinkText">Copy Link</span>
                                <span class="sm:hidden" id="copyLinkTextMobile">Copy</span>
                            </button>
                        </div>
                    </div>

                    <!-- Copy Link Success Message -->
                    <div id="copyLinkSuccess"
                        class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all"
                        style="font-family: 'Poppins', sans-serif;">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="font-semibold">Link copied to clipboard!</span>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const copyLinkBtn = document.getElementById('copyLinkBtn');
                            const copyLinkSuccess = document.getElementById('copyLinkSuccess');
                            const copyLinkText = document.getElementById('copyLinkText');
                            const copyLinkTextMobile = document.getElementById('copyLinkTextMobile');

                            if (copyLinkBtn) {
                                copyLinkBtn.addEventListener('click', function () {
                                    const url = copyLinkBtn.dataset.url;

                                    // Use modern Clipboard API if available
                                    if (navigator.clipboard && navigator.clipboard.writeText) {
                                        navigator.clipboard.writeText(url).then(function () {
                                            showCopySuccess();
                                        }).catch(function () {
                                            fallbackCopyText(url);
                                        });
                                    } else {
                                        fallbackCopyText(url);
                                    }
                                });
                            }

                            function showCopySuccess() {
                                if (copyLinkSuccess) {
                                    copyLinkSuccess.classList.remove('hidden');
                                    if (copyLinkText) copyLinkText.textContent = 'Copied!';
                                    if (copyLinkTextMobile) copyLinkTextMobile.textContent = 'Copied!';

                                    setTimeout(function () {
                                        copyLinkSuccess.classList.add('hidden');
                                        if (copyLinkText) copyLinkText.textContent = 'Copy Link';
                                        if (copyLinkTextMobile) copyLinkTextMobile.textContent = 'Copy';
                                    }, 2000);
                                }
                            }

                            function fallbackCopyText(text) {
                                const textArea = document.createElement('textarea');
                                textArea.value = text;
                                textArea.style.position = 'fixed';
                                textArea.style.left = '-999999px';
                                document.body.appendChild(textArea);
                                textArea.focus();
                                textArea.select();

                                try {
                                    document.execCommand('copy');
                                    showCopySuccess();
                                } catch (err) {
                                    console.error('Failed to copy text:', err);
                                    alert('Failed to copy link. Please copy manually: ' + text);
                                }

                                document.body.removeChild(textArea);
                            }
                        });
                    </script>


                    
                    <!-- Comments Section -->
                    <?php if($article->allow_comments): ?>
                        <div class="mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-gray-200 dark:!border-border-secondary">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:!text-white mb-4 sm:mb-6"
                                style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                Comments (<?php echo e($article->comments->count()); ?>)
                            </h2>

                            <!-- Comment Form -->
                            <div
                                class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 sm:p-6 mb-6 sm:mb-8">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:!text-white mb-3 sm:mb-4"
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Leave a Comment
                                </h3>

                                <?php if(session('success')): ?>
                                    <div
                                        class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
                                        <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>

                                <?php if(session('error')): ?>
                                    <div
                                        class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
                                        <?php echo e(session('error')); ?>

                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo e(route('comments.store', $article)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="name"
                                                class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="name" id="comment-name"
                                                value="<?php echo e(old('name', auth()->check() ? auth()->user()->name : '')); ?>" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                                placeholder="Your name">
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div>
                                            <label for="email"
                                                class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                Email <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" name="email" id="comment-email"
                                                value="<?php echo e(old('email', auth()->check() ? auth()->user()->email : '')); ?>"
                                                required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                                placeholder="your@email.com">
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="content"
                                            class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                            Comment <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="content" id="content" rows="5" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                            placeholder="Write your comment here..."><?php echo e(old('content')); ?></textarea>
                                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-4">
                                        <label for="captcha_answer"
                                            class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                            CAPTCHA: <?php echo e($captchaQuestion ?? '0 + 0'); ?> = ? <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="captcha_answer" id="captcha_answer" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                            placeholder="Enter the answer">
                                        <?php $__errorArgs = ['captcha_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <button type="submit"
                                        class="w-full sm:w-auto px-6 py-2.5 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-accent text-sm sm:text-base"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        Post Comment
                                    </button>
                                </form>
                            </div>

                            <!-- Comments List -->
                            <div id="commentsList" class="space-y-3">
                                <?php if($article->comments->count() > 0): ?>
                                    <?php $__currentLoopData = $article->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div
                                            class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-3 sm:p-4">
                                            <div class="flex items-start gap-2 sm:gap-4">
                                                <!-- Avatar -->
                                                <div class="flex-shrink-0">
                                                    <?php if($comment->user && $comment->user->avatar): ?>
                                                        <img src="<?php echo e($comment->user->avatar); ?>" alt="<?php echo e($comment->user->name); ?>"
                                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                                    <?php else: ?>
                                                        <div
                                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                                                            <?php echo e(strtoupper(substr($comment->user ? $comment->user->name : $comment->name, 0, 1))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Comment Content -->
                                                <div class="flex-1">
                                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                                        <h4 class="font-semibold text-gray-900 dark:!text-white text-xs sm:text-sm"
                                                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                            <?php echo e($comment->user ? $comment->user->name : $comment->name); ?>

                                                        </h4>
                                                        <?php if($comment->user && $comment->user->isAuthor()): ?>
                                                            <span
                                                                class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400"
                                                                style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                                                Author
                                                            </span>
                                                        <?php endif; ?>
                                                        <span class="text-xs text-gray-500 dark:!text-text-secondary"
                                                            style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                            <span class="hidden sm:inline">•</span>
                                                            <?php echo e($comment->created_at->diffForHumans()); ?>

                                                        </span>
                                                    </div>
                                                    <p class="text-gray-700 dark:!text-text-primary mb-2 whitespace-pre-line text-sm break-words"
                                                        style="font-family: 'Poppins', sans-serif; font-weight: 400; line-height: 1.6;">
                                                        <?php echo e(trim($comment->content)); ?>

                                                    </p>

                                                    <!-- Reply Button -->
                                                    <button onclick="showReplyForm('<?php echo e($comment->id); ?>')"
                                                        class="text-sm text-accent hover:text-accent-light font-semibold transition-colors"
                                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                        Reply
                                                    </button>

                                                    <!-- Reply Form (Hidden by default) -->
                                                    <div id="reply-form-<?php echo e($comment->id); ?>"
                                                        class="hidden mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200 dark:!border-border-secondary">
                                                        <form action="<?php echo e(route('comments.reply', [$article, $comment])); ?>"
                                                            method="POST">
                                                            <?php echo csrf_field(); ?>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                                <div>
                                                                    <label for="reply-name-<?php echo e($comment->id); ?>"
                                                                        class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                        Name <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" name="name" id="reply-name-<?php echo e($comment->id); ?>"
                                                                        class="reply-name-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                                                        value="<?php echo e(auth()->check() ? auth()->user()->name : ''); ?>"
                                                                        required placeholder="Your name">
                                                                </div>
                                                                <div>
                                                                    <label for="reply-email-<?php echo e($comment->id); ?>"
                                                                        class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                        Email <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="email" name="email" id="reply-email-<?php echo e($comment->id); ?>"
                                                                        class="reply-email-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                                                        value="<?php echo e(auth()->check() ? auth()->user()->email : ''); ?>"
                                                                        required placeholder="your@email.com">
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <textarea name="content" rows="3" required
                                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                                                    placeholder="Write your reply..."></textarea>
                                                            </div>

                                                            <div class="mb-4">
                                                                <label for="reply-captcha-<?php echo e($comment->id); ?>"
                                                                    class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2"
                                                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                    CAPTCHA: <?php echo e($captchaQuestion ?? '0 + 0'); ?> = ? <span
                                                                        class="text-red-500">*</span>
                                                                </label>
                                                                <input type="number" name="captcha_answer"
                                                                    id="reply-captcha-<?php echo e($comment->id); ?>" required
                                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                                                    placeholder="Enter the answer">
                                                                <?php $__errorArgs = ['captcha_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>

                                                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                                                <button type="submit"
                                                                    class="w-full sm:w-auto px-4 py-2 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all text-sm"
                                                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                    Post Reply
                                                                </button>
                                                                <button type="button" onclick="hideReplyForm('<?php echo e($comment->id); ?>')"
                                                                    class="w-full sm:w-auto px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all text-sm dark:!bg-bg-card-hover dark:!text-white"
                                                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <!-- Replies -->
                                                    <?php if($comment->replies->count() > 0): ?>
                                                        <div
                                                            class="mt-4 sm:mt-6 ml-4 sm:ml-8 space-y-3 sm:space-y-4 border-l-2 border-gray-200 dark:!border-border-secondary pl-3 sm:pl-6">
                                                            <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="flex items-start gap-2 sm:gap-4">
                                                                    <div class="flex-shrink-0">
                                                                        <?php if($reply->user && $reply->user->avatar): ?>
                                                                            <img src="<?php echo e($reply->user->avatar); ?>" alt="<?php echo e($reply->user->name); ?>"
                                                                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover">
                                                                        <?php else: ?>
                                                                            <div
                                                                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xs">
                                                                                <?php echo e(strtoupper(substr($reply->user ? $reply->user->name : $reply->name, 0, 1))); ?>

                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-3 mb-1">
                                                                            <h5 class="font-semibold text-gray-900 dark:!text-white text-xs sm:text-sm"
                                                                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                                                                <?php echo e($reply->user ? $reply->user->name : $reply->name); ?>

                                                                            </h5>
                                                                            <?php if($reply->user && $reply->user->isAuthor()): ?>
                                                                                <span
                                                                                    class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400"
                                                                                    style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                                                                    Author
                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <p class="text-xs text-gray-500 dark:!text-text-secondary mb-2"
                                                                            style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                                                            <?php echo e($reply->created_at->format('M d, Y \a\t g:i A')); ?>

                                                                        </p>
                                                                        <p class="text-gray-700 dark:!text-text-primary text-sm whitespace-pre-line break-words"
                                                                            style="font-family: 'Poppins', sans-serif; font-weight: 400; line-height: 1.6;">
                                                                            <?php echo e(trim($reply->content)); ?>

                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div id="noCommentsMessage"
                                        class="text-center py-12 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
                                        <p class="text-gray-600 dark:!text-text-secondary"
                                            style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                            No comments yet. Be the first to comment!
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mt-12 pt-8 border-t border-gray-200 dark:!border-border-secondary text-center">
                            <p class="text-gray-600 dark:!text-text-secondary"
                                style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Comments are disabled for this article.
                            </p>
                        </div>
                    <?php endif; ?>

                </div> <!-- End Content Column -->

                <!-- Right Sidebar -->
                <div class="w-full lg:w-80 xl:w-96 flex-shrink-0 space-y-8 mt-12 lg:mt-0">

                    <!-- Table of Contents -->
                    <?php
                        // Ensure headings is always available as an array
                        if (!isset($headings) || !is_array($headings)) {
                            $headings = [];
                        }
                    ?>
                    <?php if(count($headings) > 0): ?>
                        <div class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm sticky top-32 transition-all duration-300 hover:shadow-md hidden lg:block"
                            id="toc-container">
                            <div
                                class="p-6 border-b border-gray-100 dark:!border-border-primary bg-gray-50 dark:!bg-bg-card-hover">
                                <h3 class="text-lg font-bold text-gray-900 dark:!text-white flex items-center gap-2 uppercase tracking-wider"
                                    style="font-family: 'Poppins', sans-serif;">
                                    <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                                    Table of Contents
                                </h3>
                            </div>
                            <div class="p-6">
                                <ul class="space-y-3">
                                    <?php $__currentLoopData = $headings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li style="padding-left: <?php echo e(max(0, ($heading['level'] - 2) * 1.25)); ?>rem;">
                                            <a href="#<?php echo e(Str::slug($heading['text'])); ?>"
                                                onclick="scrollToHeading('<?php echo e(Str::slug($heading['text'])); ?>'); return false;"
                                                class="toc-link text-sm font-medium text-gray-600 dark:text-text-secondary hover:text-accent dark:hover:text-accent transition-colors flex items-start gap-2 group">
                                                <span
                                                    class="opacity-0 group-hover:opacity-100 transition-opacity mt-1.5 w-1.5 h-1.5 rounded-full bg-accent flex-shrink-0"></span>
                                                <span><?php echo e($heading['text']); ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    

                    <!-- Search Widget -->
                    <div
                        class="bg-gray-50 dark:!bg-bg-card rounded-2xl p-6 border border-gray-200 dark:!border-border-secondary shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-4 uppercase tracking-wider"
                            style="font-family: 'Poppins', sans-serif;">Search</h3>
                        <form action="<?php echo e(route('search')); ?>" method="GET" class="relative">
                            <input type="text" name="q" placeholder="Find stories..."
                                class="w-full pl-5 pr-12 py-3.5 bg-white dark:!bg-bg-card-hover border border-gray-200 dark:!border-border-primary rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent dark:!text-white transition-all font-medium shadow-sm">
                            <button type="submit"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Trending / Featured Widget -->
                    <?php if(isset($featuredArticles) && $featuredArticles->count() > 0): ?>
                        <div
                            class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm">
                            <div
                                class="p-6 border-b border-gray-100 dark:!border-border-primary bg-gray-50 dark:!bg-bg-card-hover">
                                <h3 class="text-lg font-bold text-gray-900 dark:!text-white flex items-center gap-2 uppercase tracking-wider"
                                    style="font-family: 'Poppins', sans-serif;">
                                    <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                                    Trending Now
                                </h3>
                            </div>
                            <div class="divide-y divide-gray-100 dark:!divide-border-primary">
                                <?php $__currentLoopData = $featuredArticles->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $featured): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('articles.show', $featured->slug)); ?>"
                                        class="flex gap-4 p-5 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors group items-start">
                                        <div class="w-20 h-20 flex-shrink-0 rounded-xl overflow-hidden relative shadow-md">
                                            <?php if($featured->featured_image): ?>
                                                <img src="<?php echo e($featured->featured_image_url); ?>" alt="<?php echo e($featured->title); ?>"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900"></div>
                                            <?php endif; ?>
                                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors">
                                            </div>
                                            <!-- Rank Badge -->
                                            <div
                                                class="absolute top-0 left-0 bg-accent text-white text-[10px] font-bold px-1.5 py-0.5 rounded-br-lg shadow-sm">
                                                #<?php echo e($index + 1); ?>

                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0 py-0.5">
                                            <span
                                                class="text-[10px] font-bold text-accent uppercase tracking-wider mb-1 block"><?php echo e($featured->category->name ?? 'News'); ?></span>
                                            <h4 class="text-sm font-bold text-gray-900 dark:!text-white leading-snug line-clamp-2 group-hover:text-accent transition-colors mb-1"
                                                style="font-family: 'Poppins', sans-serif;">
                                                <?php echo e($featured->title); ?>

                                            </h4>
                                            <div class="flex items-center gap-2 text-xs text-gray-400 dark:!text-text-tertiary">
                                                <span><?php echo e($featured->created_at->format('M d')); ?></span>
                                                <span>•</span>
                                                <span><?php echo e($featured->views); ?> views</span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Categories Widget -->
                    <?php if(isset($categories) && $categories->count() > 0): ?>
                        <div
                            class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-5 flex items-center gap-2 uppercase tracking-wider"
                                style="font-family: 'Poppins', sans-serif;">
                                <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                                Explore Topics
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('categories.show', $category->slug)); ?>"
                                        class="group pl-3 pr-4 py-2 bg-gray-50 hover:bg-accent hover:text-white dark:!bg-bg-card-hover dark:!text-text-secondary dark:!hover:bg-accent dark:!hover:text-white rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 border border-gray-100 dark:!border-border-primary hover:border-accent dark:!hover:border-accent hover:shadow-md">
                                        <span class="w-2 h-2 rounded-full bg-accent group-hover:bg-white transition-colors"></span>
                                        <span><?php echo e($category->name); ?></span>
                                        <span
                                            class="opacity-40 text-xs ml-auto group-hover:opacity-80">(<?php echo e($category->articles_count); ?>)</span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Newsletter Widget (Visual) -->
                    <div
                        class="relative rounded-2xl overflow-hidden p-8 text-center group shadow-xl transform hover:-translate-y-1 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-accent to-purple-800 opacity-95"></div>
                        <div
                            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tight"
                                style="font-family: 'Poppins', sans-serif;">Subscribe</h3>
                            <p class="text-white/90 text-sm mb-6 font-medium leading-relaxed">Get the latest entertainment
                                news, reviews, and exclusive updates delivered to your inbox.</p>
                            <form action="#" class="flex flex-col gap-3">
                                <input type="email" placeholder="Your email address"
                                    class="w-full px-5 py-3 rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:bg-white/20 focus:border-white transition-all backdrop-blur-sm font-medium">
                                <button type="button"
                                    class="w-full px-5 py-3 bg-white text-accent font-black uppercase tracking-wider rounded-xl hover:bg-gray-50 transition-colors shadow-lg">Join
                                    the Circle</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <?php if(isset($popularTags) && $popularTags->count() > 0): ?>
                        <div
                            class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-5 flex items-center gap-2 uppercase tracking-wider"
                                style="font-family: 'Poppins', sans-serif;">
                                <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                                Popular Tags
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $popularTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('tags.show', $tag->slug)); ?>"
                                        class="text-xs font-bold px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-black hover:text-white dark:!bg-bg-card-hover dark:!text-text-secondary dark:!hover:bg-white dark:!hover:text-black rounded-lg transition-colors uppercase tracking-wide border border-transparent hover:border-gray-900">
                                        #<?php echo e($tag->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div> <!-- End Right Sidebar -->

            </div> <!-- End Flex Container -->

            <!-- Related Articles Section (Full Width) -->
            <?php if($relatedArticles->count() > 0): ?>
                <div class="mt-20 pt-12 border-t border-gray-200 dark:!border-border-secondary">
                    <div class="flex items-end justify-between mb-10">
                        <div>
                            <span class="text-accent font-bold uppercase tracking-widest text-xs mb-2 block">Keep Reading</span>
                            <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:!text-white uppercase tracking-tight"
                                style="font-family: 'Poppins', sans-serif;">
                                More Like This
                            </h2>
                        </div>
                        <a href="<?php echo e(route('articles.index')); ?>"
                            class="group flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-accent transition-colors uppercase tracking-wide">
                            <span>View All Stories</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php $__currentLoopData = $relatedArticles->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedArticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('articles.show', $relatedArticle->slug)); ?>" class="group block h-full">
                                <div class="aspect-[16/10] rounded-2xl overflow-hidden mb-5 relative shadow-lg">
                                    <?php if($relatedArticle->featured_image): ?>
                                        <img src="<?php echo e($relatedArticle->featured_image_url); ?>" alt="<?php echo e($relatedArticle->title); ?>"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gray-200 dark:!bg-gray-800"></div>
                                    <?php endif; ?>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60 group-hover:opacity-40 transition-opacity">
                                    </div>
                                    <span
                                        class="absolute top-4 left-4 bg-accent/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-lg">
                                        <?php echo e($relatedArticle->category->name ?? 'Article'); ?>

                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-3 leading-tight group-hover:text-accent transition-colors"
                                    style="font-family: 'Poppins', sans-serif;">
                                    <?php echo e($relatedArticle->title); ?>

                                </h3>
                                <div class="flex items-center gap-3 text-xs text-gray-400 font-bold uppercase tracking-wide">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo e($relatedArticle->reading_time ?? 5); ?> min
                                    </span>
                                    <span>•</span>
                                    <span><?php echo e($relatedArticle->created_at->format('M d, Y')); ?></span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div> <!-- End Main Wrapper -->
    </article>

    <!-- Fixed Series Progress Widget (Bottom Right) -->
    <?php if($article->series && $seriesArticles && $seriesArticles->count() > 0): ?>
        <div class="hidden lg:block fixed bottom-4 right-4 z-40 w-72" x-data="{ open: false }">
            <div
                class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary shadow-xl overflow-hidden">
                <!-- Header (Clickable to toggle) -->
                <button @click="open = !open"
                    class="w-full p-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white hover:from-purple-600 hover:to-blue-600 transition-all">
                    <div class="flex items-center justify-between">
                        <div class="text-left">
                            <p class="text-xs font-semibold mb-0.5"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Article <?php echo e($currentSeriesIndex ?? 1); ?> of <?php echo e($totalSeriesArticles); ?>

                            </p>
                            <p class="text-xs opacity-90" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <?php echo e(round((($currentSeriesIndex ?? 1) / $totalSeriesArticles) * 100)); ?>% Complete
                            </p>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <!-- Progress Bar -->
                    <?php
                        $progressPercentage = $totalSeriesArticles ? round((($currentSeriesIndex ?? 1) / $totalSeriesArticles) * 100) : 0;
                    ?>
                    <div class="mt-2 w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full transition-all duration-300"
                            style="width: <?php echo e($progressPercentage); ?>%;"></div>
                    </div>
                </button>

                <!-- Expandable Content -->
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95" style="display: none;">
                    <div class="p-4 max-h-96 overflow-y-auto">
                        <a href="<?php echo e(route('series.show', $article->series->slug)); ?>"
                            class="text-xs font-semibold text-purple-600 dark:!text-purple-400 hover:text-purple-700 dark:!hover:text-purple-300 transition-colors mb-3 block"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            <?php echo e($article->series->title); ?>

                        </a>

                        <h4 class="text-xs font-semibold text-gray-900 dark:!text-white mb-2"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Table of Contents
                        </h4>
                        <div class="space-y-1.5">
                            <?php $__currentLoopData = $seriesArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seriesArticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div
                                    class="flex items-start gap-2 <?php echo e($seriesArticle->id === $article->id ? 'bg-purple-50 dark:!bg-purple-900/10 rounded p-1.5 -mx-1.5' : ''); ?>">
                                    <span class="text-xs text-gray-500 dark:!text-text-tertiary w-5 flex-shrink-0 pt-0.5"
                                        style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        <?php echo e($seriesArticle->series_order ?? $loop->iteration); ?>.
                                    </span>
                                    <?php if($seriesArticle->id === $article->id): ?>
                                        <span class="text-xs font-semibold text-purple-600 dark:!text-purple-400 flex-1 line-clamp-2"
                                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                            <?php echo e($seriesArticle->title); ?> <span class="text-purple-500">(Current)</span>
                                        </span>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('articles.show', $seriesArticle->slug)); ?>"
                                            class="text-xs text-gray-700 hover:text-purple-600 dark:!text-text-secondary dark:!hover:text-purple-400 flex-1 line-clamp-2 transition-colors"
                                            style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                            <?php echo e($seriesArticle->title); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // LocalStorage keys
        const COMMENT_NAME_KEY = 'comment_user_name';
        const COMMENT_EMAIL_KEY = 'comment_user_email';

        // Load saved name and email from localStorage
        document.addEventListener('DOMContentLoaded', function () {
            const savedName = localStorage.getItem(COMMENT_NAME_KEY);
            const savedEmail = localStorage.getItem(COMMENT_EMAIL_KEY);

            // Fill main comment form
            const nameInput = document.getElementById('comment-name');
            const emailInput = document.getElementById('comment-email');

            if (nameInput && savedName) {
                nameInput.value = savedName;
            }
            if (emailInput && savedEmail) {
                emailInput.value = savedEmail;
            }

            // Fill reply forms when they're shown
            const replyNameInputs = document.querySelectorAll('.reply-name-input');
            const replyEmailInputs = document.querySelectorAll('.reply-email-input');

            replyNameInputs.forEach(input => {
                if (savedName) {
                    input.value = savedName;
                }
            });

            replyEmailInputs.forEach(input => {
                if (savedEmail) {
                    input.value = savedEmail;
                }
            });
        });

        // AJAX Comment Submission
        document.addEventListener('DOMContentLoaded', function () {
            const commentForm = document.querySelector('form[action*="comments.store"]');
            if (commentForm) {
                commentForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    submitButton.disabled = true;
                    submitButton.textContent = 'Posting...';

                    // Save to localStorage
                    const nameInput = document.getElementById('comment-name');
                    const emailInput = document.getElementById('comment-email');

                    if (nameInput && nameInput.value.trim()) {
                        localStorage.setItem(COMMENT_NAME_KEY, nameInput.value.trim());
                    }
                    if (emailInput && emailInput.value.trim()) {
                        localStorage.setItem(COMMENT_EMAIL_KEY, emailInput.value.trim());
                    }

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            submitButton.disabled = false;
                            submitButton.textContent = originalText;

                            if (data.success) {
                                // Show success message
                                showMessage(data.message, 'success');

                                // Clear form
                                this.reset();

                                // Re-fill with saved values
                                if (nameInput) nameInput.value = localStorage.getItem(COMMENT_NAME_KEY) || '';
                                if (emailInput) emailInput.value = localStorage.getItem(COMMENT_EMAIL_KEY) || '';

                                // If not pending, add comment to list
                                if (!data.pending && data.comment) {
                                    addCommentToPage(data.comment);
                                    updateCommentCount();
                                    document.getElementById('noCommentsMessage')?.remove();
                                }
                            }
                        })
                        .catch(error => {
                            submitButton.disabled = false;
                            submitButton.textContent = originalText;

                            // Handle validation errors (including CAPTCHA)
                            if (error.errors && error.errors.captcha_answer) {
                                showMessage(error.errors.captcha_answer[0] || 'CAPTCHA answer is incorrect.', 'error');
                            } else {
                                showMessage(error.message || 'An error occurred. Please try again.', 'error');
                            }
                            console.error('Error:', error);
                        });
                });
            }

            // AJAX Reply Submission
            document.addEventListener('submit', function (e) {
                if (e.target.matches('form[action*="comments.reply"]')) {
                    e.preventDefault();

                    const form = e.target;
                    const formData = new FormData(form);
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    // Extract parent comment ID from form action: /articles/{article}/comments/{commentId}/reply
                    const actionParts = form.action.split('/');
                    const parentId = actionParts[actionParts.length - 2]; // Second to last part

                    submitButton.disabled = true;
                    submitButton.textContent = 'Posting...';

                    // Save to localStorage
                    const nameInput = form.querySelector('.reply-name-input');
                    const emailInput = form.querySelector('.reply-email-input');

                    if (nameInput && nameInput.value.trim()) {
                        localStorage.setItem(COMMENT_NAME_KEY, nameInput.value.trim());
                    }
                    if (emailInput && emailInput.value.trim()) {
                        localStorage.setItem(COMMENT_EMAIL_KEY, emailInput.value.trim());
                    }

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            submitButton.disabled = false;
                            submitButton.textContent = originalText;

                            if (data.success) {
                                showMessage(data.message, 'success');

                                // Hide reply form
                                const replyFormDiv = form.closest('[id^="reply-form-"]');
                                if (replyFormDiv) {
                                    const commentId = replyFormDiv.id.replace('reply-form-', '');
                                    hideReplyForm(commentId);
                                }

                                // If not pending, add reply to page
                                if (!data.pending && data.reply) {
                                    addReplyToPage(data.reply, parentId);
                                    updateCommentCount();
                                }
                            } else {
                                showMessage(data.message || 'An error occurred. Please try again.', 'error');
                            }
                        })
                        .catch(error => {
                            submitButton.disabled = false;
                            submitButton.textContent = originalText;

                            // Handle validation errors (including CAPTCHA)
                            if (error.errors && error.errors.captcha_answer) {
                                showMessage(error.errors.captcha_answer[0] || 'CAPTCHA answer is incorrect.', 'error');
                            } else {
                                showMessage(error.message || 'An error occurred. Please try again.', 'error');
                            }
                            console.error('Error:', error);
                        });
                }
            });
        });

        // Helper functions
        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-4 p-4 rounded-lg ${type === 'success' ? 'bg-green-100 border border-green-400 text-green-700 dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400' : 'bg-red-100 border border-red-400 text-red-700 dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400'}`;
            messageDiv.textContent = message;
            messageDiv.style.fontFamily = "'Poppins', sans-serif";

            const commentForm = document.querySelector('.bg-white.dark\\!bg-bg-card.rounded-lg.border');
            if (commentForm) {
                const existingMessage = commentForm.querySelector('.mb-4.p-4.rounded-lg');
                if (existingMessage) {
                    existingMessage.remove();
                }
                commentForm.insertBefore(messageDiv, commentForm.querySelector('form'));

                setTimeout(() => {
                    messageDiv.remove();
                }, 5000);
            }
        }

        function addCommentToPage(comment) {
            const commentsList = document.getElementById('commentsList');
            if (!commentsList) return;

            const commentHtml = `
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        ${comment.avatar ?
                    `<img src="${comment.avatar}" alt="${comment.name}" class="w-10 h-10 rounded-full object-cover">` :
                    `<div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-sm">${comment.name.charAt(0).toUpperCase()}</div>`
                }
                                </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-semibold text-gray-900 dark:!text-white text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                ${comment.name}
                                    </h4>
                            ${comment.is_author ? '<span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: \'Poppins\', sans-serif; font-weight: 500;">Author</span>' : ''}
                            <span class="text-xs text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                • ${comment.created_at}
                            </span>
                        </div>
                        <p class="text-gray-700 dark:!text-text-primary mb-2 whitespace-pre-line text-sm break-words" style="font-family: 'Poppins', sans-serif; font-weight: 400; line-height: 1.6;">
                            ${comment.content.trim()}
                        </p>
                        <button onclick="showReplyForm(${comment.id})" class="text-sm text-accent hover:text-accent-light font-semibold transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Reply
                        </button>
                        <div id="reply-form-${comment.id}" class="hidden mt-4 pt-4 border-t border-gray-200 dark:!border-border-secondary">
                            ${getReplyFormHtml(comment.id)}
                                </div>
                    </div>
                </div>
            </div>
        `;

            commentsList.insertAdjacentHTML('afterbegin', commentHtml);
        }

        function addReplyToPage(reply, parentId) {
            // Find parent comment - could be a main comment or a nested reply
            let parentElement = document.querySelector(`[data-comment-id="${parentId}"]`);

            // If not found as main comment, try to find in replies
            if (!parentElement) {
                const allReplies = document.querySelectorAll('.replies-container > div');
                for (let replyDiv of allReplies) {
                    if (replyDiv.getAttribute('data-reply-id') === parentId.toString()) {
                        parentElement = replyDiv;
                        break;
                    }
                }
            }

            if (!parentElement) {
                // Find by looking for the reply form that was just submitted
                const replyForm = document.querySelector(`form[action*="/comments/${parentId}/reply"]`);
                if (replyForm) {
                    parentElement = replyForm.closest('[data-comment-id]') || replyForm.closest('.bg-white');
                }
            }

            if (!parentElement) return;

            let repliesContainer = parentElement.querySelector('.replies-container');
            if (!repliesContainer) {
                repliesContainer = document.createElement('div');
                repliesContainer.className = 'mt-3 ml-6 space-y-2 border-l-2 border-gray-200 dark:!border-border-secondary pl-4 replies-container';
                const commentContent = parentElement.querySelector('.flex-1');
                if (commentContent) {
                    commentContent.appendChild(repliesContainer);
                }
            }

            const replyHtml = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    ${reply.avatar ?
                    `<img src="${reply.avatar}" alt="${reply.name}" class="w-8 h-8 rounded-full object-cover">` :
                    `<div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xs">${reply.name.charAt(0).toUpperCase()}</div>`
                }
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h5 class="font-semibold text-gray-900 dark:!text-white text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            ${reply.name}
                        </h5>
                        ${reply.is_author ? '<span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: \'Poppins\', sans-serif; font-weight: 500;">Author</span>' : ''}
                                <span class="text-xs text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            • ${reply.created_at}
                                </span>
                    </div>
                    <p class="text-gray-700 dark:!text-text-primary text-sm whitespace-pre-line break-words mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400; line-height: 1.6;">
                        ${reply.content.trim()}
                    </p>
                </div>
            </div>
        `;

            repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
        }

        function getReplyFormHtml(commentId) {
            const savedName = localStorage.getItem(COMMENT_NAME_KEY) || '';
            const savedEmail = localStorage.getItem(COMMENT_EMAIL_KEY) || '';
            const articleId = '<?php echo e($article->id); ?>';

            return `
            <form action="/articles/${articleId}/comments/${commentId}/reply" method="POST">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" class="reply-name-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white" value="${savedName}" required placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" class="reply-email-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white" value="${savedEmail}" required placeholder="your@email.com">
        </div>
    </div>
                <div class="mb-4">
                    <textarea name="content" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                              placeholder="Write your reply..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        CAPTCHA: <?php echo e($captchaQuestion ?? '0 + 0'); ?> = ? <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="captcha_answer" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="Enter the answer">
                </div>
                <div class="mb-4">
                    <textarea name="content" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white" placeholder="Write your reply..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Post Reply</button>
                    <button type="button" onclick="hideReplyForm(${commentId})" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all text-sm dark:!bg-bg-card-hover dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Cancel</button>
                </div>
            </form>
        `;
        }

        function updateCommentCount() {
            const commentsList = document.getElementById('commentsList');
            const comments = commentsList.querySelectorAll('.bg-white.dark\\!bg-bg-card.rounded-lg');
            const count = comments.length;
            const countElement = document.querySelector('h2');
            if (countElement && countElement.textContent.includes('Comments')) {
                countElement.textContent = `Comments (${count})`;
            }
        }

        function showReplyForm(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            replyForm.classList.remove('hidden');

            // Load saved name and email into reply form
            const savedName = localStorage.getItem(COMMENT_NAME_KEY);
            const savedEmail = localStorage.getItem(COMMENT_EMAIL_KEY);

            const nameInput = document.getElementById('reply-name-' + commentId);
            const emailInput = document.getElementById('reply-email-' + commentId);

            if (nameInput && savedName) {
                nameInput.value = savedName;
            }
            if (emailInput && savedEmail) {
                emailInput.value = savedEmail;
            }
        }

        function hideReplyForm(commentId) {
            document.getElementById('reply-form-' + commentId).classList.add('hidden');
            // Clear form
            const form = document.getElementById('reply-form-' + commentId).querySelector('form');
            if (form) {
                form.reset();

                // Re-fill with saved values after reset
                const savedName = localStorage.getItem(COMMENT_NAME_KEY);
                const savedEmail = localStorage.getItem(COMMENT_EMAIL_KEY);

                const nameInput = form.querySelector('.reply-name-input');
                const emailInput = form.querySelector('.reply-email-input');

                if (nameInput && savedName) {
                    nameInput.value = savedName;
                }
                if (emailInput && savedEmail) {
                    emailInput.value = savedEmail;
                }
            }
        }

        // Article Like functionality
        document.addEventListener('DOMContentLoaded', function () {
            const likeButton = document.getElementById('likeButton');
            if (likeButton) {
                likeButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    const articleSlug = this.getAttribute('data-article-slug');
                    if (!articleSlug) {
                        console.error('Article slug not found');
                        alert('Failed to like article. Article information is missing.');
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value ||
                        document.querySelector('input[name="csrf_token"]')?.value;

                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Security token missing. Please refresh the page and try again.');
                        return;
                    }

                    // Disable button during request
                    this.disabled = true;

                    // Create form data for POST request
                    const formData = new FormData();
                    formData.append('_token', csrfToken);

                    // Construct the like URL using article slug (route model binding uses slug)
                    const likeUrl = `/articles/${articleSlug}/like`;
                    fetch(likeUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    })
                        .then(response => {
                            // Handle different response statuses
                            if (response.status === 404) {
                                console.error('Like route not found:', likeUrl);
                                alert('Like feature is not available. Please try again later.');
                                throw new Error('Route not found');
                            }

                            if (!response.ok) {
                                // Try to parse error response
                                return response.text().then(text => {
                                    let errorData;
                                    try {
                                        errorData = JSON.parse(text);
                                    } catch (e) {
                                        errorData = { message: text || 'Failed to like article' };
                                    }
                                    console.error('Server error response:', errorData);
                                    throw new Error(errorData.message || errorData.error || 'Failed to like article');
                                });
                            }

                            return response.json();
                        })
                        .then(data => {
                            this.disabled = false;

                            const likesCountEl = document.getElementById('likesCount');
                            if (likesCountEl && data.likes_count !== undefined) {
                                const likeText = data.likes_count === 1 ? 'Like' : 'Likes';
                                const parent = likesCountEl.parentElement;
                                if (parent) {
                                    parent.innerHTML = `<span id="likesCount">${data.likes_count}</span> ${likeText}`;
                                }
                            }

                            // Update button state
                            if (data.liked) {
                                this.setAttribute('data-liked', 'true');
                                this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200', 'dark:!bg-bg-card-hover', 'dark:!text-white', 'dark:!hover:bg-bg-card');
                                this.classList.add('bg-red-100', 'text-red-600', 'dark:!bg-red-900/20', 'dark:!text-red-400');
                                const svg = this.querySelector('svg');
                                if (svg) {
                                    svg.setAttribute('fill', 'currentColor');
                                }
                            } else {
                                this.setAttribute('data-liked', 'false');
                                this.classList.remove('bg-red-100', 'text-red-600', 'dark:!bg-red-900/20', 'dark:!text-red-400');
                                this.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200', 'dark:!bg-bg-card-hover', 'dark:!text-white', 'dark:!hover:bg-bg-card');
                                const svg = this.querySelector('svg');
                                if (svg) {
                                    svg.setAttribute('fill', 'none');
                                }
                            }
                        })
                        .catch(error => {
                            this.disabled = false;
                            console.error('Like error:', error);
                            console.error('Error details:', {
                                message: error.message,
                                stack: error.stack,
                                likeUrl: likeUrl,
                                articleSlug: articleSlug
                            });

                            // Don't show alert if it's already been shown (e.g., for 404)
                            if (error.message !== 'Route not found') {
                                const errorMessage = error.message || 'Failed to like article. Please try again.';
                                alert(errorMessage);
                            }
                        });
                });
            }

            // Article Bookmark functionality
            const bookmarkButton = document.getElementById('bookmarkButton');
            if (bookmarkButton) {
                bookmarkButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    const articleSlug = this.getAttribute('data-article-slug');
                    if (!articleSlug) {
                        console.error('Article slug not found');
                        alert('Failed to bookmark article. Article information is missing.');
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value ||
                        document.querySelector('input[name="csrf_token"]')?.value;

                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Security token missing. Please refresh the page and try again.');
                        return;
                    }

                    // Disable button during request
                    this.disabled = true;

                    // Create form data for POST request
                    const formData = new FormData();
                    formData.append('_token', csrfToken);

                    // Construct the bookmark URL using article slug (route model binding uses slug)
                    const bookmarkUrl = `/articles/${articleSlug}/bookmark`;

                    fetch(bookmarkUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    })
                        .then(response => {
                            // Handle authentication errors
                            if (response.status === 401) {
                                return response.json().then(err => {
                                    alert(err.message || 'You must be logged in to bookmark articles.');
                                    window.location.href = '<?php echo e(route("login")); ?>';
                                    throw new Error('Unauthorized');
                                });
                            }

                            // Handle 404 errors
                            if (response.status === 404) {
                                console.error('Bookmark route not found:', bookmarkUrl);
                                alert('Bookmark feature is not available. Please try again later.');
                                throw new Error('Route not found');
                            }

                            if (!response.ok) {
                                // Try to parse error response
                                return response.text().then(text => {
                                    let errorData;
                                    try {
                                        errorData = JSON.parse(text);
                                    } catch (e) {
                                        errorData = { message: text || 'Failed to bookmark article' };
                                    }
                                    console.error('Server error response:', errorData);
                                    throw new Error(errorData.message || errorData.error || 'Failed to bookmark article');
                                });
                            }

                            return response.json();
                        })
                        .then(data => {
                            this.disabled = false;

                            if (data.success) {
                                // Update button state
                                const isBookmarked = data.bookmarked;
                                this.setAttribute('data-bookmarked', isBookmarked ? 'true' : 'false');

                                const buttonText = this.querySelector('span');
                                const svg = this.querySelector('svg');

                                if (isBookmarked) {
                                    this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200', 'dark:!bg-bg-card-hover', 'dark:!text-white', 'dark:!hover:bg-bg-card');
                                    this.classList.add('bg-yellow-100', 'text-yellow-600', 'dark:!bg-yellow-900/20', 'dark:!text-yellow-400');
                                    if (svg) svg.setAttribute('fill', 'currentColor');
                                    if (buttonText) buttonText.textContent = 'Bookmarked';
                                } else {
                                    this.classList.remove('bg-yellow-100', 'text-yellow-600', 'dark:!bg-yellow-900/20', 'dark:!text-yellow-400');
                                    this.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200', 'dark:!bg-bg-card-hover', 'dark:!text-white', 'dark:!hover:bg-bg-card');
                                    if (svg) svg.setAttribute('fill', 'none');
                                    if (buttonText) buttonText.textContent = 'Bookmark';
                                }

                                // Show message if available
                                if (data.message) {
                                    showMessage(data.message, 'success');
                                }
                            }
                        })
                        .catch(error => {
                            this.disabled = false;
                            console.error('Bookmark error:', error);
                            console.error('Error details:', {
                                message: error.message,
                                stack: error.stack,
                                bookmarkUrl: bookmarkUrl,
                                articleSlug: articleSlug
                            });

                            // Don't show alert if it's already been shown (e.g., for 401 or 404)
                            if (error.message !== 'Unauthorized' && error.message !== 'Route not found') {
                                // Try to get more details from the error
                                const errorMessage = error.message || 'Failed to bookmark article. Please try again.';
                                alert(errorMessage);
                            }
                        });
                });
            }
        });

        // Ensure images are responsive while preserving intended sizes
        document.addEventListener('DOMContentLoaded', function () {
            const articleContent = document.querySelector('.article-content');
            if (articleContent) {
                const images = articleContent.querySelectorAll('img');
                images.forEach(function (img, index) {
                    // Add lazy loading and optimization attributes if not already present
                    if (!img.hasAttribute('loading')) {
                        // First image loads eagerly, rest lazy
                        img.setAttribute('loading', index === 0 ? 'eager' : 'lazy');
                    }
                    if (!img.hasAttribute('decoding')) {
                        img.setAttribute('decoding', 'async');
                    }
                    // Make images clickable for lightbox
                    img.style.cursor = 'pointer';
                    img.classList.add('image-lightbox-trigger');
                    img.setAttribute('data-lightbox-index', index);
                    img.setAttribute('data-lightbox-src', img.src);
                    img.setAttribute('data-lightbox-alt', img.alt || '');

                    // Add click handler
                    img.addEventListener('click', function (e) {
                        e.preventDefault();
                        const allImages = Array.from(articleContent.querySelectorAll('img')).map(img => ({
                            src: img.src,
                            alt: img.alt || ''
                        }));
                        openImageLightbox(img.src, img.alt || '', allImages, index);
                    });

                    // Add hover effect
                    img.addEventListener('mouseenter', function () {
                        this.style.opacity = '0.9';
                    });
                    img.addEventListener('mouseleave', function () {
                        this.style.opacity = '1';
                    });
                    // Get computed styles to check actual width
                    const computedStyle = window.getComputedStyle(img);
                    const parentWidth = img.parentElement ? img.parentElement.offsetWidth : articleContent.offsetWidth;

                    // Check if image has inline width/height styles
                    const hasInlineWidth = img.style.width && img.style.width !== '';
                    const hasInlineHeight = img.style.height && img.style.height !== '';
                    const hasWidthAttr = img.hasAttribute('width');
                    const hasHeightAttr = img.hasAttribute('height');

                    // If image has fixed pixel width that might exceed container, make it responsive
                    if (hasInlineWidth) {
                        const widthValue = img.style.width;
                        // Check if it's a pixel value
                        if (widthValue.includes('px')) {
                            const pixelWidth = parseInt(widthValue);
                            // If pixel width exceeds parent or is very large, make responsive
                            if (pixelWidth > parentWidth || pixelWidth > 1200) {
                                img.style.maxWidth = '100%';
                                img.style.width = 'auto';
                                img.style.height = 'auto';
                            } else {
                                // Keep width but ensure max-width
                                img.style.maxWidth = '100%';
                            }
                        } else {
                            // Percentage or other units - just ensure max-width
                            img.style.maxWidth = '100%';
                        }
                    } else {
                        // No inline width - ensure max-width
                        img.style.maxWidth = '100%';
                    }

                    // Handle height - always auto for aspect ratio unless in specific cases
                    if (hasInlineHeight && hasInlineWidth) {
                        // Both width and height set - calculate aspect ratio but make responsive
                        img.style.height = 'auto';
                    } else if (!hasHeightAttr && !hasInlineHeight) {
                        img.style.height = 'auto';
                    }

                    // Handle width/height attributes
                    if (hasWidthAttr || hasHeightAttr) {
                        img.style.maxWidth = '100%';
                        img.style.height = 'auto';
                        img.style.width = 'auto';
                    }

                    // Add border-radius if not present
                    if (!img.style.borderRadius && !img.classList.contains('no-radius')) {
                        img.style.borderRadius = '8px';
                    }

                    // Set display based on parent element
                    if (!img.style.display) {
                        const parent = img.parentElement;
                        if (parent) {
                            if (parent.tagName === 'P') {
                                img.style.display = 'inline-block';
                                img.style.verticalAlign = 'middle';
                            } else if (parent.tagName === 'FIGURE') {
                                img.style.display = 'block';
                                img.style.width = '100%';
                            } else if (parent.tagName === 'DIV') {
                                img.style.display = 'block';
                                img.style.marginLeft = 'auto';
                                img.style.marginRight = 'auto';
                            } else {
                                img.style.display = 'block';
                            }
                        } else {
                            img.style.display = 'block';
                        }
                    }
                });
            }
        });

        // Image Lightbox with Zoom Functionality
        (function () {
            let currentImageIndex = 0;
            let imageGallery = [];
            let currentZoom = 1;
            let isDragging = false;
            let dragStart = { x: 0, y: 0 };
            let imagePosition = { x: 0, y: 0 };

            // Create lightbox HTML
            const lightboxHTML = `
            <div id="imageLightbox" class="image-lightbox" style="display: none;">
                <div class="lightbox-overlay"></div>
                <div class="lightbox-container">
                    <button class="lightbox-close" onclick="closeImageLightbox()" aria-label="Close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)" aria-label="Previous">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                    <div class="lightbox-image-container">
                        <img id="lightboxImage" src="" alt="" class="lightbox-image">
                    </div>
                    <div class="lightbox-controls">
                        <button class="lightbox-btn" onclick="zoomLightbox(0.25)" title="Zoom In">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </button>
                        <button class="lightbox-btn" onclick="zoomLightbox(-0.25)" title="Zoom Out">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </button>
                        <button class="lightbox-btn" onclick="resetLightboxZoom()" title="Reset Zoom">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                <path d="M21 3v5h-5"></path>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                <path d="M3 21v-5h5"></path>
                            </svg>
                        </button>
                        <button class="lightbox-btn" onclick="toggleFullscreen()" title="Fullscreen">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="lightbox-info">
                        <span id="lightboxImageCounter"></span>
                        <span id="lightboxImageTitle"></span>
                    </div>
                </div>
            </div>
        `;

            // Inject lightbox HTML
            document.body.insertAdjacentHTML('beforeend', lightboxHTML);

            const lightbox = document.getElementById('imageLightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            const imageContainer = document.querySelector('.lightbox-image-container');

            // Keyboard navigation
            document.addEventListener('keydown', function (e) {
                if (lightbox.style.display === 'none') return;

                switch (e.key) {
                    case 'Escape':
                        closeImageLightbox();
                        break;
                    case 'ArrowLeft':
                        navigateLightbox(-1);
                        break;
                    case 'ArrowRight':
                        navigateLightbox(1);
                        break;
                    case '+':
                    case '=':
                        zoomLightbox(0.25);
                        break;
                    case '-':
                    case '_':
                        zoomLightbox(-0.25);
                        break;
                    case '0':
                        resetLightboxZoom();
                        break;
                }
            });

            // Mouse wheel zoom
            imageContainer.addEventListener('wheel', function (e) {
                if (lightbox.style.display === 'none') return;
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.1 : 0.1;
                zoomLightbox(delta);
            });

            // Drag to pan when zoomed
            lightboxImage.addEventListener('mousedown', function (e) {
                if (currentZoom > 1) {
                    isDragging = true;
                    dragStart.x = e.clientX - imagePosition.x;
                    dragStart.y = e.clientY - imagePosition.y;
                    lightboxImage.style.cursor = 'grabbing';
                }
            });

            document.addEventListener('mousemove', function (e) {
                if (isDragging && currentZoom > 1) {
                    imagePosition.x = e.clientX - dragStart.x;
                    imagePosition.y = e.clientY - dragStart.y;
                    updateImageTransform();
                }
            });

            document.addEventListener('mouseup', function () {
                if (isDragging) {
                    isDragging = false;
                    lightboxImage.style.cursor = currentZoom > 1 ? 'grab' : 'default';
                }
            });

            function updateImageTransform() {
                lightboxImage.style.transform = `scale(${currentZoom}) translate(${imagePosition.x / currentZoom}px, ${imagePosition.y / currentZoom}px)`;
            }

            // Global functions
            window.openImageLightbox = function (src, alt, gallery = null, index = 0) {
                imageGallery = gallery || [{ src: src, alt: alt }];
                currentImageIndex = index;
                currentZoom = 1;
                imagePosition = { x: 0, y: 0 };

                lightbox.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                loadImage(src, alt);
                updateImageCounter();
            };

            window.closeImageLightbox = function () {
                lightbox.style.display = 'none';
                document.body.style.overflow = '';
                currentZoom = 1;
                imagePosition = { x: 0, y: 0 };
                updateImageTransform();
            };

            window.navigateLightbox = function (direction) {
                currentImageIndex += direction;
                if (currentImageIndex < 0) {
                    currentImageIndex = imageGallery.length - 1;
                } else if (currentImageIndex >= imageGallery.length) {
                    currentImageIndex = 0;
                }

                const image = imageGallery[currentImageIndex];
                currentZoom = 1;
                imagePosition = { x: 0, y: 0 };
                loadImage(image.src, image.alt);
                updateImageCounter();
            };

            window.zoomLightbox = function (factor) {
                currentZoom = Math.max(0.5, Math.min(5, currentZoom + factor));
                updateImageTransform();
                lightboxImage.style.cursor = currentZoom > 1 ? 'grab' : 'default';
            };

            window.resetLightboxZoom = function () {
                currentZoom = 1;
                imagePosition = { x: 0, y: 0 };
                updateImageTransform();
                lightboxImage.style.cursor = 'default';
            };

            window.toggleFullscreen = function () {
                if (!document.fullscreenElement) {
                    lightbox.requestFullscreen().catch(err => {
                        console.log('Fullscreen not supported');
                    });
                } else {
                    document.exitFullscreen();
                }
            };

            function loadImage(src, alt) {
                lightboxImage.src = src;
                lightboxImage.alt = alt;
                document.getElementById('lightboxImageTitle').textContent = alt;
                resetLightboxZoom();
            }

            function updateImageCounter() {
                if (imageGallery.length > 1) {
                    document.getElementById('lightboxImageCounter').textContent = `${currentImageIndex + 1} / ${imageGallery.length}`;
                } else {
                    document.getElementById('lightboxImageCounter').textContent = '';
                }
            }

            // Close on overlay click
            document.querySelector('.lightbox-overlay').addEventListener('click', closeImageLightbox);
        })();
    </script>

    <style>
        /* Image Lightbox Styles */
        .image-lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .lightbox-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .lightbox-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .lightbox-image-container {
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            transition: transform 0.3s ease;
            cursor: grab;
            user-select: none;
        }

        .lightbox-image:active {
            cursor: grabbing;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .lightbox-close:hover {
            background: rgba(255, 0, 0, 0.8);
            transform: scale(1.1);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.7);
            border: none;
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .lightbox-prev {
            left: 20px;
        }

        .lightbox-next {
            right: 20px;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) scale(1.1);
        }

        .lightbox-controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 30px;
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .lightbox-btn {
            background: transparent;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        /* Video Container for Auto-embedded videos */
        .video-container {
            width: 100%;
            height: auto;
            position: relative;
            background: #000;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3);
            margin: 2.5rem 0;
            aspect-ratio: 16 / 9; /* Modern aspect ratio handling */
        }

        /* Fallback for browsers that don't support aspect-ratio */
        @supports not (aspect-ratio: 16 / 9) {
            .video-container {
                height: 0;
                padding-bottom: 56.25%;
            }
        }

        /* Specific aspect ratios for non-16:9 players */
        .video-container.aspect-square {
            aspect-ratio: 1 / 1;
        }

        @supports not (aspect-ratio: 1 / 1) {
            .video-container.aspect-square {
                height: 0;
                padding-bottom: 100%;
            }
        }


        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Fixed heading offset for better readability */
        .article-content h1, 
        .article-content h2, 
        .article-content h3, 
        .article-content h4, 
        .article-content h5, 
        .article-content h6 {
            scroll-margin-top: 120px;
        }


        .lightbox-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .lightbox-info {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            z-index: 10;
            font-family: 'Poppins', sans-serif;
        }

        #lightboxImageCounter {
            font-weight: 600;
            margin-right: 10px;
            color: #ccc;
        }

        #lightboxImageTitle {
            font-weight: 500;
        }

        /* Make images in article clickable */
        .article-content img {
            transition: opacity 0.3s ease;
        }

        .article-content img:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .lightbox-controls {
                bottom: 20px;
                padding: 8px 15px;
                gap: 8px;
            }

            .lightbox-btn {
                width: 36px;
                height: 36px;
            }

            .lightbox-close,
            .lightbox-nav {
                width: 40px;
                height: 40px;
            }

            .lightbox-prev {
                left: 10px;
            }

            .lightbox-next {
                right: 10px;
            }

            .lightbox-info {
                bottom: 80px;
                font-size: 14px;
                padding: 8px 15px;
            }
        }

        /* Table of Contents Modern Styling */
        #toc-container {
            max-height: calc(100vh - 160px);
            display: flex;
            flex-direction: column;
        }

        #toc-container .p-6 {
            overflow-y: auto;
        }

        /* Custom Scrollbar for TOC */
        #toc-container .p-6::-webkit-scrollbar {
            width: 4px;
        }

        #toc-container .p-6::-webkit-scrollbar-track {
            background: transparent;
        }

        #toc-container .p-6::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dark #toc-container .p-6::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }

        .toc-link {
            position: relative;
            padding: 2px 0;
            line-height: 1.4;
            display: block;
        }

        .toc-link span:last-child {
            display: inline-block;
            transition: transform 0.2s ease;
        }

        .toc-link:hover span:last-child {
            transform: translateX(4px);
        }

        /* Progress Bar Glow */
        #readingProgress {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
        }

        .dark #readingProgress {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
        }
    </style>

    <script>
        /**
         * TOC & Reading Progress Functionality
         */

        function scrollToHeading(id) {
            const element = document.getElementById(id);
            if (element) {
                // Offset for fixed headers (adjust as needed)
                const offset = 100;
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Update URL hash without jumping
                history.pushState(null, null, '#' + id);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Inject IDs into headings for TOC linking
            const articleContent = document.querySelector('.article-content');
            if (articleContent) {
                const headings = articleContent.querySelectorAll('h2, h3, h4, h5, h6');
                headings.forEach((heading) => {
                    if (!heading.id) {
                        // Generate slug: lowercase, remove special characters, replace spaces with dashes
                        const slug = heading.textContent
                            .toLowerCase()
                            .trim()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                        heading.id = slug;
                    }
                });
            }

            // 2. Reading Progress Logic
            const progressBar = document.getElementById('readingProgress');
            const articleContainer = document.querySelector('.article-content');

            if (progressBar && articleContainer) {
                const updateProgress = () => {
                    const rect = articleContainer.getBoundingClientRect();
                    const articleHeight = articleContainer.offsetHeight;
                    const windowHeight = window.innerHeight;

                    // Calculate how much of the article has passed the bottom of the viewport
                    // We start counting progress when the top of the article enters the viewport
                    // and finish when the bottom of the article leaves the viewport.

                    let progress = 0;
                    const startPoint = articleContainer.offsetTop;
                    const endPoint = startPoint + articleHeight - windowHeight;
                    const currentPos = window.scrollY;

                    if (currentPos > startPoint) {
                        progress = ((currentPos - startPoint) / (articleHeight - windowHeight)) * 100;
                    }

                    progress = Math.min(100, Math.max(0, progress));
                    progressBar.style.width = progress + '%';

                    // Optional: Show/Hide TOC container based on scroll
                    const tocContainer = document.getElementById('toc-container');
                    if (tocContainer) {
                        if (currentPos > startPoint + 200 && currentPos < startPoint + articleHeight - 400) {
                            tocContainer.style.opacity = '1';
                        } else if (currentPos < startPoint) {
                            tocContainer.style.opacity = '0.8';
                        }
                    }
                };

                window.addEventListener('scroll', updateProgress);
                window.addEventListener('resize', updateProgress);
                updateProgress(); // Initial call
            }

            // 3. Active Link Highlighting (Intersection Observer)
            const tocLinks = document.querySelectorAll('.toc-link');
            if (tocLinks.length > 0 && articleContainer) {
                const headings = Array.from(articleContainer.querySelectorAll('h2, h3, h4, h5, h6'));

                const observerOptions = {
                    root: null,
                    rootMargin: '-100px 0px -70% 0px',
                    threshold: 0
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const id = entry.target.id;
                            tocLinks.forEach(link => {
                                link.classList.remove('text-accent', 'dark:text-accent', 'font-bold');
                                if (link.getAttribute('href') === '#' + id) {
                                    link.classList.add('text-accent', 'dark:text-accent', 'font-bold');
                                    // Ensure the active dot is visible
                                    const dot = link.querySelector('span');
                                    if (dot) dot.style.opacity = '1';
                                } else {
                                    const dot = link.querySelector('span');
                                    if (dot) dot.style.opacity = '0';
                                }
                            });
                        }
                    });
                }, observerOptions);

                headings.forEach(heading => observer.observe(heading));
            }
        });
    </script>

    <!-- Social Bar Ad - Removed -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/articles/show.blade.php ENDPATH**/ ?>