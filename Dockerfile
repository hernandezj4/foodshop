FROM php:8.2-cli

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

COPY . /var/www/html/

WORKDIR /var/www/html

RUN chmod -R 755 /var/www/html/public/uploads

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "router.php"]
