<?php
/**
 * file to create the user creation output
 * calls on relevant controller and container to facilitate this
 *
 **/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post(
    '/create-user-output',
    function (Request $request, Response $response) use ($app) {

        $create_user_controller = $app->getContainer()->get('createUserController');
        $view = $app->getContainer()->get('view');

        $create_user_controller->createOutput($app, $view, $response, $request);

    })->setName('create-user-output');
