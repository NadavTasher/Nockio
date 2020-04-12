# Select the base image
FROM debian:buster
# Expose ports
EXPOSE 22
EXPOSE 80
EXPOSE 443
# Install additional software
RUN apt-get -y update
RUN apt-get -y install git --no-install-recommends --no-install-suggests
RUN apt-get -y install nginx --no-install-recommends --no-install-suggests
RUN apt-get -y install openssh-server --no-install-recommends --no-install-suggests
# Install docker-compose
RUN apt-get -y install python3 python3-pip --no-install-recommends --no-install-suggests
RUN pip3 install setuptools
RUN pip3 install docker-compose
# Set up Supervisor
COPY configurations/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
# Set up SSH
RUN mkdir /var/run/sshd
COPY configurations/ssh/sshd_config /etc/ssh/sshd_config
# Set up nginx
COPY configurations/proxy/nginx.conf /etc/nginx/nginx.conf
# Create the user
RUN useradd -M -d /var/lib/nockio/git/sources/ -s /bin/bash nockio
# Entrypoint command
CMD ["supervisord"]