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

class JSJobsModelHighesteducation extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_heighesteducation = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getHeighestEducation($title) {
        if (!$this->_heighesteducation) {
            $db = Factory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_heighesteducation` WHERE isactive = 1";
            $query.=" ORDER BY ordering ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
            $this->_heighesteducation = $rows;
        }
        $heighesteducation = array();
        if ($title)
            $heighesteducation[] = array('value' => Text::_(''), 'text' => $title);

        foreach ($this->_heighesteducation as $row) {
            $heighesteducation[] = array('value' => $row->id, 'text' => Text::_($row->title));
        }
        return $heighesteducation;
    }


    function getTitleById($highesteducation_id) {
        if (is_numeric($highesteducation_id) == false)
            return false;
        $highesteducation_name = '';
        $db = Factory::getDBO();
        $query = "SELECT title FROM `#__js_job_heighesteducation` WHERE id = ".$highesteducation_id;
        $db->setQuery($query);
        $highesteducation_object = $db->loadObject();
        if(!empty($highesteducation_object)){
            $highesteducation_name = $highesteducation_object->title;
        }
        //echo var_dump($highesteducation_name);die('highesteducation_model 22114');
        return $highesteducation_name;
    }

}
?>