#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# If vendor folder or autoload not found, install dependencies
if [ ! -f vendor/autoload.php ]; then
  echo "→ Running composer install..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi