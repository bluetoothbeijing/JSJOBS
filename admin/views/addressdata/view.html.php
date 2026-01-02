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

class JSJobsViewAddressData extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'loadaddressdata') {          // load address data
            ToolbarHelper::title(Text::_('Load Address Data'));
            $error = Factory::getApplication()->input->get('er','0');
            $this->error = $error;
        }
//        layout end

        $this->config = $config;
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
