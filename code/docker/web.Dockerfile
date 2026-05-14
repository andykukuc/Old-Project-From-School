FROM php:8.1-apache

# Install dependencies
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get update --fix-missing && \
    apt-get install -y --no-install-recommends \
    libzip-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    openssl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo_mysql mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod ssl rewrite headers

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Generate self-signed SSL cert for 10.20.20.75
RUN openssl req -new -newkey rsa:4096 -x509 -sha256 -days 365 -nodes \
    -out /etc/ssl/certs/MyCertificate.crt \
    -keyout /etc/ssl/private/MyKey.key \
    -subj "/C=US/ST=IL/L=Chicago/O=Untitled Logistics/CN=10.20.20.75" \
    -addext "subjectAltName=IP:10.20.20.75"

# Configure Apache
COPY docker/apache-ssl.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 443
