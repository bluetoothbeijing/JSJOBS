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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelEmployerPackages extends JSModel {

    var $_config = null;
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

    function storeEmployerPackage() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('employerpackage');

        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string

        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configuration')->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }


        if ($dateformat == 'm/d/Y') {
            $arr = explode('/', $data['discountstartdate']);
            $data['discountstartdate'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
            $arr = explode('/', $data['discountenddate']);
            $data['discountenddate'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['discountstartdate']);
            $data['discountstartdate'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['discountenddate']);
            $data['discountenddate'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }

        if($data['discountstartdate'] != ''){
            $data['discountstartdate'] = date('Y-m-d H:i:s', getJSJobsPHPFunctionsClass()->jsjobs_strtotime($data['discountstartdate']));
        }else{
            $data['discountstartdate'] = '0000-00-00 00:00:00';
        }

        if($data['discountenddate'] != ''){
            $data['discountenddate'] = date('Y-m-d H:i:s', getJSJobsPHPFunctionsClass()->jsjobs_strtotime($data['discountenddate']));
        }else{
            $data['discountenddate'] = '0000-00-00 00:00:00';
        }
        $data['description'] = $this->getJSModel('common')->getHtmlInput('description');


        // deafult values fix
        if(!is_numeric($data['discount'])){
            $data['discount'] = 0;
        }
        if(!is_numeric($data['featuredcompaniesexpireindays'])){
            $data['featuredcompaniesexpireindays'] = 0;
        }
        if(!is_numeric($data['goldcompaniesexpireindays'])){
            $data['goldcompaniesexpireindays'] = 0;
        }

        if(!is_numeric($data['enforcestoppublishjobvalue'])){
            $data['enforcestoppublishjobvalue'] = 0;
        }
        if(!is_numeric($data['folders'])){
            $data['folders'] = 0;
        }

        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        return true;
    }

    function deleteEmployerPackage() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('employerpackage');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->employerPackageCanDelete($cid) == true) {

                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function employerPackageCanDelete($id) {
        if (is_numeric($id) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT 
                    (SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE packageid = " . $id . " AND packagefor=1 ) AS total";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getEmployerPackages($searchtitle, $searchprice, $searchstatus, $limitstart, $limit) {
        $db = Factory::getDBO();
        $fquery = "";
        $clause = " WHERE ";
        if($searchtitle){
            $fquery .= $clause."emp.title LIKE ".$db->Quote('%'.$searchtitle.'%');
            $clause = " AND ";
        }
        if($searchprice){
            $fquery .= $clause."emp.price = ".$db->Quote($searchprice);
            $clause = " AND ";
        }
        if($searchstatus || $searchstatus == 0){        
            if(is_numeric($searchstatus))
                $fquery .= $clause."emp.status =".$searchstatus;
        }
        $lists = array();
        $lists['searchtitle'] = $searchtitle;
        $lists['searchprice'] = $searchprice;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);

        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_employerpackages` emp ";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT emp.id,emp.title,emp.price,emp.discount,emp.status,cur.symbol,emp.discounttype
         FROM `#__js_job_employerpackages` emp
         LEFT JOIN `#__js_job_currencies` cur ON cur.id=emp.currencyid ";
        $query .= $fquery." ORDER BY id ASC";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function getEmployerPackagebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT * FROM `#__js_job_employerpackages` WHERE id = " . $c_id;
        $db->setQuery($query);
        $package = $db->loadObject();
        $status = array(
            '0' => array('value' => 0, 'text' => Text::_('Un-published')),
            '1' => array('value' => 1, 'text' => Text::_('Published')),);
        $enforcestoppublishjobtype = array(
            '0' => array('value' => 1, 'text' => Text::_('Days')),
            '1' => array('value' => 2, 'text' => Text::_('Weeks')),
            '2' => array('value' => 3, 'text' => Text::_('Months')),);
        $type = array(
            '0' => array('value' => 1, 'text' => Text::_('Amount')),
            '1' => array('value' => 2, 'text' => Text::_('%')),);

        $yesNo = array(
            '0' => array('value' => 1, 'text' => Text::_('JYES')),
            '1' => array('value' => 0, 'text' => Text::_('JNO')),);

        if (isset($package)) {
            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $package->status);
            $lists['unpublishjobtype'] = HTMLHelper::_('select.genericList', $enforcestoppublishjobtype, 'enforcestoppublishjobtype', 'class="inputbox required" ' . '', 'value', 'text', $package->enforcestoppublishjobtype);
            $lists['type'] = HTMLHelper::_('select.genericList', $type, 'discounttype', 'class="inputbox required" ' . '', 'value', 'text', $package->discounttype);
            $lists['resumesearch'] = HTMLHelper::_('select.genericList', $yesNo, 'resumesearch', 'class="inputbox required" ' . '', 'value', 'text', $package->resumesearch);
            $lists['saveresumesearch'] = HTMLHelper::_('select.genericList', $yesNo, 'saveresumesearch', 'class="inputbox required" ' . '', 'value', 'text', $package->saveresumesearch);
            $lists['messages'] = HTMLHelper::_('select.genericList', $yesNo, 'messageallow', 'class="inputbox required" ' . '', 'value', 'text', $package->messageallow);
            $lists['currency'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', $package->currencyid);
        } else {
            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', 1);
            $lists['unpublishjobtype'] = HTMLHelper::_('select.genericList', $enforcestoppublishjobtype, 'enforcestoppublishjobtype', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['type'] = HTMLHelper::_('select.genericList', $type, 'discounttype', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['resumesearch'] = HTMLHelper::_('select.genericList', $yesNo, 'resumesearch', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['saveresumesearch'] = HTMLHelper::_('select.genericList', $yesNo, 'saveresumesearch', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['messages'] = HTMLHelper::_('select.genericList', $yesNo, 'messageallow', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['currency'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', '');
        }

        $result[0] = $package;
        $result[1] = $lists;

        return $result;
    }

    function getEmployerPackageForCombo($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_employerpackages` WHERE status = 1 ORDER BY id ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $packages = array();
        if ($title)
            $packages[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $packages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $packages;
    }

    function getFreeEmployerPackageForCombo($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_employerpackages` WHERE status = 1 AND price = 0 ORDER BY id ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $packages = array();
        if ($title)
            $packages[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $packages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $packages;
    }

}

?>
