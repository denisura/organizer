<?php
/**
 * Entrance point for the application
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/controllers'),
    realpath(APPLICATION_PATH . '/views'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path()
)));
require_once realpath(APPLICATION_PATH.'/Bootstrap.php');
// Create application, bootstrap, and run
$application = new Bootstrap(
    APPLICATION_PATH . '/configs/'.APPLICATION_ENV.'.application.ini'
);
$application->bootstrap()
            ->run();