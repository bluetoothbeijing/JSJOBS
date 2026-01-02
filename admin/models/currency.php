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

class JSJobsModelCurrency extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getCurrencybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT c.id,c.title,c.symbol,c.code,c.default,c.ordering,c.status FROM #__js_job_currencies AS c WHERE id = " . $c_id;
        $db->setQuery($query);
        $currency = $db->loadObject();
        return $currency;
    }

    function getCurrency($title = "") {
        $db = Factory::getDBO();
        if (!isset($this->_defaultcurrency))
            $this->_defaultcurrency = $this->getDefaultCurrency();

        if (is_numeric($this->_defaultcurrency) == false)
            return false;

        $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1 AND id = " . $this->_defaultcurrency;
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $db->setQuery($q);
        $defaultcurrency = $db->loadObject();
        $combobox = array();
        if ($title)
            $combobox[] = array('value' => '', 'text' => $title);
        //$combobox[] = array('value' => $defaultcurrency->id, 'text' => Text::_($defaultcurrency->symbol));

        $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1 ";
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $q.=" ORDER BY ordering ASC";
        $db->setQuery($q);
        $allcurrency = $db->loadObjectList();
        foreach ($allcurrency as $currency) {
            $combobox[] = array('value' => $currency->id, 'text' => Text::_($currency->symbol));
        }

        return $combobox;
    }

    function getDefaultCurrency() {
        $db = Factory::getDBO();
        $q = "SELECT currency.id FROM `#__js_job_currencies` currency WHERE currency.default = 1 AND currency.status=1";
        $db->setQuery($q);
        $defaultValue = $db->loadResult();
        if (!$defaultValue) {
            $q = "SELECT id FROM `#__js_job_currencies` WHERE status=1";
            $db->setQuery($q);
            $defaultValue = $db->loadResult();
        }
        return $defaultValue;
    }

    function getDefaultCurrencyValue() {
        $db = Factory::getDBO();
        $q = "SELECT symbol FROM `#__js_job_currencies` AS symbol WHERE symbol.default = 1";
        $db->setQuery($q);
        $defaultValue = $db->loadResult();
        if (!$defaultValue) {
            $q = "SELECT symbol FROM `#__js_job_currencies`";
            $db->setQuery($q);
            $defaultValue = $db->loadResult();
        }
        return $defaultValue;
    }

    function getAllCurrencies($searchtitle, $searchstatus, $searchcode, $limitstart, $limit) {
        $db = Factory::getDBO();
        $fquery="";
        $clause=" WHERE ";
        if($searchtitle){
            $fquery = $clause."c.title LIKE ".$db->Quote('%'.$searchtitle.'%');
            $clause = " AND ";
        }
        if($searchstatus || $searchstatus == 0){
            if(is_numeric($searchstatus)){
                $fquery .= $clause."c.status =".$searchstatus;
                $clause = " AND ";
            }
        }
        if($searchcode){
            $fquery .= $clause."c.code LIKE ".$db->Quote('%'.$searchcode.'%');
        }

        $lists = array();
        $lists['searchtitle'] = $searchtitle;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);
        $lists['searchcode'] = $searchcode;

        $query = "SELECT count(id) FROM `#__js_job_currencies` AS c";
        $query .=$fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT c.id,c.title,c.symbol,c.code,c.default,c.ordering,c.status FROM `#__js_job_currencies` AS c";
        $query .= $fquery." ORDER BY c.ordering ASC";
        $db->setQuery($query, $limitstart, $limit);
        $currencyresults = $db->loadObjectList();

        $result[0] = $currencyresults;
        $result[1] = $total;
        $result[2] = $lists;

        return $result;
    }

    function storeCurrency() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('currency');
        $returnvalue = 1;
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        if ($data['id'] == '') { // only for new
            $result = $this->isCurrencyExist($data['title']);
            if ($result == true)
                $returnvalue = 3;
            else {
                $db = Factory::getDBO();
                $query = "SELECT max(ordering)+1 AS maxordering FROM #__js_job_currencies";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering;
                $data['default'] = 0;
            }
        }else{
            if(!(isset($data['status']))){
                $return_var = $this->getJSModel('common')->canUnpublishRecord($data['id'],'currencies');
                if($return_var=='no'){
                    $data['status'] = 1;
                }
            }
        }

        $data['status'] = isset($data['status']) ? $data['status'] : 0;
        if ($returnvalue == 1) {
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
                return false;
            }
            $server_currencies_data = array();
            if ($this->_client_auth_key != "") {
                $server_currencies_data['id'] = $row->id;
                $server_currencies_data['title'] = $row->title;
                $server_currencies_data['symbol'] = $row->symbol;
                $server_currencies_data['status'] = $row->status;
                $server_currencies_data['default'] = $row->default;
                $server_currencies_data['serverid'] = $row->serverid;
                $server_currencies_data['authkey'] = $this->_client_auth_key;
                $table = "currencies";
                $jobsharing = $this->getJSModel('jobsharing');
                $return_value = $jobsharing->storeDefaultTables($server_currencies_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        } else {
            return $returnvalue;
        }
    }

    function isCurrencyExist($title) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_currencies WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function deleteCurrency() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('currency');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->currencyCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function currencyCanDelete($currencyid) {
        if (is_numeric($currencyid) == false)
            return false;
        $db = $this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE currencyid = " . $currencyid . " OR dcurrencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_employerpackages` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobseekerpackages` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.default =1)
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCurrencyResumeApplied($title) {
        $db = Factory::getDBO();
        $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1";
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $db->setQuery($q);
        $allcurrency = $db->loadObjectList();
        $combobox = array();
        if ($title)
            $combobox[] = array('value' => Text::_(''), 'text' => $title);
        if (!empty($allcurrency)) {
            foreach ($allcurrency as $currency) {
                $combobox[] = array('value' => $currency->id, 'text' => Text::_($currency->symbol));
            }
        }
        return $combobox;
    }

    function makeDefaultCurrency($id, $defaultvalue) {
        if (is_numeric($id) == false)
            return false;
        if (is_numeric($defaultvalue) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_job_currencies` WHERE id = ".$id." AND status = 1";
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result == 1){
            $query = "update `#__js_job_currencies` as currency SET currency.default = 0 ";
            $db->setQuery($query);
            if (!$db->execute()) {
                return false;
            }
            $row = $this->getTable('currency');
            $row->id = $id;
            $row->default = $defaultvalue;
            if (!$row->store()) {
                $this->setError($row->getError());
                return false;
            }
        }else{
            return false;
        }
        return true;
    }

    function getCurrencySymbol($id){
        if(!($id > 0)){
            return '';
        }
        if (is_numeric($id) === false)
            return false;
        $db = Factory::getDBO();
        $q = "SELECT symbol FROM `#__js_job_currencies` WHERE id=".$id;
        $db->setQuery($q);
        $symbol = $db->loadResult();
        return $symbol;
    }
}

?>
