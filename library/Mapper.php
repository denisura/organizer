<?php
/**
 * Files contains declaration of Mapper class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Abstract class used to map models with database
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @abstract
 */
abstract class Mapper
{
    /**
     *
     * @var Database
     */
    protected $_db;
    /**
     * Class constructor
     * @uses Database::getInstance()
     */
    public function __construct ()
    {
        $this->_db = Database::getInstance()->getDbh();
    }
}