package com.nockio.structure;

import com.github.dockerjava.api.DockerClient;

import java.util.List;

/**
 * This class represents a Nockio application.
 */
public class Application {

    private String name;

    private List<Deployment> deployments;

    public void deploy(DockerClient client) {
        // Build the image
        createImage(client);
        // Create the network
        createNetwork(client);
    }

    private void createImage(DockerClient client) {
        File baseDir = new File("~/kpelykh/docker/netcat");

        BuildImageResultCallback callback = new BuildImageResultCallback() {
            @Override
            public void onNext(BuildResponseItem item) {
                System.out.println("" + item);
                super.onNext(item);
            }
        };

        dockerClient.buildImageCmd(baseDir).exec(callback).awaitImageId();
    }

    private void createNetwork(DockerClient client) {
        // Create the network
        client.createNetworkCmd().withName("application-" + name.toLowerCase()).withDriver("bridge").exec();
    }

    private void createContainer(DockerClient client){
        // TODO: Create a new container with the configuration
        // TODO: Add application network, proxy network
    }
}
