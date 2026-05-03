<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

function loadPoem(?string $slug = null): array
{
    $baseDir   = dirname(__DIR__); // adjust if needed
    $indexFile = $baseDir . '/data/poems-index.json';
    $poemDir   = $baseDir . '/poems/source';

    if (!is_file($indexFile)) {
        throw new RuntimeException('Poem index not found.');
    }

    $index = json_decode(file_get_contents($indexFile), true);
    if (!isset($index['poems'])) {
        throw new RuntimeException('Invalid poem index.');
    }

    // --------------------------------------------------
    // Resolve poem (featured or by slug)
    // --------------------------------------------------

    $poemMeta = null;

    if ($slug === null) {
        foreach ($index['poems'] as $poem) {
            if (!empty($poem['featured'])) {
                $poemMeta = $poem;
                break;
            }
        }
        if ($poemMeta === null) {
            throw new RuntimeException('No featured poem found.');
        }
    } else {
        foreach ($index['poems'] as $poem) {
            if ($poem['slug'] === $slug) {
                $poemMeta = $poem;
                break;
            }
        }
        if ($poemMeta === null) {
            throw new RuntimeException('Poem not found.');
        }
    }

    // --------------------------------------------------
    // Load poem source
    // --------------------------------------------------

    $poemFile = $poemDir . '/' . $poemMeta['slug'] . '.txt';

    if (!is_file($poemFile)) {
        throw new RuntimeException('Poem source file not found.');
    }

    $raw = file_get_contents($poemFile);

    // --------------------------------------------------
    // Parse YAML front matter
    // --------------------------------------------------
    
    $content = rtrim($raw);
    $yaml = []; // no front matter

    // --------------------------------------------------
    // Prepare poem body for display
    // --------------------------------------------------

    // Escape HTML, preserve line breaks
    $html = nl2br($content);

    // --------------------------------------------------
    // Return normalized poem
    // --------------------------------------------------
    $poem = [
        'slug'   => $poemMeta['slug'],
        'title'  => $poemMeta['slug'],
        'author' => [
            'first_name' => $yaml['author']['first_name'] ?? '',
            'last_name'  => $yaml['author']['last_name'] ?? '',
        ],
        'notes'  => $yaml['notes'] ?? '',
        'html'   => $html,
    ];

//    return [
//        'slug'   => $poemMeta['slug'],
//        'title'  => $poemMeta['slug'],
//        'author' => [
//            'first_name' => $yaml['author']['first_name'] ?? '',
//            'last_name'  => $yaml['author']['last_name'] ?? '',
//        ],
//        'notes'  => $yaml['notes'] ?? '',
//        'html'   => $html,
//    ];
    return $poem;
}
