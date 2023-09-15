FROM php:8.1-fpm-bullseye as backend-stage

LABEL maintainer="Mocard"

ARG UID
ARG GID

# Install services
RUN apt update \
    && apt install -y \
        g++ \
        git \
        curl \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        zip \
        zlib1g-dev \
        unzip \
        sudo \
        cron \
        nano \
    && pecl install xdebug \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl \
        opcache \
        pdo \
        pdo_mysql \
        sockets \
        mbstring \
        exif \
        pcntl \
        bcmath \
        shmop \
    && apt clean && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/backend

# Copy project files in work directory
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && addgroup --system sigma --gid ${GID} --shell /bin/bash \
    && adduser --system sigma --uid ${UID} --shell /bin/bash \
    && echo "sigma ALL=(ALL) NOPASSWD:ALL" | tee /etc/sudoers.d/sigma

USER ${UID}

ENTRYPOINT ["entrypoint.sh"]
