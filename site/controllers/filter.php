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

class JSJobsControllerFilter extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function savefilter() { //save filter
        global $mainframe;

        $fileter = $this->getModel('Filter', 'JSJobsModel');


        $session = Factory::getApplication()->getSession();
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');// wrong way to fetch this variable

        $Itemid = Factory::getApplication()->input->get('Itemid');
        $data = Factory::getApplication()->input->post->getArray();
        $link = $data['formaction'];
        $return_value = $fileter->storeFilter();

        if ($return_value == 1) {
            $session->clear('jsuserfilter');
            JSJOBSActionMessages::setMessage(SAVED, 'filter','message');
        }else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'filter','error');
        }
        $this->setRedirect(Route::_($link , false));
    }

    function deletefilter() { //delete filter
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');// wrong way to fetch this variable
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $data = Factory::getApplication()->input->post->getArray();
        $link = $data['formaction'];
        $fileter = $this->getmodel('Filter', 'JSJobsModel');
        $return_value = $fileter->deleteUserFilter();
        $session = Factory::getApplication()->getSession();
        if ($return_value == 1) {
            $session->clear('jsuserfilter');
            JSJOBSActionMessages::setMessage(DELETED, 'filter','message');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'filter','error');
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
    
