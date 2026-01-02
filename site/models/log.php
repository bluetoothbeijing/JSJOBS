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
use Joomla\CMS\Uri\Uri;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelLog extends JSModel {

    var $_uid = null;
    var $_siteurl;

    function __construct() {
        parent::__construct();
        $user = Factory::getUser();
        $this->_uid = $user->id;
        $this->_siteurl = Uri::root();
    }

    function log_Store_JobapplySharing($return_value) {
        $jobseeker_model = $this->getJSModel('jobseeker');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['isjobapplystore'] == 1) {
                if ($return_value['status'] == "Jobapply Sucessfully") {
                    $serverjobapplystatus = "ok";
                }
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Jobapply";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverjobapplystatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobapply');
            } elseif ($return_value['isjobapplystore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverjobapplystatus = "Data not post on server";
                } elseif ($return_value['status'] == "Jobapply Saving Error") {
                    $serverjobapplystatus = "Error Jobapply Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverjobapplystatus = "Authentication Fail";
                }
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Jobapply";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverjobapplystatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobapply');
            }
        }
        return true;
    }

    function log_Store_JobSharing($return_data) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_data)) {
            if ($return_data['isjobstore'] == 1) {
                if ($return_data['status'] == "Job Edit") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Job Add") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Edit Job Userfield") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Add Job Userfield") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_data['jobcities'])) {
                    $jobsharing->update_MultiCityServerid($return_data['jobcities'], 'jobcities');
                }
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverjobstatus, $logarray['referenceid'], $return_data['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_data['isjobstore'] == 0) {
                if ($return_data['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_data['status'] == "job Saving Error") {
                    $serverjobstatus = "Error Job Saving";
                } elseif ($return_data['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                } elseif ($return_data['status'] == "Error Save Job Userfield") {
                    $serverjobstatus = "Error Save Job Userfield";
                } elseif ($return_data['status'] == "Improper job name") {
                    $serverjobstatus = "Improper job name";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
        }
        return true;
    }

    function log_Store_VisitorCompanyJobSharing($return_value) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['isjobstore'] == 1) {
                if ($return_value['status'] == "Job Edit") {
                    $serverjobstatus = "ok";
                } elseif ($return_value['status'] == "Job Add") {
                    $serverjobstatus = "ok";
                } elseif ($return_value['status'] == "Edit Job Userfield") {
                    $serverjobstatus = "ok";
                } elseif ($return_value['status'] == "Add Job Userfield") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverjobstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_value['isjobstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_value['status'] == "job Saving Error") {
                    $serverjobstatus = "Error Job Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Error Save Job Userfield") {
                    $serverjobstatus = "Error Save Job Userfield";
                } elseif ($return_value['status'] == "Improper job name") {
                    $serverjobstatus = "Improper job name";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = "Visitor" . $return_value['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
        }
        return true;
    }

    function log_Store_CompanySharing($return_value) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['iscompanystore'] == 1) {
                if ($return_value['status'] == "Company Edit") {
                    $servercompanytatus = "ok";
                } elseif ($return_value['status'] == "Company Add") {
                    $servercompanytatus = "ok";
                } elseif ($return_value['status'] == "Company with logo Add") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_value['companycities'])) {
                    $jobsharing->update_MultiCityServerid($return_value['companycities'], 'companycities');
                }
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($return_value['iscompanystore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercompanytatus = "Data not post on server";
                } elseif ($return_value['status'] == "Company Saving Error") {
                    $servercompanytatus = "Error Company Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercompanytatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Company name") {
                    $servercompanytatus = "Improper Company name";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
        }
        return true;
    }

    function log_Store_ResumeSharing($return_value) {
        $jobseeker_model = $this->getJSModel('jobseeker');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['isresumestore'] == 1) {
                if ($return_value['status'] == "Resume Edit") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Resume Add") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Edit Resume Userfield") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Add Resume Userfield") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Resume with Picture") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Resume with File") {
                    $serverresumestatus = "ok";
                }
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumestore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Saving Error") {
                    $serverresumestatus = "Error Resume Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Error in saving resume user fields") {
                    $serverresumestatus = "Error in saving resume user fields";
                } elseif ($return_value['status'] == "Improper Resume name") {
                    $serverresumestatus = "Improper Resume name";
                }
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
        }
        return true;
    }

    function log_Store_DepartmentSharing($return_value) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['isdepartmentstore'] == 1) {
                if ($return_value['status'] == "Department Edit") {
                    $serverdepartmentstatus = "ok";
                } elseif ($return_value['status'] == "Department Add") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
            } elseif ($return_value['isdepartmentstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverdepartmentstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Department Saving Error") {
                    $serverdepartmentstatus = "Error Department Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverdepartmentstatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Department name") {
                    $serverdepartmentstatus = "Improper Department name";
                }
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
        }
        return true;
    }

    function log_Store_CoverLetterSharing($return_value) {
        $jobseeker_model = $this->getJSModel('jobseeker');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['iscoverletterstore'] == 1) {
                if ($return_value['status'] == "Coverletter Edit") {
                    $servercoverletterstatus = "ok";
                } elseif ($return_value['status'] == "Coverletter Add") {
                    $servercoverletterstatus = "ok";
                }
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "CoverLetter";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($servercoverletterstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'coverletters');
            } elseif ($return_value['iscoverletterstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercoverletterstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Coverletter Saving Error") {
                    $servercoverletterstatus = "Error Coverletter Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercoverletterstatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Coverletter name") {
                    $servercoverletterstatus = "Improper Coverletter name";
                }
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "CoverLetter";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($servercoverletterstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'coverletters');
            }
        }
        return true;
    }

    function log_Delete_CoverletterSharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharingsite');
        $jobseeker_model = $this->getJSModel('jobseeker');
        if (is_array($return_value)) {
            if ($return_value['iscoverletterdelete'] == 1) {
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Coverletter";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
            } elseif ($return_value['iscoverletterdelete'] == -1) {
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Coverletter";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
            }
        }
        return true;
    }

    function log_Delete_ResumeSharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharingsite');
        $jobseeker_model = $this->getJSModel('jobseeker');
        if (is_array($return_value)) {
            if ($return_value['isresumedelete'] == 1) {
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Resume";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
            } elseif ($return_value['isfolderdelete'] == -1) {
                $logarray['uid'] = $jobseeker_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
            }
        }
        return true;
    }

    function log_Delete_JobSharing($return_value) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['isjobdelete'] == 1) {
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Job";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
            } elseif ($return_value['isjobdelete'] == -1) {
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
            }
        }
        return true;
    }

    function log_Delete_DepartmentSharing($return_value) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['isdepartmentdelete'] == 1) {
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Department";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
            } elseif ($return_value['isdepartmentdelete'] == -1) {
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Department";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
            }
        }
        return true;
    }

    function log_Delete_CompanySharing($return_value) {
        $employer_model = $this->getJSModel('employer');
        $jobsharing = $this->getJSModel('jobsharingsite');
        if (is_array($return_value)) {
            if ($return_value['iscompanydelete'] == 1) {
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Company";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->write_JobSharingLog($logarray);
            } elseif ($return_value['iscompanydelete'] == -1) {
                $logarray['uid'] = $employer_model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
            }
        }
        return true;
    }

}

/*$common_model = new JSJobsModelCommon;
        $jobseeker_model = new JSJobsModelJobseeker;
        $employer_model = new JSJobsModelEmployer;
        $jobsharing = new JSJobsModelJob_Sharing;*/
