FROM debian:buster
# Expose ports
EXPOSE 80
EXPOSE 443
# Create volume
RUN mkdir /nockio
RUN mkdir /nockio/certificates
RUN mkdir /nockio/configurations
VOLUME /nockio
# Install apache2
RUN apt-get -y update
RUN apt-get -y install apache2
# Enable modifications
RUN a2enmod ssl proxy proxy_http headers
# Disable default configurations
RUN a2dissite 000-default default-ssl
# Remove default configurations
RUN rm /etc/apache2/sites-available/*
# Copy custom configurations
COPY configurations/proxy.conf /etc/apache2/sites-available/
# TODO: Proxy for the dashboard
# Enable custom configuration
RUN a2ensite proxy
# Entrypoint command
CMD apachectl -D FOREGROUND