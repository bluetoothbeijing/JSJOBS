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

class JSJobsControllerCompany extends JSController {

    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }
        parent::__construct();
    }

    function savecompany() { //save company
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $company = $this->getmodel('Company', 'JSJobsModel');
        $return_value = $company->storeCompany();
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=' . $Itemid;
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'company','message');
        } else if ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'company','error');
            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&Itemid=' . $Itemid;
        } else if ($return_value == 6) {
            JSJOBSActionMessages::setMessage(FILE_TYPE_ERROR, 'company','warning');
        } else if ($return_value == 5) {
            JSJOBSActionMessages::setMessage(FILE_SIZE_ERROR, 'company','warning');
            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&Itemid=' . $Itemid;
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'company','error');
            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&Itemid=' . $Itemid;
        }
        $this->setRedirect(Route::_($link , false));
    }

    function deletecompany() { //delete company
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $companyid = $common->parseId(Factory::getApplication()->input->get('cd', ''));
        $company = $this->getmodel('Company', 'JSJobsModel');
        $return_value = $company->deleteCompany($companyid, $uid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'company','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'company','error');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'company','error');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'company','error');
        }
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function postcompanyonjomsocial(){
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $isallowed = JSModel::getJSModel('configurations')->getConfigValue('jomsocial_allowpostcompany');
        if($isallowed){            
            $companyid = Factory::getApplication()->input->get('id',0);
            $res = JSModel::getJSModel('company')->postCompanyOnJomSocial($companyid);
            if($res){
                JSJOBSActionMessages::setMessage("Company has been successfully posted on JomSocial", 'company','message');
            }else{
                JSJOBSActionMessages::setMessage("Company has not been posted on JomSocial", 'company','error');
            }
        }
        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=' . $Itemid;
        $this->setRedirect($link);
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
    
