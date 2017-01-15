<?php
/**
 * Files contains declaration of Model class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Abstract class used to manage models
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @abstract
 */
abstract class Model
{
    /**
     *
     * @var string
     */
    const EXCEPTION_INVALID_PROPERTY = 'Invalid property';
    /**
     *
     * @var Mapper
     */
    protected $_mapper;
    /**
     * Class constructor
     *
     * @param array $options
     */
    public function __construct (array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    /**
     * Magic method used to set class property
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set ($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception(self::EXCEPTION_INVALID_PROPERTY);
        }
        $this->$method($value);
    }
    /**
     * Magic method used to get class property
     * @param string $name
     * @throws Exception
     */
    public function __get ($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists($this, $method)) {
            throw new Exception(self::EXCEPTION_INVALID_PROPERTY);
        }
        return $this->$method();
    }
    /**
     * Sets class properties
     * @param array $options
     * @return Model
     */
    public function setOptions (array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    /**
     *
     * @param $mapper
     * @return Model
     */
    public function setMapper ($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }
}