<?php

namespace App\Support;

use JsonException;
use Throwable;

class CarModelRepository
{
    private ?array $cache = null;

    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $rawData = $this->loadFromCardata() ?? $this->loadFromLegacyJson();

        return $this->cache = $this->normalizeRawData($rawData);
    }

    public function withBrand(?string $brand, ?string $model): array
    {
        return $this->ensureBrandModel($this->all(), $brand, $model);
    }

    public function toJson(?array $data = null): string
    {
        $payload = $data ?? $this->all();

        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $encoded === false ? '{}' : $encoded;
    }

    private function loadFromCardata(): ?array
    {
        $path = base_path('Cardata.json');

        if (! is_file($path)) {
            return null;
        }

        try {
            $contents = file_get_contents($path);
            $payload = json_decode($contents ?: '{}', true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            if (function_exists('report')) {
                report($exception);
            }

            return null;
        }

        $raw = $payload['content'] ?? null;

        if (! is_string($raw)) {
            return null;
        }

        return $this->parsePhpArray($raw);
    }

    private function parsePhpArray(string $code): ?array
    {
        $normalized = trim($code);

        if ($normalized === '') {
            return null;
        }

        $normalized = preg_replace('/^\$[A-Za-z_][A-Za-z0-9_]*\s*=\s*/', '', $normalized, 1);

        if (! is_string($normalized)) {
            return null;
        }

        $normalized = trim($normalized);

        if ($normalized === '') {
            return null;
        }

        if (str_contains($normalized, '<?') || str_contains($normalized, '?>')) {
            return null;
        }

        $normalized = rtrim($normalized, ";\n\r\t ");

        if ($normalized === '') {
            return null;
        }

        $expression = 'return ' . $normalized . ';';

        try {
            /** @var mixed $result */
            $result = eval($expression);
        } catch (Throwable $exception) {
            if (function_exists('report')) {
                report($exception);
            }

            return null;
        }

        return is_array($result) ? $result : null;
    }

    private function loadFromLegacyJson(): array
    {
        $path = database_path('data/car_models_full.json');

        if (! is_file($path)) {
            return [];
        }

        try {
            $contents = file_get_contents($path);
            $rawData = json_decode($contents ?: '[]', true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            if (function_exists('report')) {
                report($exception);
            }

            return [];
        }

        return is_array($rawData) ? $rawData : [];
    }

    private function normalizeRawData(?array $rawData): array
    {
        if (! is_array($rawData)) {
            return [];
        }

        $brands = [];

        foreach ($rawData as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $brand = $entry['brand'] ?? null;

            if (! is_string($brand)) {
                continue;
            }

            $brand = trim($brand);

            if ($brand === '') {
                continue;
            }

            $models = $this->normalizeModelList($entry['models'] ?? []);

            $brands[$brand] = $models;
        }

        ksort($brands, SORT_NATURAL | SORT_FLAG_CASE);

        return $brands;
    }

    private function normalizeModelList(mixed $models): array
    {
        if (! is_array($models)) {
            return [];
        }

        $normalized = [];

        foreach ($models as $model) {
            if (is_array($model)) {
                $model = $model['title'] ?? $model['name'] ?? null;
            }

            if (! is_string($model)) {
                continue;
            }

            $model = trim($model);

            if ($model === '') {
                continue;
            }

            $normalized[] = $model;
        }

        return collect($normalized)
            ->filter(fn ($value) => filled($value))
            ->unique(fn ($value) => mb_strtolower($value, 'UTF-8'))
            ->sort(fn ($a, $b) => strnatcasecmp($a, $b))
            ->values()
            ->all();
    }

    private function ensureBrandModel(array $data, ?string $brand, ?string $model): array
    {
        $brand = is_string($brand) ? trim($brand) : '';

        if ($brand === '') {
            return $data;
        }

        $models = collect($data[$brand] ?? [])
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => filled($value));

        if (filled($model)) {
            $models->push(trim($model));
        }

        $data[$brand] = $models
            ->unique(fn ($value) => mb_strtolower($value, 'UTF-8'))
            ->sort(fn ($a, $b) => strnatcasecmp($a, $b))
            ->values()
            ->all();

        ksort($data, SORT_NATURAL | SORT_FLAG_CASE);

        return $data;
    }
}
