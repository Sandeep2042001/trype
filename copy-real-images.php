<?php
// Create real images based on database settings
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin\Setting;

echo "=== Create Real Images Script ===\n";

// Get current settings
$currentLogo = Setting::get('site_logo');
$currentHero = Setting::get('hero_bg_image');

echo "Current logo setting: " . ($currentLogo ?: 'Not set') . "\n";
echo "Current hero setting: " . ($currentHero ?: 'Not set') . "\n";

$logosDir = storage_path('app/public/logos');
$heroDir = storage_path('app/public/hero');

// Ensure directories exist
if (!is_dir($logosDir)) {
    mkdir($logosDir, 0755, true);
    echo "Created logos directory\n";
}

if (!is_dir($heroDir)) {
    mkdir($heroDir, 0755, true);
    echo "Created hero directory\n";
}

// Create a proper logo based on the filename from database
$logoFilename = '7Hx0NJN8GwxI1srCWrhfQQpt1hJx7CS4fu33xzhC.png';
$logoPath = "{$logosDir}/{$logoFilename}";

if (!file_exists($logoPath)) {
    echo "Creating logo: {$logoFilename}\n";
    
    // Create a proper logo image (blue background with white text)
    $width = 200;
    $height = 60;
    
    // Create image
    $image = imagecreate($width, $height);
    
    // Define colors
    $blue = imagecolorallocate($image, 37, 99, 235); // Blue background
    $white = imagecolorallocate($image, 255, 255, 255); // White text
    
    // Fill background
    imagefill($image, 0, 0, $blue);
    
    // Add text (simplified - you might want to use a font file)
    imagestring($image, 5, 70, 20, 'Tryp', $white);
    
    // Save as PNG
    imagepng($image, $logoPath);
    imagedestroy($image);
    
    echo "✅ Created logo: {$logoFilename}\n";
} else {
    echo "✅ Logo already exists: {$logoFilename}\n";
}

// Create a proper hero image based on the filename from database
$heroFilename = '7uY5jJLbY1RSPtxRZ8xcxt9h16RsjEUdUHu0tTTV.jpg';
$heroPath = "{$heroDir}/{$heroFilename}";

if (!file_exists($heroPath)) {
    echo "Creating hero image: {$heroFilename}\n";
    
    // Create a gradient hero image
    $width = 1920;
    $height = 1080;
    
    // Create image
    $image = imagecreate($width, $height);
    
    // Create gradient from blue to darker blue
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = 37 + ($ratio * 20);   // Blue to darker blue
        $g = 99 + ($ratio * 30);
        $b = 235 + ($ratio * 20);
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $width, $y, $color);
    }
    
    // Save as JPEG
    imagejpeg($image, $heroPath, 90);
    imagedestroy($image);
    
    echo "✅ Created hero image: {$heroFilename}\n";
} else {
    echo "✅ Hero image already exists: {$heroFilename}\n";
}

// Update database settings to use the correct paths
Setting::set('site_logo', "/storage/logos/{$logoFilename}", 'general');
Setting::set('hero_bg_image', "/storage/hero/{$heroFilename}", 'general');

echo "✅ Updated database settings\n";

// Verify files exist
echo "\n=== Verification ===\n";
$logoPublicPath = public_path("/storage/logos/{$logoFilename}");
$heroPublicPath = public_path("/storage/hero/{$heroFilename}");

echo "Logo file exists: " . (file_exists($logoPublicPath) ? '✅ Yes' : '❌ No') . "\n";
echo "Hero file exists: " . (file_exists($heroPublicPath) ? '✅ Yes' : '❌ No') . "\n";

if (file_exists($logoPublicPath)) {
    echo "Logo file size: " . filesize($logoPublicPath) . " bytes\n";
}

if (file_exists($heroPublicPath)) {
    echo "Hero file size: " . filesize($heroPublicPath) . " bytes\n";
}

// Show current settings
echo "\n=== Current Settings ===\n";
echo "Logo setting: " . Setting::get('site_logo') . "\n";
echo "Hero setting: " . Setting::get('hero_bg_image') . "\n";

echo "\n=== Script Complete ===\n";
?>
