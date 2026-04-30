<?php
$pageTitle = 'Featured Poem';
require BASE_PATH . '/app/Views/partials/head.php';
require BASE_PATH . '/app/Views/partials/header.php';
?>

<?php /** @var TYPE_NAME $poem */
if ($poem): ?>
  <main class="content">
    <!-- Introductory text -->
    <div class="page-intro">
      <p>In 1965, poet and editor James L. Weil published <cite>Of Poem: An Anthology</cite>, a collection drawn from the pages of
        his magazine <cite>Elizabeth</cite>. Calling himself “an amateur collector of poems ‘of poem’,” he gathered what he described
        as “the prize pieces” of that six-year experiment.</p>

      <p>“The poems,” he wrote, “are loud”: they presume a listener, “often brashly, on the faith that someone is
        there.” Against the impersonal voice of the “poem addressed to The Editor,” these works insist on presence—on
        poem as a direct human act.</p>

      <p>This site presents a new <cite>Of Poem</cite>: a selection drawn from the original anthology and from the later work of James L. Weil, as both poet and publisher. It includes poems by writers he published and admired—among them William Bronk and Lorine Niedecker—alongside a range of his own work across different periods.</p>

      <p>The selection is necessarily partial, shaped by preference as much as by design. A featured poem appears here
        and changes daily.</p>

    </div>

<!--    <section class="section-break"></section>-->
    <article class="poem">
      <div class="poem-inner">
        <h2><?= htmlspecialchars($poem['title']) ?></h2>

        <p class="poem-author">
          <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?>
        </p>

        <pre class="poem-body">
<?= htmlspecialchars($poem['body']) ?>
    </pre>
      </div>
    </article>
  </main>


<?php else: ?>
  <p>No featured poem for today.</p>
<?php endif; ?>

<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
