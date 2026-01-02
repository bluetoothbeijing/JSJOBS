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

class JSJobsViewDepartment extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formdepartment') {
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true)
                $result = $this->getJSModel('department')->getDepartmentById($c_id, $uid);
            if (isset($result[0]->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Department') . ': <small><small>[ ' . $text . ' ]</small></small>');
            $this->department = $result[0];
            $this->lists = $result[1];
            $this->uid = $uid;
            ToolbarHelper::save('department.savedepatrment');
            if ($isNew)
                ToolbarHelper::cancel('department.cancel');
            else
                ToolbarHelper::cancel('department.cancel', 'Close');
        }elseif ($layoutName == 'departmentqueue') { 
            ToolbarHelper::title(Text::_('Department Queue'));
            $searchcompany = $mainframe->getUserStateFromRequest($option . 'searchcompany', 'searchcompany', '', 'string');
            $searchdepartment = $mainframe->getUserStateFromRequest($option . 'searchdepartment', 'searchdepartment', '', 'string');
            $result = $this->getJSModel('department')->getDepartments( 2, $searchcompany, $searchdepartment, '', $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
        }elseif ($layoutName == 'departments') { 
            ToolbarHelper::title(Text::_('Departments'));
            ToolbarHelper::addNew('department.add');
            ToolbarHelper::editList('department.edit');
            ToolbarHelper::deleteList(Text::_("Are You Sure?"), 'department.remove');
            $companyid = Factory::getApplication()->input->get('md');
            $searchcompany = $mainframe->getUserStateFromRequest($option . 'searchcompany', 'searchcompany', '', 'string');
            $searchdepartment = $mainframe->getUserStateFromRequest($option . 'searchdepartment', 'searchdepartment', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($option . 'searchstatus', 'searchstatus', '', 'string');
            $result = $this->getJSModel('department')->getDepartments( 1, $searchcompany, $searchdepartment, $searchstatus, $limitstart, $limit , $companyid);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
            $this->companyid = $companyid;
        }

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
