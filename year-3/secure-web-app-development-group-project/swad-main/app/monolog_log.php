<?php

/**
 * Create Monolog Log File
 * Creates new instances
 * Specifies where to create instance and log details
 * Streamhandler creates new channel to log info in
 *
 * */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require '../../vendor/autoload.php';

// create a new log instance
$log = new logger (name: "logger");

// specify where to log info/which files etc
$logs_file_path = '/p3t/phpappfolder/logs/';
$logs_file_name_warning = 'tester_warning.log';
$logs_file_warning = $logs_file_path . $logs_file_name_warning;

$logs_file_name_notice = 'tester_notice.log';
$logs_file_notice = $logs_file_path . $logs_file_name_notice;

// create a channel to log info in
$log->pushHandler(new StreamHandler($logs_file_name_warning, level: Logger::WARNING));

$log->pushHandler(new StreamHandler($logs_file_notice, level: Logger::NOTICE));

echo 'Adding entries to the logging file';

$log->notice(message: 'Testing the Monolog library');
$log->warning(message: 'Testing Warnings..');