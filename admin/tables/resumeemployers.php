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

class TableResumeEmployers extends Table {

    var $id = null;
    var $resumeid = null;
    var $employer = null;
    var $employer_position = null;
    var $employer_resp = null;
    var $employer_pay_upon_leaving = null;
    var $employer_supervisor = null;
    var $employer_from_date = null;
    var $employer_to_date = null;
    var $employer_leave_reason = null;
    var $employer_country = null;
    var $employer_state = null;
    var $employer_city = null;
    var $employer_zip = null;
    var $employer_phone = null;
    var $employer_address = null;
    var $created = null;
    var $last_modified = null;
    var $params = null;
    

    function __construct(&$db) {
        parent::__construct('#__js_job_resumeemployers', 'id', $db);
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
