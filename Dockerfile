FROM php:8.2-apache

# Instalar utilidades del sistema y extensiones necesarias para la base de datos
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Habilitar el módulo rewrite de Apache (necesario para el .htaccess)
RUN a2enmod rewrite

# Copiar Composer desde su imagen oficial externa
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copiar primero los archivos de configuración de Composer para aprovechar la caché de Docker
COPY composer.json composer.lock* ./

# Instalar las dependencias de Composer sin scripts de desarrollo ni optimizaciones pesadas
RUN composer install --no-dev --no-scripts --prefer-dist --no-autoloader

# Copiar el resto del código del proyecto al contenedor
COPY . .

# Generar el autoloader definitivo optimizado
RUN composer dump-autoload --optimize

# Asegurar que Apache tenga los permisos correctos sobre los archivos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
