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
use Joomla\CMS\Component\ComponentHelper;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelCommon extends JSModel {

    var $_siteurl = null;
    var $_stats = null;
    var $_jinput = null;

    function __construct() {
        parent::__construct();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
        $this->_jinput = Factory::getApplication()->input;
    }

    function b64ForEncode($string) {
        return base64_encode($string);
    }
    
    function b64ForDecode($string) {
        return base64_decode($string);
    }

    function setTheme($theme = '', $css = '') {
        $document = Factory::getDocument();
        $document->addStyleSheet('components/com_jsjobs/css/style.css' . $css, 'text/css');
        $document->addStyleSheet('components/com_jsjobs/css/style_color.php' . $css, 'text/css');
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    function getDocumentName($str) {
        $l = strrpos($str, ".");
        if (!$l) {
            return $str;
        } else {
            $name = substr($str, 0, $l);
            return $name;
        }
    }

    function checkPostMaxSize() {
        // Total length of post back data in bytes.
        $contentLength = (int) $_SERVER['CONTENT_LENGTH'];

        // Maximum allowed size of post back data in MB.
        $postMaxSize = (int) ini_get('post_max_size');

        // Maximum allowed size of script execution in MB.
        $memoryLimit = (int) ini_get('memory_limit');

        // Check for the total size of post back data.
        if (($postMaxSize > 0 && $contentLength > $postMaxSize * 1024 * 1024) || ($memoryLimit != -1 && $contentLength > $memoryLimit * 1024 * 1024)) {
            return false;
        } else {
            return true;
        }
    }

    function mpGetstats($employer, $jobseeker, $jobs, $companies, $activejobs, $resumes) {
        if (!$this->_stats) {
            $db = Factory::getDBO();
            $result = array();
            $curdate = date('Y-m-d');
            if ($employer) {
                $query = "SELECT count(userrole.id) AS totalemployer
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 1";
                $db->setQuery($query);
                $employer = $db->loadResult();
                $result['employer'] = $employer;
            }
            if ($jobseeker) {
                $query = "SELECT count(userrole.id) AS totaljobseeker
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 2";
                $db->setQuery($query);
                $jobseeker = $db->loadResult();
                $result['jobseeker'] = $jobseeker;
            }
            if ($jobs) {
                $query = "SELECT count(job.id) AS totaljobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 ";
                $db->setQuery($query);
                $totaljobs = $db->loadResult();
                $result['totaljobs'] = $totaljobs;
            }
            if ($companies) {
                $query = "SELECT count(company.id) AS totalcomapnies
                    FROM `#__js_job_companies` AS company
                    WHERE company.status = 1 ";
                $db->setQuery($query);
                $totalcompanies = $db->loadResult();
                $result['totalcompanies'] = $totalcompanies;
            }
            if ($activejobs) {
                $query = "SELECT count(job.id) AS totalactivejobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $tatalactivejobs = $db->loadResult();
                $result['tatalactivejobs'] = $tatalactivejobs;
            }
            if ($resumes) {
                $query = "SELECT count(resume.id) AS totalresume
                    FROM `#__js_job_resume` AS resume
                    WHERE resume.status = 1 ";
                $db->setQuery($query);
                $totalresume = $db->loadResult();
                $result['totalresume'] = $totalresume;
            }
            if ($employer) {
                $query = "SELECT count(userrole.id) AS todayemployer
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 1 AND DATE(userrole.dated) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todyemployer = $db->loadResult();
                $result['todyemployer'] = $todyemployer;
            }
            if ($jobseeker) {
                $query = "SELECT count(userrole.id) AS todayjobseeker
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 2 AND DATE(userrole.dated) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todyjobseeker = $db->loadResult();
                $result['todyjobseeker'] = $todyjobseeker;
            }
            if ($jobs) {
                $query = "SELECT count(job.id) AS todayjobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todayjobs = $db->loadResult();
                $result['todayjobs'] = $todayjobs;
            }
            if ($companies) {
                $query = "SELECT count(company.id) AS todaycomapnies
                    FROM `#__js_job_companies` AS company
                    WHERE company.status = 1 AND DATE(company.created) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todaycompanies = $db->loadResult();
                $result['todaycompanies'] = $todaycompanies;
            }
            if ($activejobs) {
                $query = "SELECT count(job.id) AS todayactivejobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todayactivejobs = $db->loadResult();
                $result['todayactivejobs'] = $todayactivejobs;
            }
            if ($resumes) {
                $query = "SELECT count(resume.id) AS todayresume
                    FROM `#__js_job_resume` AS resume
                    WHERE resume.status = 1 AND DATE(resume.created) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todayresume = $db->loadResult();
                $result['todayresume'] = $todayresume;
            }


            $this->_stats = $result;
        }
        return $this->_stats;
    }

    function removeSpecialCharacter($string) {
        if (!class_exists('getJSJobsPHPFunctionsClass')) { // fix js jobs register plugin selct role field
            require_once (JPATH_ADMINISTRATOR.'/components/com_jsjobs/include/classes/jsjobsphpfunctions.php');
            $obj = new jsjobsphpfunctions();
            $string = $obj->jsjobs_strip_tags($string, "");
        }else{
            $string = getJSJobsPHPFunctionsClass()->jsjobs_strip_tags($string, "");
        }
        $string = preg_replace("/[@!*%^(){}?&$\\\\#\\/]+/", "", $string);        
        $string = preg_replace("/[@!*&$\\\\#\\/]+/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    function getDefaultValue($table) {
        $db = Factory::getDBO();

        switch ($table) {
            case "categories":
            case "jobtypes":
            case "jobstatus":
            case "shifts":
            case "heighesteducation":
            case "ages":
            case "careerlevels":
            case "experiences":
            case "salaryrange":
            case "salaryrangetypes":
            case "subcategories":
            case "experiences":
                $query = "SELECT id FROM `#__js_job_" . $table . "` WHERE isdefault=1";
                $db->setQuery($query);
                $default_id = $db->loadResult();
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `#__js_job_" . $table . "`";
                    $db->setQuery($query);
                    $min_id = $db->loadResult();
                    return $min_id;
                }
            case "currencies":
                $query = "SELECT id FROM `#__js_job_" . $table . "` WHERE `default`=1";
                $db->setQuery($query);
                $default_id = $db->loadResult();
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `#__js_job_" . $table . "`";
                    $db->setQuery($query);
                    $min_id = $db->loadResult();
                    return $min_id;
                }
                break;
        }
    }

    function getServerSerialNumber() {
        $db = Factory::getDbo();
        $ip = $_SERVER['SERVER_ADDR'];
        $siteurl = $this->_siteurl;
        $serial_number = "";
        $versioncode = "";
        $query = "SELECT * FROM `#__js_job_config` WHERE configname='server_serial_number' OR configname='versioncode'  ";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        foreach ($data AS $d) {
            if ($d->configname == 'server_serial_number')
                $serial_number = $d->configvalue;
            if ($d->configname == 'versioncode')
                $versioncode = $d->configvalue;
        }
        $data = array('ip' => $ip, 'siteurl' => $siteurl, 'server_serialnumber' => $serial_number, 'versioncode' => $versioncode);
        $jsondata = json_encode($data);
        return $jsondata;
    }

    function getServerid($table, $id) {
        if ($id)
            if (is_numeric($id) === false)
                return false;
        $db = Factory::getDBO();
        switch ($table) {
            case "salaryrangetypes";
            case "careerlevels";
            case "experiences";
            case "ages";
            case "currencies";
            case "subcategories";
            case "companies";
                $query = "SELECT serverid FROM `#__js_job_" . $table . "` WHERE status=1 AND id=" . $id;
                break;
            case "salaryrange";
                $query = "SELECT serverid FROM `#__js_job_" . $table . "` WHERE id=" . $id;
                break;
            case "countries";
            case "states";
            case "cities";
                $query = "SELECT serverid FROM `#__js_job_" . $table . "` WHERE enabled=1 AND id=" . $id;
                break;
            default:
                $query = "SELECT serverid FROM `#__js_job_" . $table . "` WHERE isactive=1 AND id=" . $id;
                break;
        }

        $db->setQuery($query);
        $server_id = $db->loadResult();
        if(!$server_id)
            $server_id = $id;
        return $server_id;
    }

    function getClientId($table, $id) {
        if ($id)
            if (is_numeric($id) === false)
                return false;
        $db = Factory::getDBO();
        switch ($table) {
            case "salaryrangetypes";
            case "careerlevels";
            case "experiences";
            case "ages";
            case "currencies";
            case "subcategories";
                $query = "SELECT id FROM `#__js_job_" . $table . "` WHERE status=1 AND serverid=" . $id;
                break;
            case "salaryrange";
                $query = "SELECT id FROM `#__js_job_" . $table . "` WHERE serverid=" . $id;
                break;
            case "countries";
            case "states";
            case "cities";
                $query = "SELECT id FROM `#__js_job_" . $table . "` WHERE enabled=1 AND serverid=" . $id;
                break;
            default:
                $query = "SELECT id FROM `#__js_job_" . $table . "` WHERE isactive=1 AND serverid=" . $id;
                break;
        }

        $db->setQuery($query);
        $server_id = $db->loadResult();
        return $server_id;
    }

    function getClientAuthenticationKey() {
        if (Factory::getApplication()->isClient('administrator')) {
            $job_sharing_config = $this->getJSModel('configuration')->getConfigByFor('jobsharing');
        }else{
            $job_sharing_config = $this->getJSModel('configurations')->getConfigByFor('jobsharing');
        }
        $client_auth_key = $job_sharing_config['authentication_client_key'];
        return $client_auth_key;
    }

    function checkAssignGroup($uid, $groupval) {
        if (is_numeric($uid) === false)
            return false;
        if (is_numeric($groupval) === false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT  COUNT(user_id) FROM `#__user_usergroup_map` WHERE user_id=" . $uid . " AND group_id=" . $groupval;
        $db->setQuery($query);
        $alreadyassign = $db->loadResult();
        if ($alreadyassign > 0)
            return true;
        else
            return false;
    }

    function getRequiredTravel($title) {
        $requiredtravel = array();
        if ($title)
            $requiredtravel[] = array('value' => '', 'text' => $title);
        $requiredtravel[] = array('value' => 1, 'text' => Text::_('Not Required'));
        $requiredtravel[] = array('value' => 2, 'text' => Text::_('25 %'));
        $requiredtravel[] = array('value' => 3, 'text' => Text::_('50 %'));
        $requiredtravel[] = array('value' => 4, 'text' => Text::_('75 %'));
        $requiredtravel[] = array('value' => 5, 'text' => Text::_('100 %'));
        return $requiredtravel;
    }

    function getGender($title) {
        $gender = array();
        if ($title)
            $gender[] = array('value' => '', 'text' => $title);
        $gender[] = array('value' => 1, 'text' => Text::_('Male'));
        $gender[] = array('value' => 2, 'text' => Text::_('Female'));
        return $gender;
    }

    public function spamCheckRandom() {
        $pw = '';
// first character has to be a letter
        $characters = range('a', 'z');
        $pw .= $characters[mt_rand(0, 25)];
// other characters arbitrarily
        $numbers = range(0, 9);
        $characters = array_merge($characters, $numbers);
        $pw_length = mt_rand(4, 12);
        for ($i = 0; $i < $pw_length; $i++) {
            $pw .= $characters[mt_rand(0, 35)];
        }
        return $pw;
    }

    function performChecks() {
        //$request = JRequest::get();
        $session = Factory::getApplication()->getSession();
        $type_calc = true;
        if ($type_calc) {
            if ($session->get('jsjobs_rot13', null, 'jsjobs_checkspamcalc') == 1) {
                $spamcheckresult = $this->b64ForDecode(str_rot13($session->get('jsjobs_spamcheckresult', null, 'jsjobs_checkspamcalc')));
            } else {
                $spamcheckresult = $this->b64ForDecode($session->get('jsjobs_spamcheckresult', null, 'jsjobs_checkspamcalc'));
            }
            //$spamcheck = JRequest::getInt($session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc'), '', 'post');
            $spamcheck = Factory::getApplication()->input->get($session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc'), '', 'post');
            $session->clear('jsjobs_rot13', 'jsjobs_checkspamcalc');
            $session->clear('jsjobs_spamcheckid', 'jsjobs_checkspamcalc');
            $session->clear('jsjobs_spamcheckresult', 'jsjobs_checkspamcalc');

            if (!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck) {
                return false; // Failed
            }
        }
// Hidden field
        $type_hidden = 0;
        if ($type_hidden) {
            $hidden_field = $session->get('hidden_field', null, 'checkspamcalc');
            $session->clear('hidden_field', 'checkspamcalc');

            if (Factory::getApplication()->input->get($hidden_field, '', 'post')) {
                return false; // Hidden field was filled out - failed
            }
        }
// Time lock
        $type_time = 0;
        if ($type_time) {
            $time = $session->get('time', null, 'checkspamcalc');
            $session->clear('time', 'checkspamcalc');

            if (time() - $this->params->get('type_time_sec') <= $time) {
                return false; // Submitted too fast - failed
            }
        }
// Own Question
// Conversion to lower case
        $session->clear('ip', 'jsjobs_checkspamcalc');
        $session->clear('saved_data', 'jsjobs_checkspamcalc');

        return true;
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fclose($ourFileHandle);
        }
    }

    function checkUserDetail($val, $for) {
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__users` WHERE ".$db->Quote($for)." =" . $db->quote($val);
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return true;
        else
            return false;
    }

    function parseId($value) {
        $ids = explode("-", $value);
        //$count = count($ids);
        // $id = (int) $ids[($count - 1)];
        $id = (int) end($ids); // changed by muhiaudin in case of any digit detected in rd alongwith a '-'
        return $id;
    }

    function checkImageFileExtensions($file_name, $file_tmp, $image_extension_allow) {
        $image_extension_allow = strtolower($image_extension_allow);
        $image_extension_allow = str_replace(' ', '', $image_extension_allow);
        $allow_image_extension = explode(',', $image_extension_allow);
        if ($file_name != '' AND $file_tmp != "") {
            $ext = $this->getJSModel('common')->getExtension($file_name);
            $ext = strtolower($ext);
            if (in_array($ext, $allow_image_extension)){
                /*
                $imagetypes = array(
                    'ani','bmp','cal','fax','gif','img','jbg','jpe','jpeg','jpg','mac','pbm','pcd','pcx','pct','pgm','png','ppm','psd','ras','tga','tiff','wmf','tif'
                );        
                if(in_array($ext,$imagetypes)){
                    if(file_exists($file_tmp)){
                        $mimetype = mime_content_type($file_tmp);
                        $flag = false;
                        foreach($imagetypes AS $type){
                            if($mimetype == "image/$type"){
                                $flag = true;
                            }
                        }
                        if($flag == true){
                            return 1;
                        }else{
                            return 6;
                        }
                    }else
                        return 1;
                }
                */
                return 1;
            }else{
                return 6; //file type mistmathc
            }
        }
    }

    function checkDocumentFileExtensions($file_name, $file_tmp, $document_extension_allow) {
        $document_extension_allow = strtolower($document_extension_allow);
        $document_extension_allow = str_replace(' ', '', $document_extension_allow);
        
        $allow_document_extension = explode(',', strtolower($document_extension_allow));
        if ($file_name != '' AND $file_tmp != "") {
            $ext = $this->getJSModel('common')->getExtension($file_name);
            $ext = strtolower($ext);
            if (in_array($ext, $allow_document_extension)){
                $imagetypes = array(
                    'ani','bmp','cal','fax','gif','img','jbg','jpe','jpeg','jpg','mac','pbm','pcd','pcx','pct','pgm','png','ppm','psd','ras','tga','tiff','wmf','tif'
                );        
                if(in_array($ext,$imagetypes)){
                    if(file_exists($file_tmp)){
                        $mimetype = mime_content_type($file_tmp);
                        $flag = false;
                        foreach($imagetypes AS $type){
                            if($mimetype == "image/$type"){
                                $flag = true;
                            }
                        }
                        if($flag == true){
                            return 1;
                        }else{
                            return 6;
                        }
                    }else{
                        return 6;
                    }
                }
                return 1;
            }else{
                return 6; //file type mistmathc
            }
        }
    }

    function getMiniMax($title) {
        $minimax = array();
        if ($title)
            $minimax[] = array('value' => Text::_(''), 'text' => $title);
        $minimax[] = array('value' => 1, 'text' => Text::_('Minimum'));
        $minimax[] = array('value' => 2, 'text' => Text::_('Maximum'));

        return $minimax;
    }

    function getCaptchaForForm() {
        $session = Factory::getApplication()->getSession();
        $rand = $this->spamCheckRandom();
        $session->set('jsjobs_spamcheckid', $rand, 'jsjobs_checkspamcalc');
        $session->set('jsjobs_rot13', mt_rand(0, 1), 'jsjobs_checkspamcalc');
        // Determine operator
        $operator = 2;
        if ($operator == 2) {
            $tcalc = mt_rand(1, 2);
        }
        $max_value = 20;
        $negativ = 0;

        $operend_1 = mt_rand(1, $max_value);
        $operend_2 = mt_rand(1, $max_value);
        $operand = 2;
        if ($operand == 3) {
            $operend_3 = mt_rand(0, $max_value);
        }

        if ($tcalc == 1) { // Addition
            if ($session->get('jsjobs_rot13', null, 'jsjobs_checkspamcalc') == 1) { // ROT13 coding
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 + $operend_2)), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 + $operend_2 + $operend_3)), 'jsjobs_checkspamcalc');
                }
            } else {
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 + $operend_2), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 + $operend_2 + $operend_3), 'jsjobs_checkspamcalc');
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($session->get('jsjobs_rot13', null, 'jsjobs_checkspamcalc') == 1) {
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 - $operend_2)), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 - $operend_2 - $operend_3)), 'jsjobs_checkspamcalc');
                }
            } else {
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 - $operend_2), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 - $operend_2 - $operend_3), 'jsjobs_checkspamcalc');
                }
            }
        }
        $add_string = "";
        $add_string .= '<div class="jsjobs-captcha-wrp"><label for="' . $session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc') . '">';

        $add_string .= Text::_('Spam Check') . ': ';

        if ($tcalc == 1) {
            $converttostring = 0;
            if ($converttostring == 1) {
                if ($operand == 2) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . Text::_('Plus') . ' ' . $this->converttostring($operend_2) . ' ' . Text::_('Equals') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . Text::_('Plus') . ' ' . $this->converttostring($operend_2) . ' ' . Text::_('Plus') . ' ' . $this->converttostring($operend_3) . ' ' . Text::_('Equals') . ' ';
                }
            } else {
                if ($operand == 2) {
                    $add_string .= $operend_1 . ' ' . Text::_('Plus') . ' ' . $operend_2 . ' ' . Text::_('Equals') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $operend_1 . ' ' . Text::_('Plus') . ' ' . $operend_2 . ' ' . Text::_('Plus') . ' ' . $operend_3 . ' ' . Text::_('Equals') . ' ';
                }
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($converttostring == 1) {
                if ($operand == 2) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . Text::_('Minus') . ' ' . $this->converttostring($operend_2) . ' ' . Text::_('Equals') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . Text::_('Minus') . ' ' . $this->converttostring($operend_2) . ' ' . Text::_('Minus') . ' ' . $this->converttostring($operend_3) . ' ' . Text::_('Equals') . ' ';
                }
            } else {
                if ($operand == 2) {
                    $add_string .= $operend_1 . ' ' . Text::_('Minus') . ' ' . $operend_2 . ' ' . Text::_('Equals') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $operend_1 . ' ' . Text::_('Minus') . ' ' . $operend_2 . ' ' . Text::_('Minus') . ' ' . $operend_3 . ' ' . Text::_('Equals') . ' ';
                }
            }
        }

        $add_string .= '</label>';
        $add_string .= '<input type="text" name="' . $session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc') . '" id="' . $session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc') . '" size="3" class="inputbox ' . $rand . ' validate-numeric required" value="" required="required" />';
        $add_string .= '</div>';

        return $add_string;
    }
    
    function getSalaryRangeView($currencysymbol,$salaryrangestart,$salaryrangeend,$salarytype,$currency_align){
        $salary = '';
        if($currency_align == 1){ // Left align
            $salary = $currencysymbol . ' ' . $salaryrangestart . ' - ' . $salaryrangeend . ' ' . Text::_($salarytype);
        }elseif($currency_align == 2){ // Right align
            $salary = $salaryrangestart . ' - ' . $salaryrangeend . ' ' . $currencysymbol . ' ' . Text::_($salarytype);
        }
        return $salary;
    }

    function getLocationForView($cityname,$statename,$countryname){
        $config = $this->getJSModel('configurations')->getConfigByFor('default');
        $location = Text::_($cityname);
        switch($config['defaultaddressdisplaytype']){
            case 'csc':
                if($statename) $location .= ', '. Text::_($statename);
                if($countryname) $location .= ', '. Text::_($countryname);
            break;
            case 'cs':
                if($statename) $location .= ', '. Text::_($statename);
            break;
            case 'cc':
                if($countryname) $location .= ', '. Text::_($countryname);
            break;            
        }
        return $location;
    }    

    function getHtmlInput($htmlText){
        $app = Factory::getApplication();
        //$text = ComponentHelper::filterText($app->input->get($htmlText, '', 'raw'));
        //$text = Factory::getApplication()->input->get($htmlText, '', 'post', 'string', JREQUEST_ALLOWHTML);
        
        $text = Factory::getApplication()->input->get($htmlText, '', 'raw');
        return $text;    
    }

    function islistjobforvisitor() {
        $user = Factory::getUser();
        if($user->guest){
            return 1;
        }else{
            return 0;
        }
    }

    // new
    function uploadOrDeleteFileCustom($object_id, $field, $isdeletefile , $for , $resumeid = false , $sectionid = false, $i = false, $res_sec = false){
        $id = $object_id;
        switch ($for) {
            case '1':
                $datafor = 'company';
                $table_name = 'company';
                break;
            case '2':
                $datafor = 'job';
                $table_name = 'job';
                break;
            case '3':
                $datafor = 'resume';
                $table_name = 'resume';
                if($res_sec){
                    if ($res_sec == "address") {
                        $table_name = 'resume' . $res_sec . 'es';
                    } elseif ($res_sec == "skills" || $res_sec == "editor") {
                        $table_name = 'resume';
                    } else {
                        $table_name = 'resume' . $res_sec . 's';
                    }
                }
                break;
        }
        if( ! isset($datafor))
            return;
        $db = Factory::getDBO();

        $row = $this->getTable($table_name);

        $base = JPATH_BASE;
        if (!isset($this->_config))
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        $image_file_types = '';
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
            if ($conf->configname == 'image_file_type'){
                if($image_file_types)
                    $image_file_types .= ',';
                $image_file_types .= $conf->configvalue;
            }
            if ($conf->configname == 'document_file_type'){
                if($image_file_types)
                    $image_file_types .= ',';
                $image_file_types .= $conf->configvalue;
            }
            if ($conf->configname == 'document_file_size')
                $maxFileSize = $conf->configvalue;

        }
        $path = $base . '/' . $datadirectory;
        if (!file_exists($path)) { // create user directory
            $this->makeDir($path);
        }
        $isupload = false;
        $path = $path . '/data';
        if (!file_exists($path)) { // create user directory
            $this->makeDir($path);
        }
        if($for == 3 )
            $path = $path . '/jobseeker';
        else
            $path = $path . '/employer';

        if (!file_exists($path)) { // create user directory
            $this->makeDir($path);
        }
        // if resume uploads
        if($for == 3){
            if($sectionid == 1){
                $section_id = 'sec_'.$sectionid;
                $file = $this->_jinput->files->get($section_id);
                $file_size = isset($file[$field]['size']) ? $file[$field]['size'] : 0;
            }else{
                $section_id = 'sec_'.$sectionid;
                $file = $this->_jinput->files->get($section_id);
                $file_size = isset($file[$field][$i]['size']) ? $file[$field][$i]['size'] : 0;
            }
        }else{
            $file = $this->_jinput->files->get($field);
            $file_size = $file['size'];
        }
        $isupload = false;
        if ($file_size > 0 AND $isdeletefile == 0) {
            if($for == 3){
                if($sectionid == 1){
                    $section_id = 'sec_'.$sectionid;
                    $file_name = isset($file) ? $file[$field]['name'] : '';
                    $file_tmp = isset($file) ? $file[$field]['tmp_name'] : '';
                }else{
                    $section_id = 'sec_'.$sectionid;
                    $file_name = isset($file) ? $file[$field][$i]['name'] : '';
                    $file_tmp = isset($file) ? $file[$field][$i]['tmp_name'] : '';
                }
            }else{
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
            }

            if ($file_name != '' AND $file_tmp != "") {

                $check_image_extension = $this->checkImageFileExtensions($file_name, $file_tmp, $image_file_types);

                $file_name = $field.'_'.$file_name;

                if ($check_image_extension == 6 || $file_size > ($maxFileSize * 1024) ) {
                    $row->load($id);
                    $params_before = $row->params;
                    $params_before = json_decode($params_before , TRUE);
                    $params_before[$field] = '';
                    $row->params = json_encode($params_before);
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                    return '';
                }else{
                    $row->load($id);
                    $params_before = $row->params;
                    $params_before = json_decode($params_before , TRUE);
                    $old_file_name =  $params_before[$field];
                    $params_before[$field] = $file_name;
                    $row->params = json_encode($params_before);
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
                if($for == 3){
                    $userpath = $path . '/'.$datafor.'_' . $resumeid;
                }else{
                    $userpath = $path . '/'.$datafor.'_' . $id;
                }
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $userpath = $userpath . '/customfiles';
                if (!file_exists($userpath)) { // create logo directory
                    $this->makeDir($userpath);
                }
                $isupload = true;
            }
        }
        if ($isupload) {
            // Delete previous uploaded file
            $mask = $userpath.'/'.$old_file_name;
            array_map('unlink', glob($mask));
            // upload new
            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
            return 1;
        } else { // DELETE FILES
            if ($isdeletefile == 1) {
                if($for == 3){
                    $userpath = $path . '/'.$datafor.'_' . $resumeid . '/customfiles/';
                }else{
                    $userpath = $path . '/'.$datafor.'_' . $id . '/customfiles/';
                }
                $file = $userpath.$field;
                unlink($file);
            }
            return 1;
        }
    }

    function getCompanyLogo($id , $filename , $config = null , $for = null){
        
        $mdl = $this->getJSModel('configurations');
        $config['companydefaultlogopath'] = $mdl->getConfigValue('companydefaultlogopath');
        $config['data_directory'] = $mdl->getConfigValue('data_directory');            
    
        //for jobi template
        if(Factory::getApplication()->getTemplate() == 'jobi'){
            if( $filename == "" ){
                $filepath = $config['companydefaultlogopath'];
                if($filepath != ''){
                    $path = Uri::root().$filepath;
                }else{
                    $path = Uri::root().'/templates/jobi/images/default-company.png';
                }
            }else{
                $path = Uri::root().$config['data_directory']."/data/employer/comp_".$id."/logo/".$filename;
            }
        }
        else {
            if( $filename == "" ){
                $filepath = $config['companydefaultlogopath'];
                if($filepath != ''){
                    $path = Uri::root().$filepath;
                }else{
                    if($for == 2){
                        $path = Uri::root().'components/com_jsjobs/images/defaultlogo.png';
                    }else{
                        $path = Uri::root().'components/com_jsjobs/images/blank_logo.png';
                    }
                }
            }else{
                $path = Uri::root().$config['data_directory']."/data/employer/comp_".$id."/logo/".$filename;
            }
        }
        return $path;
    }

    function getUploadedCustomFile($id, $file_name, $for ){
        
        switch ($for) {
            case '1':
                $datafor = 'company';
                break;
            case '2':
                $datafor = 'job';
                break;
            case '3':
                $datafor = 'resume';
                break;
        }
        if( ! isset($datafor))
            return;

        $base = Uri::root();
        if (!isset($this->_config))
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
        }
        $path = $base . $datadirectory;
        $path = $path . '/data';
        if($for == 3 ){
            $path = $path . '/jobseeker';
        }else{
            $path = $path . '/employer';
        }
        $userpath = $path . '/'.$datafor.'_' . $id;
        $finalpath = $userpath . '/customfiles/'.$file_name;

        return $finalpath;
    }   

    function isPluginEnabled($xml){
        $db = Factory::getDbo();
        if( preg_match('/.+'.$this->b64ForDecode("YW05dGMyOWphV0Zz").'.+/', $xml) ){
            if(!ComponentHelper::isEnabled('com_community'))  return false;
            if(!JPluginHelper::isEnabled('community','jsjobs')) return false;
            if(!$this->getJSModel('configurations')->getConfigValue('isenabled_jomsocial')) return false;
            return $this->getJSModel(VIRTUEMARTJSJOB)->prepareJomSocial();
        }
    }
}
?>
