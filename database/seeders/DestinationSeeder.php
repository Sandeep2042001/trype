<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        $destinations = [
            [
                'bundle_id' => 1, // Assuming bundle with ID 1 exists
                'name' => 'Paris',
                'location' => 'France',
                'description' => 'The city of lights and love.',
                'main_image' => 'images/paris.jpg',
                'included_items' => json_encode(['Hotel', 'Breakfast', 'City Tour']),
                'gallery' => json_encode(['images/paris1.jpg', 'images/paris2.jpg']),
                'destination_type' => 'international',
            ],
            [
                'bundle_id' => 1,
                'name' => 'Tokyo',
                'location' => 'Japan',
                'description' => 'A bustling metropolis blending tradition and technology.',
                'main_image' => 'images/tokyo.jpg',
                'included_items' => json_encode(['Hotel', 'Breakfast', 'Temple Tour']),
                'gallery' => json_encode(['images/tokyo1.jpg', 'images/tokyo2.jpg']),
                'destination_type' => 'international',
            ],
            [
                'bundle_id' => 1,
                'name' => 'New York',
                'location' => 'USA',
                'description' => 'The city that never sleeps.',
                'main_image' => 'images/newyork.jpg',
                'included_items' => json_encode(['Hotel', 'Breakfast', 'Broadway Show']),
                'gallery' => json_encode(['images/newyork1.jpg', 'images/newyork2.jpg']),
                'destination_type' => 'international',
            ],
            [
                'bundle_id' => 1,
                'name' => 'Sydney',
                'location' => 'Australia',
                'description' => 'Famous for its Opera House and beautiful harbor.',
                'main_image' => 'images/sydney.jpg',
                'included_items' => json_encode(['Hotel', 'Breakfast', 'Harbor Cruise']),
                'gallery' => json_encode(['images/sydney1.jpg', 'images/sydney2.jpg']),
                'destination_type' => 'international',
            ],
            [
                'bundle_id' => 1,
                'name' => 'Rome',
                'location' => 'Italy',
                'description' => 'The eternal city with rich history and culture.',
                'main_image' => 'images/rome.jpg',
                'included_items' => json_encode(['Hotel', 'Breakfast', 'Colosseum Tour']),
                'gallery' => json_encode(['images/rome1.jpg', 'images/rome2.jpg']),
                'destination_type' => 'international',
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
