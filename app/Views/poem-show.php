<?php
/**
 * @var array{
 *   slug: string,
 *   title: string,
 *   author: array{first_name: string, last_name: string},
 *   notes?: string,
 *   featured?: bool
 * } $poem
 *
 * @var string $body
 */
$is_subpage = true;
$pageTitle = $poem['title'] ?? 'Untitled';

require BASE_PATH . '/app/Views/partials/head.php';
require BASE_PATH . '/app/Views/partials/header.php';
?>

  <main class="content">
<!--    <p class="back-link">-->
<!--      <a href="/poems">← Back to poems</a>-->
<!--    </p>-->
    <article class="poem">

      <h1 class="poem-title">
        <?= htmlspecialchars(trim($poem['title'] ?? '') !== '' ? $poem['title'] : 'Untitled') ?>
      </h1>

      <p class="poem-author">
        <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?>
      </p>

      <?php if (trim($poem['notes'] ?? '') !== ''): ?>
        <div class="poem-notes">
          <?= nl2br(htmlspecialchars($poem['notes'])) ?>
        </div>
      <?php endif; ?>

      <div class="poem-body"><pre><?= htmlspecialchars($body !== '' ? $body : 'Poem text not available.') ?></pre></div>
      <p class="back-link">
        <a href="/poems">← Back to poems</a>
      </p>
    </article>


<!--    <nav class="poem-nav">-->
<!--      --><?php //if ($prev): ?>
<!--        <a class="prev" href="/poems/--><?php //= htmlspecialchars($prev['slug']) ?><!--">-->
<!--          ← --><?php //= htmlspecialchars($prev['title'] ?: 'Untitled') ?>
<!--        </a>-->
<!--      --><?php //endif; ?>
<!---->
<!--      --><?php //if ($next): ?>
<!--        <a class="next" href="/poems/--><?php //= htmlspecialchars($next['slug']) ?><!--">-->
<!--          --><?php //= htmlspecialchars($next['title'] ?: 'Untitled') ?><!-- →-->
<!--        </a>-->
<!--      --><?php //endif; ?>
<!--    </nav>-->
  </main>

<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
<!---->
<!--</body>-->
