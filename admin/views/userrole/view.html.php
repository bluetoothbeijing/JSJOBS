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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewUserrole extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'changerole') {       // users - change role
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true)
                $result = $this->getJSModel('userrole')->getChangeRolebyId($c_id);
            
            $this->role = $result[0];
            $this->lists = $result[1];
            ToolbarHelper::title(Text::_('Change Role'));
            ToolbarHelper::save('userrole.saveuserrole');
            if ($isNew)
                ToolbarHelper::cancel('userrole.cancel');
            else
                ToolbarHelper::cancel('userrole.cancel', 'Close');
        }elseif ($layoutName == 'users') {
            ToolbarHelper::title(Text::_('Users'));
            $form = 'com_jsjobs.users.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchusername = $mainframe->getUserStateFromRequest($form . 'searchusername', 'searchusername', '', 'string');
            $searchcompany = $mainframe->getUserStateFromRequest($form . 'searchcompany', 'searchcompany', '', 'string');
            $searchresume = $mainframe->getUserStateFromRequest($form . 'searchresume', 'searchresume', '', 'string');
            $searchrole = $mainframe->getUserStateFromRequest($form . 'searchrole', 'searchrole', '', 'string');
            $usergroup = $mainframe->getUserStateFromRequest($form . 'usergroup', 'usergroup', '', 'string');
            $status = $mainframe->getUserStateFromRequest($form . 'status', 'status', '', 'string');
            $datestart = $mainframe->getUserStateFromRequest($form . 'datestart', 'datestart', '', 'string');
            $dateend = $mainframe->getUserStateFromRequest($form . 'dateend', 'dateend', '', 'string');
            $result = $this->getJSModel('user')->getAllUsers($searchname, $searchusername, $searchcompany, $searchresume, $searchrole, $usergroup, $status, $datestart ,$dateend ,$limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
        }
//        layout end

        $this->config = $config;
        //$this->application = $application;
        if(isset($items)) $this->items = $items;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
