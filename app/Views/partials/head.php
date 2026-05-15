<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars(!empty($pageTitle)
        ? "$pageTitle | Of Poem: James L. Weil"
        : "Of Poem: James L. Weil") ?>
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= BASE_URL ?>/css/site.css?v=2">

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-9TX3Y6BWEV"></script>
  <script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-9TX3Y6BWEV', {
	    anonymize_ip: true
    });
  </script>
</head>
<body>
