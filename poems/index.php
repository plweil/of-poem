<?php

declare(strict_types=1);
//echo 'POEM INDEX HIT'; exit;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/poem-loader.php';

use Symfony\Component\Yaml\Yaml;

$slug = $_GET['slug'] ?? '';

if ($slug === '') {
    http_response_code(404);
    echo 'Poem not found.';
    exit;
}

// Build path to Markdown file
$poemFile = __DIR__ . '/source/' . basename($slug) . '.txt';

if (!is_file($poemFile)) {
    http_response_code(404);
    echo "Poem file not found: " . htmlspecialchars($slug);
    exit;
}

// Load Markdown content (your existing logic)
$content = file_get_contents($poemFile);

$yaml = [];
$body = $content;

if (preg_match('/^---\s*(.*?)\s*---\s*(.*)$/s', $content, $matches)) {
    $yaml = Yaml::parse(trim($matches[1]));
    $body = trim($matches[2]);
}

$title  = $yaml['title'] ?? '';
$author = $yaml['author'] ?? ['first_name' => '', 'last_name' => ''];
$notes  = $yaml['notes'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title !== '' ? htmlspecialchars($title) : 'Poem' ?></title>
</head>
<body>

<article class="poem">

    <?php if ($title !== ''): ?>
        <h1><?= htmlspecialchars($title) ?></h1>
    <?php endif; ?>

    <p class="poem-author">
        <?= htmlspecialchars(trim($author['first_name'] . ' ' . $author['last_name'])) ?>
    </p>

<!--    <div class="poem-text">-->
<!--        --><?php //= nl2br(htmlspecialchars($body)) ?>
<!--    </div>-->

    <?php if ($notes !== ''): ?>
        <footer class="poem-notes">
            <?= htmlspecialchars($notes) ?>
        </footer>
    <?php endif; ?>

</article>

</body>
</html>
