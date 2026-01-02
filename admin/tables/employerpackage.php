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
class TableEmployerPackage extends Table {

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
    var $companiesallow = null;
    var $jobsallow = null;
    var $viewresumeindetails = null;
    var $resumesearch = null;
    var $saveresumesearch = null;
    var $featuredcompaines = null;
    var $goldcompanies = null;
    var $featuredjobs = null;
    var $goldjobs = null;
    var $jobseekershortlist = null;
    var $shortdetails = null;
    var $packageexpireindays = null;
    var $description = null;
    var $status = null;
    var $video = null;
    var $map = null;
    var $created = null;
    var $featuredcompaniesexpireindays = null;
    var $goldcompaniesexpireindays = null;
    var $featuredjobsexpireindays = null;
    var $goldjobsexpireindays = null;
    var $enforcestoppublishjob = 0;
    var $enforcestoppublishjobvalue = null;
    var $enforcestoppublishjobtype = null;
    var $fastspringlink = null;
    var $otherpaymentlink = null;
    var $folders = null;
    var $messageallow = null;
    var $virtuemartproductid = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_employerpackages', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
      if (trim( $this->title) == '') {
          return false;
      }
        return true;
    }

}

?>
