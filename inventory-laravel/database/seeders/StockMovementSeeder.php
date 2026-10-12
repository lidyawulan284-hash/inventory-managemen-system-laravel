<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first();

        $keyboard = Product::where('sku', 'SKU-KB-001')->first();
        $mouse = Product::where('sku', 'SKU-MS-002')->first();
        $laptop = Product::where('sku', 'SKU-LP-004')->first();
        $paper = Product::where('sku', 'SKU-PPR-007')->first();

        $movements = [
            [
                'product_id' => $keyboard->id,
                'user_id' => $admin->id,
                'type' => 'IN',
                'quantity' => 30,
                'reference_number' => 'PO-2026-001',
                'notes' => 'Penerimaan stok awal dari PT Digital Solusindo',
                'created_at' => now()->subDays(5),
            ],
            [
                'product_id' => $keyboard->id,
                'user_id' => $staff->id,
                'type' => 'OUT',
                'quantity' => 5,
                'reference_number' => 'DO-2026-010',
                'notes' => 'Pengeluaran untuk kebutuhan divisi Marketing',
                'created_at' => now()->subDays(3),
            ],
            [
                'product_id' => $mouse->id,
                'user_id' => $staff->id,
                'type' => 'IN',
                'quantity' => 50,
                'reference_number' => 'PO-2026-002',
                'notes' => 'Penerimaan restock batch 1',
                'created_at' => now()->subDays(4),
            ],
            [
                'product_id' => $mouse->id,
                'user_id' => $staff->id,
                'type' => 'OUT',
                'quantity' => 10,
                'reference_number' => 'DO-2026-015',
                'notes' => 'Pengeluaran untuk inventaris ruang training',
                'created_at' => now()->subDays(2),
            ],
            [
                'product_id' => $laptop->id,
                'user_id' => $admin->id,
                'type' => 'IN',
                'quantity' => 5,
                'reference_number' => 'PO-2026-003',
                'notes' => 'Penerimaan laptop pengadaan Q3',
                'created_at' => now()->subDays(6),
            ],
            [
                'product_id' => $laptop->id,
                'user_id' => $staff->id,
                'type' => 'OUT',
                'quantity' => 2,
                'reference_number' => 'DO-2026-018',
                'notes' => 'Penyerahan unit laptop untuk staf IT baru',
                'created_at' => now()->subDays(1),
            ],
            [
                'product_id' => $paper->id,
                'user_id' => $staff->id,
                'type' => 'IN',
                'quantity' => 80,
                'reference_number' => 'PO-2026-004',
                'notes' => 'Penerimaan kertas HVS dari CV Prima Jaya Mandiri',
                'created_at' => now()->subDays(7),
            ],
            [
                'product_id' => $paper->id,
                'user_id' => $staff->id,
                'type' => 'OUT',
                'quantity' => 20,
                'reference_number' => 'DO-2026-020',
                'notes' => 'Distribusi kertas ke divisi Operasional',
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($movements as $m) {
            StockMovement::create($m);
        }
    }
}
