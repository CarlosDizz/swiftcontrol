# Usar la imagen oficial de PHP con FPM
FROM php:8.3-fpm

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    zip unzip git curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev zlib1g-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        intl \
        zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Copiar los archivos del proyecto al contenedor
COPY . .

# Dar permisos a storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
