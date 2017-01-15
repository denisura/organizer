<?php
/**
 * Files contains declaration of FrontController class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Used to manage controllers and views
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class FrontController
{
    /**
     *
     * @var FrontController
     */
    private static $_singleton;
    /**
     *
     * @var Controller
     */
    private static $_controller;
    /**
     *
     * @var Object
     */
    private $view;
    /**
     * Class constructor
     * Verifies that user is logged in. Also loads specified controller
     * @throws Exception_Controller
     * @uses Auth::getInstance()
     */
    private function __construct ()
    {
        try {
            $controller = strtolower($_REQUEST['controller']);
            if (! strlen($controller) || ! isset($_REQUEST['controller'])) {
                $controller = 'index';
            } elseif (! preg_match("/^[a-z]{3,}$/", $controller)) {
                throw new Exception_Controller("Invalid FrontController $controller called");
            }
            $controllerName = ucwords($controller) . 'Controller';
            if (! __autoload($controllerName)) {
                throw new Exception_Controller("Invalid FrontController $controllerName called");
            }
            $auth = Auth::getInstance();
            if ($auth->isLoggedIn()) {
                self::$_controller = new $controllerName();
            } else {
                self::$_controller = new AuthController();
            }
        } catch (Exception_Controller $e) {
             $_REQUEST['action'] = '';
            self::$_controller = new ErrorController();
        } catch (Exception_Action $e) {
            $_REQUEST['action'] = '';
            self::$_controller = new ErrorController();
        }
        $this->view = self::$_controller->view;
        return self::$_singleton;
    }
    /**
     * Creates instance of itself
     * @return FrontController
     */
    public static function getInstance ()
    {
        if (is_null(self::$_singleton)) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }
    /**
     * Used to show view.
     * View can be ebedded in layout or can be displayed without alyout
     */
    public function dispatch ()
    {
        //generate View
        $view = self::$_controller->getView();
        $viewPrefix = strtolower(preg_replace("/(.*)Controller$/", '\\1', self::$_controller->_controller));
        $viewFile = APPLICATION_PATH . '/views/' . $viewPrefix . "/{$view}.phtml";
        if (! file_exists($viewFile)) {
            throw new Exception_View("Invalid $viewPrefix/$view called ");
        }
        ob_start();
        include_once ($viewFile);
        $this->content = ob_get_contents();
        ob_end_clean();
        //generate View
        if (self::$_controller->getDisableLayout()) {
            echo $this->content;
        } else {
            $layout = self::$_controller->_layout;
            $layoutFile = APPLICATION_PATH . "/layouts/{$layout}.phtml";
            if (! file_exists($layoutFile)) {
                echo $layoutFile;
                throw new Exception("Invalid layout $layout called ");
            }
            ob_start();
            include_once ($layoutFile);
            $this->content = ob_get_contents();
            ob_end_clean();
            echo $this->content;
        }
    }
}