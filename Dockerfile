# Utilisation de l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installation des dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Installation des extensions PHP pour MySQL
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail
WORKDIR /var/www/html

# Copie du projet dans le container
COPY . .

# Installation des dépendances Laravel (Vendor)
RUN composer install --no-dev --optimize-autoloader

# Configuration d'Apache pour pointer sur le dossier /public de Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Donner les permissions aux dossiers de stockage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

CMD php artisan migrate --force && apache2-foreground
