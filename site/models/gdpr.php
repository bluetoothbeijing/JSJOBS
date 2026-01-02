<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:    www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Feb 24, 2020
  ^
  + Project:    JS Jobs
  ^
 */

defined('_JEXEC') or die('Not Allowed');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;


jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelGdpr extends JSModel {

    function __construct() {
        parent::__construct();
    }

	function getUserEraseDataRequest($uid){
        if (!is_numeric($uid))
            return false;
        if($uid == 0){
            return;
        }
        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__js_job_erasedatarequests` WHERE uid = $uid";
        $db->setQuery($query);
        $gdprfields = $db->loadObject();
        return $gdprfields;
    }

    function storeUserEraseRequest($data){
        $user = Factory::getUser();
        $uid = $user->id;
    	if (!$data['id']) { //new
            $data['created'] = date('Y-m-d H:i:s');
            $data['uid'] = $uid;
            $data['status'] = 1;
            $call = 1;
    	}
        $data['jobrole'] = $this->getJSModel('userrole')->getUserRoleByUid($uid);
    	$data = getJSJobsPHPFunctionsClass()->sanitizeData($data);
        $data['message'] = $this->getJSModel('common')->getHtmlInput('message');
    	$row = $this->getTable('erasedatarequests');
    	$data = $this->stripslashesFull($data);// remove slashes with quotes.
    	$error = 0;
        $return_value = true;
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            $return_value = false;
        }
        if(!$data['id'])
        if (!$row->check()) {
            $this->setError($row->getError());
            return MESSAGE_EMPTY;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($row->getError());
            $return_value = false;
        }


    	if ($return_value) {
            if(isset($call)){
                $return_option = $this->getJSModel('emailtemplate')->getEmailOption('receive_erase_data_request' , 'admin');
                if($return_option == 1){
                    $this->getJSModel('adminemail')->sendMailtoAdmin($row->id, $data['uid'], 8);
                }
            }
    	    return SAVED;
    	} else {
	       return SAVE_ERROR;
    	}
        return;
    }

    function deleteUserEraseRequest($id){
        if(!is_numeric($id)){
            return false;
        }
        if($this->checkCanDelete($id)){
            $row = $this->getTable('erasedatarequests');
            if ($row->delete($id)) {
                return DELETED;
            } else {
                $this->getJSModel('systemerrors')->updateSystemErrors($db->getErrorMsg());
                $this->setError($db->getErrorMsg());
                return DELETE_ERROR;
            }
        }
        return PERMISSION_ERROR;
    }

    function checkCanDelete($id){
        if (!is_numeric($id))
            return false;
        // if(current_user_can('manage_options')){ // allow admin to delete ??
        //     return true;
        // }
        $db = Factory::getDbo();
        $user = Factory::getUser();
        $uid = $user->id;
        $query = "SELECT uid FROM `#__js_job_erasedatarequests` WHERE id = $id";
        $db->setQuery($query);
        $db_uid = $db->loadResult();
        if( $db_uid == $uid){
            return true;
        }else{
            return false;
        }
    }

    function setUserExportByuid($uid = 0){
        
        $roleid = $this->getJSModel('userrole')->getUserRoleByUid($uid);
        if($roleid == 1){ // For employer
        	$result = $this->getEmployerDetailReportByUid($uid);
            $fromdate = date('Y-m-d',strtotime($result['curdate']));
            $fromdate = date('Y-m-d',strtotime($result['curdate']));
            $todate = date('Y-m-d',strtotime($result['fromdate']));
            if(!empty($result)){
                $data = $this->getExportDataForEmployer($fromdate,$todate,$result);
            }
        }else{ // For jobseeker
        	$result = $this->getJobseekerDetailReportByUid($uid);
            $fromdate = date('Y-m-d',strtotime($result['curdate']));
            $fromdate = date('Y-m-d',strtotime($result['curdate']));
            $todate = date('Y-m-d',strtotime($result['fromdate']));
            if(!empty($result)){
                $data = $this->getExportDataForJobseeker($fromdate,$todate,$result);
            }
        }
        if(empty($result))
            return '';
        return $data;
    }

    function stripslashesFull($input){// testing this function/.
        if (is_array($input)) {
            $input = array_map(array($this,'stripslashesFull'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = stripslashes($input);
        }
        return $input;
    }

    private function getEmployerDetailReportByUid($uid){
		$db = Factory::getDbo();
        $curdate = Factory::getApplication()->input->get('date_start', 'get');
        $fromdate = Factory::getApplication()->input->get('date_end', 'get');
        if($uid == 0 || $uid == ''){
            $id = Factory::getApplication()->input->get('uid', 'get');
        }else{
            $id = $uid;
            if (!is_numeric($id))
                return false;
            $query = "SELECT created FROM `#__js_job_jobs` WHERE uid = ".$id ." ORDER BY created ASC LIMIT 1";
            $db->setQuery($query);
            $curdate = $db->loadResult();
            $fromdate = date('Y-m-d H:i:s');
        }
        if( empty($curdate) OR empty($fromdate))
            return null;
        if(! is_numeric($id))
            return null;

        $result['curdate'] = $curdate;
        $result['fromdate'] = $fromdate;
        $result['id'] = $id;

        //Query to get Data
        
        // unpublish companies
        $query = "SELECT created FROM `#__js_job_companies` WHERE status = 0 AND created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['unpublishcompanies'] = $db->loadObjectList();
        
        //publish companies
        $query = "SELECT created FROM `#__js_job_companies` WHERE status = 1 AND created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['publishcompanies'] = $db->loadObjectList();

		//publish job
        $query = "SELECT created FROM `#__js_job_jobs` WHERE status = 1 AND created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['publishjobs'] = $db->loadObjectList();

        // unpublish job
        $query = "SELECT created FROM `#__js_job_companies` WHERE status = 0 AND created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['unpublishjobs'] = $db->loadObjectList();

        // publish departments
        $query = "SELECT created FROM `#__js_job_departments` WHERE created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['departments'] = $db->loadObjectList();
        	
    	//user detail
        $query = "SELECT user.name as display_name,user.email AS user_email,user.username,user.id,
                    (SELECT COUNT(created) FROM `#__js_job_companies` WHERE created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate) . ") AS totalcompanies,
                    (SELECT COUNT(created) FROM `#__js_job_jobs` WHERE created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate) . ") AS totaljobs,
                    (SELECT COUNT(created) FROM `#__js_job_departments` WHERE created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate) . ") AS totaldepartments
                FROM `#__users` AS user WHERE user.id = ".$id;
        $db->setQuery($query);
        $user = $db->loadObject();
        $result['users'] = $user;
		
        //jobs
        $query = "SELECT job.title,job.created , job.noofjobs,cat.cat_title,company.name AS companyname,jobtype.title AS jobtypetitle		FROM `#__js_job_jobs` AS job
		                JOIN `#__js_job_companies` AS company ON company.id = job.companyid
		                LEFT JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
		                LEFT JOIN `#__js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
		                WHERE DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
		                AND job.status = 1 AND job.uid = ". $id ."";
        $db->setQuery($query);
        $result['jobs'] = $db->loadObjectList();

        //companies
        $query = "SELECT company.name, company.created, cat.cat_title";
		$query .= " 
            FROM `#__js_job_companies` AS company
            LEFT JOIN `#__js_job_categories` AS cat ON company.category = cat.id
            WHERE company.uid = " . $id;
        $db->setQuery($query);
        $result['companies'] = $db->loadObjectList();
        
        return $result;	
    }

    function getExportDataForEmployer($fromdate,$todate,$result){
        $tb = "\t";
        $nl = "\n";
        $data = Text::_('User Report').' '.Text::_('From').' '.$fromdate.' - '.$todate.$nl.$nl;

        // By 1 month
        $data .= Text::_('Employer data').$nl.$nl;
        $data .= Text::_('Date').$tb.Text::_('Publish jobs').$tb.Text::_('Unpublish Jobs').$tb.Text::_('Publish Companies').$tb.Text::_('Unpublish Companies').$tb.Text::_('Departments').$nl;

        while (strtotime($fromdate) <= strtotime($todate)) {
            $publishjobs = 0;
            $unpublishjobs = 0;
            $publishcompanies = 0;
            $unpublishcompanies = 0;
            $departments = 0;
            foreach ($result['publishjobs'] as $job) {
                $job_date = date('Y-m-d', strtotime($job->created));
                if($job_date == $fromdate)
                    $publishjobs += 1;
            }
            foreach ($result['unpublishjobs'] as $job) {
                $job_date = date('Y-m-d', strtotime($job->created));
                if($job_date == $fromdate)
                    $unpublishjobs += 1;
            }
            foreach ($result['publishcompanies'] as $company) {
                $company_date = date('Y-m-d', strtotime($company->created));
                if($company_date == $fromdate)
                    $publishcompanies += 1;
            }
            foreach ($result['unpublishcompanies'] as $company) {
                $company_date = date('Y-m-d', strtotime($company->created));
                if($company_date == $fromdate)
                    $unpublishcompanies += 1;
            }
            foreach ($result['departments'] as $department) {
                $department_date = date('Y-m-d', strtotime($department->created));
                if($department_date == $fromdate)
                    $departments += 1;
            }
            $data .= '"'.$fromdate.'"'.$tb.'"'.$publishjobs.'"'.$tb.'"'.$unpublishjobs.'"'.$tb.'"'.$publishcompanies.'"'.$tb.'"'.$unpublishcompanies.'"'.$tb.'"'.$departments.'"'.$nl;
            $fromdate = date("Y-m-d", strtotime("+1 day", strtotime($fromdate)));
        }
        $data .= $nl.$nl.$nl;
        // END By 1 month

        // Employer detail
        $data .= Text::_('Employer detail').$nl.$nl;
        if(!empty($result['users'])){
            $user = $result['users'];
            $data .= Text::_('Name').$tb.Text::_('Email').$tb.Text::_('Total jobs').$tb.Text::_('Total Companies').$tb.Text::_('Total Departments');
            $data .= $nl;
            $data .= '"'.Text::_($user->display_name).'"'.$tb.'"'.$user->user_email.'"'.$tb.'"'.Text::_($user->totaljobs).'"'.$tb.'"'.Text::_($user->totalcompanies).'"'.$tb.'"'.Text::_($user->totaldepartments).'"';
            $data .= $nl;
            $data .= $nl.$nl.$nl;
        }
        
        //Job details
        $data .= Text::_('Jobs detail').$nl.$nl;
        if(!empty($result['jobs'])){
            $data .= Text::_('Title').$tb.Text::_('Category').$tb.Text::_('Company').$tb.Text::_('Job Type').$tb.Text::_('No. of jobs').$tb.Text::_('Created');
            $data .= $nl;
            $status = '';
            foreach ($result['jobs'] as $job) {
                $created = date('Y-m-d',strtotime($job->created));
                $data .= '"'.$job->title.'"'.$tb.'"'.Text::_($job->cat_title).'"'.$tb.'"'.Text::_($job->companyname).'"'.$tb.'"'.Text::_($job->jobtypetitle).'"'.$tb.'"'.Text::_($job->noofjobs).'"'.$tb.'"'.$created.'"';
                $data .= $nl;
            }
            $data .= $nl.$nl.$nl;
        }

        // company details
        $data .= Text::_('Companies detail').$nl.$nl;
        if(!empty($result['companies'])){
            $data .= Text::_('Title').$tb.Text::_('Category').$tb.Text::_('Created');
            $data .= $nl;
            $status = '';
            foreach ($result['companies'] as $company) {
                $created = date('Y-m-d',strtotime($company->created));
                $data .= '"'.$company->name.'"'.$tb.'"'.Text::_($company->cat_title).'"'.$tb.'"'.$created.'"';
                $data .= $nl;
            }
            $data .= $nl.$nl.$nl;
        }

        return $data;   
    }

    private function getJobseekerDetailReportByUid($uid){
        $db = Factory::getDbo();
        $curdate = Factory::getApplication()->input->get('date_start', 'get');
        $fromdate = Factory::getApplication()->input->get('date_end', 'get');
        if($uid == 0 || $uid == ''){
            $id = Factory::getApplication()->input->get('uid', 'get');
        }else{
            $id = $uid;
            if(! is_numeric($id))
                return null;
            $query = "SELECT created FROM `#__js_job_resume` WHERE uid = ".$id ." ORDER BY created ASC LIMIT 1";
            $db->setQuery($query);
            $curdate = $db->loadResult();
            $fromdate = date('Y-m-d H:i:s');
        }
        if( empty($curdate) OR empty($fromdate))
            return null;
        if(! is_numeric($id))
            return null;

        $result['curdate'] = $curdate;
        $result['fromdate'] = $fromdate;
        $result['id'] = $id;

        //Query to get Data
        
        // total resumes
        $query = "SELECT created FROM `#__js_job_resume` WHERE created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['resumes'] = $db->loadObjectList();
        
        //total job apply
        $query = "SELECT apply_date AS created FROM `#__js_job_jobapply` WHERE apply_date >= " . $db->Quote($curdate) . " AND apply_date <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['jobapply'] = $db->loadObjectList();

        //total job search
        $query = "SELECT created FROM `#__js_job_jobsearches` WHERE status = 1 AND created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate);
        if($id) $query .= " AND uid = ".$id;
        $db->setQuery($query);
        $result['jobsearches'] = $db->loadObjectList();

        //user detail
        $query = "SELECT user.name as display_name,user.email AS user_email,user.username,user.id,
                    (SELECT COUNT(created) FROM `#__js_job_resume` WHERE uid = user.id AND  created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate) . ") AS totalresumes,
                    (SELECT COUNT(apply_date)  FROM `#__js_job_jobapply`  WHERE uid = user.id AND apply_date >= " . $db->Quote($curdate) . " AND apply_date <= " . $db->Quote($fromdate) . ") AS totaljobsapply,
                    (SELECT COUNT(created) FROM `#__js_job_jobsearches` WHERE uid = user.id AND created >= " . $db->Quote($curdate) . " AND created <= " . $db->Quote($fromdate) . ") AS totaljobsearches
                FROM `#__users` AS user WHERE user.id = ".$id;
        $db->setQuery($query);
        $user = $db->loadObject();
        $result['users'] = $user;
        
        //jobs
        $query = "SELECT resume.first_name,resume.created,resume.application_title,resume.email_address,resume.last_name,resume.status
                    ,category.cat_title, jobtype.title AS jobtypetitle, salary.rangestart, salary.rangeend
                    ,edu.title AS educationtitle, currency.symbol,salarytype.title AS salarytype ,exp.title AS exptitle,
                    resume.total_experience
                    FROM `#__js_job_resume` AS resume
                    LEFT JOIN `#__js_job_categories` AS category ON resume.job_category = category.id
                    LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                    LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                    LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON resume.jobsalaryrangetype = salarytype.id
                    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid
                    LEFT JOIN `#__js_job_experiences` AS exp ON exp.id = resume.experienceid
                    LEFT JOIN `#__js_job_heighesteducation` AS edu ON edu.id = resume.heighestfinisheducation
                    WHERE resume.uid  = " . $id . "";
        $db->setQuery($query);
        $result['allresumes'] = $db->loadObjectList();

        //companies
        $query = "SELECT job.title,job.jobcategory,cat.cat_title, apply.apply_date, jobtype.title AS jobtypetitle, 
                        jobstatus.title AS jobstatustitle, salary.rangestart,salaryto.rangeend, salaryto.rangeend AS salaryto
                        ,company.name AS companyname,salarytype.title AS salaytype,job.noofjobs,job.city,cur.symbol,apply.action_status AS resumestatus
                        ,resume.application_title AS applicationtitle,coverletter.title AS coverlettertitle
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_jobapply` AS apply ON apply.jobid = job.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_resume` AS resume ON resume.id = apply.cvid
                        LEFT JOIN `#__js_job_coverletters` AS coverletter ON coverletter.id = apply.coverletterid
                        LEFT JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `#__js_job_salaryrange` AS salary ON job.salaryrangefrom = salary.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = job.currencyid
                        WHERE apply.uid = " . $id . "";
        $db->setQuery($query);
        $result['alljobapply'] = $db->loadObjectList();

        // job search
        $query = "SELECT search.* FROM `#__js_job_jobsearches` AS search WHERE search.uid  = " . $id;
        $db->setQuery($query);
        $result['alljobsearch'] = $db->loadObjectList();

        return $result; 
    }

    function getExportDataForJobseeker($fromdate,$todate,$result){

        $tb = "\t";
        $nl = "\n";
        $data = Text::_('User Report').' '.Text::_('From').' '.$fromdate.' - '.$todate.$nl.$nl;

        // By 1 month
        $data .= Text::_('Jobseeker data').$nl.$nl;
        $data .= Text::_('Date').$tb.Text::_('Total Resumes').$tb.Text::_('Total applied jobs').$tb.Text::_('Total job searches').$nl;

        while (strtotime($fromdate) <= strtotime($todate)) {
            $totalresumes = 0;
            $totaljobapply = 0;
            $totaljobsearch = 0;
            foreach ($result['resumes'] as $resume) {
                $resume_date = date('Y-m-d', strtotime($resume->created));
                if($resume_date == $fromdate)
                    $totalresumes += 1;
            }
            foreach ($result['jobapply'] as $job) {
                $job_date = date('Y-m-d', strtotime($job->created));
                if($job_date == $fromdate)
                    $totaljobapply += 1;
            }
            foreach ($result['jobsearches'] as $job) {
                $job_date = date('Y-m-d', strtotime($job->created));
                if($job_date == $fromdate)
                    $totaljobsearch += 1;
            }
            $data .= '"'.$fromdate.'"'.$tb.'"'.$totalresumes.'"'.$tb.'"'.$totaljobapply.'"'.$tb.'"'.$totaljobsearch.'"'.$nl;
            $fromdate = date("Y-m-d", strtotime("+1 day", strtotime($fromdate)));
        }
        $data .= $nl.$nl.$nl;
        // END By 1 month

        // Jobseeker detail
        $data .= Text::_('Jobseeker detail').$nl.$nl;
        if(!empty($result['users'])){
            $user = $result['users'];
            $data .= Text::_('Name').$tb.Text::_('Email').$tb.Text::_('Total resumes').$tb.Text::_('Total Appied Jobs').$tb.Text::_('Total Job Searches');
            $data .= $nl;
            $data .= '"'.Text::_($user->display_name).'"'.$tb.'"'.$user->user_email.'"'.$tb.'"'.Text::_($user->totalresumes).'"'.$tb.'"'.Text::_($user->totaljobsapply).'"'.$tb.'"'.Text::_($user->totaljobsearches).'"';
            $data .= $nl;
            $data .= $nl.$nl.$nl;
        }
        
        //Resume details
        $data .= Text::_('Resumes detail').$nl.$nl;
        if(!empty($result['allresumes'])){
            $data .= Text::_('Name').$tb.Text::_('Application Title').$tb.Text::_('Email Address').$tb.Text::_('Category').$tb.Text::_('Job Type').$tb.Text::_('Salary Range').$tb.Text::_('Created');
            $data .= $nl;
            $status = '';
            foreach ($result['allresumes'] as $resume) {
                $created = date('Y-m-d',strtotime($resume->created));
                $data .= '"'.Text::_($resume->first_name) . " " . Text::_($resume->last_name) .'"'.$tb.'"'.Text::_($resume->application_title).'"'.$tb.'"'.Text::_($resume->email_address).'"'.$tb.'"'.Text::_($resume->cat_title).'"'.$tb.'"'.Text::_($resume->jobtypetitle).'"'.$tb.'"'.$resume->symbol.$resume->rangestart . "-" . $resume->rangeend .'"'.$tb.'"'.$created.'"';
                $data .= $nl;
            }
            $data .= $nl.$nl.$nl;
        }

        // applied job details
        $data .= Text::_('Applied Job Detail').$nl.$nl;
        if(!empty($result['alljobapply'])){
            $data .= Text::_('Job Title').$tb.Text::_('Application Title').$tb.Text::_('Category').$tb.Text::_('Company').$tb.Text::_('Job Type').$tb.Text::_('Job Status').$tb.Text::_('Created');
            $data .= $nl;
            $status = '';
            foreach ($result['alljobapply'] as $apply) {
                $created = date('Y-m-d',strtotime($apply->apply_date));
                $data .= '"'.Text::_($apply->title).'"'.$tb.'"'.Text::_($apply->applicationtitle).'"'.$tb.'"'.Text::_($apply->cat_title).'"'.$tb.'"'.Text::_($apply->companyname).'"'.$tb.'"'.Text::_($apply->jobtypetitle).'"'.$tb.'"'.Text::_($apply->jobstatustitle).'"'.$tb.'"'.$created.'"';
                $data .= $nl;
            }
            $data .= $nl.$nl.$nl;
        }

        //Job search
        $data .= Text::_('Job Search').$nl.$nl;
        if(!empty($result['alljobsearch'])){
            $data .= Text::_('Search Title').$tb.Text::_('Created');
            $data .= $nl;
            $status = '';
            foreach ($result['alljobsearch'] as $search) {
                $created = date('Y-m-d',strtotime($search->created));
                $data .= '"'.Text::_($search->searchname).'"'.$tb.'"'.$created.'"';
                $data .= $nl;
            }
            $data .= $nl.$nl.$nl;
        }

        return $data;   
    }
}
?>
