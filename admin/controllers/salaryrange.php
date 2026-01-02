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

class JSJobsControllerSalaryrange extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobsalaryrange() {
        Factory::getApplication()->input->set('layout', 'formsalaryrange');
        Factory::getApplication()->input->set('view', 'salaryrange');
        Factory::getApplication()->input->set('c', 'salaryrange');
        $this->display();
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'salaryrange','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange');
    }

    function remove() {
        $salaryrange_model = $this->getmodel('Salaryrange', 'JSJobsModel');
        $returnvalue = $salaryrange_model->deleteSalaryRange();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'salaryrange','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'salaryrange','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange');
    }

    function savejobsalaryrange() {
        $redirect = $this->savesalaryrange('saveclose');
    }

    function savejobsalaryrangesave() {
        $redirect = $this->savesalaryrange('save');
    }

    function savejobsalaryrangeandnew() {
        $redirect = $this->savesalaryrange('saveandnew');
    }

    function savesalaryrange($callfrom) {
        $salaryrange_model = $this->getmodel('Salaryrange', 'JSJobsModel');
        $return_value = $salaryrange_model->storeSalaryRange();
        $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'salaryrange','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'salaryrange','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'salaryrange','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'salaryrange','warning');
                    $this->setRedirect($link);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                    JSJOBSActionMessages::setMessage(SAVED, 'salaryrange','message');
                    $this->setRedirect($link);
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                    JSJOBSActionMessages::setMessage(SAVED, 'salaryrange','message');
                    $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange';
                    $this->setRedirect($link);
                } else if ($return_value[1] == 2) {
                    JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'salaryrange','error');
                    Factory::getApplication()->input->set('view', 'salaryrange');
                    Factory::getApplication()->input->set('hidemainmenu', 1);
                    Factory::getApplication()->input->set('layout', 'formsalaryrange');
                    // Display based on the set variables
                    $this->display();
                }
            }
            if ($redirect == 1) {
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=formsalaryrange&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=formsalaryrange';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'salaryrange','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'salaryrange','notice');
                Factory::getApplication()->input->set('view', 'salaryrange');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formsalaryrange');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=formsalaryrange&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            }else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'salaryrange','error');
                $link = 'index.php?option=com_jsjobs&c=salaryrange&view=salaryrange&layout=salaryrange';
                $this->setRedirect($link);
            }
        }
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'salaryrange');
        $layoutName = Factory::getApplication()->input->get('layout', 'salaryrange');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $salaryrange_model = $this->getModel('Salaryrange', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($salaryrange_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
