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

class JSJobsControllerSubcategory extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function setorderingsubcategories() {
        $data = Factory::getApplication()->input->post->getArray();
        $categoryid = Factory::getApplication()->input->get('cd');
        $subcategory_model = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory_model->setOrderingSubcategories($categoryid);
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage('Ordering change successfully', 'subcategory','message');
        else
            JSJOBSActionMessages::setMessage('Error set ordering', 'subcategory','error');
        $for = "subcategories&cd=" . $categoryid;
        $link = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=' . $for;
        $this->setRedirect($link);
    }

    function editsubcategories() {
        Factory::getApplication()->input->set('layout', 'formsubcategory');
        Factory::getApplication()->input->set('view', 'subcategory');
        Factory::getApplication()->input->set('c', 'subcategory');
        $this->display();
    }

    function publishsubcategories() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $id = $cid[0];
        $subcategory_model = $this->getmodel('Subcategory', 'JSJobsModel');
        $return_value = $subcategory_model->subCategoryChangeStatus($id, 1);
        if ($return_value != 1)
            JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'subcategory','error');
        else
            JSJOBSActionMessages::setMessage(PUBLISHED, 'subcategory','message');
        $categoryid = Factory::getApplication()->input->get('cd');
        $link = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid;
        $this->setRedirect($link);
    }

    function unpublishsubcategories() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $id = $cid[0];
        $subcategory_model = $this->getmodel('Subcategory', 'JSJobsModel');
        $return_value = $subcategory_model->subCategoryChangeStatus($id, 0);
        if ($return_value != 1)
            JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'subcategory','error');
        else
            JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'subcategory','message');
        $categoryid = Factory::getApplication()->input->get('cd');
        $link = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid;
        $this->setRedirect($link);
    }

    function removesubcategory() {
        $subcategory_model = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory_model->deleteSubCategory();
        if ($returnvalue == 1)
            JSJOBSActionMessages::setMessage(DELETED, 'subcategory','message');
        else
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'subcategory','error');
        $session = Factory::getApplication()->getSession();
        $categoryid = $session->get('sub_categoryid');
        $this->setRedirect('index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid);
    }

    function cancelsubcategories() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'subcategory','notice');
        $session = Factory::getApplication()->getSession();
        $categoryid = $session->get('sub_categoryid');
        $this->setRedirect('index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid);
    }

    function savesubcategory() {
        $subcategory_model = $this->getmodel('Subcategory', 'JSJobsModel');
        $return_value = $subcategory_model->storeSubCategory();
        $session = Factory::getApplication()->getSession();
        $categoryid = $session->get('sub_categoryid');
        $link = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid;
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'subcategory','message');
                if ($return_value['rejected_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_IMPROPER_NAME, 'subcategory','warning');
                if ($return_value['authentication_value'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_AUTH_FAIL, 'subcategory','warning');
                if ($return_value['server_responce'] != "")
                    JSJOBSActionMessages::setMessage(SHARING_SYNCHRONIZE_ERROR, 'subcategory','warning');
                $this->setRedirect($link);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                JSJOBSActionMessages::setMessage(SAVED, 'subcategory','message');
                $this->setRedirect($link);
            }
        } else {
            if ($return_value == 1) {
                JSJOBSActionMessages::setMessage(SAVED, 'subcategory','message');
                $link = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid;
                $this->setRedirect($link);
            } else if ($return_value == 2) {
                JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'subcategory','error');
                Factory::getApplication()->input->set('view', 'subcategory');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formsubcategory');
                // Display based on the set variables
                $this->display(); //parent::display();
            } else if ($return_value == 3) {
                JSJOBSActionMessages::setMessage(ALREADY_EXIST, 'subcategory','notice');
                Factory::getApplication()->input->set('view', 'subcategory');
                Factory::getApplication()->input->set('hidemainmenu', 1);
                Factory::getApplication()->input->set('layout', 'formsubcategory');
                $this->display(); //parent::display();
            } else {
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'subcategory','error');
                $link = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=subcategories&cd=' . $categoryid;
                $this->setRedirect($link);
            }
        }
    }

    function listsubcategories() {
        $val = Factory::getApplication()->input->get('val');
        $subcategory_model = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory_model->listSubCategories($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function listsubcategoriesforsearch() {
        $val = Factory::getApplication()->input->get('val');
        $model = $this->getModel('subcategory', 'JSJobsModel');
        $returnvalue = $model->listSubCategoriesForSearch($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'subcategory');
        $layoutName = Factory::getApplication()->input->get('layout', 'subcategory');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $subcategory_model = $this->getModel('Subcategory', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($subcategory_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
