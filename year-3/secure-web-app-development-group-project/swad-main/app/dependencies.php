<?php

/**
 * dependencies file to create and instantiate containers
 * tells which files to link with
 * tells which files to use
 * adds Slim specific extension
 * handles the monolog logging container
 *
 * @container:Twig returns: view
 * @containers:mailer & phpMailer returns: new Mailer and mail
 *
 * NB: The following containers returns are of the same name as its container:
 * @containers: createTelemetryOutputView, processTelemetryModel, createTelemetryModel, createTelemetryController,
 * createTelemetryView, processTelemetryController, processTelemetryView, obtainTelemetryController, obtainTelemetryView,
 * logoutView, logoutController, loginView, LoginUserController, homepageController, homepageView, dashboardView, sessionWrapper,
 * sessionModel, bcryptWrapper, formValidator, sqlQueries, databaseWrapper, validateUser, SoapWrapper, soapValidator, telemetryValidator,
 * telemetryDetailsModel, validateTelemetryData
 *
 * @container:monologLogger returns logger
 * @container:sessionLogger returns logger
 **/

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Slim\Views\Twig;
use TelemetryController\SessionWrapper;
use TelemetryController\SoapWrapper;

use TelemetryModel\TelemetryValidator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

#use TelemetryController\SoapWrapper.php;
#use TelemetryModel\FormValidator;
#use TelemetryModel\SQLQueries;
#use Telemetry\DatabaseWrapper;
#use TelemetryModel\SQLQueries;
#use TelemetryModel\UserValidator;

# Misc
include '/p3t/phpappfolder/includes/telemetry/app/src/misc/SQLQueries.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/misc/Mailer.php';

#Wrappers
include '/p3t/phpappfolder/includes/telemetry/app/src/wrappers/BcryptWrapper.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/wrappers/SessionWrapper.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/wrappers/DatabaseWrapper.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/wrappers/SoapWrapper.php';

#Validators
include '/p3t/phpappfolder/includes/telemetry/app/src/validators/ValidateUser.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/validators/FormValidator.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/validators/TelemetryValidator.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/validators/SoapValidator.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/validators/ValidateTelemetryData.php';


# Models
include '/p3t/phpappfolder/includes/telemetry/app/src/model/SessionModel.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/model/TelemetryDetailsModel.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/model/CreateTelemetryModel.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/model/ProcessTelemetryModel.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/model/AdminDashboardModel.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/model/DeleteUserModel.php';


# Views
include '/p3t/phpappfolder/includes/telemetry/app/src/view/HomepageView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/LoginView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/LogoutView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/ObtainTelemetryView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/ProcessTelemetryView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/CreateTelemetryView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/CreateTelemetryOutputView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/DeleteUserView.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/view/CreateUserView.php';

