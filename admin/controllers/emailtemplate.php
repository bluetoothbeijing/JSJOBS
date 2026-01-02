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

jimport('joomla.application.component.controller');

class JSJobsControllerEmailtemplate extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saveemailtemplate() {
        $data = Factory::getApplication()->input->post->getArray();
        $templatefor = $data['templatefor'];
        $emailtemplate_model = $this->getmodel('Emailtemplate', 'JSJobsModel');
        $return_value = $emailtemplate_model->storeEmailTemplate();
        switch ($templatefor) {
            case 'company-new' : $tempfor = 'ew-cm';
                break;
            case 'company-approval' : $tempfor = 'cm-ap';
                break;
            case 'company-rejecting' : $tempfor = 'cm-rj';
                break;
            case 'job-new' : $tempfor = 'ew-ob';
                break;
            case 'job-new-employer' : $tempfor = 'ew-ob-em';
                break;
            case 'job-approval' : $tempfor = 'ob-ap';
                break;
            case 'job-rejecting' : $tempfor = 'ob-rj';
                break;
            case 'resume-new' : $tempfor = 'ew-rm';
                break;
            case 'message-email' : $tempfor = 'ms-sy';
                break;
            case 'resume-approval' : $tempfor = 'rm-ap';
                break;
            case 'resume-rejecting' : $tempfor = 'rm-rj';
                break;
            case 'applied-resume_status' : $tempfor = 'ap-rs';
                break;
            case 'jobapply-jobapply' : $tempfor = 'ba-ja';
                break;
            case 'department-new' : $tempfor = 'ew-md';
                break;
            case 'employer-buypackage' : $tempfor = 'ew-rp';
                break;
            case 'jobseeker-buypackage' : $tempfor = 'ew-js';
                break;
            case 'job-alert' : $tempfor = 'jb-at';
                break;
            case 'job-alert-visitor' : $tempfor = 'jb-at-vis';
                break;
            case 'job-to-friend' : $tempfor = 'jb-to-fri';
                break;
            case 'jobseeker-packagepurchase' : $tempfor = 'jb-pkg-pur';
                break;
            case 'employer-packagepurchase' : $tempfor = 'emp-pkg-pur';
                break;
            case 'company-delete' : $tempfor = 'cm-dl';
                break;
            case 'job-delete' : $tempfor = 'ob-dl';
                break;
            case 'resume-delete' : $tempfor = 'rm-dl';
                break;
            case 'jobapply-jobseeker' : $tempfor = 'js-ja';
                break;
            case 'resume-new-vis' : $tempfor = 'ew-rm-vis';
                break;
            case 'erase-user-data': $tempfor = 'u-da-de';
                break;
            case 'erase-date-request-receive': $tempfor = 'u-da-re';
                break;
        }
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'emailtemplate','message');
            $link = 'index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=' . $tempfor;
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'emailtemplate','error');
            $link = 'index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=' . $tempfor;
            $this->setRedirect($link);
        }else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'emailtemplate','error');
            $link = 'index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=' . $tempfor;
        }
        $this->setRedirect($link);
    }

    function updateemailoption(){
        $emailfor = Factory::getApplication()->input->get('emailfor');
        $for = Factory::getApplication()->input->get('for');
        $emailtemplate_model = $this->getmodel('Emailtemplate', 'JSJobsModel');
        $return_value = $emailtemplate_model->updateEmailTemplateOption($emailfor , $for);
        $link = 'index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplateoptions';
        $this->setRedirect($link);
    }


    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'emailtemplate');
        $layoutName = Factory::getApplication()->input->get('layout', 'emailtemplate');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $emailtemplate_model = $this->getModel('Emailtemplate', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($emailtemplate_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
