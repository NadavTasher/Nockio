# Select the base image
FROM httpd:2.4.43

# Expose ports
EXPOSE 80
EXPOSE 443

# Configure nginx
COPY configurations/proxy/httpd.conf /usr/local/apache2/conf/httpd.conf