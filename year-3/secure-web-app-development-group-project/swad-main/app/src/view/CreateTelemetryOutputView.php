<?php

/**
 * file to output a failure or success message
 * calls on Twig files (create_telemetry_failure/success)
 * Calls on standard css, boostrap, sets action and title page
 * class CreateTelemetryOutputView
 *
 * @createOutputFailure: calls: view, create_telemetry_failure twig file, sets as Telemetry Output Page
 * @createOutputSuccess: calls: view, create_telemetry_success twig file, sets as Create Telemetry Output
 *
 */

class CreateTelemetryOutputView
{
    public function createOutputFailure(object $view, object $response): void
    {
        $view->render($response,
            'create_telemetry_failure.twig',
            [
                'css_path' => CSS_PATH . 'standard_css',
                'bootstrap' => BOOTSTRAP_PATH,
                'action' => 'dashboard',
                'initial_input_box_value' => null,
                'page_title' => 'Telemetry Output Page',
            ]);
    }

    public function createOutputSuccess(object $view, object $response, string $xml_string): void
    {
        $view->render($response,
            'create_telemetry_success.twig',
            [
                'css_path' => CSS_PATH . 'standard_css',
                'bootstrap' => BOOTSTRAP_PATH,
                'action' => 'dashboard',
                'method' => 'post',
                'header_1' => 'Telemetry successfully sent!',
                'header_2' => 'Message preview',
                'username' => $_SESSION['username'],
                'xml_string' => $xml_string,
                'logout_path' => LOGOUT_PATH,
                'page_title' => 'Create telemetry output',
            ]);
    }

}
