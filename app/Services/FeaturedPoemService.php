<?php

namespace App\Services;

use App\Repositories\PoemRepository;
use Throwable;

class FeaturedPoemService
{
    public function __construct(
        private PoemRepository $poems,
        private string $historyFile
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
        if (!is_file($this->historyFile)) {
            return null;
        }

        $raw = file_get_contents($this->historyFile);
        $data = json_decode($raw, true);

        if (empty($data['history']) || !is_array($data['history'])) {
            return null;
        }

        // Sort newest to oldest just in case
        usort($data['history'], fn($a, $b) => strcmp($b['date'], $a['date']));

        foreach ($data['history'] as $entry) {
            if (!isset($entry['slug'], $entry['date'])) {
                continue;
            }

            if ($entry['date'] <= $today) {
                return $this->poems->findBySlug($entry['slug']);
            }
        }

        return null;
    }
}
