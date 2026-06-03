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
$pageTitle =
    !empty($poem['title'])
        ? $poem['author']['first_name'] . ' ' . $poem['author']['last_name']. ' — '.$poem['title']
        : $poem['author']['first_name'] . ' ' . $poem['author']['last_name'] . ' — Untitled Poem';

require BASE_PATH . '/app/Views/partials/head.php';
require BASE_PATH . '/app/Views/partials/header.php';
?>

  <main class="content">
    <article class="poem <?= strtolower($poem['title'])?>">

      <?php if (!empty($poem['artwork']['src'])): ?>
        <figure class="poem-artwork">
          <img
              src="/images/artwork/<?= htmlspecialchars($poem['artwork']['src']) ?? '' ?>"
              alt="<?= htmlspecialchars($poem['artwork']['alt']) ?? '' ?>"
          >
        </figure>
      <?php endif; ?>
      <h1 class="poem-title">
        <?= formatTitle($poem['title']); ?>
        <span class="sr-only">By <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?></span>
      </h1>

      <div class="poem-body"><pre><?= ($body !== '' ? renderPoemText($body) : 'Poem text not available.') ?></pre></div>
      <p class="poem-author">
        <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?>
      </p>

      <?php if (trim($poem['notes'] ?? '') !== ''): ?>
        <div class="poem-notes">
          <?= nl2br(renderAllowedInlineHtml($poem['notes'])) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($mark)): ?>
        <figure class="poem-imprint-mark poem-imprint-mark--<?= htmlspecialchars($imprint) ?>">
          <img
              src="<?= htmlspecialchars($mark['src']) ?>"
              alt="<?= htmlspecialchars($mark['alt']) ?>"
          >
          <figcaption>
            <?= htmlspecialchars($mark['label']) ?>
          </figcaption>
        </figure>
      <?php endif; ?>
      <p class="back-link">
        <a href="/poems">← Back to poems</a>
      </p>
    </article>
  </main>

<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
