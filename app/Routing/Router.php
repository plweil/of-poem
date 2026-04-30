<?php
declare(strict_types=1);

namespace App\Routing;

use RuntimeException;
use App\Core\Container;

class Router
{
    private Container $container;
    private array $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][] = [
            'pattern' => $this->compilePath($path),
            'params'  => $this->extractParams($path),
            'handler' => $handler,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                $params = [];

                foreach ($route['params'] as $name) {
                    $params[] = $matches[$name];
                }

                $this->invoke($route['handler'], $params);
                return;
            }
        }

        $this->notFound($path);
    }

    private function invoke(callable|array $handler, array $params): void
    {
        // Controller-style: [ClassName::class, 'method']
        if (is_array($handler)) {
            [$target, $method] = $handler;

            // If it's a class name, resolve via container
            if (is_string($target)) {
                $target = $this->container->get($target);
            }

            if (!method_exists($target, $method)) {
                throw new RuntimeException("Method {$method} not found on controller");
            }

            $target->$method(...$params);
            return;
        }

        // Closure or callable
        $handler(...$params);
    }

    private function compilePath(string $path): string
    {
        $pattern = preg_replace(
            '#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#',
            '(?P<$1>[^/]+)',
            $path
        );

        return '#^' . $pattern . '$#';
    }

    private function extractParams(string $path): array
    {
        preg_match_all(
            '#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#',
            $path,
            $matches
        );

        return $matches[1];
    }

    private function notFound(string $path): void
    {
        http_response_code(404);
        \App\Core\View::render('404');
    }
}
