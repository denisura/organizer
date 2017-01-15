<?php
/**
 * Files contains declaration of User class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * User class
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 */
class User extends Model
{
    /**
     *
     * @var integer
     */
    protected $_userId = 0;
    /**
     *
     * @var string
     */
    protected $_username;
    /**
     *
     * @var string
     */
    const EXCEPTION_INVALID_PROPERTY = 'Invalid user property';
    /**
     * Username getter
     * @return string
     */
    public function getUsername ()
    {
        return $this->_username;
    }
    /**
     * Username setter
     * @param string $username
     * @return User
     */
    public function setUsername ($username)
    {
        $this->_username = (string) $username;
        return $this;
    }
    /**
     * User Id getter
     * @return integer
     */
    public function getUserId ()
    {
        return $this->_userId;
    }
    /**
     *
     * @param integer $id
     */
    public function setUserId ($id)
    {
        $this->_userId = (int) $id;
        return $this;
    }
    /**
     * Mapper getter
     * @return UserMapper
     */
    public function getMapper ()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new UserMapper());
        }
        return $this->_mapper;
    }
    /**
     * Load user info with specified username and password
     * @param string $username
     * @param string $password
     * @return User
     */
    public function load ($username, $password)
    {
        $this->getMapper()->load($username, $password, $this);
        return $this;
    }
}