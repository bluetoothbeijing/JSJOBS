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

class JSJobsControllerResumesearch extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function saveresumesearch() { //save resume search
        // error incorrect method to fetch uid
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $searchdata = Factory::getApplication()->input->post->getArray();
        $user = Factory::getUser();
        $searcharray = json_decode($this->getmodel('common', 'JSJobsModel')->b64ForDecode($searchdata['searchcriteria']), true);

        $data['uid'] = $user->id;
        $data['searchname'] = isset($searchdata['searchname']) ? $searchdata['searchname'] : "";
        $data['created'] = date('Y-m-d H:i:s');
        $data['params'] = isset($searcharray['params']) ? json_encode($searcharray['params']) : "";
        $data['searchparams'] = json_encode($searcharray);
        $data['status'] = 1;
        
        $resumesearch = $this->getmodel('Resumesearch', 'JSJobsModel');
        $return_value = $resumesearch->storeResumeSearch($data);

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'search','message');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage('Limit exceed or admin block this', 'search','notice');
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'search','error');
        }
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_searchresults&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function deleteresumesearch() { //delete resume search
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $session = Factory::getApplication()->getSession();
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $data = Factory::getApplication()->input->post->getArray();
        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=my_resumesearches&Itemid=' . $Itemid;
        $searchid = Factory::getApplication()->input->get('rs');
        $resumesearch = $this->getmodel('Resumesearch', 'JSJobsModel');
        $return_value = $resumesearch->deleteResumeSearch($searchid, $uid);

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'search','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'search','notice');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'search','error');
        }
        $this->setRedirect(Route::_($link , false));
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
    
