<!DOCTYPE html>
<?php
    // Normalize locale to valid BCP 47 format (e.g., en_US -> en, en-US -> en)
    $htmlLang = $seo['locale'] ?? app()->getLocale() ?? 'en';
    // Extract language code (before underscore or hyphen) and convert to lowercase
    $htmlLang = strtolower(explode('_', explode('-', $htmlLang)[0])[0]);
?>
<html lang="<?php echo e($htmlLang); ?>">
<head>
    <!-- Clarity tracking code - Deferred for better LCP -->
    <script>
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.defer=true;t.src="https://www.clarity.ms/tag/"+i+"?ref=bwt";
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "uk3krprfwi");
    </script>
    
    <!-- Ahrefs Analytics tracking code - Already async -->
    <script src="https://analytics.ahrefs.com/analytics.js" data-key="fiM9R/3k5Rs/E/NBUoBOrQ" async></script>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZK9NE04CFK"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-ZK9NE04CFK');
    </script>
    
    <!-- Yandex Verification -->
    <meta name="yandex-verification" content="94d48238b177b601" />

    <!-- Pinterest Verification -->
    <meta name="p:domain_verify" content="95b5cd53b3e893c3e8b4ba73c79352dd"/>
    
    <!-- Google Search Console Verification -->
    <?php if(config('services.google.search_console_verification')): ?>
    <meta name="google-site-verification" content="<?php echo e(config('services.google.search_console_verification')); ?>" />
    <?php endif; ?>
    
    <!-- Additional Google Verification -->
    <?php if(config('services.google.site_verification')): ?>
    <meta name="google-site-verification" content="<?php echo e(config('services.google.site_verification')); ?>" />
    <?php endif; ?>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <?php
        // ALWAYS prioritize PageSeo from database - ignore controller SEO if PageSeo exists
        $seoService = app(\App\Services\SeoService::class);
        
        // Detect page key from route
        $routeName = request()->route()?->getName();
        $pageKeyMap = [
            'home' => 'home',
            'articles.index' => 'articles.index',
            'articles.show' => 'articles.show',
            
            'categories.index' => 'categories.index',
            'categories.show' => 'categories.show',
            'tags.index' => 'tags.index',
            'tags.show' => 'tags.show',
            'search' => 'search',
            'about' => 'about',
            'contact' => 'contact',
            'privacy' => 'privacy',
            'terms' => 'terms',
        ];
        
        $detectedPageKey = $pageKeyMap[$routeName] ?? null;
        
        // ALWAYS check PageSeo first - this overrides controller SEO
        if ($detectedPageKey) {
            // Use the model method to get fresh PageSeo data
            $pageSeo = \App\Models\PageSeo::getByPageKey($detectedPageKey);
            
            if ($pageSeo) {
                // PageSeo exists and is active - ALWAYS use it (overrides controller SEO)
                // Pass empty array to ensure PageSeo data takes priority
                $seo = $seoService->generate([], $detectedPageKey);
            } else {
                // No active PageSeo - use controller SEO or auto-detect
                $seo = $seo ?? $seoService->forCurrentRoute();
            }
        } else {
            // Unknown route - use controller SEO or auto-detect
            $seo = $seo ?? $seoService->forCurrentRoute();
        }
    ?>
    
    <!-- RSS Feed Links for Auto-Discovery -->
    <link rel="alternate" type="application/rss+xml" title="<?php echo e(config('app.name')); ?> RSS Feed" href="<?php echo e(route('feed')); ?>">
    <link rel="alternate" type="application/rss+xml" title="<?php echo e(config('app.name')); ?> RSS Feed" href="<?php echo e(url('/feed.xml')); ?>">
    
    <!-- Primary Meta Tags -->
    <title><?php echo e($seo['title'] ?? 'Nazaara Circle - Entertainment News & Reviews'); ?></title>
    <meta name="title" content="<?php echo e($seo['title'] ?? 'Nazaara Circle - Entertainment News & Reviews'); ?>">
    <meta name="description" content="<?php echo e($seo['description'] ?? 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends.'); ?>">
    <meta name="keywords" content="<?php echo e($seo['keywords'] ?? 'Nazaara Circle, entertainment, movie reviews, tv series explained, celebrity biographies, pop culture, cinema'); ?>">
    <meta name="author" content="<?php echo e($seo['author'] ?? 'Nazaara Circle'); ?>">
    <meta name="robots" content="<?php echo e($seo['robots'] ?? 'index, follow'); ?>">
    <meta name="language" content="<?php echo e($seo['locale'] ?? 'en'); ?>">
    <meta name="revisit-after" content="7 days">
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="coverage" content="worldwide">
    <meta name="target" content="all">
    <meta name="audience" content="all">
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    <meta name="apple-mobile-web-app-title" content="Nazaara Circle">
    <meta name="format-detection" content="telephone=no">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo e($seo['canonical'] ?? url()->current()); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo e($seo['type'] ?? 'website'); ?>">
    <meta property="og:url" content="<?php echo e($seo['url'] ?? url()->current()); ?>">
    <meta property="og:title" content="<?php echo e($seo['og_title'] ?? $seo['title'] ?? 'Nazaara Circle - Entertainment News & Reviews'); ?>">
    <meta property="og:description" content="<?php echo e($seo['og_description'] ?? $seo['description'] ?? 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.'); ?>">
    <meta property="og:image" content="<?php echo e($seo['og_image'] ?? $seo['image'] ?? asset('icon.png')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?php echo e($seo['title'] ?? 'Nazaara Circle'); ?>">
    <meta property="og:site_name" content="Nazaara Circle">
    <meta property="og:locale" content="<?php echo e($seo['locale'] ?? 'en_US'); ?>">
    <?php if(!empty($seo['published_time'])): ?>
    <meta property="og:published_time" content="<?php echo e($seo['published_time']); ?>">
    <?php endif; ?>
    <?php if(!empty($seo['modified_time'])): ?>
    <meta property="og:modified_time" content="<?php echo e($seo['modified_time']); ?>">
    <?php endif; ?>
    <?php if($seoService->getFacebookAppId()): ?>
    <meta property="fb:app_id" content="<?php echo e($seoService->getFacebookAppId()); ?>">
    <?php endif; ?>
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="<?php echo e($seo['twitter_card'] ?? 'summary_large_image'); ?>">
    <meta name="twitter:url" content="<?php echo e($seo['url'] ?? url()->current()); ?>">
    <meta name="twitter:title" content="<?php echo e($seo['twitter_title'] ?? $seo['title'] ?? 'Nazaara Circle - Entertainment News & Reviews'); ?>">
    <meta name="twitter:description" content="<?php echo e($seo['twitter_description'] ?? $seo['description'] ?? 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.'); ?>">
    <meta name="twitter:image" content="<?php echo e($seo['twitter_image'] ?? $seo['image'] ?? asset('icon.png')); ?>">
    <meta name="twitter:image:alt" content="<?php echo e($seo['twitter_title'] ?? $seo['title'] ?? 'Nazaara Circle'); ?>">
    <?php if($seoService->getTwitterHandle()): ?>
    <meta name="twitter:site" content="<?php echo e($seoService->getTwitterHandle()); ?>">
    <meta name="twitter:creator" content="<?php echo e($seoService->getTwitterHandle()); ?>">
    <?php endif; ?>
    
    <!-- Additional SEO Enhancements -->
    <meta name="theme-color" content="#E50914">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <meta name="application-name" content="Nazaara Circle">
    <meta name="msapplication-TileColor" content="#E50914">
    <meta name="msapplication-config" content="<?php echo e(asset('browserconfig.xml')); ?>">
    
    <!-- Alternate Languages (Hreflang) -->
    <?php if(!empty($seo['alternate_locales']) && is_array($seo['alternate_locales'])): ?>
        <?php $__currentLoopData = $seo['alternate_locales']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <link rel="alternate" hreflang="<?php echo e($locale); ?>" href="<?php echo e($url); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <link rel="alternate" hreflang="x-default" href="<?php echo e(url()->current()); ?>">
    <link rel="alternate" hreflang="<?php echo e(str_replace('_', '-', $seo['locale'] ?? 'en-US')); ?>" href="<?php echo e(url()->current()); ?>">
    
    <!-- Preconnect for Performance (Core Web Vitals Optimization) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://www.clarity.ms" crossorigin>
    <link rel="preconnect" href="https://analytics.ahrefs.com" crossorigin>
    
    <!-- Resource Hints for Better Performance -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="preload" as="script" href="https://cdn.tailwindcss.com">
    
    <!-- Structured Data (JSON-LD) -->
    <?php if(!empty($seo['schema'])): ?>
        <?php $__currentLoopData = $seo['schema']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script type="application/ld+json">
        <?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>

        </script>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    
    <!-- Favicon - Using icon.png with cache busting -->
    <?php
        $iconVersion = file_exists(public_path('icon.png')) ? filemtime(public_path('icon.png')) : time();
        $iconUrl = asset('icon.png') . '?v=' . $iconVersion;
    ?>
    <!-- Override default favicon.ico -->
    <link rel="icon" type="image/png" href="<?php echo e($iconUrl); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e($iconUrl); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e($iconUrl); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e($iconUrl); ?>">
    <!-- Explicitly override favicon.ico -->
    <link rel="alternate icon" type="image/png" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e($iconUrl); ?>">
    <link rel="apple-touch-icon" href="<?php echo e($iconUrl); ?>">
    <meta name="msapplication-TileImage" content="<?php echo e($iconUrl); ?>">
    <meta name="msapplication-TileColor" content="#000000">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
                    },
                    colors: {
                        'bg-primary': '#0D0D0D',
                        'bg-secondary': '#181818',
                        'bg-card': '#1F1F1F',
                        'bg-card-hover': '#2A2A2A',
                        'accent': '#E50914',
                        'accent-dark': '#B20710',
                        'accent-light': '#F40612',
                        'text-primary': '#FFFFFF',
                        'text-secondary': '#B3B3B3',
                        'text-tertiary': '#808080',
                        'text-muted': '#666666',
                        'border-primary': 'rgba(255, 255, 255, 0.1)',
                        'border-secondary': 'rgba(255, 255, 255, 0.05)',
                        'rating': '#FFD700',
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #E50914 0%, #B20710 100%)',
                        'gradient-overlay': 'linear-gradient(180deg, rgba(13, 13, 13, 0.3) 0%, rgba(13, 13, 13, 0.9) 100%)',
                    },
                    boxShadow: {
                        'accent': '0 10px 30px rgba(229, 9, 20, 0.3)',
                        'accent-lg': '0 10px 30px rgba(229, 9, 20, 0.4)',
                        'card': '0 4px 12px rgba(0, 0, 0, 0.3)',
                    }
                }
            }
        }
    </script>
    
    <!-- Preload critical CSS for better LCP -->
    <link rel="preload" as="style" href="<?php echo e(asset('css/theme.css')); ?>">
    <link rel="preload" as="style" href="<?php echo e(asset('css/components.css')); ?>">
    
    <!-- Load CSS files -->
    <link rel="stylesheet" href="<?php echo e(asset('css/theme.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
    
    <style>
        :root {
            /* ============================================
               DARK NEUTRAL + RED ACCENT THEME
               Professional theme optimized for movie/TV content
               ============================================ */
            
            /* Primary Background */
            --bg-primary: #0D0D0D;
            
            /* Secondary Background */
            --bg-secondary: #181818;
            
            /* Cards/Containers */
            --bg-card: #1F1F1F;
            --bg-card-hover: #2A2A2A;
            
            /* Accent Color (Buttons/Links) - Netflix Red */
            --color-accent: #E50914;
            --color-accent-dark: #B20710;
            --color-accent-light: #F40612;
            --color-accent-hover: #F40612;
            
            /* Text Colors */
            --text-primary: #FFFFFF;
            --text-secondary: #B3B3B3;
            --text-tertiary: #808080;
            --text-muted: #666666;
            --text-disabled: #4D4D4D;
            
            /* Background Overlays */
            --bg-overlay: rgba(13, 13, 13, 0.95);
            --bg-overlay-light: rgba(31, 31, 31, 0.8);
            --bg-overlay-medium: rgba(31, 31, 31, 0.9);
            --bg-overlay-dark: rgba(13, 13, 13, 0.98);
            --bg-input: rgba(31, 31, 31, 0.8);
            
            /* Border Colors */
            --border-primary: rgba(255, 255, 255, 0.1);
            --border-secondary: rgba(255, 255, 255, 0.05);
            --border-accent: rgba(229, 9, 20, 0.3);
            --border-focus: rgba(229, 9, 20, 0.5);
            
            /* Gradient Colors */
            --gradient-primary: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark) 100%);
            --gradient-primary-reverse: linear-gradient(135deg, var(--color-accent-dark) 0%, var(--color-accent) 100%);
            --gradient-background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            --gradient-overlay: linear-gradient(180deg, rgba(13, 13, 13, 0.3) 0%, rgba(13, 13, 13, 0.9) 100%);
            --gradient-overlay-dark: linear-gradient(180deg, rgba(13, 13, 13, 0.5) 0%, rgba(13, 13, 13, 0.95) 100%);
            
            /* Status Colors */
            --color-success: #4ade80;
            --color-warning: #fbbf24;
            --color-error: #ef4444;
            --color-info: #60a5fa;
            --color-rating: #FFD700;
            
            /* Shadow Colors */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 10px 30px rgba(229, 9, 20, 0.3);
            --shadow-glow: 0 0 20px rgba(229, 9, 20, 0.4);
            --shadow-card: 0 4px 12px rgba(0, 0, 0, 0.3);
            
            /* Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            
            /* Border Radius */
            --radius-xs: 4px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 25px;
            --radius-xl: 50px;
            --radius-full: 9999px;
            
            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
            
            /* Z-Index */
            --z-dropdown: 1000;
            --z-sticky: 1010;
            --z-modal: 1020;
            --z-tooltip: 1030;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
            border: none !important;
            outline: none !important;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Light Mode Styles (Default) */
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #FFFFFF !important;
            color: #1F1F1F !important;
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
            font-weight: 400;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        
        p {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            line-height: 1.7;
        }
        
        a {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }
        
        button {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        
        .bg-bg-primary {
            background-color: #FFFFFF !important;
        }
        
        .bg-bg-card {
            background-color: #FFFFFF !important;
            border-color: #E0E0E0 !important;
        }
        
        .bg-bg-card-hover {
            background-color: #F5F5F5 !important;
        }
        
        .text-text-primary {
            color: #1F1F1F !important;
        }
        
        .text-text-secondary {
            color: #666666 !important;
        }
        
        .text-text-tertiary {
            color: #808080 !important;
        }
        
        .text-text-muted {
            color: #999999 !important;
        }
        
        .border-border-primary {
            border-color: #E0E0E0 !important;
        }
        
        .border-border-secondary {
            border-color: #D0D0D0 !important;
        }
        
        nav {
            background-color: #FFFFFF !important;
            border-bottom-color: #E0E0E0 !important;
        }
        
        nav .text-text-primary {
            color: #1F1F1F !important;
        }
        
        footer {
            background-color: #F8F8F8 !important;
            border-top-color: #E0E0E0 !important;
        }
        
        footer .text-text-secondary {
            color: #666666 !important;
        }
        
        input, textarea, select {
            background-color: #F5F5F5 !important;
            border-color: #E0E0E0 !important;
            color: #1F1F1F !important;
        }
        
        input::placeholder, textarea::placeholder {
            color: #999999 !important;
        }
        
        /* Dark Mode Styles */
        body.dark-mode,
        html.dark body {
            background: #000000 !important;
            color: #FFFFFF !important;
        }
        
        body.dark-mode .bg-bg-primary,
        html.dark .bg-bg-primary {
            background-color: #000000 !important;
        }
        
        /* Main element background in dark mode */
        body.dark-mode main,
        html.dark main {
            background-color: #000000 !important;
        }
        
        body.dark-mode .bg-bg-card,
        html.dark .bg-bg-card {
            background-color: #1F1F1F !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        body.dark-mode .bg-bg-card-hover,
        html.dark .bg-bg-card-hover {
            background-color: #2A2A2A !important;
        }
        
        body.dark-mode .text-text-primary,
        html.dark .text-text-primary {
            color: #FFFFFF !important;
        }
        
        body.dark-mode .text-text-secondary,
        html.dark .text-text-secondary {
            color: #B3B3B3 !important;
        }
        
        body.dark-mode .text-text-tertiary,
        html.dark .text-text-tertiary {
            color: #808080 !important;
        }
        
        body.dark-mode .text-text-muted,
        html.dark .text-text-muted {
            color: #666666 !important;
        }
        
        body.dark-mode .border-border-primary,
        html.dark .border-border-primary {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        body.dark-mode .border-border-secondary,
        html.dark .border-border-secondary {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
        
        body.dark-mode nav,
        html.dark nav {
            background-color: rgba(0, 0, 0, 0.95) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        body.dark-mode nav .text-text-primary,
        html.dark nav .text-text-primary {
            color: #FFFFFF !important;
        }
        
        body.dark-mode footer,
        html.dark footer {
            background-color: #000000 !important;
            border-top-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        body.dark-mode footer .text-text-secondary,
        html.dark footer .text-text-secondary {
            color: #B3B3B3 !important;
        }
        
        body.dark-mode input, 
        body.dark-mode textarea, 
        body.dark-mode select,
        html.dark input,
        html.dark textarea,
        html.dark select {
            background-color: #1F1F1F !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #FFFFFF !important;
        }
        
        body.dark-mode input::placeholder, 
        body.dark-mode textarea::placeholder,
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #666666 !important;
        }
        
        /* Additional Dark Mode Rules for Cards and Text */
        body.dark-mode article,
        html.dark article {
            background-color: #000000 !important;
        }
        
        body.dark-mode article h2,
        html.dark article h2 {
            color: #FFFFFF !important;
        }
        
        body.dark-mode article p,
        html.dark article p {
            color: #B3B3B3 !important;
        }
        
        body.dark-mode article .text-gray-900,
        html.dark article .text-gray-900 {
            color: #FFFFFF !important;
        }
        
        body.dark-mode article .text-gray-600,
        html.dark article .text-gray-600 {
            color: #B3B3B3 !important;
        }
        
        body.dark-mode article .text-gray-500,
        html.dark article .text-gray-500 {
            color: #808080 !important;
        }
        
        body.dark-mode .bg-white,
        html.dark .bg-white {
            background-color: #000000 !important;
        }
        
        body.dark-mode .text-gray-900,
        html.dark .text-gray-900 {
            color: #FFFFFF !important;
        }
        
        body.dark-mode .text-gray-600,
        html.dark .text-gray-600 {
            color: #B3B3B3 !important;
        }
        
        body.dark-mode .text-gray-500,
        html.dark .text-gray-500 {
            color: #808080 !important;
        }
        
        body.dark-mode .border-gray-200,
        html.dark .border-gray-200 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        body.dark-mode .bg-gray-50,
        html.dark .bg-gray-50 {
            background-color: #2A2A2A !important;
        }
        
        body.dark-mode .bg-gray-100,
        html.dark .bg-gray-100 {
            background-color: #2A2A2A !important;
        }
        
        body.dark-mode .hover\:bg-gray-50:hover,
        html.dark .hover\:bg-gray-50:hover {
            background-color: #2A2A2A !important;
        }
        
        /* Force dark mode styles for all elements */
        html.dark body article,
        body.dark-mode article {
            background-color: #000000 !important;
        }
        
        /* Apply dark background to article divs, but exclude image containers and their children */
        html.dark body article > div:not(.aspect-video),
        body.dark-mode article > div:not(.aspect-video),
        html.dark body article div:not(.aspect-video):not(.aspect-video *) {
            background-color: #000000 !important;
        }
        
        /* Explicitly exclude image containers from dark background */
        html.dark body article .aspect-video,
        body.dark-mode article .aspect-video,
        html.dark body article .aspect-video *,
        body.dark-mode article .aspect-video * {
            background-color: transparent !important;
        }
        
        /* Keep the container background visible only for placeholder */
        html.dark body article .aspect-video.bg-gray-200.dark\:bg-gray-800,
        body.dark-mode article .aspect-video.bg-gray-200.dark\:bg-gray-800 {
            background-color: rgba(31, 41, 55, 0.3) !important;
        }
        
        html.dark body article h2,
        body.dark-mode article h2 {
            color: #FFFFFF !important;
        }
        
        html.dark body article h2 span,
        body.dark-mode article h2 span {
            color: #B3B3B3 !important;
        }
        
        /* Ensure titles are always visible - Light Mode */
        article h2 {
            color: #1F1F1F !important;
        }
        
        article h2 span {
            color: #666666 !important;
        }
        
        /* Ensure titles are always visible - Dark Mode */
        html.dark article h2,
        body.dark-mode article h2 {
            color: #FFFFFF !important;
        }
        
        html.dark article h2 span,
        body.dark-mode article h2 span {
            color: #B3B3B3 !important;
        }
        
        /* Override any inline styles for titles */
        html.dark article h2[style*="color"],
        body.dark-mode article h2[style*="color"] {
            color: #FFFFFF !important;
        }
        
        html.dark article h2 span[style*="color"],
        body.dark-mode article h2 span[style*="color"] {
            color: #B3B3B3 !important;
        }
        
        html.dark body article p,
        body.dark-mode article p {
            color: #B3B3B3 !important;
        }
        
        html.dark body article .text-gray-500,
        body.dark-mode article .text-gray-500 {
            color: #808080 !important;
        }
        
        html.dark body .bg-white,
        body.dark-mode .bg-white {
            background-color: #000000 !important;
        }
        
        html.dark body .text-gray-900,
        body.dark-mode .text-gray-900 {
            color: #FFFFFF !important;
        }
        
        html.dark body .text-gray-600,
        body.dark-mode .text-gray-600 {
            color: #B3B3B3 !important;
        }
        
        html.dark body .text-gray-500,
        body.dark-mode .text-gray-500 {
            color: #808080 !important;
        }
        
        html.dark body .border-gray-200,
        body.dark-mode .border-gray-200 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Sidebar dark mode fixes */
        html.dark body .lg\:col-span-1 div,
        body.dark-mode .lg\:col-span-1 div {
            background-color: #1F1F1F !important;
        }
        
        html.dark body .lg\:col-span-1 h3,
        body.dark-mode .lg\:col-span-1 h3 {
            color: #FFFFFF !important;
        }
        
        html.dark body .lg\:col-span-1 h4,
        body.dark-mode .lg\:col-span-1 h4 {
            color: #FFFFFF !important;
        }
        
        html.dark body .lg\:col-span-1 p,
        body.dark-mode .lg\:col-span-1 p {
            color: #B3B3B3 !important;
        }
        
        html.dark body .lg\:col-span-1 .border-b,
        body.dark-mode .lg\:col-span-1 .border-b {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Pagination dark mode */
        html.dark body .mt-8 a,
        body.dark-mode .mt-8 a {
            background-color: #1F1F1F !important;
            color: #B3B3B3 !important;
        }
        
        html.dark body .mt-8 a:hover,
        body.dark-mode .mt-8 a:hover {
            background-color: #2A2A2A !important;
            color: #FFFFFF !important;
        }
        
        html.dark body .mt-8 .bg-accent,
        body.dark-mode .mt-8 .bg-accent {
            background-color: #E50914 !important;
            color: #FFFFFF !important;
        }
        
        html.dark body .mt-8 span,
        body.dark-mode .mt-8 span {
            color: #B3B3B3 !important;
        }
        
        /* Alpine.js x-cloak - Hide elements before Alpine initializes */
        [x-cloak] {
            display: none !important;
        }
        
        
        /* Filter tabs styling - Light mode */
        .flex-wrap a.bg-white {
            background-color: #FFFFFF !important;
            color: #1F1F1F !important;
            border-color: #E0E0E0 !important;
        }
        
        .flex-wrap a.bg-white:hover {
            background-color: #F5F5F5 !important;
        }
        
        .flex-wrap a.bg-accent {
            background-color: #E50914 !important;
            color: #FFFFFF !important;
            border-color: #E50914 !important;
        }
        
        /* Filter tabs styling - Dark mode */
        html.dark .flex-wrap a.bg-white,
        body.dark-mode .flex-wrap a.bg-white {
            background-color: #1F1F1F !important;
            color: #B3B3B3 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        html.dark .flex-wrap a.bg-white:hover,
        body.dark-mode .flex-wrap a.bg-white:hover {
            background-color: #2A2A2A !important;
            color: #FFFFFF !important;
        }
        
        html.dark .flex-wrap a.bg-accent,
        body.dark-mode .flex-wrap a.bg-accent {
            background-color: #E50914 !important;
            color: #FFFFFF !important;
            border-color: #E50914 !important;
        }
        
        /* Ensure image container divs don't hide images in dark mode */
        html.dark article .aspect-video,
        body.dark-mode article .aspect-video {
            background-color: transparent !important;
        }
        
        html.dark article .aspect-video.bg-gray-200,
        body.dark-mode article .aspect-video.bg-gray-200,
        html.dark article .aspect-video.dark\:bg-gray-800,
        body.dark-mode article .aspect-video.dark\:bg-gray-800 {
            background-color: transparent !important;
            background: none !important;
        }
        
        /* Ensure images are fully visible and bright in dark mode */
        /* Exclude article content images to preserve their inline styles */
        html.dark article img:not(.article-content img),
        body.dark-mode article img:not(.article-content img),
        html.dark .aspect-video img,
        body.dark-mode .aspect-video img,
        html.dark article .aspect-video img,
        body.dark-mode article .aspect-video img,
        html.dark img[alt*="Movie"],
        body.dark-mode img[alt*="Movie"],
        html.dark img[alt*="TV Show"],
        body.dark-mode img[alt*="TV Show"] {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            filter: brightness(1.1) !important;
            -webkit-filter: brightness(1.1) !important;
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Article content images - preserve inline styles, only adjust visibility/brightness */
        html.dark .article-content img,
        body.dark-mode .article-content img {
            visibility: visible !important;
            opacity: 1 !important;
            filter: brightness(1.1) !important;
            -webkit-filter: brightness(1.1) !important;
            /* Don't override width/height/max-width - preserve inline styles */
        }
        
        /* Ensure card images have proper stacking context and same size in dark mode */
        html.dark article .relative img,
        body.dark-mode article .relative img,
        html.dark article .aspect-video img,
        body.dark-mode article .aspect-video img {
            position: absolute !important;
            z-index: 1 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
        }
        
        /* Ensure aspect-video container maintains exact same size in dark mode as light mode */
        html.dark article .aspect-video,
        body.dark-mode article .aspect-video {
            width: 100% !important;
            aspect-ratio: 16 / 9 !important;
            min-height: 0 !important;
            padding-bottom: 0 !important;
            padding-top: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            max-width: 100% !important;
        }
        
        /* Ensure article containers maintain same dimensions in dark mode */
        html.dark article,
        body.dark-mode article {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        
        /* Ensure article links maintain same dimensions */
        html.dark article > a,
        body.dark-mode article > a {
            width: 100% !important;
            display: block !important;
        }
        
        /* Make overlay lighter in dark mode to show more of the image */
        html.dark article .absolute.inset-0.bg-gradient-to-t,
        body.dark-mode article .absolute.inset-0.bg-gradient-to-t {
            pointer-events: none !important;
            mix-blend-mode: normal !important;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0.15) 50%, transparent 70%) !important;
        }
        
        html.dark article .absolute.inset-0 > div,
        body.dark-mode article .absolute.inset-0 > div {
            pointer-events: auto !important;
        }
        
        /* TinyMCE Content Styling */
        .article-content {
            line-height: 1.8;
        }
        
        .article-content h1,
        .article-content h2,
        .article-content h3,
        .article-content h4,
        .article-content h5,
        .article-content h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            margin-top: 1em;
            margin-bottom: 0.5em;
            line-height: 1.3;
        }
        
        .article-content h1 {
            font-size: 2.5em;
        }
        
        .article-content h2 {
            font-size: 2em;
        }
        
        .article-content h3 {
            font-size: 1.75em;
        }
        
        .article-content h4 {
            font-size: 1.5em;
        }
        
        .article-content p {
            margin-bottom: 0.75em;
            line-height: 1.7;
        }
        
        /* Base image styles - match TinyMCE editor styling */
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1em 0;
            display: inline-block;
            vertical-align: middle;
        }
        
        /* Handle images with width/height attributes - ensure responsiveness */
        .article-content img[width],
        .article-content img[height] {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
        }
        
        /* Images with only width in inline style */
        .article-content img[style*="width"]:not([style*="height"]) {
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Images with only height in inline style */
        .article-content img[style*="height"]:not([style*="width"]) {
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Images with both width and height in inline style - calculate aspect ratio */
        .article-content img[style*="width"][style*="height"] {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
        }
        
        /* Images in paragraphs - inline-block for text flow */
        .article-content p img {
            display: inline-block;
            vertical-align: middle;
            max-width: 100%;
        }
        
        /* Images in divs - can be block or inline-block */
        .article-content div img {
            display: block;
            margin: 1em auto;
            max-width: 100%;
        }
        
        /* Standalone images (direct children) - block display */
        .article-content > img {
            display: block;
            margin: 1em auto;
            max-width: 100%;
        }
        
        /* Images in figures - full width */
        .article-content figure img {
            display: block;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Fix for images with fixed pixel widths that exceed container - most specific */
        .article-content img[style*="px"] {
            max-width: 100% !important;
            width: auto !important;
            height: auto !important;
        }
        
        /* Ensure images with percentage widths are also responsive */
        .article-content img[style*="%"] {
            max-width: 100% !important;
        }
        
        /* Mobile responsive adjustments for article content */
        @media (max-width: 640px) {
            .article-content {
                font-size: 0.95rem;
                line-height: 1.7;
            }
            
            .article-content h1 {
                font-size: 1.75em;
            }
            
            .article-content h2 {
                font-size: 1.5em;
            }
            
            .article-content h3 {
                font-size: 1.25em;
            }
            
            .article-content h4 {
                font-size: 1.1em;
            }
            
            .article-content img {
                max-width: 100%;
                height: auto;
                border-radius: 6px;
                margin: 0.75em 0;
            }
            
            /* Ensure responsiveness on mobile without breaking layout */
            .article-content img[style*="width"] {
                max-width: 100% !important;
            }
            
            .article-content img[style*="height"] {
                height: auto !important;
            }
            
            .article-content table {
                font-size: 0.875rem;
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .article-content pre {
                font-size: 0.8rem;
                padding: 0.5em;
            }
            
            .article-content blockquote {
                margin: 0.75em 0;
                padding-left: 0.75em;
            }
        }
        
        .article-content figure {
            margin: 1em 0;
        }
        
        .article-content figure img {
            max-width: 100%;
            width: 100%;
            height: auto;
            border-radius: 8px;
            display: block;
            margin: 0 auto;
        }
        
        /* Ensure figures are responsive */
        .article-content figure img[style*="width"] {
            max-width: 100% !important;
        }
        
        .article-content figure img[style*="height"] {
            height: auto !important;
        }
        
        .article-content figcaption {
            text-align: center;
            font-size: 0.875em;
            color: #666;
            margin-top: 0.5em;
            font-style: italic;
        }
        
        .article-content pre {
            background-color: #f4f4f4;
            padding: 0.75em;
            border-radius: 6px;
            overflow-x: auto;
            margin: 1em 0;
            border: 1px solid #e5e7eb;
            position: relative;
        }
        
        /* Code samples from TinyMCE codesample plugin */
        .article-content pre[class*="language-"],
        .article-content code[class*="language-"] {
            font-family: 'Fira Code', 'Courier New', 'Consolas', monospace;
            font-size: 0.9em;
            line-height: 1.6;
            direction: ltr;
            text-align: left;
            white-space: pre;
            word-spacing: normal;
            word-break: normal;
            tab-size: 4;
            hyphens: none;
        }
        
        .article-content pre[class*="language-"] {
            background-color: #2d2d2d;
            color: #f8f8f2;
            padding: 0.75em 1em;
            margin: 1em 0;
            overflow: auto;
            border-radius: 8px;
            border: 1px solid #3d3d3d;
        }
        
        .article-content code {
            background-color: #f4f4f4;
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: 'Fira Code', 'Courier New', 'Consolas', monospace;
            font-size: 0.9em;
            color: #e83e8c;
        }
        
        /* Inline code (not in pre) */
        .article-content p code,
        .article-content li code,
        .article-content td code {
            background-color: #f4f4f4;
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-size: 0.9em;
        }
        
        /* Code inside pre blocks */
        .article-content pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
            font-size: inherit;
            border-radius: 0;
        }
        
        /* Ensure code samples are visible */
        .article-content pre code[class*="language-"] {
            display: block;
            color: #f8f8f2;
        }
        
        .article-content blockquote {
            border-left: 4px solid #E50914;
            margin: 1em 0;
            padding-left: 1em;
            color: #666;
            font-style: italic;
        }
        
        .article-content ul,
        .article-content ol {
            margin: 1em 0;
            padding-left: 2em;
        }
        
        .article-content li {
            margin-bottom: 0.5em;
        }
        
        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
        }
        
        .article-content table td,
        .article-content table th {
            border: 1px solid #ddd;
            padding: 0.75em;
        }
        
        .article-content table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        
        .article-content a {
            color: #E50914;
            text-decoration: underline;
        }
        
        .article-content a:hover {
            color: #b8070f;
        }
        
        /* Dark mode for article content - Force text visibility */
        html.dark .article-content,
        body.dark-mode .article-content {
            color: #FFFFFF !important;
        }
        
        /* Ensure all text elements in article content are visible in dark mode */
        html.dark .article-content p,
        body.dark-mode .article-content p,
        html.dark .article-content div,
        body.dark-mode .article-content div,
        html.dark .article-content span,
        body.dark-mode .article-content span,
        html.dark .article-content li,
        body.dark-mode .article-content li,
        html.dark .article-content td,
        body.dark-mode .article-content td {
            color: #B3B3B3 !important;
        }
        
        /* Headings in dark mode */
        html.dark .article-content h1,
        html.dark .article-content h2,
        html.dark .article-content h3,
        html.dark .article-content h4,
        html.dark .article-content h5,
        html.dark .article-content h6,
        body.dark-mode .article-content h1,
        body.dark-mode .article-content h2,
        body.dark-mode .article-content h3,
        body.dark-mode .article-content h4,
        body.dark-mode .article-content h5,
        body.dark-mode .article-content h6 {
            color: #FFFFFF !important;
        }
        
        html.dark .article-content figcaption,
        body.dark-mode .article-content figcaption {
            color: #9ca3af !important;
        }
        
        html.dark .article-content blockquote,
        body.dark-mode .article-content blockquote {
            color: #d1d5db !important;
            border-left-color: #E50914 !important;
        }
        
        html.dark .article-content pre,
        body.dark-mode .article-content pre {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
        
        html.dark .article-content pre[class*="language-"],
        body.dark-mode .article-content pre[class*="language-"] {
            background-color: #1e1e1e !important;
            border-color: #3d3d3d !important;
            color: #d4d4d4 !important;
        }
        
        html.dark .article-content code,
        body.dark-mode .article-content code {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
        }
        
        html.dark .article-content p code,
        html.dark .article-content li code,
        html.dark .article-content td code,
        body.dark-mode .article-content p code,
        body.dark-mode .article-content li code,
        body.dark-mode .article-content td code {
            background-color: #374151 !important;
            color: #f472b6 !important;
        }
        
        html.dark .article-content pre code[class*="language-"],
        body.dark-mode .article-content pre code[class*="language-"] {
            color: #d4d4d4 !important;
        }
        
        html.dark .article-content table td,
        html.dark .article-content table th,
        body.dark-mode .article-content table td,
        body.dark-mode .article-content table th {
            border-color: #4b5563 !important;
        }
        
        html.dark .article-content table th,
        body.dark-mode .article-content table th {
            background-color: #374151 !important;
        }
        
        /* Override any inline styles that might hide text in dark mode */
        html.dark .article-content [style*="color"],
        body.dark-mode .article-content [style*="color"] {
            color: inherit !important;
        }
        
        /* Secondary Navigation Bar (Below Main Navbar) */
        .secondary-nav-link {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            font-family: 'Poppins', sans-serif !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
            color: #374151 !important;
            padding: 0.25rem 0.5rem !important;
        }
        
        .secondary-nav-link:hover {
            color: #E50914 !important;
        }
        
        /* Dark mode secondary navigation */
        html.dark .secondary-nav-link,
        body.dark-mode .secondary-nav-link {
            color: #D1D5DB !important;
        }
        
        html.dark .secondary-nav-link:hover,
        body.dark-mode .secondary-nav-link:hover {
            color: #E50914 !important;
        }
        
        @media (min-width: 640px) {
            .secondary-nav-link {
                font-size: 0.9375rem !important;
            }
        }
        
        @media (min-width: 768px) {
            .secondary-nav-link {
                font-size: 1rem !important;
            }
        }
        
        /* Footer Styles */
        footer {
            background-color: #2c2c2c !important;
        }
        
        footer a {
            transition: color 0.3s ease;
        }
        
        footer .app-badge {
            height: 40px;
            width: auto;
            max-width: 135px;
            object-fit: contain;
        }
        
        footer .app-badge-large {
            height: 50px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
        }
        
        .coming-soon-badge {
            display: inline-block;
            background: rgba(229, 9, 20, 0.2);
            color: #ff6b6b;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .coming-soon-link {
            cursor: not-allowed;
            opacity: 0.7;
            position: relative;
        }
        
        .coming-soon-link:hover {
            opacity: 0.9;
        }
        
        /* Footer Navigation Styles - Ensure visibility */
        .footer-nav {
            display: block !important;
            visibility: visible !important;
            margin-bottom: 1.5rem !important;
        }
        
        .footer-nav ul {
            display: flex !important;
            visibility: visible !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 1rem !important;
        }
        
        .footer-nav ul li {
            display: inline-block !important;
        }
        
        .footer-nav-link {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            font-family: 'Poppins', sans-serif !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
            color: #374151 !important;
        }
        
        .footer-nav-link:hover {
            color: #E50914 !important;
        }
        
        /* Dark mode footer navigation */
        html.dark .footer-nav-link,
        body.dark-mode .footer-nav-link {
            color: #D1D5DB !important;
        }
        
        html.dark .footer-nav-link:hover,
        body.dark-mode .footer-nav-link:hover {
            color: #E50914 !important;
        }
        
        @media (min-width: 640px) {
            .footer-nav-link {
                font-size: 1rem !important;
            }
        }
        
        /* Navbars - Static positions, no scroll effects */
        
        #collapsedNavbar {
            display: none; /* Not used - removed scroll functionality */
        }
        
        /* Responsive navbar height */
        @media (max-width: 768px) {
            #mainNavbar .max-w-7xl > div {
                height: 3.5rem;
            }
            
            #collapsedNavbar {
                height: 3rem;
            }
        }
        
        /* Responsive secondary navbar */
        @media (max-width: 768px) {
            #secondaryNavbar {
                display: none;
            }
        }
        
        /* Responsive utility items container */
        @media (max-width: 1024px) {
            #mainNavbar ul.hidden.md\\:flex {
                gap: 0.25rem;
            }
        }
        
        @media (max-width: 768px) {
            #mainNavbar ul.hidden.md\\:flex {
                display: none;
            }
        }
        /* Utility Bar Items in Navbar */
        .utility-bar-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            min-width: 50px;
        }
        
        /* Responsive padding for utility items */
        @media (max-width: 1024px) {
            .utility-bar-item {
                padding: 0.5rem 0.5rem;
                min-width: 45px;
            }
        }
        
        @media (max-width: 768px) {
            .utility-bar-item {
                padding: 0.375rem 0.5rem;
                min-width: 40px;
            }
        }
        
        .utility-bar-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .utility-bar-item::after {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            bottom: 20%;
            width: 1px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .utility-bar-item:last-child::after {
            display: none;
        }
        
        .utility-bar-icon {
            width: 18px;
            height: 18px;
            margin-bottom: 0.25rem;
        }
        
        @media (max-width: 1024px) {
            .utility-bar-icon {
                width: 16px;
                height: 16px;
            }
        }
        
        .utility-bar-text {
            font-size: 0.625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 0.125rem 0.375rem;
            border-radius: 2px;
            margin-top: -0.25rem;
            color: #374151;
        }
        
        @media (max-width: 1024px) {
            .utility-bar-text {
                font-size: 0.5rem;
                padding: 0.125rem 0.25rem;
            }
        }
        
        .utility-bar-number {
            font-size: 0.75rem;
            font-weight: 700;
        }
        
        @media (max-width: 1024px) {
            .utility-bar-number {
                font-size: 0.625rem;
            }
        }
        
        /* Dark mode for utility items */
        html.dark .utility-bar-item,
        body.dark-mode .utility-bar-item {
            color: #D1D5DB;
        }
        
        html.dark .utility-bar-item:hover,
        body.dark-mode .utility-bar-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        html.dark .utility-bar-item::after,
        body.dark-mode .utility-bar-item::after {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        html.dark .utility-bar-text,
        body.dark-mode .utility-bar-text {
            background-color: rgba(13, 13, 13, 0.8);
            color: #D1D5DB;
        }
        
        /* Bottom Navigation Styles */
        #bottomNav {
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 8px 4px;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.3s ease;
            position: relative;
            min-width: 0;
        }
        
        .bottom-nav-item:hover {
            color: #E50914;
            background-color: rgba(229, 9, 20, 0.05);
        }
        
        .bottom-nav-item.active {
            color: #E50914;
        }
        
        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background-color: #E50914;
            border-radius: 0 0 3px 3px;
        }
        
        .bottom-nav-icon {
            width: 24px;
            height: 24px;
            margin-bottom: 4px;
            transition: transform 0.3s ease;
        }
        
        .bottom-nav-item:active .bottom-nav-icon {
            transform: scale(0.9);
        }
        
        .bottom-nav-label {
            font-size: 11px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        
        /* More Menu Dropdown */
        #bottomNavMoreMenu {
            max-height: calc(100vh - 4rem);
            z-index: 60;
        }
        
        #bottomNavMoreMenu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Add padding to main content on mobile to prevent overlap */
        @media (max-width: 767px) {
            main {
                padding-bottom: 4.5rem !important;
            }
            
            body {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
        
        /* Dark mode styles for bottom nav */
        html.dark .bottom-nav-item,
        body.dark-mode .bottom-nav-item {
            color: #9ca3af;
        }
        
        html.dark .bottom-nav-item:hover,
        body.dark-mode .bottom-nav-item:hover {
            color: #E50914;
            background-color: rgba(229, 9, 20, 0.1);
        }
        
        html.dark .bottom-nav-item.active,
        body.dark-mode .bottom-nav-item.active {
            color: #E50914;
        }
        
        /* Safe area support for devices with notches */
        .safe-area-inset-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* YouTube Shorts Responsive Embed */
        .article-content iframe[width="315"][height="560"] {
            max-width: 100%;
            height: auto;
            aspect-ratio: 315 / 560;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin: 1.5rem auto;
            display: block;
        }

        @media (max-width: 640px) {
            .article-content iframe[width="315"][height="560"] {
                max-width: 280px; /* Slightly smaller on very small screens */
            }
        }

        /* Top Navbar - Always Black Background */
        nav.bg-black {
            background-color: #000000 !important;
        }
        
        html.dark nav.bg-black,
        body.dark-mode nav.bg-black {
            background-color: #000000 !important;
            border-color: #1f1f1f !important;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('head'); ?>
    

    <!-- Clerk JS SDK & React App -->
    <?php if(config('services.clerk.publishable_key')): ?>
    <script>
        window.LARAVEL_AUTH = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
    </script>
    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/react/app.jsx']); ?>
    <?php endif; ?>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav id="topNavbar" class="bg-black !bg-black text-white border-b border-gray-800 sticky top-0 z-[60]" style="background-color: #000 !important;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-10">
                <!-- Left Section - Pages -->
                <div class="flex items-center gap-3 lg:gap-4">
                    <a href="<?php echo e(route('about')); ?>" class="text-xs sm:text-sm text-gray-300 hover:text-white transition-colors font-medium">About Us</a>
                    <a href="<?php echo e(route('privacy')); ?>" class="text-xs sm:text-sm text-gray-300 hover:text-white transition-colors font-medium">Privacy</a>
                    <a href="<?php echo e(route('tips.create')); ?>" class="text-xs sm:text-sm text-gray-300 hover:text-white transition-colors font-medium">Tip Us</a>
                    <a href="<?php echo e(route('contact')); ?>" class="text-xs sm:text-sm text-gray-300 hover:text-white transition-colors font-medium">Contact</a>
                    <a href="<?php echo e(route('feed')); ?>" class="text-xs sm:text-sm text-gray-300 hover:text-white transition-colors font-medium">RSS</a>
                </div>
                
                <!-- Right Section - Social Media Icons -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- YouTube -->
                    <a href="https://www.youtube.com/channel/UCOiiIYdcKBeMFDCa42iylmA" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-red-500 transition-colors" title="YouTube">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/profile.php?id=61585279116089" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-blue-600 transition-colors" title="Facebook">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/nazaaracircle" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-pink-600 transition-colors" title="Instagram">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    
                    <!-- Twitter/X -->
                    <a href="https://x.com/NazaaraCirlce" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-gray-100 transition-colors" title="Twitter">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <nav id="mainNavbar" class="sticky top-[40px] z-50 bg-white backdrop-blur-lg shadow-lg dark:!bg-bg-primary/95 dark:!border-border-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16 md:h-20">
                <a href="<?php echo e(route('home')); ?>" class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-accent hover:text-accent-light transition-colors dark-mode:text-accent" style="font-family: 'Poppins', sans-serif; font-weight: 800; letter-spacing: -0.03em;">
                    Nazaara Circle
                </a>
                
                <!-- Search Bar - Hidden on Mobile -->
                <div class="hidden md:flex flex-1 max-w-md mx-4 lg:mx-8 relative" x-data="{ searchOpen: false, searchQuery: '' }" @click.away="searchOpen = false">
                    <form action="<?php echo e(route('search')); ?>" method="GET" class="w-full" @submit="if(searchQuery.trim()) { searchOpen = false; }">
                        <div class="relative">
                            <div class="flex items-center bg-gray-100 dark:!bg-bg-card-hover rounded-lg border border-gray-300 dark:!border-border-primary">
                                <svg class="absolute left-3 w-5 h-5 text-gray-400 dark:!text-text-tertiary pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" 
                                       name="q" 
                                       x-model="searchQuery"
                                       value="<?php echo e(request('q')); ?>"
                                       placeholder="Search" 
                                       @focus="searchOpen = true"
                                       @input="searchOpen = true"
                                       class="w-full px-4 py-2 pl-10 pr-20 bg-transparent text-gray-900 placeholder-gray-500 focus:outline-none dark:!text-white dark:!placeholder-text-tertiary"
                                       style="font-family: 'Poppins', sans-serif;">
                                <div class="flex items-center gap-1 pr-2">
                                    <button type="submit" class="px-3 py-1 text-xs bg-gray-300 hover:bg-gray-400 dark:!bg-bg-card dark:!hover:bg-bg-card-hover text-gray-700 dark:!text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        GO
                                    </button>
                                    <a href="<?php echo e(route('search')); ?>" class="px-3 py-1 text-xs bg-gray-300 hover:bg-gray-400 dark:!bg-bg-card dark:!hover:bg-bg-card-hover text-gray-700 dark:!text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        ADVANCED
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Search Dropdown -->
                            <div x-show="searchOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute top-full left-0 right-0 mt-1 bg-white dark:!bg-bg-card border border-gray-300 dark:!border-border-primary rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto"
                                 style="display: none;"
                                 x-cloak>
                                <!-- Last Visited Section -->
                                <div id="lastVisitedSection" class="p-4">
                                    <h3 class="text-sm font-semibold text-gray-500 dark:!text-text-tertiary mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">LAST VISITED</h3>
                                    <div id="lastVisitedList" class="space-y-2">
                                        <!-- Last visited items will be populated by JavaScript -->
                                    </div>
                                    <div id="noLastVisited" class="text-sm text-gray-400 dark:!text-text-tertiary text-center py-4" style="display: none;">
                                        No recently visited articles
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <ul class="hidden md:flex items-center gap-2 lg:gap-3">
                    
                    </li>
                </ul>
                <div class="flex items-center gap-2 sm:gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <!-- User Dropdown - Desktop -->
                        <div class="relative hidden md:block" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 border border-gray-300 transition-all dark:!bg-bg-card dark:!border-border-primary dark:!hover:bg-bg-card-hover">
                                <?php if(auth()->user()->avatar): ?>
                                    <img src="<?php echo e(auth()->user()->avatar); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="w-8 h-8 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-white font-semibold">
                                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <span class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif;">
                                    <?php echo e(auth()->user()->name); ?>

                                </span>
                                <svg class="w-4 h-4 text-gray-900 dark:!text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white border border-gray-200 dark:!bg-bg-card dark:!border-border-primary z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-200 dark:!border-border-primary">
                                        <p class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif;">
                                            <?php echo e(auth()->user()->name); ?>

                                        </p>
                                        <p class="text-xs text-gray-500 dark:!text-text-muted" style="font-family: 'Poppins', sans-serif;">
                                            <?php echo e(auth()->user()->email); ?>

                                        </p>
                                    </div>
                                    <a href="<?php echo e(route('user.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            My Dashboard
                                        </div>
                                    </a>
                                    <?php if(auth()->user()->isAuthor()): ?>
                                        <a href="<?php echo e(route('author.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Author Dashboard
                                            </div>
                                        </a>
                                        <a href="<?php echo e(route('admin.articles.index')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                My Articles
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                Admin Dashboard
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:!text-red-400 dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Logout
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if(!request()->routeIs('login') && !request()->routeIs('register')): ?>
                            <!-- Login/Register Buttons - Desktop -->
                            <div class="hidden md:flex items-center gap-3">
                                <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 text-gray-900 hover:text-accent transition-colors font-semibold dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Login
                                </a>
                                <a href="<?php echo e(route('register')); ?>" class="px-6 py-2 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-accent" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Sign Up
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Theme Toggle -->
                    <button id="themeToggle" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 border border-gray-300 transition-all dark:!bg-bg-card dark:!border-border-primary dark:!hover:bg-bg-card-hover" title="Toggle Theme">
                        <svg id="sunIcon" class="w-5 h-5 text-gray-900 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg id="moonIcon" class="w-5 h-5 text-white hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>


     <!-- Collapsed Navbar (Shows on Scroll Down) -->
    <nav id="collapsedNavbar" class="fixed top-[40px] left-0 right-0 z-50 bg-white backdrop-blur-lg shadow-lg border-b border-gray-200 dark:!bg-bg-primary/95 dark:!border-border-primary transform -translate-y-full transition-transform duration-300" style="top: 40px !important;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <a href="<?php echo e(route('home')); ?>" class="text-lg sm:text-xl font-bold text-accent hover:text-accent-light transition-colors dark-mode:text-accent" style="font-family: 'Poppins', sans-serif; font-weight: 800;">
                    Nazaara Circle
                </a>
                
                <!-- Search Bar - Hidden on Mobile -->
                <div class="hidden md:flex flex-1 max-w-sm mx-4 lg:mx-6 relative" x-data="{ searchOpen: false, searchQuery: '' }" @click.away="searchOpen = false">
                    <form action="<?php echo e(route('search')); ?>" method="GET" class="w-full" @submit="if(searchQuery.trim()) { searchOpen = false; }">
                        <div class="relative">
                            <div class="flex items-center bg-gray-100 dark:!bg-bg-card-hover rounded-lg border border-gray-300 dark:!border-border-primary">
                                <svg class="absolute left-2.5 w-4 h-4 text-gray-400 dark:!text-text-tertiary pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" 
                                       name="q" 
                                       x-model="searchQuery"
                                       value="<?php echo e(request('q')); ?>"
                                       placeholder="Search" 
                                       @focus="searchOpen = true"
                                       @input="searchOpen = true"
                                       class="w-full px-3 py-1.5 pl-9 pr-16 text-sm bg-transparent text-gray-900 placeholder-gray-500 focus:outline-none dark:!text-white dark:!placeholder-text-tertiary"
                                       style="font-family: 'Poppins', sans-serif;">
                                <div class="flex items-center gap-1 pr-1.5">
                                    <button type="submit" class="px-2 py-0.5 text-xs bg-gray-300 hover:bg-gray-400 dark:!bg-bg-card dark:!hover:bg-bg-card-hover text-gray-700 dark:!text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        GO
                                    </button>
                                    <a href="<?php echo e(route('search')); ?>" class="px-2 py-0.5 text-xs bg-gray-300 hover:bg-gray-400 dark:!bg-bg-card dark:!hover:bg-bg-card-hover text-gray-700 dark:!text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        ADVANCED
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Search Dropdown -->
                            <div x-show="searchOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute top-full left-0 right-0 mt-1 bg-white dark:!bg-bg-card border border-gray-300 dark:!border-border-primary rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto"
                                 style="display: none;"
                                 x-cloak>
                                <!-- Last Visited Section -->
                                <div id="lastVisitedSectionCollapsed" class="p-3">
                                    <h3 class="text-xs font-semibold text-gray-500 dark:!text-text-tertiary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">LAST VISITED</h3>
                                    <div id="lastVisitedListCollapsed" class="space-y-1.5">
                                        <!-- Last visited items will be populated by JavaScript -->
                                    </div>
                                    <div id="noLastVisitedCollapsed" class="text-xs text-gray-400 dark:!text-text-tertiary text-center py-3" style="display: none;">
                                        No recently visited articles
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <ul class="hidden md:flex items-center gap-1 lg:gap-2">
                </ul>
                <div class="flex items-center gap-2 sm:gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <div class="relative hidden md:block" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-2 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 border border-gray-300 transition-all dark:!bg-bg-card dark:!border-border-primary dark:!hover:bg-bg-card-hover">
                                <?php if(auth()->user()->avatar): ?>
                                    <img src="<?php echo e(auth()->user()->avatar); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="w-6 h-6 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-full bg-accent flex items-center justify-center text-white text-xs font-semibold">
                                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <svg class="w-3 h-3 text-gray-900 dark:!text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white border border-gray-200 dark:!bg-bg-card dark:!border-border-primary z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-200 dark:!border-border-primary">
                                        <p class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif;">
                                            <?php echo e(auth()->user()->name); ?>

                                        </p>
                                        <p class="text-xs text-gray-500 dark:!text-text-muted" style="font-family: 'Poppins', sans-serif;">
                                            <?php echo e(auth()->user()->email); ?>

                                        </p>
                                    </div>
                                    <a href="<?php echo e(route('user.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                        My Dashboard
                                    </a>
                                    <?php if(auth()->user()->isAuthor()): ?>
                                        <a href="<?php echo e(route('author.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            Author Dashboard
                                        </a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 dark:!text-white dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            Admin Dashboard
                                        </a>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:!text-red-400 dark:!hover:bg-bg-card-hover transition-colors" style="font-family: 'Poppins', sans-serif;">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if(!request()->routeIs('login') && !request()->routeIs('register')): ?>
                            <div class="hidden md:flex items-center gap-2">
                                <a href="<?php echo e(route('login')); ?>" class="px-3 py-1.5 text-sm text-gray-900 hover:text-accent transition-colors font-semibold dark:!text-white" style="font-family: 'Poppins', sans-serif;">
                                    Login
                                </a>
                                <a href="<?php echo e(route('register')); ?>" class="px-4 py-1.5 bg-accent hover:bg-accent-light text-white text-sm font-semibold rounded-lg transition-all" style="font-family: 'Poppins', sans-serif;">
                                    Sign Up
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <button id="themeToggleCollapsed" class="p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 border border-gray-300 transition-all dark:!bg-bg-card dark:!border-border-primary dark:!hover:bg-bg-card-hover" title="Toggle Theme">
                        <svg id="sunIconCollapsed" class="w-4 h-4 text-gray-900 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg id="moonIconCollapsed" class="w-4 h-4 text-white hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <!-- Secondary Navigation Bar (Below Main Navbar) - Hidden on Mobile -->
    <nav id="secondaryNavbar" class="hidden md:block sticky top-[40px] z-40 bg-white border-b border-gray-200 dark:bg-bg-card dark:border-border-primary shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6 md:gap-8 py-3">
                <a href="<?php echo e(route('home')); ?>" class="secondary-nav-link">
                    Home
                </a>
                
                <?php
                    $top10Category = \App\Models\Category::where('slug', 'top-10')->first();
                ?>
                <?php if($top10Category): ?>
                <a href="<?php echo e(route('categories.show', $top10Category->slug)); ?>" 
                   class="group relative inline-flex items-center gap-2 px-4 py-2 rounded-lg overflow-hidden font-bold text-sm uppercase tracking-wider transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                   style="background: linear-gradient(135deg, <?php echo e($top10Category->color ?? '#E50914'); ?>, #b20710); color: white;">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e($top10Category->name ?? 'Top 10'); ?>

                    </span>
                    <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                    <span class="absolute -bottom-1 -right-1 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-500"></span>
                </a>
                <?php endif; ?>
                
                
                
                
                <a href="<?php echo e(route('articles.index')); ?>" class="secondary-nav-link">
                    Reviews
                </a>

                <a href="<?php echo e(route('series.index')); ?>" class="secondary-nav-link">
                    Series
                </a>
            </div>
        </div>
    </nav>
    
    <main style="overflow-x: visible;" class="pb-16 md:pb-0">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Bottom Navigation Menu (Mobile Only) -->
    <nav id="bottomNav" class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 dark:!bg-bg-primary dark:!border-border-primary shadow-lg md:hidden" style="box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
        <div class="flex items-center justify-around h-16 px-2 safe-area-inset-bottom">
            <!-- Home -->
            <a href="<?php echo e(route('home')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" data-route="home">
                <svg class="bottom-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="bottom-nav-label">Home</span>
            </a>
            
            <!-- Reviews -->
            <a href="<?php echo e(route('articles.index')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('articles.*') ? 'active' : ''); ?>" data-route="articles">
                <svg class="bottom-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="bottom-nav-label">LATEST STORIES</span>
            </a>
            
            <!-- Search -->
            <a href="<?php echo e(route('search')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('search') ? 'active' : ''); ?>" data-route="search">
                <svg class="bottom-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span class="bottom-nav-label">Search</span>
            </a>
            
            <!-- More Menu -->
            <button type="button" id="bottomNavMoreBtn" class="bottom-nav-item" aria-label="More menu">
                <svg class="bottom-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
                <span class="bottom-nav-label">More</span>
            </button>
        </div>
        
        <!-- More Menu Dropdown -->
        <div id="bottomNavMoreMenu" class="absolute bottom-full left-0 right-0 bg-white dark:!bg-bg-primary border-t border-gray-200 dark:!border-border-primary shadow-lg transform translate-y-full opacity-0 invisible transition-all duration-200" style="display: none;">
            <div class="py-2 max-h-96 overflow-y-auto">
                
                
                
                
                <a href="<?php echo e(route('series.index')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors border-b border-gray-100 dark:!border-border-primary">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>Series</span>
                    </div>
                </a>
                <a href="<?php echo e(route('how-to-circle')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors border-b border-gray-100 dark:!border-border-primary">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <span>How to Circle</span>
                    </div>
                </a>
                <a href="<?php echo e(route('about')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors border-b border-gray-100 dark:!border-border-primary">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>About Us</span>
                    </div>
                </a>
                <a href="<?php echo e(route('privacy')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors border-b border-gray-100 dark:!border-border-primary">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Privacy Terms</span>
                    </div>
                </a>
                <a href="<?php echo e(route('contact')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors border-b border-gray-100 dark:!border-border-primary">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Contact</span>
                    </div>
                </a>
                <?php if(auth()->guard()->check()): ?>
                    <div class="border-t border-gray-200 dark:!border-border-primary mt-2 pt-2">
                        <a href="<?php echo e(route('user.dashboard')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>My Dashboard</span>
                            </div>
                        </a>
                    </div>
                <?php else: ?>
                        <?php if(!request()->routeIs('login') && !request()->routeIs('register')): ?>
                            <div class="border-t border-gray-200 dark:!border-border-primary mt-2 pt-2">
                                <a href="<?php echo e(route('login')); ?>" class="block px-4 py-3 text-sm text-gray-900 dark:!text-white hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Login</span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12" style="background-color: #2c2c2c;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Left Section - Logo -->
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-3">
                        <h2 class="text-2xl font-bold text-white">Nazaara Circle</h2>
                        <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">360</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400">Your trusted source for entertainment news, movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends and cinema insights.</p>
                </div>
                
                <!-- Middle Section - Links (Two Columns) -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <ul class="space-y-2">
                            <li><a href="<?php echo e(route('about')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">About Us</a></li>
                            <li><a href="<?php echo e(route('sitemaps')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Sitemaps</a></li>
                            <li><a href="<?php echo e(route('tips.create')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Feedback</a></li>
                            <li><a href="<?php echo e(route('archives')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Archives</a></li>
                            <li><a href="<?php echo e(route('contact')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Contact Us</a></li>
                            <li><a href="<?php echo e(route('rss')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">RSS</a></li>
                                                    </ul>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <ul class="space-y-2">
                            <li><a href="<?php echo e(route('careers')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Career</a></li>
                            <li><a href="<?php echo e(route('privacy')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Privacy Terms</a></li>
                            <li><a href="<?php echo e(route('ethics')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Ethics</a></li>
                            <li><a href="<?php echo e(route('editorial-policy')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Editorial Policy</a></li>
                            <li><a href="<?php echo e(route('complaint-redressal')); ?>" class="text-gray-300 hover:text-white text-sm transition-colors">Complaint Redressal</a></li>
                        </ul>
                    </div>
                </div>
                
            <!-- Right Section -->
            <div class="space-y-6">
                <!-- Download Our Apps -->
                <div>
                    <p class="text-sm text-gray-300 mb-3">Download Our Apps</p>
                    <div class="flex flex-col gap-2">
                        <a href="#" onclick="return false;" class="inline-block bg-black rounded px-3 py-2 flex items-center gap-2 hover:opacity-80 transition-opacity coming-soon-link relative">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            <span class="text-white text-xs">Download on the<br>App Store</span>
                            <span class="coming-soon-badge absolute -top-1 -right-1">Coming Soon</span>
                        </a>
                        <a href="#" onclick="return false;" class="inline-block bg-black rounded px-3 py-2 flex items-center gap-2 hover:opacity-80 transition-opacity coming-soon-link relative">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L14.54,17.15L13.69,16.31L19.31,12.35L13.69,7.69L14.54,6.85L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                            </svg>
                            <span class="text-white text-xs">GET IT ON<br>Google Play</span>
                            <span class="coming-soon-badge absolute -top-1 -right-1">Coming Soon</span>
                        </a>
                    </div>
                </div>
                
            </div>
            </div>
            
            <!-- Copyright Section -->
            <div class="border-t border-gray-700 pt-6 mt-8">
                <p class="text-center text-sm text-gray-400">
                    Â© Copyright Red Pixels Ventures Limited <?php echo e(date('Y')); ?>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        const html = document.documentElement;
        const body = document.body;
        
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        if (currentTheme === 'dark') {
            html.classList.add('dark');
            body.classList.add('dark-mode');
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        } else {
            // Light mode is default, show sun icon
            html.classList.remove('dark');
            body.classList.remove('dark-mode');
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        }
        
        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            body.classList.toggle('dark-mode');
            
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                localStorage.setItem('theme', 'light');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        });
        
        // Theme Toggle for Collapsed Navbar
        const themeToggleCollapsed = document.getElementById('themeToggleCollapsed');
        const sunIconCollapsed = document.getElementById('sunIconCollapsed');
        const moonIconCollapsed = document.getElementById('moonIconCollapsed');
        
        if (themeToggleCollapsed) {
            themeToggleCollapsed.addEventListener('click', () => {
                html.classList.toggle('dark');
                body.classList.toggle('dark-mode');
                
                if (html.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                    if (sunIconCollapsed) sunIconCollapsed.classList.add('hidden');
                    if (moonIconCollapsed) moonIconCollapsed.classList.remove('hidden');
                    if (sunIcon) sunIcon.classList.add('hidden');
                    if (moonIcon) moonIcon.classList.remove('hidden');
                } else {
                    localStorage.setItem('theme', 'light');
                    if (sunIconCollapsed) sunIconCollapsed.classList.remove('hidden');
                    if (moonIconCollapsed) moonIconCollapsed.classList.add('hidden');
                    if (sunIcon) sunIcon.classList.remove('hidden');
                    if (moonIcon) moonIcon.classList.add('hidden');
                }
            });
        }

        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                // On mobile, reset all navbar states
                if (topNavbar) {
                    topNavbar.classList.remove('navbar-hidden');
                    body.classList.remove('top-navbar-hidden');
                }
                if (mainNavbar) {
                    mainNavbar.classList.remove('navbar-hidden');
                }
                if (collapsedNavbar) {
                    collapsedNavbar.classList.remove('navbar-visible');
                    body.classList.remove('collapsed-navbar-visible');
                }
            }
        });
        
    </script>
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- TinyMCE Editor (only for admin pages) -->
    <?php if(request()->is('admin/*')): ?>
        <?php echo $__env->make('components.head.tinymce-config', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    
     <?php echo $__env->yieldPushContent('scripts'); ?>

    
<?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/layouts/app.blade.php ENDPATH**/ ?>