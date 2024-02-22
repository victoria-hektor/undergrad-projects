<?php

/**
 * class ProcessTelemetryModel, 11 public function (PF) 
 * PF:
 * @validateTeamName - validates then returns team name. returns: $v_team
 * @sanitiseTeamName - calls on telemetryValidator, if id is set, return validated team as string. returns: $validated_team
 * @checkTeamName - soap call to validate team id, calls on soapValidator. returns $team_authenticated
 * @validateDownloadedData - validates the telemetry data in an array, calls on telemetryValidator,
 *        validates the metadata & telemetry data using a for loop, then returns validated data. returns $validated_data
 * @getMessageDetails - Calls on telemetryDetailsModel and soapWrapper and relevent containers,
 *        instructs function to carry out soap call, set soap wrapper, set params and returns via getResult
 * @countArrayNumber - function to count the array and returns no. of elements in the array
 * @parseData - parses the data into string form and puts in an array if it meets the set requirements of if statement
 *        success will show all messages with our team name. returns $message_array
 * @storeTelemetryData - enables storing of the data safely in an array, calls on: settings, pdo_settings, databaseWrapper,
 *        sqlQueries, validateTelemetryData, monologLogger and relevent containers.
 *        Declares variables then sends to DB for safe storage
 * @getTempAndTime - function to get temperature & time from DB, calls on: settings, pdo_settings, databaseWrapper,
 *        sqlQueries, validateTelemetryData, then returns the result. returns $result
 * @getTimestamps - function to take previous function results and place into a $timestamps array. returns $timestamps
 * @getTemperatures - function to take previous function results and place into a $temperatures array. return $temperatures
 **/

class ProcessTelemetryModel
{
    private $database_connection_settings;
    private $database_wrapper;
    private $db_conf;
    private $sql_queries;
    private $monolog_logger;
    private $telemetry_validator;
    private $telemetry_details_model;
    private $soap_wrapper;
    private $soap_validator;
    private $validate_message;
    private $mailer;
    private $php_mailer;
    private $create_telemetry_model;


    public function __construct()
    {
        $this->db_conf = null;
        $this->database_connection_settings = null;
        $this->database_wrapper = null;
        $this->sql_queries = null;
        $this->telemetry_validator = null;
        $this->telemetry_details_model = null;
        $this->soap_wrapper = null;
        $this->validate_message = null;
        $this->monolog_logger = null;
        $this->php_mailer = null;
        $this->mailer = null;
        $this->create_telemetry_model;
    }

