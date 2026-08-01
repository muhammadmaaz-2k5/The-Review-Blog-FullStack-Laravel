<?php $__env->startSection('title', $user->name . ' - Author Profile'); ?>

<?php $__env->startPush('head'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#141414] min-h-screen pb-4">
    <!-- Hero Cover -->
    <div class="relative w-full h-[40vh] min-h-[350px] lg:h-[50vh] overflow-hidden group">
        <?php if($user->cover_image): ?>
            <img src="<?php echo e($user->cover_image); ?>" alt="<?php echo e($user->name); ?>" class="w-full h-full object-cover transition-transform duration-[20s] group-hover:scale-105" onerror="this.style.display='none'">
        <?php else: ?>
            <div class="w-full h-full bg-gradient-to-br from-gray-900 via-[#0d0d0d] to-[#1a1a1a]"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        <?php endif; ?>
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-[#141414]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#141414]/30 to-transparent"></div>
    </div>

    <!-- Profile Info Container -->
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-32">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">
            
            <!-- Sidebar / Profile Card -->
            <div class="w-full lg:w-[350px] flex-shrink-0">
                <div class="bg-[#1a1a1a] rounded-3xl border border-white/10 p-6 shadow-2xl relative overflow-hidden">
                    <!-- Glow Effect -->
                    <div class="absolute top-0 right-0 w-2/3 h-2/3 bg-accent/5 blur-3xl rounded-full pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col items-center text-center">
                        <!-- Avatar -->
                        <div class="relative mb-6 group">
                            <div class="w-40 h-40 rounded-full p-1 bg-gradient-to-br from-white/20 to-white/5 shadow-2xl">
                                <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>" class="w-full h-full rounded-full object-cover border-4 border-[#1a1a1a] group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <?php if($isFollowing ?? false): ?>
                                <div class="absolute bottom-2 right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-[#1a1a1a] flex items-center justify-center text-white shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Name & Username -->
                        <h1 class="text-3xl font-black text-white mb-1 leading-tight" style="font-family: 'Poppins', sans-serif;">
                            <?php echo e($user->name); ?>

                        </h1>
                        <?php if($user->username): ?>
                            <p class="text-accent font-medium mb-4">@ <?php echo e($user->username); ?></p>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="flex gap-3 w-full mb-8">
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(Auth::id() === $user->id): ?>
                                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex-1 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/5 hover:border-white/20 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </a>
                                <?php else: ?>
                                    <div id="react-follow-button-root" 
                                         data-user-id="<?php echo e($user->id); ?>" 
                                         data-following="<?php echo e($isFollowing ? 'true' : 'false'); ?>"
                                         data-logged-in="true"
                                         class="flex-1 flex">
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div id="react-follow-button-root" 
                                     data-user-id="<?php echo e($user->id); ?>" 
                                     data-following="false"
                                     data-logged-in="false"
                                     class="flex-1 flex">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Bio -->
                        <?php if($user->bio): ?>
                            <p class="text-gray-400 text-sm leading-relaxed mb-6 line-clamp-4">
                                <?php echo e($user->bio); ?>

                            </p>
                        <?php endif; ?>

                        <!-- Meta Info -->
                        <div class="w-full space-y-3 pt-6 border-t border-white/5">
                            <?php if($user->location): ?>
                                <div class="flex items-center text-gray-400 text-sm">
                                    <svg class="w-4 h-4 mr-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <?php echo e($user->location); ?>

                                </div>
                            <?php endif; ?>
                            
                            <?php if($user->website || $user->twitter || $user->github || $user->linkedin): ?>
                                <div class="flex items-center gap-4 pt-2 justify-center">
                                    <?php if($user->website): ?>
                                        <a href="<?php echo e($user->website); ?>" target="_blank" class="text-gray-400 hover:text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg></a>
                                    <?php endif; ?>
                                    <?php if($user->twitter): ?>
                                        <a href="https://twitter.com/<?php echo e(ltrim($user->twitter, '@')); ?>" target="_blank" class="text-gray-400 hover:text-[#1DA1F2] transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg></a>
                                    <?php endif; ?>
                                    <!-- Add other social icons similarly styled -->
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid (Mobile/Sidebar) -->
                <div class="grid grid-cols-3 gap-2 mt-4">
                    <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-3 text-center">
                        <span class="block text-xl font-bold text-white"><?php echo e(number_format($stats['views'])); ?></span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Views</span>
                    </div>
                    <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-3 text-center">
                        <span class="block text-xl font-bold text-white"><?php echo e(number_format($stats['likes'])); ?></span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Likes</span>
                    </div>
                    <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 p-3 text-center">
                        <span id="followersCount" class="block text-xl font-bold text-white"><?php echo e(number_format($stats['followers'])); ?></span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Fans</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 w-full min-w-0 pt-8 lg:pt-0">
                
                <!-- Navigation Tabs -->
                <div class="flex items-center gap-8 border-b border-white/10 mb-10 overflow-x-auto no-scrollbar">
                    <a href="<?php echo e(route('profile.show', $user->username ?? $user->id)); ?>" class="pb-4 border-b-2 border-accent text-white font-bold text-sm uppercase tracking-wide whitespace-nowrap">
                        Articles
                    </a>
                </div>

                <!-- Badges Section -->
                <?php if($badges->count() > 0): ?>
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                            <span class="w-1 h-5 bg-accent rounded-sm"></span>
                            Achievements
                        </h2>
                        <div class="flex flex-wrap gap-4">
                            <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3 px-4 py-3 bg-[#1a1a1a] border border-white/5 rounded-xl hover:border-accent/30 transition-colors group cursor-default">
                                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                        <?php echo e($badge->icon); ?>

                                    </div>
                                    <div>
                                        <span class="block text-white font-bold text-sm group-hover:text-accent transition-colors"><?php echo e($badge->name); ?></span>
                                        <span class="text-xs text-gray-500"><?php echo e($badge->description); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Recent Articles Grid -->
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                            Latest Releases
                        </h2>
                    </div>

                    <?php if($recentArticles->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="articles-container">
                            <?php $__currentLoopData = $recentArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('profile._article_card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <!-- Load More Trigger -->
                        <div id="profile-load-more-trigger" class="py-8 text-center" data-page="2" data-url="<?php echo e(route('profile.load-more', $user->username ?? $user->id)); ?>" data-has-more="<?php echo e($recentArticles->hasMorePages() ? 'true' : 'false'); ?>">
                            <div class="inline-block animate-spin w-8 h-8 border-4 border-accent border-t-transparent rounded-full hidden" id="profile-load-more-spinner"></div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-20 bg-[#1a1a1a] rounded-3xl border border-white/5 border-dashed">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-1">No content yet</h3>
                            <p class="text-gray-500 text-sm">This creator hasn't published any articles.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Infinite Scroll Logic
    const trigger = document.getElementById('profile-load-more-trigger');
    const spinner = document.getElementById('profile-load-more-spinner');
    const container = document.getElementById('articles-container');
    
    if (trigger && container) {
        let isLoading = false;
        
        const observer = new IntersectionObserver((entries) => {
            const entry = entries[0];
            if (entry.isIntersecting && !isLoading && trigger.dataset.hasMore === 'true') {
                loadMore();
            }
        }, { rootMargin: '200px' });
        
        observer.observe(trigger);
        
        function loadMore() {
            isLoading = true;
            spinner.classList.remove('hidden');
            
            const url = trigger.dataset.url;
            const page = trigger.dataset.page;
            
            fetch(`${url}?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    container.insertAdjacentHTML('beforeend', data.html);
                }
                
                trigger.dataset.hasMore = data.hasMore ? 'true' : 'false';
                if (data.hasMore) {
                    trigger.dataset.page = parseInt(page) + 1;
                } else {
                    trigger.style.display = 'none';
                    observer.disconnect();
                }
            })
            .catch(error => console.error('Error loading more articles:', error))
            .finally(() => {
                isLoading = false;
                spinner.classList.add('hidden');
            });
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/profile/show.blade.php ENDPATH**/ ?>