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

class JSJobsModelUserrole extends JSModel {

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

    function getRolebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT * FROM #__js_job_roles WHERE id = " . $c_id;

        $db->setQuery($query);
        $role = $db->loadObject();
        $for = array(
            '0' => array('value' => 1, 'text' => Text::_('Employer')),
            '1' => array('value' => 2, 'text' => Text::_('Job Seeker')),);

        if (isset($role)) {
            $lists['rolefor'] = HTMLHelper::_('select.genericList', $for, 'rolefor', 'class="inputbox required" ' . '', 'value', 'text', $role->rolefor);
        } else {
            $lists['rolefor'] = HTMLHelper::_('select.genericList', $for, 'rolefor', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $role;
        $result[1] = $lists;
        return $result;
    }

    function getChangeRolebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = 'SELECT a.id,a.block,a.name,a.username, g.title AS groupname, usr.id AS userroleid, usr.role, 
                            role.title AS roletitle,usr.dated AS dated'
                . ' FROM #__users AS a'
                . ' INNER JOIN #__user_usergroup_map AS aro ON aro.user_id = a.id'
                . ' INNER JOIN #__usergroups AS g ON g.id = aro.group_id'
                . ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
                . ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role'
                . ' WHERE a.id = ' . $c_id;
        $db->setQuery($query);
        $user = $db->loadObject();
        $roles = $this->getRoles('');
        if (isset($user)) {
            $lists['roles'] = HTMLHelper::_('select.genericList', $roles, 'role', 'class="inputbox required" ' . '', 'value', 'text', $user->role);
        } else {
            $lists['roles'] = HTMLHelper::_('select.genericList', $roles, 'role', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $user;
        $result[1] = $lists;
        return $result;
    }

    function getAllRoles($limitstart, $limit) {
        $db = Factory::getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_roles";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM #__js_job_roles ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function storeRole() {
        $row = $this->getTable('role');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
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

        return true;
    }

    function storeUserRole() {
        $row = $this->getTable('userrole');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
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

        return true;
    }

    function deleteRole() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('role');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->roleCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function roleCanDelete($roleid) {
        if (is_numeric($roleid) === false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT COUNT(userrole.id) FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = " . $roleid;

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function getRoles($rolefor) {
        if($rolefor)
            if(!is_numeric($rolefor)) return false;
        
        $db = Factory::getDBO();

        if ($rolefor != "")
            $query = "SELECT id, title FROM `#__js_job_roles` WHERE rolefor = " . $rolefor . " AND published = 1 ORDER BY id ASC ";
        else
            $query = "SELECT id, title FROM `#__js_job_roles` WHERE published = 1 ORDER BY id ASC ";

        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $roles = array();
        foreach ($rows as $row) {
            $roles[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $roles;
    }

    function listUserDataForPackage($val) {
        if (!is_numeric($val))
            return false;
        $db = $this->getDBO();

        $query = "SELECT userrole.role FROM `#__js_job_userroles` AS userrole WHERE userrole.uid = " . $val;
        $db->setQuery($query);
        $userrole = $db->loadResult();
        if (!$userrole)
            return false;
        if ($userrole == 1)
            $tablename = '#__js_job_employerpackages';
        elseif ($userrole == 2)
            $tablename = '#__js_job_jobseekerpackages';
        $query = "SELECT package.id,package.title FROM `" . $tablename . "` AS package";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (isset($result)) {
            $return_value = "<select name='packageid' class='inputbox' >\n";
            $return_value .= "<option value='' >" . Text::_('Packages') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->title</option> \n";
            }
            $return_value .= "</select>\n";
        }
        $return['list'] = $return_value;
        $return['userrole'] = $userrole;
        return json_encode($return);
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
