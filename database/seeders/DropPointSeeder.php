<?php

namespace Database\Seeders;

use App\Models\DropPoint;
use Illuminate\Database\Seeder;

class DropPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dropPoints = [
            [
                'name' => 'Drop Point EcoPoints Pusat',
                'address' => 'Jl. Jenderal Sudirman No. 10, RT 01 / RW 02, Gelora, Tanah Abang, Jakarta Pusat',
                'latitude' => -6.21462000,
                'longitude' => 106.82084000,
                'is_active' => true,
            ],
            [
                'name' => 'Drop Point EcoPoints Jakarta Selatan',
                'address' => 'Jl. RS. Fatmawati Raya No. 45, Cilandak Barat, Jakarta Selatan',
                'latitude' => -6.29175000,
                'longitude' => 106.79720000,
                'is_active' => true,
            ],
            [
                'name' => 'Drop Point EcoPoints Jakarta Barat',
                'address' => 'Jl. Panjang No. 18, Kebon Jeruk, Jakarta Barat',
                'latitude' => -6.18950000,
                'longitude' => 106.76810000,
                'is_active' => true,
            ],
            [
                'name' => 'Drop Point EcoPoints Jakarta Timur',
                'address' => 'Jl. Pemuda No. 22, Rawamangun, Pulo Gadung, Jakarta Timur',
                'latitude' => -6.19420000,
                'longitude' => 106.88330000,
                'is_active' => true,
            ],
        ];

        foreach ($dropPoints as $point) {
            DropPoint::updateOrCreate(
                ['name' => $point['name']],
                $point
            );
        }
    }
}
