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

class JSJobsControllerCareerlevel extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editcareerlevels() {
        Factory::getApplication()->input->set('layout', 'formcareerlevels');
        Factory::getApplication()->input->set('view', 'careerlevel');
        Factory::getApplication()->input->set('c', 'careerlevel');
        $this->display();
    }

    function savejobcareerlevel() {
        $redirect = $this->savecareerlevel('saveclose');
    }

    function savejobcareerlevelsave() {
        $redirect = $this->savecareerlevel('save');
    }

    function savejobcareerlevelandnew() {
        $redirect = $this->savecareerlevel('saveandnew');
    }

    function savecareerlevel($callfrom) {
        $careerlevel_model = $this->getmodel('Careerlevel', 'JSJobsModel');
        $return_value = $careerlevel_model->storeCareerLevel();
        $link = 'index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=careerlevels';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'careerlevel','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'careerlevel','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'careerlevel','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'careerlevel','warning');
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
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=careerlevels';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=formcareerlevels&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=formcareerlevels';
                }
                JSJOBSActionMessages::setMessage(SAVED, 'careerlevel','message');
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'careerlevel','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'careerlevel','warning');
                Factory::getApplication()->input->set('view', 'careerlevel');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formcareerlevels');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'careerlevel','error');
                $link = 'index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=formcareerlevels&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'careerlevel','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $careerlevel_model = $this->getmodel('Careerlevel', 'JSJobsModel');
        $returnvalue = $careerlevel_model->deleteCareerLevel();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'careerlevel','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'careerlevel','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=careerlevels');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'careerlevel','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=careerlevel&view=careerlevel&layout=careerlevels');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'careerlevel');
        $layoutName = Factory::getApplication()->input->get('layout', 'careerlevel');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $careerlevel_model = $this->getModel('Careerlevel', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($careerlevel_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
