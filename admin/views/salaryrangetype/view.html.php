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

class JSJobsViewSalaryrangetype extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formsalaryrangetype') {          // jobtypes
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0)
                $application = $this->getJSModel('salaryrangetype')->getSalaryRangeTypebyId($c_id);
            if (isset($application->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Salary Range Types') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('salaryrangetype.savejobsalaryrangetypesave', 'Save');
            ToolbarHelper::save2new('salaryrangetype.savejobsalaryrangetypeandnew');
            ToolbarHelper::save('salaryrangetype.savejobsalaryrangetype');
            if ($isNew)
                ToolbarHelper::cancel('salaryrangetype.cancel');
            else
                ToolbarHelper::cancel('salaryrangetype.cancel', 'Close');
            if(isset($application)) $this->application = $application;
        }elseif ($layoutName == 'salaryrangetype') {        //job types
            ToolbarHelper::title(Text::_('Salary Range Type'));
            ToolbarHelper::addNew('salaryrangetype.editjobsalaryrangrtype');
            ToolbarHelper::editList('salaryrangetype.editjobsalaryrangrtype');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'salaryrangetype.remove');
            $form = 'com_jsjobs.salaryrangetype.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');            
            $result = $this->getJSModel('salaryrangetype')->getAllSalaryRangeType($searchtitle, $searchstatus, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }
//        layout end

        $this->config = $config;
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
