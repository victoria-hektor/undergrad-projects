<?php
/**
 * file to output a failure or success message
 * calls on relevant Twig files 
 * Calls on standard css, boostrap, sets action and title page
 * class CreateTelemetryOutputView
 *
 * @createUserForm: calls: view, create_telemetry_failure twig file, sets as Telemetry Output Page
 * @userCreationSuccess: calls: view, create_telemetry_success twig file, sets as Create Telemetry Output
 * @userCreationFailure: calls: view, create_user_failure.twig, 
 *
 */

class CreateUserView
{
    public function createUserForm($view, $response): void
    {
        $view->render($response,
            'create_user.twig',
            [
                'css_path' => CSS_PATH . 'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'header_1' => 'User creation',
                'header_2' => 'Insert a new user into the database',
                'method' => 'post',
                'action' => 'create-user-output',
                'action_dashboard' => 'dashboard',
                'logout_path' => LOGOUT_PATH,
            ]);
    }

    public function userCreationSuccess(object $view, object $response, string $username): void
    {
        $view->render($response,
            'create_user_success.twig',
            [
                'css_path' => CSS_PATH . 'dashboard.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'header_1' => 'User creation',
                'header_2' => 'User successfully created!',
                'paragraph_1' => 'was created and inserted into database!',
                'username' => $username,
                'method' => 'post',
                'action_dashboard' => 'dashboard',
                'logout_path' => LOGOUT_PATH,
                'session_value' => $_SESSION['username'],
            ]);
    }

    public function userCreationFailure(object $view, object $response, string $username): void
    {
        $view->render($response,
            'create_user_failure.twig',
            [
                'css_path' => CSS_PATH . 'dashboard.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'header_1' => 'User creation',
                'header_2' => 'User unsuccessfully created!',
                'paragraph_1' => 'already exists in the database',
                'username' => $username,
                'method' => 'post',
                'action_dashboard' => 'dashboard',
                'logout_path' => LOGOUT_PATH,
                'session_value' => $_SESSION['username'],
            ]);
    }

}
