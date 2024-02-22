<?php

/**
 * class ObtainTelemetryView, public function createOutput
 * returns telemetry obtained view to user
 * calls on obtain_telemetry.twig
 *
 * @createOutput - returns obtain telemetry view 
 *
 **/

class ObtainTelemetryView
{
    public function createOutput(object $view, object $response): void
    {
        $session_value = $_SESSION['username'];

        $view->render($response,
            'obtain_telemetry.twig',
            [
                'css_path' => CSS_PATH .'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'method' => 'post',
                'action' => 'process-telemetry',
                'page_title' => 'Obtain telemetry',
                'page_heading_1' => 'Obtain telemetry data',
                'team_name' => '21-3110-AC',
                'username' => $session_value,
            ]);
    }
}
