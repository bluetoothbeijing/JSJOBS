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

class TableCompany extends Table {

    /** @var int Primary key */
    var $id = null;
    var $uid = null;
    //var $companyid=null;
    var $category = null;
    var $name = null;
    var $alias = null;
    var $url = null;

    /** @var blob */
    var $logofilename = null;
    var $logoisfile = null;
    var $logo = null;
    var $smalllogofilename = null;
    var $smalllogoisfile = null;
    var $smalllogo = null;
    var $aboutcompanyfilename = null;
    var $aboutcompanyisfile = null;
    var $aboutcompanyfilesize = null;
    var $aboutcompany = null;
    var $contactname = null;
    var $contactphone = null;
    var $companyfax = null;
    var $contactemail = null;
    var $since = null;
    var $companysize = null;
    var $income = null;
    var $description = null;
    var $country = null;
    var $state = null;
    var $county = null;
    var $city = null;
    var $zipcode = null;
    var $address1 = null;
    var $address2 = null;
    var $created = null;
    var $modified = null;
    var $hits = null;
    var $metadescription = null;
    var $metakeywords = null;
    var $status = null;
    var $packageid = null;
    var $paymenthistoryid = null;
    var $isgoldcompany = 2;
    var $startgolddate = null;
    var $endgolddate = null;
    var $isfeaturedcompany = 2;
    var $startfeaturddate = null;
    var $endfeatureddate = null;
    var $notifications = null;
    var $params = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_companies', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
 	      if (trim( $this->name ) == '') {
             return false;
          }
        return true;
    }

    function bindCompany($data, $ignore = '') {
        $this->id = $ignore;
        $this->uid = 0;
        $this->category = $data['companycategory'];
        $this->name = $data['companyname'];
        $this->url = $data['companyurl'];
        $this->alias = $data['alias'];
        if (isset($data['companylogofilename']))
            $this->logofilename = $data['companylogofilename'];
        if (isset($data['companylogoisfile']))
            $this->logoisfile = $data['companylogoisfile'];
        if (isset($data['companylogo']))
            $this->logo = $data['companylogo'];
        if (isset($data['companysmalllogofilename']))
            $this->smalllogofilename = $data['companysmalllogofilename'];
        if (isset($data['companysmalllogoisfile']))
            $this->smalllogoisfile = $data['companysmalllogoisfile'];
        if (isset($data['companysmalllogo']))
            $this->smalllogo = $data['companysmalllogo'];
        if (isset($data['companyaboutcompanyfilename']))
            $this->aboutcompanyfilename = $data['companyaboutcompanyfilename'];
        if (isset($data['companyaboutcompanyisfile']))
            $this->aboutcompanyisfile = $data['companyaboutcompanyisfile'];
        if (isset($data['companyaboutcompanyfilesize']))
            $this->aboutcompanyfilesize = $data['companyaboutcompanyfilesize'];
        if (isset($data['companyaboutcompany']))
            $this->aboutcompany = $data['companyaboutcompany'];

        $this->contactname = $data['companycontactname'];
        $this->contactphone = $data['companycontactphone'];
        $this->companyfax = $data['companyfax'];
        $this->contactemail = $data['companycontactemail'];
        $this->since = $data['companysince'];
        $this->companysize = $data['companysize'];
        $this->income = $data['companyincome'];
        $this->description = $data['companydescription'];
        $this->country = $data['companycountry'];
        $this->state = $data['companystate'];
        $this->county = $data['companycounty'];
        $this->city = $data['companycity'];
        $this->zipcode = $data['companyzipcode'];
        $this->address1 = $data['companyaddress1'];
        $this->address2 = $data['companyaddress2'];
        $this->created = $data['created'];
        $this->modified = $data['companymodified'];
        $this->hits = $data['companyhits'];
        $this->metadescription = $data['companymetadescription'];
        $this->metakeywords = $data['companymetakeywords'];
        $this->status = $data['companystatus'];
        $this->params = $data['companyparams'];
        return parent::bind($this, $ignore);
    }

}

?>
