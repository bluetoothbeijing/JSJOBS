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

class JSJobsControllerCommon extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function makedefault() { //set default for default tables 
        $id = Factory::getApplication()->input->get('id');
        $for = Factory::getApplication()->input->get('for');
        $common_model = $this->getmodel('Common', 'JSJobsModel');
        $returnvalue = $common_model->setDefaultForDefaultTable($id, $for);
        $msg = "";
        switch ($for) {
            case "jobtypes":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'jobtype','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'jobtype','error');
                break;
            case "jobstatus":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'jobstatus','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'jobstatus','error');
                break;
            case "shifts":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'shift','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'shift','error');
                break;
            case "heighesteducation":
                $for = "highesteducations";
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'highesteducation','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'highesteducation','error');
                break;
            case "ages":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'age','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'age','error');
                break;
            case "careerlevels":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'careerlevel','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'careerlevel','error');
                break;
            case "experiences":
                $for = "experience";
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'experience','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'experience','error');
                break;
            case "salaryrange":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'salaryrange','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'salaryrange','error');
                break;
            case "salaryrangetypes":
                $for = "salaryrangetype";
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'salaryrangetype','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'salaryrangetype','error');
                break;
            case "categories":
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'category','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'category','error');
                break;
            case "subcategories":
            case "subcategory":
                $session = Factory::getApplication()->getSession();
                $categoryid = $session->get('sub_categoryid');
                $for = "subcategories&cd=" . $categoryid;
                $layoutfor = 'subcategories';
                if ($returnvalue == 1)
                    JSJOBSActionMessages::setMessage(SET_DEFAULT, 'subcategory','message');
                else
                    JSJOBSActionMessages::setMessage(SET_DEFAULT_ERROR, 'subcategory','error');
                break;
        }
        if (isset($layoutfor) && $layoutfor == 'subcategories')
            $object = $this->getControllerViewByLayout('subcategories');
        else
            $object = $this->getControllerViewByLayout($for);
        $link = 'index.php?option=com_jsjobs&c=' . $object['c'] . '&view=' . $object['view'] . '&layout=' . $for;
        $this->setRedirect($link);
    }

    function makePublishUnpublish(){
        $for = Factory::getApplication()->input->get('for');
        $arr = Factory::getApplication()->input->get('cid');
        $rowid = $arr[0];
        $status = Factory::getApplication()->input->get('status');
        $common_model = $this->getmodel('Common', 'JSJobsModel');
        $returnvalue = $common_model->makeFieldPublishUnpublish($rowid, $for, $status);
        $msg = "";
        switch ($for) {
            case "jobtypes":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'jobtype','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'jobtype','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'jobtype','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'jobtype','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'jobtype','error');
                    }
                }
                break;
            case "jobstatus":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'jobstatus','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'jobstatus','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'jobstatus','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'jobstatus','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'jobstatus','error');
                    }
                }
                break;
            case "shifts":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'shift','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'shift','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'shift','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'shift','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'shift','error');
                    }
                }
                break;
            case "heighesteducation":
                $for = "highesteducations";
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'highesteducation','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'highesteducation','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'highesteducation','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'highesteducation','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'highesteducation','error');
                    }
                }
                break;
            case "ages":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'age','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'age','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'age','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'age','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'age','error');
                    }
                }
                break;
            case "careerlevels":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'careerlevel','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'careerlevel','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'careerlevel','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'careerlevel','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'careerlevel','error');
                    }
                }
                break;
            case "experiences":
                $for = "experience";
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'experience','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'experience','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'experience','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'experience','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'experience','error');
                    }
                }
                break;
            case "salaryrange":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'salaryrange','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'salaryrange','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'salaryrange','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'salaryrange','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'salaryrange','error');
                    }
                }
                break;
            case "currency":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'currency','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'currency','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'currency','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'currency','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'currency','error');
                    }
                }
                break;
            case "salaryrangetypes":
                $for = "salaryrangetype";
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'salaryrangetype','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'salaryrangetype','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'salaryrangetype','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'salaryrangetype','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'salaryrangetype','error');
                    }
                }
                break;
            case "categories":
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'category','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'category','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'category','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'category','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'category','error');
                    }
                }
                break;
            case "subcategories":
            case "subcategory":
                $session = Factory::getApplication()->getSession();
                $categoryid = $session->get('sub_categoryid');
                $for = "subcategories&cd=" . $categoryid;
                $layoutfor = 'subcategories';
                if($status==0){
                    if ($returnvalue == 3){
                        JSJOBSActionMessages::setMessage(DEFAULT_UN_PUBLISH_ERROR, 'subcategory','error');
                    }elseif ($returnvalue == 1) {
                        JSJOBSActionMessages::setMessage(UN_PUBLISHED, 'subcategory','message');
                    }else{
                        JSJOBSActionMessages::setMessage(UN_PUBLISH_ERROR, 'subcategory','error');
                    }
                }elseif($status==1){
                    if ($returnvalue == 1){
                        JSJOBSActionMessages::setMessage(PUBLISHED, 'subcategory','message');
                    }else{
                        JSJOBSActionMessages::setMessage(PUBLISH_ERROR, 'subcategory','error');
                    }
                }
                break;
        }
        if (isset($layoutfor) && $layoutfor == 'subcategories')
            $object = $this->getControllerViewByLayout('subcategories');
        else
            $object = $this->getControllerViewByLayout($for);
        $link = 'index.php?option=com_jsjobs&c=' . $object['c'] . '&view=' . $object['view'] . '&layout=' . $for;
        $this->setRedirect($link);
    }

    function getControllerViewByLayout($for) {
        switch ($for) {
            case 'jobtypes' : $object['view'] = $object['c'] = "jobtype";
                break;
            case 'shifts' : $object['view'] = $object['c'] = "shift";
                break;
            case 'heighesteducations' : $object['view'] = "highesteducation";
                $object['c'] = "highesteducation";
                break;
            case 'highesteducations' : $object['view'] = "highesteducation";
                $object['c'] = "highesteducation";
                break;
            case 'ages' : $object['view'] = $object['c'] = "age";
                break;
            case 'currencies' : $object['view'] = $object['c'] = "currency";
                break;
            case 'careerlevels' : $object['view'] = $object['c'] = "careerlevel";
                break;
            case 'salaryrangetypes' : $object['view'] = $object['c'] = "salaryrangetype";
                break;
            case 'categories' : $object['view'] = $object['c'] = "category";
                break;
            case 'subcategories' : case 'subcategory' : $object['view'] = $object['c'] = "subcategory";
                break;
            default : $object['view'] = $object['c'] = $for;
                break;
        }
        return $object;
    }

    function defaultorderingup() {
        $id = Factory::getApplication()->input->get('id');
        $for = Factory::getApplication()->input->get('for');
        $common_model = $this->getmodel('Common', 'JSJobsModel');
        $returnvalue = $common_model->setOrderingUpForDefaultTable($id, $for);
        if ($for == "heighesteducation")
            $for = "highesteducations";
        elseif ($for == "experiences")
            $for = "experience";
        elseif ($for == "currencies")
            $for = "currency";
        elseif ($for == "salaryrangetypes")
            $for = "salaryrangetype";
        elseif ($for == "subcategories") {
            $session = Factory::getApplication()->getSession();
            $categoryid = $session->get('sub_categoryid');
            $for = "subcategories&cd=" . $categoryid;
            $layout = 'subcategories';
        }
        if (isset($layout) && $layout == 'subcategories')
            $object = $this->getControllerViewByLayout('subcategories');
        else
            $object = $this->getControllerViewByLayout($for);
        $link = 'index.php?option=com_jsjobs&c=' . $object['c'] . '&view=' . $object['view'] . '&layout=' . $for;
        JSJOBSActionMessages::setMessage(ORDERING_DOWN, 'field','message');
        $this->setRedirect($link, $msg);
    }

    function defaultorderingdown() {
        $id = Factory::getApplication()->input->get('id');
        $for = Factory::getApplication()->input->get('for');
        $common_model = $this->getmodel('Common', 'JSJobsModel');
        $returnvalue = $common_model->setOrderingDownForDefaultTable($id, $for);
        if ($for == "heighesteducation")
            $for = "highesteducations";
        elseif ($for == "experiences")
            $for = "experience";
        elseif ($for == "currencies")
            $for = "currency";
        elseif ($for == "salaryrangetypes")
            $for = "salaryrangetype";
        elseif ($for == "subcategories") {
            $session = Factory::getApplication()->getSession();
            $categoryid = $session->get('sub_categoryid');
            $for = "subcategories&cd=" . $categoryid;
            $layout = 'subcategories';
        }
        if (isset($layout) && $layout == 'subcategories')
            $object = $this->getControllerViewByLayout('subcategories');
        else
            $object = $this->getControllerViewByLayout($for);
        $link = 'index.php?option=com_jsjobs&c=' . $object['c'] . '&view=' . $object['view'] . '&layout=' . $for;
        JSJOBSActionMessages::setMessage(ORDERING_UP, 'field','message');
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'common');
        $layoutName = Factory::getApplication()->input->get('layout', 'common');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $view->setModel($model, true);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
