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
use Joomla\CMS\Uri\Uri;

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelJobShift extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function jobShifts($id, $val, $fild) {
        if (is_numeric($val) == false)
            return false;
        if (!$this->_config)
            $this->getJSModel('configurations')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == $fild)
                $value = $conf->configvalue;
        }
        if ($value != $id)
            return 3;
        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_shifts` SET status = " . $val;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        return true;
    }

    function getTitleById($jobshift_id) {
        if (is_numeric($jobshift_id) == false)
            return false;
        $jobshift_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_shifts` WHERE id = ".$jobshift_id;
        $db->setQuery($query);
        $jobshift_object = $db->loadObject();
        if(!empty($jobshift_object)){
            $jobshift_name = $jobshift_object->title;
        }
        //echo var_dump($jobshift_name);die('jobshift_model 22114');
        return $jobshift_name;
    }

}
?>