<?php
declare(strict_types=1);

$indexFile = __DIR__ . '/poem-index.json';

if (!file_exists($indexFile)) {
    die('Poem index not generated yet.');
}

$poems = json_decode(file_get_contents($indexFile), true);

// Sorting (UI only)
$sortKey = $_GET['sort'] ?? 'id';
$sortDir = $_GET['dir'] ?? 'asc';

usort($poems, function ($a, $b) use ($sortKey, $sortDir) {
    $aVal = strtolower((string)($a[$sortKey] ?? ''));
    $bVal = strtolower((string)($b[$sortKey] ?? ''));

    if ($sortKey === 'id') {
        $aVal = (int)$aVal;
        $bVal = (int)$bVal;
    }

    $result = $aVal <=> $bVal;
    return $sortDir === 'desc' ? -$result : $result;
});

function sortLink($key, $label, $currentKey, $currentDir) {
    $dir = ($key === $currentKey && $currentDir === 'asc') ? 'desc' : 'asc';
    $arrow = ($key === $currentKey)
        ? ($currentDir === 'asc' ? ' ▲' : ' ▼')
        : '';
    return "<a href=\"?sort=$key&dir=$dir\">" .
        htmlspecialchars($label) . "$arrow</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Poem Index</title>
    <style>
        body { font-family: sans-serif; padding: 1em; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 0.5em; text-align: left; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        th a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

<h1>Poem Index</h1>

<table>
    <tr>
        <th><?= sortLink('id', 'ID', $sortKey, $sortDir) ?></th>
        <th><?= sortLink('title', 'Title', $sortKey, $sortDir) ?></th>
        <th><?= sortLink('author', 'Author', $sortKey, $sortDir) ?></th>
        <th><?= sortLink('notes', 'Notes', $sortKey, $sortDir) ?></th>
        <th><?= sortLink('slug', 'Slug', $sortKey, $sortDir) ?></th>
        <th><?= sortLink('featured', 'Featured', $sortKey, $sortDir) ?></th>
    </tr>

    <?php foreach ($poems as $poem): ?>
        <tr>
            <td><?= htmlspecialchars((string)$poem['id']) ?></td>
            <td><?= htmlspecialchars($poem['title']) ?></td>
            <td><?= htmlspecialchars($poem['author']) ?></td>
            <td><?= htmlspecialchars($poem['notes']) ?></td>
            <td><?= htmlspecialchars($poem['slug']) ?></td>
            <td><?= $poem['featured'] ? '✓' : '' ?></td>
        </tr>
    <?php endforeach; ?>

</table>
</body>
</html>
