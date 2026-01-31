#!/usr/bin/env bash
set -o errexit

# Installation des dépendances
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Nettoyage COMPLET des caches pour forcer Brevo
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Migration de la base de données
php artisan migrate --force

# Mise en cache propre (Optionnel mais recommandé pour la vitesse)
php artisan config:cache