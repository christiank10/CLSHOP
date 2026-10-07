FROM php:8.2-apache

# Instala as extensões necessárias para o PostgreSQL no PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Ativa o mod_rewrite do Apache (útil para URLs amigáveis)
RUN a2enmod rewrite

# Copia todos os arquivos do projeto para o diretório web do Apache
COPY . /var/www/html/

# Ajusta as permissões dos arquivos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80