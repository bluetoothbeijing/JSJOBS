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

class JSJobsControllerExperience extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobexperience() {
        Factory::getApplication()->input->set('layout', 'formexperience');
        Factory::getApplication()->input->set('view', 'experience');
        Factory::getApplication()->input->set('c', 'experience');
        $this->display();
    }

    function savejobexperience() {
        $redirect = $this->saveexperience('saveclose');
    }

    function savejobexperiencesave() {
        $redirect = $this->saveexperience('save');
    }

    function savejobexperienceandnew() {
        $redirect = $this->saveexperience('saveandnew');
    }

    function saveexperience($callfrom) {
        $experience_model = $this->getmodel('Experience', 'JSJobsModel');
        $return_value = $experience_model->storeExperience();
        $link = 'index.php?option=com_jsjobs&c=experience&view=experience&layout=experience';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'experience','message');

                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'experience','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'experience','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'experience','warning');
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
                JSJOBSActionMessages::setMessage(SAVED, 'experience','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=experience&view=experience&layout=experience';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=experience&view=experience&layout=formexperience&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=experience&view=experience&layout=formexperience';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                    JSJOBSActionMessages::setMessage(SAVE_ERROR, 'experience','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'experience','error');
                Factory::getApplication()->input->set('view', 'experience');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formexperience');
                $this->display();
            }elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'experience','error');
                $link = 'index.php?option=com_jsjobs&c=experience&view=experience&layout=formexperience&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'experience','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $experience_model = $this->getmodel('Experience', 'JSJobsModel');
        $returnvalue = $experience_model->deleteExperience();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'experience','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'experience','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=experience&view=experience&layout=experience');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'experience','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=experience&view=experience&layout=experience');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'experience');
        $layoutName = Factory::getApplication()->input->get('layout', 'experience');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $experience_model = $this->getModel('Experience', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($experience_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
