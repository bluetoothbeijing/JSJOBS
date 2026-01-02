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
use Joomla\CMS\Router\Route;

jimport('joomla.application.component.controller');

class JSJobsControllerResume extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function getresumedetail() {
        $user = Factory::getUser();
        $uid = $user->id;
        $jobid = Factory::getApplication()->input->get('jobid');
        $resumeid = Factory::getApplication()->input->get('resumeid');
        //require_once(JPATH_ROOT.'/components/com_jsjobs/models/resume.php');
        //$resume_model = new JSJobsModelResume();
        $resume_model = $this->getmodel('Resume', 'JSJobsModel');
        $returnvalue = $resume_model->getResumeDetail($uid, $jobid, $resumeid);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    /* STRAT EXPORT RESUMES */

    function resumeenforcedelete() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $resumeid = $cid[0];
        $user = Factory::getUser();
        $uid = $user->id;
        $resume_model = $this->getmodel('Resume', 'JSJobsModel');
        $return_value = $resume_model->resumeEnforceDelete($resumeid, $uid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'resume','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'resume','error');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'resume','warning');
        }
        $layout = Factory::getApplication()->input->get('callfrom','empapps');
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout='.$layout;
        
        $this->setRedirect($link);
    }

    function resumeapprove() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $appid = Factory::getApplication()->input->get('id');
        
        $resume_model = $this->getmodel('Resume', 'JSJobsModel');
        $return_value = $resume_model->empappApprove($appid);

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(APPROVED, 'resume','message');
        } else
            JSJOBSActionMessages::setMessage(APPROVE_ERROR, 'resume','error');
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=appqueue';
        $this->setRedirect($link);
    }

    function resumereject() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $appid = Factory::getApplication()->input->get('id');

        $resume_model = $this->getmodel('Resume', 'JSJobsModel');
        $return_value = $resume_model->empappReject($appid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(REJECTED, 'resume','message');
        } else
            JSJOBSActionMessages::setMessage(REJECT_ERROR, 'resume','error');
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=appqueue';
        $this->setRedirect($link);
    }

    function saveresume() {
        $data = Factory::getApplication()->input->post->getArray();
        require_once (JPATH_ROOT.'/components/com_jsjobs/models/resume.php');
        $resume_model = new JSJobsModelResume();
        $resumeid = $resume_model->storeResume( $data );
        if(isset($data['save'])){
            if($resumeid){
                JSJOBSActionMessages::setMessage(SAVED, 'resume','message');
            }else{
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'resume','error');
            }
            $link = 'index.php?option=com_jsjobs&c=resume&task=resume.edit&cid[]=' . $resumeid;
        }elseif(isset($data['saveandclose'])){
            if($resumeid){
                JSJOBSActionMessages::setMessage(SAVED, 'resume','message');
            }else{
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'resume','error');
            }
            $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps';
        }
        $this->setRedirect(Route::_($link,false));
    }

    function remove() { 
        $resume_model = $this->getmodel('Resume', 'JSJobsModel');
        $returnvalue = $resume_model->deleteResume();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'resume','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'resume','error');
        }
        $layout = Factory::getApplication()->input->get('callfrom','empapps');
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout='.$layout;
        $this->setRedirect($link);
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'resume','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps');
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'formresume');
        Factory::getApplication()->input->set('view', 'resume');
        Factory::getApplication()->input->set('c', 'resume');
        $layout = Factory::getApplication()->input->get('callfrom','empapps');
        Factory::getApplication()->input->set('callfrom', $layout);
        $this->display();
    }

    function postresumeonjomsocial(){
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));        
        $resumeid = Factory::getApplication()->input->get('id',0);
        $res = JSModel::getJSModel('resume')->postResumeOnJomSocial($resumeid);
        if($res){
            JSJOBSActionMessages::setMessage("Resume has been successfully posted on JomSocial", 'resume','message');
        }else{
            JSJOBSActionMessages::setMessage("Resume has not been posted on JomSocial", 'resume','error');
        }
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps';
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'resume');
        $layoutName = Factory::getApplication()->input->get('layout', 'resume');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setLayout($layoutName);
        $view->display();
    }
}
?>
