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
use Joomla\CMS\Router\Route;    

jimport('joomla.application.component.controller');

class JSJobsControllerDepartment extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }
        parent::__construct();
    }

    function savedepartment() { //save department
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string'); // wrong way to fetch this variable
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $department = $this->getmodel('Department', 'JSJobsModel');
        $return_value = $department->storeDepartment();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'department','message');
        } else if ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'department','error');
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'department','error');
        }
        $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=mydepartments&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function deletedepartment() { //delete department
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $departmentid = $common->parseId(Factory::getApplication()->input->get('pd', ''));
        $department = $this->getmodel('Department', 'JSJobsModel');
        $return_value = $department->deleteDepartment($departmentid, $uid);
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'department','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'department','error');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'department','error');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'department','error');
        }
        $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=mydepartments&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function listdepartments() {
        $val = Factory::getApplication()->input->get('val');
        $depatments = $this->getmodel('Department', 'JSJobsModel');
        $returnvalue = $depatments->listDepartments($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function display($cachable = false, $urlparams = false) { // correct employer controller display function manually.
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'default');
        $layoutName = Factory::getApplication()->input->get('layout', 'default');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
