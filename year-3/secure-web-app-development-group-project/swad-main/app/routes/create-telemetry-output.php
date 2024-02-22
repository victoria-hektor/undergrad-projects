<?php

/**
 * file to create the telemetry output
 * calls on relevant controller and container to facilitate this
 **/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->post('/create-telemetry-output', function(Request $request, Response $response) use ($app)
{
    $create_telemetry_controller = $app->getContainer()->get('createTelemetryController');
    $create_telemetry_controller->createMessageSentOutput($app, $request, $response);

})->setName('create-telemetry-output');