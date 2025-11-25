<?php

namespace Database\Seeders;


use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'AC & Appliances Repair',
                'description' => 'Home appliances and AC repair services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Men Salon',
                'description' => 'Men grooming and salon services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Women Salon',
                'description' => 'Women grooming and salon services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cleaning',
                'description' => 'Home and office cleaning services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electrician, Plumber & Carpenter',
                'description' => 'Quick fix services for electric, plumbing and carpentry',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Water Purifier',
                'description' => 'Water purifier services and maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Spa',
                'description' => 'Relaxation and massage spa services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
