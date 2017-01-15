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
/**
 * Class Bootstrap used to setup environment and initiate application
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class Bootstrap
{
    /**
     * Class constructor.
     * Loads config variables in Reistry
     * @param string $config path to COnfig file
     * @uses Registry::getInstance()
     */
    public function __construct ($config)
    {
        session_start();
        $ini_array = parse_ini_file($config, true);
        $registry = Registry::getInstance();
        if (! empty($ini_array)) {
            foreach ($ini_array as $key => $value) {
                $registry->set($key, $value);
            }
        }
    }
    /**
     * Method used to initiate Bootstrap method which starts with _init.
     * If not specified all such methods will be initiated
     * @param array $extentions
     * @return Bootstrap
     */
    public function initExtentions (array $extentions = NULL)
    {
        $methods = get_class_methods($this);
        if ($extentions === null) {
            foreach ($methods as $method) {
                if (preg_match("/^_init.*/", $method)) {
                    $this->$method();
                }
            }
        } else {
            foreach ($extentions as $extention) {
                $method = '_init' . ucfirst($extention);
                if (in_array($method, $methods)) {
                    $this->$method();
                }
            }
        }
        return $this;
    }
    /**
     *
     * @param array $extentions
     * @return Bootstrap
     */
    public function bootstrap (array $extentions = null)
    {
        $this->initExtentions($extentions);
        return $this;
    }
    /**
     * Creates database instance
     * @uses Database::getInstance()
     */
    protected function _initDatabase ()
    {
        try {
            Database::getInstance();
        } catch (Exception $e) {}
    }
    /**
     * Runs application
     * @uses FrontController::getInstance()
     */
    public function run ()
    {
        try {
            $frontController = FrontController::getInstance();
            $frontController->dispatch();
        } catch (Exception $exception) {}
    }
}
/**
 * Function used to autoload classes
 * @param string $className
 */
function __autoload ($className)
{
    $path = str_ireplace('_', '/', $className);
    if (@include_once $path . '.php') {
        return true;
    }
}