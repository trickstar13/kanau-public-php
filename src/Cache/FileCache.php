<?php

declare(strict_types=1);

namespace App\Cache;

class FileCache
{
    public function __construct(private string $cacheDir)
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function get(string $key, int $ttl = 300): mixed
    {
        $file = $this->path($key);
        if (!file_exists($file)) {
            return null;
        }

        $age = time() - filemtime($file);
        if ($age > $ttl) {
            return null;
        }

        $data = file_get_contents($file);
        return $data !== false ? json_decode($data, true) : null;
    }

    /**
     * Returns stale data with a freshness flag.
     * Stale data is kept for up to 24 hours as fallback.
     */
    public function getStale(string $key, int $freshTtl = 300): ?array
    {
        $file = $this->path($key);
        if (!file_exists($file)) {
            return null;
        }

        $age = time() - filemtime($file);
        if ($age > 86400) {
            unlink($file);
            return null;
        }

        $data = file_get_contents($file);
        if ($data === false) {
            return null;
        }

        return [
            'data' => json_decode($data, true),
            'fresh' => $age <= $freshTtl,
        ];
    }

    public function set(string $key, mixed $data, int $ttl = 300): void
    {
        $file = $this->path($key);
        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    private function path(string $key): string
    {
        return $this->cacheDir . '/' . md5($key) . '.json';
    }
}
