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
use Joomla\CMS\Factory;

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelServer extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_countries = null;

    function __construct() {
    }

    function getDataFromLocalServer($updateTime, $limitstart, $limit) {
    }

    function getIDDefaultCountry($defaultcountrycode) {
    }

    function getJobsFromServerAndFill($variables) {
    }

    function getJobsFromServerFilter($variables) {
    }

    function getSharingCountries($title) {
    }

}
?>    