    public function setDBConf($db_conf)
    {
        $this->db_conf = $db_conf;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setLogger($logger)
    {
        $this->monolog_logger = $logger;
    }

    public function setSQLQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function setSoapValidator($soap_validator)
    {
        $this->soap_validator = $soap_validator;
    }

    public function setTelemetryValidator($telemetry_validator)
    {
        $this->telemetry_validator = $telemetry_validator;
    }

    public function setTelemetryDetailsModel($telemetry_details_model)
    {
        $this->telemetry_details_model = $telemetry_details_model;
    }

    public function setCreateTelemetryModel($create_telemetry_model)
    {
        $this->create_telemetry_model = $create_telemetry_model;
    }

    public function setValidateMessage($validate_message)
    {
        $this->validate_message = $validate_message;
    }

    public function setPHPMailer($php_mailer)
    {
        $this->php_mailer = $php_mailer;
    }
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    public function validateTeamName(object $request): array
    {
        $tainted_parameters = $request->getParsedBody();
        $v_team['validated_team'] = $this->sanitiseTeamName($tainted_parameters);
        $v_team['authenticated'] = $this->checkTeamName($v_team['validated_team']);

        return $v_team;
    }

    public function sanitiseTeamName($tainted_parameters): array
    {
        $telemetry_validator = $this->telemetry_validator;

        if (isset($tainted_parameters['id']))
        {
            $tainted_team = $tainted_parameters['id'];
            $validated_team['id'] = $telemetry_validator->validateString($tainted_team);
            $validated_team['id'] = (string)$validated_team['id'];
        }

        return $validated_team;
    }

    public function checkTeamName($validated_team): bool
    {
        $soap_validator = $this->soap_validator;
        $team_authenticated = $soap_validator->validateId($validated_team['id']);

        return $team_authenticated;
    }

    public function validateDownloadedData($message_array, $array_count): array
    {
        $telemetry_validator = $this->telemetry_validator;
        $validated_data = [];

        if (!empty($message_array))
        {
            for ($i = 0; $i < $array_count; $i++)
            {
                # Meta data
                $validated_data[$i]['msidsn'] = $telemetry_validator->validateNumber($message_array[$i]['sourcemsisdn']);
                $validated_data[$i]['eemsidsn'] = $telemetry_validator->validateNumber($message_array[$i]['destinationmsisdn']);
                $validated_data[$i]['receivedtime'] = $telemetry_validator->validateDate($message_array[$i]['receivedtime']);
                $validated_data[$i]['bearer'] = $telemetry_validator->validateString($message_array[$i]['bearer']);
                $validated_data[$i]['messageref'] = $telemetry_validator->validateNumber($message_array[$i]['messageref']);

                # Telemetry data
                $validated_data[$i]['temperature'] = $telemetry_validator->validateTemperature($message_array[$i]['message']['temp']);
                $validated_data[$i]['fan_direction'] = $telemetry_validator->validateString($message_array[$i]['message']['fan_direction']);
                $validated_data[$i]['keypad'] = $telemetry_validator->validateNumber($message_array[$i]['message']['keypad']);
                $validated_data[$i]['s1'] = $telemetry_validator->validateSwitch($message_array[$i]['message']['s1']);
                $validated_data[$i]['s2'] = $telemetry_validator->validateSwitch($message_array[$i]['message']['s2']);
                $validated_data[$i]['s3'] = $telemetry_validator->validateSwitch($message_array[$i]['message']['s3']);
                $validated_data[$i]['s4'] = $telemetry_validator->validateSwitch($message_array[$i]['message']['s4']);
            }
        }

        return $validated_data;
    }

    public function getMessageDetails(array $cleaned_parameters)
    {
        $message_details_model = $this->telemetry_details_model;
        $soap_wrapper = $this->soap_wrapper;
        $soap_call_details = $message_details_model->peakMessageDetail();


        $message_details_model->setSoapWrapper($soap_wrapper);

        $message_details_model->setParameters($cleaned_parameters);
        $message_details_model->doSoapCall($soap_call_details);

        return $message_details_model->getResult();
    }

    public function countArrayNumber($multi_array): int
    {
        $count = 0;

        foreach ($multi_array as $array)
        {
            $array = (array) $array;
            $count += count($array);
        }

        return $count;
    }

    public function parseData($message_details_result, $count): array
    {
        $message_array = [];
        $j = 0;

        for ($i = 0; $i < $count; $i++)
        {
            $string = $message_details_result[$i];

            if (str_contains($string, TEAM_NAME) === true)
            {
                $xml_string = simplexml_load_string($string);
                $message_array[$j] = json_decode(json_encode($xml_string), true);
                $j++;
            }
        }

        $message_array['count'] = $j;

        return $message_array;
    }

    public function storeTelemetryData($v_array, $array_count)
    {
        $validate_message = $this->validate_message;

        $database_wrapper = $this->database_wrapper;

        $database_wrapper->setSqlQueries($this->sql_queries);
        $database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $database_wrapper->setLogger($this->monolog_logger);

        $validate_message->setDatabaseConnectionSettings($this->database_connection_settings);
        $validate_message->setDatabaseWrapper($this->database_wrapper);
        $validate_message->setSqlQueries($this->sql_queries);
        $validate_message->setLogger($this->monolog_logger);
        $validate_message->setPHPMailer($this->php_mailer);
        $validate_message->setMailer($this->mailer);
        $validate_message->setCreateTelemetryModel($this->create_telemetry_model);

        for ($i = 0; $i < $array_count; $i++)
        {
            //Declare Variables
            $msisdn = $v_array[$i]['msidsn'];
            $temperature = $v_array[$i]['temperature'];
            $fan_direction = $v_array[$i]['fan_direction'];
            $keypad = $v_array[$i]['keypad'];
            $switch_1 = $v_array[$i]['s1'];
            $switch_2 = $v_array[$i]['s2'];
            $switch_3 = $v_array[$i]['s3'];
            $switch_4 = $v_array[$i]['s4'];
            $message_timestamp = $v_array[$i]['receivedtime'];

            //Insert into DB
            $validate_message->setMsisdn($msisdn);
            $validate_message->setTemperature($temperature);
            $validate_message->setFanDirection($fan_direction);
            $validate_message->setKeypad($keypad);
            $validate_message->setSwitch_1($switch_1);
            $validate_message->setSwitch_2($switch_2);
            $validate_message->setSwitch_3($switch_3);
            $validate_message->setSwitch_4($switch_4);
            $validate_message->setMessageTimestamp($message_timestamp);
            $validate_message->checkMessageInDatabase($message_timestamp, $msisdn, $temperature, $fan_direction, $keypad, $switch_1, $switch_2, $switch_3, $switch_4);

        }
    }

    public function getTempAndTime(): array
    {
        $validate_message = $this->validate_message;

        //Fetch Temperature and Timestamp from DB
        $validate_message->setDatabaseConnectionSettings($this->database_connection_settings);
        $validate_message->setDatabaseWrapper($this->database_wrapper);
        $validate_message->setSqlQueries($this->sql_queries);
        $result = $validate_message->checkMessagesExist();

        return $result;
    }

    public function getTimestamps(): string
    {
        $timestamps = [];

        $result = $this->getTempAndTime();
        $size = sizeof($result);

        for ($i = 0; $i < $size; $i++)
        {
            $timestamps[] = $result[$i]['message_timestamp'];
        }

        $timestamps = json_encode($timestamps);

        return $timestamps;

    }

    public function getTemperatures(): string
    {
        $temperatures = [];

        $result = $this->getTempAndTime();
        $size = sizeof($result);

        for ($i = 0; $i < $size; $i++)
        {
            $temperatures[] = $result[$i]['temperature'];
        }

        $temperatures = json_encode($temperatures);

        return $temperatures;
    }

}
