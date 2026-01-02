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

class JSJobsViewSalaryrange extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formsalaryrange') {       // salary range
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0){
                $application = $this->getJSModel('salaryrange')->getSalaryRangebyId($c_id);
                if(isset($application)) $this->application = $application;
            }
            // get configurations
            $config = Array();
            $results = $this->getJSModel('configuration')->getConfig();
            if ($results) { //not empty
                foreach ($results as $result) {
                    $config[$result->configname] = $result->configvalue;
                }
            }
            $this->config = $config;

            if (isset($application->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Salary Range') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('salaryrange.savejobsalaryrangesave', 'Save');
            ToolbarHelper::save2new('salaryrange.savejobsalaryrangeandnew');
            ToolbarHelper::save('salaryrange.savejobsalaryrange');
            if ($isNew)
                ToolbarHelper::cancel('salaryrange.cancel');
            else
                ToolbarHelper::cancel('salaryrange.cancel', 'Close');
        }elseif ($layoutName == 'salaryrange') {         // salary range
            ToolbarHelper::title(Text::_('Salary Range'));
            ToolbarHelper::addNew('salaryrange.editjobsalaryrange');
            ToolbarHelper::editList('salaryrange.editjobsalaryrange');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'salaryrange.remove');
            $form = 'com_jsjobs.salaryrange.list.';
            $searchrangestart = $mainframe->getUserStateFromRequest($form . 'searchrangestart', 'searchrangestart', '', 'string');
            $searchrangeend = $mainframe->getUserStateFromRequest($form . 'searchrangeend', 'searchrangeend', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');            

            $result = $this->getJSModel('salaryrange')->getAllSalaryRange($searchrangestart, $searchrangeend, $searchstatus, $limitstart, $limit);
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
