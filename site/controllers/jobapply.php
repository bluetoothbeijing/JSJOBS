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

class JSJobsControllerJobApply extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function applyjob() {
        $visitorapplyjob = $this->getModel('configurations', 'JSJobsModel')->getConfigValue('visitor_can_apply_to_job');
        if (!Factory::getUser()->guest || $visitorapplyjob == '0') {
            $jobid = Factory::getApplication()->input->get('jobid', false);
            $result = $this->getModel('Jobapply', 'JSJobsModel')->applyJob($jobid);
            $array[0] = 'popup';
            $array[1] = $result;
            print_r(json_encode($array));
        } else {
            $link = Route::_('index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_apply&bd=' . Factory::getApplication()->input->get('jobid', false) . '&Itemid=' . Factory::getApplication()->input->get('Itemid', false) ,false);
            $array[0] = 'redirect';
            $array[1] = $link;
            print_r(json_encode($array));
        }
        Factory::getApplication()->close();
    }

    function jobapply() {
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        $uid = Factory::getApplication()->input->get($uid, 'none', 'string');
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $jobapply = $this->getmodel('Jobapply', 'JSJobsModel');
        $return_value = $jobapply->jobapply();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage('Application successfully applied', 'jobapply','message');
            $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&uid=' . $uid . '&Itemid=' . $Itemid;
        } else if ($return_value == 3) {
            JSJOBSActionMessages::setMessage('You already apply for this job', 'jobapply','warning');
            $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $Itemid;
        } else if ($return_value == 10) {
            $textvar = Text::_('You can not apply for this job').'.'.Text::_('Your job apply limit exceeds');
            JSJOBSActionMessages::setMessage($textvar, 'jobapply','warning');
            $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $Itemid;
        } else {
            JSJOBSActionMessages::setMessage('Error in applying for a job', 'jobapply','error');
            $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&uid=' . $uid . '&Itemid=' . $Itemid;
        }
        $this->setRedirect(Route::_($link , false));
    }

    function jobapplyajax() {
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');
        $jobapply = $this->getmodel('Jobapply', 'JSJobsModel');
        $return_value = $jobapply->jobapply();
        if ($return_value == 1) {
            $msg = Text::_('Application successfully applied');
        } else if ($return_value == 3) {
            $msg = Text::_('You already apply for this job');
        } else if ($return_value == 10) {
            $msg = Text::_('You can not apply for this job. Your job apply limit exceeds');
        } else {
            $msg = Text::_('Error in applying for a job');
        }
        echo $msg;
        Factory::getApplication()->close();
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


