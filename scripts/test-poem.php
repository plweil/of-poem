<?php

require __DIR__ . '../vendor/autoload.php';
require __DIR__ . '../app/Bootstrap.php'; // adjust path if needed

use App\Repositories\PoemRepository;

// adjust paths if needed
$poemRepository = new PoemRepository(
    __DIR__ . '/data/poems.index.json',
    __DIR__ . '/poems'
);

$poem = $poemRepository->findBySlug('merlin'); // pick a slug from your index
var_dump($poem);
exit;
