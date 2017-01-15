<?php
/**
 * Files contains declaration of InterestMapper class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * InterestMapper class
 * Used to save and get interest related data in database
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class InterestMapper extends Mapper
{
    /**
     * Gets interest ids for specified interests.
     * If interest does not exist add it
     * @param $interests
     * @return array
     */
    public function fetchInterestIds ($interests)
    {
        $filtered = array();
        if (! is_array($interests)) {
            $interests = array($interests);
        }
        if (! empty($interests)) {
            foreach ($interests as $interest) {
                if (strlen($interest)) {
                    $filtered[] = $interest;
                }
            }
        }
        if (empty($filtered)) {
            return $filtered;
        }
        $sql = 'INSERT IGNORE interest (interestName)
        		VALUES(?)';
        if ($stmt = $this->_db->prepare($sql)) {
            $stmt->bind_param("s", $interest);
            foreach ($filtered as $interest) {
                $stmt->execute();
            }
            $stmt->close();
        }
        $in = implode(',', array_fill(0, count($filtered), '?'));
        $query = "SELECT interestId FROM interest
        			WHERE interestName IN ({$in})";
        if ($stmt = $this->_db->prepare($query)) {
            array_unshift($filtered, str_repeat('s', count($filtered)));
            call_user_func_array(array($stmt , 'bind_param'), $filtered);
            $stmt->execute();
            $stmt->bind_result($interestId);
            /* fetch values */
            while ($stmt->fetch()) {
                $interestIds[] = $interestId;
            }
            $stmt->close();
        }
        return $interestIds;
    }
}
