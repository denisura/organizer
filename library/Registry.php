<?php
/**
 * Files contains declaration of Registry class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Class used to store global data
 * Modified from Zend_Register class
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class Registry extends ArrayObject
{
    /**
     * Registry object provides storage for shared objects.
     * @var Registry
     */
    private static $_registry = null;
    /**
     * Retrieves the default registry instance.
     *
     * @return Registry
     */
    public static function getInstance ()
    {
        if (self::$_registry === null) {
            self::$_registry = new Registry();
        }
        return self::$_registry;
    }
    /**
     * getter method, basically same as offsetGet().
     *
     * This method can be called from an object of type Zend_Registry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index - get the value associated with $index
     * @return mixed
     * @throws Exception if no entry is registerd for $index.
     */
    public static function get ($index)
    {
        $instance = self::getInstance();
        if (! $instance->offsetExists($index)) {
            throw new Exception("No entry is registered for key '$index'");
        }
        return $instance->offsetGet($index);
    }
    /**
     * setter method, basically same as offsetSet().
     *
     * This method can be called from an object of type Zend_Registry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index The location in the ArrayObject in which to store
     *   the value.
     * @param mixed $value The object to store in the ArrayObject.
     * @return void
     */
    public static function set ($index, $value)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value);
    }
    /**
     * Returns TRUE if the $index is a named value in the registry,
     * or FALSE if $index was not found in the registry.
     *
     * @param  string $index
     * @return boolean
     */
    public static function isRegistered ($index)
    {
        if (self::$_registry === null) {
            return false;
        }
        return self::$_registry->offsetExists($index);
    }
    /**
     * Constructs a parent ArrayObject with default
     * ARRAY_AS_PROPS to allow acces as an object
     *
     * @param array $array data array
     * @param integer $flags ArrayObject flags
     */
    public function __construct ($array = array(), $flags = parent::ARRAY_AS_PROPS)
    {
        parent::__construct($array, $flags);
    }
    /**
     * @param string $index
     * @returns mixed
     *
     * Workaround for http://bugs.php.net/bug.php?id=40442 (ZF-960).
     */
    public function offsetExists ($index)
    {
        return array_key_exists($index, $this);
    }
}
