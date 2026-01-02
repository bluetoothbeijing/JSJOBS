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

class JSJobsModelCareerLevel extends JSModel {

    var $_uid = null;
    var $_careerlevel = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getCareerLevel($title) {
        if (!$this->_careerlevel) {
            $db = Factory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_careerlevels` WHERE status = 1";
            $query.=" ORDER BY ordering ASC ";
            try{

                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
            $this->_careerlevel = $rows;
        }

        $careerlevel = array();
        if ($title)
            $careerlevel[] = array('value' => Text::_(''), 'text' => $title);
        foreach ($this->_careerlevel as $row) {
            $careerlevel[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $careerlevel;
    }

        function getTitleById($careerlevel_id) {
        if (is_numeric($careerlevel_id) == false)
            return false;
        $careerlevel_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_careerlevels` WHERE id = ".$careerlevel_id;
        $db->setQuery($query);
        $careerlevel_object = $db->loadObject();
        if(!empty($careerlevel_object)){
            $careerlevel_name = $careerlevel_object->title;
        }
        //echo var_dump($careerlevel_name);die('careerlevel_model 22114');
        return $careerlevel_name;
    }

}
?>