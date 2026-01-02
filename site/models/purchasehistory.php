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

class JSJobsModelPurchasehistory extends JSModel {

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

    function getEmployerPurchaseHistory($uid, $limit, $limitstart) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;

        $db = $this->getDBO();
        $result = array();

        $query = "SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE uid = " . $uid . " AND status = 1 AND packagefor=1";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT purchase.paidamount, purchase.transactionverified,purchase.created,cur.symbol
                            ,package.id, package.title,package.companiesallow, package.jobsallow, package.packageexpireindays
                            FROM `#__js_job_paymenthistory` AS purchase
                            JOIN `#__js_job_employerpackages` AS package ON package.id = purchase.packageid
                            LEFT JOIN `#__js_job_currencies` AS cur ON package.currencyid = cur.id
                            WHERE purchase.uid = " . $uid . " AND purchase.status = 1 AND purchase.packagefor=1 ORDER BY purchase.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;

        return $result;
    }

    //Payment system end
    function getJobSeekerPurchaseHistory($uid, $limit, $limitstart) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = $this->getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE uid = " . $uid . " AND status = 1 AND packagefor=2 ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT purchase.paidamount,purchase.transactionverified,purchase.created,
                    package.id,package.title,package.resumeallow,package.coverlettersallow,package.packageexpireindays,cur.symbol
                    FROM `#__js_job_paymenthistory` AS purchase 
                    JOIN `#__js_job_jobseekerpackages` AS package ON package.id = purchase.packageid
                    LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = purchase.currencyid
                    WHERE purchase.uid = " . $uid . " AND purchase.status = 1 AND purchase.packagefor=2 ORDER BY purchase.created DESC ";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();
        $result[0] = $packages;
        $result[1] = $total;
        return $result;
    }
    
    //Payment system end
    function assignDefaultPackage($usertype, $uid) {
        if (is_numeric($uid) == false)
            return false;
        $db = $this->getDBO();
        $packageconfig = $this->getJSModel('configurations')->getConfigByFor('package');
        if ($usertype == 1) { //employer
            if ($packageconfig['employer_defaultpackage']) { // add this employer package
                $packageid = $packageconfig['employer_defaultpackage'];
                if (is_numeric($packageid) == false)
                    return false;
                $query = "SELECT package.* FROM `#__js_job_employerpackages` AS package WHERE id = " . $packageid;
                $db->setQuery($query);
                $package = $db->loadObject();
                if (isset($package)) {
                    $paidamount = $package->price;
                    if ($packageconfig['onlyonce_employer_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_employerpackages` AS package
							JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
							WHERE package.price = 0 AND payment.uid = " . $uid;
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                    $query = 'INSERT INTO `#__js_job_paymenthistory` 
					(uid,packageid,packagetitle,packageprice,transactionverified,transactionautoverified,status,discountamount,paidamount,created,packagefor)
					VALUES(' . $uid . ',' . $packageid . ',' . $db->quote($package->title) . ',' . $package->price . ',1,1,1,0,' . $paidamount . ',now(),1)';
                    $db->setQuery($query);
                    $db->execute();

                    $query = 'SELECT MAX(id) FROM `#__js_job_paymenthistory`';
                    $db->setQuery($query);
                    $maxid = $db->loadResult();

                }
            }
        }else { // job seeker
            if ($packageconfig['jobseeker_defaultpackage']) { // add this jobsseker package
                $packageid = $packageconfig['jobseeker_defaultpackage'];
                if (is_numeric($packageid) == false)
                    return false;
                $query = "SELECT package.* FROM `#__js_job_jobseekerpackages` AS package WHERE id = " . $packageid;
                $db->setQuery($query);
                $package = $db->loadObject();
                if (isset($package)) {
                    $paidamount = $package->price;

                    if ($packageconfig['onlyonce_jobseeker_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_jobseekerpackages` AS package
							JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
							WHERE package.price = 0 AND payment.uid = " . $uid;
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                    $query = 'INSERT INTO `#__js_job_paymenthistory` 
					(uid,packageid,packagetitle,packageprice,transactionverified,transactionautoverified,status,discountamount,paidamount,created,packagefor)
					VALUES(' . $uid . ',' . $packageid . ',' . $db->quote($package->title) . ',' . $package->price . ',1,1,1,0,' . $paidamount . ',now(),2)';
                    $db->setQuery($query);
                    $db->execute();
                    $query = 'SELECT MAX(id) FROM `#__js_job_paymenthistory`';
                    $db->setQuery($query);
                    $maxid = $db->loadResult();

                    
                }
            }
        }

        return true;
    }

}

?>
