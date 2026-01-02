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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.view');

class JSJobsViewPostInstallation extends JSView
{
	function display($tpl = null)	{
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
		ToolbarHelper::title(Text::_('Installation Complete'));
		if($layoutName == 'stepone'){
			ToolbarHelper::title(Text::_('General Settings') );
			$result = $this->getJSModel('postinstallation')->getConfigurationValues();
			$this->result = $result;
		}elseif($layoutName == 'steptwo'){
			ToolbarHelper::title(Text::_('Employer Settings') );
			$result = $this->getJSModel('postinstallation')->getConfigurationValues();
			$this->result = $result;
		}elseif($layoutName == 'stepthree'){
			ToolbarHelper::title(Text::_('Jobseeker Settings') );
			$result = $this->getJSModel('postinstallation')->getConfigurationValues();
			$this->result = $result;
		}elseif($layoutName == 'stepfour'){
			ToolbarHelper::title(Text::_('Sample Data') );
		}else{

		}

		$this->config = $config;
        //$this->application = $application; not in used
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

		parent::display($tpl);
	}
	
}
