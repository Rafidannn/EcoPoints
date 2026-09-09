<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wasteTypes = [
            [
                'name' => 'Plastik (Botol / Ember / Kresek)',
                'unit_price_per_kg' => 3000.00,
                'points_per_kg' => 500,
                'description' => 'Botol PET bersih, gelas plastik, kantong kresek, dan ember bekas.',
                'is_active' => true,
            ],
            [
                'name' => 'Kertas & Karton',
                'unit_price_per_kg' => 2000.00,
                'points_per_kg' => 300,
                'description' => 'Kardus, kertas HVS bekas, koran, dan majalah dalam kondisi kering.',
                'is_active' => true,
            ],
            [
                'name' => 'Logam & Kaleng',
                'unit_price_per_kg' => 5000.00,
                'points_per_kg' => 700,
                'description' => 'Kaleng minuman, besi tua, aluminium, tembaga, dan seng.',
                'is_active' => true,
            ],
            [
                'name' => 'Kaca & Botol Beling',
                'unit_price_per_kg' => 1500.00,
                'points_per_kg' => 200,
                'description' => 'Botol sirup, toples kaca, dan pecahan kaca yang aman dikemas.',
                'is_active' => true,
            ],
            [
                'name' => 'Elektronik (E-Waste)',
                'unit_price_per_kg' => 10000.00,
                'points_per_kg' => 1500,
                'description' => 'Komponen komputer rusak, ponsel lama, kabel, dan charger bekas.',
                'is_active' => true,
            ],
            [
                'name' => 'Minyak Jelantah',
                'unit_price_per_kg' => 7000.00,
                'points_per_kg' => 1000,
                'description' => 'Minyak goreng bekas pakai rumah tangga yang telah disaring dan disimpan dalam botol.',
                'is_active' => true,
            ],
        ];

        foreach ($wasteTypes as $type) {
            WasteType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
