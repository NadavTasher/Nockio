<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "api.php";

class Nockio
{

    // Constants
    public const API = "nockio";

    private static Database $database;

    private static Configuration $configuration;

    public static function initialize()
    {
        // Load configuration
        self::$configuration = new Configuration(self::API);
        // Load database
        self::$database = new Database(self::API);
        self::$database->createColumn("name");
        self::$database->createColumn("description");
        self::$database->createColumn("deployments");
    }

    public static function handle()
    {
        Base::handle(function ($action, $parameters) {
            // Requires authentication
            Authenticate::initialize();
            if ($action === "setup") {
                if (!self::$configuration->get("setup")) {
                    if (isset($parameters->name) && isset($parameters->password)) {
                        if (is_string($parameters->name) && isset($parameters->password)) {
                            Authenticate::signUp($parameters->name, $parameters->password);
                            self::$configuration->set("setup", true);
                            return [true, "Setup finished"];
                        }
                        return [false, "Invalid parameters"];
                    }
                    return [false, "Missing parameters"];
                }
                return [false, "Already set-up"];
            } else {
                // Check for token
                if (isset($parameters->token) && is_string($parameters->token)) {
                    $authentication = Authenticate::validate($parameters->token);
                    if ($authentication[0]) {
                        // Authenticated
                    }
                }
                return [false, "Authentication failure"];
            }
        });
    }

}