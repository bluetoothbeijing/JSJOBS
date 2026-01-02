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

class JSJobsControllerJobsearch extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function savejobsearch() { //save job search

        $searchname = Factory::getApplication()->input->getString('searchname',null,'post');
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $filtered_vars = Factory::getApplication()->input->get('searchcriteria',null,'post');
        $filtered_vars = json_decode($this->getmodel('common', 'JSJobsModel')->b64ForDecode($filtered_vars),true);
        $data['uid'] = Factory::getUser()->id;
        $data['searchname'] = $searchname;
        $data['params'] = isset($filtered_vars['params']) ? json_encode($filtered_vars['params']) : "";
        $data['searchparams'] = json_encode($filtered_vars);
        $data['created'] = date('Y-m-d H:i:s');
        $data['status'] = 1;

        $jobsearch = $this->getmodel('Jobsearch', 'JSJobsModel');
        $return_value = $jobsearch->storeJobSearch($data);

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'search','message');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage('Limit exceed or admin block this', 'search','error');
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'search','error');
        }
        $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobs&Itemid='.$Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function deletejobsearch() { //delete job search
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $data = Factory::getApplication()->input->post->getArray();
        $link = 'index.php?option=com_jsjobs&c=jobsearch&view=jobsearch&layout=my_jobsearches&Itemid=' . $Itemid;
        $searchid = Factory::getApplication()->input->get('js');
        $jobsearch = $this->getmodel('Jobsearch', 'JSJobsModel');
        $return_value = $jobsearch->deleteJobSearch($searchid, $uid);

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'search','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'search','error');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'search','error');
        }
        $this->setRedirect(Route::_($link , false));
    }

    function display($cachable = false, $urlparams = false) {
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
