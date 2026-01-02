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

class JSJobsControllerUserRole extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function savenewinjsjobs() { //save new in jsjobs
        $session = Factory::getApplication()->getSession();
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');// wrong way to fetch this variable
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $data = Factory::getApplication()->input->post->getArray();

        $usertype = $data['usertype'];
        $userrole = $this->getmodel('Userrole', 'JSJobsModel');
        $return_value = $userrole->storeNewinJSJobs();
        $session = Factory::getApplication()->getSession();
        $session->set('jsjobconfig_dft', '');
        $session->set('jsjobcur_usr', '');

        if ($usertype == 1) { // employer
            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid=' . $Itemid;
        } elseif ($usertype == 2) {// job seeker
            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid=' . $Itemid;
        }

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'settings','message');
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'settings','error');
        }
        $this->setRedirect(Route::_($link , false));
    }

    function checkuserdetail() {
        $val = Factory::getApplication()->input->get('val');
        $for = Factory::getApplication()->input->get('fr');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $returnvalue = $common->checkUserDetail($val, $for);
        echo $returnvalue;
        Factory::getApplication()->close();
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
    
