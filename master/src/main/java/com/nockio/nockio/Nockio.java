package com.nockio.nockio;

import com.github.dockerjava.api.DockerClient;
import com.github.dockerjava.core.DockerClientBuilder;
import com.nockio.structure.Application;

import java.util.List;

public abstract class Nockio {

    private static List<Application> applications;

    private static DockerClient client;

    public static void initialize() {
        // Create the docker client
        client = DockerClientBuilder.getInstance("unix:///var/run/docker.sock").build();
        // TODO: Load configurations
        // TODO: Start containers
    }


}
