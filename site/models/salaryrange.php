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

class JSJobsModelSalaryRange extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_jobsalaryrange = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobSalaryRange($title, $jobdata) {
        $db = Factory::getDBO();
        if (!$this->_jobsalaryrange) {
            $query = "SELECT id, rangestart, rangeend FROM `#__js_job_salaryrange`  WHERE status = 1";
            if ($this->_client_auth_key != "")
                $query.=" WHERE serverid!='' AND serverid!=0";
            $query.=" ORDER BY ordering ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
            $this->_jobsalaryrange = $rows;
        }
        $jobsalaryrange = array();
        if ($title)
            $jobsalaryrange[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($this->_jobsalaryrange as $row) {
            if ($jobdata == 1)
                $salrange = $row->rangestart;
            else
                $salrange = $row->rangestart . ' - ' . $row->rangeend;
            $jobsalaryrange[] = array('value' => $row->id, 'text' => $salrange);
        }
        return $jobsalaryrange;
    }

    function getJobSalaryRangeFromTo($title, $jobdata) { // 1for FROM and 2for TO
        $db = Factory::getDBO();
        if (!$this->_jobsalaryrange) {
            $query = "SELECT id, rangestart, rangeend FROM `#__js_job_salaryrange`";
            $query.=" ORDER BY ordering ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
            $this->_jobsalaryrange = $rows;
        }
        $jobsalaryrange = array();
        if ($title)
            $jobsalaryrange[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($this->_jobsalaryrange as $row) {
            if ($jobdata == 1)
                $salrange = $row->rangestart;
            elseif ($jobdata == 2)
                $salrange = $row->rangeend;
            else
                $salrange = $row->rangestart . ' - ' . $row->rangeend;
            $jobsalaryrange[] = array('value' => $row->id, 'text' => $salrange);
        }
        return $jobsalaryrange;
    }

    function getRangeStartById($id){
        if(!is_numeric($id)) return false;
        if(!($id > 0)){
            return 0;
        }
        $db = Factory::getDBO();
        $query = "SELECT rangestart FROM `#__js_job_salaryrange` WHERE id=".$id;
        $db->setQuery($query);
        $range = $db->loadResult();
        return $range;
    }



    function getTitleById($salaryrange_id) {
        if (is_numeric($salaryrange_id) == false)
            return false;
        $salaryrange_name = '';
        $db = Factory::getDBO();
        $query = "SELECT rangestart AS title FROM `#__js_job_salaryrange` WHERE id = ".$salaryrange_id;
        $db->setQuery($query);
        $salaryrange_object = $db->loadObject();
        if(!empty($salaryrange_object)){
            $salaryrange_name = $salaryrange_object->title;
        }
        //echo var_dump($salaryrange_name);die('salaryrange_model 22114');
        return $salaryrange_name;
    }

    function getSalaryRangeById($salaryrange_id) {
        if (is_numeric($salaryrange_id) == false)
            return false;
        $salaryrange_range = '';
        $db = Factory::getDBO();
        $query = "SELECT rangestart,rangeend FROM `#__js_job_salaryrange` WHERE id = ".$salaryrange_id;
        $db->setQuery($query);
        $salaryrange_object = $db->loadObject();
        if(!empty($salaryrange_object)){
            $salaryrange_range = $salaryrange_object->rangestart . ' - ' . $salaryrange_object->rangeend;
        }
        //echo var_dump($salaryrange_range);die('salaryrange_model 22114');
        return $salaryrange_range;
    }
}
?>
    
