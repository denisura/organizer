<?php
/**
 * Files contains declaration of Contact class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Class to manage contact
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class Contact extends Model
{
    /**
     *
     * @var integer User Id
     */
    protected $_userId;
    /**
     *
     * @var integer Contact Id
     */
    protected $_contactId;
    /**
     *
     * @var string Contact First Name
     */
    protected $_contactFirstName;
    /**
     *
     * @var string Contact Last Name
     */
    protected $_contactLastName;
    /**
     *
     * @var string Zipcode
     */
    protected $_contactZipCode;
    /**
     *
     * @var string Location City
     */
    protected $_locationCity;
    /**
     *
     * @var string Location State
     */
    protected $_locationState;
    /**
     *
     * @var array Interests
     */
    protected $_interests;
    /**
     * Class constructor.
     * Sets provided object values
     * @param array $options
     */
    public function __construct (array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    /**
     * User Id getter
     * @return integer $_userId
     */
    public function getUserId ()
    {
        return $this->_userId;
    }
    /**
     * User Id Setter
     * @param integer $_userId
     * @return Contact
     */
    public function setUserId ($_userId)
    {
        $this->_userId = (int) $_userId;
        return $this;
    }
    /**
     * Contact Id getter
     * @return integer $_contactId
     */
    public function getContactId ()
    {
        return $this->_contactId;
    }
    /**
     * Contact Id setter
     * @param integer $_contactId
     * @return Contact
     */
    public function setContactId ($_contactId)
    {
        $this->_contactId = (int) $_contactId;
        return $this;
    }
    /**
     * Contact First Name getter
     * @return string $_contactFirstName
     */
    public function getContactFirstName ()
    {
        return $this->_contactFirstName;
    }
    /**
     * Contact First Name setter
     * @param string $_contactFirstName
     * @return Contact
     */
    public function setContactFirstName ($_contactFirstName)
    {
        $this->_contactFirstName = (string) $_contactFirstName;
        return $this;
    }
    /**
     * Contact Last Name getter
     * @return string $_contactLastName
     */
    public function getContactLastName ()
    {
        return $this->_contactLastName;
    }
    /**
     * Contact Last Name setter
     * @param string $_contactLastName
     * @return Contact
     */
    public function setContactLastName ($_contactLastName)
    {
        $this->_contactLastName = (string) $_contactLastName;
        return $this;
    }
    /**
     * Contact Zipcode getter
     * @return the $_contactZipCode
     */
    public function getContactZipCode ()
    {
        return $this->_contactZipCode;
    }
    /**
     * Contact Zipcode setter
     * @param string $_contactZipCode
     * @return Contact
     */
    public function setContactZipCode ($_contactZipCode)
    {
        $this->_contactZipCode = $_contactZipCode;
        return $this;
    }
    /**
     * Location City getter
     * @return string $_locationCity
     */
    public function getLocationCity ()
    {
        return $this->_locationCity;
    }
    /**
     * Location City getter
     * @param string $_locationCity
     * @return Contact
     */
    public function setLocationCity ($_locationCity)
    {
        $this->_locationCity = (string) $_locationCity;
        return $this;
    }
    /**
     * Location State getter
     * @return string $_locationState
     */
    public function getLocationState ()
    {
        return $this->_locationState;
    }
    /**
     * Location State setter
     * @param string $_locationState
     * @return Contact
     */
    public function setLocationState ($_locationState)
    {
        $this->_locationState = $_locationState;
        return $this;
    }
    /**
     * Interests getter
     * @return array $_interests
     */
    public function getInterests ()
    {
        return $this->_interests;
    }
    /**
     * Interest setter
     * Creates array with unique interests
     * @param string $_interests
     * @return Contact
     */
    public function setInterests ($_interests)
    {
        $interests = explode(',', $_interests);
        if (! empty($interests)) {
            foreach ($interests as $interest) {
                $interest = trim($interest);
                if (strlen($interest)) {
                    $filtered[] = $interest;
                }
            }
        }
        $this->_interests = (! empty($filtered)) ? array_unique($filtered) : array();
        return $this;
    }
    /**
     * Mapper getter
     * @return ContactMapper
     */
    public function getMapper ()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new ContactMapper());
        }
        return $this->_mapper;
    }
    /**
     * Use mapper to save current contact in dataabse
     */
    public function save ()
    {
        $this->getMapper()->save($this);
    }
    /**
     * Use mapper to delete contact
     * @retrun boolean
     */
    public function delete ()
    {
        return $this->getMapper()->delete($this);
    }
    /**
     * Use mapper to find contact
     * @param integer $contactId
     * @param integer $userId
     * @return Contact
     */
    public function find ($contactId, $userId)
    {
        $this->getMapper()->find($contactId, $userId, $this);
        return $this;
    }
    /**
     * Use mapper to get contacts using pagination
     * @param integer $count
     * @param integer $page
     * @return array
     */
    public function fetchAll ($count, $page)
    {
        $userId = $this->getUserId();
        return ($userId) ? $this->getMapper()->fetchAll($userId, $count, $page) : null;
    }
}