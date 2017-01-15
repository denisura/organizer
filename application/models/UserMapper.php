<?php
/**
 * Files contains declaration of UserMapper class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * UserMapper class
 * Used to save and get User related data in database
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class UserMapper extends Mapper
{
    /**
     * Load user data
     * If user with specified username and password exists load data
     * @param string $username
     * @param string $password
     * @param User $user
     */
    public function load ($username, $password, User $user)
    {
        $userId = 0;
        $sql = 'SELECT userId,username FROM user
					WHERE username = ? AND userhash = passwordHash(?)
					LIMIT 1';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            /* bind variables to prepared statement */
            $stmt->bind_result($userId, $username);
            /* fetch values */
            $stmt->fetch();
            $user->setUserId($userId)->setUsername($username);
            /* close statement */
            $stmt->close();
        }
    }
}
