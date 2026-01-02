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
class TableJobSeekerPaymentHistory extends Table {

    var $id = null;
    var $uid = null;
    var $packageid = null;
    var $packagetitle = null;
    var $packageprice = null;
    var $discountamount = null;
    var $paidamount = null;
    var $discountmessage = null;
    var $packagestartdate = null;
    var $packageenddate = null;
    var $resumeallow = null;
    var $coverlettersallow = null;
    var $applyjobs = null;
    var $jobsearch = null;
    var $jobsumesearch = null;
    var $featuredresume = null;
    var $goldresume = null;
    var $video = null;
    var $packageexpireindays = null;
    var $packagedescription = null;
    var $packageshortdetails = null;
    var $status = null;
    var $created = null;
    var $transactionverified = null;
    var $transactionautoverified = null;
    var $verifieddate = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_jobseekerpaymenthistory', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
      if (trim( $this->packagetitle) == '') {
          return false;
      }
        return true;
    }

}

?>
