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

class TableResumeInstitutes extends Table {

    var $id = null;
    var $resumeid = null;
    var $institute = null;
    var $institute_country = null;
    var $institute_state = null;
    var $institute_city = null;
    var $institute_county = null;
    var $institute_address = null;
    var $institute_certificate_name = null;
    var $institute_study_area = null;
    var $created = null;
    var $last_modified = null;
    var $params = null;
    

    function __construct(&$db) {
        parent::__construct('#__js_job_resumeinstitutes', 'id', $db);
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
