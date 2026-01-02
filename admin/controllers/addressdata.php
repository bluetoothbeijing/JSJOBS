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

class JSJobsControllerAddressdata extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function loadaddressdata() {
        $data = Factory::getApplication()->input->post->getArray();
        $addressdata_model = $this->getmodel('Addressdata', 'JSJobsModel');
        $return_value = $addressdata_model->loadAddressData();
        $link = 'index.php?option=com_jsjobs&c=addressdata&view=addressdata&layout=loadaddressdata&er=2';
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'addressdata','message');
            $link = 'index.php?option=com_jsjobs&c=addressdata&view=addressdata&layout=loadaddressdata';
        } elseif ($return_value == 3) { // file mismatch
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'addressdata','error');
        } elseif ($return_value == 3) { // file mismatch
            JSJOBSActionMessages::setMessage(SAVED, 'resume','message');
            JSJOBSActionMessages::setMessage(FILE_TYPE_ERROR, 'resume','warning');            
        } elseif ($return_value == 5) { // state alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'state','warning');
        } elseif ($return_value == 8) { // county alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'county','warning');
        } elseif ($return_value == 11) { // state and county alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'stateandcounty','warning');
        } elseif ($return_value == 7) { // city alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'city','warning');
        } elseif ($return_value == 6) { // state and city alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'stateandcity','warning');
        } elseif ($return_value == 9) { // county and city alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'countyandcity','warning');
        } elseif ($return_value == 12) { // state, counnty and city alredy exist 
            JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'statecountyandcity','warning');
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'addressdata','error');
        }
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'addressdata');
        $layoutName = Factory::getApplication()->input->get('layout', 'loadaddressdata');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
