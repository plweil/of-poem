<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $path = BASE_PATH . '/app/Views/' . $view . '.php';

        if (!file_exists($path)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);

        require $path;
    }
}
