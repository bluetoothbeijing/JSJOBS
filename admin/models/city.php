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

class JSJobsModelCity extends JSModel {

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

    function getCitybyId($c_id, $countryid, $stateid) {
        if (is_numeric($c_id) == false)
            return false;
        $list = array();
        $return = array();
        $db = Factory::getDBO();
        $city = NULL;
        if ($c_id) {
            $query = "SELECT id,name,enabled,latitude,longitude FROM #__js_job_cities WHERE id = " . $c_id;
            $db->setQuery($query);
            $city = $db->loadObject();
        }
        $return[0] = $city;
        if (isset($countryid)) {
            $states = $this->getJSModel('state')->getStates($countryid, 'STATES');
            $list['states'] = HTMLHelper::_('select.genericList', $states, 'stateid', 'class="inputbox" ' . '', 'value', 'text', $stateid);
            $return[1] = $list;
        }
        return $return;
    }

    function getDefaultCitiesForSharing($title, $countryid) {
        if (!is_numeric($countryid))
            return false;
        $cities = array();
        $db = Factory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_cities` WHERE enabled = 1 AND countryid=" . $countryid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        if ($title)
            $cities[] = array('value' => 0, 'text' => $title);
        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }

        return $cities;
    }

    function getAllStatesCities($searchname, $searchstatus, $stateid, $countryid,  $sortby, $js_sortby, $limitstart, $limit) {
        $db = Factory::getDBO();
        
        if($js_sortby==1){
            $sortby = " name $sortby ";
        }elseif($js_sortby==2){
            $sortby = " enabled $sortby ";
        }else{
            $sortby = " name asc";
        }


        $result = array();
        $wherequery = "";
        $fquery = "";
        if ($stateid) {
            if (!is_numeric($stateid))
                return false;
            $wherequery = " WHERE stateid = " . $stateid;
        }
        if ($countryid) {
            if(!is_numeric($countryid)) return false;
            if (empty($wherequery))
                $wherequery = " WHERE countryid = " . $countryid;
            else
                $wherequery .= " AND countryid = " . $countryid;
        }
        $fquery = $wherequery;

        if ($searchname) {
            if($fquery != ''){
                $fquery .= " AND name LIKE " . $db->Quote('%' . $searchname . '%');
            }else{
                $fquery .= " WHERE name LIKE " . $db->Quote('%' . $searchname . '%');
            }
        }

        if(is_numeric($searchstatus))
            $fquery .= " AND enabled =".$searchstatus;
        $query = "SELECT COUNT(id) FROM `#__js_job_cities`";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT id,name,enabled FROM `#__js_job_cities` ";
        $query .= $fquery;
        $query .= " ORDER BY ".$sortby;

        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $lists['searchname'] = $searchname;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);
        $result[2] = $lists;
        
