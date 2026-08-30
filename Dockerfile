FROM php:8.2-cli

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

COPY . /var/www/html/

WORKDIR /var/www/html

RUN rm -rf public/customer public/admin \
    && ln -s ../customer public/customer \
    && ln -s ../admin public/admin \
    && mkdir -p logs && chmod -R 755 public/uploads

EXPOSE 8000

CMD ["sh", "-c", "php -d display_errors=1 -d error_reporting=E_ALL -S 0.0.0.0:${PORT:-8000} router.php 2>&1"]
