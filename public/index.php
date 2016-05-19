<?php
/**
 * Setting paths relative to application root
 * This makes life easier
 */
use System\Request;

chdir(dirname(__DIR__));

//Transform all errors in exceptions
set_error_handler(function($errno, $errstr = '', $errfile = '', $errline = '')
{
    if (!($errno & error_reporting())) {
        return;
    }

    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});

//To catch uncaught exception not treated properly (this is confusing, don't you think?)
set_exception_handler(function($exception){
    $logger = new System\Logger();
    $logger->critical('something goes badly here, Dude. See: {message} Backtrace: {backtrace}', ['message' => $exception->getMessage(), 'backtrace' => $exception->getTraceAsString()]);

    $request = new Request($_SERVER);
    $request->setStatus(500);
    $request->sendHeaders();
});
/**
 * Default timezone settings
 */
date_default_timezone_set("America/Sao_Paulo");

/**
 * APPLICATION ENVIRONMENT
 * Will used for load different configurations such as application config files, error_reporting and logging
 */
defined('ENVIRONMENT') || define('ENVIRONMENT', (getenv('ENVIRONMENT') ? getenv('ENVIRONMENT') : 'development'));

/**
 * different levels of error reporting for different environments
 */
if(defined('ENVIRONMENT')){
    switch (ENVIRONMENT){
        case 'development':
            ini_set('display_errors', 1);
            ini_set("log_errors", 1);
            //Set local folder to write logs
            ini_set("error_log", "data/log/php_log.log");
            error_reporting(E_ALL);
            break;
        case 'production':
            ini_set('display_errors', 0);
            error_reporting(0);
            break;
        default:
            exit('The application environment is not set correctly.');
    }
}
/**
 * Ensure all paths are on include_path
 */
set_include_path(implode(PATH_SEPARATOR, [
    realpath('/system'),
    realpath('/app'),
    get_include_path(),
]));

/**
 * Autoloading
 */
require 'auto_loader.php';

/**
 * Bootstrap
 */
require 'application/Bootstrap.php';

/* End of file index.php */