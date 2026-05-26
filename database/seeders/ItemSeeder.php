<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::pluck('id', 'category_code');

        $items = [
            // TENDA
            ['code' => 'ITM-001', 'cat' => 'CAT-01', 'name' => 'Tenda Dome 4P', 'stock' => 8, 'price' => 45000, 'desc' => 'Tenda dome kapasitas 4 orang, bahan polyester 210T, waterproof 3000mm. Cocok untuk camping di segala medan.', 'spec' => "Kapasitas: 4 orang\nDimensi: 210 x 210 x 130 cm\nBahan: Polyester 210T\nWaterproof: 3000mm\nBerat: 3.5 kg"],
            ['code' => 'ITM-002', 'cat' => 'CAT-01', 'name' => 'Tenda Dome 2P', 'stock' => 6, 'price' => 30000, 'desc' => 'Tenda dome untuk 2 orang, ringan dan mudah dipasang.', 'spec' => "Kapasitas: 2 orang\nDimensi: 140 x 210 x 110 cm\nBerat: 2.1 kg"],
            ['code' => 'ITM-003', 'cat' => 'CAT-01', 'name' => 'Tenda Family 6P', 'stock' => 3, 'price' => 75000, 'desc' => 'Tenda keluarga kapasitas besar untuk 6 orang.', 'spec' => "Kapasitas: 6 orang\nDimensi: 300 x 240 x 180 cm"],
            ['code' => 'ITM-004', 'cat' => 'CAT-01', 'name' => 'Flysheet 3x4m', 'stock' => 5, 'price' => 15000, 'desc' => 'Pelindung tambahan dari hujan & matahari.', 'spec' => "Ukuran: 3 x 4 meter\nBahan: Polyester PU"],

            // CARRIER
            ['code' => 'ITM-005', 'cat' => 'CAT-02', 'name' => 'Carrier 60L', 'stock' => 7, 'price' => 25000, 'desc' => 'Carrier kapasitas 60L untuk pendakian 3-5 hari.', 'spec' => "Kapasitas: 60 liter\nBerat: 1.8 kg\nFitur: Hipbelt, rain cover included"],
            ['code' => 'ITM-006', 'cat' => 'CAT-02', 'name' => 'Carrier 80L', 'stock' => 4, 'price' => 35000, 'desc' => 'Carrier ekspedisi 80L untuk perjalanan panjang.', 'spec' => "Kapasitas: 80 liter\nBerat: 2.3 kg"],
            ['code' => 'ITM-007', 'cat' => 'CAT-02', 'name' => 'Daypack 30L', 'stock' => 10, 'price' => 15000, 'desc' => 'Tas harian untuk hiking ringan.', 'spec' => "Kapasitas: 30 liter\nBerat: 0.9 kg"],

            // SLEEPING
            ['code' => 'ITM-008', 'cat' => 'CAT-03', 'name' => 'Sleeping Bag Polar', 'stock' => 12, 'price' => 15000, 'desc' => 'Sleeping bag polar untuk suhu hingga 5°C.', 'spec' => "Suhu kerja: 5-15°C\nBahan: Polar fleece\nUkuran: 210 x 80 cm"],
            ['code' => 'ITM-009', 'cat' => 'CAT-03', 'name' => 'Sleeping Bag Down', 'stock' => 5, 'price' => 25000, 'desc' => 'Sleeping bag down feather untuk medan dingin.', 'spec' => "Suhu kerja: -5 sampai 10°C\nFilling: Goose down"],
            ['code' => 'ITM-010', 'cat' => 'CAT-03', 'name' => 'Matras Lipat', 'stock' => 15, 'price' => 8000, 'desc' => 'Matras lipat foam untuk alas tidur.', 'spec' => "Dimensi: 180 x 50 cm\nBahan: EVA foam"],
            ['code' => 'ITM-011', 'cat' => 'CAT-03', 'name' => 'Hammock Single', 'stock' => 6, 'price' => 12000, 'desc' => 'Hammock untuk satu orang, lengkap dengan tali.', 'spec' => "Kapasitas: 1 orang (120 kg)\nBahan: Parasut nylon"],

            // KOMPOR
            ['code' => 'ITM-012', 'cat' => 'CAT-04', 'name' => 'Kompor Portable', 'stock' => 8, 'price' => 10000, 'desc' => 'Kompor portable dengan gas butane.', 'spec' => "Bahan bakar: Butane gas (terpisah)\nBerat: 0.4 kg"],
            ['code' => 'ITM-013', 'cat' => 'CAT-04', 'name' => 'Nesting Set 4P', 'stock' => 6, 'price' => 12000, 'desc' => 'Set alat masak untuk 4 orang.', 'spec' => "Material: Aluminium\nIsi: Panci, wajan, mangkok x4, gelas x4"],
            ['code' => 'ITM-014', 'cat' => 'CAT-04', 'name' => 'Trangia Set', 'stock' => 4, 'price' => 18000, 'desc' => 'Trangia set lengkap dengan kompor spiritus.', 'spec' => "Bahan bakar: Spiritus / alcohol\nFlame guard included"],

            // LIGHTING
            ['code' => 'ITM-015', 'cat' => 'CAT-05', 'name' => 'Lampu Camping LED', 'stock' => 10, 'price' => 8000, 'desc' => 'Lampu LED rechargeable dengan 3 mode pencahayaan.', 'spec' => "Lumens: 200lm\nBaterai: 2400mAh (USB charge)\nDurasi: 8 jam"],
            ['code' => 'ITM-016', 'cat' => 'CAT-05', 'name' => 'Headlamp', 'stock' => 8, 'price' => 7000, 'desc' => 'Headlamp untuk navigasi malam.', 'spec' => "Lumens: 150lm\nBaterai: 3xAAA"],
            ['code' => 'ITM-017', 'cat' => 'CAT-05', 'name' => 'Lantern Petromax', 'stock' => 3, 'price' => 15000, 'desc' => 'Lantern petromax untuk pencahayaan luas.', 'spec' => "Lumens: 800lm\nBaterai rechargeable"],

            // ALAT LAIN
            ['code' => 'ITM-018', 'cat' => 'CAT-06', 'name' => 'Trekking Pole', 'stock' => 14, 'price' => 10000, 'desc' => 'Tongkat trekking aluminium teleskopik (sepasang).', 'spec' => "Material: Aluminium 7075\nPanjang: 65-135 cm (adjustable)"],
            ['code' => 'ITM-019', 'cat' => 'CAT-06', 'name' => 'Jas Hujan Setelan', 'stock' => 10, 'price' => 8000, 'desc' => 'Jas hujan setelan (atasan + celana).', 'spec' => "Ukuran: M / L / XL\nBahan: PVC waterproof"],
            ['code' => 'ITM-020', 'cat' => 'CAT-06', 'name' => 'Kompor Tablet Lipat', 'stock' => 8, 'price' => 5000, 'desc' => 'Kompor lipat dengan bahan bakar tablet hexamine.', 'spec' => "Berat: 0.1 kg\nUkuran lipat: 11 x 8 x 2 cm"],
        ];

        foreach ($items as $row) {
            Item::create([
                'item_code' => $row['code'],
                'category_id' => $cats[$row['cat']],
                'name' => $row['name'],
                'description' => $row['desc'],
                'specifications' => $row['spec'],
                'stock' => $row['stock'],
                'available_stock' => $row['stock'],
                'condition' => Item::CONDITION_GOOD,
                'status' => Item::STATUS_AVAILABLE,
                'price_per_day' => $row['price'],
                'is_active' => true,
            ]);
        }
    }
}
