<?php
/**
 * creates HomePageView class with function
 * calls on CSS, sets method
 * 
 * @createHomePageView - returns homepage form view
 *
 * Calls on Twig and CSS files for the homepage
 * Calls on bootstrap, sets page title
 */

class HomepageView
{
    public function createHomepageForm(object $view, object $response)
    {
        $view->render($response,
            'homepageform.twig',
            [
                'css_path' => CSS_PATH . 'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'action' => 'dashboard',
                'initial_input_box_value' => null,
                'page_title' => 'Login/Register',
            ]);
    }
}
