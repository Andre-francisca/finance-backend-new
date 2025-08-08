FROM php:8.2-fpm-alpine
RUN apk add --no-cache \
    postgresql-dev \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/project

CMD ["php-fpm"]
