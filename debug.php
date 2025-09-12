<?php
// Debug script to test basic functionality
echo "=== Railway Debug Script ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current Directory: " . getcwd() . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";

// Test if we can load Laravel
echo "\n=== Testing Laravel Bootstrap ===\n";
try {
    require_once 'vendor/autoload.php';
    echo "✅ Composer autoload loaded\n";
    
    $app = require_once 'bootstrap/app.php';
    echo "✅ Laravel app bootstrap loaded\n";
    
    // Test database connection
    echo "\n=== Testing Database Connection ===\n";
    $config = $app['config']['database.connections.mysql'];
    echo "DB Host: " . $config['host'] . "\n";
    echo "DB Port: " . $config['port'] . "\n";
    echo "DB Database: " . $config['database'] . "\n";
    echo "DB Username: " . $config['username'] . "\n";
    
    try {
        $pdo = new PDO(
            "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}",
            $config['username'],
            $config['password']
        );
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

echo "\n=== Environment Variables ===\n";
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'not set') . "\n";
echo "APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? 'not set') . "\n";
echo "DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'not set') . "\n";
echo "PORT: " . ($_ENV['PORT'] ?? 'not set') . "\n";

echo "\n=== File Permissions ===\n";
echo "storage/ writable: " . (is_writable('storage') ? '✅' : '❌') . "\n";
echo "bootstrap/cache/ writable: " . (is_writable('bootstrap/cache') ? '✅' : '❌') . "\n";

echo "\n=== Debug Complete ===\n";
?>
