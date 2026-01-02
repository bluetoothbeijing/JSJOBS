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
class TableEmployerPaymentHistory extends Table {

    var $id = null;
    var $uid = null;
    var $companyid = null;
    var $packageid = null;
    var $packagetitle = null;
    var $packageprice = null;
    //var $discountamount` INT NULL ,
    var $discountamount = null;
    var $paidamount = null;
    //var $paidamount` INT NULL ,
    var $discountmessage = null;
    var $packagediscountstartdate = null;
    var $packagediscountenddate = null;
    var $companiesallow = null;
    var $jobsallow = null;
    var $viewresumeindetails = null;
    var $resumesearch = null;
    var $saveresumesearch = null;
    var $packageexpireindays = null;
    var $packagedescription = null;
    var $status = 1;
    var $created = null;
    var $featuredcompanies = null;
    var $goldcompanies = null;
    var $featuredjobs = null;
    var $goldjobs = null;
    var $candidateshortlist = null;
    var $video = null;
    var $map = null;
    var $packageshortdetails = null;
    var $transactionverified = null;
    var $transactionautoverified = null;
    var $verifieddate = null;
    var $referenceid = null;
    var $payer_firstname = null;
    var $payer_lastname = null;
    var $payer_email = null;
    var $payer_amount = null;
    var $payer_itemname = null;
    var $payer_itemname2 = null;
    var $payer_status = null;
    var $payer_tx_token = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_employerpaymenthistory', 'id', $db);
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
