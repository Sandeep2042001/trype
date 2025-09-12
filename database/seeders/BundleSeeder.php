<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bundle;

class BundleSeeder extends Seeder
{
    public function run()
    {
        $bundles = [
            [
                'name' => 'Adventure Package',
                'slug' => 'adventure-package',
                'description' => 'Exciting adventure trips for thrill seekers.',
                'image' => 'images/adventure.jpg',
            ],
            [
                'name' => 'Relaxation Package',
                'slug' => 'relaxation-package',
                'description' => 'Peaceful and relaxing vacation packages.',
                'image' => 'images/relaxation.jpg',
            ],
            [
                'name' => 'Family Package',
                'slug' => 'family-package',
                'description' => 'Fun-filled trips for the whole family.',
                'image' => 'images/family.jpg',
            ],
            [
                'name' => 'Romantic Getaway',
                'slug' => 'romantic-getaway',
                'description' => 'Perfect packages for couples.',
                'image' => 'images/romantic.jpg',
            ],
            [
                'name' => 'Cultural Experience',
                'slug' => 'cultural-experience',
                'description' => 'Explore rich cultures and traditions.',
                'image' => 'images/cultural.jpg',
            ],
        ];

        foreach ($bundles as $bundle) {
            Bundle::create($bundle);
        }
    }
}
