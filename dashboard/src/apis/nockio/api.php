<?php

/**
 * Copyright (c) 2020 Nadav Tasher
 * https://github.com/NadavTasher/Nockio/
 **/

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "api.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "authenticate" . DIRECTORY_SEPARATOR . "api.php";

/**
 * Nockio API for application management.
 */
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
    private const DIRECTORY_GIT_SOURCES = self::DIRECTORY_GIT . DIRECTORY_SEPARATOR . "sources";

    public static function initialize()
    {
        // Requires authentication
        Authenticate::initialize();
    }

    public static function handle()
    {
        // Handle the request
        Base::handle(function ($action, $parameters) {
            if ($action === "setUp") {
                if (isset($parameters->password)) {
                    if (is_string($parameters->password)) {
                        return Authenticate::signUp("Administrator", $parameters->password);
                    }
                    return [false, "Invalid parameters"];
                }
                return [false, "Missing parameters"];
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
                            // Check for invalid app names
                            $filtered = [];
                            // Filter applications
                            foreach ($paths as $path) {
                                // Filter
                                if ($path[0] === ".") continue;
                                // Add to array
                                array_push($filtered, $path);
                            }
                            // Return the array
                            return [true, $filtered];
                        } else if ($action === "createApplication") {
                            if (isset($parameters->application)) {
                                if (is_string($parameters->application)) {
                                    $applicationName = basename($parameters->application);
                                    return self::applicationCreate($applicationName);
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "printApplication") {
                            if (isset($parameters->application)) {
                                if (is_string($parameters->application)) {
                                    $applicationName = basename($parameters->application);
                                    return self::applicationPrint($applicationName);
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "logApplication") {
                            if (isset($parameters->application)) {
                                if (is_string($parameters->application)) {
                                    $applicationName = basename($parameters->application);
                                    return self::applicationLog($applicationName);
                                }
                                return [false, "Invalid parameters"];
                            }
                            return [false, "Missing parameters"];
                        } else if ($action === "addKey") {
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
        if (file_exists(Utility::evaluatePath($applicationName, self::DIRECTORY_GIT_SOURCES))) {
            return true;
        }
        return false;
    }

    private static function applicationCreate($applicationName)
    {
        // Make sure the path does not exist
        if (!self::applicationExists($applicationName)) {
            // Create a new Git repository
            $repositoryDirectory = Utility::evaluatePath("$applicationName", self::DIRECTORY_GIT_SOURCES);
            // Create the target directory
            mkdir($repositoryDirectory, 0777, true);
            // Create the repository
            shell_exec("cd $repositoryDirectory && git init");
            // Change the permissions
            shell_exec("chmod 777 -R $repositoryDirectory");
            // Return the contents
            return [true, null];
        }
        return [false, "Application already exists"];
    }

    private static function applicationPrint($applicationName)
    {
        // Make sure the path exists
        if (self::applicationExists($applicationName)) {
            if (file_exists($applicationFile = Utility::evaluatePath("$applicationName:.application.nockio", self::DIRECTORY_GIT_SOURCES))) {
                // Return the contents
                return [true, json_decode(file_get_contents($applicationFile))];
            }
            // Not deployed yet
            return [true, null];
        }
        return [false, "Application does not exist"];
    }

    private static function applicationLog($applicationName)
    {
        // Make sure the path exists
        if (self::applicationExists($applicationName)) {
            if (file_exists($logFile = Utility::evaluatePath("$applicationName:.log.compose.nockio", self::DIRECTORY_GIT_SOURCES))) {
                // Return the contents
                return [true, file_get_contents($logFile)];
            }
            // Not deployed yet
            return [true, null];
        }
        return [false, "Application does not exist"];
    }
}