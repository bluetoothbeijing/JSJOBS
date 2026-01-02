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

class JSJobsModelSubCategory extends JSModel {

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

    function listFilterSubCategories($val) {
        if (is_numeric($val) === false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY title ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            $return_value = "<select name='filter_jobsubcategory' id='filter_jobsubcategory'  class='inputbox' >\n";
            $return_value .= "<option value='' >" . Text::_('Sub Category') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >" . Text::_($row->title) . "</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function listSubCategoriesForResume($val) {
        if (!is_numeric($val))
            return false;
        $db = $this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY ordering ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (isset($result)) {
            $return_value = "<select name='job_subcategory'  class='inputbox jsjobs-cbo' >\n";
            $return_value .= "<option value='' >" . Text::_('Sub Category') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >" . Text::_($row->title) . "</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function listSubCategories($val) {
        if (!is_numeric($val))
            return false;
        $db = $this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY ordering ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (isset($result)) {
            $return_value = "<select name='subcategoryid'  class='inputbox jsjobs-cbo' >\n";
            $return_value .= "<option value='' >" . Text::_('Sub Category') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >" . Text::_($row->title) . "</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function listSubCategoriesForSearch($val) {
        if (!is_numeric($val)) {
            $rv = false;
            return $rv;
        }
        $db = $this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY ordering ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            $return_value = "<select name='jobsubcategory' class='inputbox jsjobs-cbo' >\n";
            $return_value .= "<option value='' >" . Text::_('Sub Category') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >" . Text::_($row->title) . "</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function SubCategoriesForSearch($array) {
        if(empty($array)){
            return false;
        }
        $db = $this->getDBO();
        $results = array();
        $title = '';
        foreach ($array as $val) {
            if($val)
                if(!is_numeric($val)) return false;
            
            $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY ordering ASC";
            $db->setQuery($query);
            $results[] = $db->loadObjectList();
        }

        if (isset($results)) {
            $return_value = "<select id='jobsubcategory' name='jobsubcategory' multiple='true' class='inputbox jsjob-multiselect' >\n";
            $return_value .= "<option value='' >" . $title . "</option> \n";
            foreach ($results as $result) {
                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->id\" >" . Text::_($row->title) . "</option> \n";
                }
            }
            $return_value .= "</select>\n";
        }

        return $return_value;
    }

    function makeQueryFromArray( $array ){
        if(empty($array))
            return '';
        $qa = array();
        foreach ($array as $item) {
            if(is_numeric($item)){
                $qa[] = "categoryid = $item";
            }
        }
        $query = implode(" OR ", $qa);
        return $query;
    }


    function getSubCategoriesforCombo($categoryid, $title, $value) {

        $inquery = '';
        if(is_array($categoryid)){
            $res = $this->makeQueryFromArray($categoryid);
            if($res) $inquery .= " AND ( ".$res." )";
        }else{
            if(is_numeric($categoryid))
                $inquery = " AND categoryid = " . $categoryid;
        }
        
        $db = Factory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories` WHERE status = 1 ";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";

        $query .= $inquery;

        $query.=" ORDER BY ordering ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $subcategories = array();
        if ($title)
            $subcategories[] = array('value' => Text::_($value), 'text' => Text::_($title));
        foreach ($rows as $row) {
            $subcategories[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $subcategories;        
    
    }

}
?>
    
