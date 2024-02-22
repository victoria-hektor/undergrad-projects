<?php


/**
 * Database Wrapper - includes connection settings, queries, errors and logging
 * @__construct(), 
 * @__destruct(), 
 * @setDatabaseConnectionSettings, 
 * @setSqlQueries, 
 * @setLogger
 * @makeDatabaseConnection, returns $pdo_error
 * @fetchAllAssoc, returns $result
 * @checkUsername, returns $user_exists
 * @checkUsernameAndPassword, returns $result
 * @createNewUser, returns $check
 * @lastInsertedID, returns $last_inserted_id
 * @checkMessageTimestamp, returns $message_exists
 * @insertMessage, returns $check
 * @checkMessagesExist, returns $action OR $result
 * @fetchTempAndTime, returns $result
 *
 * private function: @safeQuery, returns error
 *
 */
#namespace TelemetryModel;

#use PDO;
#use PDOException;


class DatabaseWrapper
{
    private $database_connection_settings;
    private $db_handle;
    private $sql_queries;
    private $prepared_statement;
    private $errors;
    private $monolog_logger;

    public function __construct()
    {
        $this->database_connection_settings = null;
        $this->db_handle = null;
        $this->sql_queries = null;
        $this->prepared_statement = null;
        $this->monolog_logger = null;
        $this->errors = [];
    }

    public function __destruct()
    {
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setLogger($logger)
    {
        $this->monolog_logger = $logger;
    }

    public function makeDatabaseConnection()
    {
        $pdo_error = '';

        $database_settings = $this->database_connection_settings;
        $host_name = $database_settings['rdbms'] . ':host=' . $database_settings['host'];
        $port_number = ';port=' . '3306';
        $user_database = ';dbname=' . $database_settings['db_name'];

        $host_details = $host_name . $port_number . $user_database;

        $user_name = $database_settings['user_name'];
        $user_password = $database_settings['user_password'];
        $pdo_attributes = $database_settings['options'];

        try {
            $pdo_handle = new PDO($host_details, $user_name, $user_password, $pdo_attributes);
            $this->db_handle = $pdo_handle;
            //Perform logging here
            $this->monolog_logger->notice('Successful connection to database');
        } catch (PDOException $exception_object) {
            var_dump($exception_object);
            trigger_error('Error connecting to database');
            $pdo_error = 'Error connecting to database';
            //Perform Logging here
            $this->monolog_logger->warning('Error connecting to database');
            echo "Error Connecting to the Database";
        }

        return $pdo_error;
    }

    private function safeQuery($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;


        try {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $execute_result = $this->prepared_statement->execute($query_parameters);

            $this->errors['execute-OK'] = $execute_result;
            //Perform Logging here
            $this->monolog_logger->notice('Successful query: ', (array) $query_string);
        } catch (PDOException $exception_object) {
            $error_message = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";

            echo $exception_object->getMessage();
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
            //Perform logging here
            $this->monolog_logger->warning('Error issuing SQL query: ', (array) $this->prepared_statement->errorInfo());
            return $this->errors['db_error'];
        }


    }


    public function fetchAllAssoc()
    {
        $result = $this->prepared_statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function deleteSingleUser($user_id): bool
    {
        $query_string = $this->sql_queries->deleteUser();
        $query_parameters = [
            ':user_id' => $user_id
        ];

        $this->safeQuery($query_string, $query_parameters);
        $check = $this->errors['execute-OK'];

        return $check;
    }


    public function userList()
    {
        $query_string = $this->sql_queries->listAllUsers();
        $this->safeQuery($query_string);

        $result = $this->fetchAllAssoc();

        return $result;
    }

    public function checkUsername($clean_username): bool
    {
        $query_string = $this->sql_queries->checkUserExists();
        $query_parameters = [
            ':clean_username' => $clean_username
        ];

        $user_exists = false;

        $this->safeQuery($query_string, $query_parameters);
        $result = $this->fetchAllAssoc();

        if (!empty($result))
        {
            $user_exists = true;
        }

        return $user_exists;

    }

    public function checkUsernameAndPassword($clean_username)
    {
        $query_string = $this->sql_queries->getUserPassword();
        $query_parameters = [
            ':clean_username' => $clean_username
        ];

        $this->safeQuery($query_string, $query_parameters);
        $result = $this->fetchAllAssoc();

        return $result;
    }

    public function createNewUser($clean_username, $hashed_password): bool
    {
        $query_string = $this->sql_queries->createUser();
        $query_parameters = [
            ':clean_username' => $clean_username,
            ':hashed_password' => $hashed_password,
        ];

        $this->safeQuery($query_string, $query_parameters);
        $check = $this->errors['execute-OK'];

        return $check;
    }

    public function checkMessageTimestamp($message_timestamp): bool
    {
        $query_string = $this->sql_queries->checkMessageExists();
        $query_parameters = [
            ':message_timestamp' => $message_timestamp
        ];

        $message_exists = false;

        $this->safeQuery($query_string, $query_parameters);
        $result = $this->fetchAllAssoc();

        if (!empty($result))
        {
            $message_exists = true;
        }

        return $message_exists;

    }

    public function insertMessage($msisdn, $temperature, $fan_direction, $keypad, $switch_1, $switch_2, $switch_3, $switch_4, $message_timestamp)
    {
        $query_string = $this->sql_queries->createMessage();
        $query_parameters = [
            ':msisdn' => $msisdn,
            ':temperature' => $temperature,
            ':fan_direction' => $fan_direction,
            ':keypad' => $keypad,
            ':switch_1' => $switch_1,
            ':switch_2' => $switch_2,
            ':switch_3' => $switch_3,
            ':switch_4' => $switch_4,
            ':message_timestamp' => $message_timestamp,

        ];

        $this->safeQuery($query_string, $query_parameters);
        $check = $this->errors['execute-OK'];

        return $check;
    }

    public function checkMessagesExist()
    {
        $query_string = $this->sql_queries->checkMessagesExist();
        $query_parameters = [];

        $message_exists = false;

        $this->safeQuery($query_string, $query_parameters);
        $result = $this->fetchAllAssoc();

        if (!empty($result))
        {
            $message_exists = true;
            $action = $this->fetchTempAndTime();

            return $action;
        }
        else
        {
            return $result;
        }
    }

    public function fetchTempAndTime()
    {
        $query_string = $this->sql_queries->fetchTempAndTime();
        $query_parameters = [];

        $this->safeQuery($query_string, $query_parameters);

        $result = $this->fetchAllAssoc();

        return $result;
    }


}
