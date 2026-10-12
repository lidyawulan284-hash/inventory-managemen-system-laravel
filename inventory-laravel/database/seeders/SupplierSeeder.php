<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Digital Solusindo',
                'email' => 'sales@digitalsolusindo.co.id',
                'phone' => '021-5551234',
                'address' => 'Jl. Jenderal Sudirman Kav. 45, Jakarta Selatan',
            ],
            [
                'name' => 'CV Prima Jaya Mandiri',
                'email' => 'kontak@primajaya.com',
                'phone' => '022-7778901',
                'address' => 'Jl. Asia Afrika No. 12, Bandung',
            ],
            [
                'name' => 'PT Sinar Logistik Abadi',
                'email' => 'info@sinarlogistik.co.id',
                'phone' => '031-8889900',
                'address' => 'Jl. Pemuda No. 88, Surabaya',
            ],
            [
                'name' => 'CV Multi Distribusi Nusantara',
                'email' => 'halo@multidistribusi.com',
                'phone' => '0274-555666',
                'address' => 'Jl. Malioboro No. 101, Yogyakarta',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::updateOrCreate(['name' => $sup['name']], $sup);
        }
    }
}
