# docker/php-fpm/Dockerfile

FROM php:fpm-alpine
COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it
RUN apk --update --no-cache add git
RUN apk --no-cache update \
        && apk --no-cache upgrade \
        && apk add --no-cache \
                freetype \
                libpng \
                freetype-dev \
                libpng-dev \
                jpeg-dev \
                libjpeg \
                libjpeg-turbo-dev \
                wget \
                zlib-dev \
                ttf-freefont \
                fontconfig \
                xvfb \
                libxrender-dev \
                gettext \
                gettext-dev \
                libxml2-dev \
                gnu-libiconv-dev \
                autoconf \
                g++ \
                git \
                bash \
                wkhtmltopdf \
                postgresql-dev
RUN docker-php-ext-install pdo_pgsql
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD  php-fpm
EXPOSE 9000
