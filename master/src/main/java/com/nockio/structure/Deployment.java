package com.nockio.structure;

import java.util.List;

/**
 * This class represents a container in an application.
 */
public class Deployment {

    private String name;
    private String description;

    private String hostname;

    private List<Port> ports;
    private List<Rule> rules;

    private static class Port {

        private int hostPort;
        private int guestPort;

    }

    private static class Rule {

        private String name;

        private String hostProtocol;
        private String guestProtocol;

        private Port port;

    }

}
