<?php
/**
 * Files contains declaration of ErrorController class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * ErrorController class
 * Contains actions to show error messages
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 */
class ErrorController extends Controller
{
    /**
     * Default action
     */
    public function indexAction ()
    {
        $this->setLayout('service');
    }
}