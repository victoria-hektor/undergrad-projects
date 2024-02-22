<?php

/**
 * class ProcessTelemetryController, Public Function
 * public function calls on processTelemetryView and processTelemetryModel
 * Specifies team name validation with success outputting the correct messages
 *
 * @reateOutput: returns success or failure view
 **/

class ProcessTelemetryController
{
    public function createOutput(object $app, object $request, object $response): void
    {
        $process_telemetry_view = $app->getContainer()->get('processTelemetryView');
        $process_telemetry_model = $app->getContainer()->get('processTelemetryModel');
        $homepage_view = $app->getContainer()->get('homepageView');
        $view = $app->getContainer()->get('view');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];
        $database_wrapper = $app->getContainer()->get('databaseWrapper');
        $soap_wrapper = $app->getContainer()->get('SoapWrapper');
        $sql_queries = $app->getContainer()->get('sqlQueries');
        $logger = $app->getContainer()->get('monologLogger');
        $telemetry_validator = $app->getContainer()->get('telemetryValidator');
        $soap_validator = $app->getContainer()->get('soapValidator');
        $telemetry_details_model = $app->getContainer()->get('telemetryDetailsModel');
        $validate_message = $app->getContainer()->get('validateTelemetryData');
        $create_telemetry_model = $app->getContainer()->get('createTelemetryModel');
        $php_mailer = $app->getContainer()->get('phpMailer');
        $mailer = $app->getContainer()->get('mailer');

        $process_telemetry_model->setDBConf($db_conf);
        $process_telemetry_model->setDatabaseConnectionSettings($database_connection_settings);
        $process_telemetry_model->setDatabaseWrapper($database_wrapper);
        $process_telemetry_model->setSQLQueries($sql_queries);
        $process_telemetry_model->setSoapWrapper($soap_wrapper);
        $process_telemetry_model->setSoapValidator($soap_validator);
        $process_telemetry_model->setTelemetryValidator($telemetry_validator);
        $process_telemetry_model->setTelemetryDetailsModel($telemetry_details_model);
        $process_telemetry_model->setValidateMessage($validate_message);
        $process_telemetry_model->setLogger($logger);
        $process_telemetry_model->setMailer($mailer);
        $process_telemetry_model->setPHPMailer($php_mailer);
        $process_telemetry_model->setCreateTelemetryModel($create_telemetry_model);

        $v_number = $process_telemetry_model->validateTeamName($request);
        $authenticated = $v_number['authenticated'];
        $validated_team = $v_number['validated_team'];

        if ($authenticated === true && !empty($_SESSION['username']))
        {
            $message_details_result = $process_telemetry_model->getMessageDetails($validated_team);
            $array_count = $process_telemetry_model->countArrayNumber($message_details_result);

            $message_array = $process_telemetry_model->parseData($message_details_result, $array_count);

            $message_count = $message_array['count'];

            $v_array = $process_telemetry_model->validateDownloadedData($message_array, $message_count);
            $process_telemetry_model->storeTelemetryData($v_array, $message_count);
            $timestamps = $process_telemetry_model->getTimestamps();
            $temperatures = $process_telemetry_model->getTemperatures();

            $process_telemetry_view->processTelemetrySuccess($view, $response, $message_count, $v_array, $timestamps, $temperatures);
        }
        elseif (empty($_SESSION['username']))
        {
            $homepage_view->createHomepageForm($view, $response);
        }
        else
        {
            $process_telemetry_view->processTelemetryFailure($view, $response);
        }
    }

}
