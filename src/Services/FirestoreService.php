<?php

declare(strict_types=1);

namespace App\Services;

use App\Cache\FileCache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FirestoreService
{
    private Client $http;
    private string $baseUrl;

    public function __construct(
        private string $projectId,
        private string $apiKey,
        private FileCache $cache,
    ) {
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents";
        $this->http = new Client(['timeout' => 10]);
    }

    public function getProject(string $id): ?array
    {
        return $this->getDocument('publicProjects', $id);
    }

    public function getProfile(string $userId): ?array
    {
        return $this->getDocument('publicProfiles', $userId);
    }

    private function getDocument(string $collection, string $docId): ?array
    {
        $cacheKey = "{$collection}_{$docId}";

        $stale = $this->cache->getStale($cacheKey);
        if ($stale !== null && $stale['fresh']) {
            return $stale['data'];
        }

        $fresh = $this->fetchFromApi($collection, $docId);

        if ($fresh !== null) {
            $this->cache->set($cacheKey, $fresh, 300);
            return $fresh;
        }

        // API failed but we have stale data
        if ($stale !== null) {
            return $stale['data'];
        }

        return null;
    }

    private function fetchFromApi(string $collection, string $docId): ?array
    {
        try {
            $url = "{$this->baseUrl}/{$collection}/{$docId}";
            $params = [];
            if ($this->apiKey !== '') {
                $params['query'] = ['key' => $this->apiKey];
            }

            $res = $this->http->get($url, $params);
            $body = json_decode($res->getBody()->getContents(), true);

            if (!isset($body['fields'])) {
                return null;
            }

            $data = $this->parseFields($body['fields']);
            $data['id'] = $docId;

            return $data;
        } catch (GuzzleException) {
            return null;
        }
    }

    private function parseFields(array $fields): array
    {
        $result = [];
        foreach ($fields as $key => $value) {
            $result[$key] = $this->parseValue($value);
        }
        return $result;
    }

    private function parseValue(array $value): mixed
    {
        if (isset($value['stringValue'])) {
            return $value['stringValue'];
        }
        if (isset($value['integerValue'])) {
            return (int) $value['integerValue'];
        }
        if (isset($value['doubleValue'])) {
            return (float) $value['doubleValue'];
        }
        if (isset($value['booleanValue'])) {
            return (bool) $value['booleanValue'];
        }
        if (isset($value['nullValue'])) {
            return null;
        }
        if (isset($value['arrayValue'])) {
            $values = $value['arrayValue']['values'] ?? [];
            return array_map(fn($v) => $this->parseValue($v), $values);
        }
        if (isset($value['mapValue'])) {
            return $this->parseFields($value['mapValue']['fields'] ?? []);
        }
        if (isset($value['timestampValue'])) {
            return $value['timestampValue'];
        }

        return null;
    }
}
