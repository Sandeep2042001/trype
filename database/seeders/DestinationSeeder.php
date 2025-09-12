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
                'name' => 'Paris',
                'location' => 'France',
                'description' => 'The city of lights and love.',
                'featured_image' => 'images/paris.jpg',
            ],
            [
                'name' => 'Tokyo',
                'location' => 'Japan',
                'description' => 'A bustling metropolis blending tradition and technology.',
                'featured_image' => 'images/tokyo.jpg',
            ],
            [
                'name' => 'New York',
                'location' => 'USA',
                'description' => 'The city that never sleeps.',
                'featured_image' => 'images/newyork.jpg',
            ],
            [
                'name' => 'Sydney',
                'location' => 'Australia',
                'description' => 'Famous for its Opera House and beautiful harbor.',
                'featured_image' => 'images/sydney.jpg',
            ],
            [
                'name' => 'Rome',
                'location' => 'Italy',
                'description' => 'The eternal city with rich history and culture.',
                'featured_image' => 'images/rome.jpg',
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
