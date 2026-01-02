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

class JSJobsViewJobapply extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';

        if ($layoutName == 'jobappliedresume') { //job applied resume
            ToolbarHelper::title(Text::_('Applied Resume'));
            ToolbarHelper::cancel('job.cancel');
            $jobid = Factory::getApplication()->input->get('oi');
            $tab_action = Factory::getApplication()->input->get('ta', '');
            $session = Factory::getApplication()->getSession();
            $needle_array = $session->get('jsjobappliedresumefilter');

            if (empty($tab_action))
                $tab_action = 1;
            $needle_array = ($needle_array ? $needle_array : "");

            if($needle_array)
                $arr_for_filter = json_decode($needle_array, TRUE);


            $form = 'com_jsjobs.jobappliedresume.list.';
            $result = $this->getJSModel('jobapply')->getJobAppliedResume($needle_array, $tab_action, $jobid, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->oi = $jobid;
            $this->tabaction = $tab_action;
            $this->resumeCountPerTab = isset($result[3]) ? $result[3] : ''; // for Advanced Search tab 
            $session->clear('jsjobappliedresumefilter');
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
