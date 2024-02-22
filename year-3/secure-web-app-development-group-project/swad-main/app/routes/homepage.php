<?php
/**
 * homepage.php
 * calls on container and controller for homepage
 *
 */


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->get('/', function(Request $request, Response $response) use ($app)
{
    $homepage_controller = $app->getContainer()->get('homepageController');
    $homepage_controller->createOutput($app, $response);

})->setName('homepage');