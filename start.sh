#!/bin/bash

# Set the port from Railway environment variable
export PORT=${PORT:-8000}
echo "Railway PORT environment variable: $PORT"

echo "=== Starting Laravel Application ==="
echo "Port: $PORT"
echo "Working Directory: $(pwd)"
echo "PHP Version: $(php -v | head -1)"

# Make sure storage directories exist and are writable
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views
chmod -R 775 storage bootstrap/cache

# Clear caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Create storage link if it doesn't exist
echo "Creating storage link..."
php artisan storage:link || true

# Test basic PHP functionality
echo "Testing PHP functionality..."
php debug.php

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully'; } catch(Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }" || echo "Database connection test failed"

# Run migrations and seeders now that database is available
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

echo "Running seeders..."
php artisan db:seed --force || echo "Seeding failed, continuing..."

# Start the application
echo "Starting Laravel application on port $PORT"
echo "Application will be available at: http://0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT
