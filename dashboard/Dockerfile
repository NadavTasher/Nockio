# Select the base image
FROM php:7.4.1-apache
# Install additional software
RUN apt-get -y update
# Install git
RUN apt-get -y install git --no-install-recommends --no-install-suggests
# Enable modifications
RUN a2enmod headers
# Add www-data to root group
RUN usermod -aG root www-data
# Copy sources to /var/www/html
COPY src /var/www/html
# Change ownership & permissions of /var/www
RUN chown www-data /var/www/ -R && chmod 775 /var/www/ -R