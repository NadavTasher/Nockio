# Select the base image
FROM debian:buster

# Expose ports
EXPOSE 22

# Install additional software
RUN apt-get -y update
RUN apt-get -y install git --no-install-recommends --no-install-suggests
RUN apt-get -y install curl --no-install-recommends --no-install-suggests
RUN apt-get -y install openssh-server --no-install-recommends --no-install-suggests

# Install docker-compose
RUN curl -skL "https://github.com/docker/compose/releases/download/1.25.4/docker-compose-Linux-x86_64" -o /usr/local/bin/docker-compose
RUN chmod +x /usr/local/bin/docker-compose

# Set up Git
RUN mkdir -p /etc/git
# Copy hooks
COPY configurations/git/hooks /etc/git/hooks
RUN chmod +x -R /etc/git/hooks
# Change configuration
RUN git config --system core.hooksPath /etc/git/hooks
RUN git config --system receive.denyCurrentBranch updateInstead

# Set up SSH
RUN mkdir /var/run/sshd
# Change configuration
COPY configurations/ssh/sshd_config /etc/ssh/sshd_config

# Create the user
RUN useradd -M -d /var/lib/nockio/git/sources/ -s /bin/bash nockio

# Entrypoint command
CMD ["/usr/sbin/sshd", "-D"]