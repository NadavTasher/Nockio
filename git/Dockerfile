# Select the base image
FROM debian:buster
# Install additional software
RUN apt-get -y update
RUN apt-get -y install openssh-server
# Set up SSH
RUN mkdir /var/run/sshd
RUN echo "AuthorizedKeysFile /nockio/infrastructure/git/ssh" >> /etc/ssh/sshd_config
# Create the user
RUN useradd -m -d /nockio/infrastructure/git/sources -s /bin/bash nockio