<?php
/**
 * class and public function set a session log, returns a true or false value
 *
 * @setSessionLogger, 
 * @SetSessionValue, returns $value_set
 * @unsetSessionValue, returns $session_unset 
 * @getSessionValue, returns $value
 *
 */

namespace TelemetryController;

class SessionWrapper
{
    private $session_logger;

    public function setSessionLogger($session_logger)
    {
        $this->session_logger = $session_logger;
    }

    public function setSessionValue($key, $value): bool
    {
        $value_set = false;

        if (!empty($value))
        {
            $_SESSION[$key] = $value;

            if (isset($_SESSION))
            {
                $value_set = true;
            }
        }

        return $value_set;
    }

    public function unsetSessionValue($key): bool
    {
        $session_unset = false;

        if (isset($key))
        {
            $session = $_SESSION[$key];
            unset($session);
            $session_unset = true;
        }


        return $session_unset;
    }

    public function isSessionValueSet($key): bool
    {
        $session_value_set = false;

        if (isset($_SESSION[$key]))
        {
            $session_value_set = true;
        }

        return $session_value_set;
    }

    public function getSessionValue($key)
    {
        $value = null;
        $session = $_SESSION[$key];

        if (isset($session))
        {
            $value = $session;
        }

        return $value;
    }


}
