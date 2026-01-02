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

class JSJobsControllerDepartment extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function departmentapprove() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $departmentid = $cid[0];
        $department_model = $this->getmodel('Department', 'JSJobsModel');
        $return_value = $department_model->departmentApprove($departmentid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(APPROVED, 'department','message');
        } else {
            JSJOBSActionMessages::setMessage(APPROVE_ERROR, 'department','error');
        }
        $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=departmentqueue';
        $this->setRedirect($link);
    }

    function departmentreject() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $departmentid = $cid[0];
        $department_model = $this->getmodel('Department', 'JSJobsModel');
        $return_value = $department_model->departmentReject($departmentid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(REJECTED, 'department','message');
        } else {
            JSJOBSActionMessages::setMessage(REJECT_ERROR, 'department','error');
        }
        $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=departmentqueue';
        $this->setRedirect($link);
    }

    function savedepatrment() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $department_model = $this->getmodel('Department', 'JSJobsModel');
        $return_value = $department_model->storeDepartment();
        $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=departments';
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'department','message');
        }elseif($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'department','error');
            $id = Factory::getApplication()->input->get('id');
            if($id) 
                $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment&cid[]='.Factory::getApplication()->input->get('id');
            else
                $link = 'index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment';
            $this->setRedirect($link);
        }else{
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'department','error');
        }
        $this->setRedirect($link);
    }

    function listdepartments() {
        $val = Factory::getApplication()->input->get('val');
        $department_model = $this->getmodel('Department', 'JSJobsModel');
        $returnvalue = $department_model->listDepartments($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'formdepartment');
        Factory::getApplication()->input->set('view', 'department');
        Factory::getApplication()->input->set('c', 'department');
        $this->display();
    }

    function remove() {
        $department_model = $this->getmodel('Department', 'JSJobsModel');
        $returnvalue = $department_model->deleteDepartment();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'department','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'department','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=department&view=department&layout=departments');
    }

    function cancel() {
        $companyid = Factory::getApplication()->input->get('md');
        if($companyid){
            $companyid = '&md='.$companyid;
        }
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'department','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=department&view=department&layout=departments'.$companyid);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'department');
        $layoutName = Factory::getApplication()->input->get('layout', 'department');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $department_model = $this->getModel('Department', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($department_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
