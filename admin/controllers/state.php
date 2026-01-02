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

class JSJobsControllerState extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobstate() {
        Factory::getApplication()->input->set('layout', 'formstate');
        Factory::getApplication()->input->set('view', 'state');
        Factory::getApplication()->input->set('c', 'state');
        $this->display();
    }

    function deletestate() {
        $session = Factory::getApplication()->getSession();
        $countryid = $session->get('countryid');
        $state_model = $this->getmodel('State', 'JSJobsModel');
        $return_value = $state_model->deleteState();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'state','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'state','error');
        }
        $link = 'index.php?option=com_jsjobs&c=state&view=state&layout=states&ct=' . $countryid;
        $this->setRedirect($link);
    }

    function publishstates() {
        $ct = Factory::getApplication()->input->get('ct');
        $state_model = $this->getmodel('State', 'JSJobsModel');
        $return_value = $state_model->publishstates();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(PUBLISHED, 'state','message');
        } else {
            JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'state','error');
        }

        $link = 'index.php?option=com_jsjobs&c=state&view=state&layout=states&ct=' . $ct;
        $this->setRedirect($link);
    }

    function unpublishstates() {
        $ct = Factory::getApplication()->input->get('ct');
        $state_model = $this->getmodel('State', 'JSJobsModel');
        $return_value = $state_model->unpublishstates();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'state','message');
        } else {
            JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'state','error');
        }

        $link = 'index.php?option=com_jsjobs&c=state&view=state&layout=states&ct=' . $ct;
        $this->setRedirect($link);
    }

    function savestate() {
        $data = Factory::getApplication()->input->post->getArray();
        $session = Factory::getApplication()->getSession();
        $countryid = $session->get('countryid');

        $state_model = $this->getmodel('State', 'JSJobsModel');
        $return_value = $state_model->storeState($countryid);
        $link = 'index.php?option=com_jsjobs&c=state&view=state&layout=states&ct=' . $countryid;
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'state','message');
                if ($return_value['rejected_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'state','warning');
                if ($return_value['authentication_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'state','warning');
                if ($return_value['server_responce'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'state','warning');
                $this->setRedirect($link);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'state','message');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'state','message');
                $this->setRedirect($link);
            } elseif ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'state','notice');
                Factory::getApplication()->input->set('view', 'state');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formstate');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=state&view=state&layout=formstate&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'state','error');
                $this->setRedirect($link);
            }
        }
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'state','notice');
        $session = Factory::getApplication()->getSession();
        $var = $session->get('js_countrycode');
        if(!empty($var))
            $countrycode = $var;
        $this->setRedirect('index.php?option=com_jsjobs&c=state&view=state&layout=states&ct=' . $countrycode);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'state');
        $layoutName = Factory::getApplication()->input->get('layout', 'state');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $state_model = $this->getModel('State', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($state_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
