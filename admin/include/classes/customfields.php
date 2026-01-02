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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Editor\Editor;

class customfields {

    function formCustomFields($field , $obj_id, $obj_params ,  $resumeform = null, $section = null, $sectionid = null, $ishidden = null) {

        if ($resumeform != 1) {
            if ($field->isuserfield != 1) {
                return '';
            }
        }

        $cssclass = "";
        $html = '';
        $app = Factory::getApplication();
	$isadmin = Factory::getApplication()->isClient('administrator');

        if ($resumeform == 1) {
            //had to do this so that there are minimum changes in resume code
            $field = $this->userFieldData($field->field, 5, $section);
            if (empty($field)) {
                return '';
            }

            $div1 = '';
            $div2 = '';
            $div3 = '';

        } else {

            $div1 = ($isadmin) ? 'js-field-wrapper js-row no-margin' : 'js-col-md-12 js-form-wrapper';
            $div2 = ($isadmin) ? 'js-field-title js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding' : 'js-col-md-12 js-form-title';
            $div3 = ($isadmin) ? 'js-field-obj js-col-lg-9 js-col-md-9 no-padding' : 'js-col-md-12 js-form-value';

        }

        $required = $field->required;
        if($field->userfieldtype == 'termsandconditions'){
            if (isset($obj_id) && is_numeric($obj_id) && $obj_id != 0) {
                return false;
            }
            $required = 1;
        }
        $html = '<div class="' . $div1 . '">
               <div class="' . $div2 . '">';
        if ($required == 1) {
            $html .= Text::_($field->fieldtitle) . '<font color="red">*</font>';
            if ($field->userfieldtype == 'email')
                $cssclass = "required validate-email";
            else
                $cssclass = "required";
        }else {
            $html .= Text::_($field->fieldtitle);
            if ($field->userfieldtype == 'email')
                $cssclass = "validate-email";
            else
                $cssclass = "";
        }
        $html .= ' </div><div class="' . $div3 . '">';

        $resumeTitle = Text::_($field->fieldtitle);

        $size = '';
        $maxlength = '';
        if($field->size)
            $size = $field->size;
        if($field->maxlength)
            $maxlength = $field->maxlength;

        $fvalue = "";
        $value = "";
        $userdataid = "";
        if ($resumeform == 1) {

            $value = $obj_params;

            if($value){ // data has been stored
                $userfielddataarray = json_decode($value);
                $valuearray = json_decode($value,true);
            }else{
                $valuearray = array();
            }
            if(array_key_exists($field->field, $valuearray)){
                $value = $valuearray[$field->field];
                $value = Text::_($value);
            }else{
                $value = '';
            }
        } elseif (isset($obj_id)) {
            if(isset($obj_params)){ // data has been stored
                $userfielddataarray = json_decode($obj_params);
            }else{
                $userfielddataarray = array();
            }
            $uffield = $field->field;
            if (isset($userfielddataarray->$uffield) || !empty($userfielddataarray->$uffield)) {
                $value = $userfielddataarray->$uffield;
                $value = Text::_($value);
            } else {
                $value = '';
            }
        }

        $user_field = '';
        switch ($field->userfieldtype) {
            case 'text':
            case 'email':
                $extraattr = array('class' => "inputbox one $cssclass", 'data-validation' => $cssclass, 'size' => $size, 'maxlength' => $maxlength);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume
                $user_field .= $this->text($field->field, $value, $extraattr, $section , $sectionid);
                break;
            case 'date':
                $extraattr = array('class' => 'inputbox cal_userfield '.$cssclass, 'size' => '10', 'maxlength' => '19');
                $name = $field->field;
                // handleformresume
                if($section){
                    if($section != 1){
                        if($ishidden){
                            if ($required == 1) {
                                $extraattr['data-myrequired'] = $cssclass;
                                $extraattr['class'] = "inputbox cal_userfield";
                            }
                        }
                        $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                    }else{
                        $name = 'sec_'.$section.'['.$name.']';
                    }
                }
                //END handleformresume

                if (Factory::getApplication()->isClient('administrator')) {
                    $date_format = JSModel::getJSModel('configuration')->getConfigValue('date_format');
                }else{
                    $date_format = JSModel::getJSModel('configurations')->getConfigValue('date_format');
                }


                $date_format = "%".$date_format;
                $date_format = str_replace('-','-%',$date_format);
                $date_format = str_replace('/','/%',$date_format);
                $user_field .= HTMLHelper::_('calendar', $value, $name, $field->field, $date_format, $extraattr);
                break;
            case 'textarea':
                $rows = '';
                $cols = '';
                if($field->rows)
                    $rows = $field->rows;
                if($field->cols)
                    $cols = $field->cols;

                $extraattr = array('class' => "inputbox one $cssclass", 'data-validation' => $cssclass, 'rows' => $rows, 'cols' => $cols);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume

                //$user_field .= $this->textarea($field->field, $value, $extraattr , $section , $sectionid);
                $conf   = Factory::getConfig();
                $resume_editor = Editor::getInstance($conf->get('editor'));
                $name = $field->field;
                if($section){
                   if($section != 1){
                       $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                   }else{
                       $name = 'sec_'.$section.'['.$name.']';
                   }
                }
                $user_field .= $resume_editor->display( $name , $value, '100%', '100%', '60', '20', false);
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    $i = 0;
                    $valuearray = explode(', ',$value);
                    $name = $field->field;
                    $data_required = '';
                    if($section){
                        if($section != 1){
                            if($ishidden){
                                if($required == 1){
                                    $data_required = 'data-myrequired="required"';
                                    $cssclass = '';
                                }
                            }
                            $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                        }else{
                            $name = 'sec_'.$section.'['.$name.']';
                        }
                    }

                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $user_field .= '<input type="checkbox" ' . $check . ' '.$data_required.' class="radiobutton '.$cssclass.'" value="' . $option . '" id="' . $field->field . '_' . $sectionid . '_' . $i . '" name="' . $name . '[]">';
                        $user_field .= '<label class="cf_chkbox" for="' . $field->field . '_' . $sectionid . '_' . $i . '" id="foruf_checkbox1">' . Text::_($option) . '</label>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => Text::_($field->fieldtitle));
                    $extraattr = array('class' => "radiobutton $cssclass");
                    // handleformresume
                    if($section AND $section != 1){
                        if($ishidden){
                            if ($required == 1) {
                                $extraattr['data-myrequired'] = $cssclass;
                                $extraattr['class'] = "radiobutton";
                            }
                        }
                    }
                    //END handleformresume

                    $user_field .= $this->checkbox($field->field, $comboOptions, $value, array('class' => "radiobutton $cssclass") , $section , $sectionid);
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = Text::_($obj_option[$i]);
                    }
                }
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2,'".$section."','".$sectionid."');";
                }
                $extraattr = array('class' => "cf_radio radiobutton $cssclass" , 'data-validation' => $cssclass, 'onclick' => $jsFunction);
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "cf_radio radiobutton";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->radiobutton($field->field, $comboOptions, $value, $extraattr , $section , $sectionid);
                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => Text::_($opt));
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1,'".$section."','".$sectionid."');";
                }
                //end
                $extraattr = array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => "inputbox one $cssclass");
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->select($field->field, $comboOptions, $value, Text::_('Select') . ' ' . Text::_($field->fieldtitle), $extraattr , null,$section , $sectionid);
                break;
            case 'depandant_field':
                $comboOptions = array();
                if ($value != null) {
                    if (!empty($field->userfieldparams)) {
                        $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => Text::_($opt));
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1,'".$section."','".$sectionid."');";
                }
                //end
                $extraattr = array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => "inputbox one $cssclass");
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->select($field->field, $comboOptions, $value, Text::_('Select') . ' ' . Text::_($field->fieldtitle), $extraattr , null, $section , $sectionid);
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => Text::_($opt));
                    }
                }
                $name = $field->field;
                $name .= '[]';
                $valuearray = explode(', ', $value);
                $ismultiple = 1;
                $extraattr = array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => "inputbox one $cssclass jsjobs-file-wrp multiple");
                // handleformresume
                if($section AND $section != 1){
                    if($ishidden){
                        if ($required == 1) {
                            $extraattr['data-myrequired'] = $cssclass;
                            $extraattr['class'] = "inputbox one";
                        }
                    }
                }
                //END handleformresume

                $user_field .= $this->select($name, $comboOptions, $valuearray, '', $extraattr , null ,$section , $sectionid , $ismultiple);
                break;
            case 'file':
                if($value != null){ // since file already uploaded so we reglect the required
                    $cssclass = str_replace('required', '', $cssclass);
                }

                $name = $field->field;
                $data_required = '';
                if($section){
                    if($section != 1){
                        if($ishidden){
                            if($required == 1){
                                $data_required = 'data-myrequired="required"';
                                $cssclass = '';
                            }
                        }
                        $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                    }else{
                        $name = 'sec_'.$section.'['.$name.']';
                    }
                }

                $user_field .= '<input type="file" class="'.$cssclass.' cf_uploadfile" '.$data_required.' name="'.$name.'" id="'.$field->field.'"/>';
                if(Factory::getApplication()->isClient('administrator')){
                    $config = JSModel::getJSModel('configuration')->getConfig();
                }else{
                    $config = JSModel::getJSModel('configurations')->getConfig('');
                }
                $fileext  = '';
                foreach ($config as $conf) {
                    if ($conf->configname == 'image_file_type'){
                        if($fileext)
                            $fileext .= ',';
                        $fileext .= $conf->configvalue;
                    }
                    if ($conf->configname == 'document_file_type'){
                        if($fileext)
                            $fileext .= ',';
                        $fileext .= $conf->configvalue;
                    }
                    if ($conf->configname == 'document_file_size')
                        $maxFileSize = $conf->configvalue;
                }

                $fileext = explode(',', $fileext);
                $fileext = array_unique($fileext);
                $fileext = implode(',', $fileext);
                $user_field .= '<div id="js_cust_file_ext">'.Text::_('Files').'&nbsp;('.$fileext.')<br> '.Text::_('Maximum Size').' '.$maxFileSize.'(kb)</div>';
                if($value != null){
                    $user_field .= $this->hidden($field->field.'_1', 0 , array(), $section , $sectionid);
                    $user_field .= $this->hidden($field->field.'_2',$value, array(), $section , $sectionid);
                    $jsFunction = "deleteCutomUploadedFile('".$field->field."','".$field->required."')";
                    $file_name = explode('_', $value , 2);
                    if(isset($file_name[1]))
                        $file_name = $file_name[1];
                    else
                        $file_name = $file_name[0];
                    $path = JSModel::getJSModel('common')->getUploadedCustomFile($obj_id , $value , $field->fieldfor );
                    $user_field .='<span class='.$field->field.'_1><a download="'.$file_name.'" href="' . $path . '">'.$file_name.'</a>( ';
                    $user_field .= "<a href='javascript:void(0)' onClick=".$jsFunction." >". Text::_('Delete')."</a>";
                    $user_field .= ' )</span>';
                }
                break;
            case 'termsandconditions':
                if (isset($obj_id) && is_numeric($obj_id) && $obj_id != 0) {
                    break;
                }
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams,true);

                    $url = $obj_option['termsandconditions_link'];
                    if( isset($obj_option['termsandconditions_linktype']) && $obj_option['termsandconditions_linktype'] == 2){
                        // $url = 'index.php?option=com_content&view=article&id=' . $obj_option['termsandconditions_page'];
                        $url = 'index.php?option=com_content&view=article&id=' . $obj_option['termsandconditions_page'] . '&Itemid=';
                    }

                    $link_start = '<a href="' . $url . '" class="termsandconditions_link_anchor" target="_blank" >';
                    $link_end = '</a>';

                    if(strstr($obj_option['termsandconditions_text'], '[link]') && strstr($obj_option['termsandconditions_text'], '[/link]')){
                        $label_string = str_replace('[link]', $link_start, $obj_option['termsandconditions_text']);
                        $label_string = str_replace('[/link]', $link_end, $label_string);
                    }else{
                        $label_string = $obj_option['termsandconditions_text'].'&nbsp;'.$link_start.$field->fieldtitle.$link_end;
                    }
                    $c_field_required = '';
                    if($field->required == 1){
                        $c_field_required = 'required';
                    }
                    // ticket terms and conditonions are required.
                    // if($field->fieldfor == 1){
                    //     $c_field_required = 'required';
                    // }

                    $user_field .= '<div class="js-job-custom-terms-and-condition-box jsjob-formfield-radio-button-wrap" style="display:inline-block;">';
                    $name = $field->field;
                    $data_required = '';
                    if($c_field_required == 'required'){
                        $data_required = 'required='. $c_field_required . '';
                    }
                    $data_validation = 'data-validation="'.$c_field_required.'"';
                    if (!class_exists('getJSJobsPHPFunctionsClass')) { // fix js jobs register plugin selct role field
                        require_once (JPATH_ADMINISTRATOR.'/components/com_jsjobs/include/classes/jsjobsphpfunctions.php');
                        $obj = new jsjobsphpfunctions();
                        $check_flag = $obj->jsjobs_strpos($name, '[]');
                    }else{
                        $check_flag = getJSJobsPHPFunctionsClass()->jsjobs_strpos($name, '[]');
                    }

                    if ($check_flag !== false) {
                        $id = str_replace('[]', '', $name);
                    }else{
                        $id = $name;
                    }
                    if($section){
                        if($section != 1){
                            if($ishidden){
                                if($required == 1){
                                    $data_required = 'data-myrequired="required"';
                                    $cssclass = '';
                                }
                            }
                            $name = 'sec_'.$section.'['.$name.']['.$sectionid.']';
                            $id .= $sectionid;
                        }else{
                            $name = 'sec_'.$section.'['.$name.']';
                        }
                    }
                    $user_field .= '<input type="checkbox" value="1" id="' . $id . '" name="' . $name . '" '.$data_required.' '.$data_validation.'  style="margin-right:5px;margin-top:5px;">';
                    $user_field .= '<label for="' . $id . '" id="foruf_checkbox1" style="display:inline-block;">' . $label_string . '</label>';
                    $user_field .= '</div>';
                }
                break;
        }

        $html .= $user_field;
        $html .= '</div></div>';

        if ($resumeform === 1) {
            return array('title' => $resumeTitle , 'value' => $user_field);
        }elseif($resumeform == 'admin'){
            return array('title' => $resumeTitle , 'value' => $user_field , 'lable' => $field->field);
        }elseif($resumeform == 'f_company'){
            return array('title' => $resumeTitle , 'value' => $user_field , 'lable' => $field->field);
        }elseif($resumeform == 'gdpr_fields'){
            return $html;
        }else {
            echo $html;
        }
    }

    function formCustomFieldsForSearch($field, &$i, $filter_params ,  $datafor = null) { // $filter_params
        if ($field->isuserfield != 1)
            return false;
        $cssclass = "";
        $html = '';
        $i++;
        $div1 = 'js-col-md-12 js-form-wrapper';
        $div2 = 'js-col-md-12 js-form-title';
        $div3 = 'js-col-md-12 js-form-value';

        $html = '<div class="' . $div1 . '">
               <div class="' . $div2 . '">';
        $html .= Text::_($field->fieldtitle);
        $html .= ' </div><div class="' . $div3 . '">';

        $field_title = $field->fieldtitle;

        $readonly = '';
        $maxlength = '';
        $fvalue = "";
        $value = null;
        $userdataid = "";
        $userfielddataarray = array();
        if (isset($filter_params)) {
            $userfielddataarray = $filter_params;
            $uffield = $field->field;
            //had to user || oprator bcz of radio buttons

            if (isset($userfielddataarray[$uffield]) || !empty($userfielddataarray[$uffield])) {
                $value = $userfielddataarray[$uffield];
                if($value){
                    if(!is_array($value)){
                        $value = Text::_($value);
                    }else{
                        $value = $value;
                    }
        		}else{
        		 	$value = "";
        		}
            } else {
                $value = '';
            }
        }
        $field_value = '';
        switch ($field->userfieldtype) {
            case 'text':
            case 'email':
            case 'file':
                $field_value .= $this->text($field->field, $value, array('class' => "inputbox one $cssclass", 'data-validation' => $cssclass,'placeholder' => Text::_($field->fieldtitle)));
                break;
            case 'date':
                $field_value .= HTMLHelper::_('calendar', $value, $field->field, $field->field, '%Y-%m-%d', array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19'));
                break;
            case 'editor':
                break;
            case 'textarea':
                $rows = '';
                $cols = '';
                if(isset($field->rows))
                    $rows = $field->rows;
                if(isset($field->cols))
                    $cols = $field->cols;

                $field_value .= $this->textarea($field->field, $value, array('class' => "inputbox one $cssclass", 'data-validation' => $cssclass, 'rows' => $rows, 'cols' => $cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    if(empty($value))
                        $value = array();
                    foreach ($obj_option AS $option) {
                        if( in_array($option, $value)){
                            $check = 'checked="true"';
                        }else{
                            $check = '';
                        }
                        $field_value .= '<input type="checkbox" ' . $check . ' class="radiobutton cflabelcb" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]">';
                        $field_value .= '<label for="' . $field->field . '_' . $i . '" class="cflabelcb" id="foruf_checkbox1">' . $option . '</label>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => Text::_($field->fieldtitle) );
                    $field_value .= $this->checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    for ($i = 0; $i < count($obj_option); $i++) {
                        $comboOptions[$obj_option[$i]] = Text::_($obj_option[$i]);
                    }
                }
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2,1,'');";
                }
                $field_value .= $this->radiobutton($field->field, $comboOptions, $value, array('class' => 'radiobutton cflabelcb' , 'data-validation' => $cssclass, "autocomplete" => "off", 'onclick' => $jsFunction));
                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => Text::_($opt));
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1,1,'');";
                }
                //end
                $field_value .= $this->select($field->field, $comboOptions, $value, Text::_('Select') . ' ' . Text::_($field->fieldtitle), array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => "inputbox one $cssclass"));
                break;
            case 'depandant_field':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                    if (!empty($obj_option)) {
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => Text::_($opt));
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1 ,1,'');";
                }
                //end
                $field_value .= $this->select($field->field, $comboOptions, $value, Text::_('Select') . ' ' . Text::_($field->fieldtitle), array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => "inputbox one $cssclass"));
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => Text::_($opt));
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $field_value .= $this->select($array, $comboOptions, $value, Text::_('Select') . ' ' . Text::_($field->fieldtitle), array('data-validation' => $cssclass, null ,'multiple' => 'multiple','class' => 'multiple'));
                break;
        }

        $html .= $field_value;
        $html .= '</div></div>';

        if ($datafor == 1) {
            if($field_value){
                return array('title' => Text::_($field_title) , 'field' => $field_value);
            }
            return '';
        } else {
            echo $html;
        }
    }

    function getUserFieldByField($field){
        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__js_job_fieldsordering` WHERE field = '".$field."' AND isuserfield = 1 AND userfieldtype != 'termsandconditions'";
        $db->setQuery($query);
        $field = $db->loadObject();
        return $field;
    }


    function showCustomFields($field, $fieldfor, $object , $labelinlisting = 1) { // lableinlisting configuration for Job Labels
        $html = '';
        $fvalue = '';
        if($field == '')
            return false;
        $value = '';
        $title = '';
        if($fieldfor == 11){
            $field = $this->getUserFieldByField($field);
            if(empty($field)){
                return false;
            }
        }

        $params = $object->params;
        if(isset($object->id)){
            $id = $object->id;
        }else{
            $id = $object->jobid;
        }

        if(!empty($params)){
            $data = json_decode($params,true);
            if(array_key_exists($field->field, $data)){
                if($field->userfieldtype == 'date'){
                    
                    if (Factory::getApplication()->isClient('administrator')) {
                        $date_format = JSModel::getJSModel('configuration')->getConfigValue('date_format');
                    }else{
                        $date_format = JSModel::getJSModel('configurations')->getConfigValue('date_format');
                    }
                    if(isset($data[$field->field]) && $data[$field->field] != ''){
                        $fvalue = HTMLHelper::_('date', $data[$field->field], $date_format);
                    }else{
                        $fvalue = '';
                    }
                }else{
                    $fvalue = $data[$field->field];
                }
            }
        }
        $css1 = ''; $css2 = ''; $css3 = '';
        $css4 = ''; $css5 = ''; $wrapperelement = '';
        $uploadcustomfilevariable = '';

        if($fieldfor == 1){ // jobs listing
            $css1 = 'js-col-xs-12 js-col-sm-6 js-col-md-4 js-fields for-rtl joblist-datafields custom-field-wrapper';
            $css2 = 'js-bold';  $css3 = 'get-text'; $css4 = 'js-bold';
            $css5 = 'get-text'; $wrapperelement = 'div';
            $uploadcustomfilevariable = 2;
        }elseif($fieldfor == 7){ //
            $css1 = 'custom-field-wrapper';
            $css2 = 'js-bold';  $css3 = 'get-text'; $css4 = 'js-bold';
            $css5 = 'get-text'; $wrapperelement = 'div';
            $uploadcustomfilevariable = 2;
        }elseif($fieldfor == 9){ //myresume
            $css1 = 'js-myresume-field-wrapper';   $css2 = 'js-myresume-field-title';
            $css3 = 'js-myresume-field-value'; $css4 = 'js-myresume-field-title';
            $css5 = 'js-myresume-field-value'; $wrapperelement = 'span';
            $uploadcustomfilevariable = 3;
        }elseif($fieldfor == 10){ //resume listing
            $css1 = 'jsjobs-main-wrap';   $css2 = 'js_job_data_2_title';
            $css3 = 'js_job_data_2_value'; $css4 = 'js_job_data_2_title';
            $css5 = 'js_job_data_2_value'; $wrapperelement = 'span';
            $uploadcustomfilevariable = 3;
        }elseif($fieldfor == 4){ // company listing
            $css1 = 'jsjobs-listcompany-location jsjobs-location-wrp';   $css2 = 'jsjobs-listcompany-website';
            $css3 = 'js-get-value'; $css4 = 'jsjobs-listcompany-website';
            $css5 = 'js-get-value'; $wrapperelement = 'span';
            $uploadcustomfilevariable = 1;
        }elseif($fieldfor == 5){ // company view
            $css1 = 'js_job_data_wrapper';   $css2 = 'js_job_data_title';
            $css3 = 'js_job_data_value'; $css4 = 'js_job_data_title';
            $css5 = 'js_job_data_value'; $wrapperelement = 'div';
            $uploadcustomfilevariable = 1;
        }elseif($fieldfor == 8){ // mycompanies
            $css1 = 'jsjobs-data-2-wrapper';   $css2 = 'jsjobs-data-2-title';
            $css3 = 'jsjobs-data-2-value'; $css4 = 'jsjobs-data-2-title';
            $css5 = 'jsjobs-data-2-value'; $wrapperelement = 'div';
            $uploadcustomfilevariable = 1;
        }elseif($fieldfor == 11 || $fieldfor == 6){ // view resume
            if($field->userfieldtype == 'file'){
                if($fvalue != null){
                    $file_name = explode('_', $fvalue , 2);
                    $file_name = $file_name[1];
                    if(isset($object->resumeid)){
                        $id = $object->resumeid;
                    }
                    $path = JSModel::getJSModel('common')->getUploadedCustomFile($id , $fvalue , 3 );
                    $html = '<a download="'.$file_name.'" href="' . $path . '">' . $file_name . '</a>';
                }
                return array('title' => Text::_($field->fieldtitle), 'value' => $html);
            }else{
                return array('title' => Text::_($field->fieldtitle), 'value' => Text::_($fvalue) );
            }
        }elseif($fieldfor == 2){ // job view
            $css1 = 'js_job_data_wrapper';   $css2 = 'js_job_data_title';
            $css3 = 'js_job_data_value'; $css4 = 'js_job_data_title';
            $css5 = 'js_job_data_value'; $wrapperelement = 'div';
            $uploadcustomfilevariable = 2;
        }elseif($fieldfor == 12){ // MY JOBS
            $css1 = 'jsjobs-data-2-wrapper js_forcat';   $css2 = 'js_job_data_2_title';
            $css3 = 'js_job_data_2_value'; $css4 = 'js_job_data_2_title';
            $css5 = 'js_job_data_2_value'; $wrapperelement = 'div';
            $uploadcustomfilevariable = 2;
        }elseif($fieldfor == 3){ // Shortlisted jobs , myappliedjobs
            $css1 = 'jsjobs-data-2-wrapper';   $css2 = 'jsjobs-data-2-title';
            $css3 = 'jsjobs-data-2-value'; $css4 = 'jsjobs-data-2-title';
            $css5 = 'jsjobs-data-2-value'; $wrapperelement = 'div';
        }
        //for jobi template
        if(Factory::getApplication()->getTemplate() != 'jobi'){
            $html = '<'.$wrapperelement.' class="'.$css1.'">';
        }
        if($field->userfieldtype == 'file'){
            if(Factory::getApplication()->getTemplate() != 'jobi'){
                if ($labelinlisting == 1) {
                    $html .= '<span class="'.$css2.'">' . Text::_($field->fieldtitle) . ':&nbsp</span>';
                }
            }
            $title = Text::_($field->fieldtitle);
            if($fvalue != null){
                if($uploadcustomfilevariable == 3){
                    if(isset($object->resumeid)){
                        $id = $object->resumeid;
                    }
                }

                $file_name = explode('_', $fvalue , 2);
                if(count($file_name) > 1){
                    $file_name = $file_name[1];    
                }else{
                    $file_name = $file_name[0];    
                }
                

                $path = JSModel::getJSModel('common')->getUploadedCustomFile($id , $fvalue , $uploadcustomfilevariable);
                if(Factory::getApplication()->getTemplate() != 'jobi'){
                    $html .= '
                        <span class="'.$css3.'">
                            <a download="'.$file_name.'" href="' . $path . '">' . $file_name . '</a>
                        </span>';
                } else {
                    $value = '<a download="'.$file_name.'" href="' . $path . '">' . $file_name . '</a>';
                }
            }
        }else{
            if ($labelinlisting == 1) {
                if(Factory::getApplication()->getTemplate() != 'jobi'){
                    $html .= '<span class="'.$css4.'">' . Text::_($field->fieldtitle) . ':&nbsp</span>';
                } else {
                    $title = Text::_($field->fieldtitle);
                }
            }
            if(Factory::getApplication()->getTemplate() != 'jobi'){
              $html .= '<span class="'.$css5.'">' . Text::_($fvalue) . '</span>';
            }
            else {
                $value = Text::_($fvalue);
            }
        }
        if(Factory::getApplication()->getTemplate() != 'jobi'){
            $html .=   '</'.$wrapperelement.'>';
        }
        if(Factory::getApplication()->getTemplate() == 'jobi') {
            return array('title' => $title, 'value' => $value);
        } else {
            return $html;
        }
    }

    function userFieldData($field, $fieldfor, $section = null) {
        $db = Factory::getDbo();

        if(empty($field))
            return '';

        $user = Factory::getUser();
        if ($user->guest) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }

        $ff = '';
        if ($fieldfor == 2 || $fieldfor == 3) {
            $ff = " AND fieldfor = 2 ";
        } elseif ($fieldfor == 1 || $fieldfor == 4) {
            $ff = "AND fieldfor = 1 ";
        } elseif ($fieldfor == 5) {
            $ff = "AND fieldfor = 3 ";
        } elseif ($fieldfor == 6) {
            //form resume
            $ff = "AND fieldfor = 3 ";
            if(is_numeric($section)){
                $ff .= " AND section = $section ";
            }
        }
        $query = "SELECT * FROM `#__js_job_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND field ='" . $field . "'" . $ff;
        $db->setQuery($query);
        $data = $db->loadObject();
        return $data;
    }

    function userFieldsData($fieldfor, $listing = null , $getpersonal = null) {

        $db = Factory::getDbo();
        $user = Factory::getUser();

        if( ! is_numeric($fieldfor))
            return '';

        if ($user->guest) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }

        $inquery = '';
        if ($listing == 1) {
            $inquery .= ' AND showonlisting = 1 ';
        }

        if( $getpersonal == 1){
            $inquery .= ' AND section = 1 ';

        }


        $query = "SELECT * FROM `#__js_job_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND fieldfor =" . $fieldfor . $inquery . ' ORDER BY id ASC';

        $db->setQuery($query);
        $data = $db->loadObjectList();
        return $data;
    }

    function getDataForDepandantFieldByParentField($fieldfor, $data) {

        $db = Factory::getDbo();
        $user = Factory::getUser();

        if(empty($fieldfor))
            return '';

        if ($user->guest) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }

        $value = '';
        $returnarray = array();
        $query = "SELECT field FROM `#__js_job_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND depandant_field ='" . $fieldfor . "'";
        $db->setQuery($query);
        $field = $db->loadResult();
        if ($data != null) {
            foreach ($data as $key => $val) {
                if ($key == $field) {
                    $value = $val;
                }
            }
        }
        $query = "SELECT userfieldparams FROM `#__js_job_fieldsordering` WHERE isuserfield = 1 AND " . $published . " AND field ='" . $fieldfor . "'";
        $db->setQuery($query);
        $field = $db->loadResult();
        if (isset($field)) {
            $fieldarray = json_decode($field);
            foreach ($fieldarray as $key => $val) {
                if ($value == $key)
                    $returnarray = $val;
            }
        }
        return $returnarray;
    }

    // new
    static function text($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        if (getJSJobsPHPFunctionsClass()->jsjobs_strpos($name, '[]') !== false) {
            $id = str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textfield = '<input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }
    static function textarea($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textarea = '<textarea name="' . $name . '" id="' . $name . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textarea .= ' ' . $key . '="' . $val . '"';
        $textarea .= ' >' . $value . '</textarea>';
        return $textarea;
    }
    static function hidden($name, $value, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        $id = $name;
        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        $textfield = '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }
    static function select($name, $list, $defaultvalue, $title = '', $extraattr = array() , $disabled = '',  $resume_section_id = null , $sectionid = null , $ismultiple = false) {
        if (getJSJobsPHPFunctionsClass()->jsjobs_strpos($name, '[]') !== false) {
            $id = str_replace('[]', '', $name);
        }else{
            $id = $name;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                if($ismultiple){
                    $name = str_replace('[]', '', $name);
                    $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.'][]';
                    $id .= $sectionid;
                }else{
                    $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
                    $id .= $sectionid;
                }
            }else{
                if($ismultiple){
                    $name = str_replace('[]', '', $name);
                    $name = 'sec_'.$resume_section_id.'['.$name.'][]';
                }else{
                    $name = 'sec_'.$resume_section_id.'['.$name.']';
                }
            }
        }
        //END handleformresume

        $selectfield = '<select name="' . $name . '" id="' . $id . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' . $val . '"';
            }
        if($disabled)
            $selectfield .= ' disabled>';
        else
            $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . $title . '</option>';
        }
        if (!empty($list))
            foreach ($list AS $record) {
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option selected="selected" value="' . $record->id . '">' . Text::_($record->text) . '</option>';
                else
                    $selectfield .= '<option value="' . $record->id . '">' . Text::_($record->text) . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }
    static function radiobutton($name, $list, $defaultvalue, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        $radiobutton = '';
        $count = 1;
        $match = false;
        $firstvalue = '';
        foreach($list AS $value => $label){
            if($firstvalue == '')
                $firstvalue = $value;
            if($defaultvalue == $value){
                $match = true;
                break;
            }
        }
        if($match == false){
            //$defaultvalue = $firstvalue;
        }

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.']';
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.']';
            }
        }
        //END handleformresume

        foreach ($list AS $value => $label) {
            $radiobutton .= '<input type="radio" name="' . $name . '" id="' . $name . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value){
                $radiobutton .= ' checked="checked"';
            }
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $radiobutton .= ' ' . $key . '="' . $val . '"';
                }
            $radiobutton .= '/><label id="for' . $name . '" class="cf_radiobtn" for="' . $name . $count . '">' . $label . '</label>';
            $count++;
        }
        return $radiobutton;
    }
    static function checkbox($name, $list, $defaultvalue, $extraattr = array() , $resume_section_id = null , $sectionid = null) {
        $checkbox = '';
        $count = 1;

        // handleformresume
        if($resume_section_id){
            if($resume_section_id != 1){
                $name = 'sec_'.$resume_section_id.'['.$name.']['.$sectionid.'][]';
            }else{
                $name = 'sec_'.$resume_section_id.'['.$name.'][]';
            }
        }
        //END handleformresume

        foreach ($list AS $value => $label) {
            $checkbox .= '<input type="checkbox" name="' . $name . '" id="' . $name . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value)
                $checkbox .= ' checked="checked"';
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $checkbox .= ' ' . $key . '="' . $val . '"';
                }
            $checkbox .= '/><label id="for' . $name . '" for="' . $name . $count . '">' . $label . '</label>';
            $count++;
        }
        return $checkbox;
    }
}
?>
