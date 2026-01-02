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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;    

jimport('joomla.application.component.controller');

class JSJobsControllerExportResume extends JSController {

    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }
        $this->_uid = $user->id;
        parent::__construct();
    }

    function exportallresume() {
        $jobaliasid = Factory::getApplication()->input->get('bd');
        $common = $this->getmodel('Common', 'JSJobsModel');
        $jobid = $common->parseId($jobaliasid);

        $export = $this->getmodel('Export', 'JSJobsModel');
        $return_value = $export->setAllExport($jobid);
        if ($return_value == true) {
            // Push the report now!
            $msg = Text ::_('Resume Export');
            $name = 'export-resumes';
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $name . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Lacation: excel.htm?id=yes");
            ob_clean();
            flush();
            print $return_value;
            die();
        } else {
            JSJOBSActionMessages::setMessage('Error in exporting resume', 'resume','error');
        }
        $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_appliedapplications&bd=' . $jobaliasid;
        $this->setRedirect(Route::_($link , false));
    }

    /* END EXPORT RESUMES */
    function exportresume() {
        $common_model = $this->getModel('Common', 'JSJobsModel');
        $jobaliasid = Factory::getApplication()->input->get('bd');
        $jobid = $common_model->parseId($jobaliasid);
        $resumealiasid = Factory::getApplication()->input->get('rd');
        $resumeid = $common_model->parseId($resumealiasid);

        // For jobseeker
        $validate_check = false;
        $resume_for = Factory::getApplication()->input->get('for');
        if(is_string($resume_for) && $resume_for != '' && $jobaliasid == ""){
            $resume_for = JSModel::getJSModel('common')->b64ForDecode($resume_for);
            $user = Factory::getUser();
            $validate_check = $this->getModel('Resume','JSJobsModel')->checkResumeIsValidUser($resumeid,$user->id);    
        }elseif($resume_for == '' && is_numeric($jobid)){
            $validate_check = true;
        }
        if($validate_check == true){
            $export_model = $this->getModel('Export', 'JSJobsModel');

            $return_value = $export_model->setExport($jobid, $resumeid);
            if ($return_value == true) {
                // Push the report now!
                $msg = Text::_('Resume Export');
                $name = 'export-resume';
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . $name . ".xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Lacation: excel.htm?id=yes");
                ob_clean();
                flush();
                print($return_value);
                die();
            } else {
                $msg = Text::_('Error in exporting resume');
            }
        }else{
            $msg = Text::_('Error in exporting resume');
        }
        echo $msg;
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
    
