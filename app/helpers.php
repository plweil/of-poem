<?php
function current_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
}


/**
 * @param string $text
 * @return string
 *
 * Provides special formatting for poems that require it
 */
function renderPoemText(string $text): string
{
    $lines = preg_split('/\R/', $text);

    $renderedLines = array_map(function ($line) {
        // Match markers like:
        // {indent-25}text
        // {indent-40}text
        // {indent-60}text
        if (preg_match('/^\{indent-(10|15|25|40|60)\}(.*)$/', $line, $matches)) {
            $indent = $matches[1];
            $content = $matches[2];

            return '<span class="indent-' . $indent . '">' .
                htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
                '</span>';
        }

        return htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }, $lines);

    return implode("\n", $renderedLines);
}
