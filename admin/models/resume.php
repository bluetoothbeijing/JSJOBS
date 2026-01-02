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
use Joomla\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelResume extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_empoptions = null;
    var $_application = null;
    var $_ai_resume_data_array = [];

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }
    function getResumeViewbyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT app.id
                , reference_city.name AS reference_city2 , reference_state.name AS reference_state2 , reference_country.name AS reference_country
                , reference1_city.name AS reference1_city2 , reference1_state.name AS reference1_state2 , reference1_country.name AS reference1_country
                , reference2_city.name AS reference2_city2 , reference2_state.name AS reference2_state2 , reference2_country.name AS reference2_country
                , reference3_city.name AS reference3_city2 , reference3_state.name AS reference3_state2 , reference3_country.name AS reference3_country

                FROM `#__js_job_resume` AS app
                LEFT JOIN `#__js_job_cities` AS reference_city ON app.reference_city = reference_city.id
                LEFT JOIN `#__js_job_states` AS reference_state ON reference_city.stateid = reference_state.id
                LEFT JOIN `#__js_job_countries` AS reference_country ON reference_city.countryid = reference_country.id
                LEFT JOIN `#__js_job_cities` AS reference1_city ON app.reference1_city = reference1_city.id
                LEFT JOIN `#__js_job_states` AS reference1_state ON reference1_city.stateid = reference1_state.id
                LEFT JOIN `#__js_job_countries` AS reference1_country ON reference1_city.countryid = reference1_country.id
                LEFT JOIN `#__js_job_cities` AS reference2_city ON app.reference2_city = reference2_city.id
                LEFT JOIN `#__js_job_states` AS reference2_state ON reference2_city.stateid = reference2_state.id
                LEFT JOIN `#__js_job_countries` AS reference2_country ON reference2_city.countryid = reference2_country.id
                LEFT JOIN `#__js_job_cities` AS reference3_city ON app.reference3_city = reference3_city.id
                LEFT JOIN `#__js_job_states` AS reference3_state ON reference3_city.stateid = reference3_state.id
                LEFT JOIN `#__js_job_countries` AS reference3_country ON reference3_city.countryid = reference3_country.id

                WHERE app.id = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }


    function getEmpAppbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT * FROM #__js_job_resume WHERE id = " . $c_id;

        $db->setQuery($query);
        $this->_application = $db->loadObject();

        $result[0] = $this->_application;
        $result[2] = $this->getJSModel('fieldordering')->getFieldsOrderingforView(3); // job fields , ref id
        //$result[3] = $this->getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
        return $result;
    }

    function getResumeDetail($uid, $jobid, $resumeid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;

        $db = $this->getDBO();
        $db = Factory::getDBO();
        $canview = 1;

        if ($canview == 1) {

            $query = "UPDATE `#__js_job_jobapply` SET resumeview = 1 WHERE jobid = " . $jobid . " AND cvid = " . $resumeid;
            $db->setQuery($query);
            $db->execute();

                $query = "SELECT  app.iamavailable
                        , app.id AS appid, app.first_name, app.last_name, app.email_address 
                        , app.jobtype,app.gender,institute.institute,institute.institute_study_area ,cities.stateid ,address.address_city
                        , app.total_experience, app.jobsalaryrange
                        , salary.rangestart, salary.rangeend,education.title AS educationtitle
                        , currency.symbol
                        FROM `#__js_job_resume` AS app 
                        LEFT JOIN `#__js_job_resumeaddresses` AS  address  ON app.id=address.resumeid 
                        LEFT JOIN `#__js_job_resumeinstitutes` AS  institute  ON app.id=institute.resumeid 
                        LEFT JOIN `#__js_job_cities` AS  cities  ON address.address_city=cities.id 
                        LEFT JOIN `#__js_job_heighesteducation` AS  education  ON app.heighestfinisheducation=education.id 
                        LEFT OUTER JOIN  `#__js_job_salaryrange` AS salary  ON  app.jobsalaryrange=salary.id 
                        LEFT JOIN `#__js_job_currencies` AS  currency  ON app.currencyid=currency.id 
                        WHERE app.id = " . $resumeid;

            $db->setQuery($query);
            $resume = $db->loadObject();

            $fieldsordering = $this->getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields ordering
            if (isset($resume)) {
                $trclass = array('row0', 'row1');
                $i = 0; // for odd and even rows
                $return_value = "<div id='js_job_app_actions'>\n";
                $return_value .= "<img src='components/com_jsjobs/include/images/act_no.png' onclick='closethisactiondiv2();' class='image_close'>";
                foreach ($fieldsordering AS $field) {
                    switch ($field->field) {
                        case 'heighesteducation':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data' class='js-col-xs-12 js-col-md-6'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . Text::_('Education') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->educationtitle . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'institute_institute':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data' class='js-col-xs-12 js-col-md-6'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . Text::_('Institute') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->institute . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'institute_study_area':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data' class='js-col-xs-12 js-col-md-6'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . Text::_('Study Area') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->institute_study_area . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'totalexperience':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data' class='js-col-xs-12 js-col-md-6'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . Text::_('Experience') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->total_experience . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'Iamavailable':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data' class='js-col-xs-12 js-col-md-6'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . Text::_('I Am Available') . "</span>\n";
                                if ($resume->iamavailable == 1)
                                    $return_value .= "<span id='resumedetail_data_value' >" . Text::_('JYES') . "</span>\n";
                                else
                                    $return_value .= "<span id='resumedetail_data_value' >" . Text::_('JNO') . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'salary':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data' class='js-col-xs-12 js-col-md-6'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . Text::_('Current Salary') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->symbol . $resume->rangestart . ' - ' . $resume->symbol . ' ' . $resume->rangeend . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                    }
                }

                $return_value .= "</div>\n";
            }
        } else {
            $return_value = "<div id='resumedetail'>\n";
            $return_value .= "<tr><td>\n";
            $return_value .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
            $return_value .= "<tr class='odd'>\n";
            $return_value .= "<td ><b>" . Text::_('You can not view resume in detail') . "</b></td>\n";
            $return_value .= "<td width='20'><input type='button' class='button' onclick='clsjobdetail(\"resumedetail_$resume->appid\")' value=" . Text::_('Close') . "> </td>\n";
            $return_value .= "</tr>\n";
            $return_value .= "</table>\n";

            $return_value .= "</div>\n";
        }

        return $return_value;
    }




    function getAllEmpApps($datafor, $resumetitle, $resumename, $resumecategory, $resumetype, $desiredsalary, $location, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit) {
        
        if($js_sortby==1){
            $sortby = " resume.application_title $sortby "; 
        }elseif($js_sortby==2){
            $sortby = " resume.first_name $sortby ";
        }elseif($js_sortby==3){
            $sortby = " cat.cat_title $sortby ";
        }elseif($js_sortby==4){
            $sortby = " jobtype.title $sortby ";
        }elseif($js_sortby==5){
            $sortby = " city.cityname $sortby "; //Location
        }elseif($js_sortby==6){
            $sortby = " resume.status $sortby ";
        }elseif($js_sortby==7){
            $sortby = " resume.created $sortby ";
        }elseif($js_sortby==8){
            $sortby = " resume.hits $sortby ";
        }else{
            $sortby = " resume.created DESC ";
        }

        /*
            $isgfcombo
            1 - for gold
            2 - for featured
            3 - for gold & featured
            4 - for all
        */
        $db = Factory::getDBO();
        if($datafor==1){ // 1 for resumes, 2 for resumes queue
            $status_opr = (is_numeric($status)) ? ' = '.$status : ' <> 0 ';
            $fquery = " WHERE resume.status".$status_opr;
            switch ($isgfcombo) {
                case '1':
                    $fquery .= " AND resume.isgoldresume = 1  AND DATE(resume.endgolddate) >= CURDATE()";
                break;
                case '2':
                    $fquery .= " AND resume.isfeaturedresume = 1  AND DATE(resume.endfeaturedate) >= CURDATE()";
                break;
                case '3':
                    $fquery .= " AND resume.isgoldresume = 1 AND resume.isfeaturedresume = 1  AND DATE(resume.endgolddate) >= CURDATE() AND DATE(resume.endfeaturedate) >= CURDATE() ";
                break;
                case '4':
                    // get all
                break;
            }
        }else{ // For resumes Queue
            switch ($isgfcombo) {
                case '1':
                    $fquery = " WHERE resume.isgoldresume = 0 ";
                break;
                case '2':
                    $fquery = " WHERE resume.isfeaturedresume = 0 ";
                break;
                case '3':
                    $fquery = " WHERE (resume.isgoldresume = 0 AND resume.isfeaturedresume = 0 ) ";
                break;
                case '4':
                    $fquery = " WHERE (resume.status = 0 OR resume.isgoldresume = 0 OR resume.isfeaturedresume = 0 ) ";
                break;
                default:
                    $fquery = " WHERE (resume.status = 0 OR resume.isgoldresume = 0 OR resume.isfeaturedresume = 0 ) ";
                break;
            }
        }

        if($resumetitle)
            $fquery .= " AND LOWER(resume.application_title) LIKE ".$db->Quote('%'.$resumetitle.'%');
        if($resumename){
            $fquery .= " AND (";
            $fquery .= " LOWER(resume.first_name) LIKE " . $db->Quote('%' . $resumename . '%');
            $fquery .= " OR LOWER(resume.last_name) LIKE " . $db->Quote('%' . $resumename . '%');
            $fquery .= " OR LOWER(resume.middle_name) LIKE " . $db->Quote('%' . $resumename . '%');
            $fquery .= " )";
        }
        if($location)
            $fquery .= " AND LOWER(city.cityName) LIKE ".$db->Quote('%'.$location.'%');
        if ($resumecategory){
            if(is_numeric($resumecategory))
                $fquery .= " AND resume.job_category = ".$resumecategory;
        }
        if ($resumetype){
            if(is_numeric($resumetype))
                $fquery .= " AND resume.jobtype = ".$resumetype;
        }
        if ($desiredsalary){
            if(is_numeric($desiredsalary))
                $fquery .= " AND resume.desired_salary = ".$desiredsalary;
        }

        if($dateto !='' AND $datefrom !=''){
            $fquery .= " AND DATE(resume.created) <= ".$db->Quote(date('Y-m-d' , strtotime($dateto)))." AND DATE(resume.created) >= ".$db->Quote(date('Y-m-d' , strtotime($datefrom)));
        }else{
            if($dateto)
                $fquery .= " AND DATE(resume.created) <= ".$db->Quote(date('Y-m-d' , strtotime($dateto)));
            if($datefrom)
                $fquery .= " AND DATE(resume.created) >= ".$db->Quote(date('Y-m-d' , strtotime($datefrom)));
        }

        $lists = array();
        $lists['resumetitle'] = $resumetitle;
        $lists['resumename'] = $resumename;
        $lists['resumecategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category'), ''), 'resumecategory', 'class="inputbox" '. '', 'value', 'text', $resumecategory);
        $lists['resumetype'] = HTMLHelper::_('select.genericList',array(0 => array('value' => '', 'text' => Text::_('Select Job Type')), 1 => array('value' => 1 , 'text' => Text::_('Full-time')), 2 => array('value' => 2 , 'text' => Text::_('Part-time')), 3 => array('value' => 3 , 'text' => Text::_('Internship'))),'resumetype', 'class="inputbox" ', 'value', 'text', $resumetype);
        $lists['desiredsalary'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('Select Salary Range'), ''), 'desiredsalary', 'class="inputbox" ', 'value', 'text', $desiredsalary);
        $lists['location'] = $location;
        $lists['dateto'] = $dateto;
        $lists['datefrom'] = $datefrom;
        if($datafor==1)
            $lists['status'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getApprove(Text::_('Select Status'),'') , 'status', 'class="inputbox" ' , 'value', 'text', $status);
        else
            $lists['status'] = $status;

        $result = array();
        $query = "SELECT COUNT(resume.id)
                    FROM #__js_job_resume AS resume
                    LEFT JOIN #__js_job_categories AS cat ON resume.job_category = cat.id
                    LEFT JOIN #__js_job_jobtypes AS jobtype ON resume.jobtype = jobtype.id
                    LEFT JOIN #__js_job_salaryrange AS dsalary ON resume.desired_salary = dsalary.id
                    LEFT JOIN `#__js_job_cities` AS city ON city.id = (SELECT address_city FROM `#__js_job_resumeaddresses` WHERE resumeid = resume.id ORDER BY id DESC LIMIT 1) ";
                
        $query .= $fquery;                
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT resume.id, resume.application_title,resume.first_name, resume.last_name, resume.jobtype,resume.photo,
                resume.jobsalaryrange, resume.created, resume.status, cat.cat_title, dsalary.rangestart, dsalary.rangeend , dcurrency.symbol ,dsalaryrangetype.title AS srangetypetitle,
                jobtype.title AS jobtypetitle,resume.isgoldresume,resume.isfeaturedresume, resume.startgolddate, resume.startfeatureddate, resume.endgolddate, resume.endfeaturedate,
                city.id AS resumecity
            FROM #__js_job_resume AS resume
            LEFT JOIN #__js_job_categories AS cat ON resume.job_category = cat.id
            LEFT JOIN #__js_job_jobtypes AS jobtype ON resume.jobtype = jobtype.id
            LEFT JOIN #__js_job_salaryrange AS dsalary ON resume.desired_salary = dsalary.id
            LEFT JOIN #__js_job_salaryrangetypes AS dsalaryrangetype ON resume.djobsalaryrangetype = dsalaryrangetype.id
            LEFT JOIN #__js_job_currencies AS dcurrency ON dcurrency.id = resume.dcurrencyid
            LEFT JOIN `#__js_job_cities` AS city ON city.id = (SELECT address_city FROM `#__js_job_resumeaddresses` WHERE resumeid = resume.id ORDER BY id DESC LIMIT 1) ";
        $query .=$fquery;
        $query .= " GROUP BY resume.id ORDER BY $sortby ";
        
        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $resumes = array();
        foreach ($this->_application as $d) {
            $d->location = $this->getJSModel('city')->getLocationDataForView($d->resumecity);
            $d->salary = $this->getJSModel('common')->getSalaryRangeView($d->symbol,$d->rangestart,$d->rangeend,Text::_($d->srangetypetitle));
            $resumes[] = $d;
        }

        $this->_application = $resumes;

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeResume() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $row = $this->getTable('resume');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        if (!$this->_config)
            $this->_config = $this->getJSModel('configuration')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }
        if ($dateformat == 'm/d/Y') {
            $arr = explode('/', $data['date_start']);
            $data['date_start'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
            $arr = explode('/', $data['date_of_birth']);
            $data['date_of_birth'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['date_start']);
            $data['date_start'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['date_of_birth']);
            $data['date_of_birth'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
        $data['date_start'] = date('Y-m-d H:i:s', strtotime($data['date_start']));
        $data['date_of_birth'] = date('Y-m-d H:i:s', strtotime($data['date_of_birth']));
        $data['resume'] = $this->getJSModel('common')->getHtmlInput('resume');
        if (isset($data['deleteresumefile']) && ($data['deleteresumefile'] == 1)) {
            $data['filename'] = '';
            $data['filecontent'] = '';
        }
        if (isset($data['deletephoto']) && ($data['deletephoto'] == 1)) {
            $data['photo'] = '';
        }
        if (!empty($data['alias']))
            $resumealias = $this->getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $resumealias = $this->getJSModel('common')->removeSpecialCharacter($data['application_title']);

        $resumealias = strtolower(str_replace(' ', '-', $resumealias));
        $data['alias'] = $resumealias;

        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }
        $filemismatch = 0;
        $resumereturnvalue = $this->uploadResume($row->id);
        if (empty($resumereturnvalue) OR $resumereturnvalue == 6) {
            if ($returnvalue == 6)
                $filemismatch = 1;
        }else {
            $upload_resume_file_real_path = $resumereturnvalue;
        }
        $returnvalue = $this->uploadPhoto($row->id);
        $photomismatch = 0;
        if (empty($returnvalue) OR $returnvalue == 6) {
            if ($returnvalue == 6)
                $photomismatch = 1;
        }else {
            $upload_pic_real_path = $returnvalue;
        }
        if ($this->_client_auth_key != "") {
        }

        if (($filemismatch == 1) OR ( $photomismatch == 1))
            return 6;
        return true;
    }

    function uploadResume($id) {
        if (is_numeric($id) == false)
            return false;
        $row = $this->getTable('resume');
        jimport('joomla.filesystem.file');
        global $resumedata;
        $db = Factory::getDBO();
        $str = JPATH_BASE;
        $base = substr($str, 0, strlen($str) - 14); //remove administrator
        $resumequery = "SELECT * FROM `#__js_job_resume` WHERE uid = " . $db->Quote($u_id);
        $iddir = 'resume_' . $id;
        if (!isset($this->_config))
            $this->_config = $this->getJSModel('configuration')->getConfig();
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
            if ($conf->configname == 'document_file_type')
                $document_file_types = $conf->configvalue;
        }
        $path = $base . '/' . $datadirectory;

        $resume_file_file = Factory::getApplication()->input->files->get('resume_file');
        $resume_file_filename = File::makeSafe($resume_file_file['name']);
        $resume_file_tmpname = $resume_file_file['tmp_name'];
        $resume_file_size = $resume_file_file['size'];

        if ($resume_file_size > 0) {
            $file_name = $resume_file_filename; // file name
            $file_tmp = $resume_file_tmpname; // actual location
            $file_size = $resume_file_size; // file size
            $file_type = ""; // mime type of file determined by php

            if (!empty($file_tmp)) { // only MS office and text file is accepted.
                $check_document_extension = $this->getJSModel('common')->checkDocumentFileExtensions($file_name, $file_tmp, $document_file_types);
                if ($check_document_extension == 6) {
                    $row->load($id);
                    $row->filename = "";
                    $row->filetype = "";
                    $row->filesize = "";
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                    return $check_document_extension;
                } else {
                    $row->load($id);
                    $row->filename = getJSJobsPHPFunctionsClass()->sanitizeData($file_name);
                    $row->filetype = getJSJobsPHPFunctionsClass()->sanitizeData($file_type);
                    $row->filesize = $file_size;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }

                if (!file_exists($path)) { // creating main directory
                    $this->getJSModel('common')->makeDir($path);
                }
                $path = $path . '/data';
                if (!file_exists($path)) { // creating data directory
                    $this->getJSModel('common')->makeDir($path);
                }
                $path = $path . '/jobseeker';
                if (!file_exists($path)) { // creating jobseeker directory
                    $this->getJSModel('common')->makeDir($path);
                }
                $userpath = $path . '/' . $iddir;
                if (!file_exists($userpath)) { // create user directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $userpath = $path . '/' . $iddir . '/resume';
                if (!file_exists($userpath)) { // create user directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $files = glob($userpath . '/*.*');
                array_map('unlink', $files);  //delete all file in user directory

                move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
                return $userpath . '/' . $file_name;
                return 1;
            } else {
                if ($resumedata['deleteresumefile'] == 1) {
                    $path = $path . '/data/jobseeker';
                    $userpath = $path . '/' . $iddir . '/resume';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files);
                    $row->load($id);
                    $row->filename = "";
                    $row->filetype = "";
                    $row->filesize = "";
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                } else {
                    
                }
                return 1;
            }
        }
    }

    function uploadPhoto($id) {
        if (is_numeric($id) == false)
            return false;
        $row = $this->getTable('resume');
        jimport('joomla.filesystem.file');
        global $resumedata;
        $db = Factory::getDBO();
        $str = JPATH_BASE;
        $base = substr($str, 0, strlen($str) - 14); //remove administrator
        if (!isset($this->_config))
            $this->_config = $this->getJSModel('configuration')->getConfig();
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
            if ($conf->configname == 'image_file_type')
                $image_file_types = $conf->configvalue;
        }
        $path = $base . '/' . $datadirectory;

        $resumequery = "SELECT * FROM `#__js_job_resume` WHERE uid = " . $db->Quote($u_id);
        $iddir = 'resume_' . $id;

        $photo_file = Factory::getApplication()->input->files->get('photo');
        $photo_filename = File::makeSafe($photo_file['name']);
        $photo_tmpname = $photo_file['tmp_name'];
        $photo_size = $photo_file['size'];

        if ($photo_size > 0) {
            $file_name = $photo_filename; // file name
            $file_tmp = $photo_tmpname; // actual location
            $file_size = $photo_size; // file size
            $file_type = ""; // mime type of file determined by php
            if (!empty($file_tmp)) {
                $check_image_extension = $this->getJSModel('common')->checkImageFileExtensions($file_name, $file_tmp, $image_file_types);
                if ($check_image_extension == 6) {
                    $row->load($id);
                    $row->photo = "";
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                    return $check_image_extension;
                } else {
                    $row->load($id);
                    $row->photo = getJSJobsPHPFunctionsClass()->sanitizeData($file_name);
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
            }

            if (!file_exists($path)) { // creating main directory
                $this->getJSModel('common')->makeDir($path);
            }
            $path = $path . '/data';
            if (!file_exists($path)) { // creating data directory
                $this->getJSModel('common')->makeDir($path);
            }
            $path = $path . '/jobseeker';
            if (!file_exists($path)) { // creating jobseeker directory
                $this->getJSModel('common')->makeDir($path);
            }
            $userpath = $path . '/' . $iddir;
            if (!file_exists($userpath)) { // create user directory
                $this->getJSModel('common')->makeDir($userpath);
            }
            $userpath = $path . '/' . $iddir . '/photo';
            if (!file_exists($userpath)) { // create user directory
                $this->getJSModel('common')->makeDir($userpath);
            }
            $files = glob($userpath . '/*.*');
            array_map('unlink', $files);  //delete all file in user directory

            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);

            $ext = $this->getJSModel('common')->getExtension($file_name);
            $ext = strtolower($ext);

            $imagetypes = array(
                'ani','bmp','cal','fax','gif','img','jbg','jpe','jpeg','jpg','mac','pbm','pcd','pcx','pct','pgm','png','ppm','psd','ras','tga','tiff','wmf','tif'
            );        
                /*
            if(in_array($ext,$imagetypes)){
                $mimetype = mime_content_type($userpath . '/' . $file_name);
                $flag = false;
                foreach($imagetypes AS $type){
                    if($mimetype == "image/$type"){
                        $flag = true;
                    }
                }
                if($flag == false){
                    @unlink($userpath.'/'.$file_name);
                    $query = "UPDATE `#__js_job_resume` SET photo = '' WHERE id = ".$id;
                    $db = Factory::getDBO();
                    $db->setQuery($query);
                    $db->execute();
                }
            }
                */


            return $userpath . '/' . $file_name;
            return 1;
        } else {
            if ($resumedata['deleteresumefile'] == 1) {
                $path = $path . '/data/jobseeker';
                $userpath = $path . '/' . $iddir . '/photo';
                $files = glob($userpath . '/*.*');
                array_map('unlink', $files);
                $row->load($id);
                $row->photo = "";
                if (!$row->store()) {
                    $this->setError($row->getError());
                }
            } else {
                
            }
            return 1;
        }
    }

    function deleteResume() {
        if(!Factory::getSession()->checkToken('post')){
	        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        }
        $db = $this->getDBO();
        $cids = Factory::getApplication()->input->get('cid', array(0), '', 'array');
        $row = $this->getTable('resume');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if (is_numeric($cid) == false)
                return false;
            $juid = 0; // jobseeker uid
            $serverresumeid = 0;
            if ($this->_client_auth_key != "") {
                $query = "SELECT resume.serverid AS id,resume.uid AS uid FROM `#__js_job_resume` AS resume WHERE resume.id = " . $cid;
                $db->setQuery($query);
                $data = $db->loadObject();
                $serverresumeid = $data->id;
                $juid = $data->uid;
            }
            if ($this->resumeCanDelete($cid) == true) {
                $query = "SELECT app.application_title, app.first_name, app.middle_name, app.last_name, app.email_address,CONCAT(app.alias,'-',app.id) AS aliasid 
                            FROM `#__js_job_resume` AS app
                            WHERE app.id = " . $cid;

                $db->setQuery($query);
                $app = $db->loadObject();

                $name = $app->first_name;
                if ($app->middle_name)
                    $name .= " " . $app->middle_name;
                if ($app->last_name)
                    $name .= " " . $app->last_name;
                $Email = $app->email_address;
                $resumeTitle = $app->application_title;

                $session = Factory::getApplication()->getSession();
                $session->set('name',$name);
                $session->set('email_address',$Email);
                $session->set('application_title',$resumeTitle);

                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }else{ // resume has been deleted so we delete record from their child tables
                    $db = Factory::getDBO();
                    $query = "DELETE FROM `#__js_job_resumeaddresses` WHERE resumeid = ".$cid;
                    $db->setQuery($query);$db->execute();
                    $query = "DELETE FROM `#__js_job_resumeemployers` WHERE resumeid = ".$cid;
                    $db->setQuery($query);$db->execute();
                    $query = "DELETE FROM `#__js_job_resumeinstitutes` WHERE resumeid = ".$cid;
                    $db->setQuery($query);$db->execute();
                    $query = "DELETE FROM `#__js_job_resumelanguages` WHERE resumeid = ".$cid;
                    $db->setQuery($query);$db->execute();
                    $query = "DELETE FROM `#__js_job_resumereferences` WHERE resumeid = ".$cid;
                    $db->setQuery($query);$db->execute();
                }
                $this->getJSModel('emailtemplate')->sendDeleteMail( $cid , 3);
                if ($serverresumeid != 0) {
                    $data = array();
                    $data['id'] = $serverresumeid;
                    $data['referenceid'] = $cid;
                    $data['uid'] = $juid;
                    $data['authkey'] = $this->_client_auth_key;
                    $data['siteurl'] = $this->_siteurl;
                    $data['task'] = 'deleteresume';
                    $jsjobsharingobject = $this->getJSModel('jobsharing');
                    $return_value = $jsjobsharingobject->deleteResumeSharing($data);
                    $jsjobslogobject = $this->getJSModel('log');
                    $jsjobslogobject->logDeleteResumeSharing($return_value);
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteEmpApp() {
        $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
        $row = $this->getTable('empapp');

        foreach ($cids as $cid) {
            if (!$row->delete($cid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function resumeCanDelete($resumeid) {
        if (is_numeric($resumeid) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE cvid = " . $resumeid . ")
                    AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function resumeEnforceDelete($resumeid, $uid) {
        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($resumeid) == false)
            return false;
        $db = $this->getDBO();
        $juid = 0; // jobseeker uid
        $serverresumeid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT resume.serverid AS id,resume.uid AS uid FROM `#__js_job_resume` AS resume WHERE resume.id = " . $resumeid;
            $db->setQuery($query);
            $data = $db->loadObject();
            $serverresumeid = $data->id;
            $juid = $data->uid;
        }

        $query = "SELECT app.application_title, app.first_name, app.middle_name, app.last_name, app.email_address,CONCAT(app.alias,'-',app.id) AS aliasid 
                    FROM `#__js_job_resume` AS app
                    WHERE app.id = " . $resumeid;

        $db->setQuery($query);
        $app = $db->loadObject();

        $name = $app->first_name;
        if ($app->middle_name)
            $name .= " " . $app->middle_name;
        if ($app->last_name)
            $name .= " " . $app->last_name;
        $Email = $app->email_address;
        $resumeTitle = $app->application_title;

        $session = Factory::getApplication()->getSession();
        $session->set('name',$name);
        $session->set('email_address',$Email);
        $session->set('application_title',$resumeTitle);


        $query = "DELETE  resume,apply,address,emp,inst,lang,ref
                    FROM `#__js_job_resume` AS resume
                    LEFT JOIN `#__js_job_jobapply` AS apply ON resume.id=apply.cvid
                    LEFT JOIN `#__js_job_resumeaddresses` AS address ON resume.id = address.resumeid
                    LEFT JOIN `#__js_job_resumeemployers` AS emp ON resume.id = emp.resumeid
                    LEFT JOIN `#__js_job_resumeinstitutes` AS inst ON resume.id = inst.resumeid
                    LEFT JOIN `#__js_job_resumelanguages` AS lang ON resume.id = lang.resumeid
                    LEFT JOIN `#__js_job_resumereferences` AS ref ON resume.id = ref.resumeid
                    WHERE resume.id = " . $resumeid;

        $db->setQuery($query);
        if (!$db->execute()) {
            return 2; //error while delete resume
        }
        $this->getJSModel('emailtemplate')->sendDeleteMail( $resumeid , 3);
        if ($serverresumeid != 0) {
            $data = array();
            $data['id'] = $serverresumeid;
            $data['referenceid'] = $cid;
            $data['uid'] = $juid;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $data['enforcedeleteresume'] = 1;
            $data['task'] = 'deleteresume';
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_value = $jsjobsharingobject->deleteResumeSharing($data);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logDeleteResumeSharingEnforce($return_value);
        }
        return 1;
    }


    function empappApprove($app_id) {
        if (is_numeric($app_id) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_resume SET status = 1 WHERE id = " . $db->Quote($app_id);
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        $this->getJSModel('emailtemplate')->sendMail(3, 1, $app_id);
        if ($this->_client_auth_key != "") {
            $data_resume_approve = array();
            $query = "SELECT serverid FROM #__js_job_resume WHERE id = " . $app_id;
            $db->setQuery($query);
            $serverresumeid = $db->loadResult();
            $data_resume_approve['id'] = $serverresumeid;
            $data_resume_approve['resume_id'] = $app_id;
            $data_resume_approve['authkey'] = $this->_client_auth_key;
            $fortask = "resumeapprove";
            $server_json_data_array = json_encode($data_resume_approve);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logEmpappApprove($return_value);
        }
        return true;
    }

    function empappReject($app_id) {
        if (is_numeric($app_id) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_resume SET status = -1 WHERE id = " . $db->Quote($app_id);
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        $this->getJSModel('emailtemplate')->sendMail(3, -1, $app_id);
        if ($this->_client_auth_key != "") {
            $data_resume_reject = array();
            $query = "SELECT serverid FROM #__js_job_resume WHERE id = " . $app_id;
            $db->setQuery($query);
            $serverresumeid = $db->loadResult();
            $data_resume_reject['id'] = $serverresumeid;
            $data_resume_reject['resume_id'] = $app_id;
            $data_resume_reject['authkey'] = $this->_client_auth_key;
            $fortask = "resumereject";
            $server_json_data_array = json_encode($data_resume_reject);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('jobsharing');
            $jsjobslogobject->logEmpappReject($return_value);
        }
        return true;
    }

    function getEmpOptions() {
        if (!$this->_empoptions) {
            $this->_empoptions = array();

            $gender = array(
                '0' => array('value' => 1, 'text' => Text::_('Male')),
                '1' => array('value' => 2, 'text' => Text::_('Female')),);

            $status = array(
                '0' => array('value' => 0, 'text' => Text::_('Pending')),
                '1' => array('value' => 1, 'text' => Text::_('Approve')),
                '2' => array('value' => -1, 'text' => Text::_('Reject')),);

            $job_type = $this->getJSModel('jobtype')->getJobType('');
            $heighesteducation = $this->getJSModel('highesteducation')->getHeighestEducation('');
            $job_categories = $this->getJSModel('category')->getCategories('', '');
            $job_salaryrange = $this->getJSModel('salaryrange')->getJobSalaryRange('', '');
            $countries = $this->getJSModel('country')->getCountries('');
            if (isset($this->_application)) {
                $job_subcategories = $this->getJSModel('subcategory')->getSubCategoriesforCombo($this->_application->job_category, '', $this->_application->job_subcategory);
            } else {
                $job_subcategories = $this->getJSModel('subcategory')->getSubCategoriesforCombo($job_categories[0]['value'], '', '');
            }

            if (isset($this->_application)) {
                $this->_empoptions['nationality'] = HTMLHelper::_('select.genericList', $countries, 'nationality', 'class="inputbox" ' . '', 'value', 'text', $this->_application->nationality);
                $this->_empoptions['gender'] = HTMLHelper::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', $this->_application->gender);

                $this->_empoptions['job_category'] = HTMLHelper::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_application->job_category);
                $this->_empoptions['job_subcategory'] = HTMLHelper::_('select.genericList', $job_subcategories, 'job_subcategory', 'class="inputbox" ' . '', 'value', 'text', $this->_application->job_subcategory);

                $this->_empoptions['jobtype'] = HTMLHelper::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobtype);
                $this->_empoptions['heighestfinisheducation'] = HTMLHelper::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', $this->_application->heighestfinisheducation);
                $this->_empoptions['jobsalaryrange'] = HTMLHelper::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobsalaryrange);
                $this->_empoptions['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $this->_application->status);
                $this->_empoptions['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox required" ' . '', 'value', 'text', $this->_application->currencyid);
                $address_city = ($this->_application->address_city == "" OR $this->_application->address_city == 0 ) ? -1 : $this->_application->address_city;
                $this->_empoptions['address_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $address_city);
                $address1_city = ($this->_application->address1_city == "" OR $this->_application->address1_city == 0 ) ? -1 : $this->_application->address1_city;
                $this->_empoptions['address1_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $address1_city);
                $address2_city = ($this->_application->address2_city == "" OR $this->_application->address2_city == 0 ) ? -1 : $this->_application->address2_city;
                $this->_empoptions['address2_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $address2_city);
                $institute_city = ($this->_application->institute_city == "" OR $this->_application->institute_city == 0 ) ? -1 : $this->_application->institute_city;
                $this->_empoptions['institute_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $institute_city);
                $institute1_city = ($this->_application->institute1_city == "" OR $this->_application->institute1_city == 0 ) ? -1 : $this->_application->institute1_city;
                $this->_empoptions['institute1_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $institute1_city);
                $institute2_city = ($this->_application->institute2_city == "" OR $this->_application->institute2_city == 0 ) ? -1 : $this->_application->institute2_city;
                $this->_empoptions['institute2_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $institute2_city);
                $institute3_city = ($this->_application->institute3_city == "" OR $this->_application->institute3_city == 0 ) ? -1 : $this->_application->institute3_city;
                $this->_empoptions['institute3_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $institute3_city);
                $employer_city = ($this->_application->employer_city == "" OR $this->_application->employer_city == 0 ) ? -1 : $this->_application->employer_city;
                $this->_empoptions['employer_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $employer_city);
                $employer1_city = ($this->_application->employer1_city == "" OR $this->_application->employer1_city == 0 ) ? -1 : $this->_application->employer1_city;
                $this->_empoptions['employer1_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $employer1_city);
                $employer2_city = ($this->_application->employer2_city == "" OR $this->_application->employer2_city == 0 ) ? -1 : $this->_application->employer2_city;
                $this->_empoptions['employer2_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $employer2_city);
                $employer3_city = ($this->_application->employer3_city == "" OR $this->_application->employer3_city == 0 ) ? -1 : $this->_application->employer3_city;
                $this->_empoptions['employer3_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $employer3_city);
                $reference_city = ($this->_application->reference_city == "" OR $this->_application->reference_city == 0 ) ? -1 : $this->_application->reference_city;
                $this->_empoptions['reference_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $reference_city);
                $reference1_city = ($this->_application->reference1_city == "" OR $this->_application->reference1_city == 0 ) ? -1 : $this->_application->reference1_city;
                $this->_empoptions['reference1_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $reference1_city);
                $reference2_city = ($this->_application->reference2_city == "" OR $this->_application->reference2_city == 0 ) ? -1 : $this->_application->reference2_city;
                $this->_empoptions['reference2_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $reference2_city);
                $reference3_city = ($this->_application->reference3_city == "" OR $this->_application->reference3_city == 0 ) ? -1 : $this->_application->reference3_city;
                $this->_empoptions['reference3_city'] = $this->getJSModel('city')->getAddressDataByCityName('', $reference3_city);
            } else {
                $this->_empoptions['nationality'] = HTMLHelper::_('select.genericList', $countries, 'nationality', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['gender'] = HTMLHelper::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['job_category'] = HTMLHelper::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
                $this->_empoptions['job_subcategory'] = HTMLHelper::_('select.genericList', $job_subcategories, 'job_subcategory', 'class="inputbox" ' . '', 'value', 'text', '');


                $this->_empoptions['jobtype'] = HTMLHelper::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['heighestfinisheducation'] = HTMLHelper::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['jobsalaryrange'] = HTMLHelper::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
                $this->_empoptions['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox required" ' . '', 'value', 'text', '');
            }
        }
        return $this->_empoptions;
    }

    function getUserStatsResumes($resumeuid, $limitstart, $limit) {
        if (is_numeric($resumeuid) == false)
            return false;
        $db = Factory::getDBO();
        $result = array();

        $query = 'SELECT COUNT(resume.id) FROM #__js_job_resume AS resume WHERE resume.uid = ' . $resumeuid;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = 'SELECT resume.id,resume.application_title,resume.first_name,resume.last_name,cat.cat_title,resume.created,resume.status
                    FROM #__js_job_resume AS resume
                    LEFT JOIN #__js_job_categories AS cat ON cat.id=resume.job_category
                    WHERE resume.uid = ' . $resumeuid;
        $query .= ' ORDER BY resume.first_name';
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function postResumeOnJomSocial($resumeid){
        if( $this->getJSModel(VIRTUEMARTJSJOBS)->{VIRTUEMARTJSJOBSFUN}("aWVCV3Jiam9tc29jaWFsZGpjek5o") ){
            JPluginHelper::importPlugin('community');
            $dispatcher = JEventDispatcher::getInstance();
            $res = $dispatcher->trigger('JSjobsPostResumeOnJomSocial',array($resumeid));
            if(!$res[0])
                return false;
        }
        return true;
    }

    function jmGetResumeById($id){
        if (is_numeric($id) == false)
            return false;

        $db = Factory::getDBO();
        $query = "SELECT resume.id, resume.params, resume.experienceid, exp.title AS experiencetitle, resume.license_no, licensecountry.name AS licensecountryname,
        resume.driving_license, resume.uid, resume.created, resume.last_modified, resume.published, resume.hits 
        , resume.application_title, resume.keywords, resume.alias, resume.first_name, resume.last_name 
        , resume.middle_name, resume.gender, resume.email_address, resume.home_phone, resume.work_phone 
        , resume.cell, resume.nationality, resume.iamavailable, resume.searchable, resume.photo 
        , resume.job_category, resume.jobsalaryrange, resume.jobsalaryrangetype, resume.jobtype 
        , resume.heighestfinisheducation, resume.status, resume.resume, resume.date_start, resume.desired_salary 
        , resume.djobsalaryrangetype, resume.dcurrencyid, resume.can_work, resume.available, resume.unavailable 
        , resume.total_experience, resume.skills, resume.driving_license, resume.license_no, resume.license_country 
        , resume.packageid, resume.paymenthistoryid, resume.currencyid, resume.job_subcategory 
        , resume.date_of_birth, resume.video, resume.isgoldresume, resume.isfeaturedresume, resume.serverstatus 
        , resume.serverid, heighesteducation.title AS heighesteducationtitle
        , nationality_country.name AS nationalitycountry 
        , currency.symbol,dcurrency.symbol AS dsymbol, cat.cat_title AS categorytitle, subcat.title AS subcategorytitle, salary.rangestart 
        , salary.rangeend, dsalary.rangeend AS drangeend, dsalary.rangestart AS drangestart, jobtype.title AS jobtypetitle
        , CONCAT(resume.alias,'-',resume.id) AS resumealiasid 
        , salarytype.title AS salarytype, dsalarytype.title AS dsalarytype        
        FROM `#__js_job_resume` AS resume
        LEFT JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
        LEFT JOIN `#__js_job_subcategories` AS subcat ON resume.job_subcategory = subcat.id
        LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
        LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id
        LEFT JOIN `#__js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id
        LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
        LEFT JOIN  `#__js_job_salaryrangetypes` AS salarytype ON resume.jobsalaryrangetype = salarytype.id
        LEFT JOIN `#__js_job_countries` AS licensecountry ON resume.license_country = licensecountry.id
        LEFT JOIN `#__js_job_countries` AS countries ON resume.nationality = nationality_country.id
        LEFT JOIN `#__js_job_salaryrange` AS dsalary ON resume.desired_salary = dsalary.id
        LEFT JOIN  `#__js_job_salaryrangetypes` AS dsalarytype ON resume.djobsalaryrangetype = dsalarytype.id
        LEFT JOIN `#__js_job_currencies` AS dcurrency ON dcurrency.id = resume.dcurrencyid
        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid
        LEFT JOIN `#__js_job_experiences` AS exp ON exp.id = resume.experienceid
        WHERE resume.id = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObject();
        $resume->params = empty($resume->params)? new stdClass() : json_decode($resume->params);
        $result['personal'] = $resume;

        $query = "SELECT address.id,address.params, address.resumeid, address.address 
        , address.address_city AS address_cityid, address.address_zipcode 
        , address.longitude, address.latitude, address.created 
        , countries.name AS address_countryname, cities.name AS address_cityname 
        , states.name AS address_statename         
        FROM `#__js_job_resumeaddresses` AS address
        LEFT JOIN `#__js_job_cities` AS cities ON cities.id = address.address_city
        LEFT JOIN `#__js_job_states` AS states ON states.id = cities.stateid
        LEFT JOIN `#__js_job_countries` AS countries ON countries.id = cities.countryid
        WHERE address.resumeid = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['addresses'] = $resume;

        $query = "SELECT institute.id, institute.params, institute.resumeid, institute.institute 
        , institute.institute_address, institute.institute_city AS institute_cityid 
        , institute.institute_certificate_name, institute.institute_study_area, institute.created 
        , countries.name AS institute_countryname, cities.name AS institute_cityname 
        , states.name AS institute_statename                 
        FROM `#__js_job_resumeinstitutes` AS institute
        LEFT JOIN `#__js_job_cities` AS cities ON institute.institute_city = cities.id
        LEFT JOIN `#__js_job_states` AS states ON cities.stateid = states.id
        LEFT JOIN `#__js_job_countries` AS countries ON cities.countryid = countries.id
        WHERE institute.resumeid = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['institutes'] = $resume;

        $query = "SELECT employer.id, employer.params, employer.resumeid, employer.employer, employer.employer_address 
        , employer.employer_city AS employer_cityid, employer.employer_position 
        , employer.employer_resp, employer.employer_pay_upon_leaving, employer.employer_supervisor 
        , states.name AS employer_statename, employer.created, employer.last_modified 
        , countries.name AS employer_countryname, cities.name AS employer_cityname 
        , employer.employer_from_date, employer.employer_to_date 
        , employer.employer_leave_reason, employer.employer_zip 
        , employer.employer_phone        
        FROM `#__js_job_resumeemployers` AS employer
        LEFT JOIN `#__js_job_cities` AS cities ON employer.employer_city = cities.id
        LEFT JOIN `#__js_job_states` AS states ON cities.stateid = states.id
        LEFT JOIN `#__js_job_countries` AS countries ON cities.countryid = countries.id
        WHERE employer.resumeid = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['employers'] = $resume;

        $query = "SELECT reference.id, reference.params, reference.resumeid, reference.reference 
        , reference.reference_name, reference.reference_zipcode 
        , reference.reference_city AS reference_cityid, reference.reference_address 
        , reference.reference_phone, reference.reference_relation 
        , reference.reference_years, reference.created, reference.last_modified 
        , countries.name AS reference_countryname, cities.name AS reference_cityname 
        , states.name AS reference_statename         
        FROM `#__js_job_resumereferences` AS reference
        LEFT JOIN `#__js_job_cities` AS cities ON reference.reference_city = cities.id
        LEFT JOIN `#__js_job_states` AS states ON cities.stateid = states.id
        LEFT JOIN `#__js_job_countries` AS countries ON cities.countryid = countries.id
        WHERE reference.resumeid = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['references'] = $resume;

        $query = "SELECT language.id, language.params, language.resumeid, language.language 
        , language.language_reading, language.language_writing 
        , language.language_understanding, language.language_where_learned 
        , language.created, language.last_modified 
        FROM `#__js_job_resumelanguages` AS language WHERE language.resumeid = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObjectList();
        $result['languages'] = $resume;

        $fores = JSModel::getJSModel('customfield')->getFieldsOrdering(3);
        $fieldsordering = array();
        foreach ($fores AS $key => $field) {
            if($field->issocialpublished)
                $fieldsordering[$field->field] = $field->issocialpublished;
        }
        return array($result, $fieldsordering);
    }

    function updateResumeDataForEraseDataRequest($resumeid,$uid){
        if(!is_numeric($resumeid) || $resumeid == 0)
            return false;
        if(!is_numeric($uid) || $uid == 0)
            return false;
        $db = Factory::getDBO();
        
        $query = "UPDATE `#__js_job_resume` SET `application_title`='-----',`keywords`='' , `first_name`='-----',`last_name`='-----',`middle_name`='-----',`email_address`='-----',`home_phone`='-----',`work_phone`='-----',`cell`='-----',`nationality`='-----',`iamavailable`='',`searchable`= 0,`photo`='',`job_category`=0,`jobsalaryrange`=0,`jobsalaryrangetype`=0,`jobtype`=0,`heighestfinisheducation`=0,`status`=0,`resume`=0,`desired_salary`='-----',`djobsalaryrangetype`=0,`dcurrencyid`=0,`can_work`=0,`available`=0,`unavailable`=0,`experienceid`=0,`total_experience`='-----',`skills`='-----',`driving_license`='-----',`license_no`='-----',`license_country`='-----',`job_subcategory`= 0,`video`='-----',`isgoldresume`=0,`isfeaturedresume`=0,`notifications`=0,`serverstatus`='-----',`serverid`=0,`videotype`='',`isnotify`=0,`notify_type`=0,`params`='-----' WHERE id = " . $resumeid . " AND uid = " . $uid;
        $db->setQuery($query);
        if($db->execute()){
            $this->removeUserResumeDataByResumeId($resumeid);
        }else{
            return false;
        }

        return true;
    }

    function removeUserResumeDataByResumeId($resumeid){
        if(!is_numeric($resumeid) || $resumeid == 0)
            return false;
        $db = Factory::getDBO();

        $query = "DELETE FROM `#__js_job_resumeaddresses` WHERE resumeid = " . $resumeid;
        $db->setQuery($query);
        $db->execute();   

        // resume employer detail
        $query = "DELETE FROM `#__js_job_resumeemployers` WHERE resumeid = " . $resumeid;
        $db->setQuery($query);
        $db->execute();        

        // resume files data
        $datadirectory = $this->getJSModel('configuration')->getConfigValue('data_directory');
        $mainpath = JPATH_BASE;
        if(Factory::getApplication()->isClient('administrator')){
            $mainpath = substr($mainpath, 0, strlen($mainpath) - 14); //remove administrator

        }
        $mainpath = $mainpath .'/'.$datadirectory.'/data';
        $mainpath = $mainpath . '/jobseeker/';
        $folder = $mainpath . 'resume_'.$resumeid.'';
        if(file_exists($folder)){
            $logo = $folder . '/photo/';
            if(file_exists($logo)){
                $path = $logo . '*.*';
                $files = glob($path);
                array_map('unlink', $files);//deleting files
                rmdir($logo);
            }
            $logo = $folder;
            if(file_exists($logo)){
                $path = $logo . '/*.*';
                $files = glob($path);
                array_map('unlink', $files);//deleting files
            }
            rmdir($folder);
        }

        // for removing internal data
        $mainpath = JPATH_BASE;
        $mainpath = $mainpath .'/'.$datadirectory.'/data';
        $mainpath = $mainpath . '/jobseeker/';
        $folder = $mainpath . 'resume_'.$resumeid.'';
        if(file_exists($folder)){
            $resume = $folder . '/resume/';
            if(file_exists($resume)){
                $path = $resume . '*.*';
                $files = glob($path);
                array_map('unlink', $files);//deleting files
                rmdir($resume);
            }
            $path = $folder . '/*.*';
            $files = glob($path);
            array_map('unlink', $files);//deleting files
            rmdir($folder);
        }
        
        $query = "DELETE FROM `#__js_job_resumefiles` WHERE resumeid = " . $resumeid;
        $db->setQuery($query);
        $db->execute();

        // resume institue
        $query = "DELETE FROM `#__js_job_resumeinstitutes` WHERE resumeid = " . $resumeid;
        $db->setQuery($query);
        $db->execute();

        // resume languages
        $query = "DELETE FROM `#__js_job_resumelanguages` WHERE resumeid = " . $resumeid;
        $db->setQuery($query);
        $db->execute();


        // references
        $query = "DELETE FROM `#__js_job_resumereferences` WHERE resumeid = " . $resumeid;
        $db->setQuery($query);
        $db->execute();
    }


    function importAIStringDataForResumes($personal_section) {
        if(empty($personal_section)){
            return;
        }
        //$personal_section = $data['sec_1'];
        $resume_ai_string_main = '';

        // Application title
        if (!empty($personal_section['application_title'])) {
            $resume_ai_string_main .= trim($personal_section['application_title']) . ' ';
        }

        // Job Category
        if (!empty($personal_section['job_category']) && is_numeric($personal_section['job_category'])) {
            $cat_id = $personal_section['job_category'];
            if (!isset($this->_ai_resume_data_array['categories'][$cat_id])) {
                $this->_ai_resume_data_array['categories'][$cat_id] = $this->getJSSiteModel('category')->getTitleByCategory($cat_id);
            }
            if ($this->_ai_resume_data_array['categories'][$cat_id]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['categories'][$cat_id] . ' ';
            }
        }

        // Job Subcategory
        if (!empty($personal_section['job_subcategory']) && is_numeric($personal_section['job_subcategory'])) {
            $sub_cat_id = $personal_section['job_subcategory'];
            if (!isset($this->_ai_resume_data_array['categories'][$sub_cat_id])) {
                $this->_ai_resume_data_array['categories'][$sub_cat_id] = $this->getJSSiteModel('category')->getTitleByCategory($sub_cat_id);
            }
            if ($this->_ai_resume_data_array['categories'][$sub_cat_id]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['categories'][$sub_cat_id] . ' ';
            }
        }

        // Job Type
        if (!empty($personal_section['jobtype']) && is_numeric($personal_section['jobtype'])) {
            $type_id = $personal_section['jobtype'];
            if (!isset($this->_ai_resume_data_array['jobtypes'][$type_id])) {
                $this->_ai_resume_data_array['jobtypes'][$type_id] = $this->getJSSiteModel('jobtype')->getTitleById($type_id);
            }
            if ($this->_ai_resume_data_array['jobtypes'][$type_id]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['jobtypes'][$type_id] . ' ';
            }
        }

        // Nationality
        if (!empty($personal_section['nationality']) && is_numeric($personal_section['nationality'])) {
            $country_id = $personal_section['nationality'];
            if (!isset($this->_ai_resume_data_array['countries'][$country_id])) {
                $this->_ai_resume_data_array['countries'][$country_id] = $this->getJSSiteModel('countries')->getCountryNameById($country_id);
            }
            if ($this->_ai_resume_data_array['countries'][$country_id]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['countries'][$country_id] . ' ';
            }
        }

        // Job Salary
        if (!empty($personal_section['currencyid'])) {
            if (!isset($this->_ai_resume_data_array['currency'][$personal_section['currencyid']])) {
                $this->_ai_resume_data_array['currency'][$personal_section['currencyid']] = $this->getJSModel('currency')->getCurrencySymbol((int)$personal_section['currencyid']);
            }
            if ($this->_ai_resume_data_array['currency'][$personal_section['currencyid']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['currency'][$personal_section['currencyid']] . ' ';
            }
        }

        if (!empty($personal_section['jobsalaryrange'])) {
            if (!isset($this->_ai_resume_data_array['salaryrange'][$personal_section['jobsalaryrange']])) {
                $this->_ai_resume_data_array['salaryrange'][$personal_section['jobsalaryrange']] = $this->getJSSiteModel('salaryrange')->getSalaryRangeById((int)$personal_section['jobsalaryrange']);
            }
            if ($this->_ai_resume_data_array['salaryrange'][$personal_section['jobsalaryrange']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['salaryrange'][$personal_section['jobsalaryrange']] . ' ';
            }
        }

        if (!empty($personal_section['jobsalaryrangetype'])) {
            if (!isset($this->_ai_resume_data_array['salaryrangetype'][$personal_section['jobsalaryrangetype']])) {
                $this->_ai_resume_data_array['salaryrangetype'][$personal_section['jobsalaryrangetype']] = $this->getJSSiteModel('salaryrangetype')->getTitleById((int)$personal_section['jobsalaryrangetype']);
            }
            if ($this->_ai_resume_data_array['salaryrangetype'][$personal_section['jobsalaryrangetype']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['salaryrangetype'][$personal_section['jobsalaryrangetype']] . ' ';
            }
        }

        // Desired Salary
        if (!empty($personal_section['dcurrencyid'])) {
            if (!isset($this->_ai_resume_data_array['currency'][$personal_section['dcurrencyid']])) {
                $this->_ai_resume_data_array['currency'][$personal_section['dcurrencyid']] = $this->getJSModel('currency')->getCurrencySymbol((int)$personal_section['dcurrencyid']);
            }
            if ($this->_ai_resume_data_array['currency'][$personal_section['dcurrencyid']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['currency'][$personal_section['dcurrencyid']] . ' ';
            }
        }

        if (!empty($personal_section['desired_salary'])) {
            if (!isset($this->_ai_resume_data_array['salaryrange'][$personal_section['desired_salary']])) {
                $this->_ai_resume_data_array['salaryrange'][$personal_section['desired_salary']] = $this->getJSSiteModel('salaryrange')->getSalaryRangeById((int)$personal_section['desired_salary']);
            }
            if ($this->_ai_resume_data_array['salaryrange'][$personal_section['desired_salary']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['salaryrange'][$personal_section['desired_salary']] . ' ';
            }
        }

        if (!empty($personal_section['djobsalaryrangetype'])) {
            if (!isset($this->_ai_resume_data_array['salaryrangetype'][$personal_section['djobsalaryrangetype']])) {
                $this->_ai_resume_data_array['salaryrangetype'][$personal_section['djobsalaryrangetype']] = $this->getJSSiteModel('salaryrangetype')->getTitleById((int)$personal_section['djobsalaryrangetype']);
            }
            if ($this->_ai_resume_data_array['salaryrangetype'][$personal_section['djobsalaryrangetype']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['salaryrangetype'][$personal_section['djobsalaryrangetype']] . ' ';
            }
        }

        // Experience
        if (!empty($personal_section['experienceid'])) {
            if (!isset($this->_ai_resume_data_array['experience'][$personal_section['experienceid']])) {
                $this->_ai_resume_data_array['experience'][$personal_section['experienceid']] = $this->getJSSiteModel('experience')->getTitleById((int)$personal_section['experienceid']);
            }
            if ($this->_ai_resume_data_array['experience'][$personal_section['experienceid']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['experience'][$personal_section['experienceid']] . ' ';
            }
        }

        // Highest Education
        if (!empty($personal_section['heighestfinisheducation'])) {
            if (!isset($this->_ai_resume_data_array['highesteducation'][$personal_section['heighestfinisheducation']])) {
                $this->_ai_resume_data_array['highesteducation'][$personal_section['heighestfinisheducation']] = $this->getJSSiteModel('highesteducation')->getTitleById((int)$personal_section['heighestfinisheducation']);
            }
            if ($this->_ai_resume_data_array['highesteducation'][$personal_section['heighestfinisheducation']]) {
                $resume_ai_string_main .= $this->_ai_resume_data_array['highesteducation'][$personal_section['heighestfinisheducation']] . ' ';
            }
        }

        // Salary Fixed
        if (!empty($personal_section['salaryfixed'])) {
            $resume_ai_string_main .= trim($personal_section['salaryfixed']) . ' ';
        }

        // Custom Fields (Section 1)
        if (!isset($this->_ai_resume_data_array['customfields'][3][1])) {
            $this->_ai_resume_data_array['customfields'][3][1] = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, 1);
        }
        $custom_fields = $this->_ai_resume_data_array['customfields'][3][1];

        $skip_types = ['file', 'email', 'textarea'];
        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if (!in_array($single_field->userfieldtype, $skip_types)) {
                if (!empty($personal_section[$single_field->field])) {
                    $resume_ai_string_main .= is_array($personal_section[$single_field->field])
                        ? implode(',', $personal_section[$single_field->field]) . ' '
                        : $personal_section[$single_field->field] . ' ';
                }
            } elseif ($single_field->userfieldtype == 'textarea' && !empty($personal_section[$single_field->field])) {
                $text_area_field_values .= $personal_section[$single_field->field] . ' ';
            }
        }

        $resume_ai_string_main = trim($resume_ai_string_main);
        $resume_ai_string_desc = $resume_ai_string_main . $text_area_field_values;

        // // Process other sections
        // foreach ($data as $section => $fields) {
        //     if (in_array($section, ['sec_1']) || !is_array($fields)) {
        //         continue;
        //     }

        //     $num_entries = count(reset($fields));
        //     for ($i = 0; $i < $num_entries; $i++) {
        //         if (!empty($fields['deletethis'][$i]) && $fields['deletethis'][$i] == 1) {
        //             continue;
        //         }
        //         $record_string = "";
        //         foreach ($fields as $key => $values) {
        //             if ($key === 'deletethis') continue;
        //             $value = $values[$i] ?? '';
        //             if ($key === 'address_city' && !empty($value)) {
        //                 if (!isset($this->_ai_resume_data_array['cities'][$value])) {
        //                     $this->_ai_resume_data_array['cities'][$value] = $this->getJSModel('city')->getLocationDataForView($value);
        //                 }
        //                 if ($this->_ai_resume_data_array['cities'][$value]) {
        //                     $resume_ai_string_main .= $this->_ai_resume_data_array['cities'][$value] . ' ';
        //                     $value = $this->_ai_resume_data_array['cities'][$value];
        //                 }
        //             }
        //             if (is_array($value)) {
        //                 $value = implode(",", array_filter($value));
        //             }
        //             if (!empty($value)) {
        //                 $record_string .= $value . " ";
        //             }
        //         }
        //         $resume_ai_string_desc .= $record_string . " ";
        //     }
        // }

        // Custom fields for other sections
        $sectionarray = array(
            (object) array('id' => 1, 'text' => Text::_('Personal Information')),
            (object) array('id' => 2, 'text' => Text::_('Addresses')),
            (object) array('id' => 3, 'text' => Text::_('Education')),
            (object) array('id' => 4, 'text' => Text::_('Employer')),
            (object) array('id' => 5, 'text' => Text::_('Skills')),
            (object) array('id' => 6, 'text' => Text::_('Resume')),
            (object) array('id' => 7, 'text' => Text::_('References')),
            (object) array('id' => 8, 'text' => Text::_('Languages'))
        );


        foreach ($sectionarray as $section) {
            switch ($section->id){
                case 2:
                    $fields = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
                    $section_data = $this->getAddressSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data['address_string'];
                break;
                case 3:
                    $fields = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
                    $section_data = $this->getEducationSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 4:
                    $fields = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
                    $section_data = $this->getEmployerSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 5:
                    $fields = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
                    $section_data = $this->getSkillSectionDataForAI($personal_section['id'],$fields,$personal_section);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 8:
                    $fields = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
                    $section_data = $this->getLanguageSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 7:
                    $fields = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
                    $section_data = $this->getReferenceSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;

                default:
                break;
            }


            if (!isset($this->_ai_resume_data_array['customfields'][3][$section->id])) {
                $this->_ai_resume_data_array['customfields'][3][$section->id] = $this->getJSSiteModel('customfields')->getUserfieldsfor(3, $section->id);
            }
            $custom_fields = $this->_ai_resume_data_array['customfields'][3][$section->id];

            $skip_types = ['file', 'email'];
            foreach ($custom_fields as $single_field) {
                if (!in_array($single_field->userfieldtype, $skip_types)) {
                    if (!empty($data[$single_field->field])) {
                        $resume_ai_string_desc .= is_array($data[$single_field->field])
                            ? implode(',', $data[$single_field->field]) . ' '
                            : $data[$single_field->field] . ' ';
                    }
                }
            }
        }

        // Update database
        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_resume` SET `airesumesearchtext` = " . $db->quote($resume_ai_string_main) . ", `airesumesearchdescription` = " . $db->quote($resume_ai_string_desc) . " WHERE id = " . (int)$personal_section['id'];
        $db->setQuery($query);
        if (!$db->execute()) {
            //die('Resume AI string store error');
        }
    }

    // resume sections
    function getAddressSectionDataForAI($resumeid, $fields) {
        if (!is_numeric($resumeid)) return false;

        $db = Factory::getDBO();
        $query = "SELECT address_city, address, address_zipcode, params, longitude, latitude
                 FROM #__js_job_resumeaddresses
                 WHERE resumeid = " . (int)$resumeid;
        $db->setQuery($query);
        $results = $db->loadObjectList();

        $return_data = array('address_string' => '', 'location_names' => '');
        $skip_types = ['file', 'email'];

        foreach ($results as $address) {
            // City with caching
            if (!empty($address->address_city)) {
                if (!isset($this->_city_cache[$address->address_city])) {
                    $this->_city_cache[$address->address_city] = $this->getJSModel('city')->getLocationDataForView($address->address_city);
                }
                if ($this->_city_cache[$address->address_city]) {
                    $return_data['address_string'] .= $this->_city_cache[$address->address_city] . ' ';
                    $return_data['location_names'] .= $this->_city_cache[$address->address_city] . ' ';
                }
            }

            // Direct fields
            $fields_to_add = ['address', 'longitude', 'address_zipcode', 'latitude'];
            foreach ($fields_to_add as $field) {
                if (!empty($address->$field)) {
                    $return_data['address_string'] .= $address->$field . ' ';
                }
            }

            // Custom fields from params
            if (!empty($address->params)) {
                $params = json_decode($address->params, true);
                foreach ($fields as $field) {
                    if (!in_array($field->userfieldtype, $skip_types) && !empty($params[$field->field])) {
                        $return_data['address_string'] .= $params[$field->field] . ' ';
                    }
                }
            }
        }

        return $return_data;
    }

    // Education Section
    function getEducationSectionDataForAI($resumeid, $fields) {
        if (!is_numeric($resumeid)) return false;

        $db = Factory::getDBO();
        $query = "SELECT institute, institute_certificate_name, params, institute_study_area,institute_address
                 FROM #__js_job_resumeinstitutes
                 WHERE resumeid = " . (int)$resumeid;
        $db->setQuery($query);
        $results = $db->loadObjectList();

        $output = '';
        $skip_types = ['file', 'email'];

        foreach ($results as $institute) {

            if (!empty($institute->institute_address)) {
                $location_string = $this->getJSModel('city')->getLocationDataForView($institute->institute_address);
                $output .= $location_string . ' ';
            }

            // Direct fields
            $fields_to_add = [
                'institute', 'institute_certificate_name', 'institute_study_area'
            ];
            foreach ($fields_to_add as $field) {
                if (!empty($institute->$field)) {
                    $output .= $institute->$field . ' ';
                }
            }

            // Custom fields
            if (!empty($institute->params)) {
                $params = json_decode($institute->params, true);
                foreach ($fields as $field) {
                    if (!in_array($field->userfieldtype, $skip_types) && !empty($params[$field->field])) {
                        $output .= $params[$field->field] . ' ';
                    }
                }
            }
        }

        return $output;
    }

    // Employer Section
    function getEmployerSectionDataForAI($resumeid, $fields) {
        if (!is_numeric($resumeid)) return false;

        $db = Factory::getDBO();
        $query = "SELECT employer, employer_position, employer_resp, employer_from_date, employer_leave_reason,
                         employer_to_date, employer_phone, employer_address, employer_city, params, employer_supervisor
                 FROM #__js_job_resumeemployers
                 WHERE resumeid = " . (int)$resumeid;
        $db->setQuery($query);
        $results = $db->loadObjectList();

        $output = '';
        $skip_types = ['file', 'email'];

        foreach ($results as $employer) {
            // Direct fields
            $fields_to_add = [
                'employer', 'employer_position', 'employer_resp', 'employer_from_date',
                'employer_leave_reason', 'employer_to_date', 'employer_phone', 'employer_supervisor'
            ];
            foreach ($fields_to_add as $field) {
                if (!empty($employer->$field)) {
                    $output .= $employer->$field . ' ';
                }
            }

            // City with caching
            if (!empty($employer->employer_city)) {
                $location_string = $this->getJSModel('city')->getLocationDataForView($employer->employer_city);
                $output .= $location_string . ' ';
            }

            // Custom fields
            if (!empty($employer->params)) {
                $params = json_decode($employer->params, true);
                foreach ($fields as $field) {
                    if (!in_array($field->userfieldtype, $skip_types) && !empty($params[$field->field])) {
                        $output .= $params[$field->field] . ' ';
                    }
                }
            }
        }

        return $output;
    }

    // Skill Section
    function getSkillSectionDataForAI($resumeid, $fields, $data) {
        if (!is_numeric($resumeid)) return false;

        $output = '';
        $skip_types = ['file', 'email'];

        if (!empty($data['skills'])) {
            $output .= $data['skills'] . ' ';
        }

        if (!empty($data['params'])) {
            $params = json_decode($data['params'], true);
            foreach ($fields as $field) {
                if (!in_array($field->userfieldtype, $skip_types) && !empty($params[$field->field])) {
                    $output .= $params[$field->field] . ' ';
                }
            }
        }

        return $output;
    }

    // Language Section
    function getLanguageSectionDataForAI($resumeid, $fields) {
        if (!is_numeric($resumeid)) return false;

        $db = Factory::getDBO();
        $query = "SELECT language, params, language_reading, language_writing, language_understanding, language_where_learned
                 FROM #__js_job_resumelanguages
                 WHERE resumeid = " . (int)$resumeid;
        $db->setQuery($query);
        $results = $db->loadObjectList();

        $output = '';
        $skip_types = ['file', 'email'];

        foreach ($results as $language) {
            // Direct fields
            $fields_to_add = [
                'language', 'language_reading', 'language_writing', 'language_understanding', 'language_where_learned'
            ];
            foreach ($fields_to_add as $field) {
                if (!empty($language->$field)) {
                    $output .= $language->$field . ' ';
                }
            }

            if (!empty($language->params)) {
                $params = json_decode($language->params, true);
                foreach ($fields as $field) {
                    if (!in_array($field->userfieldtype, $skip_types) && !empty($params[$field->field])) {
                        $output .= $params[$field->field] . ' ';
                    }
                }
            }
        }
        return $output;
    }


    // Language Section
    function getReferenceSectionDataForAI($resumeid, $fields) {
        if (!is_numeric($resumeid)) return false;

        $db = Factory::getDBO();
        $query = "SELECT reference, reference_city, reference_name, reference_years, reference_relation, reference_zipcode, reference_address, params
                 FROM #__js_job_resumereferences
                 WHERE resumeid = " . (int)$resumeid;
        $db->setQuery($query);
        $results = $db->loadObjectList();

        $output = '';
        $skip_types = ['file', 'email'];

        foreach ($results as $reference) {
            // Direct fields
            $fields_to_add = [
                'reference', 'reference_city', 'reference_name', 'reference_years', 'reference_relation', 'reference_zipcode', 'reference_address'
            ];
            foreach ($fields_to_add as $field) {
                if (!empty($reference->$field)) {
                    $output .= $reference->$field . ' ';
                }
            }

            if (!empty($reference->params)) {
                $params = json_decode($reference->params, true);
                foreach ($fields as $field) {
                    if (!in_array($field->userfieldtype, $skip_types) && !empty($params[$field->field])) {
                        $output .= $params[$field->field] . ' ';
                    }
                }
            }
        }
        return $output;
    }


    function importResumesAIData(){
            $db = Factory::getDBO();
            $query = "SELECT * FROM `#__js_job_resume` ORDER BY id DESC";
            $db->setQuery($query);
            $resumes = $db->loadObjectList();

            $this->_ai_resume_data_array = array(
                'categories' => array(),
                'jobtypes' => array(),
                'countries' => array(),
                'currency' => array(),
                'salaryrange' => array(),
                'salaryrangetype' => array(),
                'experience' => array(),
                'highesteducation' => array(),
                'cities' => array(),
                'customfields' => array()
            );

            foreach ($resumes as $resume) {
                $resume_array = json_decode(json_encode($resume), true);
                $this->importAIStringDataForResumes($resume_array);
            }
            return;
    }

}
?>
