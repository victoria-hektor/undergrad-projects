<?php
/**
 * Bootstrap file performing the following tasks:
 * -    Requires the autoloader (autoloader.php)
 * -    Defines the settings variable by requiring settings.php
 * -    Instantiates the container - passes it the settings
 * -    Requires the dependencies
 * -    Instantiates the SLIM App
 * -    requires the routes.php file
 * -    Then runs the SLIM app
 */

require '/p3t/phpappfolder/includes/vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';

$container = new \Slim\Container($settings);

require __DIR__ . '/app/dependencies.php';

$app = new \Slim\App($container);

require __DIR__ . '/app/routes.php';


$app->run();