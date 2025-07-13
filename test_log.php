<?php

// Test log functionality
require_once './vendor/autoload.php';

// Initialize CodeIgniter
$pathsConfig = new \Config\Paths();
$request = \Config\Services::request();
$response = \Config\Services::response();

// Set up the application
$app = new \CodeIgniter\CodeIgniter(
    new \Config\App(),
    $pathsConfig,
    $request,
    $response
);

// Initialize environment
\CodeIgniter\Boot::bootWeb($pathsConfig);

// Test logging
log_message('info', 'Test log message from standalone script');
log_message('debug', 'Debug log message');
log_message('error', 'Error log message');

echo "Log test completed. Check writable/logs directory for log files.\n";
