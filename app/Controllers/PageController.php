<?php

namespace App\Controllers;

use App\Core\View;

class PageController
{
    public function about(): void
    {
        View::render('about');
    }
}
