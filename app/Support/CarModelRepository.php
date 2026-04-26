<?php

namespace App\Support;

class CarModelRepository
{
    private array $data;

    public function __construct()
    {
        $raw = $this->loadRawData();
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

    private function loadRawData(): array
    {
        $paths = [
            base_path('Cardata.json'),
            database_path('data/car_models_full.json'),
        ];

        foreach ($paths as $path) {
            if (! file_exists($path)) {
                continue;
            }

            $contents = file_get_contents($path);
            $decoded = json_decode($contents, true);

            if (is_array($decoded)) {
                if (array_key_exists('content', $decoded) && is_string($decoded['content'])) {
                    $parsedFromContent = $this->parsePhpArrayDump($decoded['content']);
                    if (! empty($parsedFromContent)) {
                        return $parsedFromContent;
                    }
                }

                return $decoded;
            }
        }

        throw new \RuntimeException('Car models file not found or invalid.');
    }

    private function parsePhpArrayDump(string $content): array
    {
        preg_match_all(
            "/'brand'\\s*=>\\s*'([^']+)'(.*?)(?='brand'\\s*=>|\\)\\s*,\\s*\\z)/s",
            $content,
            $brandBlocks,
            PREG_SET_ORDER
        );

        $result = [];

        foreach ($brandBlocks as $block) {
            $brand = trim($block[1]);
            $modelsPart = $block[2] ?? '';
            preg_match_all("/'title'\\s*=>\\s*'([^']+)'/", $modelsPart, $modelMatches);
            $result[] = [
                'brand' => $brand,
                'models' => $modelMatches[1] ?? [],
            ];
        }

        return $result;
    }
}
