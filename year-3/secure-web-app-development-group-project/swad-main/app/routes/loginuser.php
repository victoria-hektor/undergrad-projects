<?php
/**
 *
 * file to deal with logging in a user
 * requires autoload.php
 * starts a session each time
 * function to (validate) parse and clean params
 * calls on containers, validates and cleans input, returns error or success
 *
 * @checkSession - returns:  result
 **/

#namespace SQL;
#namespace Telemetry\Validate\FormValidator;

#namespace TelemetryModel;
require_once '/p3t/phpappfolder/includes/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

session_start();

$app->post(
    '/dashboard',
    function (Request $request, Response $response) use ($app) {


        $login_controller = $app->getContainer()->get('LoginUserController');
        $session_exists = checkSession($app, 'username');

        $login_controller->createOutput($app, $request, $response, $session_exists);

    });


function checkSession($app, $key): bool
{
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $result = $session_wrapper->isSessionValueSet($key);

    return $result;
}


