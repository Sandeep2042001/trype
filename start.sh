#!/bin/bash

# Set the port from Railway environment variable
export PORT=${PORT:-8000}

# Clear caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Create storage link if it doesn't exist
php artisan storage:link || true

# Run migrations (ignore errors for existing tables)
php artisan migrate --force || true

# Start the application
php artisan serve --host=0.0.0.0 --port=$PORT
