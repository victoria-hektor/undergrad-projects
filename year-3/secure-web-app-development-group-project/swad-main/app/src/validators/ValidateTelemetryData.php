<?php

/**
 * file to validate the telemetry data in the database
 *
 * 10 public functions to set the following variables
 * @setMsisdn = $msisdn;
 * @setTemperature = $temperature;
 * @setFanDirection = $fan_direction;
 * @setKeypad = $keypad;
 * @setSwitch_1 = $switch_1;
 * @setSwitch_2 = $switch_2;
 * @setSwitch_3 = $switch_3;
 * @setSwitch_4 = $switch_4;
 * @setMessageTimestamp = $message_timestamp;
 * @setSQLQueries = $sql_queries;
 * @setDatabaseWrapper = $database_wrapper;
 * @setDatabaseConnectionSettings = $database_connection_settings;
 * @setLogger = $logger;
 * @checkMessageInDatabase to check if a message is in the database returns $success
 * @createArrayForMailer returns $message
 * @checkMessageExists returns $messages_exist
 * 
 */

class ValidateTelemetryData
{
    private $msisdn;
    private $temperature;
    private $fan_direction;
    private $keypad;
    private $switch_1;
    private $switch_2;
    private $switch_3;
    private $switch_4;
    private $message_timestamp;
    private $database_connection_settings;
    private $database_wrapper;
    private $sql_queries;
    private $monolog_logger;
    private $mailer;
    private $php_mailer;
    private $create_telemetry_model;

    /**
     * public function to set the params to null
     *
     * @param unknown_type = null;
     *
     */
    public function __construct()
    {
        $this->msisdn = null;
        $this->temperature = null;
        $this->fan_direction = null;
        $this->keypad = null;
        $this->switch_1 = null;
        $this->switch_2 = null;
        $this->switch_3 = null;
        $this->switch_4 = null;
        $this->message_timestamp = null;
        $this->database_connection_settings = null;
        $this->database_wrapper = null;
        $this->sql_queries = null;
        $this->monolog_logger = null;
        $this->mailer = null;
        $this->php_mailer = null;
        $this->create_telemetry_model = null;
    }


    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
    }

    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    }

    public function setFanDirection($fan_direction)
    {
        $this->fan_direction = $fan_direction;
    }

    public function setkeypad($keypad)
    {
        $this->keypad = $keypad;
    }

    public function setSwitch_1($switch_1)
    {
        $this->switch_1 = $switch_1;
    }

    public function setSwitch_2($switch_2)
    {
        $this->switch_2 = $switch_2;
    }

    public function setSwitch_3($switch_3)
    {
        $this->switch_3 = $switch_3;
    }

    public function setSwitch_4($switch_4)
    {
        $this->switch_4 = $switch_4;
    }

    public function setMessageTimestamp($message_timestamp)
    {
        $this->message_timestamp = $message_timestamp;
    }

    public function setSQLQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setLogger($logger)
    {
        $this->monolog_logger = $logger;
    }

    public function setPHPMailer($php_mailer)
    {
        $this->php_mailer = $php_mailer;
    }

    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    public function setCreateTelemetryModel($create_telemetry_model)
    {
        $this->create_telemetry_model = $create_telemetry_model;
    }

    /**
     *  public function to check if a message is in the database
     *
     *
     * @returns success
     */
    public function checkMessageInDatabase($message_timestamp, $msisdn, $temperature, $fan_direction, $keypad, $switch_1, $switch_2, $switch_3, $switch_4)
    {
        $wrapper = $this->database_wrapper;

        $wrapper->setSqlQueries($this->sql_queries);
        $wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $wrapper->makeDatabaseConnection();
        $wrapper->setLogger($this->monolog_logger);

        // Call check message_timestamp function and store in $message_exists.
        $message_exists = $wrapper->checkMessageTimestamp($message_timestamp);

        $mail = $this->mailer;

        $mail->setPHPMailer($this->php_mailer);
        $mail->setLogger($this->monolog_logger);

        $create_telemetry_model = $this->create_telemetry_model;

        // False == message does not exist
        // True  == message does exist
        $timestamp_array = (array) $message_timestamp;
        $success = false;

        if ($message_exists === false)
        {
            $success = $wrapper->insertMessage($msisdn, $temperature, $fan_direction, $keypad, $switch_1, $switch_2, $switch_3, $switch_4, $message_timestamp);

            if ($success === true)
            {
                $message_array = $this->createArrayForMailer($temperature, $fan_direction, $keypad, $switch_1, $switch_2, $switch_3, $switch_4, $message_timestamp);
                $message = $create_telemetry_model->createXmlMessage($message_array);

                $mail->sendMail($message, 1);
                $this->monolog_logger->info('Successful message insertion: ', $message_array);
            }

        }

        return $success;

    }

    public function createArrayForMailer($temperature, $fan_direction, $keypad, $switch_1, $switch_2, $switch_3, $switch_4, $message_timestamp): array
    {
        $message['tn'] = TEAM_NAME;
        $message['temp'] = $temperature;
        $message['fan_direction'] = $fan_direction;
        $message['keypad'] = $keypad;
        $message['s1'] = $switch_1;
        $message['s2'] = $switch_2;
        $message['s3'] = $switch_3;
        $message['s4'] = $switch_4;
        $message['timestamp'] = $message_timestamp;

        return $message;

    }

    public function checkMessagesExist()
    {
        $wrapper = $this->database_wrapper;

        $wrapper->setSqlQueries($this->sql_queries);
        $wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $wrapper->makeDatabaseConnection();
        $wrapper->setLogger($this->monolog_logger);

        $messages_exist = $wrapper->checkMessagesExist();

        return $messages_exist;
    }


}
