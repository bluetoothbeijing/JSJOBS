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

    var $_uid;
    var $_siteurl;

    function __construct() {
        parent::__construct();
        $user = Factory::getUser();
        $this->_uid = $user->id;
        $this->_siteurl = Uri::root();
    }

    function logStoregoldfeaturdjob($return_data) {
        $model = $this->getJSModel('jsjobs');
        $jobsharing = $this->getJSModel('jobsharing');
        if (is_array($return_data)) {
            $logarray['uid'] = $model->_uid;
            $logarray['referenceid'] = $return_data['referenceid'];
            $logarray['event'] = "job";
            $logarray['eventtype'] = $return_data['eventtype'];
            $logarray['message'] = $return_data['message'];
            $logarray['datetime'] = date('Y-m-d H:i:s');
            if ($return_data['goldfeatured'] == 1) {
                $logarray['messagetype'] = "Sucessfully";
            } elseif ($return_data['goldfeatured'] == 0) {
                $logarray['messagetype'] = "Error";
            }
            $jobsharing->writeJobSharingLog($logarray);
        }
        return true;
    }

    function logUnsubscribeJobAlert($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isunsubjobalert'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Unsubscribe Job Alert";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($return_value['isunsubjobalert'] == 0) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Unsubscribe Job Alert";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logDeleteJobSharingEnforce($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['isjobdelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Job Enforce";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['isjobdelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Job Enforce";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logDeleteCompanySharingEnforce($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['iscompanydelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Company Enforce";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($$return_value['iscompanydelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Company Enforce";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logDeleteResumeSharingEnforce($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isresumedelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Resume Enforce";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($return_value['isfolderdelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Delete Resume Enforce";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logCompanyApprove($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['iscompanyapprove'] == 1) {
                if ($return_value['status'] == "Company Approve") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($return_value['iscompanyapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercompanytatus = "Data not post on server";
                } elseif ($return_value['status'] == "Company Approve Error") {
                    $servercompanytatus = "Error Company Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercompanytatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
        }
        return true;
    }

    function logCompanyReject($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['iscompanyreject'] == 1) {
                if ($return_value['status'] == "Company Reject") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($return_value['iscompanyreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercompanytatus = "Data not post on server";
                } elseif ($return_value['status'] == "Company Reject Error") {
                    $servercompanytatus = "Error Company Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercompanytatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
        }
        return true;
    }

    function logDepartmentApprove($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isdepartmentapprove'] == 1) {
                if ($return_value['status'] == "Department Approve") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
            } elseif ($return_value['isdepartmentapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverdepartmentstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Department Approve Error") {
                    $serverdepartmentstatus = "Error Department Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverdepartmentstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
        }

        return true;
    }

    function logDepartmentReject($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isdepartmentreject'] == 1) {
                if ($return_value['status'] == "Department Reject") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
            } elseif ($return_value['isdepartmentreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverdepartmentstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Department Reject Error") {
                    $serverdepartmentstatus = "Error Department Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverdepartmentstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
        }

        return true;
    }

    function logFolderChangeStatusPublish($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isfolderapprove'] == 1) {
                if ($return_value['status'] == "Folder Approve") {
                    $serverfolderstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'folders');
            } elseif ($return_value['isfolderapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverfolderstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Folder Approve Error") {
                    $serverfolderstatus = "Error Approve Folder";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverfolderstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'folders');
            }
        }
        return true;
    }

    function logFolderChangeStatusUnpublish($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isfolderreject'] == 1) {
                if ($return_value['status'] == "Folder Reject") {
                    $serverfolderstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'folders');
            } elseif ($return_value['isfolderreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverfolderstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Folder Reject Error") {
                    $serverfolderstatus = "Error Reject Folder";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverfolderstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'folders');
            }
        }
        return true;
    }

    function logMessageChangeStatusPublish($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['ismessageapprove'] == 1) {
                if ($return_value['status'] == "Message Approve") {
                    $servermessagestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'messages');
            } elseif ($return_value['ismessageapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servermessagestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Message Approve Error") {
                    $servermessagestatus = "Error Message Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servermessagestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'messages');
            }
        }
        return true;
    }

    function logMessageChangeStatusUnpublish($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['ismessagereject'] == 1) {
                if ($return_value['status'] == "Message Reject") {
                    $servermessagestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'messages');
            } elseif ($return_value['ismessagereject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servermessagestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Message Reject Error") {
                    $servermessagestatus = "Error Message Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servermessagestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'messages');
            }
        }
        return true;
    }

    function logMessageSharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['ismessagestore'] == 1) {
                if ($return_value['status'] == "Message Sucessfully") {
                    $servermessage = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Messages";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessage, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'messages');
            } elseif ($return_value['ismessagestore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servermessage = "Data not post on server";
                } elseif ($return_value['status'] == "Message Saving Error") {
                    $servermessage = "Error Message Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servermessage = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Messages";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessage, $logarray['referenceid'], $serverid, $logarray['uid'], 'messages');
            }
        }
        return true;
    }

    function logJobApprove($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isjobapprove'] == 1) {
                if ($return_value['status'] == "Job Approve") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_value['isjobapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Job Approve Error") {
                    $serverjobstatus = "Error Job Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
        }
        return true;
    }

    function logJobReject($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isjobreject'] == 1) {
                if ($return_value['status'] == "Job Reject") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_value['isjobreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Job Reject Error") {
                    $serverjobstatus = "Error Job Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
        }
        return true;
    }

    function logEmpappApprove($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isresumeapprove'] == 1) {
                if ($return_value['status'] == "Resume Approve") {
                    $serverresumestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumeapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Approve Error") {
                    $serverresumestatus = "Error Resume Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
        }
        return true;
    }

    function logEmpappReject($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isresumereject'] == 1) {
                if ($return_value['status'] == "Resume Reject") {
                    $serverresumestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumereject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Reject Error") {
                    $serverresumestatus = "Error Resume Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
        }
        return true;
    }

    function logDeleteResumeSharing($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['isresumedelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Resume";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['isresumedelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logDeleteCompanySharing($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['iscompanydelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Company";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['iscompanydelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logDeleteDepartmentSharing($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['isdepartmentdelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Department";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['isdepartmentdelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Department";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }

        return true;
    }

    function logDeleteJobSharing($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['isjobdelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Job";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['isjobdelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
    }

    function logDeleteMessageSharing($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['ismessagedelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Message";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['ismessagedelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Message";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logDeleteFolderSharing($returnvalue) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($returnvalue)) {
            if ($returnvalue['isfolderdelete'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Folder";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($returnvalue['isfolderdelete'] == -1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $returnvalue['referenceid'];
                $logarray['eventtype'] = $returnvalue['eventtype'];
                $logarray['message'] = $returnvalue['message'];
                $logarray['event'] = "Delete Folder";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            }
        }
        return true;
    }

    function logStoreDepartmentSharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isdepartmentstore'] == 1) {
                if ($return_value['status'] == "Department Edit") {
                    $serverdepartmentstatus = "ok";
                } elseif ($return_value['status'] == "Department Add") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
        }
        return true;
    }

    function logStoreCompanySharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['iscompanystore'] == 1) {
                if ($return_value['status'] == "Company Edit") {
                    $servercompanytatus = "ok";
                } elseif ($return_value['status'] == "Company Add") {
                    $servercompanytatus = "ok";
                } elseif ($return_value['status'] == "Company with logo Add") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_value['companycities'])) {
                    $jobsharing->updateMultiCityServerid($return_value['companycities'], 'companycities');
                }

                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
        }
        return true;
    }

    function logStoreFolderSharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
        if (is_array($return_value)) {
            if ($return_value['isfolderstore'] == 1) {
                if ($return_value['status'] == "Folder Edit") {
                    $serverfolderstatus = "ok";
                } elseif ($return_value['status'] == "Folder Add") {
                    $serverfolderstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'folders');
            } elseif ($return_value['isfolderstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverfolderstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Folder Saving Error") {
                    $serverfolderstatus = "Error Folder Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverfolderstatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Folder name") {
                    $serverfolderstatus = "Improper Folder name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'folders');
            }
        }
        return true;
    }

    function logStoreJobSharing($return_data) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_data['jobcities'])) {
                    $jobsharing->updateMultiCityServerid($return_data['jobcities'], 'jobcities');
                }
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_data['serverid'], $logarray['uid'], 'jobs');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
        }

        return true;
    }

    function logStoreResumeSharing($return_value) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumestore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Saving Error") {
                    $serverresumestatus = "Error Resume Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Resume name") {
                    $serverresumestatus = "Improper Resume name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
        }
        return true;
    }

    function logStoreCopyJob($return_data) {
        $jobsharing = $this->getJSModel('jobsharing');
        $model = $this->getJSModel('jsjobs');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job Copy";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_data['serverid'], $logarray['uid'], 'jobs');
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
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job Copy";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
        }
        return true;
    }

}
