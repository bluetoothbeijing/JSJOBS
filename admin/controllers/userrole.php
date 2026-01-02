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

class JSJobsControllerUserrole extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saverole() {
        $userrole_model = $this->getModel('Userrole', 'JSJobsModel');
        $return_value = $userrole_model->storeRole();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'userrole','message');
            $link = 'index.php?option=com_jsjobs&task=view&layout=roles';
            $this->setRedirect($link);
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
            $link = 'index.php?option=com_jsjobs&task=view&layout=roles';
            $this->setRedirect($link);
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'userrole','error');
            $link = 'index.php?option=com_jsjobs&task=view&layout=roles';
            $this->setRedirect($link);
        }
        $link = 'index.php?option=com_jsjobs&c=application&layout=roles';
    }

    function saveuserrole() {
        $userrole_model = $this->getModel('Userrole', 'JSJobsModel');
        $return_value = $userrole_model->storeUserRole();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'userrole','message');
            $link = 'index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users';
            $this->setRedirect($link);
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'job','error');
            $link = 'index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users';
            $this->setRedirect($link);
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'userrole','error');
            $link = 'index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users';
            $this->setRedirect($link);
        }
    }

    function listuserdataforpackage() {
        $val = Factory::getApplication()->input->get('val');
        $userrole_model = $this->getModel('Userrole', 'JSJobsModel');
        $returnvalue = $userrole_model->listUserDataForPackage($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'changerole');
        Factory::getApplication()->input->set('view', 'userrole');
        Factory::getApplication()->input->set('c', 'userrole');
        $this->display();
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'userrole','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'userrole');
        $layoutName = Factory::getApplication()->input->get('layout', 'userrole');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $userrole_model = $this->getModel('Userrole', 'JSJobsModel');
        $user_model = $this->getModel('User', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($userrole_model);
        $view->setModel($user_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
