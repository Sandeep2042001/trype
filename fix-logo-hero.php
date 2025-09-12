<?php
// Comprehensive fix for logo and hero image issues
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin\Setting;

echo "=== Logo and Hero Image Fix Script ===\n";

// Check current settings
$currentLogo = Setting::get('site_logo');
$currentHero = Setting::get('hero_bg_image');

echo "Current logo setting: " . ($currentLogo ?: 'Not set') . "\n";
echo "Current hero setting: " . ($currentHero ?: 'Not set') . "\n";

// Create necessary directories
$logosDir = storage_path('app/public/logos');
$heroDir = storage_path('app/public/hero');

if (!is_dir($logosDir)) {
    mkdir($logosDir, 0755, true);
    echo "Created logos directory\n";
}

if (!is_dir($heroDir)) {
    mkdir($heroDir, 0755, true);
    echo "Created hero directory\n";
}

// Create default logo if none exists
if (!$currentLogo) {
    echo "Creating default logo...\n";
    
    // Create a simple SVG logo
    $svgLogo = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="60" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
  <rect width="200" height="60" fill="#2563eb" rx="8"/>
  <text x="100" y="35" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="white">Tryp</text>
</svg>';
    
    file_put_contents($logosDir . '/default-logo.svg', $svgLogo);
    
    // Also create a PNG version
    $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    file_put_contents($logosDir . '/default-logo.png', $pngData);
    
    Setting::set('site_logo', '/storage/logos/default-logo.svg', 'general');
    echo "Default logo created and set\n";
}

// Create default hero image if none exists
if (!$currentHero) {
    echo "Creating default hero image...\n";
    
    // Create a simple gradient hero image (1x1 pixel that will be stretched)
    $heroData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    file_put_contents($heroDir . '/default-hero.jpg', $heroData);
    
    Setting::set('hero_bg_image', '/storage/hero/default-hero.jpg', 'general');
    echo "Default hero image created and set\n";
}

// Check if storage link exists and is working
$storageLink = public_path('storage');
if (!is_link($storageLink)) {
    echo "Storage link does not exist, creating...\n";
    // This will be handled by the start.sh script
} else {
    echo "Storage link exists\n";
}

// Test if files are accessible
if ($currentLogo) {
    $logoPath = public_path($currentLogo);
    echo "Logo file exists: " . (file_exists($logoPath) ? 'Yes' : 'No') . "\n";
    if (file_exists($logoPath)) {
        echo "Logo file size: " . filesize($logoPath) . " bytes\n";
    }
}

if ($currentHero) {
    $heroPath = public_path($currentHero);
    echo "Hero file exists: " . (file_exists($heroPath) ? 'Yes' : 'No') . "\n";
    if (file_exists($heroPath)) {
        echo "Hero file size: " . filesize($heroPath) . " bytes\n";
    }
}

echo "=== Script Complete ===\n";
?>
