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

class JSJobsModelCountry extends JSModel {

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

    function getSharingCountries($title) {
        $db = Factory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_countries` WHERE enabled = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";

        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            return false;
        }
        $countries = array();
        if ($title)
            $countries[] = array('value' => Text::_(''), 'text' => $title);
        else
            $countries[] = array('value' => Text::_(''), 'text' => Text::_('Select Country'));
        foreach ($rows as $row) {
            $countries[] = array('value' => $row->id,
                'text' => Text::_($row->name));
        }
        return $countries;
    }

    function getCountrybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,name,enabled FROM #__js_job_countries WHERE id = " . $c_id;
        $db->setQuery($query);
        $country = $db->loadObject();
        return $country;
    }

    function getAllCountries($searchname, $searchstatus , $hasstates, $hascities, $sortby, $js_sortby, $limitstart, $limit) {
        $db = Factory::getDBO();
        if($js_sortby==1){
            $sortby = "country.name $sortby ";
        }elseif($js_sortby==2){
            $sortby = "country.enabled $sortby ";
        }else{
            $sortby = "country.name asc";
        }

        $fquery="";
        $clause=" WHERE ";
        if($searchname){
            $fquery = $clause."country.name LIKE ".$db->Quote('%'.$searchname.'%');
            $clause = " AND ";
        }
        if($searchstatus || $searchstatus == 0){
            if(is_numeric($searchstatus)){
                $fquery .= $clause."country.enabled =".$searchstatus;
                $clause = " AND ";
            }
        }

        if(isset($hasstates)){
            $fquery .= $clause." (SELECT COUNT(id) FROM `#__js_job_states` AS state WHERE state.countryid = country.id) > 0 ";
            $clause = " AND ";
        }
        if(isset($hascities)){
            $fquery .= $clause." (SELECT COUNT(id) FROM `#__js_job_cities` AS city WHERE city.countryid = country.id) > 0 ";
        }

        $lists = array();
        $lists['searchname'] = $searchname;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);
        $lists['hasstates'] = $hasstates;
        $lists['hascities'] = $hascities;

        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_countries` AS country";
        $query .= $fquery;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT id,name,enabled FROM `#__js_job_countries` AS country";
        $query .=$fquery . " ORDER BY ".$sortby;


        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;

        return $result;
    }

    function storeCountry() {
        $row = $this->getTable('country');
        $data = Factory::getApplication()->input->post->getArray();
        $data['shortCountry'] = str_replace(' ', '-', $data['name']);
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string

        if ($data['id'] == '') { // only for new
            $existvalue = $this->isCountryExist($data['name']);
            if ($existvalue == true)
                return 3;
        }
        if (!$row->bind($data)) {
            echo $this->setError($row->getError());
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return 2;
        }
        if (!$row->store()) {
            echo $this->setError($row->getError());
            return false;
        }

        $server_country_data = array();
        if ($this->_client_auth_key != "") {
            $server_country_data['id'] = $row->id;
            $server_country_data['shortCountry'] = $row->shortCountry;
            $server_country_data['continentID'] = $row->continentID;
            $server_country_data['dialCode'] = $row->dialCode;
            $server_country_data['name'] = $row->name;
            $server_country_data['enabled'] = $row->enabled;
            $server_country_data['serverid'] = $row->serverid;
            $server_country_data['authkey'] = $this->_client_auth_key;
            $table = "countries";
            $jobsharing = $this->getJSModel('jobsharing');
            $return_value = $jobsharing->storeDefaultTables($server_country_data, $table);
            return $return_value;
        } else {
            return true;
        }
    }

    function deleteCountry() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('country');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->countryCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCounty() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('county');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->countyCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function countryCanDelete($countryid) {
        if (is_numeric($countryid) == false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(jobcity.id) 
                        FROM `#__js_job_jobcities` AS jobcity
                        JOIN `#__js_job_cities` AS city ON city.id = jobcity.cityid
                        WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(companycity.id) 
                            FROM `#__js_job_companycities` AS companycity
                            JOIN `#__js_job_cities` AS city ON city.id = companycity.cityid
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE nationality = " . $countryid . ")
                    + ( SELECT COUNT(resumecity.id) 
                            FROM `#__js_job_resumeaddresses` AS resumecity
                            JOIN `#__js_job_cities` AS city ON city.id = resumecity.address_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(institutecity.id) 
                            FROM `#__js_job_resumeinstitutes` AS institutecity
                            JOIN `#__js_job_cities` AS city ON city.id = institutecity.institute_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(employeecity.id) 
                            FROM `#__js_job_resumeemployers` AS employeecity
                            JOIN `#__js_job_cities` AS city ON city.id = employeecity.employer_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(referencecity.id) 
                            FROM `#__js_job_resumereferences` AS referencecity
                            JOIN `#__js_job_cities` AS city ON city.id = referencecity.reference_city
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_states` WHERE countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_cities` WHERE countryid = " . $countryid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCountryExist($country) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_countries WHERE name = " . $db->Quote($country);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function getCountries($title) {
        $db = Factory::getDBO();
        $query = "SELECT id,name FROM `#__js_job_countries` WHERE enabled = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";

        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $countries = array();
        if ($title)
            $countries[] = array('value' => 0, 'text' => $title);
        else
            $countries[] = array('value' => 0, 'text' => Text::_('Select Country'));
        foreach ($rows as $row) {
            $countries[] = array('value' => $row->id,
                'text' => Text::_($row->name));
        }
        return $countries;
    }

    function publishcountries() {
        $row = $this->getTable('country');
        $cids = Factory::getApplication()->input->get('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '1';
            if (!$row->bind($data)) {
                $this->setError($row->getError());
                return false;
            }
            if (!$row->store()) {
                $this->setError($row->getError());
                return false;
            }
        }
        return true;
    }

    function unpublishcountries() {
        $row = $this->getTable('country');
        $cids = Factory::getApplication()->input->get('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '0';
            if (!$row->bind($data)) {
                $this->setError($row->getError());
                return false;
            }
            if (!$row->store()) {
                $this->setError($row->getError());
                return false;
            }
        }
        return true;
    }

    function getCountryNameById($country_id) {
        if (is_numeric($country_id) == false)
            return false;
        $country_name = '';
        $db = Factory::getDBO();
        $query = "SELECT name AS title FROM `#__js_job_countries` WHERE id = ".$country_id;
        $db->setQuery($query);
        $country_object = $db->loadObject();
        if(!empty($country_object)){
            $country_name = $country_object->title;
        }
        //echo var_dump($country_name);die('country_model 22114');
        return $country_name;
    }
}
?>
