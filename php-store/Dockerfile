FROM php:8.0-apache

# Remove default index
RUN rm -f /var/www/html/index.html

# Install necessary PHP extensions and enable Apache modules
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    a2enmod rewrite

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy your PHP file to the webroot
COPY index.php /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html && chmod 755 /var/www/html

# Test PHP configuration
RUN php -v && \
    apache2ctl -M | grep php

EXPOSE 80

CMD ["apache2-foreground"] 