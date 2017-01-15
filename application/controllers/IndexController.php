<?php
/**
 * Files contains declaration of IndexController class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * IndexController class
 * Contains actions to show user contacts
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 */
class IndexController extends Controller
{
    /**
     * Default action
     * Prepares user contacts to show in view
     */
    public function indexAction ()
    {
        $id = (int) $_REQUEST['id'];
        $this->view->pageTitle = 'Contacts';
        $page = (int) $_REQUEST['page'];
        $count = (int) $_REQUEST['count'];
        if (! $count) {
            $count = 5;
        }
        if (! $page) {
            $page = 1;
        }
        $userId = $_SESSION['userId'];
        $contact = new Contact();
        $contact->setUserId($userId);
        $this->view->contacts = $contact->fetchAll($count, $page);
        $this->view->page = $page;
        $this->view->count = $count;
    }
}