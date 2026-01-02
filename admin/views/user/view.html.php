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

class JSJobsViewUser extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'userstate_companies') {          // users
            ToolbarHelper::title(Text::_('User Stats Companies'));
            ToolbarHelper::cancel('user.cancel');
            $companyuid = Factory::getApplication()->input->get('md');
            $result = $this->getJSModel('user')->getUserStatsCompanies($companyuid, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->companyuid = $companyuid;
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }elseif ($layoutName == 'userstats') {          // users
            ToolbarHelper::title(Text::_('User Stats'));
            ToolbarHelper::cancel('user.cancel');
            $form = 'com_jsjobs.users.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchusername = $mainframe->getUserStateFromRequest($form . 'searchusername', 'searchusername', '', 'string');
            $result = $this->getJSModel('user')->getUserStats($searchname, $searchusername, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
        }elseif ($layoutName == 'userstate_resumes') {          // users
            ToolbarHelper::title(Text::_('User Stats Resumes'));
            ToolbarHelper::cancel('user.cancel');
            $resumeuid = Factory::getApplication()->input->get('ruid');
            $result = $this->getJSModel('resume')->getUserStatsResumes($resumeuid, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->resumeuid = $resumeuid;
        }elseif ($layoutName == 'userstate_jobs') {          // users
            ToolbarHelper::title(Text::_('User Stats Jobs'));
            ToolbarHelper::cancel('user.cancel');
            $jobuid = Factory::getApplication()->input->get('bd');
            $result = $this->getJSModel('user')->getUserStatsJobs($jobuid, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->jobuid = $jobuid;
        }elseif ($layoutName == 'users') {          // users
            ToolbarHelper::title(Text::_('Users'));
            ToolbarHelper::editList('user.edit');
            $form = 'com_jstickets.users.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchusername = $mainframe->getUserStateFromRequest($form . 'searchusername', 'searchusername', '', 'string');
            $searchrole = $mainframe->getUserStateFromRequest($form . 'searchrole', 'searchrole', '', 'string');
            $result = $this->getJSModel('user')->getAllUserspopup($searchname, $searchusername, '', '', $searchrole, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->lists = $lists;
            $this->pagination = $pagination;
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
