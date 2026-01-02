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

class JSJobsControllerShift extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function edijobshift() {
        Factory::getApplication()->input->set('layout', 'formshift');
        Factory::getApplication()->input->set('view', 'shift');
        Factory::getApplication()->input->set('c', 'shift');
        $this->display();
    }

    function savejobshift() {
        $redirect = $this->saveshift('saveclose');
    }

    function savejobshiftsave() {
        $redirect = $this->saveshift('save');
    }

    function savejobshiftandnew() {
        $redirect = $this->saveshift('saveandnew');
    }

    function saveshift($callfrom) {
        $shift_model = $this->getmodel('Shift', 'JSJobsModel');
        $return_value = $shift_model->storeShift();
        $link = 'index.php?option=com_jsjobs&c=shift&view=shift&layout=shifts';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'shift','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'shift','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'shift','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'shift','warning');
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
                JSJOBSActionMessages::setMessage(SAVED, 'shift','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=shift&view=shift&layout=shifts';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=shift&view=shift&layout=formshift&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=shift&view=shift&layout=formshift';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'shift','message');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'shift','notice');
                Factory::getApplication()->input->set('view', 'shift');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formshift');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=shift&view=shift&layout=formshift&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'shift','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $shift_model = $this->getmodel('Shift', 'JSJobsModel');
        $returnvalue = $shift_model->deleteShift();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'shift','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'shift','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=shift&view=shift&layout=shifts');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'shift','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=shift&view=shift&layout=shifts');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'shift');
        $layoutName = Factory::getApplication()->input->get('layout', 'shift');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $shift_model = $this->getModel('Shift', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($shift_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
