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
use Joomla\CMS\Uri\Uri;

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelJobSeekerPackages extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobSeekerPackageById($packageid) {
        if (is_numeric($packageid) == false)
            return false;

        $db = $this->getDBO();
        $query = "SELECT package.title ,package.discountstartdate ,package.discountenddate ,package.discountmessage
                        ,package.discounttype ,package.price ,package.discount ,package.resumeallow
                        ,package.jobsearch ,package.coverlettersallow ,package.savejobsearch 
                        ,package.applyjobs
                        ,package.applyjobs ,package.id ,package.packageexpireindays,package.description
                        ,cur.symbol
                    FROM `#__js_job_jobseekerpackages` AS package 
                    LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=package.currencyid
                    WHERE package.id = " . $packageid;
        $db->setQuery($query);
        $package = $db->loadObject();
        return $package;
    }

    function getJobSeekerPackages($limit, $limitstart) {
        $db = $this->getDBO();
        $result = array();

        $query = "SELECT COUNT(id) FROM `#__js_job_jobseekerpackages` WHERE status = 1";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT package.title ,package.discountstartdate ,package.discountenddate ,package.discountmessage
                        ,package.discounttype ,package.price ,package.discount ,package.resumeallow
                        ,package.jobsearch ,package.coverlettersallow ,package.savejobsearch 
                        ,package.applyjobs
                        ,package.applyjobs ,package.id ,package.packageexpireindays
                        ,cur.symbol
            FROM `#__js_job_jobseekerpackages` AS package  LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=package.currencyid WHERE package.status = 1";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;

        return $result;
    }

}
?>
    
