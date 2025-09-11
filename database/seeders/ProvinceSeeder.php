<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;
// use Kavist\RajaOngkir\Facades\RajaOngkir; // removed incompatible dependency
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Placeholder seed to allow app to run without RajaOngkir
        $provinces = [
            ['province' => 'DKI Jakarta', 'province_id' => 6],
            ['province' => 'Jawa Barat', 'province_id' => 9],
        ];

        foreach ($provinces as $province) {
            $provinceResult = Province::create(['name' => $province['province']]);

            $cities = [
                ['city_name' => 'Jakarta Selatan', 'type' => 'Kota', 'postal_code' => '12240'],
                ['city_name' => 'Bandung', 'type' => 'Kota', 'postal_code' => '40111'],
            ];
            foreach ($cities as $city) {
                City::create([
                    'province_id' => $provinceResult['id'],
                    'name' => $city['city_name'],
                    'type' => $city['type'],
                    'postal_code' => $city['postal_code']
                ]);
            }
        }
    }
}
