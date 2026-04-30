<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PoemRepository;
use App\Core\View;

class PoemController
{

    public function __construct(
        private PoemRepository $poemRepository,
    )
    {
    }

    public function index(): void
    {
        $poems = $this->poemRepository->all();

        $grouped = [];

        foreach ($poems as $poem) {
            $key = $poem['author']['last_name'] . '|' . $poem['author']['first_name'];

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'author' => $poem['author'],
                    'poems' => [],
                ];
            }

            $grouped[$key]['poems'][] = $poem;
        }

        // optional: sort authors
        usort($grouped, function ($a, $b) {
            return strcmp(
                $a['author']['last_name'],
                $b['author']['last_name']
            );
        });

        View::render('poem-index', [
            'authors' => $grouped,
        ]);
    }

    public function show(string $slug): void
    {
        $poem = $this->poemRepository->findBySlug($slug);

        if (!$poem) {
            http_response_code(404);
            echo "Poem not found";
            return;
        }

        $body = $this->poemRepository->loadPoemBody($slug);
        $next = $this->poemRepository->getNext($slug);
        $prev = $this->poemRepository->getPrevious($slug);

        View::render('poem-show', [
            'poem' => $poem,
            'body' => $body,
            'next' => $next,
            'prev' => $prev,
        ]);
    }

    private function render(string $view, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require BASE_PATH . '/app/Views/' . $view;
        return ob_get_clean();
    }
}
