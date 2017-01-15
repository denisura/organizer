<?php
/**
 * Files contains declaration of Location class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Class Location
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 *
 */
class Location extends Model
{
    /**
     *
     * @var integer
     */
    protected $_locationId;
    /**
     *
     * @var string
     */
    protected $_locationState;
    /**
     *
     * @var string
     */
    protected $_locationCity;
    /**
     * Location Id getter
     * @return integer $_locationId
     */
    public function getLocationId ()
    {
        return $this->_locationId;
    }
    /**
     * Location State getter
     * @return string $_state
     */
    public function getLocationState ()
    {
        return $this->_locationState;
    }
    /**
     * Location City getter
     * @return string $_city
     */
    public function getLocationCity ()
    {
        return $this->_locationCity;
    }
    /**
     * Location Id setter
     * @param integer $_locationId the $_locationId to set
     * @return Location
     */
    public function setLocationId ($_locationId)
    {
        $this->_locationId = $_locationId;
        return $this;
    }
    /**
     * Location State setter
     * @param string $_state the $_state to set
     * @returns Location
     */
    public function setLocationState ($_state)
    {
        $this->_locationState = $_state;
        return $this;
    }
    /**
     * Location City setter
     * @param string $_city the $_city to set
     * @returns Location
     */
    public function setLocationCity ($_city)
    {
        $this->_locationCity = $_city;
        return $this;
    }
    /**
     * Mapper getter
     * @return LocationMapper
     */
    public function getMapper ()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new LocationMapper());
        }
        return $this->_mapper;
    }
    /**
     * Fetch location Id
     * @return integer Location Id
     */
    public function fetchLocationId ()
    {
        return $this->getMapper()->fetchLocationId($this);
    }
}