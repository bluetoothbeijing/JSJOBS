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

class JSJobsControllerJobtype extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobtype() {
        Factory::getApplication()->input->set('layout', 'formjobtype');
        Factory::getApplication()->input->set('view', 'jobtype');
        Factory::getApplication()->input->set('c', 'jobtype');
        $this->display();
    }

    function savejobtype() {
        $redirect = $this->storejobtype('saveclose');
    }

    function savejobtypesave() {
        $redirect = $this->storejobtype('save');
    }

    function savejobtypeandnew() {
        $redirect = $this->storejobtype('saveandnew');
    }

    function storejobtype($callfrom) {
        $jobtype_model = $this->getmodel('Jobtype', 'JSJobsModel');
        $return_value = $jobtype_model->storeJobType();
        $link = 'index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=jobtypes';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'jobtype','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'jobtype','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'jobtype','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'jobtype','warning');
                    $this->setRedirect($link);
                }elseif ($return_value == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                }
            }
            if ($redirect == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'jobtype','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=jobtypes';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=formjobtype&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=formjobtype';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'jobtype','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'jobtype','message');
                Factory::getApplication()->input->set('view', 'jobtype');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formjobtype');
                $this->display();
            }  elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=formjobtype&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            }else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'jobtype','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $jobtype_model = $this->getmodel('Jobtype', 'JSJobsModel');
        $returnvalue = $jobtype_model->deleteJobType();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'jobtype','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'jobtype','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=jobtypes');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'jobtype','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=jobtype&view=jobtype&layout=jobtypes');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'jobtype');
        $layoutName = Factory::getApplication()->input->get('layout', 'jobtype');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $view->setModel($configuration_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
