<?php

// Function to create a placeholder image
function createPlaceholderImage($path, $width, $height, $text) {
    // Create a blank image
    $image = imagecreatetruecolor($width, $height);
    
    // Define colors
    $background = imagecolorallocate($image, rand(0, 100), rand(100, 200), rand(150, 255));
    $textColor = imagecolorallocate($image, 255, 255, 255);
    
    // Fill the background
    imagefill($image, 0, 0, $background);
    
    // Add text
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textHeight = imagefontheight($fontSize);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;
    
    imagestring($image, $fontSize, $x, $y, $text, $textColor);
    
    // Create directory if it doesn't exist
    $directory = dirname($path);
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    
    // Save the image
    imagejpeg($image, $path, 90);
    imagedestroy($image);
    
    echo "Created: $path\n";
}

// Base directory
$baseDir = 'public/images/';

// Bundle image sizes
$bundleSizes = [
    'card' => ['width' => 400, 'height' => 300],
    'hero' => ['width' => 1200, 'height' => 600],
    'gallery_main' => ['width' => 800, 'height' => 600],
    'gallery1' => ['width' => 600, 'height' => 400],
    'gallery2' => ['width' => 600, 'height' => 400],
    'gallery3' => ['width' => 600, 'height' => 400],
];

// Destination image sizes
$destinationSizes = [
    'main' => ['width' => 800, 'height' => 600],
    'gallery1' => ['width' => 600, 'height' => 400],
    'gallery2' => ['width' => 600, 'height' => 400],
    'gallery3' => ['width' => 600, 'height' => 400],
];

// Bundle types
$bundleTypes = ['mountain', 'city', 'wellness', 'island', 'beach'];

// Create bundle images
foreach ($bundleTypes as $bundle) {
    foreach ($bundleSizes as $type => $size) {
        $path = $baseDir . "bundles/$bundle/$type.jpg";
        createPlaceholderImage($path, $size['width'], $size['height'], "$bundle $type");
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
    foreach ($destinationSizes as $type => $size) {
        $path = $baseDir . "destinations/$destination/$type.jpg";
        createPlaceholderImage($path, $size['width'], $size['height'], "$destination $type");
    }
}

echo "All placeholder images have been created successfully!\n"; 