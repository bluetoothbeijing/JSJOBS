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

jimport('joomla.application.component.controller');

class JSJobsControllerJob extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function jobenforcedelete() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $jobid = $cid[0];
        $user = Factory::getUser();
        $uid = $user->id;
        $job_model = $this->getmodel('Job', 'JSJobsModel');
        $returnvalue = $job_model->jobEnforceDelete($jobid, $uid);
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'job','message');
        } elseif ($returnvalue == 2) {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'job','error');
        } elseif ($returnvalue == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'job','notice');
        }
        $layout = Factory::getApplication()->input->get('callfrom','jobs');
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout='.$layout;
        $this->setRedirect($link);
    }

    function jobapprove() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $jobid = Factory::getApplication()->input->get('id');
        
        $job_model = $this->getmodel('Job', 'JSJobsModel');
        $return_value = $job_model->jobApprove($jobid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(APPROVED, 'job','message');
        } else
            JSJOBSActionMessages::setMessage(APPROVE_ERROR, 'job','error');
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobqueue';
        $this->setRedirect($link);
    }

    function jobreject() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $jobid = Factory::getApplication()->input->get('id');
        
        $job_model = $this->getmodel('Job', 'JSJobsModel');
        $return_value = $job_model->jobReject($jobid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(REJECTED, 'job','message');
        } else
            JSJOBSActionMessages::setMessage(REJECT_ERROR, 'job','error');
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobqueue';
        $this->setRedirect($link);
    }

    function savejob() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $job_model = $this->getmodel('Job', 'JSJobsModel');
        $return_data = $job_model->storeJob();
        $layout = Factory::getApplication()->input->get('callfrom','jobs');
        if ($return_data == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'job','message');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout='.$layout;
            $this->setRedirect($link);
        } elseif ($return_data == 5) {
            JSJOBSActionMessages::setMessage(SAVED, 'job','message');
            JSJOBSActionMessages::setMessage(FILE_SIZE_ERROR, 'job','warning');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout='.$layout;
            $this->setRedirect($link);
        } elseif ($return_data == 6) {
            JSJOBSActionMessages::setMessage(SAVED, 'job','message');
            JSJOBSActionMessages::setMessage(FILE_TYPE_ERROR, 'job','warning');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout='.$layout;
            $this->setRedirect($link);
        } elseif ($return_data == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&cid[]='.Factory::getApplication()->input->get('id');
            $this->setRedirect($link);
        } elseif ($return_data == 12) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob';
            $this->setRedirect($link);
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'job','error');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout='.$layout;
            $this->setRedirect($link);
        }
    }

    function remove() {
        $job_model = $this->getmodel('Job', 'JSJobsModel');
        $returnvalue = $job_model->deleteJob();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'job','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'job','error');
        }
        $layout = Factory::getApplication()->input->get('callfrom','jobs');
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout='.$layout;
    
        $this->setRedirect($link);
    }
    function edit() {
        Factory::getApplication()->input->set('c', 'job');
        Factory::getApplication()->input->set('view', 'job');
        Factory::getApplication()->input->set('layout', 'formjob');
        $layout = Factory::getApplication()->input->get('callfrom','jobs');
        Factory::getApplication()->input->set('callfrom',$layout);
        $this->display();
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'job','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=job&view=job&layout=jobs');
    }

    function postjobonjomsocial(){
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));        
        $jobid = Factory::getApplication()->input->get('id',0);
        $res = JSModel::getJSModel('job')->postJobOnJomSocial($jobid);
        if($res){
            JSJOBSActionMessages::setMessage("Job has been successfully posted on JomSocial", 'job','message');
        }else{
            JSJOBSActionMessages::setMessage("Job has not been posted on JomSocial", 'job','error');
        }
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobs';
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'job');
        $layoutName = Factory::getApplication()->input->get('layout', 'job');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $job_model = $this->getModel('Job', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($job_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
