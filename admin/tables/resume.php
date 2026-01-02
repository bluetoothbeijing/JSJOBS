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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;

class TableResume extends Table {

    var $id = null;
    var $uid = null;
    var $created = null;
    var $last_modified = null;
    var $published = null;
    var $hits = null;
    var $application_title = null;
    var $keywords = null;
    var $alias = null;
    var $first_name = null;
    var $last_name = null;
    var $middle_name = null;
    var $gender = null;
    var $email_address = null;
    var $home_phone = null;
    var $work_phone = null;
    var $cell = null;
    var $nationality = null;
    var $iamavailable = null;
    var $photo = null;
    var $job_category = null;
    var $jobsalaryrange = null;
    var $jobsalaryrangetype = null;
    var $jobtype = null;
    var $heighestfinishededucation = null;
    var $status = null;
    var $resume = null;
    var $date_start = null;
    var $desired_salary = null;
    var $djobsalaryrangetype = null;
    var $dcurrencyid = null;
    var $can_work = null;
    var $available = null;
    var $unalailable = null;
    var $experienceid = null;
    var $total_experience = null;
    var $skills = null;
    var $driving_license = null;
    var $license_no = null;
    var $license_country = null;
    var $packageid = null;
    var $paymenthistoryid = null;
    var $currencyid = null;
    var $job_subcategory = null;
    var $date_of_birth = null;
    var $video = null;
    var $isgoldresume = 2;
    var $startgolddate = null;
    var $endgolddate = null;
    var $isfeaturedresume = 2;
    var $startfeatureddate = null;
    var $endfeaturedate = null;
    var $notifications = null;    
    var $serverstatus = null;
    var $serverid = null;
    var $params = null;
    

    function __construct(&$db) {
        parent::__construct('#__js_job_resume', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        if (trim($this->first_name) == '') {
            $this->_error = Text::_("First Name cannot be empty");
            return false;
        } else if (trim($this->email_address) == '') {
            $this->_error = Text::_("Email cannot be empty");
            return false;
        }
        if (trim($this->driving_license) == '') {
            $this->driving_license = NULL;
        }
        return true;
    }

}

?>
