<?php
/**
 * file to create the user deletion form output
 * calls on relevant controller and container to facilitate this
 *
 **/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post(
    '/delete-user',
    function (Request $request, Response $response) use ($app) {

        $delete_user_controller = $app->getContainer()->get('deleteUserController');
        $view = $app->getContainer()->get('view');

        $delete_user_controller->createFormOutput($app, $view, $response);

    })->setName('delete-user');
