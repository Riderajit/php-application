# Use an official PHP runtime as the base image
FROM php:7.4-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install the PHP MySQL extension
RUN docker-php-ext-install mysqli

# Copy the application code into the container
COPY . /var/www/html

# Expose port 80 for Apache
EXPOSE 80

# Start the Apache web server
CMD ["apache2-foreground"]

