<?php
/**
 * class SQLQueries
 * 1 public function (__construct()) & 8 public static function(PSF)
 * PSF:
 *
 * @createUser: creates user, inserts into DB, sets username and stores hashed PW
 * @checkUserExists: checks if the user exists
 * @getUserPassword: obtains user PW
 * @selectTelemetryData: query required for inserting SOAP data in to DB telemetry table, selects telemetry data from telemetry DB
 * @checkMessageExists: searches telemetry DB for a particular message searching via the timestamp
 * checkMessagesExist: checks messages where the message ID is greater than 0
 * @createMessage: inserts into telemetry DB the telemetry data
 * fetchTempAndTime: query to check and display all message temperatures and timestamp
 * all return query_string
 **/
#namespace TelemetryModel;

class SQLQueries
{
    public function __construct()
    {

    }

    public function deleteUser()
    {
        $query_string = "DELETE FROM users ";
        $query_string .= "WHERE user_id = :user_id";

        return $query_string;
    }

    public static function listAllUsers()
    {
        $query_string = "SELECT user_id, username, account_timestamp ";
        $query_string .= "FROM users";

        return $query_string;
    }

    public static function createUser()
    {
        $query_string = "INSERT INTO users ";
        $query_string .= "SET username = :clean_username, ";
        $query_string .= "password_hash = :hashed_password";

        return $query_string;
    }

    public static function checkUserExists()
    {
        $query_string = "SELECT username FROM users ";
        $query_string .= "WHERE username = :clean_username";

        return $query_string;
    }

    public static function getUserPassword()
    {
        $query_string = "SELECT password_hash FROM users ";
        $query_string .= "WHERE username = :clean_username";

        return $query_string;
    }


    //query required for inserting SOAP data in to DB telemetry table
    public static function selectTelemetryData()
    {
        $query_string = "SELECT message_id, message_content, temperature, fan_direction, keypad, switch_state, message_timestamp";
        $query_string .= "FROM telemetry";
        $query_string .= "WHERE message_id = LAST_INSERTED_ID()";

        return $query_string;

    }

    public static function checkMessageExists()
    {
        $query_string = "SELECT * FROM telemetry ";
        $query_string .= "WHERE message_timestamp = :message_timestamp";

        return $query_string;
    }

    public static function checkMessagesExist()
    {
        $query_string = "SELECT message_id FROM telemetry ";
        $query_string .= "WHERE message_id > 0";

        return $query_string;
    }


    public static function createMessage()
    {
        $query_string = "INSERT INTO telemetry ";
        $query_string .= "SET msisdn = :msisdn, ";
        $query_string .= "temperature = :temperature, ";
        $query_string .= "fan_direction = :fan_direction, ";
        $query_string .= "keypad = :keypad, ";
        $query_string .= "switch_1 = :switch_1, ";
        $query_string .= "switch_2 = :switch_2, ";
        $query_string .= "switch_3 = :switch_3, ";
        $query_string .= "switch_4 = :switch_4, ";
        $query_string .= "message_timestamp = :message_timestamp";

        return $query_string;
    }

    public static function fetchTempAndTime()
    {
        $query_sting = "SELECT temperature, message_timestamp FROM telemetry WHERE message_id > 0";

        return $query_sting;
    }


}
