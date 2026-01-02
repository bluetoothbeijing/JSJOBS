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
use Joomla\CMS\Component\ComponentHelper;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewPremiumplugin extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        if( $layoutName == 'premiumplugins' ){
            $db = Factory::getDbo();
            
            //jomsocial
            $jmEnabled = ComponentHelper::isEnabled('com_community');
            $jsjobsJmEnabled = file_exists(JPATH_PLUGINS.'/community/jsjobs');
            $query = "SELECT * FROM `#__extensions` WHERE `type`='plugin' AND `element`='jsjobs' AND `folder`='community'";
            eval($this->getJSModel('common')->b64ForDecode($this->getJSModel('configuration')->getConfigValue('jmjsbasic')));
            $db->setQuery($query);
            $jmVerified = $db->loadResult() ? 1 : 0;
            $jomsocial = (object) compact('jmEnabled','jsjobsJmEnabled','jmVerified');

            $versioncode = $this->getJSModel('jsjobs')->getConfigByConfigName('version');
            $versiontype = $this->getJSModel('jsjobs')->getConfigByConfigName('versiontype');
            $writeable = is_writable(JPATH_ROOT.'/tmp');

            $this->versioncode = $versioncode;
            $this->versiontype = $versiontype;
            $this->writeable =$writeable;
            $this->jomsocial =$jomsocial;
        }
        $this->config = $config;
        //$this->application = $application;
        if(isset($items)) $this->items = $items;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;
        parent::display($tpl);
    }

}
?>
