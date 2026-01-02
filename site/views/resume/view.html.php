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
use Joomla\CMS\Router\Route;    


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewResume extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        require_once(JPATH_COMPONENT . '/models/permissions.php');
        $viewtype = 'html';

        if ($layout == 'formresume') {  // form resume
            $page_title .= ' - ' . Text::_('Resume Form');
            $resumeid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('rd', ''));
            $isadmin = 0;
            $resume_model = $this->getJSModel('resume');

            $validresume = true;
            if(is_numeric($resumeid) AND $resumeid != 0){
                $this->getJSModel('permissions');
                $canaddnewresume = VALIDATE;
                // check if own resume id
                $validresume = $resume_model->checkResumeIsValidUser($resumeid, $uid);
            }else{
                if ($uid != 0){
                    $canaddnewresume = $this->getJSModel('permissions')->checkPermissionsFor('ADD_RESUME');
                }else{ // guest
                    $canaddnewresume = $this->getJSModel('permissions')->checkPermissionsFor('ADD_RESUME');
                }
            }

            // fieldordering
            $fieldfor = 16;
            if ($uid != 0)
                $fieldfor = 3;
            $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering($fieldfor);

            $packagedetail = $resume_model->getPackageDetailByUid($uid);
            $this->resumeid = $resumeid;
            $this->fieldsordering=$fieldsordering;
            $this->resume_model=$resume_model;
            $this->canaddnewresume= $canaddnewresume;
            $this->packagedetail= $packagedetail;
            $this->isadmin= $isadmin;
            $this->validresume=$validresume;
            // captcha and nav var
            $nav = Factory::getApplication()->input->get('nav', '');
            $this->nav = $nav;
            HTMLHelper::_('behavior.formvalidator');
        } elseif ($layout == 'resumesearch') {           // resume search
            $page_title .= ' - ' . Text::_('Resume Search');
            $result = $this->getJSModel('resume')->getResumeSearchOptions();
            $this->searchoptions = isset($result[0]) ? $result[0] : '';
            $this->searchresumeconfig = isset($result[1]) ? $result[1] : '';
            $this->canview = $result[2];
        } elseif ($layout == 'myresumes') {            // my resumes
            $page_title .= ' - ' . Text::_('My Resumes');
            $myresume_allowed = $this->getJSModel('permissions')->checkPermissionsFor("MY_RESUME");
            if ($myresume_allowed == VALIDATE) {
                $sort = Factory::getApplication()->input->get('sortby', '');
                if (isset($sort)) {
                    if ($sort == '') {
                        $sort = 'createddesc';
                    }
                } else {
                    $sort = 'createddesc';
                }
                $sortby = $this->getResumeListOrdering($sort);
                $result = $this->getJSModel('resume')->getMyResumesbyUid($uid, $sortby, $limit, $limitstart);
                $this->resumes = $result[0];
                $this->resumestyle = $result[2];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
                $sortlinks = $this->getResumeListSorting($sort);
                $sortlinks['sorton'] = $sorton;
                $sortlinks['sortorder'] = $sortorder;
                $this->sortlinks = $sortlinks;
                $this->fieldsordering = $result[3];
            }
            $this->myresume_allowed = $myresume_allowed;
        } elseif ($layout == 'my_resumesearches') {            // my resume searches
            $page_title .= ' - ' . Text::_('Resume Save Searches');
            $myresumesearch_allowed = $this->getJSModel('permissions')->checkPermissionsFor("RESUME_SAVE_SEARCH");
            if ($myresumesearch_allowed == VALIDATE) {
                $result = $this->getJSModel('resume')->getMyResumeSearchesbyUid($uid, $limit, $limitstart);
                $this->jobsearches = $result[0];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
            }
            $this->myresumesearch_allowed = $myresumesearch_allowed;
        } elseif ($layout == 'resumebycategory') {      // Resume By Categories
            $result = $this->getJSModel('resume')->getResumeByCategory($uid);
            $this->categories = $result[0];
            $this->canview = $result[1];
        } elseif ($layout == 'resume_bycategory') {                // Resume By Categories

            $page_title .= ' - ' . Text::_('Resume By Categories');
            $sort = Factory::getApplication()->input->get('sortby', '');
            if (isset($sort)) {
                if ($sort == '') {
                    $sort = 'create_datedesc';
                }
            } else {
                $sort = 'create_datedesc';
            }
            $jobcategory = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('cat', ''));
            $job_subcategory = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('resumesubcat', ''));

            $sortby = $this->getResumeListOrdering($sort);

            $result = $this->getJSModel('resume')->getResumeByCategoryId($uid, $jobcategory , $job_subcategory, $sortby, $limit, $limitstart);
            $options = $this->get('Options');

            if(is_numeric($jobcategory) AND $jobcategory > 0){
                $catorsubcat = 'cat';
            }elseif(is_numeric($job_subcategory) AND $job_subcategory > 0){
                $catorsubcat = 'resumesubcat';
            }

            $this->catorsubcat = $catorsubcat;

            $sortlinks = $this->getResumeListSorting($sort);
            $sortlinks['sorton'] = $sorton;
            $sortlinks['sortorder'] = $sortorder;
            if(isset($result[1])){
                if ($result[1] <= $limitstart){
                    $limitstart = 0;
                }
            }
            if(isset($result[1])) $pagination = new Pagination($result[1], $limitstart, $limit);
            if(isset($result[1])) $this->pagination = $pagination;
            if(isset($result[0])) $this->resumes = $result[0]; else $this->resumes = null;
            if(isset($result[2])) $this->searchresumeconfig = $result[2];
            if(isset($result[3])) $this->categoryname = $result[3]; else $this->categoryname = null;
            if(isset($result[4])) $this->catid = $result[4];
            if(isset($result[5])) $this->subcategories = $result[5];
            if(isset($result[6])) $this->fieldsordering = $result[6];

            $this->sortlinks = $sortlinks;
        }elseif ($layout == 'resume_bysubcategory') {                // Resume By Categories
            $page_title .= ' - ' . Text::_('Resume By Subcategory');
            $sort = Factory::getApplication()->input->get('sortby', '');
            if (isset($sort)) {
                if ($sort == '') {
                    $sort = 'create_datedesc';
                }
            } else {
                $sort = 'create_datedesc';
            }
            $jobsubcategory = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('resumesubcat', ''));
            $sortby = $this->getResumeListOrdering($sort);

            $result = $this->getJSModel('resume')->getResumeBySubCategoryId($uid, $jobsubcategory, $sortby, $limit, $limitstart);
            $options = $this->get('Options');
            $sortlinks = $this->getResumeListSorting($sort);
            $sortlinks['sorton'] = $sorton;
            $sortlinks['sortorder'] = $sortorder;
            if ($result[1] <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($result[1], $limitstart, $limit);
            $this->pagination = $pagination;
            if (isset($result[0]))
                $this->resume = $result[0];
            if (isset($result[2]))
                $this->subcategorytitle = $result[2];
            $this->fieldsordering = $result[3];
            $this->resumesubcategory = $jobsubcategory;
            $this->sortlinks = $sortlinks;
        }elseif ($layout == 'viewresumesearch') {            // view resume seach
            $page_title .= ' - ' . Text::_('View Resume Search');
            $id = Factory::getApplication()->input->get('rs', '');
            $save_searched_fields = $this->getJSModel('resumesearch')->getResumeSearchebyId($id);


            if ($save_searched_fields->searchparams != null) {
                $search = json_decode($save_searched_fields->searchparams);
            }

            if (isset($search)) {
                $mainframe->setUserState($option . 'title', isset($search->title) ? $search->title : '' );
                $mainframe->setUserState($option . 'name', isset($search->name) ? $search->name : '' );
                $mainframe->setUserState($option . 'searchcity', isset($search->searchcity) ? $search->searchcity : '' );
                $mainframe->setUserState($option . 'zipcode', isset($search->zipcode) ? $search->zipcode : '' );
                $mainframe->setUserState($option . 'keywords', isset($search->keywords) ? $search->keywords : '' );

                $mainframe->setUserState($option . 'nationality', isset($search->nationality) ? $search->nationality : '' );
                $mainframe->setUserState($option . 'jobcategory', isset($search->jobcategory) ? $search->jobcategory : '' );
                $mainframe->setUserState($option . 'jobsubcategory', isset($search->jobsubcategory) ? $search->jobsubcategory : '' );
                $mainframe->setUserState($option . 'jobsalaryrange', isset($search->jobsalaryrange) ? $search->jobsalaryrange : '' );
                $mainframe->setUserState($option . 'jobtype', isset($search->jobtype) ? $search->jobtype : '' );
                $mainframe->setUserState($option . 'heighestfinisheducation', isset($search->heighestfinisheducation) ? $search->heighestfinisheducation : '' );
                $mainframe->setUserState($option . 'gender', isset($search->gender) ? $search->gender : '' );
                $mainframe->setUserState($option . 'iamavailable', isset($search->iamavailable) ? $search->iamavailable : '' );
                $mainframe->setUserState($option . 'currency', isset($search->currency) ? $search->currency : '' );
                $mainframe->setUserState($option . 'experiencemin', isset($search->experiencemin) ? $search->experiencemin : '' );
                $mainframe->setUserState($option . 'experiencemax', isset($search->experiencemax) ? $search->experiencemax : '' );
            }

            // custom field
            if ($save_searched_fields->params != null) {
                $params = json_decode($save_searched_fields->params);
                $data = getCustomFieldClass()->userFieldsData(3);
                foreach ($data as $uf) {
                    $fname = $uf->field;
                    $value = isset($params->$fname) ? $params->$fname : '';
                    $mainframe->setUserState( $option.$fname, $value);

                }
            }
            // custmo End
            $mainframe->redirect(Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_searchresults&Itemid=' . $itemid ,false));
        }elseif ($layout == 'resume_searchresults') {                // resume search results
            $page_title .= ' - ' . Text::_('Resume Search Result');

            $jsfrom = Factory::getApplication()->input->get('jsfrom');
            if($jsfrom == 'cpbox'){
                // empty main frame
                $mainframe->setUserState($option . 'title', '' );
                $mainframe->setUserState($option . 'name', '' );
                $mainframe->setUserState($option . 'searchcity', '' );
                $mainframe->setUserState($option . 'zipcode', '' );
                $mainframe->setUserState($option . 'keywords', '' );
                $mainframe->setUserState($option . 'nationality', '' );
                $mainframe->setUserState($option . 'jobcategory', '' );
                $mainframe->setUserState($option . 'jobsubcategory', '' );
                $mainframe->setUserState($option . 'jobsalaryrange', '' );
                $mainframe->setUserState($option . 'jobtype', '' );
                $mainframe->setUserState($option . 'heighestfinisheducation', '' );
                $mainframe->setUserState($option . 'gender', '' );
                $mainframe->setUserState($option . 'iamavailable', '' );
                $mainframe->setUserState($option . 'currency', '' );
                $mainframe->setUserState($option . 'experiencemin', '' );
                $mainframe->setUserState($option . 'experiencemax', '' );
                // custom field
                $data = getCustomFieldClass()->userFieldsData(3);
                foreach ($data as $uf) {
                    $fname = $uf->field;
                    $mainframe->setUserState( $option.$fname, '');
                }
                // custmo End

            }

            $sort = Factory::getApplication()->input->get('sortby', '');
            if (isset($sort)) {
                if ($sort == '') {
                    $sort = 'create_datedesc';
                }
            } else {
                $sort = 'create_datedesc';
            }
            $sortby = $this->getResumeListOrdering($sort);
            if ($limit != '') {
                $session->set('limit',$limit);
            } else if ($limit == '') {
                $limit = $session->get('limit');
            }

            $for_save_search = array();
            $for_save_search['title'] = $title = $mainframe->getUserStateFromRequest($option . 'title', 'title', '', 'string');
            $for_save_search['name'] = $name = $mainframe->getUserStateFromRequest($option . 'name', 'name', '', 'string');
            $for_save_search['iamavailable'] = $iamavailable = Factory::getApplication()->input->get('iamavailable');
            $for_save_search['searchcity'] = $searchcity = $mainframe->getUserStateFromRequest($option . 'searchcity', 'searchcity', '', 'string');
            $for_save_search['zipcode'] = $zipcode = $mainframe->getUserStateFromRequest($option . 'zipcode', 'zipcode', '', 'string');
            $for_save_search['keywords'] = $keywords = $mainframe->getUserStateFromRequest($option . 'keywords', 'keywords', '', 'string');

            $for_save_search['nationality'] = $nationality = $mainframe->getUserStateFromRequest($option . 'nationality', 'nationality', '', 'string');
            $for_save_search['jobcategory'] = $jobcategory = $mainframe->getUserStateFromRequest($option . 'jobcategory', 'jobcategory', '', 'string');
            $for_save_search['jobsubcategory'] = $jobsubcategory = $mainframe->getUserStateFromRequest($option . 'jobsubcategory', 'jobsubcategory', '', 'string');
            $for_save_search['jobsalaryrange'] = $jobsalaryrange = $mainframe->getUserStateFromRequest($option . 'jobsalaryrange', 'jobsalaryrange', '', 'string');
            $for_save_search['jobtype'] = $jobtype = $mainframe->getUserStateFromRequest($option . 'jobtype', 'jobtype', '', 'string');
            $for_save_search['heighestfinisheducation'] = $education = $mainframe->getUserStateFromRequest($option . 'heighestfinisheducation', 'heighestfinisheducation', '', 'string');
            $for_save_search['gender'] = $gender = $mainframe->getUserStateFromRequest($option . 'gender', 'gender', '', 'string');
            $for_save_search['currency'] = $currency = $mainframe->getUserStateFromRequest($option . 'currency', 'currency', '', 'string');
            $for_save_search['experiencemin'] = $experiencefrom = $mainframe->getUserStateFromRequest($option . 'experiencemin', 'experiencemin', '', 'string');
            $for_save_search['experiencemax'] = $experienceto = $mainframe->getUserStateFromRequest($option . 'experiencemax', 'experiencemax', '', 'string');
            $for_save_search['jobstatus'] = $jobstatus = '';
            $forsavesearch = array();
            foreach ($for_save_search as $key => $value) {
                if( ! empty($value) ){
                    $forsavesearch[$key] = $value;
                }
            }

            $result = $this->getJSModel('resume')->getResumeSearch($uid, $title, $name, $nationality, $gender, $iamavailable, $jobcategory, $jobsubcategory, $jobtype, $jobstatus, $currency, $jobsalaryrange, $education, $experiencefrom,$experienceto, $sortby, $limit, $limitstart, $zipcode, $keywords, $searchcity);
            if ($result != false) {
                $options = $this->get('Options');
                $sortlinks = $this->getResumeListSorting($sort);
                $sortlinks['sorton'] = $sorton;
                $sortlinks['sortorder'] = $sortorder;
                $forsavesearch['params'] = $result[5];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $flag = Factory::getApplication()->input->get('isresumesearch');
                $this->issearchform = $flag;
                $this->pagination = $pagination;
                $this->resumes = $result[0];
                $this->searchresumeconfig = $result[2];
                $this->canview = $result[3];
                $this->fieldsordering = $result[4];
                $this->sortlinks = $sortlinks;
                $this->forsavesearch = $forsavesearch;
                $true = true;
                $this->result = $true;
            }else {
                $this->result = $result;
            }
        } else if (($layout == 'resume_download') || ($layout == 'resume_view')) { // resume view & download
            $jinput = Factory::getApplication()->input;
            $empid = $jinput->get('rq');
            $application = $this->getJSModel('employer')->getEmpApplicationbyid($empid);
        } elseif (($layout == 'view_resume') or ( $layout == 'resume_print')) {          // view resume
            $jinput = Factory::getApplication()->input;
            if ($jinput->get('rq',''))
                $empid = $jinput->get('rq');
            else
                $empid = '';
            if ($empid != '') {
                $application = $this->getJSModel('employer')->getEmpApplicationbyid($empid);
            } else {
                $resumeid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('rd', ''));
                $myresume = Factory::getApplication()->input->get('nav', '');
                $jobid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('bd', ''));
                $folderid = Factory::getApplication()->input->get('fd', '');
                $catid = Factory::getApplication()->input->get('cat', '');
                $printresume = Factory::getApplication()->input->get('print', '');
                $resumesubcat = Factory::getApplication()->input->get('resumesubcat', '');
                if ($jobid == '0')
                    $jobid = '';
                $sortvalue = $sort = Factory::getApplication()->input->get('sortby', false);
                if ($sort != false)
                    $sort = $this->getEmpListOrdering($sort);
                $tabaction = Factory::getApplication()->input->get('ta', false);
                $show_only_section_that_have_value = $this->getJSModel('configurations')->getConfigValue('show_only_section_that_have_value');

                $result = $this->getJSModel('resume')->getResumeViewbyId($uid, $jobid, $resumeid, $myresume, $sort, $tabaction);
                $validresume = $this->getJSModel('resume')->checkResumeExists($resumeid);
                if(isset($result['personal'])){
                    $document->setMetadata('keywords', $result['personal']->keywords);
                }
                
                $this->validresume = $validresume;
                if(isset($result['personal'])) $this->resume = $result['personal'];
                if(isset($result['addresses'])) $this->addresses = $result['addresses'];
                if(isset($result['institutes'])) $this->institutes = $result['institutes'];
                if(isset($result['employers'])) $this->employers = $result['employers'];
                if(isset($result['references'])) $this->references = $result['references'];
                if(isset($result['languages'])) $this->languages = $result['languages'];
                if(isset($result['filename'])){ // only for job sharing case
                    $this->resumefiles=$result['filename'];
                }
                if(isset($result['fieldsordering'])) $this->fieldsordering = $result['fieldsordering'];
                if(isset($result['userfields'])) $this->userfields = $result['userfields'];
                if(isset($show_only_section_that_have_value)) $this->show_only_section_that_have_value = $show_only_section_that_have_value;
                if(isset($result['canview'])) $this->canview = $result['canview'];
                if(isset($result['cvids'])) $this->cvids = $result['cvids'];
                if(isset($tabaction)) $this->tabaction = $tabaction;
                if(isset($printresume)) $this->printresume = $printresume;
                $nav = Factory::getApplication()->input->get('nav', '');
                $this->nav = $nav;
                $jobaliasid = Factory::getApplication()->input->get('bd', '');
                if (!$jobid)
                    $jobid = 0;
                $this->bd = $jobid;
                $this->jobaliasid = $jobaliasid;
                $this->resumeid = $resumeid;
                $this->sortby = $sortvalue;
                $this->fd = $folderid;
                $this->ms = $myresume;
                $this->catid = $catid;
                $this->subcatid = $resumesubcat;
            }
        }
        require_once('resume_breadcrumbs.php');
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

    function getResumeListSorting($sort) {
        $sortlinks['application_title'] = $this->getSortArg("application_title", $sort);
        $sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        $sortlinks['salaryrange'] = $this->getSortArg("salaryrange", $sort);
        $sortlinks['created'] = $this->getSortArg("created", $sort);

        return $sortlinks;
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

    function getResumeListOrdering($sort) {
        global $sorton, $sortorder;
        switch ($sort) {
            case "application_titledesc": $ordering = "resume.application_title DESC";
                $sorton = "application_title";
                $sortorder = "DESC";
                break;
            case "application_titleasc": $ordering = "resume.application_title ASC";
                $sorton = "application_title";
                $sortorder = "ASC";
                break;
            case "jobtypedesc": $ordering = "resume.jobtype DESC";
                $sorton = "jobtype";
                $sortorder = "DESC";
                break;
            case "jobtypeasc": $ordering = "resume.jobtype ASC";
                $sorton = "jobtype";
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
            case "createddesc": $ordering = "resume.created DESC";
                $sorton = "created";
                $sortorder = "DESC";
                break;
            case "createdasc": $ordering = "resume.created ASC";
                $sorton = "created";
                $sortorder = "ASC";
                break;
            default: $ordering = "resume.id DESC";
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

}

?>
