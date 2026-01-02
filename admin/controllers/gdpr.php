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
defined('_JEXEC') or die('Not Allowed');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.controller');

class JSJobsControllerGdpr extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function eraseidentifyinguserdata() {
        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        $uid  = Factory::getApplication()->input->get('id');
        $gdpr = $this->getModel('gdpr', 'JSJobsModel');
        $result = $gdpr->anonymizeUserData($uid);
        if($result){
            $msg = JSJOBSActionMessages::setMessage(ERASED, 'userdataerase','message');    
        }else{
            $msg = JSJOBSActionMessages::setMessage(ERASE_ERROR, 'userdataerase','message');
        }
        $link = 'index.php?option=com_jsjobs&c=gdpr&layout=erasedatarequests';
        $this->setRedirect($link, $msg);
    }

    function deleteuserdata() {
        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        $uid  = Factory::getApplication()->input->get('id');
        $gdpr = $this->getModel('gdpr', 'JSJobsModel');
        $result = $gdpr->deleteUserData($uid);
        $link = 'index.php?option=com_jsjobs&c=gdpr&layout=erasedatarequests';
        $msg = JSJOBSActionMessages::setMessage(DELETED, 'userdataerase','message');
        $this->setRedirect($link, $msg);
    }

    function savegdprfield() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $fieldordering_model = $this->getmodel('fieldordering', 'JSJobsModel');
        $data = Factory::getApplication()->input->post->getArray();
        if($data['termsandconditions_linktype'] == 1 && $data['termsandconditions_link'] == ""){
            $return_value = SAVE_ERROR;
        }elseif($data['termsandconditions_linktype'] == 2 && $data['termsandconditions_page'] == ""){
            $return_value = SAVE_ERROR;
        }else{
            $return_value = $fieldordering_model->storeUserField();    
        }
        if(is_array($return_value)){
            $return_value = SAVED;
        }else{
            $return_value = SAVE_ERROR;
        }
        $link = 'index.php?option=com_jsjobs&c=gdpr&layout=gdprfields';
        $msg = JSJOBSActionMessages::setMessage($return_value, 'gdpr','message');
        $this->setRedirect($link, $msg);
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'addgdprfield');
        Factory::getApplication()->input->set('view', 'gdpr');
        Factory::getApplication()->input->set('c', 'gdpr');
        $this->display();
    }

    function remove() {
        $fieldordering_model = $this->getmodel('fieldordering', 'JSJobsModel');
        $id = Factory::getApplication()->input->get('cid');
        $returnvalue = $fieldordering_model->deleteUserField($id);
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'gdpr','message');
        } elseif($returnvalue == DELETE_ERROR) {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'gdpr','error');
        }else{
            JSJOBSActionMessages::setMessage($returnvalue, 'gdpr','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=gdprfields');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'gdpr');
        $layoutName = Factory::getApplication()->input->get('layout', 'erasedatarequests');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $department_model = $this->getModel('Gdpr', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($department_model);
        $view->setLayout($layoutName);
        $view->display();
    }
}
?>
