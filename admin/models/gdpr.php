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

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelGdpr extends JSModel {

    function __construct() {
        parent::__construct();
    }

    function getGDPRFeilds(){
        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__js_job_fieldsordering` WHERE fieldfor = 14 ORDER BY ordering ";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

	function getEraseDataRequests($email,$limitstart,$limit){
        $db = Factory::getDbo();
		$query = "SELECT COUNT(id) FROM `#__js_job_erasedatarequests`";
        $db->setQuery($query);
        $total = $db->loadResult();
        $result = array();
        $inquery = '';
        if ($email != null)
            $inquery .= " WHERE user.email = ".$db->Quote($email);

        // Data
        $query = "SELECT request.*, user.email, userrole.role
                    FROM `#__js_job_erasedatarequests` AS request
                    JOIN `#__users` AS user ON user.id = request.uid
                    JOIN `#__js_job_userroles` AS userrole ON user.id = userrole.uid 
                    ";
        $query .= $inquery;
        $query .= " ORDER BY request.created DESC ";
        $db->setQuery($query, $limitstart, $limit);
        $data = $db->loadObjectList();
        $result[0] = $data;
        $result[1] = $total;
        return $result;
	}

    function anonymizeUserData($uid){
        if(!is_numeric($uid) || $uid == 0){
            return false;
        }
            
        $db = Factory::getDbo();
        $jobrole = $this->getJSModel('userrole')->getUserRoleByUid($uid);
        if($jobrole == 1){ // Employer
            $return = $this->eraseEmployerDataByUid($uid);
        }elseif($jobrole == 2){ // Jobseeker
            $return = $this->eraseJobseekerDataByUid($uid);
        }
        
        if($return){
            $query = "UPDATE `#__js_job_erasedatarequests` SET status = 2 WHERE uid = $uid";
            $db->setQuery($query);
            $db->execute($query);
            $this->getJSModel('emailtemplate')->sendMail(5, 1, $uid); // Mailfor, 1 for erase user data,
        }
        return $return;
    }

    function eraseEmployerDataByUid($uid){
        if(!is_numeric($uid) || $uid == 0){
            return false;
        }
        $db = Factory::getDbo();
        $query = "SELECT id FROM `#__js_job_companies` WHERE uid = ".$uid;
        $db->setQuery($query);
        $companyids = $db->loadObjectList();
        $return = true;
        if(!empty($companyids)){
            foreach($companyids AS $company){
                $return  = $this->getJSModel('company')->updateCompanyDataForEraseDataRequest($company->id);
                // erase replies data
                if($return){
                    $jobids = $this->getJSModel('job')->getJobIdsByCompanyid($company->id);
                    foreach ($jobids as $jobid) {
                        $return  = $this->getJSModel('job')->updateJobDataForEraseDataRequest($jobid->id);
                    }
                    $query = "UPDATE `#__js_job_departments` SET `name`='-----',`description`='-----' WHERE companyid = " . $company->id;
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
        
        $query = "UPDATE `#__js_job_resumesearches` SET `searchname`='-----',`searchparams`='' ,`params` = '' WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();
        return $return;
    }

    function eraseJobseekerDataByUid($uid){
        if(!is_numeric($uid) || $uid == 0){
            return false;
        }
        $db = Factory::getDbo();
        $query = "SELECT id FROM `#__js_job_resume` WHERE uid = ".$uid;
        $db->setQuery($query);
        $resumesid = $db->loadObjectList();
        $return = true;
        if(!empty($resumesid)){
            foreach($resumesid AS $resume){
                $return = $this->getJSModel('resume')->updateResumeDataForEraseDataRequest($resume->id,$uid);
            }
        }
        
        $query = "UPDATE `#__js_job_coverletters` SET `title`='-----',`description`='' WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();

        $query = "UPDATE `#__js_job_jobsearches` SET `searchname`='-----',`params`='' WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();     
        return $return; 
    }

    function deleteUserData($uid){
        if(!is_numeric($uid) || $uid == 0){
            return false;
        }

        $db = Factory::getDbo();
        $jobrole = $this->getJSModel('userrole')->getUserRoleByUid($uid);
        if($jobrole == 1){ // Employer
            $return = $this->deleteEmployerDataByUid($uid);
        }elseif($jobrole == 2){ // Jobseeker
            $return = $this->deleteJobseekerDataByUid($uid);
        }
        
        $query = "UPDATE `#__js_job_erasedatarequests` SET status = 3 WHERE uid = $uid";
        $db->setQuery($query);
        $db->execute($query);
        $this->getJSModel('emailtemplate')->sendMail(5, 1, $uid); // Mailfor, 1 for erase user data,
        return DELETED;
    }

    function deleteEmployerDataByUid($uid){
        if(!is_numeric($uid) || $uid == 0){
            return false;
        }

        $db = Factory::getDbo();
        $query = "SELECT id FROM `#__js_job_companies` WHERE uid = ".$uid;
        $db->setQuery($query);
        $companyids = $db->loadObjectList();
        if(!empty($companyids)){
            foreach($companyids AS $company){
                // Delete company
                $row = $this->getTable('company');
                $row->delete($company->id);
                
                // Delete company cities
                $query = $db->getQuery(true);
                $condition = array($db->quoteName('companyid') . ' = ' . $company->id);
                $query->delete('#__js_job_companycities');
                $query->where($condition);
                $db->setQuery($query);
                $db->execute();
                
                // Delete Department
                $this->getJSModel('department')->deleteUserDepByCompanyId($company->id);

                $jobids = $this->getJSModel('job')->getJobIdsByCompanyid($company->id);
                foreach($jobids AS $job){
                    $this->getJSModel('job')->deleteUserJobDataById($job->id);    
                }

                // company attachments.
                $datadirectory = $this->getJSModel('configuration')->getConfigValue('data_directory');
                $mainpath = JPATH_BASE;
                if(Factory::getApplication()->isClient('administrator')){
                    $mainpath = substr($mainpath, 0, strlen($mainpath) - 14); //remove administrator
                }
                $mainpath = $mainpath .'/'.$datadirectory.'/data';
                $mainpath = $mainpath . '/employer/';
                $folder = $mainpath . 'comp_'.$company->id.'';
                if(file_exists($folder)){
                    $logo = $mainpath . '/comp_'.$company->id.'/logo/';
                    if(file_exists($folder)){
                        $path = $logo . '*.*';
                        $files = glob($path);
                        array_map('unlink', $files);//deleting files
                        rmdir($logo);
                    }
                    $path = $folder . '/*.*';
                    $files = glob($path);
                    array_map('unlink', $files);//deleting files
                    rmdir($folder);
                }
            }
        }

        //delete resume search
        $query = "DELETE FROM `#__js_job_resumesearches` WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();

        // delete payment history
        $this->getJSModel('paymenthistory')->deleteUserPaymentHistoryDataByUid($uid);      
    }

    function deleteJobseekerDataByUid($uid){
        if(!is_numeric($uid) || $uid == 0){
            return false;
        }

        $db = Factory::getDbo();
        $query = "SELECT id FROM `#__js_job_resume` WHERE uid = ".$uid;
        $db->setQuery($query);
        $resumeids = $db->loadObjectList();
        if(!empty($resumeids)){
            foreach($resumeids AS $resume){
                // Delete resume
                $row = $this->getTable('resume');
                $row->delete($resume->id);
                
                // Delete resume data
                $this->getJSModel('resume')->removeUserResumeDataByResumeId($resume->id);
            }
        }

        // job apply data
        $query = $db->getQuery(true);
        $condition = array($db->quoteName('uid') . ' = ' . $uid);
        $query->delete('#__js_job_jobapply');
        $query->where($condition);
        $db->setQuery($query);
        $db->execute();

        //delete resume search
        $query = "DELETE FROM `#__js_job_jobsearches` WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();      

        //delete cover letter
        $query = "DELETE FROM `#__js_job_coverletters` WHERE uid = " . $uid;
        $db->setQuery($query);
        $db->execute();

        // delete payment history
        $this->getJSModel('paymenthistory')->deleteUserPaymentHistoryDataByUid($uid);
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
}
?>
