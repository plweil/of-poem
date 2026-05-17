<?php
function current_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
}

function formatTitle(string $title): string
{
    $formattedTitle = trim($title) ?: 'Untitled';
    $formattedTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

    return preg_replace_callback(
        '/\{\{class=([^:]+):(.*?)\}\}/',
        function ($matches) {
            $classes = preg_split('/\s+/', trim($matches[1]));

            $classes = array_map(
                fn($class) => 'title-' . $class,
                $classes
            );

            return sprintf(
                '<span class="%s">%s</span>',
                implode(' ', $classes),
                $matches[2]
            );
        },
        $formattedTitle
    );
}

function plainTitle(string $title): string
{
    $cleanTitle = trim($title);

    if ($cleanTitle === '') {
        $cleanTitle = 'Untitled';
    }

    $cleanTitle = preg_replace(
        '/\{\{class=[^:]+:(.*?)\}\}/',
        ' $1',
        $cleanTitle
    );

    return preg_replace('/\s+/', ' ', trim($cleanTitle));
}


/**
 * @param string $text
 * @return string
 *
 * Provides special formatting for poems that require it
 */
function renderPoemText(string $text): string
{
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    return preg_replace_callback(
        '/\{\{class=([^:]+):(.*?)}}/',
        function ($matches) {
            $classes = preg_split('/\s+/', trim($matches[1]));

            $classes = array_map(
                fn($class) => 'poem-' . trim($class),
                $classes
            );

            return sprintf(
                '<span class="%s">%s</span>',
                implode(' ', $classes),
                $matches[2]
            );
        },
        $text
    );
}
