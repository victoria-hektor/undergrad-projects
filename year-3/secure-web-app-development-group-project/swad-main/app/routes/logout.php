<?php
/**
 * file to facilitate a logout feature
 * calls on the corresponding controller and container
 **/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->get('/logout', function(Request $request, Response $response) use ($app)
{
    $logout_controller = $app->getContainer()->get('logoutController');
    $logout_controller->logoutUser($app, $response);


})->setName('logout');