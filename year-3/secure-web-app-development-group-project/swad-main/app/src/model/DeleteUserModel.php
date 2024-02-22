<?php
/**
 * File to facilitate the *** output and functionality
 * Calls on relevant views, containers and files
 * class = AdminDashboardModel 
 * private functions: $database_connection_settings, $database_wrapper, $sql_queries, $monolog_logger
 *
 * @setSQLQueries - returns $sql_queries
 * @setDatabaseWrapper - returns $database_wrapper
 * @setBcryptWrapper - returns $bcrypt_wrapper
 * @setDatabaseConnectionSettings - returns $database_connection_settings
 * @setLogger - returns $logger
 * @deleteUser - returns $result
 *
 **/

class DeleteUserModel
{

    private $database_connection_settings;
    private $database_wrapper;
    private $sql_queries;
    private $monolog_logger;

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

    public function deleteUser($user_id): bool
    {
        $wrapper = $this->database_wrapper;

        $wrapper->setSqlQueries($this->sql_queries);
        $wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $wrapper->makeDatabaseConnection();

        $result = $wrapper->deleteSingleUser($user_id);

        return $result;
    }

}
