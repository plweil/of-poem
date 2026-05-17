<?php
$pageTitle = 'Featured Poem';
$is_subpage = false;
require BASE_PATH . '/app/Views/partials/head.php';
require BASE_PATH . '/app/Views/partials/header.php';
?>

<?php /** @var TYPE_NAME $poem */
if ($poem): ?>
  <main class="content">
    <!-- Introductory text -->
    <div class="page-intro">
      <p>In 1965, poet and editor James L. Weil published <cite>Of Poem: An Anthology</cite>, a collection drawn from the pages of his magazine <cite>Elizabeth</cite>. Calling himself “an amateur collector of poems ‘of poem’,” he gathered what he described as “the prize pieces” of his six-year venture to revive “Modern Elizabethan and Metaphysical poetry” in America.</p>

      <p>“The poems,” he wrote, “are loud”: they presume a listener, “often brashly, on the faith that someone is
        there.” Against the impersonal voice of the “poem addressed to The Editor,” these works insist on presence—on
        poem as a direct human act.</p>

      <p>This site presents a new <cite>Of Poem</cite>: a selection drawn from both the original anthology and the later work of James Weil, as both poet and publisher. It will include works by some of his favorite writers and closest associates— <a href="https://lorineniedecker.org/">Lorine Niedecker</a>, <a href="https://www.poetryfoundation.org/poets/theodore-enslin">Theodore Enslin</a>, <a href="https://www.poetryfoundation.org/poets/cid-corman">Cid Corman</a>, <a href="https://simonperchikpoetry.com">Simon Perchik</a>,
        <a href="https://www.poetryfoundation.org/poets/william-bronk">William Bronk</a> and others—alongside a range of his own work across different periods.</p>

      <p>The selection is necessarily partial, shaped by preference as much as by design. A featured poem appears here
        and changes daily.</p>

    </div>

    <hr class="section-divider">
    <article class="poem">
      <div class="poem-inner">
        <h2 class="poem-title"><?= formatTitle($poem['title']) ?></h2>
        <pre class="poem-body"><?= renderPoemText($poem['body']) ?></pre>
        <p class="poem-author">
          <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?>
        </p>
      </div>
    </article>
  </main>


<?php else: ?>
  <p>No featured poem for today.</p>
<?php endif; ?>

<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
