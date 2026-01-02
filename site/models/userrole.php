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
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelUserRole extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_config = null;

    function __construct() {
        parent::__construct();
        $this->_config = $this->getJSModel('configurations')->getConfig('');
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getUserType($u_id) {
        $db = $this->getDBO();
        if (is_numeric($u_id) == false)
            return false;
        $query = "SELECT userrole.*, role.rolefor 
                    FROM `#__js_job_userroles` AS userrole
                    JOIN `#__js_job_roles` AS role ON userrole.role = role.id
                    WHERE  uid  = " . $u_id;
        $db->setQuery($query);
        $result[0] = $db->loadObject();

        $showemployerlink = $this->getJSModel('configurations')->getConfigValue('showemployerlink');

        if($showemployerlink){
            $usertype = array('0' => array('value' => 1, 'text' => Text::_('Employer')),
                              '1' => array('value' => 2, 'text' => Text::_('Job Seeker')),);
        }else{
            $usertype = array('0' => array('value' => 2, 'text' => Text::_('Job Seeker')),);
        }
        if (isset($result[0]))
            $lists['usertype'] = HTMLHelper::_('select.genericList', $usertype, 'usertype', 'class="inputbox" ' . '', 'value', 'text', $result[0]->rolefor);
        else
            $lists['usertype'] = HTMLHelper::_('select.genericList', $usertype, 'usertype', 'class="inputbox" ' . '', 'value', 'text', 1);
        $result[1] = $lists;

        return $result;
    }

    function addUser($usertype, $uid) { // call from jsjobs_register plugin
        if($usertype)
            if(!is_numeric($usertype)) return false;
        if($uid)
            if(!is_numeric($uid)) return false;

        $db = Factory::getDBO();
        $roleconfig = $this->getJSModel('configurations')->getConfigByFor('default');
        if ($this->getJSModel('employer')->userCanRegisterAsEmployer() != true)
            $usertype = 2; // enforce job seeker
        $created = date('Y-m-d H:i:s');
        $query = "INSERT INTO #__js_job_userroles (uid,role,dated) VALUES (" . $uid . ", " . $usertype . ", '" . $created . "')";
        $db->setQuery($query);
        // $db->execute();
        if($db->execute()){
           $return = 1; 
        }else{
            $return = 0;
        }

        $result = $this->getJSModel('purchasehistory')->assignDefaultPackage($usertype, $uid);
        $result1 = $this->assignDefaultGroup($usertype, $uid);
        return $return;
    }

    function assignDefaultGroup($usertype, $uid) {
        if (is_numeric($uid) == false)
            return false;
        $db = $this->getDBO();
        if (!$this->_config)
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'jobseeker_defaultgroup')
                $jobseeker_defaultgroup = $conf->configvalue;
            if ($conf->configname == 'employer_defaultgroup')
                $employer_defaultgroup = $conf->configvalue;
        }
        if ($usertype == 1) { //employer
            if ($employer_defaultgroup) {
                $alreadyassign = $this->getJSModel('common')->checkAssignGroup($uid, $employer_defaultgroup);
                if ($alreadyassign == false) {
                    $query = "INSERT INTO `#__user_usergroup_map` (user_id,group_id) VALUES (" . $uid . ", " . $employer_defaultgroup . ")";
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        } else { // job seeker
            if ($jobseeker_defaultgroup) {
                $alreadyassign = $this->getJSModel('common')->checkAssignGroup($uid, $jobseeker_defaultgroup);
                if ($alreadyassign == false) {
                    $query = "INSERT INTO `#__user_usergroup_map` (user_id,group_id) VALUES (" . $uid . ", " . $jobseeker_defaultgroup . ")";
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
        return true;
    }

    function storeNewinJSJobs() {
        Factory::getSession()->checkToken() or die( 'Invalid Token' );
        global $resumedata;
        $row = $this->getTable('userrole');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $data['dated'] = date('Y-m-d H:i:s');
        $uid = $data['uid'];
        $usertype = $data['usertype'];
        if ($this->getJSModel('employer')->userCanRegisterAsEmployer() != true)
            $usertype = 2; // enforce job seeker

        if ($data['id'])
            return false; // can not edit
        $data['role'] = $usertype;
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            echo $row->getError();
            return 2;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        $result = $this->getJSModel('purchasehistory')->assignDefaultPackage($usertype, $uid);
        $result1 = $this->assignDefaultGroup($usertype, $uid);
        return true;
    }

    function getUserRole($u_id) {
        $false = false;
        $role = false;
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return $false;
        $db = $this->getDBO();
        if ($u_id != 0) {
            $query = "SELECT userrole.*, role.* 
					FROM `#__js_job_userroles` AS userrole
					JOIN  `#__js_job_roles` AS role ON	userrole.role = role.id
					WHERE userrole.uid  = " . $u_id;
            $db->setQuery($query);
            $role = $db->loadObject();
        }
        return $role;
    }

    function getUserRoleByUid($uid) {
        $role = null;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return $role;
        $db = $this->getDbo();
        $query = "SELECT userroles.role
                    FROM `#__js_job_userroles` AS userroles
                    WHERE userroles.uid = " . $uid;
        $db->setQuery($query);
        $role = $db->loadResult();
        return $role;
    }

}
?>
    
