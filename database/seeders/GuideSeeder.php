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
                'email' => 'rahim@example.com',
                'password' => bcrypt('password'),
                'photo' => 'https://i.pinimg.com/736x/02/c7/85/02c785ccea228c49562ae325d74c7007.jpg',
                'specialization' => 'Beach & Adventure Tours',
                'price_per_day' => 1500,
                'bio' => 'Experienced local guide specializing in beach tours, snorkeling spots, and hidden gems of Cox\'s Bazar. 5+ years of guiding experience.',
                'languages' => 'English, Bengali',
                'experience_years' => 5,
                'destination_id' => 1,
            ],
            [
                'name' => 'Fatima Begum',
                'email' => 'fatima@example.com',
                'password' => bcrypt('password'),
                'photo' => 'https://i.pinimg.com/1200x/b9/99/ce/b999ce3b1ad833a3eb760a742252eafe.jpg',
                'specialization' => 'Cultural & Heritage Tours',
                'price_per_day' => 2000,
                'bio' => 'Expert in local culture, history, and heritage sites. Fluent in English and Bengali. Perfect for families and cultural enthusiasts.',
                'languages' => 'English, Bengali, Hindi',
                'experience_years' => 8,
                'destination_id' => 1,
            ],
            [
                'name' => 'Jahid Hasan',
                'email' => 'jahid@example.com',
                'password' => bcrypt('password'),
                'photo' => 'https://i.pinimg.com/736x/f3/c3/13/f3c313e23a8d810207f615a1b5ee279e.jpg',
                'specialization' => 'Budget & Backpacker Tours',
                'price_per_day' => 1200,
                'bio' => 'Friendly and affordable guide perfect for backpackers and budget travelers. Knows all the best local food spots and scenic routes.',
                'languages' => 'English, Bengali',
                'experience_years' => 3,
                'destination_id' => 1,
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create($guide);
        }
    }
}
