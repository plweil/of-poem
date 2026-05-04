<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/header.php'; ?>

<main class="container">

  <article class="poem">
    <div class="poem-inner">
      <?php if (!empty(trim($poem['title'] ?? ''))): ?>
        <h2><?= htmlspecialchars($poem['title']) ?></h2>
      <?php endif; ?>
      <pre class="poem-body"><?= htmlspecialchars($poem['body']) ?></pre>
      <p class="poem-author">
        <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?>
      </p>
    </div>
  </article>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
