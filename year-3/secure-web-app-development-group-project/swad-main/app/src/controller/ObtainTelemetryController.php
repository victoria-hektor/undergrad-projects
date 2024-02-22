<?php

/**
 * class obtainTelemetryController, public function createOutput
 * calls on view, obtainTelemetryView and relevent container
 * @createOutput: returns view
 **/

class ObtainTelemetryController
{
    public function createOutput(object $app, object $response): void
    {
        $view = $app->getContainer()->get('view');
        $obtain_telemetry_view = $app->getContainer()->get('obtainTelemetryView');

        $obtain_telemetry_view->createOutput($view, $response);
    }
}
