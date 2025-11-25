<?php

namespace Database\Seeders;

use App\Models\SubCategoryItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubCategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $items = [
            'AC',
            'Television',
            'Laptop',
            'Air Cooler',
            'Washing Machine',
            'Geyser',
            'Stove',
            'Microwave',
            'Fridge',
            'Water Purifier',
            'Chimney',
        ];

        foreach ($items as $item) {
            SubCategoryItem::create([
                'category_id' => 1,
                'name' => $item,
            ]);
        }
    }
}
