<?php
/**
 * File to facilitate the creation of users output and functionality
 * Calls on relevant views, containers and files
 * @createNewUser (array) returns $result
 * @createOutput & @createFormOutput - returns $view
 *
 **/

class CreateUserController
{
    public function createNewUser(object $app, object $request): array
    {
        $login_user_controller = $app->getContainer()->get('LoginUserController');
        $dirty_parameters = $request->getParsedBody();

        $cleaned_parameters = $login_user_controller->validateInput($app, $dirty_parameters);

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

        $clean_username = $cleaned_parameters['clean_username'];
        $password_plaintext = $cleaned_parameters['password_plaintext'];
        $hashed_password = $cleaned_parameters['hashed_password'];

        $result['username'] = $clean_username;

        $validate_user->setUsername($clean_username);
        $validate_user->setPasswordAsString($password_plaintext);
        $validate_user->setPasswordHash($hashed_password);
        $validate_user->setDatabaseConnectionSettings($database_connection_settings);
        $validate_user->setDatabaseWrapper($database_wrapper);
        $validate_user->setSqlQueries($sql_queries);
        $validate_user->setBcryptWrapper($bcrypt_wrapper);
        $validate_user->setLogger($logger);

        $result['success'] = $validate_user->checkUserInDatabase($clean_username, $password_plaintext, $hashed_password);

        return $result;
    }

    public function createFormOutput(object $app, object $view, object $response)
    {
        $create_user_view = $app->getContainer()->get('createUserView');
        $create_user_view->createUserForm($view, $response);
    }

    public function createOutput(object $app, object $view, object $response, object $request): void
    {
        $result = $this->createNewUser($app, $request);
        $create_user_view = $app->getContainer()->get('createUserView');
        $logger = $app->getContainer()->get('monologLogger');

        $username = $result['username'];
        $success = $result['success'];

        $username_as_array = (array) $username;

        if ($success === true)
        {
            $create_user_view->userCreationSuccess($view, $response, $username);
            $logger->info('Successful account creation by ADMIN: ', $username_as_array);
        }

        if ($success === false)
        {
            $create_user_view->userCreationFailure($view, $response, $username);
            $logger->info('Unsuccessful account creation by ADMIN: ', $username_as_array);
        }
    }
}
