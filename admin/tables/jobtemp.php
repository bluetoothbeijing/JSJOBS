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

class TableJobTemp extends Table {

    /** @var int Primary key */
    var $localid = null;
    var $id = null;
    var $title = null;
    var $aliasid = null;
    var $companyaliasid = null;
    var $country = null;
    var $state = null;
    var $city = null;
    var $jobdays = null;
    var $companyid = null;
    var $companyname = null;
    var $jobcategory = null;
    var $cat_title = null;
    var $symbol = null;
    var $salaryfrom = null;
    var $salaryto = null;
    var $salaytype = null;
    var $jobtype = null;
    var $jobstatus = null;
    var $cityname = null;
    var $statename = null;
    var $countryname = null;
    var $noofjobs = null;
    var $created = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_jobs_temp', 'localid', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    /*
      function bind( $array, $ignore = '' )
      {
      if (key_exists( 'jobcategory', $array ) && is_array( $array['jobcategory'] )) {
      $array['jobcategory'] = implode( ',', $array['jobcategory'] );
      }
      return parent::bind( $array, $ignore );
      }
     */
}

?>
