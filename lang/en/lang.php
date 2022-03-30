<?php return [
    'plugin' => [
        'name' => 'SEO Pro',
        'description' => 'Plugin for SEO markup and generation Sitemap.xml and Robots.txt'
    ],

    'config' => [
        'description' => 'Configure sitemap.xml and robots.txt',
        'meta_title' => 'META title',
        'meta_description' => 'META description',
        'index' => 'Specify whether search engines should index this page',
        'follow' => 'Specify whether search engines should follow the links on this page',
        'use_in_sitemap' => 'Enable in the Sitemap.xml',
        'model_class' => [
            'label' => 'Associate this page with a model links will be generated from it\s records',
            'comment' => 'Author\Plugin\Models\modelClass, you can specify several separated by commas'
        ],
        'priority' => 'Page priority',
        'changefreq' => 'Page change frequency',
        'og_title' => 'By default, the value from the META title variable is used',
        'og_type' => 'The default value is "website"',
        'og_description' => 'By default, the value from the META description variable is used',
        'og_image' => 'By default, the value from the global settings is used',
        'og_ref_image' => 'Twig variable for og:image',
    ],

    'permissions' => [
        'labels' => [
            'sitemap' => 'Sitemap tab',
            'meta' => 'Meta and Open Graph Tab',
            'settings' => 'Plugin Settings',
        ]
    ],

    'settings' => [
        'enable_sitemap' => 'Use sitemap.xml',
        'use_canonical' => 'Use the Canonical tag',
        'stock_description' => [
            'label' => 'Universal Meta tag description',
            'comment' => 'Used if the description meta tag is not filled in for the page',
        ],
        'site_name' => [
            'label' => 'Name of the site/organization',
            'label_pos' => 'Display the site name',
            'prefix_pos' => 'At the start of the title',
            'suffix_pos' => 'At the end of the title',
            'nowhere_pos' => 'Disable',
            'comment_pos' => 'Choose how the site name should be displayed in the Title header',
            'separator' => 'Site name separator',
            'separator_comment' => 'A symbol to separate the site name from the title, for example: Page Title | SiteName',
        ],
        'enable_robots_txt' => 'Use robots.txt',
        'enable_robots_meta' => 'Use robots meta tags',
        'extra_meta' => 'Additional tags in <head>',
        'enable_og' => 'Use Open Graph',
    ],

    'component' => [
        'description' => 'Renders SEO meta tags in place',
    ]
];