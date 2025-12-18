FROM php:8.1-apache

# Instalar dependencias del sistema para Postgres (libpq-dev)
RUN apt-get update && apt-get install -y libpq-dev

# Instalar extensión PHP para Postgres
RUN docker-php-ext-install pdo pdo_pgsql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# --- CONFIGURACIÓN PRO (Para servir desde /public) ---
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf