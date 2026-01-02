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
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelCities extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null; //A

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getCities($stateid, $title) {
        if($stateid)
            if(!is_numeric($stateid)) return false;

        $db = Factory::getDBO();
        if (empty($stateid))
            $stateid = 0;
        if (is_string($stateid))
            $stateid = $db->Quote($stateid);
        $query = "SELECT id,name FROM `#__js_job_cities` WHERE enabled = 'Y' AND stateid = " . $stateid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY name ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $cities = array();
        if ($title)
            $cities[] = array('value' => Text::_(''), 'text' => $title);
        else
            $cities[] = array('value' => Text::_(''), 'text' => Text::_('Select City'));

        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $cities;
    }

    function getJobsCity($showonlycityhavejobs, $theme, $noofrecord = 20) {
        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $this->getJSModel('common')->setTheme();
        $curdate = date('Y-m-d H:i:s');
        $havingquery = "";
        if ($showonlycityhavejobs == 1) {
            $havingquery = " HAVING totaljobsbycity > 0 ";
        }
        $cityid = "city.id AS cityid,";
        $query = "SELECT $cityid city.cityName AS cityname, COUNT(mcity.id) AS totaljobsbycity
                    FROM `#__js_job_cities` AS city
                    LEFT JOIN `#__js_job_countries` AS country ON country.id = city.countryid 
                    LEFT JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `#__js_job_jobs` AS job ON job.id = mcity.jobid 
                    WHERE country.enabled = 1 AND job.status=1 AND job.stoppublishing >= CURDATE() 
                    GROUP BY cityid $havingquery ORDER BY totaljobsbycity DESC, cityname ASC";

        $db->setQuery($query, 0, $noofrecord);
        $result1 = $db->loadObjectList();

        $result[0] = $result1;
        $result[2] = $dateformat;
        return $result;
    }

    function getAddressDataByCityName($cityname, $id = 0) {
        if($id)
            if(!is_numeric($id)) return false;
        
        $db = Factory::getDbo();
        $config = $this->getJSModel('configurations')->getConfigByFor('default');
        $countryname = null;
        if (strstr($cityname, ',')) {
            $cityname = str_replace(' ', '', $cityname);
            $array = explode(',', $cityname);
            $cityname = $array[0];
            $statename = $array[1];
            $countryname = (isset($array[2])) ? $array[2] : null;
        }

        $query = "SELECT city.cityName AS cname, state.name AS statename, country.name AS countryname, city.id AS id,city.latitude, city.longitude
                          FROM `#__js_job_cities` AS city  
                          JOIN `#__js_job_countries` AS country on city.countryid=country.id
                          LEFT JOIN `#__js_job_states` AS state on city.stateid=state.id";
        if ($id == 0) {
            if (isset($statename) AND $countryname) {
                $query .= " WHERE city.cityName LIKE " . $db->quote($cityname . '%') . " AND state.name LIKE " . $db->quote($statename . '%') . " AND country.name LIKE " . $db->quote($countryname . '%') . " AND country.enabled = 1 AND city.enabled = 1 AND state.enabled = 1 LIMIT " . $this->getJSModel('configurations')->getConfigValue("number_of_cities_for_autocomplete");
            }elseif( isset($statename)) {
                $query .= " WHERE city.cityName LIKE " . $db->quote($cityname . '%') . " AND state.name LIKE " . $db->quote($statename . '%') . " AND state.enabled = 1 AND country.enabled = 1 AND city.enabled = 1 LIMIT " . $this->getJSModel('configurations')->getConfigValue("number_of_cities_for_autocomplete");
            } else {
                $query .= " WHERE city.cityName LIKE " . $db->quote($cityname . '%') . " AND country.enabled = 1 AND city.enabled = 1 LIMIT " . $this->getJSModel('configurations')->getConfigValue("number_of_cities_for_autocomplete");
            }
        } else {
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
            if($data)
                $location = $this->getJSModel('common')->getLocationForView($data->cityname,$data->statename,$data->countryname);
        }
        return $location;
    }    


    function storeCity($input) {
        $tempData = explode(',', $input); // array to maintain spaces
        $input = str_replace(' ', '', $input); // remove spaces from citydata
        $latitude = Factory::getApplication()->input->get('latitude',null);
        $longitude = Factory::getApplication()->input->get('longitude',null);
        $row = $this->getTable('city');
        $db = $this->getDBO();


        // find number of commas
        $num_commas = substr_count($input, ',', 0);
        if ($num_commas == 1) { // only city and country names are given
            $cityname = $tempData[0];
            $countryname = str_replace(' ', '', $tempData[1]);
        } elseif ($num_commas > 1) {
            if ($num_commas > 2)
                return 5;
            $cityname = $tempData[0];
            if (mb_strpos($tempData[1], ' ') == 0) { // remove space from start of state name if exists
                $statename = substr($tempData[1], 1, strlen($tempData[1]));
            } else {
                $statename = $tempData[1];
            }
            $countryname = str_replace(' ', '', $tempData[2]);
        }

        // get list of countries from database and check if exists or not
        $countryid = $this->getJSModel('countries')->getCountryIdByName($countryname); // new function coded
        if (!$countryid) {
            return 4;
        }

        // if state name given in input check if exists or not otherwise store in database
        if (isset($statename)) {
            $stateid = $this->getJSModel('states')->getStateIdByName(str_replace(' ', '', $statename)); // new function coded
            if (!$stateid) {
                $statedata = array();
                $statedata['id'] = null;
                $statedata['name'] = ucwords($statename);
                $statedata['shortRegion'] = ucwords($statename);
                $statedata['countryid'] = $countryid;
                $statedata['enabled'] = 1;
                $statedata['serverid'] = 0;

                $newstate = $this->getJSModel('states')->storeState($statedata);
                if (!$newstate) {
                    return 3;
                }
                $stateid = $this->getJSModel('states')->getStateIdByName($statename); // to store with city's new record
            }
        } else {
            $stateid = null;
        }
        $cityexist = $this->isCityExist($countryid, $stateid, $cityname);
        if( $cityexist === false ){
            $data = array();
            $data['id'] = null;
            $data['cityName'] = ucwords($cityname);
            $data['name'] = ucwords($cityname);
            $data['stateid'] = $stateid;
            $data['countryid'] = $countryid;
            $data['isedit'] = 1;
            $data['enabled'] = 1;
            $data['serverid'] = 0;
            $data['latitude'] = $latitude;
            $data['longitude'] = $longitude;
            $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string

            if (!$row->bind($data)) {
                $this->setError($row->getError());
                echo $row->getError();
                return 2;
            }
            if (!$row->check()) {
                $this->setError($row->getError());
                echo $row->getError();
                return 2;
            }
            if (!$row->store()) {
                $this->setError($row->getError());
                echo $row->getError();
            }
        }else{
            $row = $cityexist;
        }
        $result[0] = 1;
        $result[1] = $row->id; // get the city id for forms
        $result[2] = $row->name . ', ' . $countryname; // get the city name for forms
        $result[3] = $row->latitude;
        $result[4] = $row->longitude;
        return $result;
    }

    function isCityExist($countryid, $stateid, $cityname){
        if (!is_numeric($countryid))
            return false;

        $db = $this->getDBO();
        $query = "SELECT id,name,latitude,longitude FROM `#__js_job_cities` WHERE countryid=" . $countryid . " AND LOWER(name) = " . $db->quote(strtolower($cityname)) . "";
        if(is_numeric($stateid) && $stateid > 0){
            $query .= " AND stateid=".$stateid;
        }else{
            $query .= " AND (stateid=0 OR stateid IS NULL)";
        }
        $db->setQuery($query);
        $city = $db->loadObject();
        if ($city != null)
            return $city;
        else
            return false;
    }

}
?>    
