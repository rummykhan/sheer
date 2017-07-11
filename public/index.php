<?php

// Get and autoload vendors and application classes
require '../vendor/autoload.php';

// Create Main Application Container
$application = new \App\Sheer\Application();

// Create Exception Handler for the Application
$handler = new \App\Exceptions\Handler();

// Set Handler handle function to exception handler
set_exception_handler([$handler, 'handle']);

// Check if URL is matched or not.
if ($application->urlMatched()) {

    // If URL matches the Route Collection Send the response
    return $application->sendResponse();
}


// If URL is not matched throw an exception
throw new Exception('Route not found!');