#!/bin/sh

# Wait for Redis (optional)
until nc -z -v -w30 redis 6379
do
  echo "⏳ Waiting for Redis..."
  sleep 1
done
echo "✅ Redis is available."

# Ensure /var/www has the right permissions (optional for shared volumes)
chown -R www-data:www-data /var/www

# Install Composer dependencies if vendor folder does not exist
if [ ! -d "/var/www/vendor" ]; then
  echo "📦 Installing PHP dependencies with Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "📦 Skipping composer install: vendor folder already exists."
fi

# Laravel setup
echo "⚙️ Running Laravel setup..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan serve --host=0.0.0.0 --port=8000
