<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\PoemController;
use App\Repositories\PoemRepository;
use App\Services\FeaturedPoemService;
use App\Routing\Router;
use App\Core\Container;

class Bootstrap
{
    public function run(): void
    {
        $container = new Container();

        // Scalar config
        $container->params(PoemRepository::class, [
            'indexFile' => DATA_PATH . '/poems-index.json',
            'poemsPath' => BASE_PATH . '/poems',
        ]);

        $container->params(FeaturedPoemService::class, [
            'historyFile' => DATA_PATH . '/featured-history.json',
        ]);

        // Router (uses container for controller resolution)
        $router = new Router($container);

        // Routes
        $router->get('/', [HomeController::class, 'index']);
        $router->get('/poems', [PoemController::class, 'index']);
        $router->get('/poems/{slug}', [PoemController::class, 'show']);
        $router->get('/about', [\App\Controllers\PageController::class, 'about']);

        // Dispatch
        $router->dispatch(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']
        );
    }
}
