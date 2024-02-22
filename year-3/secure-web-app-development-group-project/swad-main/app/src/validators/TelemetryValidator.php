<?php

/**
 * validation file for the telemetry data
 * class TelemetryValidator, 7 public functions (PF)
 * @validateNumber: validates number is int, returns $number
 * @validateTemperature: validates temperature is int, returns $checked_temperature
 * @validateEmail: validates email using FILTER_VALIDATE_EMAIL filter, returns $filter_validate_email
 * @validateString: validates string, returns $string
 * @validateDate: validates the date as a string and sets format, returns $date
 * @validateSwitch: validates switch as on or off, returns $state
 * @validateFanDirection: validates fan direction, returns $fan_direction
 **/

namespace TelemetryModel;
use DateTime;

class TelemetryValidator
{
    public function validateNumber($number): int
    {
        if (!empty($number))
        {
            $filtered_number = filter_var($number, FILTER_VALIDATE_INT);
            $number = $filtered_number;

        }
        else
        {
            $number = 0;
        }

        return $number;
    }

    public function validateTemperature($temperature): int
    {
        $temperature = $this->validateNumber($temperature);

        if ($temperature < -273.15 || $temperature > 300)
        {
            $checked_temperature = -273;
        }
        else
        {
            $checked_temperature = $temperature;
        }

        return $checked_temperature;
    }

    public function validateEmail($email)
    {
        if (!empty($email))
        {
            $filtered_validate_email = filter_var($email, FILTER_VALIDATE_EMAIL);

            return $filtered_validate_email;
        }
    }

    public function validateString($string): string
    {
        if (!empty($string))
        {
            $filtered_validate_string = filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $string = $filtered_validate_string;
        }
        else
        {
            $string = "N/A";
        }

        return $string;
    }

    public function validateDate($date): string
    {
        if (!empty($date))
        {
            $format = 'd/m/Y H:i:s';
            $validated_date = DateTime::createFromFormat($format, $date);

            if ($validated_date !== false)
            {
                $date_array = json_decode(json_encode($validated_date), true);
                $date = $date_array['date'];
                $date = str_replace(".000000", "", $date);

            }
            else
            {
                $date = "N/A";
            }
        }
        else
        {
            $date = "N/A";
        }

        return $date;
    }

    public function validateSwitch($switch_state): string
    {
        if (!empty($switch_state))
        {
            $filtered_switch_state = filter_var($switch_state, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $compare_forward = strcmp($filtered_switch_state, "on");
            $compare_reverse = strcmp($filtered_switch_state, "off");

            if ($compare_forward == 0 || $compare_reverse == 0)
            {
                $state = $filtered_switch_state;
            }
            else
            {
                $state = "N/A";
            }

        }
        else
        {
            $state = "N/A";
        }

        return $state;
    }


    public function validateFanDirection($fan_direction): string
    {
        if (!empty($fan_direction))
        {
            $filtered_fan_direction = filter_var($fan_direction, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $compare_forward = strcmp($filtered_fan_direction, "forward");
            $compare_reverse = strcmp($filtered_fan_direction, "reverse");

            if ($compare_forward == 0 || $compare_reverse == 0)
            {
                $fan_direction = $filtered_fan_direction;
            }
            else
            {
                $fan_direction = "N/A";
            }

        }
        else
        {
            $fan_direction = "N/A";
        }

        return $fan_direction;
    }

}

