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

class TableFilter extends Table {

    /** @var int Primary key */
    var $id = null;
    var $uid = null;
    var $country_istext = null;
    var $country = null;
    var $state_istext = null;
    var $state = null;
    var $county_istext = null;
    var $county = null;
    var $city_istext = null;
    var $city = null;
    var $zipcode = null;
    var $category = null;
    var $jobtype = null;
    var $jobstatus = null;
    var $heighesteducation = null;
    var $salaryrange = null;
    var $created = null;
    var $status = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_filters', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        return true;
    }

}

?>
