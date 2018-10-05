<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
declare(strict_types = 1);
namespace ExampleApp;

define('ROOT', dirname(__DIR__));
require ROOT . '/vendor/autoload.php';

use \Slim\App;

if (PHP_SAPI == 'cli') {
    session_cache_limiter('0');
    session_start();
    // Instantiate the app
    $settings = require ROOT . '/src/settings.php';
    $app = new \Slim\App($settings);

    // Set up dependency factory
    require ROOT . '/src/dependencies.php';
    // Register middleware
    require ROOT . '/src/middleware.php';
    // Register routes
    require ROOT . '/src/route.php';

    // Run app
    $app->run();
} else {
    die('I think you\'re lost.. :)');
}
