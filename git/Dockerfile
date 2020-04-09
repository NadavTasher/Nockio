# Select the base image
FROM debian:buster
# Install additional software
RUN apt-get -y update
RUN apt-get -y install openssh-server
# Set up SSH
EXPOSE 22
RUN mkdir /var/run/sshd
RUN echo "AuthorizedKeysFile /nockio/infrastructure/git/ssh" >> /etc/ssh/sshd_config
# Create the user
RUN useradd -m -d /nockio/infrastructure/git/sources -s /bin/bash nockio
# Entrypoint command
CMD ["/usr/sbin/sshd -D"]