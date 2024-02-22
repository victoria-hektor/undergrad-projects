<?php
/**
 * file to obtain the telemetry data
 * calls on the controller and container
 **/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/obtain-telemetry', function(Request $request, Response $response) use ($app)
{
    $obtain_telemetry_controller = $app->getContainer()->get('obtainTelemetryController');
    $obtain_telemetry_controller->createOutput($app, $response);

})->setName('obtain-telemetry');

