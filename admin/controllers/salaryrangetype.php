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

class JSJobsControllerSalaryrangetype extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobsalaryrangrtype() {
        Factory::getApplication()->input->set('layout', 'formsalaryrangetype');
        Factory::getApplication()->input->set('view', 'salaryrangetype');
        Factory::getApplication()->input->set('c', 'salaryrangetype');
        $this->display();
    }

    function savejobsalaryrangetype() {
        $redirect = $this->savesalaryrangetype('saveclose');
    }

    function savejobsalaryrangetypesave() {
        $redirect = $this->savesalaryrangetype('save');
    }

    function savejobsalaryrangetypeandnew() {
        $redirect = $this->savesalaryrangetype('saveandnew');
    }

    function savesalaryrangetype($callfrom) {
        $salaryrangetype_model = $this->getmodel('Salaryrangetype', 'JSJobsModel');
        $return_value = $salaryrangetype_model->storeSalaryRangeType();
        $link = 'index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=salaryrangetype';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'salaryrangetype','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'salaryrangetype','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'salaryrangetype','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'salaryrangetype','warning');
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
                JSJOBSActionMessages::setMessage(SAVED, 'salaryrangetype','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=salaryrangetype';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=formsalaryrangetype&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=formsalaryrangetype';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'salaryrangetype','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'salaryrangetype','notice');
                Factory::getApplication()->input->set('view', 'salaryrangetype');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formsalaryrangetype');
                $this->display();
            }elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=formsalaryrangetype&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'salaryrangetype','error');
                $this->setRedirect($link);
            }
        }
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'salaryrangetype','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=salaryrangetype');
    }

    function remove() {
        $salaryrangetype_model = $this->getmodel('Salaryrangetype', 'JSJobsModel');
        $returnvalue = $salaryrangetype_model->deleteSalaryRangeType();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'salaryrangetype','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'salaryrangetype','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=salaryrangetype&view=salaryrangetype&layout=salaryrangetype');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'salaryrangetype');
        $layoutName = Factory::getApplication()->input->get('layout', 'salaryrangetype');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $salaryrangetype_model = $this->getModel('Salaryrangetype', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($salaryrangetype_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
