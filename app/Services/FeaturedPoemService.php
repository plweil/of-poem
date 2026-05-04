<?php

namespace App\Services;

use App\Repositories\PoemRepository;
use Throwable;

class FeaturedPoemService
{
    public function __construct(
        private PoemRepository $poems,
        private string         $historyFile
    )
    {

        if ($this->historyFile === '') {
            throw new \RuntimeException('FeaturedPoemService: historyFile is empty');
        }

        if (!is_file($this->historyFile)) {
            throw new \RuntimeException("Featured history file not found: {$this->historyFile}");
        }
    }

    /**
     * Return today's featured poem, or null if none found.
     */

    public function today(): ?array
    {
        $today = date('Y-m-d');

        $data = $this->loadHistory();

        // 1. If today already exists → return it
        foreach ($data['history'] as $entry) {
            if ($entry['date'] === $today) {
                return $this->poems->findBySlug($entry['slug']);
            }
        }

        // 2. Otherwise generate one
        $slug = $this->pickRandomSlug($data['history']);

        // 3. Append and persist
        $data['history'][] = [
            'date' => $today,
            'slug' => $slug,
        ];

        $this->saveHistory($data);

        return $this->poems->findBySlug($slug);
    }
//    public function today(): ?array
//    {
//
//        $today = date('Y-m-d');
//        if (!is_file($this->historyFile)) {
//            return null;
//        }
//
//        $raw = file_get_contents($this->historyFile);
//        $data = json_decode($raw, true);
//
//        if (empty($data['history']) || !is_array($data['history'])) {
//            return null;
//        }
//
//        // Sort newest to oldest just in case
//        usort($data['history'], fn($a, $b) => strcmp($b['date'], $a['date']));
//
//        foreach ($data['history'] as $entry) {
//            if (!isset($entry['slug'], $entry['date'])) {
//                continue;
//            }
//
//            if ($entry['date'] <= $today) {
//                return $this->poems->findBySlug($entry['slug']);
//            }
//        }
//
//        return null;
//    }


    private function loadHistory(): array
    {
        if (!is_file($this->historyFile)) {
            return ['history' => []];
        }

        return json_decode(file_get_contents($this->historyFile), true) ?? ['history' => []];
    }

    private function saveHistory(array $data): void
    {
        file_put_contents(
            $this->historyFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );

    }

    private function pickRandomSlug(array $history): string
    {
        $poems = $this->poems->all();

        $recent = array_slice(array_column($history, 'slug'), -5);

        $eligible = array_filter($poems, function ($poem) use ($recent) {
            return !in_array($poem['slug'], $recent, true);
        });

        // fallback if everything excluded
        if (empty($eligible)) {
            $eligible = $poems;
        }

        $choices = array_values($eligible);

        return $choices[array_rand($choices)]['slug'];
    }

}
