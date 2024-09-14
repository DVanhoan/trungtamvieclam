# Sử dụng image PHP chính thức
FROM php:8.2-fpm

# Cài đặt các tiện ích và phần mở rộng cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
build-essential \
libpng-dev \
libjpeg62-turbo-dev \
libfreetype6-dev \
locales \
zip \
unzip \
git \
curl \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install gd pdo pdo_mysql

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tạo thư mục làm việc
WORKDIR /var/www

# Copy toàn bộ dự án vào container
COPY . /var/www

# Cài đặt các gói PHP qua Composer
RUN composer install

# Chỉnh quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 và khởi động PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
