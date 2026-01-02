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

class JSJobsModelAge extends JSModel {

    function __construct() {
        parent::__construct();
    }

    function getJobAgesbyId($cid) {
        if (is_numeric($cid) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,title,isdefault,status,ordering FROM #__js_job_ages WHERE id = " . $cid;
        $db->setQuery($query);
        $ages = $db->loadObject();
        return $ages;
    }

    function getAllAges($searchtitle, $searchstatus, $limitstart, $limit) {
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
        $query = "SELECT COUNT(id) FROM `#__js_job_ages`";
        $query.=$fquery;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT id,title,isdefault,status,ordering FROM `#__js_job_ages`";
        $query.=$fquery." ORDER By ordering ASC";
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeAges() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('ages');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $db = $this->getDBO();

        $returnvalue = 1;
        if ($data['id'] == '') { // only for new
            $result = $this->isAgesExist($data['title']);
            if ($result == true)
                $returnvalue = 3;
            else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM `#__js_job_ages`";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering;
                $data['isdefault'] = 0;
            }
        }else{
            if(!(isset($data['status']))){
                $return_var = $this->getJSModel('common')->canUnpublishRecord($data['id'],'ages');
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
            $server_ages_data = array();
            $return_value['issharing'] = 0;
            $return_value[1] = $returnvalue;
            $return_value[2] = $row->id;
            return $return_value;
        } else {
            return $returnvalue;
        }
    }

    function deleteAge() { 
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('ages');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->ageCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function ageCanDelete($ageid) {
        if (is_numeric($ageid) == false)
            return false;
        $db = $this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE agefrom = " . $ageid . " OR ageto = " . $ageid . ")					
                    + ( SELECT COUNT(id) FROM `#__js_job_ages` WHERE id = " . $ageid . " AND isdefault = 1)
                    AS total";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getAges($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_ages` WHERE status = 1";
        $query.=" ORDER BY ordering ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            return false;
        }
        $ages = array();
        if ($title)
            $ages[] = array('value' => 0, 'text' => $title);
        foreach ($rows as $row) {
            $ages[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $ages;
    }

    function isAgesExist($title) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_ages WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

}

?>
