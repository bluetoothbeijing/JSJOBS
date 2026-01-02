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

class JSJobsModelFieldsOrdering extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() { // clean it.
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getFieldsOrderingForSearchByFieldFor($fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $user = Factory::getUser();
        if($user->guest){
            $published = " search_visitor = 1";
        }else{
            $published = " search_user = 1";
        }

        if($fieldfor == 3){
            $published .= ' AND (section = 1 OR field="address_city")';
        }

        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__js_job_fieldsordering` WHERE fieldfor = ".$fieldfor." AND ".$published;
        $query .= " ORDER BY search_ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getFieldsOrderingByFieldFor($fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $user = Factory::getUser();
        if($user->guest){
            $published = " isvisitorpublished = 1 ";
        }else{
            $published = " published = 1 ";
        }

        if($fieldfor == 3){
            $published .= ' AND section = 1 ';
        }

        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__js_job_fieldsordering` WHERE fieldfor = ".$fieldfor." AND ".$published;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getUserfieldsfor($fieldfor, $resumesection = null) {
        if (!is_numeric($fieldfor))
            return false;
        $db = Factory::getDBO();

        $published = '';
        if ($resumesection != null) {
            if (!is_numeric($resumesection))
                return false;
            $published .= " AND section = $resumesection ";
        }
        $query = "SELECT field,fieldtitle,userfieldparams,userfieldtype FROM `#__js_job_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 " . $published;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

}
?>	
