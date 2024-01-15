#########
# Build and push this docker image to the gitlab docker registry when updated to ensure an accurate CI/CD pipeline
#
# docker build -t registry.gitlab.com/juicy-media-ltd/ukpid/ukpid-backend/laravel . -f .docker/dockerfiles/laravel/Dockerfile
# docker push registry.gitlab.com/juicy-media-ltd/ukpid/ukpid-backend/laravel
#########

FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    linux-headers \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng libpng-dev \
    libpng \
    libpng-dev \
    libzip-dev \
    zip \
    curl-dev \
    openssl-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install  \
    pdo \
    pdo_mysql \
    sockets \
    bcmath \
    gd \
    zip

RUN pecl install mongodb
RUN echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini;


# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini";

# Copy the laravel files
COPY application /var/www/html/
VOLUME ["/var/www/html"]

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer install --no-interaction

ARG WITH_XDEBUG=false

RUN if [ $WITH_XDEBUG = "true" ] ; then \
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
fi ;

