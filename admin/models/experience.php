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

class JSJobsModelExperience extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_experiences = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobExperiencebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,title,isdefault,status,ordering FROM #__js_job_experiences WHERE id = " . $c_id;

        $db->setQuery($query);
        $experience = $db->loadObject();
        return $experience;
    }

    function getAllExperience($searchtitle, $searchstatus, $limitstart, $limit) {
        $db = Factory::getDBO();
        $fquery="";
        $clause=" WHERE ";
        if($searchtitle){
            $fquery = $clause."title LIKE ".$db->Quote('%'.$searchtitle.'%');
            $clause = " AND ";
        }
        if($searchstatus || $searchstatus == 0){
            if(is_numeric($searchstatus))
                $fquery .= $clause."status =".$searchstatus;
        }
        $lists = array();
        $lists['searchtitle'] = $searchtitle;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_experiences";
        $query .=$fquery;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT id,title,isdefault,status,ordering FROM #__js_job_experiences";
        $query .=$fquery." ORDER BY ordering ASC";
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeExperience() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('experience');
        $returnvalue = 1;
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        if ($data['id'] == '') { // only for new
            $result = $this->isExperiencesExist($data['title']);
            if ($result == true)
                $returnvalue = 3;
            else {
                $db = Factory::getDBO();
                $query = "SELECT max(ordering)+1 AS maxordering FROM #__js_job_experiences";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering;
                $data['isdefault'] = 0;
            }
        }else{
            if(!(isset($data['status']))){
                $return_var = $this->getJSModel('common')->canUnpublishRecord($data['id'],'experiences');
                if($return_var=='no'){
                    $data['status'] = 1;
                }
            }
        }

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
            $server_experiences_data = array();
            if ($this->_client_auth_key != "") {
                $server_experiences_data['id'] = $row->id;
                $server_experiences_data['title'] = $row->title;
                $server_experiences_data['status'] = $row->status;
                $server_experiences_data['serverid'] = $row->serverid;
                $server_experiences_data['authkey'] = $this->_client_auth_key;
                $table = "experiences";
                $jobsharing = $this->getJSModel('jobsharing');
                $return_value = $jobsharing->storeDefaultTables($server_experiences_data, $table);
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

    function deleteExperience() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('experience');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->experienceCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function experienceCanDelete($experienceid) {
        if (is_numeric($experienceid) == false)
            return false;
        $db = $this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE experienceid = " . $experienceid . " OR minexperiencerange = " . $experienceid . " OR maxexperiencerange = " . $experienceid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_experiences` WHERE id = " . $experienceid . " AND isdefault = 1)
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getExperiences($title) {
        if (!$this->_experiences) {
            $db = Factory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_experiences` WHERE status = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY ordering ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
            $this->_experiences = $rows;
        }

        $experiences = array();
        if ($title)
            $experiences[] = array('value' => 0, 'text' => $title);
        foreach ($this->_experiences as $row) {
            $experiences[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $experiences;
    }

    function isExperiencesExist($title) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_experiences WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

}

?>
