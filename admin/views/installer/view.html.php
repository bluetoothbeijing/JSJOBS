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

class JSJobsViewInstaller extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout statr
        if ($layoutName == 'finalstep') {        //job types
            ToolbarHelper::title(Text::_('Final Step'));
            $goCp = $this->getJSModel('company')->getIfSampleData();
            $this->isgotocp = $goCp;            
            $session = Factory::getApplication()->getSession();
            $data = $session->get('data');
            $this->data = $data;
        }
//        layout end
        $this->option = $option;
        $this->uid = $uid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
