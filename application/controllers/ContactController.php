<?php
/**
 * Files contains declaration of ContactController class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * ContactController class
 * Contains actions to view, add, edit and delete contact
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 */
class ContactController extends Controller
{
    /**
     * Used to set contact id
     */
    public function init ()
    {
        $this->contactId = abs((int) $_REQUEST['id']);
    }
    /**
     * Default action
     * Used to show contact
     */
    public function indexAction ()
    {
        $this->view->pageTitle = 'View Contact';
        $userId = $_SESSION['userId'];
        $contact = new Contact();
        if ($this->contactId && $contact->find($this->contactId, $userId)->getContactId()) {
            $this->view->pageTitle = "View Contact: {$contact->getContactLastName()}, {$contact->getContactFirstName()}";
            $this->view->contact = $contact;
        } else {
            $this->setView('nocontact');
            $this->view->pageTitle = 'No record found';
        }
    }
    /**
     * Action to add/save/delete contact
     * Validates form before save contact data
     */
    public function manageAction ()
    {
        $statesPath = realpath(APPLICATION_PATH . '/../data/states.ini');
        $states = parse_ini_file($statesPath);
        $formValues = $formErrors = array();
        $this->view->pageTitle = 'View Contact';
        $userId = $_SESSION['userId'];
        $contact = new Contact();

        if ($this->contactId == 0 || $contact->find($this->contactId, $userId)->getContactId()) {
            if (isset($_POST['actionCase']) && $_POST['actionCase'] == 'Delete') {
                $contact->setContactId($_POST['contactId']);
                $contact->setUserId($_SESSION['userId']);
                if ($contact->delete()) {
                    header("Location: http://{$_SERVER['HTTP_HOST']}");
                    exit();
                }
            }
            if (isset($_POST['actionCase']) && $_POST['actionCase'] == 'Save') {
                //clean data
                $formValues['contactId'] = (int) $_POST['contactId'];
                $formValues['contactFirstName'] = trim(strip_tags(stripslashes($_POST['contactFirstName'])));
                $formValues['contactLastName'] = trim(strip_tags(stripslashes($_POST['contactLastName'])));
                $formValues['locationCity'] = trim(strip_tags(stripslashes($_POST['locationCity'])));
                $formValues['locationState'] = strtoupper(strip_tags(stripslashes($_POST['locationState'])));
                $formValues['contactZipCode'] = trim(strip_tags(stripslashes($_POST['contactZipCode'])));
                $formValues['interests'] = strtolower(trim(strip_tags(stripslashes($_POST['interests']))));
                //validate
                if (!isset($_SESSION['token']) || !isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
                    $formErrors['token'] = "Oops! You wanted send same data twice";
                }
                $length = strlen($formValues['contactFirstName']);
                if (! $length || $length > 60 || preg_match("/[^\w\s']+/", $formValues['contactFirstName'])) {
                    $formErrors['contactFirstName'] = "First name must contain only letters and space and be less than 60 characters";
                }
                $length = strlen($formValues['contactLastName']);
                if (! $length || $length > 60 || preg_match("/[^\w\s']+/", $formValues['contactLastName'])) {
                    $formErrors['contactLastName'] = "Last name must contain only letters and space and be less than 60 characters";
                }
                $length = strlen($formValues['locationCity']);
                if ($length && ($length > 100 || preg_match("/[^\w\s\-]+/", $formValues['locationCity']))) {
                    $formErrors['locationCity'] = "City must contain only letters, space and be less than 100 characters long";
                }
                if ($length && ! strlen($formValues['locationState'])) {
                    $formErrors['locationState'] = "State is not specified with City";
                }
                $length = strlen($formValues['locationState']);
                if ($length && ! in_array($formValues['locationState'], array_keys($states))) {
                    $formErrors['locationState'] = "Invalid state";
                }
                if ($length && ! strlen($formValues['locationCity'])) {
                    $formErrors['locationState'] = "City is not specified with State";
                }
                $length = strlen($formValues['contactZipCode']);
                if ($length && ! preg_match("/^\d{5}(-\d{4})?$/", $formValues['contactZipCode'])) {
                    $formErrors['contactZipCode'] = "Zipcode must be in format 00000 or 00000-0000";
                }
                $length = strlen($formValues['interests']);
                if ($length && preg_match("/[^\w\s\-\d',]+/", $formValues['interests'])) {
                    $formErrors['interests'] = "Interest can contain only letter, spaces, digits and dash";
                }
                if (empty($formErrors)) {
                    $formValues['userId'] = $_SESSION['userId'];
                    $contact->setOptions($formValues);
                    try {
                        $contact->setUserId($_SESSION['userId']);
                        $contact->save();
                        //redirect
                        header("Location: http://{$_SERVER['HTTP_HOST']}/?controller=contact&id={$contact->getContactId()}");
                        exit();
                    } catch (Exception_Contact $e) {}
                }
            }
            if (empty($formValues)) {
                $formValues['contactId'] = ($this->contactId) ? $contact->getContactId() : 0;
                $formValues['contactFirstName'] = ($this->contactId) ? $contact->getContactFirstName() : '';
                $formValues['contactLastName'] = ($this->contactId) ? $contact->getContactLastName() : '';
                $formValues['locationCity'] = ($this->contactId) ? $contact->getLocationCity() : '';
                $formValues['locationState'] = ($this->contactId) ? $contact->getLocationState() : '';
                $formValues['contactZipCode'] = ($this->contactId) ? $contact->getContactZipCode() : '';
                $formValues['interests'] = ($this->contactId) ? implode(',', $contact->getInterests()) : '';
            }
            $formValues['token'] = md5(uniqid(rand(), TRUE));
            $_SESSION['token'] = $formValues['token'];
            $this->view->formValues = $formValues;
            $this->view->formErrors = $formErrors;
            $this->view->states = $states;
            $this->view->pageTitle = ($this->contactId) ? 'Edit' : 'Add';
            $this->setView('manage');
        } else {
            $this->setView('nocontact');
            $this->view->pageTitle = 'No record found';
        }
    }
    /**
     * Used to request flickr photo info
     * First tries to get data from cache and then sends request to flickr
     * @uses Registry::getInstance()
     * @uses Cache::writeCache()
     * @uses Cache::readCache()
     */
    public function interestAction ()
    {
        $this->disableLayout();
        $interest = trim(strip_tags($_REQUEST['tag']));
        $cacheFile = 'interest_' . md5($interest) . '.cache';
        if (! $photos = Cache::readCache($cacheFile, 10000)) {
            //build the API URL to call
            $registry = Registry::getInstance();
            $params = $registry->get('flickr');
            $params['method'] = 'flickr.photos.search';
            $params['tags'] = $interest;
            $params['media'] = 'photos';
            $params['per_page'] = '4';
            $params['per_page'] = '4';
            $params['format'] = 'php_serial';
            $params['extras'] = 'url_s';
            $encoded_params = array();
            foreach ($params as $k => $v) {
                $encoded_params[] = urlencode($k) . '=' . urlencode($v);
            }
            //call the API and decode the response
            $url = "http://api.flickr.com/services/rest/?" . implode('&', $encoded_params);
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $photos = curl_exec($curl_handle);
            curl_close($curl_handle);
            Cache::writeCache($photos, $cacheFile);
        }
        if (! empty($photos)) {
            $rsp_obj = unserialize($photos);
            $this->view->photos = $rsp_obj['photos']['photo'];
        } else {
            $this->view->photos = array();
        }
    }
}