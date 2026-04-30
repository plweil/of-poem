<?php
declare(strict_types=1);

// scripts/generate-poem-index.php

// --------------------------------------------------
// Configuration & paths
// --------------------------------------------------

define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');

$poemsDir   = BASE_PATH . '/poems/source';      // Path to poem .txt files
$outputFile = DATA_PATH . '/poems-index.json';  // Output JSON file

// --------------------------------------------------
// Curated poem metadata (slug => metadata)
// --------------------------------------------------

$POEM_METADATA = [
        'about-the-grass' => [
            'title' => 'About the Grass',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'autumn' => [
            'title' => 'Autumn',
            'author' => [
                'first_name' => 'Lorine',
                'last_name' => 'Niedecker'
            ],
            'notes' => ''
        ],
        'blue-chicory' => [
            'title' => 'Blue Chicory',
            'author' => [
                'first_name' => 'Lorine',
                'last_name' => 'Niedecker'
            ],
            'notes' => ''
        ],
        'corman-untitled' => [
            'title' => '',
            'author' => [
                'first_name' => 'Cid',
                'last_name' => 'Corman'
            ],
            'notes' => ''
        ],
        'dawnings' => [
            'title' => 'Dawnings',
            'author' => [
                'first_name' => 'William',
                'last_name' => 'Bronk'
            ],
            'notes' => ''
        ],
        'dear-bill' => [
            'title' => 'Dear Bill',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'endymion' => [
            'title' => 'Endymion',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => 'From \'Two poems to two prints\' (done by Jacques Hnizdovsky)'
        ],
        'english-violets' => [
            'title' => 'English Violets',
            'author' => [
                'first_name' => 'August',
                'last_name' => 'Derleth'
            ],
            'notes' => ''
        ],
        'enslin-unitiled' => [
            'title' => '',
            'author' => [
                'first_name' => 'Theodore',
                'last_name' => 'Enslin'
            ],
            'notes' => ''
        ],
        'for-christa-mcauliffe' => [
            'title' => 'For Christa McAuliffe And For The Rest Of Us',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'merlin' => [
            'title' => 'Merlin',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'paz-calls-for-a' => [
            'title' => 'Paz calls for a',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'self-portrait' => [
            'title' => 'Selfportrait',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => 'From \'Two poems to two prints\' (done by Jacques Hnizdovsky)'
        ],
        'that-shadow-this' => [
            'title' => 'That Shadow, this',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'the-appearance' => [
            'title' => 'The Appearance',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ],
        'your-father' => [
            'title' => 'Your Father',
            'author' => [
                'first_name' => 'James L.',
                'last_name' => 'Weil'
            ],
            'notes' => ''
        ]
];

// --------------------------------------------------
// Build index
// --------------------------------------------------

$poems     = [];
$seenSlugs = [];

// Iterate over poem files
foreach (glob($poemsDir . '/*.txt') as $filePath) {
    $slug = pathinfo($filePath, PATHINFO_FILENAME);

    // Slug validation
    if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
        fwrite(STDERR, "Invalid slug: {$slug}\n");
        exit(1);
    }

    // Metadata must exist
    if (!isset($POEM_METADATA[$slug])) {
        fwrite(STDERR, "Missing metadata for poem: {$slug}\n");
        exit(1);
    }

    if (isset($seenSlugs[$slug])) {
        fwrite(STDERR, "Duplicate slug detected: {$slug}\n");
        exit(1);
    }

    $meta = $POEM_METADATA[$slug];

    // Fail loudly if author data is malformed
    if (
        !isset($meta['author']['first_name']) ||
        !isset($meta['author']['last_name'])
    ) {
        fwrite(STDERR, "Invalid author format for poem: {$slug}\n");
        exit(1);
    }

    $poems[] = [
        'slug'     => $slug,
        'title'    => $meta['title'],
        'author'   => $meta['author'],
        'notes'    => $meta['notes'] ?? '',
        'featured' => (bool)($meta['featured'] ?? false),
    ];

    $seenSlugs[$slug] = true;
}

// --------------------------------------------------
// Reverse validation: metadata without files
// --------------------------------------------------

foreach (array_keys($POEM_METADATA) as $slug) {
    $path = "{$poemsDir}/{$slug}.txt";
    if (!is_file($path)) {
        fwrite(STDERR, "Metadata exists but poem file missing: {$slug}\n");
        exit(1);
    }
}

// --------------------------------------------------
// Deterministic ordering
// --------------------------------------------------

usort(
    $poems,
    fn ($a, $b) => strcmp($a['title'], $b['title'])
);

// --------------------------------------------------
// Write JSON output
// --------------------------------------------------

$output = [
    'generated_at' => gmdate('c'),
    'poems'        => $poems,
];

file_put_contents(
    $outputFile,
    json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n"
);

echo "Poems index generated successfully: {$outputFile}\n";
