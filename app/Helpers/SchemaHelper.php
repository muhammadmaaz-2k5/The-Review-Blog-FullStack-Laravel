<?php

namespace App\Helpers;

class SchemaHelper
{
    /**
     * Generate Organization schema
     */
    public static function organization(array $data = []): array
    {
        $defaults = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $data['name'] ?? 'Nazaara Circle',
            'url' => $data['url'] ?? url('/'),
            'logo' => $data['logo'] ?? url('/images/logo.png'),
            'sameAs' => $data['social_links'] ?? [],
        ];

        if (isset($data['contact_point'])) {
            $defaults['contactPoint'] = [
                '@type' => 'ContactPoint',
                'telephone' => $data['contact_point']['phone'] ?? '',
                'contactType' => $data['contact_point']['type'] ?? 'customer service',
            ];
        }

        return array_merge($defaults, $data);
    }

    /**
     * Generate Website schema
     */
    public static function website(array $data = []): array
    {
        return array_merge([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $data['name'] ?? 'Nazaara Circle',
            'url' => $data['url'] ?? url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $data['search_url'] ?? url('/search?q={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ], $data);
    }

    /**
     * Generate BreadcrumbList schema
     */
    public static function breadcrumbList(array $items): array
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'] ?? '',
                'item' => $item['url'] ?? '',
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }


    /**
     * Generate CollectionPage schema
     */
    public static function collectionPage(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $data['name'] ?? '',
            'url' => $data['url'] ?? '',
            'description' => $data['description'] ?? '',
        ];
    }

    /**
     * Generate Article schema
     */
    public static function article(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['headline'] ?? $data['name'] ?? '',
            'image' => $data['image'] ?? '',
            'description' => $data['description'] ?? '',
            'url' => $data['url'] ?? '',
            'datePublished' => $data['date_published'] ?? date('c'),
            'dateModified' => $data['date_modified'] ?? date('c'),
        ];

        if (isset($data['author'])) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => is_array($data['author']) ? ($data['author']['name'] ?? '') : $data['author'],
            ];
        }

        if (isset($data['publisher'])) {
            $schema['publisher'] = [
                '@type' => 'Organization',
                'name' => is_array($data['publisher']) ? ($data['publisher']['name'] ?? '') : $data['publisher'],
            ];
        }

        if (isset($data['category'])) {
            $schema['articleSection'] = is_array($data['category']) ? ($data['category']['name'] ?? '') : $data['category'];
        }

        if (isset($data['keywords'])) {
            $schema['keywords'] = is_array($data['keywords']) ? implode(', ', $data['keywords']) : $data['keywords'];
        }

        return $schema;
    }

    /**
     * Generate BlogPosting schema
     */
    public static function blogPosting(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $data['headline'] ?? $data['name'] ?? '',
            'image' => $data['image'] ?? '',
            'description' => $data['description'] ?? '',
            'url' => $data['url'] ?? '',
            'datePublished' => $data['date_published'] ?? date('c'),
            'dateModified' => $data['date_modified'] ?? date('c'),
        ];

        if (isset($data['author'])) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => is_array($data['author']) ? ($data['author']['name'] ?? '') : $data['author'],
            ];
        }

        if (isset($data['publisher'])) {
            $schema['publisher'] = [
                '@type' => 'Organization',
                'name' => is_array($data['publisher']) ? ($data['publisher']['name'] ?? '') : $data['publisher'],
            ];
        }

        return $schema;
    }

    /**
     * Generate FAQPage schema
     */
    public static function faqPage(array $data): array
    {
        $faqItems = [];
        foreach ($data['faqs'] ?? [] as $faq) {
            $faqItems[] = [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'] ?? '',
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqItems,
        ];
    }

    /**
     * Generate Review/Rating schema
     */
    public static function review(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Review',
            'itemReviewed' => [
                '@type' => $data['item_type'] ?? 'Article',
                'name' => $data['item_name'] ?? '',
                'url' => $data['item_url'] ?? '',
            ],
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => $data['rating'] ?? 5,
                'bestRating' => $data['best_rating'] ?? 5,
                'worstRating' => $data['worst_rating'] ?? 1,
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $data['author_name'] ?? 'Anonymous',
            ],
        ];

        if (isset($data['review_body'])) {
            $schema['reviewBody'] = $data['review_body'];
        }

        if (isset($data['date_published'])) {
            $schema['datePublished'] = $data['date_published'];
        }

        return $schema;
    }

    /**
     * Generate AggregateRating schema
     */
    public static function aggregateRating(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'AggregateRating',
            'ratingValue' => $data['rating_value'] ?? 0,
            'bestRating' => $data['best_rating'] ?? 5,
            'worstRating' => $data['worst_rating'] ?? 1,
            'ratingCount' => $data['rating_count'] ?? 0,
            'reviewCount' => $data['review_count'] ?? 0,
        ];
    }

    /**
     * Generate SoftwareApplication schema
     */
    public static function softwareApplication(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $data['name'] ?? '',
            'operatingSystem' => $data['operating_system'] ?? 'Android',
            'applicationCategory' => $data['category'] ?? 'GameApplication',
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['rating_value'] ?? '4.8',
                'ratingCount' => $data['rating_count'] ?? '1200',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $data['price'] ?? '0.00',
                'priceCurrency' => $data['currency'] ?? 'USD',
            ],
        ];

        if (isset($data['description'])) {
            $schema['description'] = $data['description'];
        }

        if (isset($data['image'])) {
            $schema['image'] = $data['image'];
        }

        if (isset($data['url'])) {
            $schema['url'] = $data['url'];
        }

        if (isset($data['download_url'])) {
            $schema['downloadUrl'] = $data['download_url'];
        }

        return $schema;
    }

    /**
     * Generate VideoGame schema
     */
    public static function videoGame(array $data): array
    {
        $schema = self::softwareApplication($data);
        $schema['@type'] = 'VideoGame';
        $schema['gamePlatform'] = $data['platform'] ?? 'Android';
        
        return $schema;
    }

    /**
     * Generate Person schema
     */
    public static function person(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $data['name'] ?? '',
        ];

        if (isset($data['url'])) {
            $schema['url'] = $data['url'];
        }

        if (isset($data['image'])) {
            $schema['image'] = $data['image'];
        }

        if (isset($data['description'])) {
            $schema['description'] = $data['description'];
        }

        if (isset($data['same_as']) && is_array($data['same_as'])) {
            $schema['sameAs'] = $data['same_as'];
        }

        if (isset($data['job_title'])) {
            $schema['jobTitle'] = $data['job_title'];
        }

        return $schema;
    }

    /**
     * Generate LocalBusiness schema (for Local SEO)
     */
    public static function localBusiness(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $data['name'] ?? '',
            'url' => $data['url'] ?? url('/'),
        ];

        if (isset($data['address'])) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $data['address']['street'] ?? '',
                'addressLocality' => $data['address']['city'] ?? '',
                'addressRegion' => $data['address']['region'] ?? '',
                'postalCode' => $data['address']['postal_code'] ?? '',
                'addressCountry' => $data['address']['country'] ?? '',
            ];
        }

        if (isset($data['phone'])) {
            $schema['telephone'] = $data['phone'];
        }

        if (isset($data['price_range'])) {
            $schema['priceRange'] = $data['price_range'];
        }

        return $schema;
    }

    /**
     * Generate VideoObject schema (for video content)
     */
    public static function videoObject(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'thumbnailUrl' => $data['thumbnail_url'] ?? '',
            'uploadDate' => $data['upload_date'] ?? date('c'),
            'duration' => $data['duration'] ?? null, // ISO 8601 duration format (PT1H30M)
        ];

        if (isset($data['content_url'])) {
            $schema['contentUrl'] = $data['content_url'];
        }

        if (isset($data['embed_url'])) {
            $schema['embedUrl'] = $data['embed_url'];
        }

        if (isset($data['publisher'])) {
            $schema['publisher'] = [
                '@type' => 'Organization',
                'name' => is_array($data['publisher']) ? ($data['publisher']['name'] ?? '') : $data['publisher'],
            ];
        }

        if (isset($data['interaction_statistic'])) {
            $schema['interactionStatistic'] = $data['interaction_statistic'];
        }

        return $schema;
    }

    /**
     * Generate HowTo schema (for step-by-step guides)
     */
    public static function howTo(array $data): array
    {
        $steps = [];
        foreach ($data['steps'] ?? [] as $index => $step) {
            $steps[] = [
                '@type' => 'HowToStep',
                'position' => $index + 1,
                'name' => $step['name'] ?? '',
                'text' => $step['text'] ?? '',
                'url' => $step['url'] ?? null,
                'image' => $step['image'] ?? null,
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'step' => $steps,
        ];

        if (isset($data['total_time'])) {
            $schema['totalTime'] = $data['total_time']; // ISO 8601 duration format
        }

        if (isset($data['estimated_cost'])) {
            $schema['estimatedCost'] = [
                '@type' => 'MonetaryAmount',
                'currency' => $data['estimated_cost']['currency'] ?? 'USD',
                'value' => $data['estimated_cost']['value'] ?? 0,
            ];
        }

        if (isset($data['image'])) {
            $schema['image'] = $data['image'];
        }

        return $schema;
    }

    /**
     * Generate TVSeries schema
     */
    public static function tvSeries(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'TVSeries',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'url' => $data['url'] ?? '',
            'image' => $data['image'] ?? '',
        ];

        if (isset($data['start_date'])) {
            $schema['startDate'] = $data['start_date'];
        }

        if (isset($data['end_date'])) {
            $schema['endDate'] = $data['end_date'];
        }

        if (isset($data['number_of_seasons'])) {
            $schema['numberOfSeasons'] = $data['number_of_seasons'];
        }

        if (isset($data['number_of_episodes'])) {
            $schema['numberOfEpisodes'] = $data['number_of_episodes'];
        }

        if (isset($data['director'])) {
            $schema['director'] = [
                '@type' => 'Person',
                'name' => is_array($data['director']) ? ($data['director']['name'] ?? '') : $data['director'],
            ];
        }

        if (isset($data['actor']) && is_array($data['actor'])) {
            $schema['actor'] = array_map(function($actor) {
                return [
                    '@type' => 'Person',
                    'name' => is_array($actor) ? ($actor['name'] ?? '') : $actor,
                ];
            }, $data['actor']);
        }

        if (isset($data['genre'])) {
            $schema['genre'] = $data['genre'];
        }

        if (isset($data['aggregate_rating'])) {
            $schema['aggregateRating'] = $data['aggregate_rating'];
        }

        if (isset($data['trailer'])) {
            $schema['trailer'] = $data['trailer'];
        }

        return $schema;
    }

    /**
     * Generate Movie schema
     */
    public static function movie(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Movie',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'url' => $data['url'] ?? '',
            'image' => $data['image'] ?? '',
        ];

        if (isset($data['date_published'])) {
            $schema['datePublished'] = $data['date_published'];
        }

        if (isset($data['director'])) {
            $schema['director'] = [
                '@type' => 'Person',
                'name' => is_array($data['director']) ? ($data['director']['name'] ?? '') : $data['director'],
            ];
        }

        if (isset($data['actor']) && is_array($data['actor'])) {
            $schema['actor'] = array_map(function($actor) {
                return [
                    '@type' => 'Person',
                    'name' => is_array($actor) ? ($actor['name'] ?? '') : $actor,
                ];
            }, $data['actor']);
        }

        if (isset($data['duration'])) {
            $schema['duration'] = $data['duration']; // ISO 8601 duration
        }

        if (isset($data['genre'])) {
            $schema['genre'] = $data['genre'];
        }

        if (isset($data['aggregate_rating'])) {
            $schema['aggregateRating'] = $data['aggregate_rating'];
        }

        if (isset($data['trailer'])) {
            $schema['trailer'] = $data['trailer'];
        }

        return $schema;
    }

    /**
     * Generate Course schema (for educational content)
     */
    public static function course(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'provider' => [
                '@type' => 'Organization',
                'name' => $data['provider']['name'] ?? 'Nazaara Circle',
                'url' => $data['provider']['url'] ?? url('/'),
            ],
        ];

        if (isset($data['course_code'])) {
            $schema['courseCode'] = $data['course_code'];
        }

        if (isset($data['educational_level'])) {
            $schema['educationalLevel'] = $data['educational_level'];
        }

        if (isset($data['time_required'])) {
            $schema['timeRequired'] = $data['time_required'];
        }

        return $schema;
    }
}

