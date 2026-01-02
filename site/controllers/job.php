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
use Joomla\CMS\Router\Route;    

jimport('joomla.application.component.controller');

class JSJobsControllerJob extends JSController {
    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }
    function subcategoriesbycatid(){
        $catid = Factory::getApplication()->input->get('catid');
        $showall = Factory::getApplication()->input->get('showall');
        if($showall=='true'){
            $showall = true;
        }else{
            $showall = false;
        }
        $result = $this->getModel('Job','JSJobsModel')->subCategoriesByCatId($catid , $showall);
        echo $result;
        Factory::getApplication()->close();
    }

    function savejob() { //save job
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $job = $this->getmodel('Job', 'JSJobsModel');

        $return_data = $job->storeJob();
        if ($return_data == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'job','message');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $Itemid;
        } else if ($return_data == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','warning');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=' . $Itemid;
        } else if ($return_data == 11) { // start date not in oldate
            JSJOBSActionMessages::setMessage('Start date not old date', 'job','warning');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=' . $Itemid;
        } else if ($return_data == 12) {
            JSJOBSActionMessages::setMessage('Start date can not be less than stop date', 'job','warning');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=' . $Itemid;
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'job','error');
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $Itemid;
        }
        $this->setRedirect(Route::_($link , false));
    }

    function deletejob() { //delete job
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $jobid = $common->parseId(Factory::getApplication()->input->get('bd'));
        $vis_email = Factory::getApplication()->input->getString('email');
        $vis_jobid = $common->parseId(Factory::getApplication()->input->get('bd'));
        $job = $this->getmodel('Job', 'JSJobsModel');
        $return_value = $job->deleteJob($jobid, $uid, $vis_email, $vis_jobid);
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'job','warning');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(IN_USE, 'job','warning');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'job','warning');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'job','warning');
        }
        if (($vis_email == '') || ($jobid == ''))
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $Itemid;
        else
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&email=' . $vis_email . '&bd=' . $vis_jobid . '&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function mailtocandidate() {
        $user = Factory::getUser();
        $uid = $user->id;
        $email = Factory::getApplication()->input->getString('email');
        $jobapplyid = Factory::getApplication()->input->get('jobapplyid');
        $jobapply = $this->getmodel('Jobapply', 'JSJobsModel');
        $returnvalue = $jobapply->getMailForm($uid, $email, $jobapplyid);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function sendtocandidate() {
        $val = json_decode(Factory::getApplication()->input->get('val',"","string"), true);
        $emailtemplate = $this->getmodel('Emailtemplate', 'JSJobsModel');
        $returnvalue = $emailtemplate->sendToCandidate($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function quickview() {
        $jobid = Factory::getApplication()->input->get('jobid', false);
        //$jobid = $this->getModel('Common', 'JSJobsModel')->parseId($jobid);
        $result = $this->getModel('Quickview', 'JSJobsModel')->getJobQuickViewById($jobid);
        echo $result;
        Factory::getApplication()->close();
    }

    function getnextjobs() {
        $result = $this->getModel('Job', 'JSJobsModel')->getNextJobs();
        echo $result;
        Factory::getApplication()->close();
    }

    function postjobonjomsocial(){
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $isallowed = JSModel::getJSModel('configurations')->getConfigValue('jomsocial_allowpostjob');
        if($isallowed){
            $jobid = Factory::getApplication()->input->get('id',0);
            $res = JSModel::getJSModel('job')->postJobOnJomSocial($jobid);
            if($res){
                JSJOBSActionMessages::setMessage("Job has been successfully posted on JomSocial", 'job','message');
            }else{
                JSJOBSActionMessages::setMessage("Job has not been posted on JomSocial", 'job','error');
            }
        }
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $Itemid;
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) { // correct employer controller display function manually.
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'default');
        $layoutName = Factory::getApplication()->input->get('layout', 'default');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}
?>
