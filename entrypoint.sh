#!/bin/bash
set -e

# Run composer only if needed
if [ ! -f vendor/autoload.php ]; then
  echo "→ Installing composer dependencies..."
  composer install
fi