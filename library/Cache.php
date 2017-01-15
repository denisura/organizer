<?php
/**
 * Files contains declaration of Cache class
 *
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 * @copyright 2010 Denis Uraganov
 * @version 1.0.0
 */
/**
 * Class contains methods to manage cache
 * @package Socialreach
 * @subpackage Test
 * @author Denis Uraganov <info@uraganov.net>
 *
 */
class Cache
{
    /**
     * Create cache file
     * @param string $content
     * @param string $filename
     * @uses Registry::getInstance()
     * @static
     */
    public static function writeCache ($content, $filename)
    {
        $registry = Registry::getInstance();
        $cache = $registry->get('cache');
        $fp = fopen($cache['cache_path'] . $filename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
    /**
     * Read chached file
     * @param string $filename
     * @param integer $expiry
     * @return boolean
     * @uses Registry::getInstance()
     * @static
     */
    public static function readCache ($filename, $expiry)
    {
        $registry = Registry::getInstance();
        $cache = $registry->get('cache');
        $fileLocation = $cache['cache_path'] . $filename;
        if (file_exists($fileLocation)) {
            if ((time() - $expiry) > filemtime($fileLocation)) {
                return false;
            }
            $cache = file($fileLocation);
            return implode('', $cache);
        }
        return false;
    }
}