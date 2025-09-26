<?php

namespace App\Support;

use JsonException;

class CarModelRepository
{
    private ?array $cache = null;

    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $path = database_path('data/car_models_full.json');

        if (! is_file($path)) {
            return $this->cache = [];
        }

        try {
            $contents = file_get_contents($path);
            $rawData = json_decode($contents ?: '[]', true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            if (function_exists('report')) {
                report($exception);
            }

            return $this->cache = [];
        }

        if (! is_array($rawData)) {
            return $this->cache = [];
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

            $models = [];

            if (isset($entry['models']) && is_array($entry['models'])) {
                foreach ($entry['models'] as $model) {
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

                    $models[] = $model;
                }
            }

            $models = collect($models)
                ->filter(fn ($value) => filled($value))
                ->unique(fn ($value) => mb_strtolower($value, 'UTF-8'))
                ->sort(fn ($a, $b) => strnatcasecmp($a, $b))
                ->values()
                ->all();

            $brands[$brand] = $models;
        }

        ksort($brands, SORT_NATURAL | SORT_FLAG_CASE);

        return $this->cache = $brands;
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
