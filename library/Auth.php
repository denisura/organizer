<?php
/**
 * Files contains declaration of Auth class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Authontication class
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class Auth
{
    /**
     *
     * @var string
     */
    const INVALID_CREDENTIALS = 'Invalid combination username and password';
    /**
     *
     * @var Auth
     */
    private static $_auth = null;
    /**
     * Gets instance of Auth class
     */
    public static function getInstance ()
    {
        if (self::$_auth === null) {
            self::$_auth = new self();
        }
        return self::$_auth;
    }
    /**
     * Class constructor
     */
    private function __construct ()
    {
        if (! $this->isLoggedIn()) {
            self::setDefault();
        }
    }
    /**
     * Authenticate user
     * @param $username
     * @param $password
     * @throws Exception_Auth
     */
    public function login ($username, $password)
    {
        $user = new User();
        $user->load($username, $password);
        if ($user->getUserId()) {
            session_regenerate_id();
            $_SESSION['userId'] = $user->getUserId();
            $_SESSION['username'] = $user->getUsername();
        } else {
            throw new Exception_Auth(self::INVALID_CREDENTIALS);
        }
    }
    /**
     * Set default values
     */
    private function setDefault ()
    {
        $_SESSION['userId'] = 0;
        $_SESSION['username'] = '';
    }
    /**
     * Logout user
     */
    public function logout ()
    {
        session_destroy();
        session_regenerate_id();
        self::setDefault();
    }
    /**
     * Check if user logged in
     */
    public function isLoggedIn ()
    {
        if (isset($_SESSION['userId']) && (int) $_SESSION['userId']
            && isset($_SESSION['username']) && strlen($_SESSION['username'])) {
            return true;
        }
        return false;
    }
    /**
     * Gets user id of current authenticated user
     * @return integer $_userId
     */
    public function getUserId ()
    {
        return $_SESSION['userId'];
    }
    /**
     * Gets username of current authenticated user
     * @return the $_username
     */
    public function getUsername ()
    {
        return $_SESSION['username'];
    }
}