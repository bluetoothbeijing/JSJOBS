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

class JSJobsModelAges extends JSModel {

    var $_uid = null;
    var $_ages = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() { // clean it.
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getAges($title) {
        if (!$this->_ages) {// make problem with age from, age to
            try{
                $db = Factory::getDBO();
                $query = "SELECT id, title FROM `#__js_job_ages` WHERE status = 1";
                if ($this->_client_auth_key != "")
                    $query.=" AND serverid != '' AND serverid != 0";
                $query.=" ORDER BY ordering ASC ";
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $this->_ages = $rows;
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
        }
        $ages = array();
        if ($title)
            $ages[] = array('value' => Text::_(''), 'text' => $title);
        foreach ($this->_ages as $row) {
            $ages[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $ages;
    }



    function getTitleById($age_id) {
        if (is_numeric($age_id) == false)
            return false;
        $age_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_ages` WHERE id = ".$age_id;
        $db->setQuery($query);
        $age_object = $db->loadObject();
        if(!empty($age_object)){
            $age_name = $age_object->title;
        }
        //echo var_dump($age_name);die('age_model 22114');
        return $age_name;
    }

}
?>	
