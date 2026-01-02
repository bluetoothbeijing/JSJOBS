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

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelExport extends JSModel {

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

    function getExportResumes($jobid, $resumeid) {
        /*if (!is_numeric($jobid))
            return false;*/
        if (!is_numeric($resumeid))
            return false;
        // Getting userfields and fieldsordering
        if(Factory::getApplication()->isClient('administrator')){
            require_once(JPATH_COMPONENT_SITE.'/models/customfields.php');
            $customfieldsModel = new JSJobsModelCustomFields();
        }else{
            $customfieldsModel = $this->getJSModel('customfields');
        }
        $result['userfields'] = '';
        $fieldordering = $customfieldsModel->getFieldsOrderingForResumeView(3);
        $result['fieldordering']['personal'] = $fieldordering['personal'];
        $result['fieldordering']['addresses'] = $fieldordering['address'];
        $result['fieldordering']['institutes'] = $fieldordering['institute'];
        $result['fieldordering']['employers'] = $fieldordering['employer'];
        $result['fieldordering']['skills'] = $fieldordering['skills'];
        $result['fieldordering']['resume'] = $fieldordering['resume'];
        $result['fieldordering']['references'] = $fieldordering['reference'];
        $result['fieldordering']['languages'] = $fieldordering['language'];

        $db = $this->getDBO();
        $query = "SELECT resume.application_title ,resume.params
                    , resume.first_name, resume.last_name, resume.middle_name
                    , resume.gender, resume.email_address, resume.home_phone, resume.work_phone 
                    , resume.cell, nationality_country.name AS nationality, resume.iamavailable, resume.searchable 
                    , resume.job_category AS categoryid, resume.jobsalaryrange, resume.jobsalaryrangetype 
                    , resume.heighestfinisheducation, resume.date_start, resume.desired_salary 
                    , resume.djobsalaryrangetype, resume.dcurrencyid, resume.can_work, resume.available 
                    , resume.total_experience, resume.skills, resume.driving_license, resume.license_no, licensecountry.name AS license_country 
                    , resume.packageid, resume.paymenthistoryid, resume.currencyid, resume.job_subcategory AS job_subcategoryid 
                    , resume.date_of_birth, resume.video, resume.isgoldresume, resume.isfeaturedresume, resume.serverstatus 
                    , resume.nationality AS nationalityid, resume.serverid, heighesteducation.title AS heighesteducationtitle 
                    , resume.last_modified, resume.hits, resume.keywords, resume.alias, resume.resume 
                    , currency.symbol, cat.cat_title AS job_category, subcat.title AS job_subcategory
                    , jobtypetbl.title AS jobtype, resume.jobtype AS jobtypeid 
                    , CONCAT(resume.alias,'-',resume.id) AS resumealiasid 
                    , exp.title AS exptitle, resume.photo, resume.id  
                    , currency.symbol,salarytble.rangestart,salarytble.rangeend,salarytype.title AS salarytypetitle
                    , dcurrency.symbol AS dsymbol,dsalarytble.rangestart AS drangestart,dsalarytble.rangeend AS drangeend,dsalarytype.title AS dsalarytypetitle 
                    FROM `#__js_job_resume` AS resume 
                    LEFT JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id 
                    LEFT JOIN `#__js_job_subcategories` AS subcat ON resume.job_subcategory = subcat.id 
                    LEFT JOIN `#__js_job_jobtypes` AS jobtypetbl ON resume.jobtype = jobtypetbl.id 
                    LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id 
                    LEFT JOIN `#__js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id 
                    LEFT JOIN `#__js_job_countries` AS licensecountry ON resume.license_country = licensecountry.id 
                    LEFT JOIN `#__js_job_salaryrange` AS salarytble ON resume.jobsalaryrange = salarytble.id 
                    LEFT JOIN  `#__js_job_salaryrangetypes` AS salarytype ON resume.jobsalaryrangetype = salarytype.id 
                    LEFT JOIN `#__js_job_salaryrange` AS dsalarytble ON resume.desired_salary = dsalarytble.id 
                    LEFT JOIN  `#__js_job_salaryrangetypes` AS dsalarytype ON resume.djobsalaryrangetype = dsalarytype.id 
                    LEFT JOIN `#__js_job_countries` AS countries ON resume.nationality = countries.id 
                    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid 
                    LEFT JOIN `#__js_job_currencies` AS dcurrency ON dcurrency.id = resume.dcurrencyid 
                    LEFT JOIN `#__js_job_experiences` AS exp ON exp.id = resume.experienceid ";
                    if($jobid > 0){
                        if (!is_numeric($jobid))
                            return false;
                        $query .= "JOIN `#__js_job_jobapply` AS applyjob ON applyjob.jobid = ".$jobid." AND applyjob.cvid=".$resumeid;
                    }
                    $query .= " WHERE resume.id = " . $resumeid;

        $db->setQuery($query);
        $resume = $db->loadObject();
        $result['personal'] = $resume;

        $query = "SELECT address.id ,address.params,  address.resumeid, address.address, address.address_city, address.address_zipcode, address.longitude, address.latitude
                    FROM `#__js_job_resumeaddresses` AS address
                    WHERE address.resumeid = " . $resumeid;

        $db->execute();

        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['addresses'] = $resume;

        $query = "SELECT institute.id, institute.params, institute.resumeid, institute.institute, institute.institute_address, institute.institute_city, institute.institute_certificate_name, institute.institute_study_area 
                    FROM `#__js_job_resumeinstitutes` AS institute
                    WHERE institute.resumeid = " . $resumeid;

        $db->execute();

        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['institutes'] = $resume;

        $query = "SELECT employer.id, employer.params, employer.resumeid, employer.employer, employer.employer_address, employer.employer_city, employer.employer_position, employer.employer_pay_upon_leaving, employer.employer_supervisor 
                    , employer.last_modified, employer.employer_resp, employer.employer_from_date, employer.employer_to_date, employer.employer_leave_reason, employer.employer_zip, employer.employer_phone
                    FROM `#__js_job_resumeemployers` AS employer
                    WHERE employer.resumeid = " . $resumeid;

        $db->execute();

        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['employers'] = $resume;

        $query = "SELECT reference.id, reference.params, reference.resumeid, reference.reference, reference.reference_name, reference.reference_zipcode 
                    , reference.reference_city, reference.reference_address, reference.reference_phone, reference.reference_relation 
                    , reference.reference_years, reference.last_modified
                    FROM `#__js_job_resumereferences` AS reference
                    WHERE reference.resumeid = " . $resumeid;

        $db->execute();

        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['references'] = $resume;

        $query = "SELECT language.id, language.params, language.resumeid, language.language 
                    , language.language_reading, language.language_writing 
                    , language.language_understanding, language.language_where_learned 
                    , language.last_modified 
                    FROM `#__js_job_resumelanguages` AS language WHERE language.resumeid = " . $resumeid;

        $db->execute();

        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['languages'] = $resume;

        $query = "SELECT filename FROM `#__js_job_resumefiles` WHERE resumeid = " . $resumeid;
        $db->execute();
        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['resume_files'] = $resume;

        return $result;
    }

    function getResumeIdsForAllExport($jobid) {
        if (!is_numeric($jobid))
            return false;
        $db = $this->getDBO();
        $query = "SELECT resume.id 
                FROM `#__js_job_resume` AS resume 
                JOIN `#__js_job_jobapply` AS applyjob ON applyjob.cvid = resume.id 
                WHERE applyjob.jobid =" . $jobid;
        $db->setQuery($query);
        $resumeids = $db->loadObjectList();
        $resumeids = $db->loadAssocList();
        return $resumeids;
    }

    function setAllExport($jobid) {
        $db = $this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;
        //for job title
        if ($this->_client_auth_key != "") {

            $expdata['jobid'] = $jobid;
            $expdata['authkey'] = $this->_client_auth_key;
            $expdata['siteurl'] = $this->_siteurl;


            $fortask = "setexportallresume";
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $encodedata = json_encode($expdata);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['exportallresume']) AND $return_server_value['exportallresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Export All Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = "";
            } else {
                $result = array();
                if ($return_server_value) {
                    $result = $return_server_value['exportresumedata'];
                } else {
                    $result[0] = "";
                }

                // Empty data vars
                $data = "";
                // We need tabbed data
                $sep = "\t";
                $fields = (array_keys($result[0]));
                // Count all fields(will be the collumns
                $columns = count($fields);
                $data .= "Job Title" . $sep . $result[0]['job_title'] . "\n";
                // Put the name of all fields to $out.
                for ($i = 0; $i < $columns; $i++) {
                    $data .= $fields[$i] . $sep;
                }
                $data .= "\n";
                // Counting rows and push them into a for loop
                for ($k = 0; $k < count($result); $k++) {
                    $row = $result[$k];
                    $line = '';
                    // Now replace several things for MS Excel
                    foreach ($row as $value) {
                        $value = str_replace('"', '""', $value);
                        $line .= '"' . $value . '"' . "\t";
                    }
                    $data .= trim($line) . "\n";
                }
                $data = str_replace("\r", "", $data);
                // If count rows is nothing show o records.
                if (count($result) == 0) {
                    $data .= "\n(0) Records Found!\n";
                }
                return $data;
            }
        } else {

            $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
            $db->setQuery($query);
            $jobtitle = $db->loadResult();
            $resumeids = $this->getResumeIdsForAllExport($jobid);
            // Empty data vars
            $data = "";
            // We need tabbed data
            $sep = "\t";
            $data .= "Job Title" . $sep . $jobtitle . "\n" . "\n";
            for ($n = 0; $n < count($resumeids); $n++) {
                $resumeNumber = $n + 1;
                $data .= "Resume-" . $resumeNumber . "\n";
                $resumeid = $resumeids[$n]['id'];
                $result = $this->getExportResumes($jobid, $resumeid);
                if (!$result) {
                    $this->setError($row->getError());
                    return false;
                } else {
                    $result = $this->makeArrayForExport($result);
                    foreach ($result[0] as $sectionName => $section) {
                        if ($sectionName == 'personal') {
                            $fields = (array_keys($section));
                            $columns = count($fields);
                            $data .= "Personal Insformation" . "\n" . "\n";
                            // Put the name of all fields to $out.
                            for ($i = 0; $i < $columns; $i++) {
                                $data .= $fields[$i] . $sep;
                            }
                            $data .= "\n";

                            // If count rows is nothing show o records.
                            if (count($section) == 0) {
                                $data .= "\n(0) Records Found!\n";
                            } else {
                                $line = '';
                                // Now replace several things for MS Excel
                                foreach ($section as $value) {
                                    $value = str_replace('"', '""', $value);
                                    $line .= '"' . $value . '"' . "\t";
                                }
                                $data .= trim($line) . "\n";
                                $data = str_replace("\r", "", $data);
                            }
                        } elseif ($sectionName == 'skills' OR $sectionName == 'resume') {
                            
                        } else {
                            $data .= "\n" . ucfirst($sectionName) . "\n" . "\n";
                            for ($m = 0; $m < count($section); $m++) {
                                $fields = (array_keys($section[$m]));
                                $columns = count($fields);
                                // Put the name of all fields to $out.
                                for ($i = 0; $i < $columns; $i++) {
                                    if ($m != 0) {
                                        $data .= $fields[$i] . "-" . $m . $sep;
                                    } else {
                                        $data .= $fields[$i] . $sep;
                                    }
                                }
                                $data .= "\n";
                                // Counting rows and push them into a for loop
                                $row = $section[$m];
                                $line = '';
                                // Now replace several things for MS Excel
                                foreach ($row as $value) {
                                    $value = str_replace('"', '""', $value);
                                    $line .= '"' . $value . '"' . "\t";
                                }
                                $data .= trim($line) . "\n" . "\n";

                                $data = str_replace("\r", "", $data);

                                // If count rows is nothing show o records.
                                if (count($result) == 0) {
                                    $data .= "\n(0) Records Found!\n";
                                }
                            }
                        }
                    }
                }
            }
            return $data;
        }
    }

    function setExport($jobid, $resumeid) {
        $db = $this->getDBO();
        /*if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;*/
        if ($this->_client_auth_key != "") {

            $expdata['jobid'] = $jobid;
            $expdata['resumeid'] = $resumeid;
            $expdata['authkey'] = $this->_client_auth_key;
            $expdata['siteurl'] = $this->_siteurl;
            $fortask = "setexportresume";
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $encodedata = json_encode($expdata);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['exportresume']) AND $return_server_value['exportresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Export Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = "";
            } else {
                $result = array();
                if ($return_server_value) {
                    $result = $return_server_value['exportresumedata'];
                } else {
                    $result[0] = "";
                }
                // Empty data vars
                $data = "";
                // We need tabbed data
                $sep = "\t";
                $data .= "Job Title" . $sep . $result[0]['job_title'] . "\n";
                $data .= "\n";
                unset($result[0]['Apply Date']);
                unset($result[0]['job_title']);
                foreach($result[0] AS $section => $sectiondata){
                    switch ($section) {
                        case 'personal':
                            $data .= "Personal Information" . "\n" . "\n";
                            $fields = (array_keys($sectiondata));
                            // Count all fields(will be the collumns
                            $columns = count($fields);
                            // Put the name of all fields to $out.
                            for ($i = 0; $i < $columns; $i++) {
                                $data .= $fields[$i] . $sep;
                            }
                            $data .= "\n";
                            foreach($sectiondata AS $d){
                                $data .= $d . $sep;
                            }
                            continue;
                        break;
                        case 'addresses':
                            $data .= "\n" ."\n" ."Addresses" . "\n";
                        break;
                        case 'institutes':
                            $data .= "\n" ."\n" ."Institutes" . "\n";
                        break;
                        case 'employers':
                            $data .= "\n" ."\n" ."Employers" . "\n";
                        break;
                        case 'languages':
                            $data .= "\n" ."\n" ."Languages" . "\n";
                        break;
                        case 'references':
                            $data .= "\n" ."\n" ."References" . "\n" ;
                        break;
                        case 'skills':
                            $data .= "\n" ."\n" ."Skills" . "\n" ;
                            $data .= $sectiondata;
                            continue; // its not an array
                        break;
                        case 'Comments':
                            $data .= "\n" ."\n" ."Comments" . "\n" ;
                            $data .= $sectiondata;
                            continue; // same here
                        break;
                    }
                    if(isset($sectiondata[0]) && $section != 'skills'){
                        $fields = (array_keys($sectiondata[0]));
                        // Count all fields(will be the collumns
                        $columns = count($fields);
                        // Put the name of all fields to $out.
                        for ($i = 0; $i < $columns; $i++) {
                            $data .= $fields[$i] . $sep;
                        }
                        $data .= "\n";
                        foreach($sectiondata AS $d){
                            foreach($d AS $ad)
                                $data .= $ad . $sep;
                            $data .= "\n";
                        }
                    }
                }
                $data = str_replace("\r", "", $data);
                // If count rows is nothing show o records.
                if (count($result) == 0) {
                    $data .= "\n(0) Records Found!\n";
                }
                return $data;
            }
        } else {

            //for job title
            if($jobid > 0){
                if (is_numeric($jobid) == false)
                    return false;
                $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
                $db->setQuery($query);
                $jobtitle = $db->loadResult();
            }

            $result = $this->getExportResumes($jobid, $resumeid);
            if (!$result) {
                $this->setError($row->getError());
                return false;
            } else {
                $result = $this->makeArrayForExport($result);
                // Empty data vars
                $data = "";
                // We need tabbed data
                $sep = "\t";
                if($jobid > 0){
                    $data .= "Job Title" . $sep . $jobtitle . "\n" . "\n";
                }

                foreach ($result[0] as $sectionName => $section) {
                    if ($sectionName == 'personal') {
                        $fields = (array_keys($section));
                        $columns = count($fields);
                        $data .= "Personal Insformation" . "\n" . "\n";
                        // Put the name of all fields to $out.
                        for ($i = 0; $i < $columns; $i++) {
                            $data .= $fields[$i] . $sep;
                        }
                        $data .= "\n";

                        // If count rows is nothing show o records.
                        if (count($section) == 0) {
                            $data .= "\n(0) Records Found!\n";
                        } else {
                            $line = '';
                            // Now replace several things for MS Excel
                            foreach ($section as $value) {
                                $value = str_replace('"', '""', $value);
                                $line .= '"' . $value . '"' . "\t";
                            }
                            $data .= trim($line) . "\n";
                            $data = str_replace("\r", "", $data);
                        }
                    } elseif ($sectionName == 'skills' OR $sectionName == 'resume') {
                        
                    } else {
                        $data .= "\n" . ucfirst($sectionName) . "\n" . "\n";
                        for ($m = 0; $m < count($section); $m++) {
                            $fields = (array_keys($section[$m]));
                            $columns = count($fields);
                            // Put the name of all fields to $out.
                            for ($i = 0; $i < $columns; $i++) {
                                if ($m != 0) {
                                    $data .= $fields[$i] . "-" . $m . $sep;
                                } else {
                                    $data .= $fields[$i] . $sep;
                                }
                            }
                            $data .= "\n";
                            // Counting rows and push them into a for loop
                            $row = $section[$m];
                            $line = '';
                            // Now replace several things for MS Excel
                            foreach ($row as $value) {
                                $value = str_replace('"', '""', $value);
                                $line .= '"' . $value . '"' . "\t";
                            }
                            $data .= trim($line) . "\n" . "\n";

                            $data = str_replace("\r", "", $data);

                            // If count rows is nothing show o records.
                            if (count($result) == 0) {
                                $data .= "\n(0) Records Found!\n";
                            }
                        }
                    }
                }
                return $data;
            }
        }
    }

    function getResumeUserField($object_obj, $field , $customfieldobj) {
        $title = '';
        $value = '';
        $field = $field->field;
        $arr = $customfieldobj->showCustomFields($field, 11 ,$object_obj );
        if(isset($arr['value'])){ // handle error
            $title = $arr['title'];
            $value = $arr['value'];
        }
        return array($title,$value);
    }


    function makeArrayForExport($result) {
        $keyString = '';
        $returnvalue = array();
        $i = 0;
        $fieldordering = $result['fieldordering'];unset($result['fieldordering']);
        $userfields = '';
        $customfieldobj = getCustomFieldClass();
        if(Factory::getApplication()->isClient('administrator')){
            require_once(JPATH_COMPONENT_SITE.'/models/configurations.php');
            $configurationsModel = new JSJobsModelConfigurations();
        }else{
            $configurationsModel = $this->getJSModel('configurations');
        }
        $dateformat = $configurationsModel->getConfigValue('date_format');
        $currency_align = $configurationsModel->getConfigValue('currency_align');
        $data_directory = $configurationsModel->getConfigValue('data_directory');
        // Personal Section
        foreach($fieldordering['personal'] AS $field){
            switch($field->field){
                case 'application_title':
                    $returnvalue['personal']['Application Title'] = $result['personal']->application_title;
                break;
                case 'first_name':
                    $returnvalue['personal']['First Name'] = $result['personal']->first_name;
                break;
                case 'middle_name':
                    $returnvalue['personal']['Middle Name'] = $result['personal']->middle_name;
                break;
                case 'last_name':
                    $returnvalue['personal']['Last Name'] = $result['personal']->last_name;
                break;
                case 'email_address':
                    $returnvalue['personal']['Email address'] = $result['personal']->email_address;
                break;
                case 'cell':
                    $returnvalue['personal']['Cell'] = $result['personal']->cell;
                break;
                case 'nationality':
                    $returnvalue['personal']['Nationality'] = $result['personal']->nationality;
                break;
                case 'gender':
                    $genderText = '';
                    if($result['personal']->gender == 1){
                        $genderText = Text::_('Male');
                    }elseif ($result['personal']->gender == 1) {
                        $genderText = Text::_('Female');
                    }elseif ($result['personal']->gender == 3) {
                        $genderText = Text::_('Does not matter');
                    }
                    $returnvalue['personal']['Gender'] = $genderText;
                break;
                case 'job_category':
                    $returnvalue['personal']['Category'] = $result['personal']->job_category;
                break;
                case 'job_subcategory':
                    $returnvalue['personal']['Sub Category'] = $result['personal']->job_subcategory;
                break;
                case 'jobtype':
                    $returnvalue['personal']['Job Type'] = $result['personal']->jobtype;
                break;
                case 'heighestfinisheducation':
                    $returnvalue['personal']['Highest Education'] = $result['personal']->heighesteducationtitle;
                break;
                case 'total_experience':
                    $returnvalue['personal']['Total Experience'] = empty($result['personal']->total_experience) ? $result['personal']->exptitle : $result['personal']->total_experience;
                break;
                case 'home_phone':
                    $returnvalue['personal']['Home Phone'] = $result['personal']->home_phone;
                break;
                case 'work_phone':
                    $returnvalue['personal']['Work Phone'] = $result['personal']->work_phone;
                break;
                case 'date_of_birth':
                    $returnvalue['personal']['Work Phone'] = HTMLHelper::_('date', $result['personal']->date_of_birth, $dateformat);
                break;
                case 'date_start':
                    $returnvalue['personal']['Date start'] = HTMLHelper::_('date', $result['personal']->date_start, $dateformat);
                break;
                case 'desired_salary':
                    $returnvalue['personal']['Desired Salary'] = $this->getJSModel('common')->getSalaryRangeView($result['personal']->dsymbol,$result['personal']->drangestart,$result['personal']->drangeend,$result['personal']->dsalarytypetitle,$currency_align);
                break;
                case 'salary':
                    $returnvalue['personal']['Current Salary'] = $this->getJSModel('common')->getSalaryRangeView($result['personal']->symbol,$result['personal']->rangestart,$result['personal']->rangeend,$result['personal']->salarytypetitle,$currency_align);
                break;
                case 'photo':
                    $returnvalue['personal']['Photo Path'] = Uri::root().$data_directory.'/data/jobseeker/resume_'.$result['personal']->id.'/photo/'.$result['personal']->photo;
                break;
                case 'resumefiles':
                    $returnvalue['personal']['Resume Files'] = '';
                    foreach($result['resume_files'] AS $file){
                        $returnvalue['personal']['Resume Files'] .= Uri::root().$data_directory.'/data/jobseeker/resume_'.$result['personal']->id.'/resume/'.$file->filename .'\n';
                    }
                break;
                case 'video':
                    $returnvalue['personal']['Youtube Video Id'] = $result['personal']->video;
                break;
                case 'keywords':
                    $returnvalue['personal']['Keywords'] = $result['personal']->keywords;
                break;
                case 'searchable':
                    $returnvalue['personal']['Searchable'] = ($result['personal']->searchable == 1) ? Text::_('JYES') : Text::_('JNO');
                break;
                case 'iamavailable':
                    $returnvalue['personal']['I Am Available'] = ($result['personal']->iamavailable == 1) ? Text::_('JYES') : Text::_('JNO');
                break;
                case 'license_country':
                    $returnvalue['personal']['License Country'] = $result['personal']->license_country;
                break;
                case 'license_no':
                    $returnvalue['personal']['License No.'] = $result['personal']->license_no;
                break;
                case 'driving_license':
                    $returnvalue['personal']['Driving License.'] = ($result['personal']->driving_license == 1) ? Text::_('JYES') : Text::_('JNO');
                break;
                default:
                    $array = $this->getResumeUserField($result['personal'],$field , $customfieldobj);
                    if(!empty($array[0])){
                        $returnvalue['personal'][$array[0]] = $array[1];                            
                    }
                break;
            }
        }
        // Address section
        if(!empty($result['addresses'])){
            $i = 0;
            foreach($result['addresses'] AS $address){
                foreach($fieldordering['addresses'] AS $field){
                    switch ($field->field) {
                        case 'address':
                            $returnvalue['addresses'][$i]['Address'] = $address->address;
                        break;
                        case 'address_zipcode':
                            $returnvalue['addresses'][$i]['Zipcode'] = $address->address_zipcode;
                        break;
                        case 'address_city':
                            if(Factory::getApplication()->isClient('administrator')){
                                $city = 'city';
                            }else{
                                $city = 'cities';
                            }
                            $returnvalue['addresses'][$i]['City'] = $this->getJSModel($city)->getLocationDataForView($address->address_city);
                        break;
                        case 'address_location':
                            $returnvalue['addresses'][$i]['Location'] = $address->longitude.', '.$address->latitude;
                        break;
                        default:
                            $array = $this->getResumeUserField($address,$field , $customfieldobj);
                            if(!empty($array[0])){
                                $returnvalue['addresses'][$i][$array[0]] = $array[1];
                            }
                        break;
                    }
                }
                $i++;
            }
        }else{
            $returnvalue['addresses'] = array();
        }
        // Institutes section
        if(!empty($result['institutes'])){
            $i = 0;
            foreach($result['institutes'] AS $institute){
                foreach($fieldordering['institutes'] AS $field){
                    switch ($field->field) {
                        case 'institute_certificate_name':
                            $returnvalue['institutes'][$i]['Certificate'] = $institute->institute_certificate_name;
                        break;
                        case 'institute_study_area':
                            $returnvalue['institutes'][$i]['Study Area'] = $institute->institute_study_area;
                        break;
                        case 'institute_address':
                            $returnvalue['institutes'][$i]['Address'] = $institute->institute_address;
                        break;
                        case 'institute_city':
                            if(Factory::getApplication()->isClient('administrator')){
                            $returnvalue['institutes'][$i]['City'] = $this->getJSModel('city')->getLocationDataForView($institute->institute_city);
                            }else{
                            $returnvalue['institutes'][$i]['City'] = $this->getJSModel('cities')->getLocationDataForView($institute->institute_city);
                            }
                        break;
                        case 'institute':
                            $returnvalue['institutes'][$i]['Institute'] = $institute->institute;
                        break;
                        default:
                            $array = $this->getResumeUserField($institute,$field , $customfieldobj);
                            if(!empty($array[0])){
                                $returnvalue['institutes'][$i][$array[0]] = $array[1];
                            }
                        break;
                    }
                }
                $i++;
            }
        }else{
            $returnvalue['institutes'] = array();
        }
        // Employer section
        if(!empty($result['employers'])){
            $i = 0;
            foreach($result['employers'] AS $employer){
                foreach($fieldordering['employers'] AS $field){
                    switch ($field->field) {
                        case 'employer_address':
                            $returnvalue['employers'][$i]['Address'] = $employer->employer_address;
                        break;
                        case 'employer_phone':
                            $returnvalue['employers'][$i]['Phone'] = $employer->employer_phone;
                        break;
                        case 'employer_zip':
                            $returnvalue['employers'][$i]['Zipcode'] = $employer->employer_zip;
                        break;
                        case 'employer_city':
                            if(Factory::getApplication()->isClient('administrator')){

                            $returnvalue['employers'][$i]['City'] = $this->getJSModel('city')->getLocationDataForView($employer->employer_city);
                            }else{
                                
                            $returnvalue['employers'][$i]['City'] = $this->getJSModel('cities')->getLocationDataForView($employer->employer_city);
                            }
                        break;
                        case 'employer_leave_reason':
                            $returnvalue['employers'][$i]['Leaving reason'] = $employer->employer_leave_reason;
                        break;
                        case 'employer_resp':
                            $returnvalue['employers'][$i]['Responsibilites'] = $employer->employer_resp;
                        break;
                        case 'employer_pay_upon_leaving':
                            $returnvalue['employers'][$i]['Pay Upon Leaving'] = $employer->employer_pay_upon_leaving;
                        break;
                        case 'employer_position':
                            $returnvalue['employers'][$i]['Position'] = $employer->employer_position;
                        break;
                        case 'employer':
                            $returnvalue['employers'][$i]['Employer'] = $employer->employer;
                        break;
                        case 'employer_from_date':
                            $returnvalue['employers'][$i]['From Date'] = HTMLHelper::_('date',$employer->employer_from_date,$dateformat);
                        break;
                        case 'employer_to_date':
                            $returnvalue['employers'][$i]['To Date'] = HTMLHelper::_('date',$employer->employer_to_date,$dateformat);
                        break;
                        case 'employer_supervisor':
                            $returnvalue['employers'][$i]['Supervisor'] = $employer->employer_supervisor;
                        break;
                        default:
                            $array = $this->getResumeUserField($employer,$field , $customfieldobj);
                            if(!empty($array[0])){
                                $returnvalue['employers'][$i][$array[0]] = $array[1];
                            }
                        break;
                    }
                }
                $i++;
            }
        }else{
            $returnvalue['employers'] = array();
        }
        // Skills section
        foreach($fieldordering['skills'] AS $field){
            switch ($field->field) {
                case 'skills':
                    $returnvalue['skills']['Skills'] = $result['personal']->skills;
                break;
                default:
                    $array = $this->getResumeUserField($result['personal'],$field , $customfieldobj);
                    if(!empty($array[0])){
                        $returnvalue['skills'][$array[0]] = $array[1];
                    }
                break;
            }
        }
        // Resume section
        foreach($fieldordering['resume'] AS $field){
            switch ($field->field) {
                case 'resume':
                    $returnvalue['resume']['Resume'] = $result['personal']->resume;
                break;
                default:
                    $array = $this->getResumeUserField($result['personal'],$field , $customfieldobj);
                    if(!empty($array[0])){
                        $returnvalue['resume'][$array[0]] = $array[1];
                    }
                break;
            }
        }
        // Reference section
        if(!empty($result['references'])){
            $i = 0;
            foreach($result['references'] AS $reference){
                foreach($fieldordering['references'] AS $field){
                    switch ($field->field) {
                        case 'reference_years':
                            $returnvalue['references'][$i]['Years'] = $reference->reference_years;
                        break;
                        case 'reference_relation':
                            $returnvalue['references'][$i]['Relation'] = $reference->reference_relation;
                        break;
                        case 'reference_city':
                            if(Factory::getApplication()->isClient('administrator')){

                            $returnvalue['references'][$i]['City'] = $this->getJSModel('city')->getLocationDataForView($reference->reference_city);
                            }else{
                                
                            $returnvalue['references'][$i]['City'] = $this->getJSModel('cities')->getLocationDataForView($reference->reference_city);
                            }
                        break;
                        case 'reference_phone':
                            $returnvalue['references'][$i]['Phone'] = $reference->reference_phone;
                        break;
                        case 'reference_address':
                            $returnvalue['references'][$i]['Address'] = $reference->reference_address;
                        break;
                        case 'reference_zipcode':
                            $returnvalue['references'][$i]['Zipcode'] = $reference->reference_zipcode;
                        break;
                        case 'reference':
                            $returnvalue['references'][$i]['Reference'] = $reference->reference;
                        break;
                        case 'reference_name':
                            $returnvalue['references'][$i]['Name'] = $reference->reference_name;
                        break;
                        default:
                            $array = $this->getResumeUserField($reference,$field , $customfieldobj);
                            if(!empty($array[0])){
                                $returnvalue['references'][$i][$array[0]] = $array[1];
                            }
                        break;
                    }
                }
                $i++;
            }
        }else{
            $returnvalue['references'] = array();
        }
        // Language section
        if(!empty($result['languages'])){
            $i = 0;
            foreach($result['languages'] AS $language){
                foreach($fieldordering['languages'] AS $field){
                    switch ($field->field) {
                        case 'language_where_learned':
                            $returnvalue['languages'][$i]['Where Learned'] = $language->language_where_learned;
                        break;
                        case 'language_understanding':
                            $returnvalue['languages'][$i]['Understanding'] = $language->language_understanding;
                        break;
                        case 'language_writing':
                            $returnvalue['languages'][$i]['Writing'] = $language->language_writing;
                        break;
                        case 'language_reading':
                            $returnvalue['languages'][$i]['Reading'] = $language->language_reading;
                        break;
                        case 'language':
                            $returnvalue['languages'][$i]['Language'] = $language->language;
                        break;
                        default:
                            $array = $this->getResumeUserField($language,$field , $customfieldobj);
                            if(!empty($array[0])){
                                $returnvalue['languages'][$i][$array[0]] = $array[1];
                            }
                        break;
                    }
                }
                $i++;
            }
        }else{
            $returnvalue['languages'] = array();
        }
        $resume[0] = $returnvalue;
        return $resume;
    }

}
?>
