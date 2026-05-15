<?php

namespace App\Controllers;

use App\Services\FeaturedPoemService;
use App\Repositories\PoemRepository;

class HomeController
{

    public function __construct(
        private FeaturedPoemService $featuredPoems,
        private PoemRepository $poems
    )
    {
    }

    public function index(): void
    {
        // Get the featured poem metadata
        $poem = $this->featuredPoems->today();

        // If we found a poem, load its text
        if ($poem) {
            $poem['body'] = $this->poems->loadPoemBody($poem['slug']);
        }
        $pageTitle =null;
        require BASE_PATH . '/app/Views/home.php';
    }
}
