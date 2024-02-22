<?php
/**
 * File to facilitate the admin dashboard output and functionality
 * Calls on relevant views, containers and files
 * @getAllUsers (array) returns $user_list
 * @createOutput - returns $view
 *
 **/

class AdminDashboardController
{
    public function getAllUsers(object $app): array
    {
        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];
        $database_wrapper = $app->getContainer()->get('databaseWrapper');
        $sql_queries = $app->getContainer()->get('sqlQueries');
        $logger = $app->getContainer()->get('monologLogger');
        $admin_dashboard_model = $app->getContainer()->get('adminDashboardModel');

        $database_wrapper->setSqlQueries($sql_queries);
        $database_wrapper->setDatabaseConnectionSettings($database_connection_settings);
        $database_wrapper->setLogger($logger);


        $admin_dashboard_model->setDatabaseConnectionSettings($database_connection_settings);
        $admin_dashboard_model->setDatabaseWrapper($database_wrapper);
        $admin_dashboard_model->setSqlQueries($sql_queries);
        $admin_dashboard_model->setLogger($logger);

        $user_list = $admin_dashboard_model->retriveAllUsers();

        return $user_list;
    }


    public function createOutput(object $app, object $view, object $response, string $session_value)
    {
        $login_view = $app->getContainer()->get('loginView');
        $homepage_view = $app->getContainer()->get('homepageView');
        $user_list = $this->getAllUsers($app);

        if (!empty($_SESSION['username']))
        {
            $login_view->adminSuccess($view, $response, $session_value, $user_list);
        }
        else
        {
            $homepage_view->createHomepageForm($view, $response);
        }
    }
}
