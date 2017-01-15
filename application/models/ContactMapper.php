<?php
/**
 * Files contains declaration of ContactMapper class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * ContactMapper class
 * Used to save and get contact related data in database
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class ContactMapper extends Mapper
{
    /**
     * Saves contact data in database
     * @param Contact $contact
     */
    public function save (Contact $contact)
    {
        /* disable autocommit */
        $this->_db->autocommit(FALSE);
        try {
            //get location
            $locationId = null;
            $locationCity = $contact->getLocationCity();
            $locationState = $contact->getLocationState();
            if (strlen($locationState) && strlen($locationCity)) {
                $location = new Location();
                $location->setLocationCity($locationCity);
                $location->setLocationState($locationState);
                $location->fetchLocationId();
                $locationId = $location->getLocationId();
            }
            $userId = $contact->getUserId();
            $contactFirstName = $contact->getContactFirstName();
            $contactLastName = $contact->getContactLastName();
            $contactZipCode = $contact->getContactZipCode();
            $contactId = $contact->getContactId();
            if (! $contactId) {
                //insert contact
                $sql = 'INSERT contact (userId
            					  , contactFirstName
            					  , contactLastName
            					  , contactZipCode
            					  , locationId)
  		      		VALUES(?,?,?,?,?)';
                if ($stmt = $this->_db->prepare($sql)) {
                    $stmt->bind_param("isssi",
                                       $userId,
                                       $contactFirstName,
                                       $contactLastName,
                                       $contactZipCode,
                                       $locationId);
                    $stmt->execute();
                    $stmt->close();
                }
                $contact->setContactId($this->_db->insert_id);
                //insert contact interestIds
            } else {
                //update contact
                $sql = 'UPDATE contact SET
       					  	contactFirstName = ?,
            			  	contactLastName = ?,
							contactZipCode = ?,
            				locationId = ?
            			WHERE contactId = ?
  		      		';
                if ($stmt = $this->_db->prepare($sql)) {
                    $stmt->bind_param("sssii",
                                      $contactFirstName,
                                      $contactLastName,
                                      $contactZipCode,
                                      $locationId,
                                      $contactId);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $this->saveInterests($contact);
            $this->_db->commit();
        } catch (Exception $e) {
            /* Rollback */
            $this->_db->rollback();
            echo $e->getMessage();
            echo $e->getTraceAsString();
            exit();
        }
    }
    /**
     * Saves contact interests in database
     * @param Contact $contact
     * @throws Exception
     */
    public function saveInterests (Contact $contact)
    {
        $contactId = $contact->getContactId();
        $sql = 'DELETE FROM contact_interest
          					WHERE contactId = ?';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param("i", $contactId);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("DB error in ContactMapper::saveInterests");
        }
        $interest = new Interest();
        $interestIds = $interest->fetchInterestIds($contact->getInterests());
        if (empty($interestIds)) {
            return;
        }
        $sql = 'INSERT INTO contact_interest (contactId, interestId)
          					VALUES (?,?)';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param("ii", $contactId, $interestId);
            foreach ($interestIds as $interestId) {
                $stmt->execute();
            }
            $stmt->close();
        } else {
            throw new Exception("DB error in ContactMapper::saveInterests");
        }
    }
    /**
     * Delete contact from database
     * Only contact that belongs to loged in user can be removed
     * @param Contact $contact
     * @return boolean
     * @throws Exception
     */
    public function delete (Contact $contact)
    {
        $contactId = $contact->getContactId();
        $sql = 'DELETE FROM contact
          			WHERE contactId = ?
          				AND userId = ?';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param("ii", $contact->getContactId(), $contact->getUserId());
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            throw new Exception("DB error in ContactMapper::delete");
        }
        return false;
    }
    /**
     * Load requested conatct
     * @param integer  $contactId
     * @param integer $userId
     * @param Contact $contact
     */
    public function find ($contactId, $userId, Contact $contact)
    {
        $sql = 'SELECT * FROM view_contact
					WHERE contactId = ? AND userId = ?
					LIMIT 1';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param('dd', $contactId, $userId);
            $stmt->execute();
            /* bind variables to prepared statement */
            $stmt->bind_result($contactId,
                               $userId,
                               $contactFirstName,
                               $contactLastName,
                               $contactZipCode,
                               $locationId,
                               $locationCity,
                               $locationState,
                               $interests);
            /* fetch values */
            while ($stmt->fetch()) {
                $contact->setContactId($contactId)
                        ->setUserId($userId)
                        ->setContactFirstName($contactFirstName)
                        ->setContactLastName($contactLastName)
                        ->setContactZipCode($contactZipCode)
                        ->setLocationCity($locationCity)
                        ->setLocationState($locationState)
                        ->setInterests($interests);
                break;
            }
            if (! count($contactId)) {
                return;
            }
            $stmt->close();
        }
    }
    /**
     * Gets user conatcts suing pagination
     * @param integer $userId
     * @param integer $count
     * @param integer $page
     * @return array
     */
    public function fetchAll ($userId, $count = null, $page = 1)
    {
        $count = abs((int) $count);
        $page = abs((int) $page);
        $page = ($page) ? $page : 1;
        $limit = '';
        if ($count) {
            $ofset = ($page - 1) * $count;
            $limit = " LIMIT $ofset,$count";
        }
        $contacts = array('data' => array() , 'total' => 0);
        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM view_contact
					WHERE userId = ? ORDER BY contactLastName' . $limit;
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param('d', $userId);
            $stmt->execute();
            /* bind variables to prepared statement */
            $stmt->bind_result($contactId,
                               $userId,
                               $contactFirstName,
                               $contactLastName,
                               $contactZipCode,
                               $locationId,
                               $locationCity,
                               $locationState,
                               $interests);
            /* fetch values */
            while ($stmt->fetch()) {
                $contact = new Contact();
                $contact->setContactId($contactId)
                        ->setUserId($userId)
                        ->setContactFirstName($contactFirstName)
                        ->setContactLastName($contactLastName)
                        ->setContactZipCode($contactZipCode)
                        ->setLocationCity($locationCity)
                        ->setLocationState($locationState)
                        ->setInterests($interests);
                $contacts['data'][] = $contact;
            }
            /* close statement */
            $stmt->close();
        }
        $query = "SELECT FOUND_ROWS() as total";
        if (! empty($contacts) && $result = $this->_db->query($query)) {
            /* fetch object array */
            while ($obj = $result->fetch_object()) {
                $contacts['total'] = $obj->total;
                break;
            }
            /* free result set */
            $result->close();
        }
        return $contacts;
    }
}