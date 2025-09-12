#!/bin/bash

# Set the port from Railway environment variable
export PORT=${PORT:-8000}

# Run migrations
php artisan migrate --force

# Start the application
php artisan serve --host=0.0.0.0 --port=$PORT
