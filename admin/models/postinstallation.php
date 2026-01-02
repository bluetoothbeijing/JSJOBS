<?php

/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , info@burujsolutions.com
 * Created on:	March 04, 2014
  ^
  + Project: 	JS Jobs
  ^
 */
defined('_JEXEC') or die('Not Allowed');
use Joomla\CMS\Factory;


jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelPostInstallation extends JSModel {

    function __construct() {
        parent::__construct();
    }

    function storeConfigurations($data){
        if (empty($data))
            return false;
        $error = false;
        $db = $this->getDBO();
        unset($data['action']);
        unset($data['form_request']);
        foreach ($data as $key => $value) {
            $query = "UPDATE `#__js_job_config` SET `configvalue` = " . $db->Quote($value) . " WHERE `configname`= " . $db->Quote($key);
            $db->setQuery($query);
            if (!$db->execute()) {
                $error = true;
            }
        }
        if ($error)
            return SAVE_ERROR;
        else
            return SAVED;
    }

    function getConfigurationValues(){
        $db = Factory::getDBO();
        $query = "SELECT configvalue,configname  FROM `#__js_job_config`";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        $config_array = array();
        foreach ($data as $config) {
            $config_array[$config->configname]=$config->configvalue;
        }
        return $config_array;
	}
}
