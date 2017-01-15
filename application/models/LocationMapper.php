<?php
/**
 * Files contains declaration of LocationMapper class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * LocationMapper class
 * Used to save and get Location related data in database
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class LocationMapper extends Mapper
{
    /**
     * Finds Location Id for specified state and city
     * and set it to location property.
     * @param Location $location
     */
    public function fetchLocationId (Location $location)
    {
        $sql = 'INSERT IGNORE location (locationCity,locationState)
        		VALUES(?,?)';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param("ss",
                               $location->getLocationCity(),
                               $location->getLocationState());
            $stmt->execute();
            $stmt->close();
        }
        $query = "SELECT locationId FROM location
        			WHERE locationCity = ?
        				AND locationState = ?
        				LIMIT 1";
        if ($stmt = $this->_db->prepare($query)) {
            $stmt->bind_param("ss", $city, $state);
            $city = $location->getLocationCity();
            $state = $location->getLocationState();
            $stmt->execute();
            /* bind variables to prepared statement */
            $stmt->bind_result($locationId);
            /* fetch values */
            while ($stmt->fetch()) {
                $location->setLocationId($locationId);
            }
            $stmt->close();
        }
    }
}