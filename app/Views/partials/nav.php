<?php
$path = current_path();
?>
<nav class="site-nav" aria-label="Main navigation">
    <ul role="list" class="site-nav-list">
        <li><a href="/" class="<?= $path === '/' ? 'active' : '' ?>">Home</a></li>
        <li><a href="/poems" class="<?= str_starts_with($path, '/poems') ? 'active' : '' ?>">Poems</a></li>
      <li><a href="/grolier/" class="<?= $path === '/grolier/' ? 'active' : ''?>">Exhibition</a></li>
<!--        <li><a href="/authors">Authors</a></li>-->
        <li><a href="/about" class="<?= $path === '/about' ? 'active' : '' ?>">About</a></li>
    </ul>
</nav>
