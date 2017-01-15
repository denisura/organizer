<?php
/**
 * Files contains declaration of Interest class
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
 *
 */
class Interest extends Model
{
    /**
     *
     * @var integer
     */
    protected $_interestId;
    /**
     *
     * @var string
     */
    protected $_interestName;
    /**
     * Mapper getter
     * @return InterestMapper
     */
    public function getMapper ()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new InterestMapper());
        }
        return $this->_mapper;
    }
    /**
     * Get InterestIds.
     * If interest if not exists in database
     * @param array $interests Array of Interests
     * @return array Array of ids
     */
    public function fetchInterestIds ($interests)
    {
        return $this->getMapper()->fetchInterestIds($interests);
    }
}