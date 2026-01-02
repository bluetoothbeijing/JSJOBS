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

class JSJobsViewJobstatus extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formjobstatus') {          // job status
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0)
                $jobstatus = $this->getJSModel('jobstatus')->getJobStatusbyId($c_id);
            if (isset($jobstatus->id))
                $isNew = false;
            if(isset($jobstatus)) $this->application = $jobstatus;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Job Status') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('jobstatus.savejobstatussave', 'Save');
            ToolbarHelper::save2new('jobstatus.savejobstatusandnew');
            ToolbarHelper::save('jobstatus.savejobstatus');
            if ($isNew)
                ToolbarHelper::cancel('jobstatus.cancel');
            else
                ToolbarHelper::cancel('jobstatus.cancel', 'Close');
        }elseif ($layoutName == 'jobstatus') {        //job status
            ToolbarHelper::title(Text::_('Job Status'));
            ToolbarHelper::addNew('jobstatus.edijobstatus');
            ToolbarHelper::editList('jobstatus.edijobstatus');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'jobstatus.remove');
            $form = 'com_jsjobs.jobstatus.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');            
            $result = $this->getJSModel('jobstatus')->getAllJobStatus($searchtitle, $searchstatus, $limitstart, $limit);
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
