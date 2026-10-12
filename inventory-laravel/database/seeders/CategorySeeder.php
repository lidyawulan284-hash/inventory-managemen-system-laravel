<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik & Gadget',
                'description' => 'Perangkat elektronik, laptop, monitor, dan perlengkapan audio.',
            ],
            [
                'name' => 'Aksesoris Komputer',
                'description' => 'Keyboard mechanical, mouse optic, headset, dan mousepad.',
            ],
            [
                'name' => 'Alat Tulis Kantor (ATK)',
                'description' => 'Kertas HVS, bolpoin, binder, kalkulator, dan perlengkapan arsip.',
            ],
            [
                'name' => 'Peralatan Jaringan',
                'description' => 'Router WiFi, Switch Gigabit, Kabel UTP Cat6, dan Patch Cord.',
            ],
            [
                'name' => 'Perabot Kantor',
                'description' => 'Kursi ergonomis, meja kerja, laci kabinet, dan rak penyimpanan.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
