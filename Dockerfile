FROM php:8.2-apache

RUN apt update;  \
    apt upgrade -y;
# Instalar composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

#Dependencias del sistema
RUN apt update

#Instalar librerias del sistema
RUN apt install -y libpq-dev libzip-dev libcurl4-openssl-dev libxml2-dev libonig-dev libpng-dev

# Instalar dependencias PECL de PHP
RUN pecl install raphf  \
    && docker-php-ext-enable raphf  \
    && pecl install pecl_http  \
    && docker-php-ext-enable http


#Instalar dependencias PEAR de PHP
RUN docker-php-ext-install pgsql pdo_pgsql zip mbstring curl xml pdo intl iconv gd
RUN docker-php-ext-enable pgsql zip mbstring curl xml pdo intl iconv gd

COPY composer.json /
RUN composer install

# Configurar apache
RUN sed -i -e 's|ServerName .*|ServerName localhost|' /etc/apache2/sites-available/000-default.conf
RUN sed -i -e 's|DocumentRoot .*|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i -e 's|<Directory .*|<Directory /var/www/html/public>|' /etc/apache2/sites-available/000-default.conf
RUN sed -i -e 's|AllowOverride .*|AllowOverride All|' /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite headers

# Puerto de escucha
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]

