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

class JSJobsControllerCountry extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobcountry() {
        Factory::getApplication()->input->set('layout', 'formcountry');
        Factory::getApplication()->input->set('view', 'country');
        Factory::getApplication()->input->set('c', 'country');
        $this->display();
    }

    function deletecountry() {
        $country_model = $this->getmodel('Country', 'JSJobsModel');
        $return_value = $country_model->deleteCountry();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'country','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'country','error');
        }
        $link = 'index.php?option=com_jsjobs&c=country&view=country&layout=countries';
        $this->setRedirect($link);
    }

    function publishcountries() {
        $country_model = $this->getmodel('Country', 'JSJobsModel');
        $return_value = $country_model->publishcountries();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(PUBLISHED, 'country','message');
        } else {
            JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'country','error');
        }
        $link = 'index.php?option=com_jsjobs&c=country&view=country&layout=countries';
        $this->setRedirect($link);
    }

    function unpublishcountries() {
        $country_model = $this->getmodel('Country', 'JSJobsModel');
        $return_value = $country_model->unpublishcountries();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'country','message');
        } else {
            JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'country','message');
        }
        $link = 'index.php?option=com_jsjobs&c=country&view=country&layout=countries';
        $this->setRedirect($link);
    }

    function savecountry() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $data = Factory::getApplication()->input->post->getArray();
        $country_model = $this->getmodel('Country', 'JSJobsModel');
        $return_value = $country_model->storeCountry();
        $link = 'index.php?option=com_jsjobs&c=country&view=country&layout=countries';
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'country','message');
                if ($return_value['rejected_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'country','warning');
                if ($return_value['authentication_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'country','warning');
                if ($return_value['server_responce'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'country','warning');
                $this->setRedirect($link);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'country','message');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'country','message');
                $this->setRedirect($link);
            } elseif ($return_value == 3) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'country','error');
                Factory::getApplication()->input->set('view', 'country');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formcountry');
                $this->display();
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'country','error');
                $link = 'index.php?option=com_jsjobs&c=country&view=country&layout=formcountry'.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            }else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'country','error');
                $this->setRedirect($link);
            }
        }
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'country','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=country&view=country&layout=countries');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'country');
        $layoutName = Factory::getApplication()->input->get('layout', 'country');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $country_model = $this->getModel('Country', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($country_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
