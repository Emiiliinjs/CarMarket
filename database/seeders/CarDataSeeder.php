<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CarDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->loadCarData();

        if ($data->isEmpty()) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        CarModel::truncate();
        CarBrand::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($data as $brand => $models) {
            $brandModel = CarBrand::create(['name' => $brand]);

            if (empty($models)) {
                continue;
            }

            $brandModel->models()->createMany(
                collect($models)
                    ->map(fn (string $name) => ['name' => $name])
                    ->all()
            );
        }
    }

    /**
     * @return Collection<string, array<int, string>>
     */
    protected function loadCarData(): Collection
    {
        $path = base_path('car_models_full.json');

        if (! File::exists($path)) {
            return collect();
        }

        $raw = json_decode(File::get($path), true);

        if (! is_array($raw)) {
            return collect();
        }

        return collect($raw)
            ->filter(fn ($item) => filled($item['brand'] ?? null))
            ->map(function ($item) {
                $brand = trim($item['brand']);

                return [
                    'brand' => $brand,
                    'models' => collect($item['models'] ?? [])
                        ->pluck('title')
                        ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                        ->filter(fn ($value) => filled($value))
                        ->unique(fn ($value) => mb_strtolower($value))
                        ->sort(fn ($a, $b) => strnatcasecmp($a, $b))
                        ->values()
                        ->all(),
                ];
            })
            ->filter(fn ($item) => filled($item['brand']))
            ->sortBy('brand', SORT_NATURAL | SORT_FLAG_CASE)
            ->mapWithKeys(fn ($item) => [$item['brand'] => $item['models']]);
    }
}
