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

class JSJobsModelJobtype extends JSModel {

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

    function getJobTypebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id,title,isdefault,isactive,ordering FROM #__js_job_jobtypes WHERE id = ".$c_id;
        $db->setQuery($query);
        $jobtype = $db->loadObject();
        return $jobtype;
    }

    function getAllJobTypes($searchtitle, $searchstatus, $limitstart, $limit) {
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
        $query = "SELECT COUNT(id) FROM `#__js_job_jobtypes`";
        $query.=$fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT id,title,isdefault,isactive,ordering FROM `#__js_job_jobtypes`";
        $query .= $fquery."  ORDER BY ordering ASC";
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeJobType() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $db = Factory::getDBO();
        $row = $this->getTable('jobtype');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $returnvalue = 1;
        if ($data['id'] == '') { // only for new
            $result = $this->isJobTypesExist($data['title']);
            if ($result == true)
                $returnvalue = 3;
            else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM `#__js_job_jobtypes`";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering;
                $data['isdefault'] = 0;
            }
        }else{
            if(!(isset($data['isactive']))){
                $return_var = $this->getJSModel('common')->canUnpublishRecord($data['id'],'jobtypes');
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

            $server_jobtype_data = array();
            if ($this->_client_auth_key != "") {
                $server_jobtype_data['id'] = $row->id;
                $server_jobtype_data['title'] = $row->title;
                $server_jobtype_data['isactive'] = $row->isactive;
                $server_jobtype_data['status'] = $row->status;
                $server_jobtype_data['serverid'] = $row->serverid;
                $server_jobtype_data['authkey'] = $this->_client_auth_key;
                $table = "jobtypes";
                $jobsharing = $this->getJSModel('jobsharing');

                $return_value = $jobsharing->storeDefaultTables($server_jobtype_data, $table);
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

    function deleteJobType() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('jobtype');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->jobTypeCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function jobTypeCanDelete($typeid) {
        if (is_numeric($typeid) == false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobtype = ".$typeid.")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE jobtype = ".$typeid.")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobtypes` WHERE id = ".$typeid." AND isdefault = 1)
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function getJobType($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY ordering ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $this->_jobtype = array();
        if ($title)
            $this->_jobtype[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $this->_jobtype[] = array('value' => Text::_($row->id),
                'text' => Text::_($row->title));
        }

        return $this->_jobtype;
    }

    function isJobTypesExist($title) {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_jobtypes WHERE title = ".$db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }
}
?>
