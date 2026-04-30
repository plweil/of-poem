<header class="site-header">
  <div class="header-inner">
    <?php if (empty($is_subpage)): ?>
    <h1 class="site-title">
      <a href="/">Of Poem</a>
    </h1>
    <?php else: ?>
    <div class="site-title">
      <a href="/">Of Poem</a>
    </div>
    <?php endif; ?>

    <p class="site-tagline">
      An anthology
    </p>
    <?php require BASE_PATH . '/app/Views/partials/nav.php'; ?>
  </div>
</header>
