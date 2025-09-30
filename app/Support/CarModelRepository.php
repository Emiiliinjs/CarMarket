<?php

namespace App\Support;

class CarModelRepository
{
    private array $data;

    public function __construct()
    {
        $path = database_path('data/car_models_full.json');

        if (! file_exists($path)) {
            throw new \RuntimeException("Car models file not found at {$path}");
        }

        $raw = json_decode(file_get_contents($path), true);

        if (! is_array($raw)) {
            throw new \RuntimeException("Car models JSON is invalid.");
        }

        $this->data = $this->normalizeRawData($raw);
    }

    /**
     * Atgriež visas markas un modeļus.
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Atgriež modeļus konkrētai markai.
     */
    public function getModelsForBrand(string $brand): array
    {
        return $this->data[$brand] ?? [];
    }

    /**
     * Atgriež JSON priekš frontend.
     */
    public function toJson(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Normalizē izejas datus no JSON faila.
     *
     * Piemēri:
     * [
     *   {"brand":"Audi","models":["A3","A4"]},
     *   {"brand":"BMW","models":[{"name":"320"},{"title":"X5"}]},
     *   "AC"
     * ]
     *
     * → ["Audi"=>["A3","A4"], "BMW"=>["320","X5"], "AC"=>[]]
     */
    private function normalizeRawData(array $rawData): array
    {
        $normalized = [];

        foreach ($rawData as $item) {
            // Ja objekts ar brand + models
            if (is_array($item) && isset($item['brand'])) {
                $brand = trim($item['brand']);
                $models = $item['models'] ?? [];
                $normalized[$brand] = $this->normalizeModelList($models);
            }
            // Ja tikai string (marka bez modeļiem)
            elseif (is_string($item)) {
                $normalized[trim($item)] = [];
            }
        }

        // Sakārto markas alfabētiski
        ksort($normalized, SORT_STRING | SORT_FLAG_CASE);

        return $normalized;
    }

    /**
     * Normalizē modeļu sarakstu (atbalsta stringus, objektus ar 'name' vai 'title').
     */
    private function normalizeModelList(array $models): array
    {
        $clean = [];

        foreach ($models as $model) {
            if (is_string($model)) {
                $clean[] = trim($model);
            } elseif (is_array($model) && isset($model['name'])) {
                $clean[] = trim($model['name']);
            } elseif (is_array($model) && isset($model['title'])) {
                $clean[] = trim($model['title']);
            }
        }

        // Izfiltrē tukšos un atgriež indeksētu masīvu
        return array_values(array_filter($clean));
    }
}
