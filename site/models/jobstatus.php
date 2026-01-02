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

class JSJobsModelJobStatus extends JSModel {

    var $_uid = null;
    var $_jobstatus = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobStatus($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobstatus` WHERE isactive = 1";
        $query.=" ORDER BY ordering ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $this->_jobstatus = array();
        if ($title)
            $this->_jobstatus[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $this->_jobstatus[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $this->_jobstatus;
    }


    function getTitleById($jobstatus_id) {
        if (is_numeric($jobstatus_id) == false)
            return false;
        $jobstatus_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_jobstatus` WHERE id = ".$jobstatus_id;
        $db->setQuery($query);
        $jobstatus_object = $db->loadObject();
        if(!empty($jobstatus_object)){
            $jobstatus_name = $jobstatus_object->title;
        }
        //echo var_dump($jobstatus_name);die('jobstatus_model 22114');
        return $jobstatus_name;
    }

}
?>