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

class JSJobsModelSalaryRangeType extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_jobsalaryrangetype = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getSalaryRangeTypes($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_salaryrangetypes` WHERE status = 1";
        $query.=" ORDER BY ordering ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $types = array();
        if ($title)
            $types[] = array('value' => 0, 'text' => $title);
        foreach ($rows as $row) {
            $types[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $types;
    }

    function getJobSalaryRangeType($title) {
        $db = Factory::getDBO();
        if (!$this->_jobsalaryrangetype) {
            $query = "SELECT id, title FROM `#__js_job_salaryrangetypes` WHERE status = 1";
            $query.=" ORDER BY ordering ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
            $this->_jobsalaryrangetype = $rows;
        }
        $jobsalaryrangetype = array();
        if ($title)
            $jobsalaryrangetype[] = array('value' => 0, 'text' => $title);

        foreach ($this->_jobsalaryrangetype as $row) {
            $jobsalaryrangetype[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $jobsalaryrangetype;
    }

    function getRangeTypeById($id){
        if(!is_numeric($id)) return false;
        if(!($id > 0)){
            return '';
        }
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_salaryrangetypes` WHERE id=".$id;
        $db->setQuery($query);
        $type = $db->loadResult();
        return $type;
    }

    function getTitleById($salaryrangetype_id) {
        if (is_numeric($salaryrangetype_id) == false)
            return false;
        $salaryrangetype_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_salaryrangetypes` WHERE id = ".$salaryrangetype_id;
        $db->setQuery($query);
        $salaryrangetype_object = $db->loadObject();
        if(!empty($salaryrangetype_object)){
            $salaryrangetype_name = $salaryrangetype_object->title;
        }
        //echo var_dump($salaryrangetype_name);die('salaryrangetype_model 22114');
        return $salaryrangetype_name;
    }


}
?>