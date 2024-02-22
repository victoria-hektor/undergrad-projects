<?php
/**
 * file to create the user creation form output
 * calls on relevant controller and container to facilitate this
 *
 **/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post(
    '/create-user',
    function (Request $request, Response $response) use ($app) {

        $create_user_controller = $app->getContainer()->get('createUserController');
        $view = $app->getContainer()->get('view');

        $create_user_controller->createFormOutput($app, $view, $response);

    })->setName('create-user');
