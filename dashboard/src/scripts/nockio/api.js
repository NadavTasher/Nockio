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

}