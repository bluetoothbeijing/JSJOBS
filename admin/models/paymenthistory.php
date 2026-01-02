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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelPaymenthistory extends JSModel {

    var $_config = null;
    var $_application = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function jobseekerPaymentApprove($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = 1, status=1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        return true;
    }

    function jobseekerPaymentReject($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = -1 , status= -1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        return true;
    }

    function employerPaymentApprove($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = 1 , status=1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        return true;
    }

    function employerPaymentReject($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = -1  , status= -1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        return true;
    }

    function getPaymentReport($buyername, $paymentfor, $searchpaymentstatus, $searchstartdate, $searchenddate, $limitstart, $limit) {
        $db = Factory::getDBO();
        $result = array();
        if (!isset($this->_config))
            $this->_config = $this->getJSModel('configuration')->getConfig();
        foreach ($this->_config AS $config) {
            if ($config->configname == 'date_format')
                $dateformat = $config->configvalue;
        }
        $companywherequery = '';
        $ewherequery = '';
        $jwherequery = '';
        $wherequery = '';
        if (!$searchstartdate) {
            $searchstartdate = date('Y-m-d', strtotime(date("Y-m-d") . " -1 month"));
            $searchenddate = date('Y-m-d', strtotime(date("Y-m-d") . " +1 day")); //include today
        } else {
            $searchstartdate = date('Y-m-d', strtotime($searchstartdate));
            $searchenddate = date('Y-m-d', strtotime($searchenddate));
        }

        if ($paymentfor == '')
            $paymentfor = 'both';
        if ($paymentfor == 'both') {
            $clause = " WHERE ";
            if (is_numeric($searchpaymentstatus)) {
                $ewherequery .= $clause . "  epayment.transactionverified = " . $searchpaymentstatus;
                $jwherequery .= $clause . "  jpayment.transactionverified = " . $searchpaymentstatus;
                $clause = " AND ";
            }
            if ($searchstartdate AND $searchenddate) {
                $ewherequery .= $clause . "  DATE(epayment.created) BETWEEN " . $db->Quote(date('Y-m-d' , strtotime($searchstartdate))) . " AND " . $db->Quote(date('Y-m-d' , strtotime($searchenddate)));
                $jwherequery .= $clause . "  DATE(jpayment.created) BETWEEN " . $db->Quote(date('Y-m-d' , strtotime($searchstartdate))) . " AND " . $db->Quote(date('Y-m-d' , strtotime($searchenddate)));
                $clause = " AND ";
            }
        } else {
            $clause = " WHERE ";
            if (is_numeric($searchpaymentstatus) ) {
                $wherequery .= $clause . "  payment.transactionverified = " . $searchpaymentstatus;
                $clause = " AND ";
            }
            if ($searchstartdate AND $searchenddate) {
                $wherequery .= $clause . "  DATE(payment.created) BETWEEN " . $db->Quote(date('Y-m-d' , strtotime($searchstartdate))) . " AND " . $db->Quote(date('Y-m-d' , strtotime($searchenddate)));
                $clause = " AND ";
            }
        }
        if ($paymentfor == 'employer') {
            $totalquery = "SELECT COUNT(payment.id)
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_employerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=1)";

            $query = "SELECT payment.uid,payment.packageid,payment.packagetitle, 'Employer' AS packagefor, payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created,cur.symbol
                                        ,(SELECT company.name FROM `#__js_job_companies` AS company WHERE payment.uid = company.uid " . $companywherequery . " LIMIT 1 ) AS buyername
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_employerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=1)
		LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = payment.currencyid
		";

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery . ' ORDER BY payment.created DESC';
        } elseif ($paymentfor == 'jobseeker') {
            $totalquery = "SELECT COUNT(payment.id)
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_jobseekerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=2)";

            $query = "SELECT payment.uid,payment.packageid,payment.packagetitle, 'Job Seeker' AS packagefor,payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created,cur.symbol
		,(SELECT CONCAT(resume.first_name,' ',resume.last_name) FROM `#__js_job_resume` AS resume WHERE payment.uid = resume.uid LIMIT 1) AS buyername
                                        FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_jobseekerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=2)
		LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = payment.currencyid
		";

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery . ' ORDER BY payment.created DESC';
        } elseif ($paymentfor == 'both') {
            $totalquery = "SELECT
		( SELECT COUNT(epayment.id) FROM `#__js_job_paymenthistory` AS epayment " . $ewherequery . ")
		+ ( SELECT COUNT(jpayment.id) FROM `#__js_job_paymenthistory` AS jpayment " . $jwherequery . ")
		AS total ";

            $query = "SELECT epayment.uid,epayment.packageid,epayment.packagetitle, 'Employer' AS packagefor, epayment.payer_firstname,epayment.paidamount,epayment.transactionverified,epayment.created,ecur.symbol AS symbol
                                        ,(SELECT company.name FROM `#__js_job_companies` AS company WHERE epayment.uid = company.uid LIMIT 1) AS buyername
		FROM `#__js_job_paymenthistory` AS epayment
		JOIN `#__js_job_employerpackages` AS epackage ON epayment.packageid = epackage.id
		LEFT JOIN `#__js_job_currencies` AS ecur ON ecur.id = epayment.currencyid
		";
            $unionquery = "
                            UNION
                            SELECT jpayment.uid,jpayment.packageid,jpayment.packagetitle, 'Job Seeker' AS packagefor,jpayment.payer_firstname,jpayment.paidamount,jpayment.transactionverified,jpayment.created,jcur.symbol AS symbol
                                        ,(SELECT CONCAT(resume.first_name,' ',resume.last_name) FROM `#__js_job_resume` AS resume WHERE jpayment.uid = resume.uid LIMIT 1) AS buyername
		FROM `#__js_job_paymenthistory` AS jpayment
		JOIN `#__js_job_jobseekerpackages` AS jpackage ON jpayment.packageid = jpackage.id
		LEFT JOIN `#__js_job_currencies` AS jcur ON jcur.id = jpayment.currencyid
		";
            $query = $query . $ewherequery . $unionquery . $jwherequery . ' ORDER BY created DESC';
        }

        $db->setQuery($totalquery);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $db->setQuery($query, $limitstart, $limit);
        $payments = $db->loadObjectList();
        $lists = array();
        $searchstartdate = date($dateformat, strtotime($searchstartdate));
        $searchenddate = date($dateformat, strtotime($searchenddate));

        if ($buyername)
            $lists['buyername'] = $buyername;
        if ($searchstartdate)
            $lists['searchstartdate'] = $searchstartdate;
        if ($searchenddate)
            $lists['searchenddate'] = $searchenddate;

        $paymentforvalues = array(
            '0' => array('value' => 'both', 'text' => Text::_('Both')),
            '1' => array('value' => 'employer', 'text' => Text::_('Employer')),
            '2' => array('value' => 'jobseeker', 'text' => Text::_('Job Seeker')),);

        $lists['paymentfor'] = HTMLHelper::_('select.genericList', $paymentforvalues, 'paymentfor', 'class="inputbox" ' , 'value', 'text', $paymentfor);

        $paymentstatus = array(
            '0' => array('value' => '', 'text' => Text::_('Select Payment Status')),
            '1' => array('value' => 1, 'text' => Text::_('Verified')),
            '2' => array('value' => 0, 'text' => Text::_('Not Verified')),);

        if (is_numeric($searchpaymentstatus))
            $lists['paymentstatus'] = HTMLHelper::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' , 'value', 'text', $searchpaymentstatus);
        else
            $lists['paymentstatus'] = HTMLHelper::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' , 'value', 'text', '');
        $result[0] = $payments;
        $result[1] = $total;
        $result[2] = $lists;
        $result[3] = $paymentfor;
        return $result;
    }

    function getPackagePaymentReport($packageid, $paymentfor, $searchpaymentstatus, $searchstartdate, $searchenddate, $limitstart, $limit) {
        if(!is_numeric($packageid)) return false;
        $db = Factory::getDBO();
        $result = array();
        $companywherequery = '';
        if (!$searchstartdate) {
            $searchstartdate = date('Y-m-d', strtotime(date("Y-m-d") . " -1 month"));
            $searchenddate = date('Y-m-d');
        }
        if (is_numeric($searchpaymentstatus))
            $wherequery .="  AND  payment.transactionverified = " . $searchpaymentstatus;
        if ($searchstartdate AND $searchenddate)
            $wherequery .="  AND  payment.created BETWEEN " . $db->Quote($searchstartdate) . " AND " . $db->Quote($searchenddate);

        if ($paymentfor == 'Employer') {
            $totalquery = "SELECT COUNT(payment.id)
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_employerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=1)
		WHERE payment.packageid=" . $packageid;

            $query = "SELECT payment.packageid,payment.packagetitle, 'Employer' AS packagefor, payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created
                                        ,(SELECT company.name FROM `#__js_job_companies` AS company WHERE payment.uid = company.uid " . $companywherequery . " LIMIT 1 ) AS buyername
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_employerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=1)
		WHERE payment.packageid=" . $packageid;

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery;
        } elseif ($paymentfor == 'Job Seeker') {

            $totalquery = "SELECT COUNT(payment.id)
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_jobseekerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=2)
		WHERE payment.packageid = " . $packageid;

            $query = "SELECT payment.packageid,payment.packagetitle, 'Job Seeker' AS packagefor,payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created
		,(SELECT CONCAT(resume.first_name,' ',resume.last_name) FROM `#__js_job_resume` AS resume WHERE payment.uid = resume.uid LIMIT 1) AS buyername
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_jobseekerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=2)
		WHERE payment.packageid = " . $packageid;

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery;
        }

        $db->setQuery($totalquery);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $db->setQuery($query, $limitstart, $limit);
        $payments = $db->loadObjectList();

        $lists = array();

        if ($searchstartdate)
            $lists['searchstartdate'] = $searchstartdate;
        if ($searchenddate)
            $lists['searchenddate'] = $searchenddate;


        $paymentstatus = array(
            '0' => array('value' => '', 'text' => Text::_('Select Payment Status')),
            '1' => array('value' => 1, 'text' => Text::_('Verified')),
            '2' => array('value' => -1, 'text' => Text::_('Not Verified')),);

        if ($searchpaymentstatus)
            $lists['paymentstatus'] = HTMLHelper::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchpaymentstatus);
        else
            $lists['paymentstatus'] = HTMLHelper::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $payments;
        $result[1] = $total;
        $result[2] = $lists;

        return $result;
    }

    function getEmployerPaymentHistory($searchtitle, $searchprice, $searchpaymentstatus, $searchempname, $searchdatestart, $searchdateend, $packagefor, $limitstart, $limit) {
        if(!is_numeric($packagefor)) return false;
        $db = Factory::getDBO();
        $clause = " WHERE ";
        $fquery="";
        if ($searchtitle) {
            $fquery .= $clause . "  payment.packagetitle LIKE ".$db->Quote('%'.$searchtitle.'%');
            $clause = " AND ";
        }
        if ($searchprice) {
            $fquery .= $clause ."payment.packageprice LIKE " . $db->Quote('%' . $searchprice . '%');
            $clause = " AND ";
        }        
        if ($searchpaymentstatus){
            if(!is_numeric($searchpaymentstatus)) return false;
            $fquery .= $clause . "  payment.transactionverified =".$searchpaymentstatus;
            $clause = " AND ";
        }
        if ($searchempname) {
            $fquery .= $clause . " user.name LIKE " . $db->Quote('%' . $searchempname . '%');
            $clause = " AND ";
        }
        if($searchdatestart !='' AND $searchdateend !=''){
            $fquery .= $clause."  DATE(payment.created) >= ".$db->Quote( date('Y-m-d' , strtotime($searchdatestart)))." AND DATE(payment.created) <= ".$db->Quote(date('Y-m-d' , strtotime($searchdateend)));
        }else{
            if($searchdatestart)
                $fquery .= $clause." DATE(payment.created) >= ".$db->Quote(date('Y-m-d' , strtotime($searchdatestart)));
            if($searchdateend)
                $fquery .= $clause." DATE(payment.created) <= ".$db->Quote(date('Y-m-d' , strtotime($searchdateend)));
        }
        $lists = array();
        $lists['searchtitle'] = $searchtitle;
        $lists['searchprice'] = $searchprice;
        $lists['searchempname'] = $searchempname;
        $lists['searchdatestart'] = $searchdatestart;
        $lists['searchdateend'] = $searchdateend;
        $lists['paymentstatus'] = HTMLHelper::_('select.genericList', array('0' => array('value' => '', 'text' => Text::_('Select Payment Status')), '1' => array('value' => 1, 'text' => Text::_('Verified')), '2' => array('value' => -1, 'text' => Text::_('Not Verified'))), 'searchpaymentstatus', 'class="inputbox" ' , 'value', 'text', $searchpaymentstatus);

        $result = array();
        $query = "SELECT COUNT(payment.id)
                FROM `#__js_job_paymenthistory` AS payment
                JOIN `#__users` AS user ON user.id = payment.uid
                JOIN `#__js_job_employerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT payment.id,payment.packagetitle,payment.packageprice,payment.discountamount
                ,payment.created,payment.transactionverified, user.name AS employername,cur.symbol
                ,payment.status
		FROM `#__js_job_paymenthistory` AS payment
		JOIN `#__js_job_employerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")
		JOIN `#__users` AS user ON user.id = payment.uid
		LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=payment.currencyid
		";
        $query .= $fquery;
        $query .= " ORDER BY payment.created DESC";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function getJobseekerPaymentHistory($searchtitle, $searchprice, $searchpaymentstatus, $searchempname, $searchdatestart, $searchdateend, $packagefor, $limitstart, $limit) {
        if($packagefor)
            if(!is_numeric($packagefor)) return false;

        $db = Factory::getDBO();
        $clause = " WHERE ";
        $fquery="";
        if ($searchtitle) {
            $fquery .= $clause . "  payment.packagetitle LIKE ".$db->Quote('%'.$searchtitle.'%');
            $clause = " AND ";
        }
        if ($searchprice) {
            $fquery .= $clause ."payment.packageprice LIKE " . $db->Quote('%' . $searchprice . '%');
            $clause = " AND ";
        }
        if ($searchpaymentstatus){
            if(!is_numeric($searchpaymentstatus)) return false;
            $fquery .= $clause . "  payment.transactionverified =".$searchpaymentstatus;
            $clause = " AND ";
        }
        if ($searchempname) {
            $fquery .= $clause . " user.name LIKE " . $db->Quote('%' . $searchempname . '%');
            $clause = " AND ";
        }
        if($searchdatestart !='' AND $searchdateend !=''){
            $fquery .= $clause." DATE(payment.created) >= ".$db->Quote(date('Y-m-d' , strtotime($searchdatestart)))." AND DATE(payment.created) <= ".$db->Quote(date('Y-m-d' , strtotime($searchdateend)));
        }else{
            if($searchdatestart)
                $fquery .= $clause." DATE(payment.created) >= ".$db->Quote(date('Y-m-d' , strtotime($searchdatestart)));
            if($searchdateend)
                $fquery .= $clause." DATE(payment.created) <= ".$db->Quote(date('Y-m-d' , strtotime($searchdateend)));
        }
        $lists = array();
        $lists['searchtitle'] = $searchtitle;
        $lists['searchprice'] = $searchprice;
        $lists['searchempname'] = $searchempname;
        $lists['searchdatestart'] = $searchdatestart;
        $lists['searchdateend'] = $searchdateend;
        $lists['paymentstatus'] = HTMLHelper::_('select.genericList', array('0' => array('value' => '', 'text' => Text::_('Select Payment Status')), '1' => array('value' => 1, 'text' => Text::_('Verified')), '2' => array('value' => -1, 'text' => Text::_('Not Verified'))), 'searchpaymentstatus', 'class="inputbox" ' , 'value', 'text', $searchpaymentstatus);

        $result = array();
        $query = "SELECT COUNT(payment.id)
		FROM `#__js_job_paymenthistory` AS payment
                JOIN `#__users` AS user ON user.id = payment.uid
		JOIN `#__js_job_jobseekerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")";
        $query .=$fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT payment.id,payment.packagetitle,payment.packageprice,payment.discountamount
            ,payment.created,payment.transactionverified,user.name AS jobseekername,cur.symbol
            ,payment.status
            FROM `#__js_job_paymenthistory` AS payment
            JOIN `#__js_job_jobseekerpackages` AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")
    		JOIN `#__users` AS user ON user.id = payment.uid
    		LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=payment.currencyid";
        $query .=$fquery;
        $query .= " ORDER BY payment.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function getEmployerPaymentHistorybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();

        $query = "SELECT payment.packagetitle,payment.payer_firstname,payment.payer_lastname,
                payment.payer_email,payment.payer_amount,payment.payer_itemname,payment.payer_itemname2,payment.transactionverified,payment.transactionautoverified,payment.verifieddate,payment.created,payment.status,payment.paidamount, package.companiesallow,package.jobsallow,
				package.resumesearch,package.saveresumesearch,package.viewresumeindetails,package.video,package.map,
				package.shortdetails,package.description,
				user.name AS employername,
				package.price,package.discount,package.discountstartdate,package.discountenddate,
				package.enforcestoppublishjob,package.enforcestoppublishjobvalue,package.enforcestoppublishjobtype,package.packageexpireindays
				FROM #__js_job_paymenthistory AS payment
				JOIN #__js_job_employerpackages AS package ON payment.packageid = package.id
				JOIN #__users AS user ON user.id = payment.uid ";
        $query .="WHERE  payment.id=" . $c_id;

        $db->setQuery($query);
        $package = $db->loadObject();


        $result[0] = $package;


        return $result;
    }

    function getJobseekerPaymentHistorybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT payment.packagetitle,payment.payer_firstname,payment.payer_lastname,
                payment.payer_email,payment.payer_amount,payment.payer_itemname,payment.payer_itemname2 AS payer_itemname1,payment.transactionverified,payment.transactionautoverified,payment.verifieddate,payment.created,payment.status,payment.paidamount,package.resumeallow,package.coverlettersallow,package.jobsearch,
				package.savejobsearch,package.applyjobs,package.video,
				package.shortdetails,package.description,package.price,package.discount,package.discountstartdate,package.discountenddate,
				package.packageexpireindays,user.name AS jobseekername
				FROM `#__js_job_paymenthistory` AS payment
				JOIN `#__js_job_jobseekerpackages` AS package ON payment.packageid = package.id
				JOIN `#__users` AS user ON user.id = payment.uid  ";
        $query .=" WHERE payment.id=" . $c_id;
        $db->setQuery($query);
        $package = $db->loadObject();

        $result[0] = $package;

        return $result;
    }

