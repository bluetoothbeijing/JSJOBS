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

class JSJobsModelCustomFields extends JSModel {

    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getFieldsOrdering($fieldfor, $visitor = false) {
        if (is_numeric($fieldfor) === false)
            return false;

        $db = $this->getDBO();
        if ($fieldfor == 16 ) { // resume visitor case
            $fieldfor = 3;
            $query = "SELECT *,isvisitorpublished AS published
                        FROM `#__js_job_fieldsordering`
                        WHERE isvisitorpublished = 1 AND fieldfor =  " . $fieldfor
                    ." ORDER BY";
        } else {
            $published_field = "published = 1";
            if ($visitor == true) {
                $published_field = "isvisitorpublished = 1";
            }
            $query = "SELECT * FROM `#__js_job_fieldsordering`
			WHERE " . $published_field . " AND fieldfor =  " . $fieldfor
                    . " ORDER BY";
        }
        if ($fieldfor == 3) // fields for resume
            $query.=" section ,";
        $query.=" ordering";


        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getFieldsOrderingForResumeView($fieldfor) { // created and used by muhiaudin for resume layout 'view_resume'
        $user = Factory::getUser();
        if (is_numeric($fieldfor) === false)
            return false;
        $db = $this->getDBO();
        if ($fieldfor == 16) { // resume visitor case
            $fieldfor = 3;
            $query = "SELECT *,isvisitorpublished AS published
                        FROM `#__js_job_fieldsordering`
                        WHERE isvisitorpublished = 1 AND fieldfor =  " . $fieldfor
                    . " ORDER BY section,ordering";
        } else {
            $published_field = "published = 1";
            if ($user->guest) {
                $published_field = "isvisitorpublished = 1";
            }
            $query = "SELECT * FROM `#__js_job_fieldsordering`
                        WHERE " . $published_field . " AND fieldfor =  " . $fieldfor
                    . " ORDER BY section,ordering";
        }
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        foreach ($fields as $field) {
            switch ($field->section) {
                case 1: $fieldsOrdering['personal'][] = $field;
                    break;
                case 2: $fieldsOrdering['address'][] = $field;
                    break;
                case 3: $fieldsOrdering['institute'][] = $field;
                    break;
                case 4: $fieldsOrdering['employer'][] = $field;
                    break;
                case 5: $fieldsOrdering['skills'][] = $field;
                    break;
                case 6: $fieldsOrdering['resume'][] = $field;
                    break;
                case 7: $fieldsOrdering['reference'][] = $field;
                    break;
                case 8: $fieldsOrdering['language'][] = $field;
                    break;
            }
        }
        return $fieldsOrdering;
    }

    function getResumeFieldsOrderingBySection($section) {
        if(!is_numeric($section))
            return false;

        $db = $this->getDBO();
        $user = Factory::getUser();
        $uid = $user->id;

        $is_visitor = '';
        if ($uid != "" AND $uid != 0){ // is admin Or is logged in
            $published = "published = 1";
        }else{
            $published = "isvisitorpublished = 1";
            $is_visitor = ' , fields.isvisitorpublished AS published ';
        }

        $query = "SELECT fields.* ".$is_visitor." FROM `#__js_job_fieldsordering` AS fields
            WHERE ".$published." AND fieldfor = 3 AND section = ".$section;
        $query .= " ORDER BY section,ordering ASC";

        $db->setQuery($query);
        $objectlist = $db->loadObjectList();
        return $objectlist;
    }

    function parseFieldsOrderingForView($fieldsordering) {
        if (!is_array($fieldsordering))
            return false;
        $fields = array();
        $user = Factory::getUser();
        foreach ($fieldsordering AS $field) {
            $fields[$field->field] = ($user->guest) ? $field->isvisitorpublished : $field->published;
        }
        return $fields;
    }

    function getFieldRequiredByNameAndFor($fieldname,$fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $db = $this->getDbo();
        $query = "SELECT required FROM `#__js_job_fieldsordering` WHERE fieldfor = ".$fieldfor." and field = ".$db->Quote($fieldname);
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
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
        $query = "SELECT field,userfieldparams,userfieldtype FROM `#__js_job_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 " . $published;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function dataForDepandantField( $val , $childfield, $type, $section, $sectionid){
        $db = $this->getDBO();
        $query = "SELECT field,userfieldparams,fieldtitle,required,depandant_field FROM `#__js_job_fieldsordering` WHERE field = ".$db->Quote($childfield);
        $db->setQuery($query);
        $data = $db->loadObject();
        $decoded_data = json_decode($data->userfieldparams);
        $customfieldobj = getCustomFieldClass();
        $comboOptions = array();

        foreach ($decoded_data as $key => $value) {
            if($key == $val){
               for ($i=0; $i < count($value) ; $i++) {
                   $comboOptions[] = (object)array('id' => $value[$i], 'text' => $value[$i]);
                }
            }
        }
        //code for handling dependent field
        $jsFunction = '';
        if ($data->depandant_field != null) {
            $jsFunction = "getDataForDepandantField('" . $data->field . "','" . $data->depandant_field . "',1,'".$section."','".$sectionid."');";
        }
        //end
        $required = $data->required;
        if ($required == 1) {
            $cssclass = "required";
        }else {
            $cssclass = "";
        }
        // end
        $extraattr = array('data-validation' => $cssclass, 'class' => "inputbox one $cssclass");
        if(""!=$jsFunction){
            $extraattr['onchange']=$jsFunction;
        }
        // handleformresume
        if($section AND $section != 1){
            if(isset($ishidden)){
                if ($required == 1) {
                    $extraattr['data-myrequired'] = $cssclass;
                    $extraattr['class'] = "inputbox one";
                }
            }
        }
        //END handleformresume
        $html = $customfieldobj->select($childfield, $comboOptions, $val, Text::_('Select') . ' ' . Text::_($data->fieldtitle), $extraattr , null,$section , $sectionid);
        return $html;
    }


    function getFieldTitleByFieldAndFieldfor($field,$fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $db = Factory::getDBO();
        $query = "SELECT fieldtitle FROM `#__js_job_fieldsordering` WHERE field = ".$db->Quote($field)." AND fieldfor = ".$fieldfor;
        $db->setQuery($query);
        $title = $db->loadResult();
        return $title;
    }

    function getUnpublishedFieldsFor($fieldfor,$section = null){
        if(!is_numeric($fieldfor)) return false;
        if($section != null)
            if(!is_numeric($section)) return false;
        $user = Factory::getUser();
        if($user->guest){
            $publihsed = ' isvisitorpublished = 0 ';
        }else{
            $publihsed = ' published = 0 ';
        }

        if($section != null){
            $publihsed .= ' AND section = '.$section;
        }

        $db = Factory::getDbo();
        $query = "SELECT field FROM `#__js_job_fieldsordering` WHERE fieldfor = ".$fieldfor." AND ".$publihsed;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getPublishedUserfieldsfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        $db = Factory::getDBO();

        $published = '';
        $user = Factory::getUser();
        $uid = $user->id;

        $is_visitor = '';
        if ($uid != "" AND $uid != 0){ // is admin Or is logged in
            $published = "AND published = 1";
        }else{
            $published = "AND isvisitorpublished = 1";
        }
        $query = "SELECT field,userfieldparams,userfieldtype FROM `#__js_job_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 " . $published;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }


}
?>
