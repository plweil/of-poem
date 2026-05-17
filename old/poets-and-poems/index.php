<?php
// Optional introductory text
$introText = ''; // Replace with actual HTML if desired

// Load poem index
$indexFile = __DIR__ . '/../poem-index.json';
$data = json_decode(file_get_contents($indexFile), true);
$poems = $data['poems'];

// --- Group poems by author ---
$byAuthor = [];
$authorLastNames = []; // For sorting authors by last name

foreach ($poems as $poem) {
  $first = $poem['author']['first_name'];
  $last  = $poem['author']['last_name'];
  $displayAuthor = trim($first . ' ' . $last);

  if (!isset($byAuthor[$displayAuthor])) {
    $byAuthor[$displayAuthor] = [];
    $authorLastNames[$displayAuthor] = $last; // store last name for sorting
  }

  $byAuthor[$displayAuthor][] = [
      'slug' => $poem['slug'],
      'display_title' => $poem['title'] !== '' ? $poem['title'] : 'Untitled'
  ];
}

// --- Handle multiple untitled poems per author ---
foreach ($byAuthor as &$poemList) {
  $untitledCount = [];
  foreach ($poemList as &$poem) {
    if ($poem['display_title'] === 'Untitled') {
      $untitledCount[] = &$poem;
    }
  }
  if (count($untitledCount) > 1) {
    $i = 1;
    foreach ($untitledCount as &$poem) {
      $poem['display_title'] = 'Untitled ' . $i++;
    }
  }
}
unset($poemList, $poem);

// --- Sort poems alphabetically within each author ---
foreach ($byAuthor as &$poemList) {
  usort($poemList, fn($a, $b) => strcmp($a['display_title'], $b['display_title']));
}
unset($poemList);

// --- Sort authors by last name ---
uksort($byAuthor, fn($a, $b) => strcmp($authorLastNames[$a], $authorLastNames[$b]));

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Poets & Poems</title>
  <link rel="stylesheet" type="text/css" href="../css/site.css">
</head>
<body>

<h1>Poets & Poems</h1>

<?php if (!empty($introText)): ?>
  <div class="page-intro">
    <?= $introText ?>
  </div>
<?php endif; ?>

<section class="poem-index">
  <ul role="list" class="poets">
  <?php foreach ($byAuthor as $author => $poemList): ?>
    <li class="poet">
      <details>
        <summary><?= htmlspecialchars($author) ?></summary>
    <ul class="poems">
      <?php foreach ($poemList as $poem): ?>
        <li>
        <a href="/poems/?slug=<?= urlencode($poem['slug']) ?>">
          <?= htmlspecialchars($poem['display_title']) ?>
        </a>
      </li>

      <?php endforeach; ?>
    </ul>
  </details>
    </li>

  <?php endforeach; ?>
  </ul>
</section>

</body>
</html>
