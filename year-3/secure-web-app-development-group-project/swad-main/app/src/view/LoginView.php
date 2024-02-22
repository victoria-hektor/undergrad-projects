<?php

/**
 * class LoginView, either success or error
 *
 * @loginError - returns: displays error message view
 * @loginSuccess - returns: displays dashboard view
 *
 **/

class LoginView
{
    public function loginError($view, $response): void
    {
        $view->render($response,
            'login_error.twig',
            [
                'css_path' => CSS_PATH . 'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'action' => 'dashboard',
                'initial_input_box_value' => null,
                'page_title' => 'Login/Register',
            ]);
    }

    public function loginSuccess($view, $response, $session_username): void
    {
        $view->render($response,
            'dashboard.twig',
            [
                'css_path' => CSS_PATH . 'dashboard.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'method' => 'post',
                'action' => 'obtain-telemetry',
                'action_create' => 'create-telemetry',
                'obtain_messages' => 'Obtain EE messages',
                'create_message' => 'Create an SMS message',
                'logout_path' => LOGOUT_PATH,
                'username' => $session_username,
            ]);
    }

    public function adminSuccess($view, $response, $session_username, $user_list): void
    {
        $view->render($response,
            'admin_dashboard.twig',
            [
                'css_path' => CSS_PATH . 'dashboard.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'method' => 'post',
                'page_heading_1' => 'Admin dashboard',
                'page_heading_3' => 'Current users in database',
                'action_create_user' => 'create-user',
                'action_delete_user' => 'delete-user',
                'logout_path' => LOGOUT_PATH,
                'username' => $session_username,
                'user_list' => $user_list,
            ]);
    }


}
