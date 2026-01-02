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

class JSJobsControllerCity extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobcity() {
        Factory::getApplication()->input->set('layout', 'formcity');
        Factory::getApplication()->input->set('view', 'city');
        Factory::getApplication()->input->set('c', 'city');
        $this->display();
    }

    function deletecity() {
        $session = Factory::getApplication()->getSession();
        $countryid = $session->get('countryid');
        $stateid = $session->get('stateid');
        $city_model = $this->getmodel('City', 'JSJobsModel');
        $return_value = $city_model->deleteCity();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'city','message');

        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'city','error');
        }
        $link = 'index.php?option=com_jsjobs&c=city&view=city&layout=cities&ct=' . $countryid . '&sd=' . $stateid;
        $this->setRedirect($link, $msg);
    }

    function publishcities() {
        $session = Factory::getApplication()->getSession();
        $country = $session->get('countryid');
        $stateid = $session->get('stateid');
        $city_model = $this->getmodel('City', 'JSJobsModel');
        $return_value = $city_model->publishcities();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(PUBLISHED, 'city','message');
        } else {
            JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'city','error');
        }

        $link = 'index.php?option=com_jsjobs&c=city&view=city&layout=cities&sd=' . $stateid . '&ct=' . $country;
        $this->setRedirect($link);
    }

    function unpublishcities() {
        $session = Factory::getApplication()->getSession();
        $country = $session->get('countryid');
        $stateid = $session->get('stateid');
        $city_model = $this->getmodel('City', 'JSJobsModel');
        $return_value = $city_model->unpublishcities();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'city','message');
        } else {
            JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'city','error');
        }

        $link = 'index.php?option=com_jsjobs&c=city&view=city&layout=cities&sd=' . $stateid . '&ct=' . $country;
        $this->setRedirect($link);
    }

    function savecity() {
        $session = Factory::getApplication()->getSession();
        $countryid = $session->get('countryid');
        $stateid = $session->get('stateid');
        $data = Factory::getApplication()->input->post->getArray();
        if ($data['stateid'])
            $stateid = $data['stateid'];

        $city_model = $this->getmodel('City', 'JSJobsModel');
        $return_value = $city_model->storeCity($countryid, $stateid);
        $link = 'index.php?option=com_jsjobs&c=city&view=city&layout=cities&ct=' . $countryid . '&sd=' . $stateid;
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'city','message');
                if ($return_value['rejected_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'city','warning');
                if ($return_value['authentication_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'city','warning');
                if ($return_value['server_responce'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'city','warning');
                $this->setRedirect($link);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'city','message');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'city','message');
                $this->setRedirect($link);
            } elseif ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'city','message');
                Factory::getApplication()->input->set('view', 'city');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formcity');
                $this->display();
            }elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
                $link = 'index.php?option=com_jsjobs&c=city&view=city&layout=formcity'.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            } else {
                JSJOBSActionMessages::setMessage(DELETE_ERROR, 'city','message');
                $this->setRedirect($link);
            }
        }
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'city','notice');
        $session = Factory::getApplication()->getSession();
        $countrycode = $session->get('countryid');
        $this->setRedirect('index.php?option=com_jsjobs&c=city&view=city&layout=cities&ct=' . $countrycode);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'city');
        $layoutName = Factory::getApplication()->input->get('layout', 'city');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $city_model = $this->getModel('City', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($city_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
