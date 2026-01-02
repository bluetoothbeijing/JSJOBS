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

class TableResumeaddresses extends Table {

    var $id = null;
    var $resumeid = null;
    var $address = null;
    var $address_country = null;
    var $address_state = null;
    var $address_city = null;
    var $address_county = null;
    var $address_zipcode = null;
    var $longitude = null;
    var $latitude = null;
    var $created = null;
    var $last_modified = null;
    var $params = null;
    

    function __construct(&$db) {
        parent::__construct('#__js_job_resumeaddresses', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        // if (trim( $this->application_title ) == '') {
        //   $this->_error = "Application Title can not be empty.";
        //   return false;
        // }else if (trim( $this->first_name ) == '') {
        //   $this->_error = "First Name cannot be empty.";
        //   return false;
        // }else if (trim( $this->last_name ) == '') {
        //   $this->_error = "Last Name cannot be empty.";
        //   return false;
        // }else if (trim( $this->email_address ) == '') {
        //   $this->_error = "Email cannot be empty.";
        //   return false;
        // }

        return true;
    }

}

?>
