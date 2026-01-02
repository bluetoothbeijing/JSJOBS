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

class JSJobsControllerEmployerpackages extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saveemployerpackage() {
        $employerpackages_model = $this->getmodel('Employerpackages', 'JSJobsModel');
        $return_value = $employerpackages_model->storeEmployerPackage();
        $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages';
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'employerpackages','message');
        }elseif($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'employerpackages','error');
            $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=formemployerpackage&cid[]='.Factory::getApplication()->input->get('id');
            $this->setRedirect($link);
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'employerpackages','error');
        }
        $this->setRedirect($link);
    }

    function remove() {
        $employerpackages_model = $this->getmodel('Employerpackages', 'JSJobsModel');
        $returnvalue = $employerpackages_model->deleteEmployerPackage();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'employerpackages','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'employerpackages','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'employerpackages','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages');
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'formemployerpackage');
        Factory::getApplication()->input->set('view', 'employerpackages');
        Factory::getApplication()->input->set('c', 'employerpackages');
        $this->display();
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'employerpackages');
        $layoutName = Factory::getApplication()->input->get('layout', 'employerpackages');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $employerpackages_model = $this->getModel('Employerpackages', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($employerpackages_model);

        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
