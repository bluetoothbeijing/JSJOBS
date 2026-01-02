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

class JSJobsControllerCurrency extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function editjobcurrency() {
        Factory::getApplication()->input->set('layout', 'formcurrency');
        Factory::getApplication()->input->set('view', 'currency');
        Factory::getApplication()->input->set('c', 'currency');
        $this->display();
    }

    function savejobcurrency() {
        $redirect = $this->savecurrency('saveclose');
    }

    function savejobcurrencysave() {
        $redirect = $this->savecurrency('save');
    }

    function savejobcurrencyandnew() {
        $redirect = $this->savecurrency('saveandnew');
    }

    function savecurrency($callfrom) {
        $currency_model = $this->getmodel('Currency', 'JSJobsModel');
        $return_value = $currency_model->storeCurrency();
        $link = 'index.php?option=com_jsjobs&c=currency&view=currency&layout=currency';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    JSJOBSActionMessages::setMessage(SAVED, 'currency','message');
                    if ($return_value['rejected_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'currency','warning');
                    if ($return_value['authentication_value'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'currency','warning');
                    if ($return_value['server_responce'] != "")
                        JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'currency','warning');
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
                JSJOBSActionMessages::setMessage(SAVED, 'currency','message');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&c=currency&view=currency&layout=currency';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&c=currency&view=currency&layout=formcurrency&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&c=currency&view=currency&layout=formcurrency';
                }
                $this->setRedirect($link);
            } elseif ($return_value == false) {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'currency','error');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'currency','error');
                Factory::getApplication()->input->set('view', 'currency');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formcurrency');
                $this->display();
            }  elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'currency','error');
                $link = 'index.php?option=com_jsjobs&c=currency&view=currency&layout=formcurrency&cid[]='.Factory::getApplication()->input->get('id');
                $this->setRedirect($link);
            }else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'currency','error');
                $this->setRedirect($link);
            }
        }
    }

    function makedefaultcurrency() { // make default currency
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $defaultid = $cid[0];
        $currency_model = $this->getmodel('Currency', 'JSJobsModel');
        $return_value = $currency_model->makeDefaultCurrency($defaultid, 1);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SET_DEFAULT, 'currency','message');
        } else {
            JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'currency','error');
        }
        $link = 'index.php?option=com_jsjobs&c=currency&view=currency&layout=currency';
        $this->setRedirect($link);
    }

    function remove() {
        $currency_model = $this->getmodel('Currency', 'JSJobsModel');
        $returnvalue = $currency_model->deleteCurrency();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'currency','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'currency','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=currency&view=currency&layout=currency');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'currency','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=currency&view=currency&layout=currency');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'currency');
        $layoutName = Factory::getApplication()->input->get('layout', 'currency');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $currency_model = $this->getModel('Currency', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($currency_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
