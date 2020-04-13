/**
 * Copyright (c) 2020 Nadav Tasher
 * https://github.com/NadavTasher/Nockio/
 **/

const NOCKIO_API = "nockio";

class Nockio {

    static setUp(callback = null) {
        // Hide the inputs
        UI.hide("authenticate-inputs");
        // Change the output message
        Authenticate.output("Hold on - Setting up...");
        // Send the API call
        API.call(NOCKIO_API, "setUp", {
            password: UI.find("authenticate-password").value
        }, (status, result) => {
            if (status) {
                // Call the signin function
                Authenticate.signIn(callback);
            } else {
                // Show the inputs
                UI.show("authenticate-inputs");
                // Change the output message
                Authenticate.output(result, true);
            }
        });
    }

    static loadApplications() {
        this.loading();
        // Send the API call
        API.call(NOCKIO_API, "listApplications", {
            token: Authenticate.token
        }, (status, result) => {
            if (status) {
                // Find the list
                let list = UI.find("applications-list");
                // Clear the list
                UI.clear(list);
                // Loop over array
                for (let applicationName of result) {
                    // Fetch the application data
                    API.call(NOCKIO_API, "printApplication", {
                        application: applicationName,
                        token: Authenticate.token
                    }, (status, result) => {
                        // Initialize the description
                        let applicationDescription = "No description provided";
                        let applicationPlatform = "docker";
                        // Check the status
                        if (status) {
                            if (result !== null) {
                                if (result.hasOwnProperty("description"))
                                    applicationDescription = result.description;
                                if (result.hasOwnProperty("services"))
                                    if (result.services.length > 0)
                                        applicationPlatform = result.services[0];
                            }
                        }
                        // Create the view
                        list.appendChild(UI.create("application", {
                            name: applicationName,
                            description: applicationDescription,
                            platform: applicationPlatform.toLowerCase()
                        }));
                    });
                }
                // Change the pane
                UI.view("applications-pane");
            }
        });
    }

    static loadApplication(name) {
        this.loading();
        // Fetch the application data
        API.call(NOCKIO_API, "printApplication", {
            application: name,
            token: Authenticate.token
        }, (status, result) => {
            if (status) {
                // Initialize the description
                let description = "No description provided";
                let services = [];
                // Check the status
                if (result !== null) {
                    if (result.hasOwnProperty("description"))
                        description = result.description;
                    if (result.hasOwnProperty("services"))
                        services = result.services;
                }
                // Create the views
                UI.find("application-name").innerText = "Name - " + name;
                UI.find("application-description").innerText = "Description - " + description;
                UI.find("application-services").innerText = services.join(", ");
                // Fetch the log
                API.call(NOCKIO_API, "logApplication", {
                    application: name,
                    token: Authenticate.token
                }, (status, result) => {
                    let list = UI.find("application-log");
                    // Clear list
                    UI.clear(list);
                    // Split log
                    let lines = result.split("\n");
                    // Add all
                    for (let line of lines) {
                        list.appendChild(UI.create("log", {text: line}));
                    }
                });
                // Set the pane
                UI.view("application-pane");
            }
        });
    }

    static loading() {
        UI.view("loading-pane");
    }


}