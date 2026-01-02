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

class JSJobsViewJobtype extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formjobtype') {          // jobtypes
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];


            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0){
                $application = $this->getJSModel('jobtype')->getJobTypebyId($c_id);
            }
            if (isset($application->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Job Type') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('jobtype.savejobtypesave', 'Save');
            ToolbarHelper::save2new('jobtype.savejobtypeandnew');
            ToolbarHelper::save('jobtype.savejobtype');
            if ($isNew)
                ToolbarHelper::cancel('jobtype.cancel');
            else
                ToolbarHelper::cancel('jobtype.cancel', 'Close');
	        if(isset($application)) $this->application = $application;
        }elseif ($layoutName == 'jobtypes') {        //job types
            ToolbarHelper::title(Text::_('Job Types'));
            ToolbarHelper::addNew('jobtype.editjobtype');
            ToolbarHelper::editList('jobtype.editjobtype');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'jobtype.remove');
            $form = 'com_jsjobs.jobtype.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');
            $result = $this->getJSModel('jobtype')->getAllJobTypes($searchtitle, $searchstatus, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }
        //layout end

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
