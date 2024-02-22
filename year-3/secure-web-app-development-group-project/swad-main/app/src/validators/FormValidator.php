<?php

/**
 * file to validate the form parameters
 * 
 * @__construct(): called on when script runs, takes any/all params
 * @cleanParameters: send params to array $dirty_parameters, returns $cleaned_parameters
 * @sanitiseUsername: sets username to $dirty_username, returns $clean_username
 *
 */


class FormValidator
{

    public function __construct()
    {

    }

    public function cleanParameters(array $dirty_parameters): array
    {
        $cleaned_parameters = [];

        if (!empty($dirty_parameters))
        {
            $dirty_username = $dirty_parameters['username'];
            $dirty_password = $dirty_parameters['password'];

            $clean_username = $this->sanitiseUsername($dirty_username);

            $cleaned_parameters['clean_username'] = $clean_username;

            if (!empty($dirty_password))
            {
                $cleaned_parameters['password_plaintext'] = $dirty_password;
            }
            else
            {
                $cleaned_parameters['password_plaintext'] = null;
            }


        }

        return $cleaned_parameters;

    }

    public function sanitiseUsername(string $dirty_username): ?string
    {
        if (!empty($dirty_username))
        {
            $clean_username = filter_var($dirty_username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (ctype_alnum($clean_username))
            {
                $username = $clean_username;
            }
            else
            {
                $username = null;
            }
        }
        else
        {
            $username = null;
        }

        return $username;
    }

    public function validateId(int $dirty_user_id): int
    {
        if (!empty($dirty_user_id))
        {
            $clean_user_id = filter_var($dirty_user_id, FILTER_SANITIZE_NUMBER_INT);
        }
        else
        {
            $clean_user_id = 0;
        }

        return $clean_user_id;
    }


}
