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

class JSJobsControllerHighesteducation extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobhighesteducation() {
        Factory::getApplication()->input->set('layout', 'formhighesteducation');
        Factory::getApplication()->input->set('view', 'highesteducation');
        Factory::getApplication()->input->set('c', 'highesteducation');
        $this->display();
    }

    function savejobhighesteducation() {
        $redirect = $this->savehighesteducation('saveclose');
    }

    function savejobhighesteducationsave() {
        $redirect = $this->savehighesteducation('save');
    }

    function savejobhighesteducationandnew() {
        $redirect = $this->savehighesteducation('saveandnew');
    }

    function savehighesteducation($callfrom) {
        $highesteducation_model = $this->getmodel('Highesteducation', 'JSJobsModel');
        $return_value = $highesteducation_model->storeHighestEducation();
        $link = 'index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=highesteducations';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'highesteducation','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'highesteducation','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'highesteducation','error');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'highesteducation','warning');
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
                JSJOBSActionMessages::setMessage(SAVED, 'highesteducation','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=highesteducations';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=formhighesteducation&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=formhighesteducation';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'highesteducation','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'highesteducation','notice');
                Factory::getApplication()->input->set('view', 'highesteducation');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formhighesteducation');
                $this->display();
            }elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=formhighesteducation&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'highesteducation','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $highesteducation_model = $this->getmodel('Highesteducation', 'JSJobsModel');
        $returnvalue = $highesteducation_model->deleteHighestEducation();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'highesteducation','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'highesteducation','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=highesteducations');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'highesteducation','message');
        $this->setRedirect('index.php?option=com_jsjobs&c=highesteducation&view=highesteducation&layout=highesteducations');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'highesteducation');
        $layoutName = Factory::getApplication()->input->get('layout', 'highesteducation');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $highesteducation_model = $this->getModel('Highesteducation', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model, true);
        $view->setModel($highesteducation_model, true);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
