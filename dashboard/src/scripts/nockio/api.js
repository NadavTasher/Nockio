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
        // Find application pane
        let pane = UI.find("application-pane");
        // Clear the pane
        UI.clear(pane);
        // Fetch the application data
        API.call(NOCKIO_API, "printApplication", {
            application: name,
            token: Authenticate.token
        }, (status, result) => {
            if (status) {
                // Initialize the description
                let applicationDescription = "No description provided";
                let applicationServices = ["docker"];
                // Check the status
                if (result !== null) {
                    if (result.hasOwnProperty("description"))
                        applicationDescription = result.description;
                    if (result.hasOwnProperty("services"))
                        applicationServices = result.services;
                }
                // Create the views
                pane.appendChild(UI.create("information", {
                    name: "Name",
                    value: name
                }));
                pane.appendChild(UI.create("information", {
                    name: "Description",
                    value: applicationDescription
                }));
                pane.appendChild(UI.create("services", {
                    names: applicationServices.join(", "),
                    platformA: applicationServices[0].toLowerCase(),
                    platformB: applicationServices[1].toLowerCase(),
                    platformC: applicationServices[2].toLowerCase(),
                }));
                UI.view(pane);
            }
        });
    }

    static loading() {
        UI.view("loading-pane");
    }


}