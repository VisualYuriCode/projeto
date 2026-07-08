FROM php:8.2-apache

# Extensões que o projeto precisa (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Habilita o mod_rewrite (seu .htaccess depende disso)
RUN a2enmod rewrite

# Aponta o Apache pra pasta public/ em vez da raiz do projeto
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Composer (copiado da imagem oficial, sem precisar instalar via curl)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN composer install