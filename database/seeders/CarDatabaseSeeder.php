<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarBrand;
use App\Models\CarModel;

class CarDatabaseSeeder extends Seeder {
    public function run(): void {
        $json = file_get_contents(database_path('data/car_models_full.json'));
        $data = json_decode($json, true);

        foreach ($data as $entry) {
            if (!isset($entry['brand'])) continue;

            $brand = CarBrand::firstOrCreate(['name' => $entry['brand']]);

            if (isset($entry['models'])) {
                foreach ($entry['models'] as $model) {
                    CarModel::firstOrCreate([
                        'brand_id' => $brand->id,
                        'title'    => $model['title']
                    ]);
                }
            }
        }
    }
}
