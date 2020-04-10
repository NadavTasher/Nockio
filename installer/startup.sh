#!/bin/bash

NOCKIO_DIRECTORY=/home/nadav/Documents/Nockio/

# Pull images
# TODO

# Create networks
docker network create -d bridge nockio-infrastructure
docker network create -d bridge nockio-applications

# Start containers
docker run \
  --name nockio-dashboard \
  --volume NOCKIO_DIRECTORY/git:/var/lib/nockio/git \
  --volume NOCKIO_DIRECTORY/proxy:/var/lib/nockio/proxy \
  --hostname nockio-dashboard \
  --network nockio-infrastructure \
  --detach nockio-dashboard-test

docker run \
  --name nockio-
