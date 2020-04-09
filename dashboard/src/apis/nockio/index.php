<?php
// Include the authenticate API
include_once __DIR__ . DIRECTORY_SEPARATOR . "api.php";

// Initialize the API
Nockio::initialize();

// Handle the API call
Nockio::handle();