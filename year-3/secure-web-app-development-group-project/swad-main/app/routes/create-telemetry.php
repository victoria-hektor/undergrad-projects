<?php
/**
 *
 * file to create telemetry input
 * calls on controller and container to facilitate
 *
 **/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->post('/create-telemetry', function(Request $request, Response $response) use ($app)
{
    $create_telemetry_controller = $app->getContainer()->get('createTelemetryController');
    $create_telemetry_controller->createOutput($app, $response);

})->setName('create-telemetry');