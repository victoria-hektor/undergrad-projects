<?php
/**
 * Settings file performing the following tasks:
 * - Initialising configuration options:
 *      -   display errors
 *      -   html errors
 *      -   xdebug trace output
 *      -   xdebug trace format
 *      -
 * -    Define the constants:
 *      -   DIRSEP - Directory Separator
 *      -   CSS_PATH
 *      -   APP NAME
 *      -   LANDING_PAGE
 *
 * -    Define the remote service
 *
 * -    Define a settings associative array with the following details:
 *      -   class_path
 *      -   view:
 *          -   template_path
 *          -   twig:
 *              -   cache setting
 *              -   auto_reload setting
 *
 * -    Returns the settings
 */


ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'telemetry_system.%t');
ini_set('xdebug.trace_format', '1');

const DIRSEP = DIRECTORY_SEPARATOR;
const APP_NAME = 'Telemetry System';
const WDSL = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
const BCRYPT_COST = 12;
const TEAM_NAME = '21-3110-AC';

$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/css/';
$bootstrap_min = $css_path . 'assets/dist/css/bootstrap.min.css';
$logs_path = '/p3t/phpappfolder/includes/telemetry/logs/';
$logout_path = $app_url . '/logout';
$dashboard_path = $app_url . '/dashboard';

$db_notices_log = $logs_path . 'database_notices.log';
$db_warnings_log = $logs_path . 'database_warning.log';
$user_login_info_log = $logs_path . 'user_and_telemetry.log';
$session_info_log = $logs_path . 'session_info.log';

# Path constant definitions
define('DASHBOARD_PATH', $dashboard_path);
define('LOGOUT_PATH', $logout_path);
define('APP_URL', $app_url);
define('LOG_SESSION_INFO', $session_info_log);
define('LOG_USER_LOGIN_INFO', $user_login_info_log);
define('LOG_DB_WARNING', $db_warnings_log);
define('LOG_DB_NOTICE', $db_notices_log);
define('BOOTSTRAP_PATH', $bootstrap_min);
define('CSS_PATH', $css_path);
define('LANDING_PAGE', $_SERVER['SCRIPT_NAME']);


# PDO & database settings
$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]],
        'pdo_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'coursework_db',
            'port' => '3306',
            'user_name' => 'coursework_user',
            'user_password' => 'coursework_user_password',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ],
    ],
];

return $settings;


