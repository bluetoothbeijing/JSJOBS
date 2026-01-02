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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Router\Route;    


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewJobSearch extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'my_jobsearches') {            // my job searches
            $page_title .= ' - ' . Text::_('Job Save Searches');
            $myjobsavesearch_allowed = $this->getJSModel('permissions')->checkPermissionsFor("JOB_SAVE_SEARCH");
            if ($myjobsavesearch_allowed == VALIDATE) {
                $result = $this->getJSModel('jobsearch')->getMyJobSearchesbyUid($uid, $limit, $limitstart);
                $this->jobsearches = $result[0];
                if (isset($result[1]))
                    $total = $result[1];
                else
                    $total = 0;
                if ($total <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($total, $limitstart, $limit);
                $this->pagination = $pagination;
            }
            $this->myjobsavesearch_allowed = $myjobsavesearch_allowed;
        }elseif ($layout == 'viewjobsearch') {            // view job seach
            $page_title .= ' - ' . Text::_('View Job Searches');
            $id = Factory::getApplication()->input->get('js', '');
            $search = $this->getJSModel('jobsearch')->getJobSearchebyId($id);
            $option = 'com_jsjobs';
            if (isset($search)) {
                $mainframe->setUserState($option . 'title', $search->jobtitle);
                if ($search->category != 0)
                    $mainframe->setUserState($option . 'jobcategory', $search->category);
                else
                    $mainframe->setUserState($option . 'jobcategory', '');
                if ($search->jobtype != 0)
                    $mainframe->setUserState($option . 'jobtype', $search->jobtype);
                else
                    $mainframe->setUserState($option . 'jobtype', '');
                if ($search->jobstatus != 0)
                    $mainframe->setUserState($option . 'jobstatus', $search->jobstatus);
                else
                    $mainframe->setUserState($option . 'jobstatus', '');
                if ($search->salaryrange != 0)
                    $mainframe->setUserState($option . 'salaryrangefrom', $search->salaryrange);
                else
                    $mainframe->setUserState($option . 'salaryrangefrom', '');

                if ($search->heighesteducation != 0)
                    $mainframe->setUserState($option . 'heighestfinisheducation', $search->heighesteducation);
                else
                    $mainframe->setUserState($option . 'heighestfinisheducation', '');
                if ($search->shift != 0)
                    $mainframe->setUserState($option . 'shift', $search->shift);
                else
                    $mainframe->setUserState($option . 'shift', '');
                $mainframe->setUserState($option . 'education', '');
                $mainframe->setUserState($option . 'jobsubcategory', '');
                $mainframe->setUserState($option . 'experience', $search->experience);
                $mainframe->setUserState($option . 'durration', $search->durration);
                if ($search->startpublishing != '0000-00-00 00:00:00')
                    $mainframe->setUserState($option . 'startpublishing', $search->startpublishing);
                else
                    $mainframe->setUserState($option . 'startpublishing', '');
                if ($search->stoppublishing != '0000-00-00 00:00:00')
                    $mainframe->setUserState($option . 'stoppublishing', $search->stoppublishing);
                else
                    $mainframe->setUserState($option . 'stoppublishing', '');
                if ($search->company != 0)
                    $mainframe->setUserState($option . 'company', $search->company);
                else
                    $mainframe->setUserState($option . 'company', '');
                $mainframe->setUserState($option . 'country', $search->country);
                $mainframe->setUserState($option . 'state', $search->state);
                $mainframe->setUserState($option . 'county', $search->county);
                $mainframe->setUserState($option . 'city', $search->city);
                $mainframe->setUserState($option . 'zipcode', $search->zipcode);
                $mainframe->setUserState($option . 'currency', '');
                $mainframe->setUserState($option . 'longitude', '');
                $mainframe->setUserState($option . 'latitude', '');
                $mainframe->setUserState($option . 'radius', '');
                $mainframe->setUserState($option . 'radius_length_type', '');
                $mainframe->setUserState($option . 'keywords', '');
            }
            $mainframe->redirect(Route::_('index.php?option=com_jsjobs&c=job&view=job&layout=job_searchresults&Itemid=' . $itemid ,false));
        }
        require_once('jobsearch_breadcrumbs.php');

        $document->setTitle($page_title);
        $this->userrole = $userrole;
        $this->config = $config;
        $this->option = $option;
        $this->params = $params;
        $this->viewtype = $viewtype;
        $this->employerlinks = $employerlinks;
        $this->jobseekerlinks = $jobseekerlinks;
        $this->uid = $uid;
        //$this->id = $id;
        $this->Itemid = $itemid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
