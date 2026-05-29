#!/bin/bash

set -e

echo "🚀 Deploying muliajaya..."

# Pull latest code
git pull origin main

# Build image
docker compose build --no-cache

# Stop & start container
docker compose down
docker compose up -d

# Wait for container to be ready
echo "⏳ Waiting for container..."
sleep 5

# Run artisan commands
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Fix permissions
docker compose exec app chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deploy selesai!"
