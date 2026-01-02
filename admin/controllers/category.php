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

class JSJobsControllerCategory extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function deletecategoryandsubcategory() {       // delete category and subcategory
        $category_model = $this->getmodel('Category', 'JSJobsModel');
        $returnvalue = $category_model->deleteCategoryAndSubcategory();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'categoryandsubcategory','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'categoryandsubcategory','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=category&view=category&layout=categories');
    }

    function publishcategories() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $id = $cid[0];
        $category_model = $this->getmodel('Category', 'JSJobsModel');
        $return_value = $category_model->categoryChangeStatus($id, 1);
        if ($return_value == 1)
            JSJOBSActionMessages::setMessage(PUBLISH, 'category','message');
        else
            JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'category','error');

        $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=categories';
        $this->setRedirect($link);
    }

    function unpublishcategories() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $id = $cid[0];
        $category_model = $this->getmodel('Category', 'JSJobsModel');
        $return_value = $category_model->categoryChangeStatus($id, 0);
        if ($return_value == 1)
            JSJOBSActionMessages::setMessage(UNPUBLISHED, 'category','message');
        else
            JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'category','error');

        $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=categories';
        $this->setRedirect($link);
    }

    function savecategory() {
        $category_model = $this->getmodel('Category', 'JSJobsModel');
        $return_value = $category_model->storeCategory();
        $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=categories';
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'category','message');
                if ($return_value['rejected_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'category','warning');
                if ($return_value['authentication_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'category','warning');
                if ($return_value['server_responce'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'category','warning');
                $this->setRedirect($link);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'category','message');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'category','message');
                $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=categories';
                $this->setRedirect($link);
            } elseif ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'category','error');
                Factory::getApplication()->input->set('view', 'category');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formcategory');
                // Display based on the set variables
                $this->display();
            } elseif ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'category','error');
                Factory::getApplication()->input->set('view', 'category');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formcategory');
                $this->display();
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'category','error');
                $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=categories';
                $this->setRedirect($link);
            }
        }
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'formcategory');
        Factory::getApplication()->input->set('view', 'category');
        Factory::getApplication()->input->set('c', 'category');
        $this->display();
    }

    function remove() {
        $category_model = $this->getmodel('Category', 'JSJobsModel');
        $returnvalue = $category_model->deleteCategory();
        if ($returnvalue == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'category','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'category','error');
        }
        $this->setRedirect('index.php?option=com_jsjobs&c=category&view=category&layout=categories');
    }

    function cancel() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'category','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=category&view=category&layout=categories');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'category');
        $layoutName = Factory::getApplication()->input->get('layout', 'category');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $category_model = $this->getModel('Category', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($category_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
