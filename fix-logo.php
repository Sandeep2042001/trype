<?php
// Quick script to fix logo issue
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin\Setting;

echo "=== Logo Fix Script ===\n";

// Check current logo setting
$currentLogo = Setting::get('site_logo');
echo "Current logo setting: " . ($currentLogo ?: 'Not set') . "\n";

// Check if storage link exists
$storageLink = public_path('storage');
echo "Storage link exists: " . (is_link($storageLink) ? 'Yes' : 'No') . "\n";

// Check if storage directory exists
$storageDir = storage_path('app/public');
echo "Storage directory exists: " . (is_dir($storageDir) ? 'Yes' : 'No') . "\n";

// List files in storage/app/public
if (is_dir($storageDir)) {
    echo "Files in storage/app/public:\n";
    $files = scandir($storageDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
}

// Check if logos directory exists
$logosDir = storage_path('app/public/logos');
echo "Logos directory exists: " . (is_dir($logosDir) ? 'Yes' : 'No') . "\n";

if (is_dir($logosDir)) {
    echo "Files in logos directory:\n";
    $logoFiles = scandir($logosDir);
    foreach ($logoFiles as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
}

// Set a default logo if none exists
if (!$currentLogo) {
    echo "Setting default logo...\n";
    
    // Create logos directory if it doesn't exist
    $logosDir = storage_path('app/public/logos');
    if (!is_dir($logosDir)) {
        mkdir($logosDir, 0755, true);
        echo "Created logos directory\n";
    }
    
    // Create default logo if it doesn't exist
    $defaultLogoPath = $logosDir . '/default-logo.png';
    if (!file_exists($defaultLogoPath)) {
        // Create a simple 1x1 transparent PNG
        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        file_put_contents($defaultLogoPath, $pngData);
        echo "Created default logo file\n";
    }
    
    Setting::set('site_logo', '/storage/logos/default-logo.png', 'general');
    echo "Default logo set to: /storage/logos/default-logo.png\n";
}

echo "=== Script Complete ===\n";
?>
