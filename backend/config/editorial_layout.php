<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Page layout slot definitions
    |--------------------------------------------------------------------------
    |
    | Each slot can hold one published article. Empty slots fall back to
    | latest published content (site-wide or per hub category).
    |
    */

    'homepage' => [
        'hero' => [
            'label' => 'Lead story',
            'description' => 'Top of homepage — largest feature',
            'count' => 1,
            'fallback' => 'latest',
        ],
        'strategic_analysis' => [
            'label' => 'Strategic analysis',
            'description' => 'Two-up grid below the lead',
            'count' => 2,
            'fallback' => 'latest',
        ],
        'daily_brief' => [
            'label' => 'Daily brief sidebar',
            'description' => 'Right rail — one lead story per editorial desk (8 hubs, excludes Archive)',
            'count' => 8,
            'fallback' => 'hub_leads',
        ],
        'latest' => [
            'label' => 'More stories',
            'description' => 'Extended homepage feed — grows as you publish',
            'count' => 8,
            'fallback' => 'latest',
        ],
    ],

    'hub' => [
        'hero' => [
            'label' => 'Section lead',
            'description' => 'Featured story at top of hub page',
            'count' => 1,
            'fallback' => 'category_latest',
        ],
        'grid' => [
            'label' => 'Section grid',
            'description' => 'Main hub story grid',
            'count' => 6,
            'fallback' => 'category_latest',
        ],
        'latest' => [
            'label' => 'More in section',
            'description' => 'Extended hub feed',
            'count' => 6,
            'fallback' => 'category_latest',
        ],
    ],

    'hub_slugs' => [
        'politics',
        'economy',
        'foreign-affairs',
        'society',
        'technology',
        'diaspora',
        'opinion',
        'analysis',
        'archive',
    ],

];
