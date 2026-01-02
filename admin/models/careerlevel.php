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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelCareerlevel extends JSModel {

    function __construct() {
        parent::__construct();
    }

    function getJobCareerLevelbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,title,isdefault,status,ordering FROM `#__js_job_careerlevels` WHERE id = " . $c_id;
        $db->setQuery($query);
        $career = $db->loadObject();
        return $career;
    }

    function getAllCareerLevels($searchtitle, $searchstatus, $limitstart, $limit) {
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
        $query = "SELECT COUNT(id) FROM `#__js_job_careerlevels`";
        $query.=$fquery;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT id,title,isdefault,status,ordering FROM `#__js_job_careerlevels`";
        $query.=$fquery." ORDER BY ordering ASC";
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeCareerLevel() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('careerlevel');
        $returnvalue = 1;
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        if ($data['id'] == '') { // only for new
            $result = $this->isCareerlevelExist($data['title']);
            if ($result == true)
                $returnvalue = 3;
            else {
                $db = Factory::getDBO();
                $query = "SELECT max(ordering)+1 AS maxordering FROM `#__js_job_careerlevels`";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering;
                $data['isdefault'] = 0;
            }
        }else{
            if(!(isset($data['status']))){
                $return_var = $this->getJSModel('common')->canUnpublishRecord($data['id'],'careerlevels');
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
            $server_careerlevels_data = array();
            if ($this->_client_auth_key != "") {
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

    function deleteCareerLevel() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('careerlevel');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->careerLevelCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function careerLevelCanDelete($careerlevelid) {
        if (is_numeric($careerlevelid) == false)
            return false;
        $db = $this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE careerlevel = " . $careerlevelid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_careerlevels` WHERE id = " . $careerlevelid . " AND isdefault = 1)
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCareerLevels($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_careerlevels` WHERE status = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY ordering ASC ";

        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            return false;
        }
        if ($title)
            $careerlevels[] = array('value' => 0, 'text' => $title);
        foreach ($rows as $row) {
            $careerlevels[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $careerlevels;
    }

    function isCareerlevelExist($title) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_job_careerlevels` WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

}

?>
