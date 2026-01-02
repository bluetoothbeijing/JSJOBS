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

class JSJobsControllerCoverLetter extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function savecoverletter() { //save cover letter
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        $uid = Factory::getApplication()->input->get('uid', 'none', 'string');// wrong way to fetch this variable
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $coverletter = $this->getmodel('Coverletter', 'JSJobsModel');
        $return_value = $coverletter->storeCoverLetter();

        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(SAVED, 'coverletter','message');
            $link = 'index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=mycoverletters&Itemid=' . $Itemid;
        } else if ($return_value == 2) {
            JSJOBSActionMessages::setMessage(REQUIRED_FIELDS, 'coverletter','error');
            $link = 'index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=formcoverletter&Itemid=' . $Itemid;
        } else {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'coverletter','error');
            $link = 'index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=formcoverletter&Itemid=' . $Itemid;
        }
        $this->setRedirect($link);
    }

    function deletecoverletter() { //delete cover letter
        Factory::getSession()->checkToken('get') or die( 'Invalid Token' );
        $user = Factory::getUser();
        $uid = $user->id;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $id = Factory::getApplication()->input->get('cl', '');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $coverletterid = $common->parseId($id);
        $coverletter = $this->getmodel('Coverletter', 'JSJobsModel');
        $return_value = $coverletter->deleteCoverLetter($coverletterid, $uid);
        $jobsharing = $this->getModel('jobsharingsite', 'JSJobsModel');
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(DELETED, 'coverletter','message');
        } elseif ($return_value == 2) {
            JSJOBSActionMessages::setMessage(NOT_YOUR, 'coverletter','error');
        } elseif ($return_value == 3) {
            JSJOBSActionMessages::setMessage(IN_USE, 'coverletter','warning');
        } else {
            JSJOBSActionMessages::setMessage(DELETE_ERROR, 'coverletter','error');
        }
        $link = 'index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=mycoverletters&Itemid=' . $Itemid;
        $this->setRedirect(Route::_($link , false));
    }

    function getcoverletter() {
        $cletterid = Factory::getApplication()->input->get('cletterid');
        $coverletter = $this->getmodel('Coverletter', 'JSJobsModel');
        $returnvalue = $coverletter->getCoverLetterForAppliedJob($cletterid);
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
    
