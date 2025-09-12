<?php
// Create a simple default logo
$logoPath = storage_path('app/public/logos');
if (!is_dir($logoPath)) {
    mkdir($logoPath, 0755, true);
}

// Create a simple SVG logo
$svgLogo = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="60" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
  <rect width="200" height="60" fill="#2563eb" rx="8"/>
  <text x="100" y="35" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="white">Tryp</text>
</svg>';

file_put_contents($logoPath . '/default-logo.svg', $svgLogo);
echo "Default SVG logo created at: " . $logoPath . '/default-logo.svg' . "\n";

// Also create a simple PNG placeholder (1x1 transparent pixel)
$pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
file_put_contents($logoPath . '/default-logo.png', $pngData);
echo "Default PNG logo created at: " . $logoPath . '/default-logo.png' . "\n";
?>
