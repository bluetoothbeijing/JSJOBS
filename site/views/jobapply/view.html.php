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

class JSJobsViewJobApply extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'job_apply') {            // job apply
            $result = null;
            $page_title .= ' - ' . Text::_('Apply Now');
            $jobid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('bd', ''));
            if ($uid) {
                if ($config['showapplybutton'] == 2) {
                    $apply_redirect_link = $config['applybuttonredirecturl'];
                    $mainframe->redirect($apply_redirect_link);
                }
                $jobresult = $this->getJSModel('jobapply')->getJobbyIdforJobApply($jobid);
                $result = $this->getJSModel('resume')->getMyResumes($uid);
            } else {
                $session = Factory::getApplication()->getSession();
                $visitor['visitor'] = 1;
                $visitor['bd'] = $jobid;
                $session->set('jsjob_jobapply', $visitor);
                if ($config['visitor_show_login_message'] != 1) {
                    $formresumelink = Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&Itemid='.$this->Itemid ,false);
                    $mainframe->redirect($formresumelink);
                }
            }
            $bd = Factory::getApplication()->input->get('bd', '');
            $this->bd = $bd;
            if(isset($jobresult[0])) $this->job = $jobresult[0];
            if(isset($jobresult[1])) $this->listjobconfig = $jobresult[1];
            if(isset($result[0])) $this->myresumes = $result[0];
            if(isset($result[2])) $this->mycoverletters = $result[2];
            if(isset($result[1])) $this->totalresume = $result[1];
            $jobcat = Factory::getApplication()->input->get('cat', '');
            $this->jobcat = $jobcat;
            $nav = Factory::getApplication()->input->get('nav', '');
            $this->nav = $nav;
            $cd = Factory::getApplication()->input->get('cd', '');
            $this->companyid = $cd;
        } elseif ($layout == 'myappliedjobs') {           //my applied jobs
            $page_title .= ' - ' . Text::_('My Applied Jobs');
            $myappliedjobs_allowed = $this->getJSModel('permissions')->checkPermissionsFor("MY_APPLIED_JOB");
            if ($myappliedjobs_allowed == VALIDATE) {
                $sort = Factory::getApplication()->input->get('sortby', '');
                if (isset($sort)) {
                    if ($sort == '') {
                        $sort = 'createddesc';
                    }
                } else {
                    $sort = 'createddesc';
                }
                $sortby = $this->getJobListOrdering($sort);
                $result = $this->getJSModel('jobapply')->getMyAppliedJobs($uid, $sortby, $limit, $limitstart);
                $application = $result[0];
                $totalresults = $result[2];
                $sortlinks = $this->getJobListSorting($sort);
                $sortlinks['sorton'] = $sorton;
                $sortlinks['sortorder'] = $sortorder;
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->application = $application;
                $this->pagination = $pagination;
                $this->totalresults = $totalresults;
                $this->sortlinks = $sortlinks;
                $this->fieldsordering = $result[2];
            }
            $this->myappliedjobs_allowed = $myappliedjobs_allowed;
        }
        elseif ($layout == 'alljobsappliedapplications') {     // all jobs applied application
            $page_title .= ' - ' . Text::_('Applied Resume');
            $myalljobsappliedapplication_allowed = $this->getJSModel('permissions')->checkPermissionsFor("APPLIED_RESUME");
            if ($myalljobsappliedapplication_allowed == VALIDATE) {
                $sort = Factory::getApplication()->input->get('sortby', '');
                if (isset($sort)) {
                    if ($sort == '') {
                        $sort = 'createddesc';
                    }
                } else {
                    $sort = 'createddesc';
                }
                $sortby = $this->getJobListOrdering($sort);
                $result = $this->getJSModel('jobapply')->getJobsAppliedResume($uid, $sortby, $limit, $limitstart);
                $sortlinks = $this->getJobListSorting($sort);
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
                $sortlinks['sorton'] = $sorton;
                $sortlinks['sortorder'] = $sortorder;
                $this->jobs = $result[0];
                $this->sortlinks = $sortlinks;
            }
            $this->myalljobsappliedapplication_allowed = $myalljobsappliedapplication_allowed;
        }elseif ($layout == 'job_appliedapplications') {          // job applied applications
            $page_title .= ' - ' . Text::_('Job Applied Resume');
            $sort = Factory::getApplication()->input->get('sortby', '');
            if (isset($sort)) {
                if ($sort == '') {
                    $sort = 'apply_datedesc';
                }
            } else {
                $sort = 'apply_datedesc';
            }
            $sortby = $this->getEmpListOrdering($sort);
            $jobid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('bd', ''));
            $tab_action = Factory::getApplication()->input->get('ta', '');
            $job_applied_call = Factory::getApplication()->input->get('jacl', '');
            $session = Factory::getApplication()->getSession();
            $needle_array = $session->get('jsjobappliedresumefilter');

            if (empty($tab_action))
                $tab_action = 1;
            $needle_array = ($needle_array ? $needle_array : "");

            if($needle_array)
                $arr_for_filter = json_decode($needle_array, TRUE);

            $result = $this->getJSModel('jobapply')->getJobAppliedResume($needle_array, $uid, $jobid, $tab_action, $sortby, $limit, $limitstart);
            $result1 = $this->getJSModel('jobapply')->getJobAppliedResumeSearchOption($needle_array);
            $application = $result[0];
            $totalresults = $result[1];
            $jobtitle = $result[2];
            $resumeCountPerTab = $result[3];
            $sortlinks = $this->getEmpListSorting($sort);
            $sortlinks['sorton'] = $sorton;
            $sortlinks['sortorder'] = $sortorder;
            if(isset($arr_for_filter)) $this->filter_data = $arr_for_filter;
            $this->resume = $result[0];
            $this->resumeCountPerTab = $resumeCountPerTab;
            $this->jobsearches = $result[0];
            if ($result[1] <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($result[1], $limitstart, $limit);
            $this->pagination = $pagination;
            $this->sortlinks = $sortlinks;
            $this->sortby = $sort;
            $jobaliasid = Factory::getApplication()->input->get('bd', '');
            $this->jobaliasid = $jobaliasid;
            $this->jobid = $jobid;
            $this->tabaction = $tab_action;
            $this->jobtitle = $jobtitle;
            $this->job_applied_call = $job_applied_call;
            $this->searchoptions = $result1[0]; // for Advanced Search tab 
            $this->fieldsordering = $result[4]; // 
            $this->stats = $result[5]; // 
            $session->clear('jsjobappliedresumefilter');
        }
        require_once('jobapply_breadcrumbs.php');
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

    function getEmpListSorting($sort) {
        $sortlinks['name'] = $this->getSortArg("name", $sort);
        $sortlinks['category'] = $this->getSortArg("category", $sort);
        $sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        $sortlinks['jobsalaryrange'] = $this->getSortArg("jobsalaryrange", $sort);
        $sortlinks['apply_date'] = $this->getSortArg("apply_date", $sort);
        $sortlinks['email'] = $this->getSortArg("email", $sort);
        $sortlinks['gender'] = $this->getSortArg("gender", $sort);
        $sortlinks['age'] = $this->getSortArg("age", $sort);
        $sortlinks['total_experience'] = $this->getSortArg("total_experience", $sort);
        $sortlinks['available'] = $this->getSortArg("available", $sort);
        $sortlinks['education'] = $this->getSortArg("education", $sort);

        return $sortlinks;
    }

    function getJobListOrdering($sort) {
        global $sorton, $sortorder;
        switch ($sort) {
            case "titledesc": $ordering = "job.title DESC";
                $sorton = "title";
                $sortorder = "DESC";
                break;
            case "titleasc": $ordering = "job.title ASC";
                $sorton = "title";
                $sortorder = "ASC";
                break;
            case "categorydesc": $ordering = "cat.cat_title DESC";
                $sorton = "category";
                $sortorder = "DESC";
                break;
            case "categoryasc": $ordering = "cat.cat_title ASC";
                $sorton = "category";
                $sortorder = "ASC";
                break;
            case "jobtypedesc": $ordering = "job.jobtype DESC";
                $sorton = "jobtype";
                $sortorder = "DESC";
                break;
            case "jobtypeasc": $ordering = "job.jobtype ASC";
                $sorton = "jobtype";
                $sortorder = "ASC";
                break;
            case "jobstatusdesc": $ordering = "job.jobstatus DESC";
                $sorton = "jobstatus";
                $sortorder = "DESC";
                break;
            case "jobstatusasc": $ordering = "job.jobstatus ASC";
                $sorton = "jobstatus";
                $sortorder = "ASC";
                break;
            case "companydesc": $ordering = "company.name DESC";
                $sorton = "company";
                $sortorder = "DESC";
                break;
            case "companyasc": $ordering = "company.name ASC";
                $sorton = "company";
                $sortorder = "ASC";
                break;
            case "salarytodesc": $ordering = "salary.rangestart DESC";
                $sorton = "salaryrange";
                $sortorder = "DESC";
                break;
            case "salarytoasc": $ordering = "salary.rangestart ASC";
                $sorton = "salaryrange";
                $sortorder = "ASC";
                break;
            case "salaryrangedesc": $ordering = "salary.rangeend DESC";
                $sorton = "salaryrange";
                $sortorder = "DESC";
                break;
            case "salaryrangeasc": $ordering = "salary.rangestart ASC";
                $sorton = "salaryrange";
                $sortorder = "ASC";
                break;
            case "countrydesc": $ordering = "country.name DESC";
                $sorton = "country";
                $sortorder = "DESC";
                break;
            case "countryasc": $ordering = "country.name ASC";
                $sorton = "country";
                $sortorder = "ASC";
                break;
            case "createddesc": $ordering = "job.created DESC";
                $sorton = "created";
                $sortorder = "DESC";
                break;
            case "createdasc": $ordering = "job.created ASC";
                $sorton = "created";
                $sortorder = "ASC";
                break;
            case "apply_datedesc": $ordering = "apply.apply_date DESC";
                $sorton = "apply_date";
                $sortorder = "DESC";
                break;
            case "apply_dateasc": $ordering = "apply.apply_date ASC";
                $sorton = "apply_date";
                $sortorder = "ASC";
                break;
            default: $ordering = "job.id DESC";
        }
        return $ordering;
    }

    function getEmpListOrdering($sort) {
        global $sorton, $sortorder;
        switch ($sort) {
            case "namedesc": $ordering = "app.first_name DESC";
                $sorton = "name";
                $sortorder = "DESC";
                break;
            case "nameasc": $ordering = "app.first_name ASC";
                $sorton = "name";
                $sortorder = "ASC";
                break;
            case "categorydesc": $ordering = "cat.cat_title DESC";
                $sorton = "category";
                $sortorder = "DESC";
                break;
            case "categoryasc": $ordering = "cat.cat_title ASC";
                $sorton = "category";
                $sortorder = "ASC";
                break;
            case "genderdesc": $ordering = "app.gender DESC";
                $sorton = "gender";
                $sortorder = "DESC";
                break;
            case "genderasc": $ordering = "app.gender ASC";
                $sorton = "gender";
                $sortorder = "ASC";
                break;
            case "jobtypedesc": $ordering = "app.jobtype DESC";
                $sorton = "jobtype";
                $sortorder = "DESC";
                break;
            case "jobtypeasc": $ordering = "app.jobtype ASC";
                $sorton = "jobtype";
                $sortorder = "ASC";
                break;
            case "jobsalaryrangedesc": $ordering = "salary.rangestart DESC";
                $sorton = "jobsalaryrange";
                $sortorder = "DESC";
                break;
            case "jobsalaryrangeasc": $ordering = "salary.rangestart ASC";
                $sorton = "jobsalaryrange";
                $sortorder = "ASC";
                break;
            case "apply_datedesc": $ordering = "apply.apply_date DESC";
                $sorton = "apply_date";
                $sortorder = "DESC";
                break;
            case "apply_dateasc": $ordering = "apply.apply_date ASC";
                $sorton = "apply_date";
                $sortorder = "ASC";
                break;
            case "emaildesc": $ordering = "app.email_address DESC";
                $sorton = "email";
                $sortorder = "DESC";
                break;
            case "emailasc": $ordering = "app.email_address ASC";
                $sorton = "email";
                $sortorder = "ASC";
                break;
            case "total_experiencedesc": $ordering = "app.total_experience DESC";
                $sorton = "total_experience";
                $sortorder = "DESC";
                break;
            case "total_experienceasc": $ordering = "app.total_experience ASC";
                $sorton = "total_experience";
                $sortorder = "ASC";
                break;
            case "agedesc": $ordering = "job.ageto DESC";
                $sorton = "age";
                $sortorder = "DESC";
                break;
            case "ageasc": $ordering = "job.agefrom ASC";
                $sorton = "age";
                $sortorder = "ASC";
                break;
            case "availabledesc": $ordering = "app.iamavailable DESC";
                $sorton = "available";
                $sortorder = "DESC";
                break;
            case "availableasc": $ordering = "app.iamavailable ASC";
                $sorton = "available";
                $sortorder = "ASC";
                break;
            case "educationdesc": $ordering = "app.heighestfinisheducation DESC";
                $sorton = "education";
                $sortorder = "DESC";
                break;
            case "educationasc": $ordering = "app.heighestfinisheducation ASC";
                $sorton = "education";
                $sortorder = "ASC";
                break;
            default: $ordering = "job.id DESC";
        }
        return $ordering;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        return "iddesc";
    }

    function getJobListSorting($sort) {
        $sortlinks['title'] = $this->getSortArg("title", $sort);
        $sortlinks['category'] = $this->getSortArg("category", $sort);
        $sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        $sortlinks['jobstatus'] = $this->getSortArg("jobstatus", $sort);
        $sortlinks['company'] = $this->getSortArg("company", $sort);
        $sortlinks['salaryrange'] = $this->getSortArg("salaryto", $sort);
        $sortlinks['country'] = $this->getSortArg("country", $sort);
        $sortlinks['created'] = $this->getSortArg("created", $sort);
        $sortlinks['apply_date'] = $this->getSortArg("apply_date", $sort);

        return $sortlinks;
    }

}

?>
