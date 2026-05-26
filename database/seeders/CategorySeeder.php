<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_code' => 'CAT-01', 'name' => 'Tenda', 'icon' => '⛺', 'description' => 'Tenda dome, family, dan tenda gunung untuk berbagai kebutuhan camping.'],
            ['category_code' => 'CAT-02', 'name' => 'Carrier & Tas', 'icon' => '🎒', 'description' => 'Carrier 40L hingga 80L untuk perjalanan singkat maupun ekspedisi panjang.'],
            ['category_code' => 'CAT-03', 'name' => 'Sleeping Gear', 'icon' => '🛏️', 'description' => 'Sleeping bag, matras, dan hammock untuk istirahat nyaman.'],
            ['category_code' => 'CAT-04', 'name' => 'Alat Masak', 'icon' => '🔥', 'description' => 'Kompor portable, nesting, dan perlengkapan masak outdoor.'],
            ['category_code' => 'CAT-05', 'name' => 'Lighting', 'icon' => '🔦', 'description' => 'Lampu camping, headlamp, dan lantern untuk penerangan area camp.'],
            ['category_code' => 'CAT-06', 'name' => 'Perlengkapan Lain', 'icon' => '🧭', 'description' => 'Trekking pole, jas hujan, dan aksesori outdoor lainnya.'],
        ];

        foreach ($categories as $cat) {
            Category::create([...$cat, 'is_active' => true]);
        }
    }
}
