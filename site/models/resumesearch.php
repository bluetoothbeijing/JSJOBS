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

class JSJobsModelResumesearch extends JSModel {

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

    function deleteResumeSearch($searchid, $uid) {

        $db = $this->getDBO();
        $row = $this->getTable('resumesearch');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($searchid) == false)
            return false;

        $query = "SELECT COUNT(search.id) FROM `#__js_job_resumesearches` AS search  
					WHERE search.id = " . $searchid . " AND search.uid = " . $uid;
        $db->setQuery($query);
        $searchtotal = $db->loadResult();

        if ($searchtotal > 0) { // this search is same user
            if (!$row->delete($searchid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
        } else
            return 2;

        return true;
    }

    function getResumeSearchebyId($id) {
        $db = $this->getDBO();
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT search.* 
                    FROM `#__js_job_resumesearches` AS search
                    WHERE search.id  = " . $id;
        $db->setQuery($query);
        return $db->loadObject();
    }

    function storeResumeSearch($data) {
        //$data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        global $resumedata;
        $row = $this->getTable('resumesearch');
        // log error
        if(!isset($data['date_start'])){
            $data['date_start']= '0000-00-00 00:00:00';
        }
        $data['date_start'] = date('Y-m-d H:i:s', strtotime($data['date_start']));
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        $returnvalue = $this->canAddNewResumeSearch($data['uid']);
        if ($returnvalue == 0)
            return 3; //not allowed save new search
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        return true;
    }

    function canAddNewResumeSearch($uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $db = $this->getDBO();
            $query = "SELECT package.saveresumesearch
                        FROM `#__js_job_employerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            $flag = 0;
            foreach ($result as $row ) {
                if ($row->saveresumesearch == 1){
                    $flag = 1;
                    break;
                }
            }
            return $flag;
        }
    }

}
?>
    
