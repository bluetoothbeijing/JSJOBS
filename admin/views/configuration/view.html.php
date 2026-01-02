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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');
// Options button.
if (Factory::getUser()->authorise('core.admin', 'com_jsjobs')) {
    ToolbarHelper::preferences('com_jsjobs');
}

class JSJobsViewConfiguration extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'configurations' || $layoutName == 'configurationsemployer' || $layoutName == 'configurationsjobseeker') {
            if ($layoutName == 'configurations')
                $ptitle = Text::_('Configurations');
            elseif ($layoutName == 'configurationsemployer')
                $ptitle = Text::_('Employer Configurations');
            else
                $ptitle = Text::_('Job Seeker Configurations');

            ToolbarHelper::title($ptitle);
            ToolbarHelper::save('configuration.save');
            $result = $this->getJSModel('configuration')->getConfigurationsForForm();
            $this->lists = $result[1];
        }
//        layout end

        $this->config = $config;
        $this->jsjobsconfigid = Factory::getApplication()->input->get('jsjobsconfigid','');
        //$this->application = $application;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }
}
?>
