<?php

/**
 * class createTelemetryModel, public function cleanTelemetryFormParameters & createXmlMessage
 *
 * @cleanTelemetryFormParameters: Takes the initial params, cleans and validates them, then assigns and returns them to post validated params
 * Returns: cleaned_parameters
 * @createXmlMessage: Takes cleaned params and assigns to this function to create an xml string message via an array
 * Returns: xml_string
 **/

class CreateTelemetryModel
{
    private $telemetry_validator;

    public function __construct()
    {
        $this->telemetry_validator = null;
    }

    public function setTelemetryValidator($telemetry_validator)
    {
        $this->telemetry_validator = $telemetry_validator;
    }

    public function cleanTelemetryFormParameters(array $dirty_parameters): array
    {
        $cleaned_parameters = [];
        $telemetry_validator = $this->telemetry_validator;

        $temperature = $dirty_parameters['temperature'];
        $fan_direction = $dirty_parameters['fan_direction'];
        $keypad = $dirty_parameters['keypad'];
        $switch_1  = $dirty_parameters['switch_1'];
        $switch_2  = $dirty_parameters['switch_2'];
        $switch_3  = $dirty_parameters['switch_3'];
        $switch_4  = $dirty_parameters['switch_4'];

        $cleaned_parameters['tn'] = TEAM_NAME;
        $cleaned_parameters['temp'] = $telemetry_validator->validateTemperature($temperature);
        $cleaned_parameters['fan_direction'] = $telemetry_validator->validateFanDirection($fan_direction);
        $cleaned_parameters['keypad'] = $telemetry_validator->validateNumber($keypad);
        $cleaned_parameters['s1'] = $telemetry_validator->validateSwitch($switch_1);
        $cleaned_parameters['s2'] = $telemetry_validator->validateSwitch($switch_2);
        $cleaned_parameters['s3'] = $telemetry_validator->validateSwitch($switch_3);
        $cleaned_parameters['s4'] = $telemetry_validator->validateSwitch($switch_4);

        return $cleaned_parameters;
    }

    public function createXmlMessage($cleaned_parameters): string
    {
        $xml_message_array = [];
        foreach ($cleaned_parameters as $key => $value)
        {
            $xml_message_array[$key] = '<' . $key . '>' . $value . '<' . '/' . $key . '>';
        }

        $xml_string = implode("\n", $xml_message_array);

        return $xml_string;
    }
}
