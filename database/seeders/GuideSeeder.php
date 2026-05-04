<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guide;

class GuideSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            [
                'name' => 'Rahim Uddin',
                'photo_url' => 'https://i.pinimg.com/736x/02/c7/85/02c785ccea228c49562ae325d74c7007.jpg',
                'speciality' => 'Beach & Adventure Tours',
                'price_per_day' => 1500,
                'rating' => 4.8,
                'description' => 'Experienced local guide specializing in beach tours, snorkeling spots, and hidden gems of Cox\'s Bazar. 5+ years of guiding experience.',
            ],
            [
                'name' => 'Fatima Begum',
                'photo_url' => 'https://i.pinimg.com/1200x/b9/99/ce/b999ce3b1ad833a3eb760a742252eafe.jpg',
                'speciality' => 'Cultural & Heritage Tours',
                'price_per_day' => 2000,
                'rating' => 4.9,
                'description' => 'Expert in local culture, history, and heritage sites. Fluent in English and Bengali. Perfect for families and cultural enthusiasts.',
            ],
            [
                'name' => 'Jahid Hasan',
                'photo_url' => 'https://i.pinimg.com/736x/f3/c3/13/f3c313e23a8d810207f615a1b5ee279e.jpg',
                'speciality' => 'Budget & Backpacker Tours',
                'price_per_day' => 1200,
                'rating' => 4.6,
                'description' => 'Friendly and affordable guide perfect for backpackers and budget travelers. Knows all the best local food spots and scenic routes.',
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create($guide);
        }
    }
}
