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

class JSJobsModelHighesteducation extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_heighesteducation = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getHighestEducationbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,title,isdefault,isactive,ordering FROM #__js_job_heighesteducation WHERE id = " . $c_id;

        $db->setQuery($query);
        $education = $db->loadObject();
        return $education;
    }

    function getAllHighestEducations($searchtitle, $searchstatus, $limitstart, $limit) {
        $db = Factory::getDBO();
        $fquery="";
        $clause=" WHERE ";
        if($searchtitle){
            $fquery = $clause."title LIKE ".$db->Quote('%'.$searchtitle.'%');
            $clause = " AND ";
        }
        if($searchstatus || $searchstatus == 0){
            if(is_numeric($searchstatus))
                $fquery .= $clause."isactive =".$searchstatus;
        }
        $lists = array();
        $lists['searchtitle'] = $searchtitle;
        $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getStatus('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);        
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_heighesteducation`";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT id,title,isdefault,isactive,ordering FROM `#__js_job_heighesteducation`";
        $query .= $fquery."  ORDER BY ordering ASC";
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeHighestEducation() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('highesteducation');
        $returnvalue = 1;
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        if ($data['id'] == '') { // only for new
            $result = $this->isHighestEducationExist($data['title']);
            if ($result == true)
                $returnvalue = 3;
            else {
                $db = Factory::getDBO();
                $query = "SELECT max(ordering)+1 AS maxordering FROM `#__js_job_heighesteducation`";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering;
                $data['isdefault'] = 0;
            }
        }else{
            if(!(isset($data['isactive']))){
                $return_var = $this->getJSModel('common')->canUnpublishRecord($data['id'],'heighesteducation');
                if($return_var=='no'){
                    $data['isactive'] = 1;
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
            $server_heighesteducation_data = array();
            if ($this->_client_auth_key != "") {
                $server_heighesteducation_data['id'] = $row->id;
                $server_heighesteducation_data['title'] = $row->title;
                $server_heighesteducation_data['isactive'] = $row->isactive;
                $server_heighesteducation_data['serverid'] = $row->serverid;
                $server_heighesteducation_data['authkey'] = $this->_client_auth_key;
                $table = "heighesteducation";
                $jobsharing = $this->getJSModel('jobsharing');

                $return_value = $jobsharing->storeDefaultTables($server_heighesteducation_data, $table);
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

    function deleteHighestEducation() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('highesteducation');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->highestEducationCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function highestEducationCanDelete($educationid) {
        if (is_numeric($educationid) == false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE heighestfinisheducation = " . $educationid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE educationid = " . $educationid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE mineducationrange = " . $educationid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE maxeducationrange = " . $educationid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE heighestfinisheducation = " . $educationid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_heighesteducation` WHERE id = " . $educationid . " AND isdefault =1)
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function getHeighestEducation($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_heighesteducation` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY ordering ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            return false;
        }
        $this->_heighesteducation = array();
        if ($title)
            $this->_heighesteducation[] = array('value' => 0, 'text' => $title);

        foreach ($rows as $row) {
            $this->_heighesteducation[] = array('value' => Text::_($row->id), 'text' => Text::_($row->title));
        }
        return $this->_heighesteducation;
    }

    function isHighestEducationExist($title) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_heighesteducation WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

}

?>
