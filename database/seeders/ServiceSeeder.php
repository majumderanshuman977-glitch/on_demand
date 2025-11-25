<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Dry Cleaning Service',
                'description' => 'Professional AC dry cleaning service',
                'price' => 500,
                'offer_price' => 400,
                'duration' => 1.0,
                'includes' => json_encode(['Cleaning Filters', 'Dust Removal', 'Basic Checkup']),
            ],
            [
                'title' => 'AC Less/No Cooling Repair',
                'description' => 'Fix cooling issues and performance drop',
                'price' => 800,
                'offer_price' => 650,
                'duration' => 1.5,
                'includes' => json_encode(['Cooling Diagnosis', 'Gas Level Check']),
            ],
            [
                'title' => 'AC Power Issue Repair',
                'description' => 'Repair AC power related issues',
                'price' => 700,
                'offer_price' => 550,
                'duration' => 1.2,
                'includes' => json_encode(['Compressor Check', 'Electrical Inspection']),
            ],
            [
                'title' => 'AC Noise/Odor Repair',
                'description' => 'Fix unusual sounds or bad smell',
                'price' => 600,
                'offer_price' => 500,
                'duration' => 1.0,
                'includes' => json_encode(['Fan Cleaning', 'Odor Treatment']),
            ],
        ];

        foreach ($services as $service) {
            Services::create(array_merge($service, [
                'sub_category_item_id' => 1, // AC
            ]));
        }
    }
}
