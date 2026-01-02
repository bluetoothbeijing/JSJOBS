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
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelJobType extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_jobtype = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();

        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobType($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1";
        $query.=" ORDER BY ordering ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $this->_jobtype = array();
        if ($title)
            $this->_jobtype[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $this->_jobtype[] = array('value' => $row->id,
                'text' => Text::_($row->title));
        }

        return $this->_jobtype;
    }

    function jobTypes($id, $val, $fild) {
        if (is_numeric($val) == false)
            return false;
        if (!$this->_config)
            $this->configuration->getConfig('');

        foreach ($this->_config as $conf) {
            if ($conf->configname == $fild)
                $value = $conf->configvalue;
        }
        $value = $this->getSubVal($value);
        if ($value != $id)
            return 3;
        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_jobtypes` SET status = " . $val;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        return true;
    }


    function getTitleById($jobtype_id) {
        if (is_numeric($jobtype_id) == false)
            return false;
        $jobtype_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_jobtypes` WHERE id = ".$jobtype_id;
        $db->setQuery($query);
        $jobtype_object = $db->loadObject();
        if(!empty($jobtype_object)){
            $jobtype_name = $jobtype_object->title;
        }
        //echo var_dump($jobtype_name);die('jobtype_model 22114');
        return $jobtype_name;
    }

}
?>