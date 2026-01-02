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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewJob extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';
		$var = $this->getJSModel('permissions')->checkPermissionsFor("MY_JOB");
        if ($layout == 'formjob') {            // form job
            $page_title .= ' - ' . Text::_('Job Information');
            $jobid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('bd', ''));
            $user = Factory::getUser();
            $result = $this->getJSModel('job')->getJobforForm($jobid, $uid, '', $user->guest);
            if (is_array($result)) {
                if(is_numeric($jobid) && $jobid != 0 && (!isset($result[0]) || $result[0]->uid == null || $result[0]->uid != $uid) ){
                    $validate = VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA;
                    $this->canaddnewjob = $validate;
                }else{
                    $this->job = $result[0];
                    $this->lists = $result[1];
                    if(isset($result[2])) $this->userfields = $result[2]; else $this->userfields = array();
                    $this->fieldsordering = $result[3];
                    $this->canaddnewjob = $result[4];
                    if(isset($result[5])) $this->packagedetail = $result[5]; else $this->packagedetail = array();
                    if(isset($result[6])) $this->packagecombo = $result[6]; else $this->packagecombo = array();
                    $this->isuserhascompany = $result[7];
                    if (isset($result[8]))
                        $this->multiselectedit = $result[8];
                    HTMLHelper::_('behavior.formvalidator');
                }
            }elseif ($result == 3) {
                $validate = $this->getJSModel('permissions')->checkPermissionsFor("ADD_JOB"); // can add
                $this->canaddnewjob = $validate;
                $this->isuserhascompany = $result;
            }
        } elseif ($layout == 'formjob_visitor') {
            $jinput = Factory::getApplication()->input;
            if ($jinput->get('email' , '') != '')
                $companyemail = $jinput->getString('email');
            $companyemail = Factory::getApplication()->input->getString('email', '');
            if (!isset($companyemail))
                $companyemail = '';

            $vis_jobid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('bd', ''));
            if (!isset($vis_jobid))
                $vis_jobid = '';
            $result = $this->getJSModel('company')->getCompanybyIdforForm('', $uid, 1, $companyemail, $vis_jobid);
            $this->company = $result[0];
            $this->companylists = $result[1];
            if(isset($result[2])) $this->companyuserfields = $result[2];
            $this->companyfieldsordering = $result[3];
            $this->canaddnewcompany = $result[4];
            $this->companypackagedetail = $result[5];
            if (isset($result[6]))
                $this->vmultiselecteditcompany = $result[6];
            $result = $this->getJSModel('job')->getJobforForm('', $uid, $vis_jobid, 1);
            $this->job = $result[0];
            $this->lists = $result[1];
            if(isset($result[2])) $this->userfields = $result[2];
            $this->fieldsordering = $result[3];
            $this->canaddnewjob = $result[4];
            $this->packagedetail = $result[5];
            $this->packagedetail = $result[5];
            if (isset($result[8]))
                $this->vmultiselecteditjob = $result[8];
            HTMLHelper::_('behavior.formvalidator');
            $result1 = $this->getJSModel('common')->getCaptchaForForm();
            $this->captcha = $result1;
        }elseif ($layout == 'myjobs') {        // my jobs
            $page_title .= ' - ' . Text::_('My Jobs');
            $myjobs_allowed = $this->getJSModel('permissions')->checkPermissionsFor("MY_JOB");
            if ($myjobs_allowed == VALIDATE) {
                $sort = Factory::getApplication()->input->get('sortby', '');
                //visitor jobid
                $vis_email = Factory::getApplication()->input->getString('email', '');
				$jobid = Factory::getApplication()->input->get('bd', '');
                if (isset($sort)) {
                    if ($sort == '')
                        $sort = 'createddesc';
                } else {
                    $sort = 'createddesc';
                }
                $sortby = $this->getJobListOrdering($sort);
                $result = $this->getJSModel('job')->getMyJobs($uid, $sortby, $limit, $limitstart, $vis_email, $jobid);
                $jobconfig = $this->getJSModel('configurations')->getConfigByFor('job');

                $sortlinks = $this->getJobListSorting($sort);
                $sortlinks['sorton'] = $sorton;
                $sortlinks['sortorder'] = $sortorder;
                $this->jobconfig = $jobconfig;
                if(isset($result[0])) $this->jobs = $result[0];
                if(isset($result[2])) $this->fieldsordering = $result[2];
                if (isset($result[1])) {
                    if ($result[1] <= $limitstart)
                        $limitstart = 0;
                    $pagination = new Pagination($result[1], $limitstart, $limit);
                    $this->pagination = $pagination;
                }
                $this->sortlinks = $sortlinks;
            }
            $this->myjobs_allowed = $myjobs_allowed;
        }elseif ($layout == 'view_job') { // view job
            $jobid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('bd', ''));
            $result = $this->getJSModel('job')->getJobbyId($jobid);
            $job = $result[0];
            $job_title = isset($job->title) ? $job->title : '';
            $job_description = isset($job->description) ? $job->description : '';
            $document->setMetaData('title', $job_title, true);
            $document->setDescription($job_description);
            $this->job = $result[0];
            $this->userfields = $result[2];
            $this->fieldsordering = $result[3];
            $this->fieldsorderingcompany = $result[4];
            $this->listjobconfig = $result[5];
            $nav = Factory::getApplication()->input->get('nav', '');
            $this->nav = $nav;
            $catid = Factory::getApplication()->input->get('cat', '');
            $this->catid = $catid;
            $jobsubcatid = Factory::getApplication()->input->get('jobsubcat', '');
            $this->jobsubcatid = $jobsubcatid;
            if (isset($job)) {
                $page_title .= ' - ' . $job->title;
                $document->setDescription($job->metadescription);
                $document->setMetadata('keywords', $job->metakeywords);
                $document->addCustomTag('<meta property="og:title" content="'.$job->companyname.'"/>');
                $phpfunctionobj = getJSJobsPHPFunctionsClass();
                $jobdescription = $phpfunctionobj->sanitizeData($job->description);
				$document->addCustomTag('<meta property="og:description" content="'.$jobdescription.'"/>');
                
                $logourl = $this->getJSModel('common')->getCompanyLogo($job->companyid, $job->companylogo );
                $document->addCustomTag('<meta property="og:image" content="'.$logourl.'"/>');
            }
        } elseif ($layout == 'jobsearch') { // job search 
            $page_title .= ' - ' . Text::_('Search Job');
            $myjobsearch_allowed = $this->getJSModel('permissions')->checkPermissionsFor("JOB_SEARCH");
            if ($myjobsearch_allowed == VALIDATE) {
                $result = $this->getJSModel('jobsearch')->getSearchOptions($uid);
                $this->searchoptions = $result[0];
                $this->searchjobconfig = $result[1];
                if(isset($result[2])) $this->canview = $result[2];
            }
            $this->myjobsearch_allowed = $myjobsearch_allowed;
        } elseif ($layout == 'jobs') {
            // we have to reset old states on every call 
            $mainframe = Factory::getApplication();
            $option = 'com_jsjobs';


            $rest_all = 0;
            $id_exsists = Factory::getApplication()->input->get('cat',null);
            if($id_exsists){
                $rest_all = 1;
            }
            $id_exsists = Factory::getApplication()->input->get('jobsubcat',null);
            if($id_exsists){
                $rest_all = 1;
            }
            $id_exsists = Factory::getApplication()->input->get('jt',null);
            if($id_exsists){
                $rest_all = 1;
            }
            $id_exsists = Factory::getApplication()->input->get('cd',null);
            if($id_exsists){
                $rest_all = 1;
            }

            $jsfrom = Factory::getApplication()->input->get('jsfrom');
            if($jsfrom == 'reset-user-state' || $rest_all == 1){
                // we have to reset old states on every call
                $mainframe = Factory::getApplication();
                $option = 'com_jsjobs';
                $mainframe->setUserState($option.'company',array());
                $mainframe->setUserState($option.'category',array());
                $mainframe->setUserState($option.'jobsubcategory',array());
                $mainframe->setUserState($option.'careerlevel',array());
                $mainframe->setUserState($option.'shift',array());
                $mainframe->setUserState($option.'jobtype',array());
                $mainframe->setUserState($option.'jobstatus',array());
                $mainframe->setUserState($option.'workpermit',array());
                $mainframe->setUserState($option.'education',array());

                $mainframe->setUserState($option . 'metakeywords','');
                $mainframe->setUserState($option . 'jobtitle','');
                $mainframe->setUserState($option . 'gender','');
                $mainframe->setUserState($option . 'agestart','');
                $mainframe->setUserState($option . 'ageend','');
                $mainframe->setUserState($option . 'currencyid','');
                $mainframe->setUserState($option . 'srangestart','');
                $mainframe->setUserState($option . 'srangeend','');
                $mainframe->setUserState($option . 'srangetype','');
                $mainframe->setUserState($option . 'experiencemin','');
                $mainframe->setUserState($option . 'experiencemax','');
                $mainframe->setUserState($option . 'city','');
                $mainframe->setUserState($option . 'state','');
                $mainframe->setUserState($option . 'requiredtravel','');
                $mainframe->setUserState($option . 'duration','');
                $mainframe->setUserState($option . 'zipcode','');
                $mainframe->setUserState($option . 'startpublishing','');
                $mainframe->setUserState($option . 'stoppublishing','');
                $mainframe->setUserState($option . 'longitude','');
                $mainframe->setUserState($option . 'latitude','');
                $mainframe->setUserState($option . 'radius','');
                $mainframe->setUserState($option . 'radiuslengthtype','');

                // ai
                $mainframe->setUserState($option . 'aijobsearcch','');
                $mainframe->setUserState($option . 'resumeid','');

                // main links
                $mainframe->setUserState($option . 'cat','');
                $mainframe->setUserState($option . 'jobsubcat','');
                $mainframe->setUserState($option . 'jt','');
                $mainframe->setUserState($option . 'cd','');

                // custmo field
                $data = getCustomFieldClass()->userFieldsData(2);
                foreach ($data as $uf) {
                    switch ($uf->userfieldtype) {
                        case 'multiple':
                        case 'checkbox':
                            $mainframe->setUserState( $option.$uf->field, array());
                            break;
                        default:
                            $mainframe->setUserState( $option.$uf->field, '');
                        break;
                    }
                }
            }

            // custmo End

            $page_title .= ' - ' . Text::_('Newest Jobs');
            $result = $this->getJSModel('job')->getJobs();
            $issearchform = Factory::getApplication()->input->get('issearchform',null,'post');
            if($issearchform !== null){
                $this->issearchform = $issearchform;
            }
        
            $search = Factory::getApplication()->input->get('search',null,'get');
            if($search != null){
                $search = 1;
                $this->isfromsavesearch = $search;
            }

            
            
            $this->fieldsordering = $result[2]; // forsearch form
            
            $this->fieldsforview = $result[6]; // for listview
         
            $this->jobs_filters = $result[3];  // filtered vars
            
            $this->search_combo = $result[4];  // combo boxes
            $this->multicities = $result[5];  // multicities

            $searchjobconfig = $this->getJSModel('configurations')->getConfigByFor('searchjob');

            $this->searchjobconfig=$searchjobconfig;

            $jobs = $result[0];
            if(isset($jobs))$this->jobs = $jobs;
            if ($result[1] <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($result[1], $limitstart, $limit);
            $this->pagination = $pagination;

            $showNumOfJobs = $this->getJSModel('configurations')->getConfigValue('showtotalnjobs');
            if($showNumOfJobs){
                $this->totalnumofjobs = $result[1];
            }

        }elseif ($layout == 'listjobtypes') {
            $page_title .= ' - ' . Text::_('Job By Types');
            $result = $this->getJSModel('job')->getJobTypes();
            $this->jobtypes = $result;
        }
        require_once('job_breadcrumbs.php');
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
            case "salarytoasc": $ordering = "job.salaryrangefrom ASC";
                $sorton = "salaryrange";
                $sortorder = "ASC";
                break;
            case "salarytodesc": $ordering = "job.salaryrangefrom DESC";
                $sorton = "salaryrange";
                $sortorder = "DESC";
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

}

?>
