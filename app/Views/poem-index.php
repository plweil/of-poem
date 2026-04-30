<?php
/** @var array<array{author: array, poems: array}> $authors */
$pageTitle = 'Poems';
$is_subpage = true;
require BASE_PATH . '/app/Views/partials/head.php';
require BASE_PATH . '/app/Views/partials/header.php';
?>

  <main class="content">
    <div class="index-inner">

      <h1 class="index-title">Poems</h1>

      <?php foreach ($authors as $group): ?>
        <section class="author-group">
          <h2 class="author-name">
            <?= htmlspecialchars($group['author']['first_name'] . ' ' . $group['author']['last_name']) ?>
          </h2>

          <ul class="poem-list">
            <?php foreach ($group['poems'] as $poem): ?>
              <li>
                <a href="/poems/<?= htmlspecialchars($poem['slug']) ?>">
                  <?= htmlspecialchars(trim($poem['title'] ?? '') !== '' ? $poem['title'] : 'Untitled') ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endforeach; ?>

    </div>
  </main>

<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
