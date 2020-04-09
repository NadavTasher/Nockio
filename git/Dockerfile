# Select the base image
FROM debian:buster
# Install additional software
RUN apt-get -y update
RUN apt-get -y install openssh-server --no-install-recommends --no-install-suggests
RUN apt-get -y install git
# Set up SSH
EXPOSE 22
RUN mkdir /var/run/sshd
RUN echo "StrictModes no" >> /etc/ssh/sshd_config
RUN echo "PasswordAuthentication no" >> /etc/ssh/sshd_config
RUN echo "AuthorizedKeysFile /nockio/infrastructure/git/ssh/authorized_keys" >> /etc/ssh/sshd_config
# Create the user
RUN useradd -M -d /nockio/infrastructure/git/ -s /bin/bash nockio
# Entrypoint command
CMD ["/usr/sbin/sshd", "-D"]