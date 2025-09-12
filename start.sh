#!/bin/bash

# Set the port from Railway environment variable
export PORT=${PORT:-8000}

# Clear caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Create storage link if it doesn't exist
php artisan storage:link || true

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully';" || echo "Database connection failed"

# Skip migrations since tables are already created manually
echo "Skipping migrations - tables already exist"

# Start the application
echo "Starting Laravel application on port $PORT"
php artisan serve --host=0.0.0.0 --port=$PORT