        return $result;
    }

    function storeCity($countryid, $stateid) {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('city');
        $db = $this->getDBO();
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $data['countryid'] = $countryid;
        $data['stateid'] = $stateid;
        $data['cityName'] = $data['name'];

        if (!$data['id']) { // only for new
            $existvalue = $this->isCityExist($countryid, $stateid, $data['name']);
            if ($existvalue == true)
                return 3;
            $row->isedit = 1;
        }

        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return 2;
        }
        // city table does not have a code column
        // if (!$data['id'])
        //     $row->code = $code;
        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }
        $server_city_data = array();
        if ($this->_client_auth_key != "") {
            $server_city_data['id'] = $row->id;
            $server_city_data['cityName'] = $row->cityName;
            $server_city_data['name'] = $row->name;
            $server_city_data['enabled'] = $row->enabled;
            $server_city_data['serverid'] = $row->serverid;
            $server_city_data['authkey'] = $this->_client_auth_key;
            if ($data['countryid']) {
                if (is_numeric($data['countryid']) == false)
                    return false;
                $query = "SELECT serverid FROM `#__js_job_countries` WHERE   id = " . $data['countryid'];
                $db->setQuery($query);
                $country_serverid = $db->loadResult();
                if ($country_serverid)
                    $server_city_data['countryid'] = $country_serverid;
                else
                    $server_city_data['countryid'] = 0;
            } else
                $server_city_data['countryid'] = 0;
            if ($data['stateid']) {
                if (is_numeric($data['stateid']) == false)
                    return false;
                $query = "SELECT serverid FROM `#__js_job_states` WHERE   id = " . $data['stateid'];
                $db->setQuery($query);
                $state_serverid = $db->loadResult();
                if ($state_serverid)
                    $server_city_data['stateid'] = $state_serverid;
                else
                    $server_city_data['stateid'] = 0;
            } else
                $server_city_data['stateid'] = 0;
            $table = "cities";
            $jobsharing = $this->getJSModel('jobsharing');
            $return_value = $jobsharing->storeDefaultTables($server_city_data, $table);
            return $return_value;
        }else {
            return true;
        }
    }

    function deleteCity() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('city');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->cityCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function cityCanDelete($cityid) {
        if (is_numeric($cityid) == false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT id FROM `#__js_job_cities`	WHERE id = " . $cityid;
        $db->setQuery($query);
        $city = $db->loadObject();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobcities` WHERE cityid = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_companycities` WHERE cityid = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resumeaddresses` WHERE address_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resumeinstitutes` WHERE institute_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resumeemployers` WHERE employer_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resumereferences` WHERE reference_city = " . $cityid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCityExist($countryid, $stateid, $title) {
        if (!is_numeric($countryid))
            return false;
        if (!is_numeric($stateid))
            return false;
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_cities WHERE countryid=" . $countryid . "
		AND stateid=" . $stateid . " AND LOWER(name) = " . $db->Quote(strtolower($title));
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return 0;
        else
            return 1;
    }

    function getAddressDataByCityName($cityname, $id = 0) {
        if($id)
            if (!is_numeric($id))
                return false;
        $db = Factory::getDbo();
        $config = $this->getJSModel('configuration')->getConfigByFor('default');
        

        $query = "SELECT city.cityName AS cname, state.name AS statename, country.name AS countryname,city.id AS id
                          FROM `#__js_job_cities` AS city  
                          JOIN `#__js_job_countries` AS country on city.countryid=country.id
                          LEFT JOIN `#__js_job_states` AS state on city.stateid=state.id";
        if ($id == 0){
            $query .= " WHERE city.cityName LIKE ". $db->Quote($cityname.'%') ." AND country.enabled = 1 AND city.enabled = 1 LIMIT " . $this->getJSModel('configuration')->getConfigValue("number_of_cities_for_autocomplete");
        }else{
            if(!is_numeric($id)) return null;
            $query .= " WHERE city.id = $id AND country.enabled = 1 AND city.enabled = 1";
        }
        $db->setQuery($query);

        $result = $db->loadObjectList();

        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    if($city->statename != ''){
                        $cityname .= ','. Text::_($city->statename);
                    }
                    $cityname .= ','. Text::_($city->countryname);
                    $city->name = $cityname;
                }
                break;
            case 'cs'://City, State
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    if($city->statename != ''){
                        $cityname .= ','. Text::_($city->statename);
                    }
                    $city->name = $cityname;
                }
                
                break;
            case 'cc'://City, Country
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    $cityname .= ','. Text::_($city->countryname);
                    $city->name = $cityname;
                }
                
                break;
            case 'c'://city by default select for each case
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    if($city->statename != ''){
                        $cityname .= ','. Text::_($city->statename);
                    }
                    $cityname .= ','. Text::_($city->countryname);
                    $city->name = $cityname;
                }
                break;
        }

        if (empty($result))
            return null;
        else
            return $result;
    }

    function getCities($stateid) {
        $cities = array();
        $db = Factory::getDBO();
        if (is_null($stateid) OR empty($stateid))
            $stateid = 0;
        if($stateid)
            if(!is_numeric($stateid)) return false;
        
        $query = "SELECT id,name FROM `#__js_job_cities` WHERE enabled = 'Y' AND stateid = " . $stateid . " ORDER BY name ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $cities;
    }

    private function getDataForLocationByCityID($id){
        if(!is_numeric($id)) return false;
        $db = Factory::getDBO();
        $query = "SELECT city.cityName AS cityname,state.name AS statename,country.name AS countryname
                    FROM `#__js_job_cities` AS city 
                    JOIN `#__js_job_countries` AS country ON country.id = city.countryid
                    LEFT JOIN `#__js_job_states` AS state ON state.id = city.stateid
                    WHERE city.id = $id";
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

    function getLocationDataForView($cityids) {
        if ($cityids == '') return false;
        $location = '';
        if(strstr($cityids,',')){ // multi cities id
            $cities = explode(',',$cityids);
            $data = array();
            foreach($cities AS $city){
                $data[] = $this->getDataForLocationByCityID($city);
            }
            $databycountry = array();
            foreach($data AS $d){
                $databycountry[$d->countryname][] = array('cityname' => $d->cityname,'statename' => $d->statename);
            }
            foreach($databycountry AS $countryname => $locdata){
                $call = 0;
                foreach($locdata AS $dl){
                    if($call == 0){
                        $location .= '['.$dl['cityname'];
                        if($dl['statename']){
                            $location .= '-'.$dl['statename'];
                        }
                    }else{
                        $location .= ', '.$dl['cityname'];
                        if($dl['statename']){
                            $location .= '-'.$dl['statename'];
                        }
                    }
                    $call++;
                }
                $location .= ', '.$countryname.'] ';
            }
        }else{ // single city id
            $data = $this->getDataForLocationByCityID($cityids);
            if(isset($data)){
                $location = $this->getJSModel('common')->getLocationForView($data->cityname,$data->statename,$data->countryname);
            }
        }
        return $location;
    }    

    function publishcities() {
        $row = $this->getTable('city');
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

    function unpublishcities() {
        $row = $this->getTable('city');
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
}
?>
