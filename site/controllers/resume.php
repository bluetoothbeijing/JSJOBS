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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;    

//HTMLHelper::_('behavior.calendar'); 
jimport('joomla.application.component.controller');

class JSJobsControllerResume extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function subcategoriesbycatidresume(){
        $catid = Factory::getApplication()->input->get('catid');
        $showall = Factory::getApplication()->input->get('showall');
        if($showall=='true'){
            $showall = true;
        }else{
            $showall = false;
        }
        $result = $this->getModel('Resume','JSJobsModel')->subCategoriesByCatIdresume($catid , $showall);
        echo $result;
        Factory::getApplication()->close();
    }

    function saveresume() {
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $data = Factory::getApplication()->input->post->getArray();
        $return = $this->getModel('resume', 'JSJobsModel')->storeResume( $data );

        if(isset($data['save'])){
            $aliasid = '';
            if($return){
                $aliasid =  $this->getmodel('common')->removeSpecialCharacter($return);
                JSJOBSActionMessages::setMessage(SAVED, 'resume','message');
            }else{
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'resume','error');
            }
            $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&nav=29&rd=' . $aliasid . '&Itemid=' . $Itemid;
        }else{
            if($return){
                JSJOBSActionMessages::setMessage(SAVED, 'resume','message');
            }else{
                JSJOBSActionMessages::setMessage(SAVE_ERROR, 'resume','error');
            }
            $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes';
        }
        $this->setRedirect(Route::_($link,false));
    }

    function getresumefiles() {
        $resumeid = Factory::getApplication()->input->get('resumeid');
        $data_directory = $this->getmodel('configurations')->getConfigValue('data_directory');
        $files = array();
        $resumeModel = $this->getmodel('Resume', 'JSJobsModel');
        $files = $resumeModel->getResumeFilesByResumeId($resumeid);
        // resume form layout class
        require_once JPATH_COMPONENT . '/views/resume/resumeformlayout.php';
        $resumeformlayout = new JSJobsResumeformlayout();
        $data = $resumeformlayout->getResumeFilesLayout($files, $data_directory);
        echo $data;
        Factory::getApplication()->close();
    }

    function getallresumefiles() {
        $resumeModel = $this->getmodel('Resume', 'JSJobsModel');
        $link = $resumeModel->getAllResumeFiles();
        Factory::getApplication()->close();
    }

    function deleteresumefiles() {
        $resumeModel = $this->getmodel('Resume', 'JSJobsModel');
        $return_value = $resumeModel->deleteResumeFile();
        if (!empty($return_value) && $return_value == 1) {
            $msg = Text::_('File Deleted');
        } else {
            $msg = Text::_('Operation Aborted');
        }
        echo $msg;
        Factory::getApplication()->close();
    }

    function deleteresume() { //delete resume
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $resumeid = $common->parseId(Factory::getApplication()->input->get('rd', ''));
        $resume = $this->getmodel('Resume', 'JSJobsModel');
        $return_value = $resume->deleteResume($resumeid, $uid);
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'resume','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(IN_USE, 'resume','message');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'resume','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'resume','message');
        }
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function getresumedetail() {
        $user = Factory::getUser();
        $uid = $user->id;
        $jobid = Factory::getApplication()->input->get('jobid');
        $resumeid = Factory::getApplication()->input->get('resumeid');
        $resume = $this->getmodel('Resume', 'JSJobsModel');
        $returnvalue = $resume->getResumeDetail($uid, $jobid, $resumeid);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function postresumeonjomsocial(){
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $isallowed = JSModel::getJSModel('configurations')->getConfigValue('jomsocial_allowpostresume');
        if($isallowed){            
            $resumeid = Factory::getApplication()->input->get('id',0);
            $res = JSModel::getJSModel('resume')->postResumeOnJomSocial($resumeid);
            if($res){
                JSJOBSActionMessages::setMessage("Resume has been successfully posted on JomSocial", 'resume','message');
            }else{
                JSJOBSActionMessages::setMessage("Resume has not been posted on JomSocial", 'resume','error');
            }
        }
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=' . $Itemid;
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
    
