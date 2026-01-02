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
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelCurrency extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_defaultcurrency = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getCurrencyCombo() {
        $db = Factory::getDBO();
        $query = "SELECT id, symbol FROM `#__js_job_currencies` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $currency = array();
        $currency[] = array('value' => Text::_(''), 'text' => Text::_('Select Currency'));
        foreach ($rows as $row) {
            $currency[] = array('value' => $row->id, 'text' => $row->symbol);
        }
        $currencycombo = HTMLHelper::_('select.genericList', $currency, 'currency', 'class="inputbox" ' . 'style="width:40%;"', 'value', 'text', '');
        return $currencycombo;
    }

    function getCurrencyComboFORMP() {
        $db = Factory::getDBO();
        $query = "SELECT id, symbol FROM `#__js_job_currencies` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $currency = array();
        $currency[] = array('value' => Text::_(''), 'text' => Text::_('Select Currency'));
        foreach ($rows as $row) {
            $currency[] = array('value' => $row->id, 'text' => $row->symbol);
        }
        $currencycombo = HTMLHelper::_('select.genericList', $currency, 'currencyid', 'class="inputbox" ' . 'style="width:40%;"', 'value', 'text', '');
        return $currencycombo;
    }

    function getCurrency($title = "") {
        $db = Factory::getDBO();

        // if (!isset($this->_defaultcurrency)) {
        //     $this->_defaultcurrency = $this->getDefaultCurrency();
        // }
        // $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1 AND id = " . $this->_defaultcurrency;
        // if ($this->_client_auth_key != "")
        //     $q.=" AND serverid!='' AND serverid!=0";
        // $db->setQuery($q);
        // $defaultcurrency = $db->loadObject();

        $combobox = array();
        if ($title)
            $combobox[] = array('value' => '', 'text' => $title);

        $q = "SELECT id,symbol FROM `#__js_job_currencies` WHERE status = 1 ";
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $q.=" ORDER BY ordering ASC";
        $db->setQuery($q);
        $allcurrency = $db->loadObjectList();
        if (!empty($allcurrency)) {
            foreach ($allcurrency as $currency) {
                $combobox[] = array('value' => $currency->id, 'text' => Text::_($currency->symbol));
            }
        }
        return $combobox;
    }

    function getDefaultCurrency() {
        $db = Factory::getDBO();
        $q = "SELECT id FROM `#__js_job_currencies` AS id WHERE id.default = 1 AND id.status=1";
        $db->setQuery($q);
        $defaultValue = $db->loadResult();
        if (!$defaultValue) {
            $q = "SELECT id FROM `#__js_job_currencies` WHERE status=1";
            $db->setQuery($q);
            $defaultValue = $db->loadResult();
        }
        return $defaultValue;
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

    function getCurrencyCode($id){
        if(!($id > 0)){
            return '';
        }
        if (is_numeric($id) === false)
            return false;
        $db = Factory::getDBO();
        $q = "SELECT code FROM `#__js_job_currencies` WHERE id=".$id;
        $db->setQuery($q);
        $code = $db->loadResult();
        return $code;
    }


}
?>    
