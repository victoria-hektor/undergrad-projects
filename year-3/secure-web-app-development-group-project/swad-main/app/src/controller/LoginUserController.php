<?php

/**
 * Class LoginUserController, 1 Public Function userRegisterOrLogin
 * 2 Functions - createOutput and validateInput
 *
 * @userRegisterOrLogin: function to validate and clean input
 * Calls on:settings, pdo_settings, databaseWrapper, sqlQueries, bcryptWrapper, validateUser, & monologLogger
 * Once validated, sets the variables and checks the database for exisitng users
 * Calls on sessionWrapper/Model/Logger, if successful, returns the session value
 *
 * @validateInput - to validate the user input, puts in array. Returns: cleanedParameters
 *
 * @createOutput to create output dependent on validation
 * calls view and loginView, sets global variable
 * outputs login success or failure depending on checked session output
 **/

class LoginUserController
{
    public function userRegisterOrLogin(object $app, object $request): ?string
    {
        $dirty_parameters = $request->getParsedBody();
        $cleaned_parameters = $this->validateInput($app, $dirty_parameters);

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];
        $database_wrapper = $app->getContainer()->get('databaseWrapper');
        $sql_queries = $app->getContainer()->get('sqlQueries');
        $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
        $validate_user = $app->getContainer()->get('validateUser');
        $logger = $app->getContainer()->get('monologLogger');

        $database_wrapper->setSqlQueries($sql_queries);
        $database_wrapper->setDatabaseConnectionSettings($database_connection_settings);
        $database_wrapper->setLogger($logger);

        $clean_username = null;
        $password_plaintext = null;

        if (isset($cleaned_parameters['clean_username']) && isset($cleaned_parameters['password_plaintext']))
        {
            $clean_username = $cleaned_parameters['clean_username'];
            $password_plaintext = $cleaned_parameters['password_plaintext'];
        }

        global $session_value;

        if (!is_null($clean_username) && !is_null($password_plaintext))
        {
            $hashed_password = $cleaned_parameters['hashed_password'];

            $validate_user->setUsername($clean_username);
            $validate_user->setPasswordAsString($password_plaintext);
            $validate_user->setPasswordHash($hashed_password);
            $validate_user->setDatabaseConnectionSettings($database_connection_settings);
            $validate_user->setDatabaseWrapper($database_wrapper);
            $validate_user->setSqlQueries($sql_queries);
            $validate_user->setBcryptWrapper($bcrypt_wrapper);
            $validate_user->setLogger($logger);
            $success = $validate_user->checkUserInDatabase($clean_username, $password_plaintext, $hashed_password);

            $session_wrapper = $app->getContainer()->get('sessionWrapper');
            $session_model = $app->getContainer()->get('sessionModel');
            $session_logger = $app->getContainer()->get('sessionLogger');

            $session_value = null;

            if ($success === true)
            {
                $session_model->setSessionWrapper($session_wrapper);
                $session_model->setSessionUsername($clean_username);
                $session_model->setSessionLogger($session_logger);
                $session_model->storeSessionValue();
                $session_value = $session_model->obtainSessionValue();
                $session_value = $session_value['username'];
            }
        }

        return $session_value;
    }


    public function validateInput(object $app, array $dirty_parameters): array
    {
        $form_validator = $app->getContainer()->get('formValidator');
        $cleaned_parameters = $form_validator->cleanParameters($dirty_parameters);

        $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');

        if (isset($cleaned_parameters['password_plaintext']))
        {
            $hashed_password = $bcrypt_wrapper->hashPassword($cleaned_parameters['password_plaintext']);
            $cleaned_parameters['hashed_password'] = $hashed_password;
        }

        return $cleaned_parameters;
    }

    public function createOutput(object $app, object $request, object $response, bool $session_exists): void
    {
        $login_view = $app->getContainer()->get('loginView');
        $admin_dashboard_controller = $app->getContainer()->get('adminDashboardController');
        $view = $app->getContainer()->get('view');

        global $session_value;

        if ($session_exists === false)
        {
            $session_value = $this->userRegisterOrLogin($app, $request);
        }

        if (is_null($session_value) && $session_exists === false)
        {
            $login_view->loginError($view, $response);
        }
        elseif ($_SESSION['username'] === "Admin")
        {
            $session_value = $_SESSION['username'];
            $admin_dashboard_controller->createOutput($app, $view, $response, $session_value);
        }
        else
        {
            $session_value = $_SESSION['username'];
            $login_view->loginSuccess($view, $response, $session_value);
        }

    }
}
