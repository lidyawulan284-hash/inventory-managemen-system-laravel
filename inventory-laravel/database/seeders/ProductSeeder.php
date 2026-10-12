<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Elektronik & Gadget')->first();
        $accessories = Category::where('name', 'Aksesoris Komputer')->first();
        $atk = Category::where('name', 'Alat Tulis Kantor (ATK)')->first();
        $network = Category::where('name', 'Peralatan Jaringan')->first();
        $furniture = Category::where('name', 'Perabot Kantor')->first();

        $supplier1 = Supplier::where('name', 'PT Digital Solusindo')->first();
        $supplier2 = Supplier::where('name', 'CV Prima Jaya Mandiri')->first();
        $supplier3 = Supplier::where('name', 'PT Sinar Logistik Abadi')->first();
        $supplier4 = Supplier::where('name', 'CV Multi Distribusi Nusantara')->first();

        $products = [
            [
                'category_id' => $accessories->id,
                'sku' => 'SKU-KB-001',
                'name' => 'Mechanical Keyboard RGB TKL',
                'description' => 'Keyboard mechanical dengan switch red tactile, RGB per-key, dan kabel detachable Type-C.',
                'price' => 450000.00,
                'stock' => 25,
                'minimum_stock' => 5,
                'suppliers' => [$supplier1->id => ['supplier_price' => 380000.00], $supplier2->id => ['supplier_price' => 390000.00]],
            ],
            [
                'category_id' => $accessories->id,
                'sku' => 'SKU-MS-002',
                'name' => 'Ergonomic Wireless Mouse 2.4GHz',
                'description' => 'Mouse nirkabel ergonomis silent click dengan sensitivitas DPI dapat diatur hingga 2400.',
                'price' => 175000.00,
                'stock' => 40,
                'minimum_stock' => 10,
                'suppliers' => [$supplier1->id => ['supplier_price' => 140000.00]],
            ],
            [
                'category_id' => $electronics->id,
                'sku' => 'SKU-MN-003',
                'name' => 'Monitor LED 24 Inch Full HD IPS 100Hz',
                'description' => 'Monitor bezel-less dengan panel IPS jernih, refresh rate 100Hz, port HDMI dan DisplayPort.',
                'price' => 1450000.00,
                'stock' => 12,
                'minimum_stock' => 5,
                'suppliers' => [$supplier1->id => ['supplier_price' => 1250000.00], $supplier3->id => ['supplier_price' => 1280000.00]],
            ],
            [
                'category_id' => $electronics->id,
                'sku' => 'SKU-LP-004',
                'name' => 'Laptop Business Ultrabook 14 Inch i5',
                'description' => 'Laptop bisnis tipis dan ringan dengan prosesor Core i5, RAM 16GB, dan SSD NVMe 512GB.',
                'price' => 9800000.00,
                'stock' => 3, // LOW STOCK!
                'minimum_stock' => 5,
                'suppliers' => [$supplier1->id => ['supplier_price' => 8900000.00]],
            ],
            [
                'category_id' => $network->id,
                'sku' => 'SKU-RT-005',
                'name' => 'Router Dual-Band Gigabit AC1200',
                'description' => 'WiFi router kecepatan tinggi dengan 4 antena omni directional dan 4 port LAN Gigabit.',
                'price' => 385000.00,
                'stock' => 18,
                'minimum_stock' => 5,
                'suppliers' => [$supplier3->id => ['supplier_price' => 310000.00]],
            ],
            [
                'category_id' => $network->id,
                'sku' => 'SKU-SW-006',
                'name' => 'Switch Hub 16-Port Gigabit Rackmount',
                'description' => 'Switch jaringan 16 port 10/100/1000 Mbps casing besi metal kokoh plug and play.',
                'price' => 850000.00,
                'stock' => 2, // LOW STOCK!
                'minimum_stock' => 5,
                'suppliers' => [$supplier3->id => ['supplier_price' => 720000.00]],
            ],
            [
                'category_id' => $atk->id,
                'sku' => 'SKU-PPR-007',
                'name' => 'Kertas HVS A4 80 GSM 1 Rim (500 Lembar)',
                'description' => 'Kertas fotokopi putih bersih ketebalan 80 gsm ramah lingkungan dan hasil cetak tajam.',
                'price' => 55000.00,
                'stock' => 60,
                'minimum_stock' => 15,
                'suppliers' => [$supplier2->id => ['supplier_price' => 45000.00], $supplier4->id => ['supplier_price' => 46000.00]],
            ],
            [
                'category_id' => $atk->id,
                'sku' => 'SKU-PEN-008',
                'name' => 'Gel Pen Hitam 0.5mm Box isi 12 Pcs',
                'description' => 'Bolpoin gel tinta pekat anti macet dengan grip karet anti selip.',
                'price' => 36000.00,
                'stock' => 45,
                'minimum_stock' => 10,
                'suppliers' => [$supplier2->id => ['supplier_price' => 28000.00]],
            ],
            [
                'category_id' => $furniture->id,
                'sku' => 'SKU-CHR-009',
                'name' => 'Kursi Kerja Ergonomis Mesh Lumbar Support',
                'description' => 'Kursi kantor sandaran jaring breathable dengan penopang punggung dan hidrolik penyesuai tinggi.',
                'price' => 950000.00,
                'stock' => 8,
                'minimum_stock' => 5,
                'suppliers' => [$supplier4->id => ['supplier_price' => 800000.00]],
            ],
            [
                'category_id' => $furniture->id,
                'sku' => 'SKU-DSK-010',
                'name' => 'Meja Kerja Minimalis Modern 120x60 cm',
                'description' => 'Meja kantor rangka besi hollow kokoh dengan daun meja motif serat kayu tahan gores.',
                'price' => 650000.00,
                'stock' => 1, // LOW STOCK!
                'minimum_stock' => 4,
                'suppliers' => [$supplier4->id => ['supplier_price' => 540000.00]],
            ],
        ];

        foreach ($products as $item) {
            $suppliersList = $item['suppliers'];
            unset($item['suppliers']);

            $product = Product::updateOrCreate(['sku' => $item['sku']], $item);

            // Sync suppliers many-to-many relationship
            $product->suppliers()->sync($suppliersList);
        }
    }
}
