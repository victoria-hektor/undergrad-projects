<?php

/**
 * class to validate user, returns a true or false value
 *
 * @__construct() - sets params to null
 * @setUserName = $username;
 * @setPasswordAsString = $password_as_string;
 * @setPasswordHash = $password_hash;
 * @setSQLQueries = $sql_queries;
 * @setDatabaseWrapper = $database_wrapper;
 * @setBcryptWrapper = $bcrypt_wrapper;
 * @setDatabaseConnectionSettings = $database_connection_settings;
 * @setLogger = $logger;
 *
 * @checkUserInDatabase to check if a user is in the database, Checks username function, authenticates, and logs
 * @checkUserInDatabase = $username, $password_plaintext, $hashed_password;
 * Returns = $user_exists, $success,
 * returns True or False
 *
 */

class ValidateUser
{
    private $username;
    private $password_as_string;
    private $hashed_password;
    private $database_connection_settings;
    private $database_wrapper;
    private $sql_queries;
    private $bcrypt_wrapper;
    private $monolog_logger;


    public function __construct()
    {
        $this->username = null;
        $this->password_as_string = null;
        $this->hashed_password = null;
        $this->database_connection_settings = null;
        $this->database_wrapper = null;
        $this->sql_queries = null;
        $this->bcrypt_wrapper = null;
        $this->monolog_logger = null;
    }


    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPasswordAsString($password_as_string)
    {
        $this->password_as_string = $password_as_string;
    }

    public function setPasswordHash($password_hash)
    {
        $this->hashed_password = $password_hash;
    }

    public function setSQLQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    public function setBcryptWrapper($bcrypt_wrapper)
    {
        $this->bcrypt_wrapper = $bcrypt_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setLogger($logger)
    {
        $this->monolog_logger = $logger;
    }

    /**
     * public function to check if a user is in the database
     * Checks username function, authenticates, and logs
     *
     * @param checkUserInDatabase = $username, $password_plaintext, $hashed_password;
     * @param checkUserInDatabase = $user_exists, $success,
     * @returns True
     * @returns False
     *
     * @returns success
     */
    public function checkUserInDatabase($username, $password_plaintext, $hashed_password)
    {
        $wrapper = $this->database_wrapper;
        $bcrypt_wrapper = $this->bcrypt_wrapper;

        $wrapper->setSqlQueries($this->sql_queries);
        $wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $wrapper->makeDatabaseConnection();
        $wrapper->setLogger($this->monolog_logger);

        # Call check username function and store in $result.
        $user_exists = $wrapper->checkUsername($username);

        # False == user does not exist
        # True  == user does exist
        $uname_array = (array) $username;
        $success = false;

        if ($user_exists === false)
        {
            $success = $wrapper->createNewUser($username, $hashed_password);

            if ($success === true)
            {
                $this->monolog_logger->info('Successful account creation: ', $uname_array);
            }
        }

        if ($user_exists === true)
        {
            $hash = $wrapper->checkUsernameAndPassword($username, $hashed_password);
            $authenticated = $bcrypt_wrapper->verifyPassword($password_plaintext, $hash);

            if ($authenticated === true)
            {
                $success = true;
                $this->monolog_logger->info('Successful account login: ', $uname_array);
            }
            elseif ($authenticated === false)
            {
                $this->monolog_logger->info('Unsuccessful account login: ', $uname_array);
            }
        }

        return $success;
    }
}
