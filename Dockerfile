FROM php:8.4-apache
RUN docker-php-ext-install mysqli

RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY . /var/www/html
#WORKDIR /usr/src/myapp
#CMD [ "php", "./index.php" ]