# Controllers
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/LogoutController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/HomepageController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/LoginUserController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/ObtainTelemetryController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/ProcessTelemetryController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/CreateTelemetryController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/AdminDashboardController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/DeleteUserController.php';
include '/p3t/phpappfolder/includes/telemetry/app/src/controller/CreateUserController.php';


    
$container['view'] = function ($container): Twig {
    $view = new Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true // This line should enable debug mode
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['mailer'] = function(): Mailer {
    return new Mailer;
};

$container['phpMailer'] = function(): PHPMailer {

    $mail = new PHPMailer(true);

    $mail->isSMTP();     //Send using SMTP
    $mail->Host = 'smtp-mail.outlook.com';    //Set the SMTP server to send through
    $mail->SMTPAuth = true;              //Enable SMTP authentication
    $mail->Username = 'c1798693cdbced@outlook.com'; //SMTP username
    $mail->Password = 'phpmailer-swad-email-test';  //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
    $mail->Port = 587;

    $mail->setFrom('c1798693cdbced@outlook.com', 'PHPMailer');
    $mail->addAddress('c1798693cdbced@outlook.com', 'PHPMailer');
    $mail->isHTML(false);

    return $mail;
};


$container['createUserController'] = function(): CreateUserController {
    return new CreateUserController();
};

$container['createUserView'] = function (): CreateUserView {
    return new CreateUserView();
};

$container['deleteUserView'] = function (): DeleteUserView {
    return new DeleteUserView();
};

$container['deleteUserModel'] = function (): DeleteUserModel {
    return new DeleteUserModel;
};

$container['deleteUserController'] = function(): DeleteUserController {
    return new DeleteUserController;
};

$container['adminDashboardModel'] = function(): AdminDashboardModel {
    return new  AdminDashboardModel;
};

$container['adminDashboardController'] = function(): AdminDashboardController {
    return new AdminDashboardController;
};

$container['createTelemetryOutputView'] = function(): CreateTelemetryOutputView {
    return new CreateTelemetryOutputView;
};

$container['processTelemetryModel'] = function(): ProcessTelemetryModel {
    return new ProcessTelemetryModel();
};

$container['createTelemetryModel'] = function(): CreateTelemetryModel {
    return new CreateTelemetryModel();
};

$container['createTelemetryController'] = function(): CreateTelemetryController {
    return new CreateTelemetryController();
};

$container['createTelemetryView'] = function(): CreateTelemetryView {
    return new CreateTelemetryView();
};

$container['processTelemetryController'] = function(): ProcessTelemetryController {
    return new ProcessTelemetryController();
};

$container['processTelemetryView'] = function(): ProcessTelemetryView {
    return new ProcessTelemetryView();
};

$container['obtainTelemetryController'] = function(): ObtainTelemetryController {
    return new ObtainTelemetryController();
};

$container['obtainTelemetryView'] = function(): ObtainTelemetryView {
    return new ObtainTelemetryView();
};

$container['logoutView'] = function(): LogoutView {
    return new LogoutView();
};

$container['logoutController'] = function(): LogoutController {
    return new LogoutController();
};

$container['loginView'] = function(): LoginView {
    return new LoginView();
};


$container['LoginUserController'] = function(): LoginUserController{
    return new LoginUserController();
};

$container['homepageController'] = function(): HomepageController {
    return new HomepageController();
};

$container['homepageView'] = function(): HomepageView {
    return new HomepageView();
};

$container['sessionWrapper'] = function(): SessionWrapper {
    $session_wrapper = new SessionWrapper();

    return $session_wrapper;
};

$container['sessionModel'] = function(): SessionModel {
    $session_model = new SessionModel();

    return $session_model;
};

$container['bcryptWrapper'] = function(): BcryptWrapper {
    $bcrypt_wrapper = new BcryptWrapper();

    return $bcrypt_wrapper;
};

$container['formValidator'] = function(): FormValidator {
    return new FormValidator();
};

$container['sqlQueries'] = function (): SQLQueries {
    $sql_queries = new SQLQueries();

    return $sql_queries;
};

$container['databaseWrapper'] = function (): DatabaseWrapper {
    $db_handle = new DatabaseWrapper();

    return $db_handle;
};

$container['validateUser'] = function (): ValidateUser {
    $validate_user = new ValidateUser();

    return $validate_user;
};

$container['SoapWrapper'] = function (): \SoapWrapper {
    $soap_wrapper = new \SoapWrapper();

    return $soap_wrapper;
};

$container['soapValidator'] = function (): SoapValidator {
    $soap_validator = new SoapValidator();

    return $soap_validator;
};

$container['telemetryValidator'] = function (): TelemetryValidator {
    $telemetry_validator = new TelemetryValidator();

    return $telemetry_validator;
};

$container['telemetryDetailsModel'] = function (): TelemetryDetailsModel {
    $telemetry_details_model = new TelemetryDetailsModel();

    return $telemetry_details_model;
};

$container['validateTelemetryData'] = function (): ValidateTelemetryData {
    $validate_telemetry_data = new ValidateTelemetryData();

    return $validate_telemetry_data;
};


# Monolog logging
$container['monologLogger'] = function () {

    $logger = new Logger('logger');

    $date_format = "[j/n/Y g:i A]";
    $output = "%datetime%  (%level_name%) -> %message% %context% %extra%\n";
    $formatter = new LineFormatter($output, $date_format);

    $user_login_log_info = LOG_USER_LOGIN_INFO;
    $stream_information = new StreamHandler($user_login_log_info, Logger::INFO, false);
    $stream_information->setFormatter($formatter);
    $logger->pushHandler($stream_information);

    $db_log_notices = LOG_DB_NOTICE;
    $stream_notices = new StreamHandler($db_log_notices, Logger::NOTICE, false);
    $stream_notices->setFormatter($formatter);
    $logger->pushHandler($stream_notices);

    $db_log_warnings = LOG_DB_WARNING;
    $stream_warnings = new StreamHandler($db_log_warnings, Logger::WARNING, false);
    $stream_warnings->setFormatter($formatter);
    $logger->pushHandler($stream_warnings);

    return $logger;
};

$container['sessionLogger'] = function () {

    $logger = new Logger('session_logger');

    $date_format = "[j/n/Y g:i A]";
    $output = "%datetime%  (%level_name%) -> %message% %context% %extra%\n";
    $formatter = new LineFormatter($output, $date_format);

    $session_logger = LOG_SESSION_INFO;
    $stream_warnings = new StreamHandler($session_logger, Logger::INFO, false);
    $stream_warnings->setFormatter($formatter);
    $logger->pushHandler($stream_warnings);

    return $logger;
};