// Payment Package End
// For Combo Sta
    function storeUserPackage() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $data = Factory::getApplication()->input->post->getArray(); // get data from form

        if (is_numeric($data['packageid']) == false)
            return false;
        $db = $this->getDBO();
        $result = array();
        $user = Factory::getUser();
        $uid = $user->id;
        $row = $this->getTable('paymenthistory');
        if ($data['userrole'] == 1) {
            $tablename = '#__js_job_employerpackages';
            $row->packagefor = 1;
        } elseif ($data['userrole'] == 2) {
            $tablename = '#__js_job_jobseekerpackages';
            $row->packagefor = 2;
        }
        $query = "SELECT package.id,package.title,package.price,package.discountstartdate,package.discountenddate,package.discount,package.discounttype,
                    package.shortdetails,package.packageexpireindays,package.description,package.discountmessage
                    FROM `" . $tablename . "` AS package WHERE id = " . $data['packageid'];
        $db->setQuery($query);
        $package = $db->loadObject();
        if (isset($package)) {
            $packageconfig = $this->getJSModel('configuration')->getConfigByFor('package');
            if (is_numeric($data['userid']) == false)
                return false;
            $row->uid = $data['userid'];
            $row->currencyid = $this->_defaultcurrency;
            $row->packageid = $package->id;
            $row->packagetitle = $package->title;
            $row->packageprice = $package->price;
            $paidamount = $package->price;
            $discountamount = 0;

            if ($package->price != 0) {
                $curdate = date('Y-m-d H:i:s');
                if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                    if ($package->discounttype == 1) { //%
                        $discountamount = ($package->price * $package->discount) / 100;
                        $paidamount = $package->price - $discountamount;
                    } else { // amount
                        $discountamount = $package->discount;
                        $paidamount = $package->price - $package->discount;
                    }
                }
                $row->transactionverified = 0;
                $row->transactionautoverified = 0;
                $row->status = 1;
            } else {

                if ($data['userrole'] == 1) {

                    if ($packageconfig['onlyonce_employer_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_employerpackages` AS package
				JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
				WHERE package.price = 0 AND payment.uid = " . $data['userid'];
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                }elseif ($data['userrole'] == 2) {

                    if ($packageconfig['onlyonce_jobseeker_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_jobseekerpackages` AS package
	                    JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
	                    WHERE package.price = 0 AND payment.uid = " . $data['userid'];
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                }

                $row->transactionverified = 1;
                $row->transactionautoverified = 1;
                $row->status = $packageconfig['jobseeker_freepackage_autoapprove'];
            }
            $row->discountamount = $discountamount;
            $row->paidamount = $paidamount;

            $row->discountmessage = $package->discountmessage;
            //if($data['userrole'] == 2){ // no column in employerpayment history
            $row->packagediscountstartdate = $package->discountstartdate;
            $row->packagediscountenddate = $package->discountenddate;
            //}
            $row->packageexpireindays = $package->packageexpireindays;
            $row->packageshortdetails = $package->shortdetails;
            $row->packagedescription = $package->description;
            $row->created = date('Y-m-d H:i:s');
        }else {
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            echo $row->getError();
            return 2;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        return true;
    }

    function deleteUserPaymentHistoryDataByUid($uid){
        if(!is_numeric($uid) || $uid == 0)
            return false;
        $db = Factory::getDBO();

        $query = "DELETE FROM `#__js_job_paymenthistory` WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();  
    }
}

?>
