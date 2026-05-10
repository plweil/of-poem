<header class="site-header">
  <div class="header-inner">

    <?php $tag = empty($is_subpage) ? 'h1' : 'div'; ?>
    <<?= $tag ?> class="site-title">
    <?php if (!empty($is_subpage)): ?>
      <a href="/">Of Poem</a>
    <?php else: ?>
      Of Poem
    <?php endif; ?>
    </<?= $tag ?>>


  <?php $tag = empty($is_subpage) ? 'h2' : 'div'; ?>

  <<?= $tag ?> class="site-tagline">
  James L. Weil and Echoes of <cite>Elizabeth</cite>
  </<?= $tag ?>>

<p class="site-tagline second">
  An anthology
</p>
<?php require BASE_PATH . '/app/Views/partials/nav.php'; ?>
</div>
</header>
