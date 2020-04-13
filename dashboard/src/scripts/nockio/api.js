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
        // Send the API call
        API.call(NOCKIO_API, "listApplications", {
            token: Authenticate.token
        }, (status, result) => {
            if (status) {
                // Find the list
                let list = UI.find("applications-pane");
                // Clear the list
                UI.clear(list);
                // Loop over array
                for (let applicationName of result) {
                    let applicationView = document.createElement("div");
                    // Add class
                    applicationView.classList.add("application");
                    // Add paragraphs
                    let applicationNameView = document.createElement("p");
                    applicationNameView.innerText = applicationName;
                    applicationView.appendChild(applicationNameView);
                }
                // Change the pane
                UI.view(list);
            }
        });
    }

    static loadApplication(name) {

    }

    static template(name) {
        // Find document templates
        let templatesElement = document.getElementsByTagName("templates")[0];
        if (templatesElement !== undefined) {
            let templateElement = undefined;
            for (let template of templatesElement.children) {
                if (template.tagName === name) {
                    templateElement = template;
                }
            }
            if (templateElement !== undefined) {
                return function (parameters) {

                };
            }
        }
    }

}