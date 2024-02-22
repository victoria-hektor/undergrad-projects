<?php
/**
 * class SessionModel, 6 public function (PF)
 *
 * private: sets to null and private: $username,$session_wrapper,$session_logger
 *
 * @__construct: sets $username,$session_wrapper,$session_logger to null as standard
 * @setSessionUsername: sets setSessionUsername as $username
 * @setSessionWrapper: sets setSessionWrapper as $session_wrapper
 * @setSessionLogger: sets setSessionLogger as $session_logger
 * @storeSessionValue: function to store the session values, returns $stored_session
 * @obtainSessionValue: function to obtain session details, $values set as null by default. returns $values
 *
 **/
class SessionModel
{
    private $username;
    private $session_wrapper;
    private $session_logger;

    public function __construct()
    {
        $this->username = null;
        $this->session_wrapper = null;
        $this->session_logger = null;
    }

    public function setSessionUsername($username)
    {
        $this->username = $username;
    }

    public function setSessionWrapper($session_wrapper)
    {
        $this->session_wrapper = $session_wrapper;
    }

    public function setSessionLogger($session_logger)
    {
        $this->session_logger = $session_logger;
    }

    public function storeSessionValue()
    {
        $stored_session = false;
        $username = $this->username;

        $set_username_result = $this->session_wrapper->setSessionValue('username', $username);

        if ($set_username_result !== false)
        {
            $stored_session = true;
        }

        $this->session_logger->info('Session successfully logged? ', (array) $stored_session);
        return $stored_session;
    }

    public function obtainSessionValue()
    {
        $values = null;

        $username = $this->session_wrapper->getSessionValue('username');
        $values['username'] = $username;

        $this->session_logger->info('Session key obtained: ', $values);

        return $values;

    }
}
