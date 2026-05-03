<?php
// Load the featured poem via your PHP function
require_once __DIR__ . '/poems/poem-loader.php';
$featuredPoem = loadPoem(); // Returns array with 'slug', 'title', 'author', 'notes', 'html'
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>James L. Weil, Master of Poetry and Publishing</title>
  <meta
      name="description"
      content="Selected works of poetry from Elizabeth magazine, The Elizabeth Press and
    James L. Weil, Publisher"
  >

  <!-- Tailwind (CDN, no build step) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>body {font-family: 'EB Garamond', serif; background-color: #fdfcf9;}</style>

  <link rel="stylesheet" type="text/css" href="/css/site.css">
</head>

<body class="flex flex-col min-h-screen text-gray-800">

<main id="app" class="max-w-3xl mx-auto py-10 flex-grow max-w-prose px-4 text-lg leading-relaxed text-center">

  <!-- Header include -->
  <div data-include="/partials/header.html" data-title="James L. Weil, Master of Poetry and Publishing: Featured Poem"></div>

  <!-- Introductory text -->
  <section class="page-intro">
    <p>Welcome to the poetry of James L. Weil and the poets he published. Explore selected works and their creators.</p>
    <p>More content may be added over time as new materials become available.</p>
  </section>

  <!-- Featured poem component -->
  <featured-poem></featured-poem>

  <!-- Footer include -->
  <div data-include="/partials/footer.html"></div>

</main>

<!-- HTML includes -->
<script type="module" src="/js/includes.js"></script>

<!-- Inject the featured poem from PHP before Vue -->
<script>
	window.FEATURED_POEM = <?= json_encode($featuredPoem, JSON_UNESCAPED_UNICODE) ?>;
</script>

<!-- Load Vue globally -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<!-- Load featured poem component -->
<script src="/js/featured-poem.js"></script>

</body>
</html>
