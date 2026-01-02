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

class JSJobsControllerCustomfield extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'customfield');
        $layoutName = Factory::getApplication()->input->get('layout', 'customfield');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $customfield_model = $this->getModel('Customfield', 'JSJobsModel');
        $fieldordering_model = $this->getModel('Fieldordering', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($customfield_model);
        $view->setModel($fieldordering_model);
        $view->setLayout($layoutName);
        $view->display();
    }
}
?>
