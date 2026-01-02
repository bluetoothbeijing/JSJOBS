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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Editor\Editor;


class JSJobsResumeformlayout {

    function makePersonalSectionFields($result, $isadmin, $cf_object, $config) {
        $resume = $result[0];
        $fields_ordering = $result[1];
        $resumelists = $result[2];

        $js_dateformat = $this->prepareDateFormat($config);
        $sectionid = 0;
        $data = '<div class="jssectionwrapper">';
            $name_counter = 0;
            $cell_counter = 0;
            $date_counter = 0;
            $available_counter = 0;
            foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "application_title":
                            $fieldValue = isset($resume) ? $resume->application_title : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue);
                        break;
                    case "first_name":
                    case "middle_name":
                    case "last_name":
                        if($name_counter == 0){ 
                            $data .= '<div class="fullwidthwrapper">';
                                $field_obj = '';
                                foreach ($fields_ordering as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "first_name":
                                                $fieldValue = isset($resume) ? $resume->first_name : "";
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3);
                                            break;
                                        case "middle_name":
                                                $fieldValue = isset($resume) ? $resume->middle_name : "";
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3);
                                            break;
                                        case "last_name":
                                                $fieldValue = isset($resume) ? $resume->last_name : "";
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3);
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $name_counter = 1;
                        break;
                    case "email_address": $email_required = ($field->required ? 'required' : '');
                            $fieldValue = isset($resume) ? $resume->email_address : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue);
                        break;
                    case "cell":
                    case "home_phone":
                    case "work_phone":
                        if($cell_counter == 0){ 
                            $data .= '<div class="fullwidthwrapper">';
                                $field_obj = '';
                                foreach ($fields_ordering as $field_obj) {    
                                    switch ($field_obj->field) {
                                        case "cell":
                                            $fieldValue = isset($resume) ? $resume->cell : "";
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3);
                                            break;
                                        case "home_phone":
                                            $fieldValue = isset($resume) ? $resume->home_phone : "";
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3);
                                            break;
                                        case "work_phone":
                                            $fieldValue = isset($resume) ? $resume->work_phone : "";
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3);
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $cell_counter = 1;
                        break;
                    case "gender":
                            $fieldValue = $resumelists['gender'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "photo":
                        $photo_required = ($field->required ? 'required' : '');
                        $imgpath = '';
                        if (!empty($resume->photo)) {
                            if(isset($resume->image_path)){
                                $imgpath = $resume->image_path;
                            }else{
                                $imgpath = Uri::root() . $config['data_directory'] . '/data/jobseeker/resume_' . $resume->id . '/photo/' . $resume->photo;
                            }
                        } else {
                            $imgpath = Uri::root() . '/components/com_jsjobs/images/user_b.png';
                        }
                        $data .= '
                            <div class="resumefieldswrapper">
                                <label class="resumefieldtitle" for="photo">'.Text::_($field->fieldtitle);
                                    if ($photo_required) {
                                        $data .= '<span class="error-msg">*</span>';
                                    }
                        $data .= '</label>
                                <div class="resumefieldvalue">
                                    <div class="resumephoto">
                                        <div class="photowrapper">
                                            <img id="resume_img" class="resume_img" src="'.$imgpath.'" />
                                        </div>
                                    </div>
                                    <div class="photodetail">';
                                    if(!empty($resume->photo)){
                                        $data.='<div class="fieldvalue_checkboxoption"><input type="checkbox" name="sec_1[deletelogo]" value="1">'.Text::_('Delete Logo File').'['.$resume->photo.']</div>';
                                    }
                                    $data.='<input type="file" class="inputbox" name="photo" id="photo" />
                                        <br>
                                        <small>' . $config['image_file_type'] . '</small>
                                        <br><small>' . Text::_('Maximum File Size') . ' (' . $config['resume_photofilesize'] . 'KB)</small>
                                    </div>
                                </div>
                            </div>';
                        break;
			case "resumefiles":
                        $files_required = ($field->required ? 'required' : '');
                        $data .= '
                            <div class="resumefieldswrapper">
                                <label class="resumefieldtitle" for="resumefile">' . Text::_($field->fieldtitle);
                                    if ($field->required == 1) {
                                        $data .= '<span class="error-msg">*</span>';
                                    }
                                $data .= '</label>
                                <div class="resumefieldvalue" id="resumefileswrapper">
                                    <div class="files-field">
                                        <span id="resumeFileSelector" onclick="return resumeFilesSelection();" class="upload_btn">'.Text::_('Choose Files').'</span>
                                        <div id="selectedFiles" class="selectedFiles" onclick="return resumeFilesSelection();">' . Text::_('No File Selected') . '</div>
                                    </div>
                                    <small class="fileSizeText">' . $config['document_file_type'] . '&nbsp;('.$config['document_file_size'].' KB)<br>'.Text::_('Maximum Uploads').' '.$config['document_max_files'].'</small>
                                    <small><strong>' . Text::_('You may also upload your resume file') . '</strong></small>
                                    <input type="hidden" maxlenght=""/>
                                    <input type="hidden" id="selectedFiles_required" name="selectedFiles_required" value="' . $files_required . '" />
                                    <div id="existingFiles" class="uploadedFiles">
                                    </div>';
                                    if($isadmin == 1){
                                        if(isset($resume)){
                                            $file_check =  JSModel::getJSSiteModel('resume')->getResumeFilesByResumeId($resume->id);
                                            if($file_check){
                                                $data .='<a title="'.Text::_('Download All').'" style="background:grey;" class="zip-downloader" href="' . Uri::root() . 'index.php?option=com_jsjobs&c=resume&task=getallresumefiles&resumeid=' . $resume->id . '&sr=1" onclick="" target="_blank"><img src="' . Uri::root() . 'components/com_jsjobs/images/download-all.png"></a>';
                                            }
                                        }
                                    }
                                $data .='
                                </div>
                                <div class="resumefieldvalue" id="resumefileswrapper_IE">
                                    <small class="fileSizeText">' . $config['document_file_type'] . '&nbsp;('.$config['document_file_size'].' KB)</small>
                                    <div id="existingFiles" class="uploadedFiles"></div>
                                </div>
                            </div>';
                        break;
                    case "job_category":
                            $fieldValue = $resumelists['job_category'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "job_subcategory":
                            $fieldValue = $resumelists['job_subcategory'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "jobtype":
                            $fieldValue = $resumelists['jobtype'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "nationality":
                            $fieldValue = $resumelists['nationality'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "driving_license": 
                            $fieldValue = $resumelists['driving_license'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "license_no": 
                            $fieldValue = isset($resume) ? $resume->license_no : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue);
                        break;
                    case "license_country":
                            $fieldValue = $resumelists['license_country'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "heighestfinisheducation":
                            $fieldValue = $resumelists['heighestfinisheducation'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "total_experience":
                            $fieldValue = $resumelists['experienceid'];
                            if(!empty($resume->total_experience) && empty($resume->experienceid)){
                                $fieldValue .= '<div id="js-jobs-old-experience"><span class="experience">'.$resume->total_experience.'</span>'.Text::_('Please Select New Experience').'</div>';
                            }
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "date_of_birth":
                    case "date_start":
                        if($date_counter == 0){ 
                            $data .= '<div class="fullwidthwrapper">';
                                $field_obj = '';
                                foreach ($fields_ordering as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "date_of_birth":
                                                $dateofbirth_required = ($field_obj->required ? 'required' : '');
                                                $data .= '
                                                    <div class="resumefieldswrapper formresumetwo">
                                                        <label class="resumefieldtitle" for="date_of_birth">' . Text::_($field_obj->fieldtitle);
                                                            if ($field_obj->required == 1) {
                                                                $data .= '<span class="error-msg">*</span>';
                                                            }
                                                $data .= '</label>
                                                        <div class="resumefieldvalue">';
                                                $date = (isset($resume) && $resume->date_of_birth != '' && $resume->date_of_birth != "0000-00-00 00:00:00") ? HTMLHelper::_('date', $resume->date_of_birth, $config['date_format']) : '';
                                                $data .= HTMLHelper::_('calendar', $date, 'sec_1[date_of_birth]', 'date_of_birth', $js_dateformat, array('class' => 'inputbox validate-validatedateofbirth '.$dateofbirth_required, 'size' => '10', 'maxlength' => '19'));
                                                $data .= '
                                                        </div>
                                                    </div>';
                                            break;
                                        case "date_start":
                                                $date_start_required = ($field_obj->required ? 'required' : '');
                                                $data .= '
                                                    <div class="resumefieldswrapper formresumetwo">
                                                        <label class="resumefieldtitle" for="date_start">' . Text::_($field_obj->fieldtitle);
                                                if ($field_obj->required == 1) {
                                                    $data .= '<span class="error-msg">*</span>';
                                                }
                                                $data .= '</label>
                                                        <div class="resumefieldvalue">';
                                                $date = (isset($resume) && $resume->date_start != '' && $resume->date_start != "0000-00-00 00:00:00") ? HTMLHelper::_('date', $resume->date_start, $config['date_format']) : '';
                                                $data .= HTMLHelper::_('calendar', $date, 'sec_1[date_start]', 'date_start', $js_dateformat, array('class' => 'inputbox validate-validatedateofbirth', 'size' => '10', 'maxlength' => '19'));
                                                $data .= '
                                                        </div>
                                                    </div>';
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $date_counter = 1;
                        break;
                    case "searchable":
                    case "iamavailable":
                        if($available_counter == 0){ 
                            $data .= '<div class="resumefieldswrapper">';
                                $field_obj = '';
                                foreach ($fields_ordering as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "searchable":
                                            $fieldValue = isset($resume) ? $resume->searchable : "";
                                            $data .= $this->getResumeCheckBoxField($field_obj, $fieldValue);
                                            break;
                                        case "iamavailable":
                                            $fieldValue = isset($resume) ? $resume->iamavailable : "";
                                            $data .= $this->getResumeCheckBoxField($field_obj, $fieldValue);
                                            break;
                                    }
                                }
                            $data .= '</div>';
                        }
                        $available_counter = 1;
                        break;
                    case "salary":
                            $fieldValue = $resumelists['currencyid'] . $resumelists['jobsalaryrange'] . $resumelists['jobsalaryrangetypes'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "desired_salary":
                            $fieldValue = $resumelists['dcurrencyid'] . $resumelists['desired_salary'] . $resumelists['djobsalaryrangetypes'];
                            $data .= $this->getResumeSelectField($field, $fieldValue);
                        break;
                    case "video":
                            $fieldValue = isset($resume) ? $resume->video : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue);
                        break;
                    case "keywords":
                            $fieldValue = isset($resume) ? $resume->keywords : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue);
                        break;
                    default:
                        $array['wrp'] = '';
                        if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) 
                        { 
                            $array['wrp'] = 'js-jobs-main-wrp';
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp '; 
                        }
                        $data .= $this->getResumeFormUserField($field, $resume , 1 , $cf_object, 0 , '',$array['wrp']);
                        break;
                }
            }

            if($isadmin == 1){
                $one = '';
                $two = '';
                $three = '';
                if(isset($resume->status)){
                    if($resume->status == 1){
                        $one = ' selected ';
                    }elseif($resume->status == 0){
                        $two = ' selected ';
                    }else{
                        $three = ' selected ';
                    }
                }
                $status = isset($resume) ? $resume->status : '';
                $data .= '
                    <div class="resumefieldswrapper">
                        <label id="total_experiencemsg" for="total_experience">'.Text::_('Status').'</label>
                        <select id="status" name="sec_1[status]">
                            <option '; 
                            $selected = ($status == 1) ? 'selected="selected"' : '';
                $data .=    $selected.' value="1" '.$one.'>'.Text::_('Approved').'</option>
                            <option '; 
                            $selected = ($status == 0) ? 'selected="selected"' : '';
                $data .=    $selected.' value="0" '.$two.'>'.Text::_('Pending').'</option>
                            <option '; 
                            $selected = ($status == -1) ? 'selected="selected"' : '';
                $data .=    $selected.' value="-1" '.$two.'>'.Text::_('Reject').'</option>
                        </select>
                    </div>
                    ';
            }
        $created = isset($resume->created) ? $resume->created : date('Y-m-d H:i:s');
        $data .= '<input type="hidden" id="created" name="sec_1[created]" value="'.$created.'">
            </div>';
        return $data;
    }

    function makeAddressSectionFields($result, $cf_object, $config) {
        $addresses = $result[0];
        $fields_ordering = $result[1];
        $sections_allowed = $config['max_resume_addresses'];

        $html = '<div id="jssection_address" class="jssectionwrapper">';
        if(empty($addresses)){
            $addresses = array();
            for ($i=0; $i < $sections_allowed; $i++) { 
                $addresses[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($addresses);
            if($totalexistings < $sections_allowed){
                $j = $sections_allowed - $totalexistings;
                for ($i=0; $i < $j; $i++) { 
                    $addresses[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($addresses as $address) {
            $jssection_hide = isset($address->id) ? '' : 'jssection_hide';
            $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_address_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />';
            foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "address_city": 
                        $for = 2;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $address, $field , $config, $jssection_hide);
                        break;
                    case "address_zipcode":
                        $fieldValue = isset($address->address_zipcode) ? $address->address_zipcode : '';
                        $html .= $this->getFieldForMultiSection($field, $fieldValue, 2, $sectionid, $jssection_hide);
                        break;
                    case "address":
                        $fieldValue = isset($address->address) ? $address->address : '';
                        $html .= $this->getFieldForMultiSection($field, $fieldValue, 2, $sectionid, $jssection_hide);
                        break;
                    case "address_location": //longitude and latitude 
                        $required = ($field->required ? 'required' : '');
                        $latitude = isset($address->latitude) ? $address->latitude : '';
                        $longitude = isset($address->longitude) ? $address->longitude : '';
                        $data_required = '';
                        if($jssection_hide){
                            if($required){
                                $data_required = 'data-myrequired="required"';
                                $required = '';
                            }
                        }                    

                        $html .= '
                            <div class="resumefieldswrapper loc-field">
                                <label id="longitudemsg" class="resumefieldtitle" for="longitude">' . Text::_($field->fieldtitle);
                                    if ($field->required == 1) {
                                        $html .= '<span class="error-msg">*</span>';
                                    }
                        $html .= '</label>
                                <div class="resumefieldvalue">
                                    <div id="outermapdiv_'.$sectionid.'" class="outermapdiv">
                                        <div id="map_'.$sectionid.'" class="map" >
                                            <div id="closetag_'.$sectionid.'"><a class="js-resume-close-cross" href="Javascript: hidediv('.$sectionid.');">' . Text::_('X') . '</a></div>
                                            <div id="map_container_'.$sectionid.'" class="map_container"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="resumefieldvalue">
                                    <div class="js-col-xs-12 js-col-md-4 leftpaddingnull">
                                        <input  class="inputbox ' . $required . '" '.$data_required.' type="text" id="latitude_'.$sectionid.'" name="sec_2[latitude]['.$sectionid.']" size="25" placeholder="' . Text::_('Latitude') . '" value = "'.$latitude.'" />
                                    </div>
                                    <div class="js-col-xs-12 js-col-md-4 leftpaddingnull">
                                        <input  class="inputbox ' . $required . '" '.$data_required.' type="text" id="longitude_'.$sectionid.'" name="sec_2[longitude]['.$sectionid.']" size="25" placeholder="' . Text::_('Longitude') . '" value = "'.$longitude.'" />
                                    </div>
                                    <div class="js-col-xs-12 js-col-md-4 leftpaddingnull">
                                        <a class="anchor map-link" href="Javascript: showdiv('.$sectionid.');loadMap('.$sectionid.');"><span id="anchor">' . Text::_('Map') . '</span></a>
                                    </div>
                                </div>
                            </div>';
                    break;
                    default:
                        $array['wrp'] = '';
                        if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp';
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp '; 
                        }
                        $html .= $this->getResumeFormUserField($field, $address , 2 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                        break;
                }
            }
            $id = isset($address->id) ? $address->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis2'.$sectionid.'" class="jsdeletethissection" name="sec_2[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_2[id]['.$sectionid.']" value="'.$id.'">
                    </div>';
            $sectionid++;
        }
        $html .= '</div><div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'address\');"><div class="jsresume_plus">+</div> '.Text::_('Add New Address').'</div>';
        return $html;
    }

    function makeInstituteSectionFields($result, $cf_object, $config) {
        $institutes = $result[0];
        $fields_ordering = $result[1];
        $sections_allowed = $config['max_resume_institutes'];

        $html = '<div id="jssection_institute" class="jssectionwrapper">';
        if(empty($institutes)){
            $institutes = array();
            for ($i=0; $i < $sections_allowed; $i++) { 
                $institutes[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($institutes);
            if($totalexistings < $sections_allowed){
                $j = $sections_allowed - $totalexistings;
                for ($i=0; $i < $j; $i++) {
                    $institutes[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($institutes as $institute) {
            $jssection_hide = isset($institute->id) ? '' : 'jssection_hide';
            $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_institute_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />';
            foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "institute":
                        $fvalue = isset($institute->institute) ? $institute->institute : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide);
                    break;
                    case "institute_city": 
                        $for = 3;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $institute, $field , $config, $jssection_hide);
                        break;
                    case "institute_address":
                        $fvalue = isset($institute->institute_address) ? $institute->institute_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide);
                        break;
                    case "institute_certificate_name":
                        $fvalue = isset($institute->institute_certificate_name) ? $institute->institute_certificate_name : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide);
                        break;
                    case "institute_study_area":
                        $fvalue = isset($institute->institute_study_area) ? $institute->institute_study_area : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 3, $sectionid, $jssection_hide);
                        break;
                    default:
                    $array['wrp']='';
                        if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp'; 
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp '; 
                        }
                        $html .= $this->getResumeFormUserField($field, $institute , 3 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                        break;
                }
            }
            $id = isset($institute->id) ? $institute->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis3'.$sectionid.'" class="jsdeletethissection" name="sec_3[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_3[id]['.$sectionid.']" value="'.$id.'">
                    </div>';
            $sectionid++;
        }
        $html .= '</div><div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'institute\');"><div class="jsresume_plus">+</div> '.Text::_('Add New').' '.Text::_('Institute').'</div>';
        return $html;
    }

    function makeEmployerSectionFields($result, $cf_object, $config) {

        $employers = $result[0];
        $fields_ordering = $result[1];
        $sections_allowed = $config['max_resume_employers'];
        $js_dateformat = $this->prepareDateFormat($config);

        $html = '<div id="jssection_employer" class="jssectionwrapper">';
        if(empty($employers)){
            $employers = array();
            for ($i=0; $i < $sections_allowed; $i++) { 
                $employers[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($employers);
            if($totalexistings < $sections_allowed){
                $j = $sections_allowed - $totalexistings;
                for ($i=0; $i < $j; $i++) { 
                    $employers[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($employers as $employer) {
            $jssection_hide = isset($employer->id) ? '' : 'jssection_hide';
            $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_employer_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />';

            // $data = array('data' => '', 'city_id' => null, 'city_name' => null);
            // if (isset($employer) && count($employer) != 0) {
            //     $data['city_id'] = "" . $employer->employer_city . "";
            // }
            // if (isset($employer) && count($employer) != 0) {
            //     $data['city_name'] = $employer->city . ", " . $employer->state . ", " . $employer->country;
            // }

            $counter = 0;
            foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "employer":
                        $fvalue = isset($employer->employer) ? $employer->employer : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_position":
                        $fvalue = isset($employer->employer_position) ? $employer->employer_position : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_resp":
                        $fvalue = isset($employer->employer_resp) ? $employer->employer_resp : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_pay_upon_leaving":
                        $fvalue = isset($employer->employer_pay_upon_leaving) ? $employer->employer_pay_upon_leaving : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_supervisor":
                        $fvalue = isset($employer->employer_supervisor) ? $employer->employer_supervisor : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_from_date":
                    case "employer_to_date":
                        if($counter == 0){ 
                            $html .= '<div class="fullwidthwrapper">';
                                $field_obj = '';
                                foreach ($fields_ordering as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "employer_from_date":
                                            $html .= '
                                                <div class="resumefieldswrapper formresumetwo">
                                                    <label class="resumefieldtitle" for="employer_from_date4'.$sectionid.'">' . Text::_($field_obj->fieldtitle);
                                                    if ($field_obj->required == 1) {
                                                        $html .= '<span class="error-msg">*</span>';
                                                    }
                                            $html .='</label>
                                                    <div class="resumefieldvalue">';
                                                        if (isset($employer->employer_from_date) && $employer->employer_from_date != '' && ($employer->employer_from_date != "0000-00-00 00:00:00")) {
                                                            $html .= HTMLHelper::_('calendar', HTMLHelper::_('date', $employer->employer_from_date, $config['date_format']), 'sec_4[employer_from_date][]', 'employer_from_date4'.$sectionid, $js_dateformat, array('class' => 'inputbox validate-employer_from_date', 'size' => '10', 'maxlength' => '19'));
                                                        } else {
                                                            $html .= HTMLHelper::_('calendar', '', 'sec_4[employer_from_date][]', 'employer_from_date4'.$sectionid, $js_dateformat, array('class' => 'inputbox validate-employer_from_date', 'size' => '10', 'maxlength' => '19'));
                                                        }
                                            $html .='</div>
                                                </div>';
                                            break;
                                        case "employer_to_date":
                                            $html .= '
                                                <div class="resumefieldswrapper formresumetwo">
                                                    <label class="resumefieldtitle" for="employer_to_date4'.$sectionid.'">' . Text::_($field_obj->fieldtitle);
                                                    if ($field_obj->required == 1) {
                                                        $html .= '<span class="error-msg">*</span>';
                                                    }
                                            $html .='</label>
                                                    <div class="resumefieldvalue">';
                                                        if (isset($employer->employer_to_date) && $employer->employer_to_date != '' && ($employer->employer_to_date != "0000-00-00 00:00:00")) {
                                                            $html .= HTMLHelper::_('calendar', HTMLHelper::_('date', $employer->employer_to_date, $config['date_format']), 'sec_4[employer_to_date][]', 'employer_to_date4'.$sectionid, $js_dateformat, array('class' => 'inputbox validate-employer_to_date', 'size' => '10', 'maxlength' => '19'));
                                                        } else {
                                                            $html .= HTMLHelper::_('calendar', '', 'sec_4[employer_to_date][]', 'employer_to_date4'.$sectionid, $js_dateformat, array('class' => 'inputbox validate-employer_to_date', 'size' => '10', 'maxlength' => '19'));
                                                        }
                                            $html .='</div>
                                                </div>';
                                        break;
                                    }
                                }
                            $html .= '</div>';
                        }
                        $counter = 1;
                        break;
                    case "employer_leave_reason":
                        $fvalue = isset($employer->employer_leave_reason) ? $employer->employer_leave_reason : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_city":
                        $for = 4;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $employer, $field , $config, $jssection_hide);
                        break;
                    case "employer_zip":
                        $fvalue = isset($employer->employer_zip) ? $employer->employer_zip : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_phone":
                        $fvalue = isset($employer->employer_phone) ? $employer->employer_phone : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    case "employer_address":
                        $fvalue = isset($employer->employer_address) ? $employer->employer_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide);
                        break;
                    default:
                        $array['wrp']='';
                        if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp'; 
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp '; 
                        }
                        $html .= $this->getResumeFormUserField($field, $employer , 4 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                    break;
                }
            }
            $id = isset($employer->id) ? $employer->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis4'.$sectionid.'" class="jsdeletethissection" name="sec_4[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_4[id]['.$sectionid.']" value="'.$id.'">
                    </div>';
            $sectionid++;
        }
        $html .= '</div><div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'employer\');"><div class="jsresume_plus">+</div> '.Text::_('Add New Employer').'</div>';
        return $html;
    }

    function makeSkillsSectionFields($result, $cf_object, $config) {
        
        $skills = $result[0];
        $fields_ordering = $result[1];

        $html = '<div id="jssection_skills" class="jssectionwrapper">';
        if(empty($skills->skills)){
            $jssection_hide = 'jssection_hide';
        }else{
            $jssection_hide = '';
        }
        $sectionid = 0;
        // <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
        // <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />
        $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_skills_'.$sectionid.'">';
        foreach ($fields_ordering as $field) {
            switch ($field->field) {
                case "skills":
                    $fvalue = isset($skills) ? $skills->skills : '';
                    $resume_required = ($field->required ? 'required' : '');
                    $data_required = '';
                    if($jssection_hide){
                        if($resume_required){
                            $data_required = 'data-myrequired="required"';
                            $resume_required = '';
                        }
                    }                    
                    $html .= '
                        <div class="resumefieldswrapper">
                            <label id="skillsmsg" class="resumefieldtitle" for="skills">' . Text::_($field->fieldtitle);
                                if ($field->required == 1) {
                                    $html .= '<span class="error-msg">*</span>';
                                }
                    $html .= '</label>
                            <div class="resumefieldvalue">
                                <textarea  class="inputbox '.$resume_required.'" '.$data_required.' name="sec_5[skills]['.$sectionid.']" id="skills" cols="200" rows="1" >'.$fvalue.'</textarea>
                            </div>
                        </div>';
                    break;
                default:
                    $array['wrp']='';
                    if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp'; 
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp ';
                        }
                    $html .= $this->getResumeFormUserField($field, $skills , 5 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                    break;
            }
        }
        $id = '';
        $deletethis = (empty($skills->skills)) ? 1 : 0;
        $html .= '<input type="hidden" id="deletethis5'.$sectionid.'" class="jsdeletethissection" name="sec_5[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                    <input type="hidden" id="id" name="sec_5[id]['.$sectionid.']" value="'.$id.'">
            </div></div>';

        if(empty($skills->skills)){
            $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'skills\');"><div class="jsresume_plus">+</div> '.Text::_('Add').' '.Text::_('Skills').'</div>';
        }
        return $html;
    }

    function makeResumeSectionFields($result, $cf_object, $config) {
        
        $resume = $result[0];
        $fields_ordering = $result[1];

        $html = '<div id="jssection_resume" class="jssectionwrapper">';
        if(empty($resume->resume)){
            $jssection_hide = 'jssection_hide';
        }else{
            $jssection_hide = '';
        }
        $sectionid = 0;
        // <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
        // <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />
        $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_resume_'.$sectionid.'">';
        foreach ($fields_ordering as $field) {
            switch ($field->field) {
                case "resume":
                    $fvalue = isset($resume) ? $resume->resume : '';
                    $resume_required = ($field->required ? 'required' : '');
                    $data_required = '';
                    if($jssection_hide){
                        if($resume_required){
                            $data_required = 'data-myrequired="required"';
                            $resume_required = '';
                        }
                    }
                    $html .= '
                        <div class="resumefieldswrapper">
                            <label id="" class="resumefieldtitle" for="resumeeditor">' . Text::_($field->fieldtitle);
                                if ($field->required == 1) {
                                    $html .= '<span class="error-msg">*</span>';
                                }
                        $conf   = Factory::getConfig();
                        $resume_editor = Editor::getInstance($conf->get('editor'));

                    //$name = 'sec_6[resume]['.$sectionid.']';
                    $name = 'resumeeditor';
                    $editor = $resume_editor->display( $name , $fvalue, '100%', '100%', '60', '20', false);
                    $html .= '</label>
                            <div class="resumefieldvalue">
                                '.$editor.'
                            </div>
                        </div>';
                    break;
                default:
                    $array['wrp']='';
                    if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp';
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp ';
                        }
                    $html .= $this->getResumeFormUserField($field, $resume , 6 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                    break;
            }
        }
        $id = '';
        $deletethis = (empty($resume->resume)) ? 1 : 0;
        $html .= '<input type="hidden" id="deletethis6'.$sectionid.'" class="jsdeletethissection" name="sec_6[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                    <input type="hidden" id="id" name="sec_6[id]['.$sectionid.']" value="'.$id.'">
            </div></div>';
        if(empty($resume->resume)){
            $html .= '<div class="jsresume_addnewbutton" onclick="showResumeSection( this, \'resume\');"><div class="jsresume_plus">+</div> '.Text::_('Add Resume').'</div>';
        }
        return $html;
    }

    function makeReferenceSectionFields($result, $cf_object, $config) {

        $references = $result[0];
        $fields_ordering = $result[1];
        $sections_allowed = $config['max_resume_references'];
        
        $html = '<div id="jssection_reference" class="jssectionwrapper">';
        if(empty($references)){
            $references = array();
            for ($i=0; $i < $sections_allowed; $i++) { 
                $references[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($references);
            if($totalexistings < $sections_allowed){
                $j = $sections_allowed - $totalexistings;
                for ($i=0; $i < $j; $i++) { 
                    $references[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($references as $reference) {
            $jssection_hide = isset($reference->id) ? '' : 'jssection_hide';
            $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_reference_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />';
            foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "reference":
                        $fvalue = isset($reference->reference) ? $reference->reference : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    case "reference_name":
                        $fvalue = isset($reference->reference_name) ? $reference->reference_name : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    case "reference_city": 
                        $for = 7;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $reference, $field , $config, $jssection_hide);
                        break;
                    case "reference_zipcode":
                        $fvalue = isset($reference->reference_zipcode) ? $reference->reference_zipcode : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    case "reference_address":
                        $fvalue = isset($reference->reference_address) ? $reference->reference_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    case "reference_phone":
                        $fvalue = isset($reference->reference_phone) ? $reference->reference_phone : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    case "reference_relation":
                        $fvalue = isset($reference->reference_relation) ? $reference->reference_relation : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    case "reference_years":
                        $fvalue = isset($reference->reference_years) ? $reference->reference_years : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 7, $sectionid, $jssection_hide);
                        break;
                    default:
                        $array['wrp']='';
                        if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp';
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp ';
                        }
                        $html .= $this->getResumeFormUserField($field, $reference , 7 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                        break;
                }
            }
            $id = isset($reference->id) ? $reference->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis7'.$sectionid.'" class="jsdeletethissection" name="sec_7[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_7[id]['.$sectionid.']" value="'.$id.'">
                    </div>';
            $sectionid++;
        }
        $html .= '</div><div class="jsresume_addnewbutton" onclick="showResumeSection( this, \'reference\');"><div class="jsresume_plus">+</div> '.Text::_('Add New Reference').'</div>';
        return $html;
    }

    function makeLanguageSectionFields($result, $cf_object, $config) {

        $languages = $result[0];
        $fields_ordering = $result[1];
        $sections_allowed = $config['max_resume_languages'];

        $html = '<div id="jssection_language" class="jssectionwrapper">';
        if(empty($languages)){
            $languages = array();
            for ($i=0; $i < $sections_allowed; $i++) { 
                $languages[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($languages);
            if($totalexistings < $sections_allowed){
                $j = $sections_allowed - $totalexistings;
                for ($i=0; $i < $j; $i++) { 
                    $languages[] = 'new';
                }
            }
        }

        $sectionid = 0;
        foreach ($languages as $language) {
            $jssection_hide = isset($language->id) ? '' : 'jssection_hide';
            $html .= '<div class="jssection_wrapper '.$jssection_hide.' jssection_language_'.$sectionid.'">
                        <div class="jsundo"><img class="jsundoimage" onclick="undoThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage" onclick="deleteThisSection(this);" src="'.Uri::root().'components/com_jsjobs/images/resume/delete-icon.png" />';
            foreach ($fields_ordering as $field) {
                switch ($field->field) {
                    case "language":
                        $fvalue = isset($language->language) ? $language->language : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide);
                        break;
                    case "language_reading":
                        $fvalue = isset($language->language_reading) ? $language->language_reading : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide);
                        break;
                    case "language_writing": 
                        $fvalue = isset($language->language_writing) ? $language->language_writing : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide);
                        break;
                    case "language_understanding":
                        $fvalue = isset($language->language_understanding) ? $language->language_understanding : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide);
                        break;
                    case "language_where_learned":
                        $fvalue = isset($language->language_where_learned) ? $language->language_where_learned : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 8, $sectionid, $jssection_hide);
                        break;
                    default:
                        $array['wrp']='';
                        if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                            $array['wrp'] = 'js-jobs-main-wrp';
                        } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                            $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp ';
                        }
                        $html .= $this->getResumeFormUserField($field, $language , 8 , $cf_object, $sectionid, $jssection_hide,$array['wrp']);
                        break;
                }
            }
            $id = isset($language->id) ? $language->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis8'.$sectionid.'" class="jsdeletethissection" name="sec_8[deletethis]['.$sectionid.']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_8[id]['.$sectionid.']" value="'.$id.'">
                    </div>';
            $sectionid++;
        }
        $html .= '</div><div class="jsresume_addnewbutton" onclick="showResumeSection( this ,\'language\');"><div class="jsresume_plus">+</div> '.Text::_('Add New Language').'</div>';
        return $html;
    }

    function getResumeSelectField($field, $fieldValue) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;
        $data = '
            <div class="resumefieldswrapper">
                <label class="resumefieldtitle" for="' . $fieldName . '">' . Text::_($fieldtitle);
                    if ($required == 1) {
                        $data .= '<span class="error-msg">*</span>';
                    }
        $data .= '
                </label>
                <div class="resumefieldvalue">
                    ' . $fieldValue .'
                </div>
            </div>';
        return $data;
    }

    function getResumeCheckBoxField($field, $fieldValue){

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;

        $name = 'sec_1['.$fieldName.']';
        $data = '
            <div class="jsresume_seach_width">
                <div class="checkbox-field">
                    <input type="checkbox" class="" name="' . $name . '" id="' . $fieldName . '" value="1" ';
                        if($fieldValue == "" AND $fieldName == 'searchable'){  //new case
                            $data .= 'checked="checked" ';
                        }elseif($fieldValue == 1){
                            $data .= 'checked="checked" ';
                        }
                    $data .= '" />
                </div>
                <div class="checkbox-field-label">
                    <label id="' . $fieldName . 'msg" for="' . $fieldName . '">' . Text::_($fieldtitle);
                        if ($required == 1) {
                            $data .= '<span class="error-msg">*</span>';
                        }
                    $data .= '</label>
                </div>
            </div>';
        return $data;
    }
    
    function getFieldForPersonalSection($field, $fieldValue, $columns = 0) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;
        $style = '';
        if($columns == 3){
            $style = ' formresumethree';
        }

        $data = '
            <div class="resumefieldswrapper'.$style.'">
                <label class="resumefieldtitle" for="' . $fieldName . '">';
                    $data .= Text::_($fieldtitle);
        if ($required == 1) {
                    $data .= '<span class="error-msg">*</span>';
        }
        $data .= '</label>
                <div class="resumefieldvalue">
                    <input class="inputbox';
                        if ($required == 1) {
                            $data .= ' required';
                        } 
                        if ($fieldName == "email_address") {
                            $data .= ' validate-email"';
                        } else {
                            $data .='"';
                        }
        $name = 'sec_1['.$fieldName.']';
        $data .=        ' type="text" name="' . $name . '" id="' . $fieldName . '" maxlength="250" value = "' . $fieldValue .
                    '" />
                </div>
            </div>';

        return $data;
    }

    function getFieldForMultiSection($field, $fieldValue, $section, $sectionid, $ishidden ) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;

        $field_id_for = $fieldName.$section.$sectionid;

        $data = '
            <div class="resumefieldswrapper">
                <label class="resumefieldtitle" for="' . $field_id_for . '">';
            $data .= Text::_($fieldtitle);
            
        if ($required == 1) {
                    $data .= '<span class="error-msg">*</span>';
        }
        $data .= '</label>';
        $data .= '<div class="resumefieldvalue">';

        $data_required = '';
        $class_required = '';
        if($ishidden != ''){
            if ($required == 1) {
                $data_required = 'data-myrequired="required"';
            }
            if ($fieldName == "email_address") {
                $data_required = 'data-myrequired="required validate-email"';
            }
        }else{
            if ($required == 1) {
                $class_required = ' required';
            }
            if ($fieldName == "email_address") {
                $class_required = ' required validate-email';
            }
        }

        $data .= '<input class="inputbox'.$class_required.'" '.$data_required;

        switch ($section) {
            case '2': $section = 'sec_2'; break;
            case '3': $section = 'sec_3'; break;
            case '4': $section = 'sec_4'; break;
            case '5': $section = 'sec_5'; break;
            case '6': $section = 'sec_6'; break;
            case '7': $section = 'sec_7'; break;
            case '8': $section = 'sec_8'; break;
        }
        $name = $section."[$fieldName][$sectionid]";

        $data .=    ' type="text" name="' . $name . '" id="' . $field_id_for . '" maxlength="250" value = "' . $fieldValue . '" />
                </div>
            </div>';

        return $data;
    }

    function getResumeFormUserField($field, $object , $section , $cf_object , $sectionid, $ishidden, $wrp='') {
        $id = isset($object->id)  ? $object->id : NULL;
        $params = isset($object->params) ? $object->params : NULL;
        $data = NULL;
        $result = $cf_object->formCustomFields($field , $id , $params, 1 , $section , $sectionid, $ishidden);

        if( isset($result['value'])){
            $data .= '<div class="resumefieldswrapper">';
            $data .= '  <label class="resumefieldtitle" for="">';
            $data .=        Text::_($result['title']);
                            if($field->required == 1 || $field->userfieldtype == "termsandconditions"){
            $data .= '          <span class="error-msg">*</span>';
                            }
            $data .= '  </label>';
            $data .= '  <div class="resumefieldvalue '.$wrp.'">';
            $data .=        $result['value'];
            $data .= '  </div>
                      </div>';            
        }
        return $data;
    }

    function prepareDateFormat($config){
        if ($config['date_format'] == 'm/d/Y')
            $dash = '/';
        else
            $dash = "-";
        $dateformat = $config['date_format'];        
        $firstdash = strpos($dateformat, $dash, 0);
        $firstvalue = substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = strpos($dateformat, $dash, $firstdash);
        $secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
        $js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;

        return $js_dateformat;
    }

    // resumeAttachedFiels
    function getResumeFilesLayout($files, $data_directory) {
        $resumeid = Factory::getApplication()->input->get('resumeid');
        $printresume = Factory::getApplication()->input->get('printresume');
        $filesfor = Factory::getApplication()->input->get('filesfor');
        if (empty($resumeid)) {
            return false;
        }
        if (empty($filesfor)) {
            $filesfor = "form";
        }
        $path = Uri::root() . $data_directory . '/data/jobseeker/resume_' . $resumeid . '/resume/';
        $returnValue = '';
        if (!$files || empty($files)) {
            if ($resumeid != -1) {
                $returnValue .= '';
            }
        } else {
            if ($filesfor == "form") {
                foreach ($files as $file) {
                    $selectedFilename = substr($file->filename, 0, 4);
                    $fileExt = substr($file->filename, strrpos($file->filename, '.') + 1);
                    $returnValue .= '<span id="' . $file->id . '" class="selectedFile">' . $selectedFilename . '.. .' . $fileExt . '<a href="javascript:void(0);" onclick="return deleteResumeFile(' . $file->id . ', ' . $resumeid . ')"><img src="' . Uri::root() . 'components/com_jsjobs/images/delete.png" height="10px" width="10px" /></a></span>';
                }
            }
            if ($filesfor == 'popup') {
                foreach ($files as $file) {
                    $returnValue .= '
                            <div id="' . $file->id . '" class="chosenFile js-row no-margin">
                                <div class="js-col-lg-12 js-col-md-12 no-padding">
                                    <div class="js-row no-margin">
                                        <span class="uploadFileName">' . $file->filename . '</span>
                                        <span id="' . $file->id . '" class="deleteUploadedFile" onclick="return deleteResumeFile(' . $file->id . ', ' . $resumeid . ')"><img src="' . Uri::root() . 'components/com_jsjobs/images/delete.png" height="15px" width="15px" /></span>
                                    </div>
                                </div>
                            </div>';
                }
            }
            if ($filesfor == 'view') {
                if ($printresume == 1) {
                    $returnValue .= '<ul>';
                } else {
                    //for jobi template
                    if(Factory::getApplication()->getTemplate() == 'jobi'){
                        $returnValue .= '<ul><a title="'.Text::_('Download All').'" class="zip-downloader" href="' . Uri::root() . 'index.php?option=com_jsjobs&c=resume&task=getallresumefiles&resumeid=' . $resumeid . '" onclick="" target="_blank"><i class="fa fa-download"></i></a>';
                    } else {
                        $returnValue .= '<ul><a title="'.Text::_('Download All').'" class="zip-downloader" href="' . Uri::root() . 'index.php?option=com_jsjobs&c=resume&task=getallresumefiles&resumeid=' . $resumeid . '" onclick="" target="_blank"><img src="' . Uri::root() . 'components/com_jsjobs/images/download-all.png"></a>';   
                    }
                }
                foreach ($files as $file) {
                    $selectedFilename = substr($file->filename, 0, 4);
                    $fileExt = substr($file->filename, strrpos($file->filename, '.') + 1);
                    $returnValue .= '<li id="' . $file->id . '" class="selectedFile" title="' . $file->filename . '">' . $selectedFilename . '.. .' . $fileExt;
                    if ($printresume != 1) {
                        //for jobi template
                        if(Factory::getApplication()->getTemplate() == 'jobi'){
                            $returnValue .= '<a target="_blank" href="' . $path . $file->filename . '" alt=""><i class="fa fa-download"></i></a></li>';
                        } else {
                            $returnValue .= '<a target="_blank" href="' . $path . $file->filename . '" alt=""><img src="' . Uri::root() . 'components/com_jsjobs/images/download.png" height="15px" width="15px" /></a></li>';
                        }
                    }
                }
                $returnValue .= '</ul>';
            }
        }
        return $returnValue;
    }

    function getCityFieldForForm($for , $sectionid, $object, $field , $config, $ishidden){
        $html = '';
        switch ($for) {
            case '2':
                $cityfor = 'address'; break;
            case '3':
                $cityfor = 'institute'; break;
            case '4':
                $cityfor = 'employer'; break;
            case '7':
                $cityfor = 'reference'; break;
            break;
        }
        $data_required = '';
        $city_required = ($field->required ? 'required' : '');
        if($ishidden){
            if($city_required){
                $data_required = 'data-myrequired="required"';
                $city_required = '';
            }
        }
        $cityforedit = '';
        $data = array('city_id' => null, 'city_name' => null);
        if (isset($object->{$field->field}) AND ($object->{$field->field})) {
            $cityforedit = 1;
            $data['city_id'] = $object->{$field->field};
            $data['city_name'] = Text::_($object->city) ;
            switch ($config['defaultaddressdisplaytype']) {
                case 'csc':
                    $data['city_name'] .= ", " . Text::_($object->state) . ", " . Text::_($object->country);
                    break;
                case 'cs':
                    $data['city_name'] .= ", " . Text::_($object->state);
                    break;
                case 'cc':
                    $data['city_name'] .= ", " . Text::_($object->country);
                    break;
            }
        }
        $html .= '
            <div class="resumefieldswrapper">
                <label id="'.$cityfor.'_citymsg" class="resumefieldtitle" for="'.$cityfor.'_city_'.$sectionid.'">' . Text::_($field->fieldtitle);
                    if ($field->required == 1) {
                        $html .= '<span class="error-msg">*</span>';
                    }
        $html .= '</label>
                <div class="resumefieldvalue">
                    <input data-for="'.$cityfor.'_'.$sectionid.'" class="inputbox jstokeninputcity ' . $city_required . '" '.$data_required.' type="text" name="sec_'.$for.'['.$cityfor.'_city]['.$sectionid.']" id="'.$cityfor.'_city_'.$sectionid.'" size="40" maxlength="100" value="" />
                    <input type="hidden" name="sec_'.$for.'['.$cityfor.'cityforedit]['.$sectionid.']" id="'.$cityfor.'cityforedit_'.$sectionid.'" value="'.$cityforedit.'" />
                    <input type="hidden" class="jscityid" name="jscityid" value="'.$data['city_id'].'" />
                    <input type="hidden" class="jscityname" name="jscityname" value="'.$data['city_name'].'" />
                </div>
            </div>';
        return $html;
    }
}
?>
