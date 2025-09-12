<?php

// Function to create a placeholder file
function createPlaceholderFile($path) {
    // Create directory if it doesn't exist
    $directory = dirname($path);
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    
    // Create an empty file
    file_put_contents($path, 'Placeholder image');
    
    echo "Created: $path\n";
}

// Base directory
$baseDir = 'public/images/';

// Bundle image types
$bundleImageTypes = ['card.jpg', 'hero.jpg', 'gallery_main.jpg', 'gallery1.jpg', 'gallery2.jpg', 'gallery3.jpg'];

// Destination image types
$destinationImageTypes = ['main.jpg', 'gallery1.jpg', 'gallery2.jpg', 'gallery3.jpg'];

// Bundle types
$bundleTypes = ['mountain', 'city', 'wellness', 'island', 'beach'];

// Create bundle images
foreach ($bundleTypes as $bundle) {
    foreach ($bundleImageTypes as $type) {
        $path = $baseDir . "bundles/$bundle/$type";
        createPlaceholderFile($path);
    }
}

// Destination types
$destinations = [
    // Beach Bundle
    'maldives', 'bali', 'cancun', 'santorini', 'miami',
    // Mountain Bundle
    'swiss_alps', 'rockies', 'himalayas', 'patagonia',
    // City Bundle
    'paris', 'tokyo', 'nyc', 'barcelona',
    // Wellness Bundle
    'bali_yoga', 'sedona', 'costa_rica', 'swiss_wellness',
    // Island Bundle
    'greek_islands', 'hawaii', 'caribbean', 'thai_islands'
];

// Create destination images
foreach ($destinations as $destination) {
    foreach ($destinationImageTypes as $type) {
        $path = $baseDir . "destinations/$destination/$type";
        createPlaceholderFile($path);
    }
}

echo "All placeholder files have been created successfully!\n"; 