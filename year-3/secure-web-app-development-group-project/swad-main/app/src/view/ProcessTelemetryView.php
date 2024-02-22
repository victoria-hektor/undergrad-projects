<?php

/**
 * file to show success or failure of processing the telemetry data
 * class ProcessTelemetryData,
 *
 * @processTelemetrySuccess calls on process_telemetry.twig, returns output data
 * @processTelemetryFailure calls on obtain_telemetry_failure, returns output failure/error
 *
 **/

class ProcessTelemetryView
{
    public function processTelemetrySuccess(object $view, object $response, int $array_count, array $v_array, string $timestamps, string $temperatures): void
    {
        $session_value = $_SESSION['username'];

        $view->render($response,
            'process_telemetry.twig',
            [
                'css_path' => CSS_PATH,
                'bootstrap' => BOOTSTRAP_PATH,
                'logout_path' => LOGOUT_PATH,
                'action_dashboard' => DASHBOARD_PATH,
                'method' => 'post',
                'page_title' => 'Display data',
                'page_heading_1' => 'EE Messages',
                'page_heading_2' => 'Charts',
                'count' => $array_count,
                'validated_array' => $v_array,
                'username' => $session_value,
                'timestamps' => $timestamps,
                'temperatures' => $temperatures,
            ]);
    }

    public function processTelemetryFailure(object $view, object $response): void
    {
        $view->render($response,
            'obtain_telemetry_error.twig',
            [
                'css_path' => CSS_PATH .'homepage.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'method' => 'post',
                'action' => 'process-telemetry',
                'page_title' => APP_NAME,
                'page_heading_1' => 'Obtain telemetry data',
                'phone_number' => '447956651686',
            ]);
    }
}
