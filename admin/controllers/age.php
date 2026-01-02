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

jimport('joomla.application.component.controller');

class JSJobsControllerAge extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobage() {
        Factory::getApplication()->input->set('layout', 'formages');
        Factory::getApplication()->input->set('view', 'age');
        Factory::getApplication()->input->set('c', 'age');
        $this->display();
    }

    function savejobage() {
        $redirect = $this->saveages('saveclose');
    }

    function savejobagesave() {
        $redirect = $this->saveages('save');
    }

    function savejobageandnew() {
        $redirect = $this->saveages('saveandnew');
    }

    function saveages($callfrom) {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));   
        $age_model = $this->getmodel('Age', 'JSJobsModel');
        $return_value = $age_model->storeAges();
        $link = 'index.php?option=com_jsjobs&c=age&view=age&layout=ages';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'age','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'age','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'age','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'age','warning');
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
                    $link = 'index.php?option=com_jsjobs&c=age&view=age&layout=ages';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=age&view=age&layout=formages&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=age&view=age&layout=formages';
                }
                JSJOBSActionMessages::setMessage(SAVED, 'age','message');
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'age','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'age','warning');
                Factory::getApplication()->input->set('view', 'age');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formages');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'age','error');
                $link = 'index.php?option=com_jsjobs&c=age&view=age&layout=formages&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'age','error');
                $this->setRedirect($link);
            }
        }
    }

    function remove() {
        $age_model = $this->getmodel('Age', 'JSJobsModel');
        $returnvalue = $age_model->deleteAge();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'age','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'age','error');
        $this->setRedirect('index.php?option=com_jsjobs&c=age&view=age&layout=ages');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'age','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=age&view=age&layout=ages');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'age');
        $layoutName = Factory::getApplication()->input->get('layout', 'ages');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $age_model = $this->getModel('Age', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($age_model);
        $view->setLayout($layoutName);
        $view->display();
    }
}
?>
