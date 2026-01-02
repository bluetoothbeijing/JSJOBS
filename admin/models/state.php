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

class JSJobsModelState extends JSModel {

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

    function getStatebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,name,enabled FROM #__js_job_states WHERE id = " . $c_id;
        $db->setQuery($query);
        $state = $db->loadObject();
        return $state;
    }

    /* STRAT EXPORT RESUMES */

    function getDefaultStatesForSharing($title, $countryid) {
        if (!is_numeric($countryid))
            return false;
        $states = array();
        $db = Factory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_states` WHERE enabled = 1 AND countryid=" . $countryid;
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
            $states[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $states[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $states;
    }

    function getDefaultStateCitiesForSharing($title, $stateid) {
        if (!is_numeric($stateid))
            return false;
        $cities = array();
        $db = Factory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_cities` WHERE enabled = 1 AND stateid=" . $stateid;
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
            $cities[] = array('value' => Text::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $cities;
    }

    function getAllCountryStates( $searchname, $searchstatus, $hascities, $countryid, $sortby, $js_sortby, $limitstart, $limit) {
        if($countryid)
            if(!is_numeric($countryid)) return false;
        
        $db = Factory::getDBO();
        if($js_sortby==1){
            $sortby = " name $sortby ";
        }elseif($js_sortby==2){
            $sortby = " enabled $sortby ";
        }else{
            $sortby = " name asc";
        }
        $fquery = " WHERE state.countryid = " . $countryid;
        if($searchname){
            $fquery .= " AND state.name LIKE ".$db->Quote('%'.$searchname.'%');
        }
        if($searchstatus || $searchstatus == 0){
            if(is_numeric($searchstatus))
                $fquery .= " AND state.enabled =".$searchstatus;
        }
        if(isset($hascities)){
            $fquery .= " AND (SELECT COUNT(id) FROM `#__js_job_cities` AS city WHERE city.stateid = state.id) > 0 ";
        }

        $lists = array();
        $lists['searchname'] = $searchname;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);
        $lists['hascities'] = $hascities;

        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_states` AS state";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT id,name,enabled FROM `#__js_job_states` AS state";
        $query .= $fquery." ORDER BY ".$sortby;
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeState($countryid) {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('state');
        $db = $this->getDBO();
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $data['countryid'] = $countryid;

        if (!$data['id']) { // only for new
            $existvalue = $this->isStateExist($data['name'], $data['countryid']);
            if ($existvalue == true)
                return 3;
            $data['shortRegion'] = $data['name'];
        } else
            $data['shortRegion'] = $data['name'];
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
        $server_state_data = array();
        if ($this->_client_auth_key != "") {
            $server_state_data['id'] = $row->id;
            $server_state_data['shortRegion'] = $row->shortRegion;
            $server_state_data['name'] = $row->name;
            $server_state_data['enabled'] = $row->enabled;
            $server_state_data['countryid'] = $row->countryid;
            $server_state_data['serverid'] = $row->serverid;
            $server_state_data['authkey'] = $this->_client_auth_key;
            if ($data['countryid']) {
                if (is_numeric($data['countryid']) == false)
                    return false;

                $query = "SELECT serverid FROM `#__js_job_countries` WHERE   id = " . $data['countryid'];
                $db->setQuery($query);
                $country_serverid = $db->loadResult();
                if ($country_serverid)
                    $server_state_data['countryid'] = $country_serverid;
                else
                    $server_state_data['countryid'] = 0;
            } else
                $server_state_data['countryid'] = 0;
            $table = "states";
            $jobsharing = $this->getJSModel('jobsharing');
            $return_value = $jobsharing->storeDefaultTables($server_state_data, $table);
            return $return_value;
        }else {
            return true;
        }
    }

    function deleteState() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('state');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->stateCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function stateCanDelete($stateid) {
        if (is_numeric($stateid) == false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT 
                    ( SELECT COUNT(mcity.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid=city.id 
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(cmcity.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_companycities` AS cmcity ON cmcity.cityid=city.id 
                            WHERE city.stateid = " . $stateid . "
                    )
                    +
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resumeaddresses` AS resume ON resume.address_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resumeinstitutes` AS resume ON resume.institute_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resumeemployers` AS resume ON resume.employer_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resumereferences` AS resume ON resume.reference_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function isStateExist($state, $countryid) {
        if($countryid)
            if(!is_numeric($countryid)) return false;
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_states WHERE name = " . $db->Quote($state) . " AND countryid = " . $countryid;

        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function getStates($country, $title = '') {
        if($country)
            if(!is_numeric($country)) return false;
        $states = array();
        $db = Factory::getDBO();
        if (is_null($country) OR empty($country))
            $country = 0;
        $query = "SELECT id,name FROM `#__js_job_states` WHERE enabled = '1' AND countryid = " . $country;
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
            $states[] = array('value' => '', 'text' => Text::_($title));
        foreach ($rows as $row) {
            $states[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $states;
    }

    function publishstates() {
        $row = $this->getTable('state');
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

    function unpublishstates() {
        $row = $this->getTable('state');
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
