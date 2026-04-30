<?php
/** @var array $poem */
?>

<h1><?= htmlspecialchars($poem['title']) ?></h1>
<p class="author">
    <?= htmlspecialchars($poem['author']['first_name'] . ' ' . $poem['author']['last_name']) ?>
</p>

<div class="poem-body">
    <?= $poem['body'] ?>  <!-- Already safe HTML from MarkdownService -->
</div>
