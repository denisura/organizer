<?php
/**
 * Files contains declaration of AuthController class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * AuthController class
 * Contains actions to Used to login and logout user
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 */
class AuthController extends Controller
{
    /**
     * Default action
     * Used to login user
     */
    public function indexAction ()
    {
        $this->view->message = 'This is message from controller';
        $this->view->pageTitle = 'Login';
        $this->setLayout('service');
        if (isset($_POST['username'])) {
            $postData['username'] = trim(strip_tags($_POST['username']));
        }
        if (isset($_POST['password'])) {
            $postData['password'] = trim(strip_tags($_POST['password']));
        }
        if (isset($postData)) {
            if (empty($postData['password']) || empty($postData['username'])) {
                $this->view->error = 'Please enter username and password';
                return;
            }
            if (preg_match("/[^\w\d]+/", $postData['password']) || preg_match("/[^\w\d]+/", $postData['username'])) {
                $this->view->error = Auth::INVALID_CREDENTIALS;
                return;
            }
            //authenticate user
            try {
                $auth = Auth::getInstance();
                $auth->login($postData['username'], $postData['password']);
            } catch (Exception_Auth $e) {
                $this->view->error = Auth::INVALID_CREDENTIALS;
                return;
            }
            if ($auth->isLoggedIn()) {
                header("Location: http://{$_SERVER['HTTP_HOST']}");
                exit();
            }
        } else {
            $postData['password'] = '';
            $postData['username'] = '';
        }
    }
    /**
     * Logout Action
     */
    public function logoutAction ()
    {
        $auth = Auth::getInstance();
        $auth->logout();
        if (! $auth->isLoggedIn()) {
            header("Location: http://{$_SERVER['HTTP_HOST']}");
            exit();
        }
    }
}