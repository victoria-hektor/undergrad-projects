<?php
/**
 * file to facilitate logout function
 * class LogoutController, public function logoutUser
 * calls on: view, sessionWrapper, sessionLogger
 * for the view, calls on the logoutView file and container
 * If successful shows success message else shows homepageView
 *
 * @logoutUser: returns logoutView OR homepageView
 **/


class LogoutController
{
    public function logoutUser(object $app, object $response)
    {
        $view = $app->getContainer()->get('view');
        $username = 'username';
        $session_wrapper = $app->getContainer()->get('sessionWrapper');
        $session_logger = $app->getContainer()->get('sessionLogger');
        $session_value_set = $session_wrapper->isSessionValueSet($username);

        $logout_view = $app->getContainer()->get('logoutView');

        if ($session_value_set === true)
        {
            $session_value['username'] = $session_wrapper->getSessionValue($username);
            $session_wrapper->unsetSessionValue($username);
            session_destroy();
            session_write_close();

            $session_logger->info('User logged out: ', $session_value);
            $logout_view->createOutput($view, $response);
        }
        else
        {
            $homepage_view = $app->getContainer()->get('homepageView');
            $homepage_view->createHomepageForm($view, $response);
        }

    }

}
