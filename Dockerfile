FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libmcrypt-dev  \
    libjpeg-dev  \
    libfreetype6-dev  \
    zlib1g-dev  \
    libzip-dev \
    openssl \
    fontconfig \
    libxrender1 \
    xfonts-75dpi \
    xfonts-base \
    wget \
    libxext6 \
    wkhtmltopdf

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl iconv zip

# Enable wkhtmltopdf
RUN ln -s /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/tabiblib-api

USER $user
