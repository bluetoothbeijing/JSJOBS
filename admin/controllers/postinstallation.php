<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     http://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^ 
 */

defined('_JEXEC') or die('Not Allowed');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;


jimport('joomla.application.component.controller');

class JSJobsControllerPostInstallation extends JSController {

    function __construct() {
        parent::__construct();
    }

    function save() {
      Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
      $data = Factory::getApplication()->input->post->getArray();
      $callfrom = $data['step'];
      if($callfrom == 4){
        $insertsampledata = $data['sampledata'];
        $createmenu = $data['importmenu'];
        $result = $this->getJSController('installer')->installSampleData($insertsampledata,$createmenu);
      }else{
        $result = $this->getmodel('postinstallation','JSJobsModel')->storeConfigurations($data);
      }
      $link = 'index.php?option=com_jsjobs&c=postinstallation&layout=steptwo';
      if ($result == SAVED) {
        if ($callfrom == 2) {
          $link = 'index.php?option=com_jsjobs&c=postinstallation&layout=stepthree';
        }elseif ($callfrom == 3) {
          $link = 'index.php?option=com_jsjobs&c=postinstallation&layout=stepfour';
        }elseif ($callfrom == 4) {
          $link = 'index.php?option=com_jsjobs&c=postinstallation&layout=settingcomplete';
        } 
      }else{
        $link = 'index.php?option=com_jsjobs&c=postinstallation&layout='.$data['layout'];
      }
      $this->setRedirect($link);
    }
    
    function display($cachable = false, $urlparams = false) {
      $document = Factory::getDocument();
      $viewName = Factory::getApplication()->input->get('view', 'postinstallation');
      $layoutName = Factory::getApplication()->input->get('layout', 'stepone');
      $viewType = $document->getType();
      $view = $this->getView($viewName, $viewType);
      $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
      $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
      $view->setModel($jobsharing_model, true);
      $view->setModel($configuration_model);
      $view->setLayout($layoutName);
      $view->display();
    }

}
