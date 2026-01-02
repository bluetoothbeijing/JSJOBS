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

class JSJobsModelDepartment extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_application = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getDepartmentById($c_id, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $result = array();
        $status = array(
            '0' => array('value' => 0, 'text' => Text::_('Pending')),
            '1' => array('value' => 1, 'text' => Text::_('Approve')),
            '2' => array('value' => -1, 'text' => Text::_('Reject')),);

        $query = "SELECT department.id,department.uid,department.companyid,department.description,department.name,department.created,department.status
		FROM `#__js_job_departments` AS department
		 WHERE department.id=" . $c_id;
        $db->setQuery($query);
        $department = $db->loadObject();
        if (!empty($department))
            if ($department->uid != $uid)
                $uid = $department->uid;
        $companies = $this->getJSModel('company')->getCompanies($uid);
        if (isset($department)) {
            $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $department->companyid);
            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox " ' . '', 'value', 'text', $department->status);
        } else {
            $companyid = Factory::getApplication()->input->get('md');
            $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox required " ' . '', 'value', 'text', $companyid);
            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox " ' . '', 'value', 'text', 1);
        }

        $result[0] = $department;
        $result[1] = $lists;
        return $result;
    }

    function getDepartmentsByCompanyId($companyid, $title) {
        if ($companyid)
            if (is_numeric($companyid) == false)
                return false;
        $db = Factory::getDBO();
        $departments = array();
        if ($companyid) {
            $query = "SELECT id, name FROM `#__js_job_departments` WHERE status = 1 AND companyid = " . $companyid . " ORDER BY name ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }

            if ($title)
                $departments[] = array('value' => Text::_(''), 'text' => $title);
            foreach ($rows as $row) {
                $departments[] = array('value' => $row->id, 'text' => $row->name);
            }
        }
        return $departments;
    }

    function getDepartments($datafor, $searchcompany, $searchdepartment, $searchstatus, $limitstart, $limit, $companyid = '') {
        $db = Factory::getDBO();
        if($datafor==1){ // 1 for departments
            $fquery = " WHERE department.status <> 0";
            if($searchstatus || $searchstatus == 0){
                if(is_numeric($searchstatus)){
                    $fquery = " WHERE department.status = ".$searchstatus;
                }
            }
        }else{ // 2 for Queue
            $fquery = " WHERE department.status = 0";
        }
        if(is_numeric($companyid)){
            $c_q = "SELECT name FROM `#__js_job_companies` WHERE id =".$companyid;
            $db->setQuery($c_q);
            $c_name = $db->loadResult();
            $fquery .= " AND department.companyid = ".$companyid;
        }else{
            $c_name = '';
            if($searchcompany)
                $fquery .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%');
        }
        if($searchdepartment)
            $fquery .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%');
        //Pagination
        $result = array();
        $query = "SELECT COUNT(department.id)
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        //Data
        $query = "SELECT department.id,department.name,department.created,department.status, company.name as companyname
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid";
        $query .= $fquery;
        $db->setQuery($query, $limitstart, $limit);
        $departments = $db->loadObjectList();
        $lists = array();
        $lists['c_name'] = $c_name;
        $lists['searchcompany'] = $searchcompany;
        $lists['searchdepartment'] = $searchdepartment;
        if($datafor==1)
            $lists['searchstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getApprove('Select Status'), 'searchstatus', 'class="inputbox" ', 'value', 'text', $searchstatus);

        $result[0] = $departments;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeDepartment() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        global $resumedata;
        $row = $this->getTable('department');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string

        if (!empty($data['alias']))
            $departmentalias = $this->getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $departmentalias = $this->getJSModel('common')->removeSpecialCharacter($data['name']);

        $departmentalias = strtolower(str_replace(' ', '-', $departmentalias));
        $data['alias'] = $departmentalias;
        $data['description'] = $this->getJSModel('common')->getHtmlInput('description');

        if ($data['id'] == '')
            $data['created'] = date('Y-m-d H:i:s');

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
            echo $row->getError();
            return false;
        }
        if ($this->_client_auth_key != "") {
            $db = $this->getDBO();
            $query = "SELECT department.* FROM `#__js_job_departments` AS department  
						WHERE department.id = " . $row->id;

            $db->setQuery($query);
            $data_department = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_department->id = $data['id']; // for edit case
            $data_department->department_id = $row->id;
            $data_department->authkey = $this->_client_auth_key;
            $data_department->task = 'storedepartment';
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_value = $jsjobsharingobject->storeDepartmentSharing($data_department);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logStoreDepartmentSharing($return_value);
        }
        return true;
    }

    function deleteDepartment() {
        $db = Factory::getDBO();
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('department');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if (is_numeric($cid) == false)
                return false;
            $serverdepartmentid = 0;
            if ($this->_client_auth_key != "") {
                $query = "SELECT dep.serverid AS id FROM `#__js_job_departments` AS dep  
							WHERE dep.id = " . $cid;
                $db->setQuery($query);
                $s_dep_id = $db->loadResult();
                $serverdepartmentid = $s_dep_id;
            }
            if ($this->departmentCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
                if ($serverdepartmentid != 0) {
                    $data = array();
                    $data['id'] = $serverdepartmentid;
                    $data['referenceid'] = $cid;
                    $data['uid'] = $this->_uid;
                    $data['authkey'] = $this->_client_auth_key;
                    $data['siteurl'] = $this->_siteurl;
                    $data['task'] = 'deletedepartment';
                    $jsjobsharingobject = $this->getJSModel('jobsharing');
                    $return_value = $jsjobsharingobject->deleteDepartmentSharing($data);
                    $jsjobslogobject = $this->getJSModel('log');
                    $jsjobslogobject->logDeleteDepartmentSharing($return_value);
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function departmentCanDelete($departmentid) {
        if (is_numeric($departmentid) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE departmentid = " . $departmentid . ") AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getDepartment($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = Factory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_departments` WHERE uid = " . $uid . " AND status = 1  ORDER BY name ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $departments = array();
        foreach ($rows as $row) {
            $departments[] = array('value' => Text::_($row->id),
                'text' => Text::_($row->name));
        }
        return $departments;
    }

    // Get Combo End
    function listDepartments($val) {
        if (is_numeric($val) === false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT id, name FROM `#__js_job_departments` WHERE status = 1 AND companyid = " . $val . "
				ORDER BY name ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        //Checking field is required or not
        $requried = $this->getJSModel('fieldordering')->getFieldRequiredByNameAndFor('department',2);
        if (isset($result)) {
            $return_value = "<select name='departmentid' class='inputbox";
            if($requried == 1){
                $return_value .= " required";
            }
            $return_value .= "' >\n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->name</option> \n";
            }
            $return_value .= "</select>\n";
        }

        return $return_value;
    }

    function departmentApprove($departmentid) {
        if (is_numeric($departmentid) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_departments SET status = 1 WHERE id = " . $departmentid;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        if ($this->_client_auth_key != "") {
            $data_department_approve = array();
            $query = "SELECT serverid FROM #__js_job_departments WHERE id = " . $departmentid;
            $db->setQuery($query);
            $serverdepartmentid = $db->loadResult();
            $data_department_approve['id'] = $serverdepartmentid;
            $data_department_approve['department_id'] = $departmentid;
            $data_department_approve['authkey'] = $this->_client_auth_key;
            $fortask = "departmentapprove";
            $server_json_data_array = json_encode($data_department_approve);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logDepartmentApprove($return_value);
        }
        return true;
    }

    function departmentReject($departmentid) {
        if (is_numeric($departmentid) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_departments SET status = -1 WHERE id = " . $departmentid;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        if ($this->_client_auth_key != "") {
            $data_department_reject = array();
            $query = "SELECT serverid FROM #__js_job_departments WHERE id = " . $departmentid;
            $db->setQuery($query);
            $serverdepartmentid = $db->loadResult();
            $data_department_reject['id'] = $serverdepartmentid;
            $data_department_reject['department_id'] = $departmentid;
            $data_department_reject['authkey'] = $this->_client_auth_key;
            $fortask = "departmentreject";
            $server_json_data_array = json_encode($data_department_reject);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logDepartmentReject($return_value);
        }
        return true;
    }

    function deleteUserDepByCompanyId($companyid){
        if(!is_numeric($companyid) || $companyid == 0){
            return false;
        }
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $condition = array($db->quoteName('companyid') . ' = ' . $companyid);
        $query->delete('#__js_job_departments');
        $query->where($condition);
        $db->setQuery($query);
        $db->execute();
        return;
    }

}

?>
