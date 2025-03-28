# Usar la imagen oficial de PHP con FPM
FROM php:8.3-fpm

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Copiar los archivos del proyecto al contenedor
COPY . .

# Dar permisos a la carpeta de almacenamiento y bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
