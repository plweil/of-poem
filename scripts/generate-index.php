<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php'; // Symfony YAML
use Symfony\Component\Yaml\Yaml;

// -------------------- BASE PATH --------------------
$BASE_DIR = __DIR__;

// -------------------- CONFIG --------------------
$recentCount = 3; // number of past poems to avoid repeating
$historyFile = $BASE_DIR . '/featured-history.json';
$poemsDir = $BASE_DIR . '/poems';
$outputJson = $BASE_DIR . '/poem-index.json';

// -------------------- LOAD POEMS --------------------
$poems = [];

foreach (glob($poemsDir . '/*.txt') as $file) {
  $filename = basename($file, '.txt'); // slug
  $content = file_get_contents($file);

  if ($content === false) {
    continue; // fail safely
  }

  if (preg_match('/---(.*?)---/s', $content, $matches)) {
    $yaml = Yaml::parse(trim($matches[1]));

    $poems[] = [
        'id'       => $yaml['id'] ?? '',
        'slug'     => $filename,
        'title'    => $yaml['title'] ?? '',
        'author'   => $yaml['author'] ?? '',
        'notes'    => $yaml['notes'] ?? '',
        'featured' => false, // assigned below
    ];
  }
}

// -------------------- LOAD HISTORY --------------------
$history = ['history' => []];

if (file_exists($historyFile)) {
  $decoded = json_decode(file_get_contents($historyFile), true);
  if (is_array($decoded)) {
    $history = $decoded;
  }
}

$recentIds = array_map(
    fn($item) => $item['id'],
    $history['history'] ?? []
);

// -------------------- DETERMINE FEATURED --------------------
$eligiblePoems = array_filter(
    $poems,
    fn($p) => !in_array($p['id'], $recentIds, true)
);

if (count($eligiblePoems) === 0) {
  // All poems recently featured → reset
  $eligiblePoems = $poems;
}

$eligibleArray = array_values($eligiblePoems);

// Pick one randomly
$selectedPoem = $eligibleArray[random_int(0, count($eligibleArray) - 1)];

// Mark featured
foreach ($poems as &$p) {
  $p['featured'] = ($p['id'] === $selectedPoem['id']);
}
unset($p);

// -------------------- UPDATE HISTORY --------------------
$history['history'][] = [
    'id'   => $selectedPoem['id'],
    'date' => date('Y-m-d'),
];

if (count($history['history']) > $recentCount) {
  $history['history'] = array_slice($history['history'], -$recentCount);
}

file_put_contents(
    $historyFile,
    json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// -------------------- SORTING (browser-only) --------------------
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

// -------------------- WRITE JSON --------------------
file_put_contents(
    $outputJson,
    json_encode($poems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// -------------------- HTML INDEX (optional) --------------------
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
      th a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<h1>Poem Index</h1>
<p>
  Index generated on <?= date('Y-m-d H:i:s') ?>.<br>
  JSON file: <code><?= htmlspecialchars($outputJson) ?></code>
</p>

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
