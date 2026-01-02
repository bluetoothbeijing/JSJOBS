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

class JSJobsControllerUser extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'user','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=payment_report');
    }
    function getuserlistajax() {

        $job_model = $this->getmodel('user', 'JSJobsModel');
        $return_data = $job_model->getUserListAjax();
        echo $return_data;
        Factory::getApplication()->close();
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'user');
        $layoutName = Factory::getApplication()->input->get('layout', 'user');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $user_model = $this->getModel('User', 'JSJobsModel');
        $resume_model = $this->getModel('Resume', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($user_model);
        $view->setModel($resume_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
