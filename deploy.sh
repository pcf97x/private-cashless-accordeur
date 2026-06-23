#!/bin/bash
# Deploy script — L'Accordeur
# Exécuté automatiquement par GitHub Actions ou manuellement via SSH

set -e

APP_DIR="/var/www/accordeur"
BRANCH="main"

echo "🚀 Déploiement L'Accordeur..."

cd "$APP_DIR"

# 1. Pull latest code
echo "📥 Pull du code..."
git pull origin "$BRANCH"

# 2. Install PHP dependencies (no dev)
echo "📦 Composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Run migrations
echo "🗄️ Migrations..."
php artisan migrate --force

# 4. Build assets
echo "🎨 Build assets..."
npm ci --production=false
npm run build

# 5. Clear & optimize caches
echo "⚡ Optimisation..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Storage link (idempotent)
php artisan storage:link 2>/dev/null || true

# 7. Permissions
echo "🔐 Permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache public/storage
sudo chmod -R 775 storage bootstrap/cache

echo "✅ Déploiement terminé !"
