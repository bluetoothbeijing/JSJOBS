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

class JSJobsViewJob extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        if ($layoutName == 'formjob') { // form job
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true)
                $result = $this->getJSModel('job')->getJobbyId($c_id, $uid);
            $this->job = $result[0];
            $this->lists = $result[1];
            $this->userfields = $result[2];
            $this->fieldsordering = $result[3];
            $callfrom = Factory::getApplication()->input->get('callfrom','jobs');
            $this->callfrom=$callfrom;
            if (isset($result[4]))
                $this->multiselectedit = $result[4];

            if (isset($result[0]->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Job') . ': <small><small>[ ' . $text . ' ]</small></small>');

            ToolbarHelper::save('job.savejob');
            if ($isNew)
                ToolbarHelper::cancel('job.cancel');
            else
                ToolbarHelper::cancel('job.cancel', 'Close');
        }elseif ($layoutName == 'jobqueue') { // job queue
            ToolbarHelper::title(Text::_('Jobs Approval Queue'));
            $form = 'com_jsjobs.jobqueue.list.';
            $datafor = 2;
            $companyname = $mainframe->getUserStateFromRequest($form . 'companyname', 'companyname','','string');
            $jobtitle = $mainframe->getUserStateFromRequest($form . 'jobtitle', 'jobtitle','','string');
            $jobcategory = $mainframe->getUserStateFromRequest($form . 'jobcategory', 'jobcategory','','string');
            $jobtype = $mainframe->getUserStateFromRequest($form . 'jobtype', 'jobtype','','string');
            $location = $mainframe->getUserStateFromRequest($form . 'location', 'location','','string');
            $dateto = $mainframe->getUserStateFromRequest($form . 'dateto', 'dateto','','string');
            $datefrom = $mainframe->getUserStateFromRequest($form . 'datefrom', 'datefrom','','string');
            $status = '';
            $isgfcombo = $mainframe->getUserStateFromRequest($form . 'isgfcombo', 'isgfcombo','','string');
            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');

            $result = $this->getJSModel('job')->getAllJobs($datafor, $companyname, $jobtitle, $jobcategory, $jobtype, $location, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
            $this->js_sortby = $js_sortby;
            $this->sort = $sortby;
        }elseif ($layoutName == 'jobs') { //jobs
            ToolbarHelper::title(Text::_('Jobs'));
            ToolbarHelper::addNew('job.add');
            ToolbarHelper::editList('job.edit');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'job.remove');
            ToolbarHelper::cancel('job.cancel');
            $form = 'com_jsjobs.jobs.list.';

            $datafor = 1;
            $companyname = $mainframe->getUserStateFromRequest($form . 'companyname', 'companyname','','string');
            $jobtitle = $mainframe->getUserStateFromRequest($form . 'jobtitle', 'jobtitle','','string');
            $jobcategory = $mainframe->getUserStateFromRequest($form . 'jobcategory', 'jobcategory','','string');
            $jobtype = $mainframe->getUserStateFromRequest($form . 'jobtype', 'jobtype','','string');
            $location = $mainframe->getUserStateFromRequest($form . 'location', 'location','','string');
            $dateto = $mainframe->getUserStateFromRequest($form . 'dateto', 'dateto','','string');
            $datefrom = $mainframe->getUserStateFromRequest($form . 'datefrom', 'datefrom','','string');
            $status = $mainframe->getUserStateFromRequest($form . 'status', 'status','','string');
            $isgfcombo = $mainframe->getUserStateFromRequest($form . 'isgfcombo', 'isgfcombo','','string');
            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');


            $aijobsearcch = $mainframe->getUserStateFromRequest($form . 'aijobsearcch', 'aijobsearcch','','string');

            $result = $this->getJSModel('job')->getAllJobs($datafor, $companyname, $jobtitle, $jobcategory, $jobtype, $location, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit,$aijobsearcch);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
            $this->js_sortby = $js_sortby;
            $this->sort = $sortby;
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
        function getSortArg($sort) {
        if ($sort == 'asc')
            return "desc";
        else
            return "asc";
    }    

}
?>
