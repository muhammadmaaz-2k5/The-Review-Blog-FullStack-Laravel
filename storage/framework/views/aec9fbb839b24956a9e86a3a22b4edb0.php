<?php
    $cleanTitle = strip_tags($article->title);
    $faqs = [
        [
            'question' => "What is {$cleanTitle} about?",
            'answer' => "Read our complete guide and detailed breakdown of {$cleanTitle} above to discover all the key details and insights."
        ],
        [
            'question' => "Is {$cleanTitle} worth reading or watching?",
            'answer' => "Our in-depth review covers everything you need to know about {$cleanTitle} to help you decide."
        ],
        [
            'question' => "Where can I find more related content?",
            'answer' => "You can browse our related articles section below to find more content similar to {$cleanTitle}."
        ]
    ];

    // Generate JSON-LD schema
    $schemaEntities = [];
    foreach($faqs as $faq) {
        $schemaEntities[] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer']
            ]
        ];
    }
?>

<!-- AI/GEO SEO: FAQPage Structured Data -->
<?php $__env->startPush('head'); ?>
<script type="application/ld+json">
<?php echo json_encode([
    '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $schemaEntities
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

</script>
<?php $__env->stopPush(); ?>

<!-- Dynamic FAQs UI -->
<div class="dynamic-faqs mt-12 mb-8 bg-white dark:!bg-bg-card rounded-2xl shadow-sm border border-gray-100 dark:!border-border-primary overflow-hidden">
    <div class="p-6 md:p-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-6 flex items-center gap-3" style="font-weight:700;">
            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Frequently Asked Questions
        </h2>
        
        <div class="space-y-4">
            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="faq-item border border-gray-200 dark:!border-border-secondary rounded-lg overflow-hidden" x-data="{ open: <?php echo e($index === 0 ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full px-5 py-4 text-left flex justify-between items-center bg-gray-50 dark:!bg-bg-card-hover hover:bg-gray-100 dark:hover:!bg-gray-800 transition-colors focus:outline-none">
                        <span class="font-semibold text-gray-900 dark:!text-white pr-4" style="font-weight:600;"><?php echo e($faq['question']); ?></span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="px-5 py-4 bg-white dark:!bg-bg-card border-t border-gray-200 dark:!border-border-secondary text-gray-700 dark:!text-text-secondary leading-relaxed" style="display: none; font-weight:400;">
                        <?php echo e($faq['answer']); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/articles/partials/dynamic-faqs.blade.php ENDPATH**/ ?>