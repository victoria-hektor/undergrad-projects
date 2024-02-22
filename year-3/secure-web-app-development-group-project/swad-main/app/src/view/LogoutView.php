<?php

/**
 * creates logout view, intial input is null, sets action
 * calls on logout.twig, css path and bootstrap path
 *
 * @createOutput - returns logout view
 **/

class LogoutView
{
    public function createOutput($view, $response): void
    {
        $view->render($response,
            'logout.twig',
            [
                'css_path' => CSS_PATH . 'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'action' => 'dashboard',
                'initial_input_box_value' => null,
                'page_title' => 'Login/Register page',
            ]);
    }

}
