<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "api.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "authenticate" . DIRECTORY_SEPARATOR . "api.php";

class Nockio
{

    // Constants
    public const API = "nockio";

    // Defaults
    private const DEFAULT_USER = "Administrator";

    // Docker socket path
    private const DOCKER_SOCKET = DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "run" . DIRECTORY_SEPARATOR . "docker.sock";

    // Directory roots
    private const DIRECTORY_ROOT = DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "nockio";

    // Subdirectory roots
    private const DIRECTORY_GIT = self::DIRECTORY_ROOT . DIRECTORY_SEPARATOR . "git";
    private const DIRECTORY_GIT_SOURCES = self::DIRECTORY_ROOT . DIRECTORY_SEPARATOR . "git" . DIRECTORY_SEPARATOR . "sources";

    public static function initialize()
    {
    }

    public static function handle()
    {
        Base::handle(function ($action, $parameters) {
            // Requires authentication
            Authenticate::initialize();
            if ($action === "setUp") {
                if (Authenticate::signUp(self::DEFAULT_USER, null)[1] !== "User already exists") {
                    if (isset($parameters->password)) {
                        if (is_string($parameters->password)) {
                            Authenticate::signUp("Administrator", $parameters->password);
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
                        if ($action === "listApplications") {
                            // List the directories in the host directory
                            $hostDirectory = Utility::evaluatePath("", self::DIRECTORY_GIT_SOURCES);
                            // Array of files
                            $paths = scandir($hostDirectory);
                            // Remove "." and ".."
                            $paths = array_slice($paths, 2);
                            // Return the array
                            return [true, $paths];
                        } else if ($action === "listDeployments") {
                            if (isset($parameters->application)) {
                                if (is_string($parameters->application)) {
                                    $applicationName = basename($parameters->application);
                                    // Make sure the application exists
                                    if ($applicationDirectory = self::applicationExists($applicationName)) {
                                        // Array of files
                                        $paths = scandir($applicationDirectory);
                                        // Remove "." and ".."
                                        $paths = array_slice($paths, 2);
                                        // Return the array
                                        return [true, $paths];
                                    }
                                    return [false, "Application does not exist"];
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "printDeployment") {
                            if (isset($parameters->application) && isset($parameters->deployment)) {
                                if (is_string($parameters->application) && is_string($parameters->deployment)) {
                                    $applicationName = basename($parameters->application);
                                    $deploymentName = basename($parameters->deployment);
                                    return self::deploymentPrint($applicationName, $deploymentName);
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "createDeployment") {
                            if (isset($parameters->application) && isset($parameters->deployment)) {
                                if (is_string($parameters->application) && is_string($parameters->deployment)) {
                                    $applicationName = basename($parameters->application);
                                    $deploymentName = basename($parameters->deployment);
                                    return self::deploymentCreate($applicationName, $deploymentName);
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "deployDeployment") {
                            if (isset($parameters->application) && isset($parameters->deployment)) {
                                if (is_string($parameters->application) && is_string($parameters->deployment)) {
                                    $applicationName = basename($parameters->application);
                                    $deploymentName = basename($parameters->deployment);
                                    return self::deploymentDeploy($applicationName, $deploymentName);
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "addPublicKey") {
                            if (isset($parameters->key) && is_string($parameters->key)) {
                                // Find authorized_keys path
                                $targetFilePath = self::DIRECTORY_GIT . DIRECTORY_SEPARATOR . "ssh" . DIRECTORY_SEPARATOR . "authorized_keys";
                                // Read contents
                                $contents = "";
                                if (file_exists($targetFilePath)) {
                                    $contents = file_get_contents($targetFilePath);
                                }
                                // Append key
                                $contents .= $parameters->key;
                                $contents .= "\n";
                                // Write file
                                file_put_contents($targetFilePath, $contents);
                                return [true, null];
                            }
                            return [false, "Missing parameters"];
                        }
                        return [false, "Unknown hook"];
                    }
                }
                return [false, "Authentication failure"];
            }
        });
    }

    private static function applicationExists($applicationName)
    {
        if (file_exists($path = Utility::evaluatePath("$applicationName", self::DIRECTORY_GIT_SOURCES))) {
            return [true, $path];
        }
        return [false, "Application does not exist"];
    }

    private static function deploymentExists($applicationName, $deploymentName)
    {
        if (self::applicationExists($applicationName)[0]) {
            $path = Utility::evaluatePath("$applicationName:$deploymentName", self::DIRECTORY_GIT_SOURCES);
            return [file_exists($path), $path];
        }
        return [false, "Application does not exist"];
    }

    private static function deploymentCreate($applicationName, $deploymentName)
    {
        // Make sure the path does not exist
        if (self::applicationExists($applicationName)[0]) {
            if (!($deploymentExists = self::deploymentExists($applicationName, $deploymentName))[0]) {
                // Create a new Git repository
                $repositoryDirectory = $deploymentExists[1];
                // Create the target directory
                mkdir($repositoryDirectory, 0777, true);
                // Create the repository
                shell_exec("git init --bare $repositoryDirectory");
                // Change the permissions
                shell_exec("chmod 777 -R $repositoryDirectory");
                // Return the contents
                return [true, null];
            }
            return [false, "Deployment already exists"];
        }
        return [false, "Application does not exist"];
    }

    private static function deploymentPrint($applicationName, $deploymentName)
    {
        // Make sure the path exists
        if (($deploymentDirectory = self::deploymentExists($applicationName, $deploymentName))[0]) {
            if (file_exists($deploymentFile = Utility::evaluatePath(".nockio", $deploymentDirectory))) {
                // Return the contents
                return [true, json_decode(file_get_contents($deploymentFile))];
            }
            // Not deployed yet
            return [true, null];
        }
        return [false, "Deployment does not exist"];
    }

    private static function deploymentDeploy($applicationName, $deploymentName)
    {
        // Make sure the deployment exists
        if (($deploymentExists = self::deploymentExists($applicationName, $deploymentName))[0]) {
            // Load the .nockio file
            if (($deploymentPrint = self::deploymentPrint($applicationName, $deploymentName))[0]) {
                if ($deploymentPrint[1]) {
                    $configuration = $deploymentPrint[1];
                    // Initialize flags

                }
            }
            return [false, "Missing deployment configuration (.nockio file)"];
        }
        return [false, "Deployment does not exist"];
    }
}