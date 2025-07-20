# Usa una imagen base oficial de PHP con FPM
# FPM (FastCGI Process Manager) es la mejor opción para servir PHP con Nginx
FROM php:8.2-fpm

# Instala las extensiones de PHP necesarias para la aplicación
# La extensión 'pdo_sqlite' es crucial para que la aplicación se conecte a la base de datos
RUN docker-php-ext-install pdo pdo_sqlite

# Instala Git y otras herramientas comunes si las necesitas
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install mbstring zip \
    && rm -rf /var/lib/apt/lists/*

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el archivo composer.json y composer.lock para instalar las dependencias
COPY composer.json composer.lock ./

# Instala las dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copia todo el código de tu aplicación al contenedor
COPY . .

# Establece los permisos de la carpeta
RUN chown -R www-data:www-data /var/www/html

# Expón el puerto 9000, que es el puerto por defecto de PHP-FPM
EXPOSE 9000

# El comando por defecto para ejecutar PHP-FPM
CMD ["php-fpm"]