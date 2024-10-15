# Utiliser l'image PHP avec NGINX
FROM php:7.4-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Copier le contenu de WordPress dans le conteneur
COPY ./wordpress /var/www/html

# Changer le propriétaire des fichiers
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80
