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
class TableJobSeekerPackage extends Table {

    var $id = null;
    var $title = null;
    var $currencyid = null;
    var $price = null;
    //var $discount` INT NULL ,
    var $discount = null;
    var $discounttype = null;
    var $discountmessage = null;
    var $discountstartdate = null;
    var $discountenddate = null;
    var $resumeallow = null;
    var $coverlettersallow = null;
    var $applyjobs = null;
    var $jobsearch = null;
    var $savejobsearch = null;
    var $featuredresume = null;
    var $goldresume = null;
    var $video = null;
    var $packageexpireindays = null;
    var $shortdetails = null;
    var $description = null;
    var $status = null;
    var $created = null;
    var $freaturedresumeexpireindays = null;
    var $goldresumeexpireindays = null;
    var $fastspringlink = null;
    var $otherpaymentlink = null;
    var $jobalertsetting = null;
    var $virtuemartproductid = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_jobseekerpackages', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        if(trim( $this->title) == '') {
          return false;
      }
        return true;
    }

}

?>
