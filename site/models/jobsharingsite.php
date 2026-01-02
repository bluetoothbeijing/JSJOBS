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

require_once(JPATH_SITE . '/administrator/components/com_jsjobs/models/jobsharing.php');

$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelJobSharingSite extends JSModel {

    private $skey = "_EI_XRV_!*%@&*/+-~~";
    var $filePath;
    var $uploadURL;
    var $formFileVariableName;
    var $_uid = null;
    var $_siteurl = null;
    var $_client_auth_key = null;
    var $postParams = array();

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function serverTask($jsondata, $fortask) {
        $logarray = array();

        $k = 1;
        $encoded_server_json_data_array = $this->encode($jsondata);
        $sitepath = 'https://jobs.joomsky.com/';
        switch ($fortask) {
            case "myjobs":
                $url = $sitepath . 'index.php?r=Jobs/getmyjobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "listjobs";
                $url = $sitepath . 'index.php?r=Jobs/getListNewestJobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "viewjobbyid";
                $url = $sitepath . 'index.php?r=Jobs/getJobbyid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobapplybyidforjobapply";
                $url = $sitepath . 'index.php?r=Jobs/getjobapplybyidforjobapply';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "listjobsbycategory";
                $url = $sitepath . 'index.php?r=JosJsJobCategories/listjobsbycategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "myappliedjobs";
                $url = $sitepath . 'index.php?r=Jobapply/getmyappliedjobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobappliedresume";
                $url = $sitepath . 'index.php?r=Jobapply/getjobappliedresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);                
                break;
            case "alljobsappliedapplications";
                $url = $sitepath . 'index.php?r=Jobs/alljobsappliedapplications';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getResumeCommentsAJAX";
                $url = $sitepath . 'index.php?r=Jobapply/getresumecommentsajax';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmyfolders";
                $url = $sitepath . 'index.php?r=Folders/getmyfolders';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getfolderdetail";
                $url = $sitepath . 'index.php?r=Folders/getfolderdetail';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getfolderbyidforform";
                $url = $sitepath . 'index.php?r=Folders/getfolderbyidforform';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumedetail";
                $url = $sitepath . 'index.php?r=Resume/getresumedetail';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "setexportresume";
                $url = $sitepath . 'index.php?r=Resume/setexportresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "setexportallresume";
                $url = $sitepath . 'index.php?r=Resume/setexportallresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumeviewbyid";
                $url = $sitepath . 'index.php?r=Resume/getresumeviewbyid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getallresumefiles";// the below manipulation is b/c we download files form the sharing server
                $url = $sitepath . 'index.php?r=Resume/getallresumefiles'.$jsondata;
                Factory::getApplication()->redirect($url);
                break;
            case "getmessagesbyjobresume";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjobresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmessagesbyjobsforjobseeker";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjobsforjobseeker';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmessagesbyjobs";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmessagesbyjob";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjob';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getfolderresumebyfolderid";
                $url = $sitepath . 'index.php?r=Folderresumes/getfolderresumebyfolderid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobsearch";
                $url = $sitepath . 'index.php?r=Jobs/getjobsearch';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "jobscategoryofsubcategories"; // for count all subcategory in this category
                $url = $sitepath . 'index.php?r=JosJsJobSubcategories/jobscategoryofsubcategories';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobsbycategory";
                $url = $sitepath . 'index.php?r=Jobs/getjobsbycategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobsbysubcategory";
                $url = $sitepath . 'index.php?r=Jobs/getjobsbysubcategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumebycategory";
                $url = $sitepath . 'index.php?r=JosJsJobCategories/getresumebycategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumebycategoryid";
                $url = $sitepath . 'index.php?r=Resume/getresumebycategoryid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumebysubcategorycount";
                $url = $sitepath . 'index.php?r=JosJsJobSubcategories/getresumebysubcategorycount';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getcompanybyid";
                $url = $sitepath . 'index.php?r=Companies/getcompanybyid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getactivejobsbycompany";
                $url = $sitepath . 'index.php?r=Jobs/getactivejobsbycompany';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "insertnewestjobsfromserver";
                $url = $sitepath . 'index.php?r=Jobs/getjobsforinsert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "sendjobalert";
                $url = $sitepath . 'index.php?r=Jobs/sendjobalert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobbytypes";
                $url = $sitepath . 'index.php?r=JosJsJobJobtypes/getjobstypes';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            default:
                break;
        }
        $server_return_value = json_decode($return_value, true);
        $exp_return_value = explode('/', $return_value);
        $eventtype = $exp_return_value[0];
        $table_name = "";
        if ($eventtype == "Curl error") {
            $logarray['uid'] = $this->_uid;
            $logarray['referenceid'] = "";
            $logarray['eventtype'] = "Curl not Responce";
            $logarray['message'] = $return_value;
            $logarray['event'] = $fortask;
            $logarray['messagetype'] = "Error";
            $logarray['datetime'] = date('Y-m-d H:i:s');
            $serverjobstatus = "Curl Not Responce";
            $serverid = 0;
            $this->write_JobSharingLog($logarray);
            if ($table_name != "") {
                $this->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], $table_name);
            }
            return true; // because not tell the user about what error occured 
        }
        return $server_return_value;
    }

    function unsubscribe_JobAlert($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->unsubscribeJobAlert($data);
        return $return_value;
    }

    function store_JobAlertSharing($data_jobalert) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeJobAlertSharing($data_jobalert);
        return $return_value;
    }

    function store_FolderSharing($data_folder) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeFolderSharing($data_folder);
        return $return_value;
    }

    function store_MessageSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeMessageSharing($data);
        return $return_value;
    }

    function store_ResumeCommentsSharing($comments_data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeCommentsSharing($comments_data);
        return $return_value;
    }

    function store_ResumeSectionSharing($section_data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeSectionSharing($section_data);
        return $return_value;
    }

    function store_ResumeFolderSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeFolderSharing($data);
        return $return_value;
    }

    function store_ShortlistcandidatesSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeShortlistcandidatesSharing($data);
        return $return_value;
    }

    function store_ResumeRatingSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeRatingSharing($data);
        return $return_value;
    }

    function store_CoverLetterSharing($data_cvletter) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeCoverLetterSharing($data_cvletter);
        return $return_value;
    }

    function store_JobapplySharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeJobapplySharing($data);
        return $return_value;
    }

    function update_JobApplyActionStatus($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->updateJobApplyActionsStatus($data);
        return $return_value;
    }

    function store_ResumeFileSharing($data_resume, $resume_file) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeFileSharing($data_resume, $resume_file);
        return $return_value;
    }

    function store_ResumePicSharing($data_resume, $resume_picture) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumePicSharing($data_resume, $resume_picture);
        return $return_value;
    }

    function store_ResumeSharing($data_resume) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeSharing($data_resume);
        return $return_value;
    }

    function store_DepartmentSharing($data_department) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeDepartmentSharing($data_department);
        return $return_value;
    }

    function store_CompanyLogoSharing($data_company, $company_logo) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeCompanyLogoSharing($data_company, $company_logo);
        return $return_value;
    }

    function store_CompanySharing($data_company) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeCompanySharing($data_company);
        return $return_value;
    }

    function store_JobSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeJobSharing($data);
        return $return_value;
    }

    function store_GoldFeaturedJobSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeGoldFeaturedJobSharing($data);
        return $return_value;
    }

    function delete_CompanySharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteCompanySharing($data);
        return $return_value;
    }

    function delete_JobSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteJobSharing($data);
        return $return_value;
    }

    function delete_DepartmentSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteDepartmentSharing($data);
        return $return_value;
    }

    function delete_FolderSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteFolderSharing($data);
        return $return_value;
    }

    function delete_ResumeSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteResumeSharing($data);
        return $return_value;
    }

    function delete_CoverletterSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteCoverletterSharing($data);
        return $return_value;
    }

    function write_JobSharingLog($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->writeJobSharingLog($data);
        return $return_value;
    }

    function Update_ServerStatus($serverstatus, $client_id, $server_id, $uid, $table) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->UpdateServerStatus($serverstatus, $client_id, $server_id, $uid, $table);
        return $return_value;
    }

    function update_MultiCityServerid($serverids, $table) {

        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->updateMultiCityServerid($serverids, $table);
        return $return_value;
    }

    function performTaskJobSharingService($jsondata, $url) {
    }

    public function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function encode($value) {
        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        $trim = trim($this->safe_b64encode($crypttext));
        return trim($this->safe_b64encode($crypttext));
    }

    function CurlFileUploader($filePath, $uploadURL, $formFileVariableName, /* assosiative array */ $otherParams = false) {
    }

    function curlUploadFile() {
    }

    function getSeverDefaultCountryid($serverdefaultcity) {
    }

    function getSeverCountryid($city_filter) {
    }

    function getServerid($table, $id) {
        if(!is_numeric($id)) return false;
        if(empty($id)) return false;
        if($id == '') return false;

        $db = Factory::getDBO();
        switch ($table) {
            case "salaryrangetypes";
            case "careerlevels";
            case "experiences";
            case "ages";
            case "currencies";
            case "subcategories";
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE status=1 AND id=" . $id;
                break;
            case "salaryrange";
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE id=" . $id;
                break;
            case "countries";
            case "states";
            case "cities";
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE enabled=1 AND id=" . $id;
                break;
            default:
                // problem case
                //$query = "SELECT serverid FROM #__js_job_" . $table . " WHERE isactive=1 AND id=" . $id;
                break;
        }
        $db->setQuery($query);
        $server_id = $db->loadResult();
        return $server_id;
    }
}
?>
    
