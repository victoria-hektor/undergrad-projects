<?php
/**
 * file to process the telemetry data
 * Calls on controller and container
 **/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post(
    '/process-telemetry',
    function(Request $request, Response $response) use ($app) {

        $process_telemetry_controller = $app->getContainer()->get('processTelemetryController');
        $process_telemetry_controller->createOutput($app, $request, $response);

    });
