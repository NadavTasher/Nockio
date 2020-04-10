# Select the base image
FROM debian:buster
# Install additional software
RUN apt-get -y update
RUN apt-get -y install openssh-server git --no-install-recommends --no-install-suggests
# Set up SSH
EXPOSE 22
RUN mkdir /var/run/sshd
# Copy configuration
COPY configurations/sshd_config /etc/ssh/sshd_config
# Create the user
RUN useradd -M -d /var/lib/nockio/git/sources/ -s /bin/bash nockio
# Entrypoint command
CMD ["/usr/sbin/sshd", "-D"]