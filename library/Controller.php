<?php
/**
 * Files contains declaration of abstract Controller class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @abstract
 */
abstract class Controller
{
    /**
     *
     * @var string current action
     */
    protected $_action;
    /**
     *
     * @var string current controller
     */
    public $_controller;
    /**
     *
     * @var string current View
     */
    public $view;
    /**
     *
     * @var boolean
     */
    protected $_disableLayout;
    /**
     *
     * @var string
     */
    protected $_view = 'index';
    /**
     * This method called before any Actions.
     * Can be redeclared in child class
     */
    public function init ()
    {}
    /**
     * Class constructor
     * Sets default action index if action is not specified
     * @throws Exception_Action
     */
    public function __construct ()
    {
        $this->_controller = get_class($this);
        $this->_action = $_REQUEST['action'];
        $this->_layout = 'default';
        if (! strlen($this->_action)) {
            $this->_action = 'index';
        }
        $actionName = $this->_action . 'Action';
        if (method_exists($this, $actionName)) {
            $this->_view = $this->_action;
            $this->init();
            $this->$actionName();
        } else {
            throw new Exception_Action("Invalid Action $actionName called");
        }
    }
    /**
     * Used to set view
     * @param string $view
     */
    protected function setView ($view)
    {
        $this->_view = $view;
    }
    /**
     * Used to set layout
     * @param unknown_type $layout
     */
    protected function setLayout ($layout)
    {
        $this->_layout = $layout;
    }
    /**
     * Used to disable layout
     */
    public function disableLayout ()
    {
        $this->_disableLayout = true;
    }
    /**
     * disbaleLayout getter
     */
    public function getDisableLayout ()
    {
        return $this->_disableLayout;
    }
    /**
     * View getter
     */
    public function getView ()
    {
        return $this->_view;
    }
}