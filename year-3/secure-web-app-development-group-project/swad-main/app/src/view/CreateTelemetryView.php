<?php

/**
 * file to create the form view for input of telemetry data
 * class createTelemetryView, public function createTelemetryForm
 * calls on create_telemetry.twig, homepage.css, bootstrap, logout function and relevent container
 *
 * @createTelemetryForm returns createTelemetryView
 *
 **/

class CreateTelemetryView
{
    public function createTelemetryForm(object $view, object $response, string $username): void
    {
        $view->render($response,
            'create_telemetry.twig',
            [
                'css_path' => CSS_PATH . 'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'header_2' => 'Create telemetry',
                'paragraph_1' => 'Fill in the details below,',
                'action' => 'create-telemetry-output',
                'action_dashboard' => 'dashboard',
                'method' => 'post',
                'initial_input_box_value' => null,
                'page_title' => 'Create telemetry form',
                'logout_path' => LOGOUT_PATH,
                'username' => $username,
            ]);
    }
}
