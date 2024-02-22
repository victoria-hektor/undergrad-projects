<?php

/**
 * file to create the dashboard view, returns either error or success
 * class DashboardView, public function loginError() returns $login_error, dashboardSuccess() returns $dashboard
 * sets out files to call on, methods, actions and paths
 *
 * @loginError - returns $login_error
 * @dashboardSuccess - returns $dashboard
 * @adminDashboardSuccess - returns $dashboard
 *
 **/

class DashboardView
{
    public function loginError(): array
    {
        $login_error['css_path'] = CSS_PATH . 'homepage.css';
        $login_error['bootstrap'] = BOOTSTRAP_PATH;
        $login_error['action'] = 'dashboard';
        $login_error['initial_input_box_value'] = null;
        $login_error['page_title'] = 'Login/Register';

        return $login_error;
    }

    public function dashboardSuccess(): array
    {
        $dashboard['css_path'] = CSS_PATH . 'dashboard.css';
        $dashboard['bootstrap'] = BOOTSTRAP_PATH;
        $dashboard['method'] = 'post';
        $dashboard['action'] = 'obtain-telemetry';
        $dashboard['action_create'] = 'create-telemetry';
        $dashboard['page_title'] = 'Dashboard';
        $dashboard['obtain_messages'] = 'Obtain EE messages';
        $dashboard['create_message'] = 'Create an SMS message';
        $dashboard['logout_path'] = LOGOUT_PATH;
        $dashboard['username'] = $_SESSION['username'];

        return $dashboard;
    }
    
    public function adminDashboardSuccess(): array
    {
        $dashboard['css_path'] = CSS_PATH . 'dashboard.css';
        $dashboard['bootstrap'] = BOOTSTRAP_PATH;
        $dashboard['method'] = 'post';
        $dashboard['action'] = 'obtain-telemetry';
        $dashboard['action_create'] = 'create-telemetry';
        $dashboard['page_title'] = 'Admin Interface';
        $dashboard['obtain_messages'] = 'Obtain EE messages';
        $dashboard['create_message'] = 'Create an SMS message';
        $dashboard['logout_path'] = LOGOUT_PATH;
        $dashboard['username'] = $_SESSION['username'];

        return $dashboard;
    }
}
