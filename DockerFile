# Utilisation d'une image officielle PHP avec Apache
FROM php:8.1-apache

# Installation des dépendances de Composer
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Activer les modules Apache
RUN a2enmod rewrite

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier les fichiers du projet dans le conteneur
COPY . .

# Installer les dépendances Laravel avec Composer
RUN composer install --no-dev --optimize-autoloader

# Installer les dépendances front-end
RUN npm install --production
RUN npm run prod

# Définir les droits d'accès sur les dossiers de stockage
RUN chown -R www-data:www-data storage bootstrap/cache

# Exposer le port d'Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
