<?php

/**
 * file to create the output
 * object-oriented output
 * calls on homepageView and container
 * @createOutput - returns: view
 */

class HomepageController
{
    public function createOutput(object $app, object $response): void
    {
        $view = $app->getContainer()->get('view');
        $homepage_view = $app->getContainer()->get('homepageView');

        $homepage_view->createHomepageForm($view, $response);
    }
}
