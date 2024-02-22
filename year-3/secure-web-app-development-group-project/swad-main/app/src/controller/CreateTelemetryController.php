<?php
/**
 * File to facilitate the creation of telemetry data
 * creates class which contains 5 public functions:
 * @parseParameters checks and cleans the information, returns xml string message
 * @sendMessage:
 *   - object oriented function, puts in array
 *   - calls on SoapWrapper, telemetryDetailsModel, and the relevant containers
 *   - completes soap-call and sets result, returns the sent message
 * @checkSendMessageResult - (Delivery Receipt), returns a true or false output
 * @createMessageSentOutput - function to check the output, calls on relevant view file, sets logger
 * if the message has sent, it will out success message, if failed, will output error message
 * @createOutput to create the output
 *   - calls on the session wrapper and relevant view file
 *   - creates a session relating to the user if it is successful
 *   - Shows the homepage view if unsuccessful
 **/

class CreateTelemetryController
{
    public function parseParameters(object $app, object $request): string
    {
        $create_telemetry_model = $app->getContainer()->get('createTelemetryModel');
        $telemetry_validator = $app->getContainer()->get('telemetryValidator');

        $create_telemetry_model->setTelemetryValidator($telemetry_validator);

        $dirty_parameters = $request->getParsedBody();
        $clean_parameters = $create_telemetry_model->cleanTelemetryFormParameters($dirty_parameters);
        $xml_message_string = $create_telemetry_model->createXmlMessage($clean_parameters);

        return $xml_message_string;
    }

    public function sendMessage(object $app, object $request): array
    {
        $message_sent = [];
        $message_sent['xml_string'] = $this->parseParameters($app, $request);

        $soap_wrapper = $app->getContainer()->get('SoapWrapper');
        $telemetry_model = $app->getContainer()->get('telemetryDetailsModel');
        $soap_call_details = $telemetry_model->sendMessageDetail($message_sent['xml_string']);

        $telemetry_model->setSoapWrapper($soap_wrapper);
        $telemetry_model->doSoapCall($soap_call_details);
        $result = $telemetry_model->getResult();

        $message_sent['result'] = $this->checkSendMessageResult($result);

        return $message_sent;
    }

    public function checkSendMessageResult(?int $result): bool
    {
        $message_sent = false;

        if (is_int($result))
        {
            $message_sent = true;
        }

        return $message_sent;
    }

    public function createMessageSentOutput($app, $request, $response): void
    {
        $create_telemetry_output_view = $app->getContainer()->get('createTelemetryOutputView');
        $logger = $app->getContainer()->get('monologLogger');
        $view = $app->getContainer()->get('view');
        $php_mailer = $app->getContainer()->get('phpMailer');
        $mail = $app->getContainer()->get('mailer');

        $mail->setPHPMailer($php_mailer);
        $mail->setLogger($logger);

        $message_sent_result = $this->sendMessage($app, $request);
        $message_sent = $message_sent_result['result'];
        $xml_string = $message_sent_result['xml_string'];

        if ($message_sent === true)
        {
            $mail->sendMail($xml_string, 0);
            $logger->info('Telemetry message successfully sent:', $message_sent_result);
            $create_telemetry_output_view->createOutputSuccess($view, $response, $xml_string);
        }
        else
        {
            $logger->info('Telemetry message unsuccessful:', $message_sent_result);
            $create_telemetry_output_view->createOutputFailure($view, $response);
        }
    }

    public function createOutput($app, $response): void
    {
        $session_wrapper = $app->getContainer()->get('sessionWrapper');
        $create_telemetry_view = $app->getContainer()->get('createTelemetryView');
        $view = $app->getContainer()->get('view');

        $username = 'username';
        $value_set = $session_wrapper->isSessionValueSet($username);

        if ($value_set === true)
        {
            $username = $_SESSION['username'];
            $create_telemetry_view->createTelemetryForm($view, $response, $username);
        }
        else
        {
            $homepage_view = $app->getContainer()->get('homepageView');
            $homepage_view->createHomepageForm();
        }
    }
}
