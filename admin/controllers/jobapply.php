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

class JSJobsControllerJobapply extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }


    function actionresume() { //save shortlist candidate
        $user = Factory::getUser();
        $uid = $user->id;
        $data = Factory::getApplication()->input->post->getArray();
        $jobid = $data['jobid'];
        $resumeid = $data['resumeid'];
        $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=jobappliedresume&oi=' . $jobid;
        $this->setRedirect($link);
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'jobapply');
        $layoutName = Factory::getApplication()->input->get('layout', 'jobapply');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $jobapply_model = $this->getModel('Jobapply', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($jobapply_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
