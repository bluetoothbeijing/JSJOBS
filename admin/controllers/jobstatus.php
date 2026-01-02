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

class JSJobsControllerJobstatus extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function edijobstatus() {
        Factory::getApplication()->input->set('layout', 'formjobstatus');
        Factory::getApplication()->input->set('view', 'jobstatus');
        Factory::getApplication()->input->set('c', 'jobstatus');
        $this->display();
    }

    function savejobstatus() {
        $redirect = $this->storejobStatus('saveclose');
    }

    function savejobstatussave() {
        $redirect = $this->storejobStatus('save');
    }

    function savejobstatusandnew() {
        $redirect = $this->storejobStatus('saveandnew');
    }

    function storejobStatus($callfrom) {
        $jobstatus_model = $this->getmodel('Jobstatus', 'JSJobsModel');
        $return_value = $jobstatus_model->storeJobStatus();
        $link = 'index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=jobstatus';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'jobstatus','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'jobstatus','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'jobstatus','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'jobstatus','warning');
                    $this->setRedirect($link);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                }
            }
            if ($redirect == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'jobstatus','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=jobstatus';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=formjobstatus&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=formjobstatus';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'jobstatus','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'jobstatus','warning');
                Factory::getApplication()->input->set('view', 'jobstatus');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formjobstatus');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=formjobstatus&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            }else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'jobstatus','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $jobstatus_model = $this->getmodel('Jobstatus', 'JSJobsModel');
        $returnvalue = $jobstatus_model->deleteJobStatus();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'jobstatus','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'jobstatus','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=jobstatus');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'jobstatus','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=jobstatus&view=jobstatus&layout=jobstatus');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'jobstatus');
        $layoutName = Factory::getApplication()->input->get('layout', 'jobstatus');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $jobstatus_model = $this->getModel('Jobstatus', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($jobstatus_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
