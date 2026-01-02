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

class JSJobsControllerCompany extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function companyenforcedelete() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $companyid = $cid[0];
        $user = Factory::getUser();
        $uid = $user->id;
        $company_model = $this->getmodel('Company', 'JSJobsModel');
        $return_value = $company_model->companyEnforceDelete($companyid, $uid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'company','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'company','error');
            $msg = Text::_('Error in deleting company');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'company','error');
        }
        $layout = Factory::getApplication()->input->get('callfrom','companies');
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout='.$layout;
        $this->setRedirect($link);
    }

    function savecompany() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $company_model = $this->getmodel('Company', 'JSJobsModel');
        $return_value = $company_model->storeCompany();
        $layout = Factory::getApplication()->input->get('callfrom','companies');
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout='.$layout;
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'company','message');
        } elseif ($return_value == 6) {
            JSJOBSActionMessages::setMessage(SAVED, 'company','message');
            JSJOBSActionMessages::setMessage(FILE_TYPE_ERROR, 'company','warning');
        }  else if ($return_value == 5) {
            JSJOBSActionMessages::setMessage(FILE_SIZE_ERROR, 'company','warning');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'comapny','error');
            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=formcompany'.Factory::getApplication()->input->get('id');
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'company','error');
        }
        $this->setRedirect($link);
    }    

    function companyapprove() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $companyid = Factory::getApplication()->input->get('id');
        $company_model = $this->getmodel('Company', 'JSJobsModel');
        $return_value = $company_model->companyApprove($companyid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(APPROVED, 'company','message');
        } else
            JSJOBSActionMessages::setMessage(APPROVE_ERROR, 'company','error');
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=companiesqueue';
        $this->setRedirect($link);
    }

    function companyreject() {
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $companyid = Factory::getApplication()->input->get('id');
        $company_model = $this->getmodel('Company', 'JSJobsModel');
        $return_value = $company_model->companyReject($companyid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(REJECTED, 'company','message');
        } else
            JSJOBSActionMessages::setMessage(REJECT_ERROR, 'company','error');
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=companiesqueue';
        $this->setRedirect($link);
    }


    function remove() {
        $company_model = $this->getmodel('Company', 'JSJobsModel');
        $returnvalue = $company_model->deleteCompany();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'company','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'company','error');
        }
        $layout = Factory::getApplication()->input->get('callfrom','companies');
        $this->setRedirect('index.php?option=com_jsjobs&c=company&view=company&layout='.$layout);
        
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'company','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=company&view=company&layout=companies');
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'formcompany');
        Factory::getApplication()->input->set('view', 'company');
        Factory::getApplication()->input->set('c', 'company');
        $layout = Factory::getApplication()->input->get('callfrom','companies');
        Factory::getApplication()->input->set('callfrom', $layout);
        $this->display();
    }

    function postcompanyonjomsocial(){
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $companyid = Factory::getApplication()->input->get('id',0);
        $res = JSModel::getJSModel('company')->postCompanyOnJomSocial($companyid);
        if($res){
            JSJOBSActionMessages::setMessage("Company has been successfully posted on JomSocial", 'company','message');
        }else{
            JSJOBSActionMessages::setMessage("Company has not been posted on JomSocial", 'company','error');
        }
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=companies&Itemid=' . $Itemid;
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'company');
        $layoutName = Factory::getApplication()->input->get('layout', 'companies');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $company_model = $this->getModel('Company', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($company_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
