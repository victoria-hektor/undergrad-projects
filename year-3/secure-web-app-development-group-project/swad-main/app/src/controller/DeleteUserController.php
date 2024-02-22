<?php
/**
 * File to facilitate the deletion of users output and functionality
 * Calls on relevant views, containers and files
 * @deleteUser (array) returns $result
 * @createOutput & @createFormOutput - returns $view
 *
 **/

class deleteUserController
{
    public function deleteUser(object $app, object $request): array
    {
        $validator = $app->getContainer()->get('formValidator');

        $dirty_parameters = $request->getParsedBody();
        $clean_user_id = $validator->validateId($dirty_parameters['user_id']);

        $result['user_id'] = $clean_user_id;

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];
        $database_wrapper = $app->getContainer()->get('databaseWrapper');
        $sql_queries = $app->getContainer()->get('sqlQueries');
        $logger = $app->getContainer()->get('monologLogger');
        $delete_user_model = $app->getContainer()->get('deleteUserModel');

        $database_wrapper->setSqlQueries($sql_queries);
        $database_wrapper->setDatabaseConnectionSettings($database_connection_settings);
        $database_wrapper->setLogger($logger);


        $delete_user_model->setDatabaseConnectionSettings($database_connection_settings);
        $delete_user_model->setDatabaseWrapper($database_wrapper);
        $delete_user_model->setSqlQueries($sql_queries);
        $delete_user_model->setLogger($logger);

        $result['success'] = $delete_user_model->deleteUser($clean_user_id);

        return $result;
    }


    public function createOutput(object $app, object $view, object $request, object $response)
    {
        $delete_user_view = $app->getContainer()->get('deleteUserView');
        $logger = $app->getContainer()->get('monologLogger');
        $result = $this->deleteUser($app, $request);

        $user_id = $result['user_id'];
        $session_value = $_SESSION['username'];

        $id_as_array = (array) $user_id;

        if ($result['success'] === true)
        {
            $delete_user_view->deleteOutput($view, $response, $session_value, $user_id);
            $logger->info('Account deletion attempt by ADMIN with USERID of: ', $id_as_array);
        }


    }

    public function createFormOutput(object $app, object $view, object $response)
    {
        $delete_user_view = $app->getContainer()->get('deleteUserView');
        $session_value = $_SESSION['username'];

        $delete_user_view->deleteUserForm($view, $response, $session_value);
    }

}
