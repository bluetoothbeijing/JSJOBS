<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     https://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Table\Table;

// our table class for the application data
class TableCity extends Table {

    var $id = null;
    var $cityName = null;
    var $name = null;
    var $stateid = null;
    var $countryid = null;
    var $isedit = null;
    var $enabled = '0';
    var $latitude = null;
    var $longitude = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_cities', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        $var = trim($this->cityName);
        if(empty($var)){
            return false;
        }
        return true;
    }

}

?>
