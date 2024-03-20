FROM php:latest

RUN apt-get update

RUN apt-get install -y  \
    libmcrypt-dev  \
    libjpeg-dev  \
    libpng-dev  \
    libfreetype6-dev  \
    zlib1g-dev  \
    libzip-dev \
    libonig-dev \
    openssl \
    fontconfig \
    libxrender1 \
    xfonts-75dpi \
    xfonts-base \
    wget \
    libxext6 \
    && rm -rf /var/lib/apt/lists/*

# Installe wkhtmltopdf
RUN wget -q https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-3/wkhtmltox_0.12.6.1-3.bullseye_arm64.deb \
    && dpkg -i wkhtmltox_0.12.6.1-3.bullseye_arm64.deb \
    && apt-get -f install -y \
    && rm wkhtmltox_0.12.6.1-3.bullseye_arm64.deb

RUN docker-php-ext-configure \
    gd --with-freetype --with-jpeg

RUN docker-php-ext-install -j$(nproc) \
    mbstring \
    gd \
    iconv \
    zip

# Définir un nouvel utilisateur
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN groupadd -g $GROUP_ID appuser && useradd -u $USER_ID -g appuser -s /bin/sh -m appuser

# Définir le répertoire de travail
WORKDIR /app

# Changer le propriétaire des fichiers
RUN chown -R appuser:appuser /app

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer


# Passer à l'utilisateur appuser
USER appuser

WORKDIR /app
COPY app /app
RUN composer install
# RUN rm composer.lock
# RUN composer install --no-dev --optimize-autoloader
# RUN composer update

# COPY nginx.conf /etc/nginx/conf.d/default.conf
# RUN rm -rf /var/www/html/*

# CMD service php-fpm start && nginx -g "daemon off;"
CMD php -S 0.0.0.0:80 -t /app
