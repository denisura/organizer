<?php
/**
 * Files contains declaration of Database class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Database class
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class Database
{
    /**
     *
     * @var Database
     */
    private static $_singleton;
    /**
     *
     * @var MySQLi
     */
    protected $_dbh;
    /**
     * Class constructor.
     * Connects to database
     * @throws Exception
     * @return Database
     */
    private function __construct ()
    {
        $registry = Registry::getInstance();
        $dbParams = $registry->get('database');
        $this->_dbh = mysqli_connect($dbParams['host'], $dbParams['user'], $dbParams['password'], $dbParams['dbname']);
        if (mysqli_connect_errno()) {
            throw new Exception('Connect failed: ' . mysqli_connect_error());
            exit();
        }
        return self;
    }
    /**
     * Gets Database instance
     */
    public static function getInstance ()
    {
        if (is_null(self::$_singleton)) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }
    /**
     * Gets MySQLi instance
     */
    public function getDbh ()
    {
        return $this->_dbh;
    }
}