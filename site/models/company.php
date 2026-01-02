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

class JSJobsModelCompany extends JSModel {

    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_config = null;
    var $_comp_editor = null;
    var $_defaultcountry = null;
    var $_jinput = null;


    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
        $this->_jinput = Factory::getApplication()->input;
    }

    function getCompanybyIdforForm($id, $uid, $visitor, $vis_email, $jobid) {
        $db = $this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        $defaultCategory = $this->getJSModel('common')->getDefaultValue('categories');
        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(1, false);
        $cat_required =  '';
        foreach ($fieldsordering AS $cfo) {
            switch ($cfo->field) {
                case "jobcategory":
                    $cat_required = ($cfo->required ? 'required' : '');
                    break;
            }
        }

        if (($id != '') && ($id != 0)) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT company.name ,company.params,company.url ,company.contactname ,company.contactphone ,company.companyfax ,company.contactemail ,company.since ,company.companysize ,company.income ,company.description
                              ,company.zipcode ,company.address1 ,company.address2 ,company.logofilename ,company.smalllogofilename ,company.aboutcompanyfilename ,company.created
                              ,company.id ,company.category ,company.uid
                FROM `#__js_job_companies` AS company
                WHERE company.id = " . $id;
                    $db->setQuery($query);
                  $company = $db->loadObject();
        }
        if (isset($vis_email) && ($vis_email != '') && ($jobid != '')) {
            $query = "SELECT company.name,company.params ,company.url ,company.contactname ,company.contactphone ,company.companyfax ,company.contactemail ,company.since ,company.companysize ,company.income ,company.description
                              ,company.zipcode ,company.address1 ,company.address2 ,company.logofilename ,company.smalllogofilename ,company.aboutcompanyfilename ,company.created
                              ,company.id ,company.category ,company.uid
            			FROM `#__js_job_jobs` AS job
            			JOIN `#__js_job_companies` AS company ON company.id = job.companyid
            			WHERE job.id = " . $db->quote($jobid) . " AND company.contactemail = " . $db->quote($vis_email);

                  $db->setQuery($query);
                  $company = $db->loadObject();
        }
        if (isset($visitor) && $visitor == 1) {
            if (isset($company)) {
                $lists['jobcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category')), 'companycategory', 'class="inputbox ' . $cat_required . ' jsjobs-cbo" ' . '', 'value', 'text', $company->category);
                $multi_lists = $this->getJSModel('employer')->getMultiSelectEdit($company->id, 2);
            } else {
                if (!isset($this->_config)) {
                    $this->_config = $this->getJSModel('configurations')->getConfig('');
                }
                $comapnies = $this->getCompanies($uid);
                if (isset($this->_defaultcountry)) {
                    $states = $this->getJSModel('states')->getStates($this->_defaultcountry, '');
                }
                $lists['jobcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category')), 'companycategory', 'class="inputbox ' . $cat_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultCategory);
            }
        } else {
            if (isset($company)) {
                $lists['jobcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category')), 'category', 'class="inputbox ' . $cat_required . ' jsjobs-cbo" ' . '', 'value', 'text', $company->category);
                $multi_lists = $this->getJSModel('employer')->getMultiSelectEdit($id, 2);
            } else {
                if (!isset($this->_config)) {
                    $this->_config = $this->getJSModel('configurations')->getConfig('');
                }
                $lists['jobcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category')), 'category', 'class="inputbox ' . $cat_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultCategory);
            }
        }
        if (isset($company))
            $result[0] = $company;
        else
            $result[0] = null;
        $result[1] = $lists;

        $result[3] = $fieldsordering;  // company fields

        if ($id) { // not new
            if (!defined('VALIDATE')) {
                define('VALIDATE', 'VALIDATE');
            }
            $result[4] = VALIDATE;
        } else { // new
            $result[4] = $this->getJSModel('permissions')->checkPermissionsFor('ADD_COMPANY');
            $result[5] = $this->getPackageDetailByUid($uid);
        }
        if (isset($multi_lists))
            $result[6] = $multi_lists;
        return $result;
    }

    function getMyCompanies($u_id, $limit, $limitstart) {
        $result = array();
        $db = $this->getDBO();

        if (is_numeric($u_id) == false)
            return false;
        if (($u_id == 0) || ($u_id == ''))
            return false;
        $query = "SELECT count(company.id)
                        FROM `#__js_job_companies` AS company
                        WHERE company.uid = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT company.params, company.status,company.logofilename,company.endgolddate,company.endfeatureddate, company.name, company.city,company.id,company.created, cat.cat_title,CONCAT(company.alias,'-',company.id) AS aliasid,CONCAT(company.alias,'-',company.serverid) AS saliasid,
                        city.cityName AS cityname,state.name AS statename,country.name AS countryname,company.url";

        $query .= " 
            FROM `#__js_job_companies` AS company
            LEFT JOIN `#__js_job_categories` AS cat ON company.category = cat.id
            LEFT JOIN `#__js_job_cities` AS city ON city.id = company.city
            LEFT JOIN `#__js_job_states` AS state ON state.id = city.stateid
            LEFT JOIN `#__js_job_countries` AS country ON country.id = city.countryid
            WHERE company.uid = " . $u_id;

        $db->setQuery($query, $limitstart, $limit);
        $companies = $db->loadObjectList();
        foreach ($companies AS $jobdata) {   // for multicity select
            $jobdata->multicity = $this->getJSModel('cities')->getLocationDataForView($jobdata->city);
        }

        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(1);
        $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);

        $result[0] = $companies;
        $result[1] = $total;
        $result[2] = $fieldsordering;

        return $result;
    }

    function getCompanybyId($companyid) {
        $db = $this->getDBO();
        if (is_numeric($companyid) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $fortask = "getcompanybyid";
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $data['companyid'] = $companyid;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['companybyid']) AND $return_server_value['companybyid'] == -1) { // auth fail
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Company View";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = (object) array();
                $result[2] = (object) array();
                $fieldfor = 1;
            } else {
                $result[0] = (object) $return_server_value[0];
                if ($result[0]->uid == 0 || $result[0]->uid == '')
                    $fieldfor = 11;
                else
                    $fieldfor = 1;
                $result[2] = (object) $return_server_value[1];
            }
        }else {
            $query = "SELECT company.params, company.id,company.name, company.zipcode,company.city,company.url,company.contactemail,company.contactname,company.contactphone,
                          company.uid,company.description,company.since,company.address1,company.address2,company.companyfax,company.companysize,
                          cat.cat_title ,CONCAT(company.alias,'-',company.id) AS aliasid,company.logofilename AS companylogo,company.income
                          
                    			FROM `#__js_job_companies` AS company
                    			LEFT JOIN `#__js_job_categories` AS cat ON company.category = cat.id
                    			WHERE  company.id = " . $companyid;
            $db->setQuery($query);
            $result[0] = $db->loadObject();
			if($result[0]){
            	$result[0]->multicity = $this->getJSModel('cities')->getLocationDataForView($result[0]->city);
				$query = "UPDATE `#__js_job_companies` SET hits = hits+1 WHERE id = " . $companyid;
				$db->setQuery($query);
				if (!$db->execute()) {
					//return false;
				}
				if ($result[0]->uid == 0 || $result[0]->uid == '')
					$fieldfor = 11;
				else
					$fieldfor = 1;
			}
		}
        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(1);
        $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);

        $result[3] = $fieldsordering; // company fields
        return $result;
    }

    function getCompanyInfoById($companyid) { // this may not use

            if (is_numeric($companyid) == false)
                return false;
            $db = $this->getDBO();
            $query = "SELECT company.name
    		FROM `#__js_job_companies` AS company
    		WHERE company.id = " . $companyid;
            $db->setQuery($query);
            $company = $db->loadObject();
            $query = "SELECT count(featuredjobs.id)

    		FROM `#__js_job_featuredjobs` AS featuredjobs
    		JOIN `#__js_job_jobs` AS job ON featuredjobs.jobid = job.id
    		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
    		LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
    		LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
    		WHERE  job.companyid = " . $companyid;
            $db->setQuery($query);
            $jobs = $db->loadResult();

            $query = "SELECT featuredjobs.*,job.created as jobcreated , job.title,company.name as companyname, country.name AS countryname, city.name AS cityname

    		FROM `#__js_job_featuredjobs` AS featuredjobs
    		JOIN `#__js_job_jobs` AS job ON featuredjobs.jobid = job.id
    		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
    		LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
    		LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
    		WHERE  job.companyid = " . $companyid;
            $db->setQuery($query);
            $info = $db->loadObjectList();
            $result[0] = $info;
            $result[1] = $jobs;
            $result[2] = $company;
            return $result;
    }

    function getPackageDetailByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $pacakges[0] = 0;
        $pacakges[1] = 0;
        $db = $this->getDbo();
        $query = "SELECT package.id, payment.id AS paymentid
                   FROM `#__js_job_employerpackages` AS package
                   JOIN `#__js_job_paymenthistory` AS payment ON ( payment.packageid = package.id AND payment.packagefor=1)
                   WHERE payment.uid = " . $uid . "
                   AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";
        $db->setQuery($query);
        $packagedetail = $db->loadObjectList();
        if (!empty($packagedetail)) { // user have pacakge
            foreach ($packagedetail AS $package) {
                $pacakges[0] = $package->id;
                $pacakges[1] = $package->paymentid;
            }
            return $pacakges;
        } else { // user have no package
            return $pacakges;
        }
    }

    function canAddNewCompany($uid) {
        $db = $this->getDBO();
        if ($uid)
            if (is_numeric($uid) == false)
                return false;

        $query = "SELECT package.id AS packageid, package.companiesallow, package.packageexpireindays, payment.id AS paymentid, payment.created
                    FROM `#__js_job_employerpackages` AS package
                    JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                    WHERE payment.uid = " . $uid . "
                    AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                    AND payment.transactionverified = 1 AND payment.status = 1";
        $db->setQuery($query);
        $valid_packages = $db->loadObjectList();
        if (empty($valid_packages)) { // user have no valid package
            $query = "SELECT package.id, package.jobsallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                        , package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
                        , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                       FROM `#__js_job_employerpackages` AS package
                       JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                       WHERE payment.uid = " . $uid . "
                       AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";
            $db->setQuery($query);
            $packagedetail = $db->loadObjectList();
            if (empty($packagedetail)) { // User have no package
                return NO_PACKAGE;
            } else { // User have packages but are expired
                return EXPIRED_PACKAGE;
            }
        } else { // user have valid package
            // check is it allow to add new company
            $unlimited = 0;
            $companyallow = 0;
            $valid_packages_ids = array();
            foreach ($valid_packages AS $company) {
                if ($unlimited == 0) {
                    if ($company->companiesallow != -1) {
                        $valid_packages_ids[] = $company->packageid;
                        $companyallow = $company->companiesallow + $companyallow;
                    } else {
                        $unlimited = 1;
                    }
                }
            }
            if ($unlimited == 0) { // user doesn't have unlimited resume package
                if ($companyallow == 0) {
                    return COMPANY_LIMIT_EXCEEDS;
                }
                //get total company count
                $valid_packages_ids = implode(', ', $valid_packages_ids);
                $query = "SELECT COUNT(company.id) AS totalcompanies
				FROM `#__js_job_companies` AS company
				WHERE company.packageid IN (".$valid_packages_ids.") AND company.uid = " . $uid;
                $db->setQuery($query);
                $totalcompany = $db->loadResult();
                if ($companyallow <= $totalcompany) {
                    return COMPANY_LIMIT_EXCEEDS;
                } else {
                    return VALIDATE;
                }
            } else { // user have unlimited company package
                return VALIDATE;
            }
        }
    }

    function deleteCompany($companyid, $uid) {
        $db = $this->getDBO();
        $row = $this->getTable('company');
        $data = Factory::getApplication()->input->post->getArray();
        if (is_numeric($companyid) == false)
            return false;
        if (is_numeric($uid) == false)
            return false;
        $servercompanyid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT company.serverid AS serverid FROM `#__js_job_companies` AS company  WHERE company.id = " . $companyid;
            $db->setQuery($query);
            $c_s_id = $db->loadResult();
            if ($c_s_id)
                $servercompanyid = $c_s_id;
        }
        $returnvalue = $this->companyCanDelete($companyid, $uid);
        if ($returnvalue == 1) {

                $query = "SELECT company.name, company.contactname, company.contactemail,CONCAT(company.alias,'-',company.id) AS aliasid
                    FROM `#__js_job_companies` AS company
                    WHERE company.id = " . $companyid;
                $db->setQuery($query);
                $company = $db->loadObject();

                $contactname = $company->contactname;
                $contactemail = $company->contactemail;
                $name = $company->name;

                $session = Factory::getApplication()->getSession();
                $session->set('contactname' , $contactname);
                $session->set('contactemail' , $contactemail);
                $session->set('name' , $name);



            if (!$row->delete($companyid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            $query = "DELETE FROM `#__js_job_companycities` WHERE companyid = " . $companyid;
            $db->setQuery($query);
            if (!$db->execute()) {
                return false;
            }
            $this->getJSModel('emailtemplate')->sendDeleteMail( $companyid , 1);
            if ($servercompanyid != 0) {
                $data = array();
                $data['id'] = $servercompanyid;
                $data['referenceid'] = $companyid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletecompany';
                $jsjobsharingobject = $this->getJSModel('jobsharingsite');
                $return_value = $jsjobsharingobject->delete_CompanySharing($data);
                $job_log_object = $this->getJSModel('log');
                $job_log_object->log_Delete_CompanySharing($return_value);
            }
        } else
            return $returnvalue; // company can not delete

        return true;
    }

    function companyCanDelete($companyid, $uid) {
        $db = $this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;
        $result = array();

        $query = "SELECT COUNT(company.id) FROM `#__js_job_companies` AS company
					WHERE company.id = " . $companyid . " AND company.uid = " . $uid;
//echo '<br> SQL '.$query;
        $db->setQuery($query);
        $comtotal = $db->loadResult();

        if ($comtotal > 0) { // this company is same user
            $query = "SELECT
                        ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE companyid = " . $companyid . ")
                        + ( SELECT COUNT(id) FROM `#__js_job_departments` WHERE companyid = " . $companyid . ")
                        AS total ";
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        } else
            return 3; // 	this company is not of this user
    }

    function storeCompany() { //store company
        Factory::getSession()->checkToken() or die( 'Invalid Token' );
        $row = $this->getTable('company');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
		if(isset($data['url']) && $data['url'] != ''){
			$data['url']  = filter_var($data['url'], FILTER_VALIDATE_URL);
		}
        $filerealpath = "";
        if (!$this->_config)
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        foreach ($this->_config as $conf) {

            if ($conf->configname == 'companyautoapprove')
                $status = $conf->configvalue;
            if ($conf->configname == 'company_logofilezize')
                $logofilesize = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
            if ($conf->configname == 'image_file_type')
                $image_file_types = $conf->configvalue;
        }

        if($data['since'] !=''){
            if ($dateformat == 'm/d/Y') {
                $arr = explode('/', $data['since']);
                $data['since'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
            } elseif ($dateformat == 'd-m-Y') {
                $arr = explode('-', $data['since']);
                $data['since'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            }
        }
        $data['since'] = date('Y-m-d H:i:s', strtotime($data['since']));
        $data['description'] = $this->getJSModel('common')->getHtmlInput('description');
        $file_size_increase = 0;
        $companylogofile = null;
		$companylogofile = $this->_jinput->files->get('logo');
        if (isset($companylogofile) && $companylogofile['size'] > 0) { // logo
            $uploadfilesize = $companylogofile['size'];
            $uploadfilesize = $uploadfilesize / 1024; //kb
            if ($uploadfilesize > $logofilesize) { // logo
                $file_size_increase = 1;  // file size error
            }
        }
        if (!empty($data['alias']))
            $companyalias = $this->getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $companyalias = $this->getJSModel('common')->removeSpecialCharacter($data['name']);

        $companyalias = strtolower(str_replace(' ', '-', $companyalias));
        $data['alias'] = $companyalias;

    //custom field code start
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = $this->getJSModel('customfields')->getUserfieldsfor(1);
        $params = array();
        $forfordelete = '';

        foreach ($userfield AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }else{
                    $c_file = $this->_jinput->files->get($ufobj->field);
                    $vardata = $c_file['name'];
                }
                $customflagforadd=true;
                $custom_field_namesforadd[]=$ufobj->field;
            }else{
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $customflagfordelete = true;
                $forfordelete = $ufobj->field;
                $custom_field_namesfordelete[]= $data[$ufobj->field.'_2'];
            }
            if($vardata != ''){
                //had to comment this so that multpli field should work properly
                // if($ufobj->userfieldtype == 'multiple'){
                //     $vardata = explode(',', $vardata[0]); // fixed index
                // }
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = htmlspecialchars($vardata);
            }
        }

        if($data['id'] != ''){
            if(is_numeric($data['id'])){
                $db = Factory::getdbo();
                $query = "SELECT params FROM `#__js_job_companies` WHERE id = ".$data['id'];
                $db->setQuery($query);
                $oParams = $db->loadResult();
                if(!empty($oParams)){
                    $oParams = json_decode($oParams,true);
                    $unpublihsedFields = $this->getJSModel('customfields')->getUnpublishedFieldsFor(1);
                    foreach($unpublihsedFields AS $field){
                        if(isset($oParams[$field->field])){
                            $params[$field->field] = $oParams[$field->field];
                        }
                    }
                }
            }
        }
        if (!empty($params)) {
            if($customflagfordelete == true){
                unset($params[$forfordelete]); // sice file is deleted so we remove the data
            }
            $params = json_encode($params);
        }
        $data['params'] = $params;


    //custom field code end


        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return 2;
        }
        if(isset($data['id']) && is_numeric($data['id'])){ //in case of edit we have mainatain pervious values of gold and featured
            unset($row->status);
        }else{
            $row->status = $status;
        }

        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }

        $companyid = $row->id;
        $filemismatch = 0;
        if ($file_size_increase != 1) {
            if (isset($companylogofile) && $companylogofile['size'] > 0) { // logo
                $data['logofilename'] = $companylogofile['name']; // file name
                $data['logoisfile'] = 1; // logo store in file system
                $returnvalue = $this->uploadFile($companyid, 1, 0);
                if ($returnvalue == 6)
                    $filemismatch = 1;
                $filerealpath = $returnvalue;
            }
        }
        if (isset($data['deletelogo']) AND $data['deletelogo'] == 1) { // delete logo
            $returnvalue = $this->uploadFile($companyid, 1, 1);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }
        if ($file_size_increase != 1) {
            $smalllogo = $this->_jinput->files->get('smalllogo');
            if (isset($smalllogo['size']) AND $smalllogo['size'] > 0) { //small logo
                $returnvalue = $this->uploadFile($companyid, 2, 0);
                if ($returnvalue == 6)
                    $filemismatch = 1;
            }
        }
        if (isset($data['deletesmalllogo']) AND $data['deletesmalllogo'] == 1) { //delete small logo
            $returnvalue = $this->uploadFile($companyid, 2, 1);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }

        if ($file_size_increase != 1) {
            $aboutcompanylogo = $this->_jinput->files->get('aboutcompany');
            if (isset($aboutcompanylogo['size']) AND $aboutcompanylogo['size'] > 0) { //about company
                $returnvalue = $this->uploadFile($companyid, 3, 0);
                if ($returnvalue == 6)
                    $filemismatch = 1;
            }
        }
        if (isset($data['deleteaboutcompany']) AND $data['deleteaboutcompany'] == 1) { // delete about company
            $returnvalue = $this->uploadFile($companyid, 3, 1);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }

        if (isset($data['city']) && $data['city'] !='')// was showing undefined error
            $storemulticity = $this->storeMultiCitiesCompany($data['city'], $row->id);
        if (isset($storemulticity) AND ( $storemulticity == false))
            return false;

        if ($data['id'] == '') { //only for new
            $this->getJSModel('adminemail')->sendMailtoAdmin($companyid, $data['uid'], 1);
            $this->getJSModel('emailtemplate')->sendMailtoEmployerNewCompany($companyid, $data['uid'] );
        }

        // new
        //removing custom field
        if($customflagfordelete == true){
            foreach ($custom_field_namesfordelete as $key) {
                $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($companyid,$key ,1,1);
            }
        }
        //storing custom field attachments
        if($customflagforadd == true){
            foreach ($custom_field_namesforadd as $key) {
                $c_file = $this->_jinput->files->get($key);
                if ($c_file['size'] > 0) { // logo
                    $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($companyid,$key ,0,1);
                }
            }
        }
        // End attachments



        if ($this->_client_auth_key != "") {

            $company_logo = array();

            $db = $this->getDBO();
            $query = "SELECT company.* FROM `#__js_job_companies` AS company
						WHERE company.id = " . $row->id;
            $db->setQuery($query);
            $data_company = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_company->id = $data['id']; // for edit case
            else
                $data_company->id = ''; // for new case
            if (isset($companylogofile) && $companylogofile['size'] > 0)
                $company_logo['logofilename'] = $filerealpath;
            $data_company->company_id = $row->id;
            $data_company->authkey = $this->_client_auth_key;
            $data_company->task = 'storecompany';
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $return_value = $jsjobsharingobject->store_CompanySharing($data_company);
            $job_log_object = $this->getJSModel('log');
            if ($return_value['iscompanystore'] == 0)
                $job_log_object->log_Store_CompanySharing($return_value);
            if ($file_size_increase != 1) {
                if ($filemismatch != 1) {
                    if (isset($companylogofile['size']) && $companylogofile['size'] > 0)
                        $return_value_company_logo = $jsjobsharingobject->store_CompanyLogoSharing($data_company, $company_logo);
                }
            }
            if (is_array($return_value) AND ! empty($return_value) AND is_array($return_value_company_logo) AND ! empty($return_value_company_logo)) {
                $company_logo_return_value = (array_merge($return_value, $return_value_company_logo));
                $job_log_object->log_Store_CompanySharing($company_logo_return_value);
            } else {
                $job_log_object->log_Store_CompanySharing($return_value);
            }
        }
        if ($file_size_increase == 1) {
            return 5;
        } elseif ($filemismatch == 1) {
            return 6;
        }
        if(!($data['id'] > 0) && $row->status == 1){ //new case
            $this->postCompanyOnJomSocial( $companyid );
        }
        return true;
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated
        if (!is_numeric($companyid))
            return false;
        $db = Factory::getDBO();
        $query = "SELECT cityid FROM #__js_job_companycities WHERE companyid = " . $companyid;
        $db->setQuery($query);
        $old_cities = $db->loadObjectList();
        $id_array = explode(",", $city_id);
        $row = $this->getTable('companycities');
        $error = array();

        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM #__js_job_companycities WHERE companyid = " . $companyid . " AND cityid=" . $oldcityid->cityid;
                $db->setQuery($query);
                if (!$db->execute()) {
                    $err = $this->setError($row->getError());
                    $error[] = $err;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $row->id = "";
                $row->companyid = $companyid;
                $row->cityid = $cityid;
                if (!$row->store()) {
                    $err = $this->setError($row->getError());
                    $error[] = $err;
                }
            }
        }
        if (!empty($error))
            return false;

        return true;
    }

    function getCompanies($uid) { 
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = Factory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` WHERE uid = " . $uid . " AND status = 1";
        $query.=" ORDER BY name ASC ";
       try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $companies = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $companies[] = array('value' => $row->id, 'text' => Text::_($row->name));
            }
        } else {
            $companies[] = array('value' => '', 'text' => '');
        }
        return $companies;
    }

    function getAllCompanies($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` ORDER BY name ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $companies = array();
        if ($title)
            $companies[] = array('value' => Text::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $companies[] = array('value' => $row->id, 'text' => $row->name);
        }
        return $companies;
    }

    function uploadFile($id, $action, $isdeletefile) {
        if (is_numeric($id) == false)
            return false;
        $row = $this->getTable('company');
        $db = Factory::getDBO();
        if (!isset($this->_config))
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
            if ($conf->configname == 'company_logofilezize')
                $logofilesize = $conf->configvalue;
            if ($conf->configname == 'image_file_type')
                $image_file_types = $conf->configvalue;
        }
        $path = JPATH_BASE . '/' . $datadirectory;
        if (!file_exists($path)) { // create user directory
            $this->getJSModel('common')->makeDir($path);
        }
        $isupload = false;
        $path = $path . '/data';
        if (!file_exists($path)) { // create user directory
            $this->getJSModel('common')->makeDir($path);
        }
        $path = $path . '/employer';
        if (!file_exists($path)) { // create user directory
            $this->getJSModel('common')->makeDir($path);
        }

        if ($action == 1) { //Company logo
            $companylogo = $this->_jinput->files->get('logo');
            if ($companylogo['size'] > 0) {
                $file_name = $companylogo['name']; // file name
                $file_tmp = $companylogo['tmp_name']; // actual location
            } elseif ($this->_jinput->files->get('companylogo')['size'] > 0) { //for visitor
                $file_name = $this->_jinput->files->get('companylogo')['name']; // file name
                $file_tmp = $this->_jinput->files->get('companylogo')['tmp_name']; // actual location
            }

            if ($file_name != '' AND $file_tmp != "") {
                $check_image_extension = $this->getJSModel('common')->checkImageFileExtensions($file_name, $file_tmp, $image_file_types);
                if ($check_image_extension == 6) {
                    $row->load($id);
                    $row->logofilename = "";
                    $row->logoisfile = -1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                    return $check_image_extension;
                } else {
                    $row->load($id);
                    $row->logofilename = getJSJobsPHPFunctionsClass()->sanitizeData($file_name);
                    $row->logoisfile = 1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
                $userpath = $path . '/comp_' . $id;

                if (!file_exists($userpath)) { // create user directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $userpath = $userpath . '/logo';
                if (!file_exists($userpath)) { // create logo directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $isupload = true;
            }
        } elseif ($action == 2) { //Company small logo
            $smalllogo = $this->_jinput->files->get('smalllogo');
            if ($smalllogo['size'] > 0) {
                $file_name = $smalllogo['name']; // file name
                $file_tmp = $smalllogo['tmp_name']; // actual location
            }

            if ($file_name != '' AND $file_tmp != "") {
                $check_image_extension = $this->getJSModel('common')->checkImageFileExtensions($file_name, $file_tmp, $image_file_types);
                if ($check_image_extension == 6) {
                    $row->load($id);
                    $row->smalllogofilename = "";
                    $row->smalllogoisfile = -1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                    return $check_image_extension;
                } else {
                    $row->load($id);
                    $row->smalllogofilename = getJSJobsPHPFunctionsClass()->sanitizeData($file_name);
                    $row->smalllogoisfile = 1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }

                $userpath = $path . '/comp_' . $id;
                if (!file_exists($userpath)) { // create user directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $userpath = $userpath . '/smalllogo';
                if (!file_exists($userpath)) { // create logo directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $isupload = true;
            }
        } elseif ($action == 3) { //About Company
            $aboutlogo = $this->_jinput->files->get('aboutcompany');
            if ($aboutlogo['size'] > 0) {
                $file_name = $aboutlogo['name']; // file name
                $file_tmp = $aboutlogo['tmp_name']; // actual location
            }

            if ($file_name != '' AND $file_tmp != "") {
                $check_image_extension = $this->getJSModel('common')->checkImageFileExtensions($file_name, $file_tmp, $image_file_types);
                if ($check_image_extension == 6) {
                    $row->load($id);
                    $row->aboutcompanyfilename = "";
                    $row->aboutcompanyisfile = -1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                    return $check_image_extension;
                } else {
                    $row->load($id);
                    $row->aboutcompanyfilename = getJSJobsPHPFunctionsClass()->sanitizeData($file_name);
                    $row->aboutcompanyisfile = 1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
                $userpath = $path . '/comp_' . $id;
                if (!file_exists($userpath)) { // create user directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $userpath = $userpath . '/aboutcompany';
                if (!file_exists($userpath)) { // create logo directory
                    $this->getJSModel('common')->makeDir($userpath);
                }
                $isupload = true;
            }
        }
        if ($isupload) {
            $files = glob($userpath . '/*.*');
            array_map('unlink', $files);  //delete all file in directory
            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
            return $userpath . '/' . $file_name;
        } else { // DELETE FILES
            if ($action == 1) { // company logo
                if ($isdeletefile == 1) {
                    $userpath = $path . '/comp_' . $id . '/logo';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files); // delete all file in the direcoty
                    $row->load($id);
                    $row->logofilename = "";
                    $row->logoisfile = -1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
            } elseif ($action == 2) { // company small logo
                if ($isdeletefile == 1) {
                    $userpath = $path . '/comp_' . $id . '/smalllogo';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files); // delete all file in the direcoty
                    $row->load($id);
                    $row->smalllogofilename = "";
                    $row->smalllogoisfile = -1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
            } elseif ($action == 3) { // about company
                if ($isdeletefile == 1) {
                    $userpath = $path . '/comp_' . $id . '/aboutcompany';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files); // delete all file in the direcoty
                    $row->load($id);
                    $row->aboutcompanyfilename = "";
                    $row->aboutcompanyisfile = -1;
                    if (!$row->store()) {
                        $this->setError($row->getError());
                    }
                }
            }
            return 1;
        }
    }

  function getAllCompaniesList($sortby, $limit, $limitstart, $filter=array()) {
    $wherequery = '';
    if (isset($filter['companycity']) && !empty($filter['companycity'])) {
        $companyCityIds_array = array_map('intval', explode(',', $filter['companycity']));
        if (!empty($companyCityIds_array)) {
            $companyCityIds = implode(',',$companyCityIds_array);
            $wherequery .= " AND companycity.cityid IN (".$companyCityIds.")";
        }
    }

    if (isset($filter['companyname']) && !empty($filter['companyname'])) {
        $wherequery .= " AND LOWER(company.name) like '%". addslashes($filter['companyname'])."%' ";
    }

    if (isset($filter['category']) && !empty($filter['category']) && is_numeric($filter['category'])) {
        $wherequery .= " AND company.category = " . $filter['category'];
    }

    $db = Factory::getDbo();
    // count
    $query = "SELECT COUNT(DISTINCT company.id)
            FROM `#__js_job_companies` AS company
            LEFT JOIN `#__js_job_companycities` AS companycity ON companycity.companyid = company.id
            WHERE company.status = 1";
    $query .= $wherequery;
    $db->setQuery($query);
    $totalcompanies = $db->loadResult();

    // Data
    $query = "SELECT DISTINCT company.params, company.name AS companyname,company.city, company.logofilename AS companylogo, company.url AS companyurl, company.isgoldcompany, company.isfeaturedcompany, company.alias, company.id,company.endgolddate,company.endfeatureddate,cat.cat_title
                FROM `#__js_job_companies` AS company
                LEFT JOIN `#__js_job_companycities` AS companycity ON companycity.companyid = company.id
                LEFT JOIN `#__js_job_categories` AS cat ON company.category = cat.id
                LEFT JOIN `#__js_job_cities` AS city ON city.id = companycity.cityid
                WHERE company.status = 1";
    $query .= $wherequery.' ORDER BY '.$sortby;
    $db->setQuery($query,$limitstart,$limit);
    $companies = $db->loadObjectList();
    foreach ($companies as $company) {
        $multicitydata = $this->getJSModel('cities')->getLocationDataForView($company->city);
        if ($multicitydata != "")
            $company->multicity = $multicitydata;
    }
    // Pagination
    $total = $totalcompanies;

    $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(1, false);
    $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);

    $result[0] = $companies;
    $result[1] = $total;
    $result[2] = isset($filter['companycity']) && !empty($filter['companycity']) ? $this->getJSModel('job')->getCitiesForFilter($filter['companycity']) : '';
    $result[3] = $fieldsordering;
    return $result;
  }
  function getIfCompanyOwner($companyid, $uid) {
        $db = $this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;

        $query = "SELECT COUNT(company.id) FROM `#__js_job_companies` AS company
                WHERE company.id = " . $companyid . " AND company.uid = " . $uid;
        $db->setQuery($query);
        $comtotal = $db->loadResult();
        if($comtotal > 0){
            return true;
        }else{
            return false;
        }
  }

    function postCompanyOnJomSocial($companyid){
        if( $this->getJSModel(VIRTUEMARTJSJOBS)->{VIRTUEMARTJSJOBSFUN}("Zms5MFowam9tc29jaWFsdk04MXND") ){
            JPluginHelper::importPlugin('community');
            $dispatcher = JEventDispatcher::getInstance();
            $res = $dispatcher->trigger('JSjobsPostCompanyOnJomSocial',array($companyid));
            if(!$res[0])
                return false;
        }
        return true;
    }

    function makeCompanySeo($company_seo , $jsjobid){
        if(empty($company_seo))
            return '';
        $db = Factory::getDBO();
        $common = $this->getJSModel('common');
        $id = $common->parseId($jsjobid);
        if(! is_numeric($id))
            return '';
        $result = '';
        $company_seo = str_replace( ' ', '', $company_seo);
        $company_seo = str_replace( '[', '', $company_seo);
        $array = explode(']', $company_seo);

        $total = count($array);
        if($total > 3)
            $total = 3;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'name':
                    $query = "SELECT name AS col FROM `#__js_job_companies` WHERE id = " . $id;
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `#__js_job_companies` AS company
                        LEFT JOIN `#__js_job_categories` AS category ON category.id = company.category
                        WHERE company.id = " . $id;
                break;
                case 'location':
                    $query = "SELECT company.city AS col
                        FROM `#__js_job_companies` AS company WHERE company.id = " . $id;
                break;
            }
            if($query){
                $db->setQuery($query);
                $data = $db->loadObject();
                if(isset($data->col)){
                    if($array[$i] == 'location'){
                        $cityids = explode(',', $data->col);
                        $location = '';
                        for ($j=0; $j < count($cityids); $j++) {
                            if(is_numeric($cityids[$j])){
                                $query = "SELECT name FROM `#__js_job_cities` WHERE id = ". $cityids[$j];
                                $db->setQuery($query);
                                $cityname = $db->loadObject();
                                if(isset($cityname->name)){
                                    if($location == '')
                                        $location .= $cityname->name;
                                    else
                                        $location .= ' '.$cityname->name;

                                }
                            }
                        }
                        $location = $common->removeSpecialCharacter($location);
                        if($location != ''){
                            if($result == '')
                                $result .= str_replace(' ', '-', $location);
                            else
                                $result .= '-'.str_replace(' ', '-', $location);
                        }
                    }else{
                        $val = $common->removeSpecialCharacter($data->col);
                        if($result == '')
                            $result .= str_replace(' ', '-', $val);
                        else
                            $result .= '-'.str_replace(' ', '-', $val);
                    }
                }
            }
        }
        return $result;
    }

    function getCompanynameById($companyid) { // this may not use
        if (is_numeric($companyid) == false)
            return false;
        $company_name = '';
        $db = $this->getDBO();
        $query = "SELECT company.name
        FROM `#__js_job_companies` AS company
        WHERE company.id = " . $companyid;
        $db->setQuery($query);
        $company = $db->loadObject();
        if(!empty($company)){
            $company_name = $company->name;
        }
        return $company_name;
   }
}
?>
