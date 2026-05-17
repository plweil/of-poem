<?php
namespace App\Repositories;
use RuntimeException;

class PoemRepository
{
    private array $index = [];
    private string $indexFile;
    private string $poemsPath;

    public function __construct(string $indexFile, string $poemsPath)
    {
        $this->indexFile = $indexFile;
        $this->poemsPath = rtrim($poemsPath, '/');

        $this->loadIndex();
    }

    public function all(): array
    {
        if (!is_file($this->indexFile)) {
            return [];
        }

        $json = file_get_contents($this->indexFile);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return [];
        }

        return $data['poems'] ?? [];
    }


    public function findBySlug(string $slug): ?array
    {
        foreach ($this->all() as $poem) {
            if ($poem['slug'] === $slug) {
                return $poem;
            }
        }

        return null;
    }

    /**
     * load the poem body and return it
     */
    public function loadPoemBody(string $slug): string
    {
        $poemFile = $this->poemSourcePath($slug);

        if (!is_file($poemFile)) {
            return '';
        }

        return trim(file_get_contents($poemFile));
    }

    /**
     * Resolve the filesystem path to a poem's source text.
     * Poem files contain body text only (no metadata).
     */
    private function poemSourcePath(string $slug): string
    {
        return $this->poemsPath . '/source/' . $slug . '.txt';
    }

    private function loadIndex(): void
    {
        if (!is_file($this->indexFile)) {
            throw new RuntimeException("Poem index not found: {$this->indexFile}");
        }

        $data = json_decode(file_get_contents($this->indexFile), true);

        if (!isset($data['poems']) || !is_array($data['poems'])) {
            throw new RuntimeException("Invalid poem index structure");
        }

        $this->index = $data['poems'];
    }

    public function getAuthors(): array
    {
        $authors = [];

        foreach ($this->all() as $poem) {
            $key = $poem['author']['last_name'] . ', ' . $poem['author']['first_name'];
            $authors[$key] = true;
        }

        $authors = array_keys($authors);
        sort($authors);

        return $authors;
    }

    public function getNext(string $slug): ?array
    {
        $poems = $this->all();

        foreach ($poems as $i => $poem) {
            if ($poem['slug'] === $slug) {
                return $poems[$i + 1] ?? null;
            }
        }

        return null;
    }

    public function getPrevious(string $slug): ?array
    {
        $poems = $this->all();

        foreach ($poems as $i => $poem) {
            if ($poem['slug'] === $slug) {
                return $poems[$i - 1] ?? null;
            }
        }

        return null;
    }


}
