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

class JSJobsModelCompany extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_application = null;
    var $_comp_editor = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getAllCompaniesForSearch($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies`";
        $query.= " ORDER BY name ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
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

    function getCompanybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $cat_required = '';
        $defaultCategory = $this->getJSModel('common')->getDefaultValue('categories');
        $companyfieldordering = $this->getJSModel('fieldordering')->getFieldsOrderingforForm(1); // company fields
        foreach ($companyfieldordering AS $cfo) {
            switch ($cfo->field) {
                case "jobcategory":
                    $cat_required = ($cfo->required ? 'required' : '');
                    break;
            }
        }

        $db = Factory::getDBO();
        $query = "SELECT comp.id,comp.params,comp.uid,comp.category,comp.status,comp.logofilename,comp.created,comp.name,comp.url,comp.contactname,comp.contactemail,comp.address1,comp.address2,comp.contactphone,comp.zipcode,comp.companyfax,comp.since,comp.description,comp.companysize,comp.income,
                    user.name AS employername,CONCAT(comp.alias,'-',comp.id) AS aliasid,comp.city,cat.cat_title
                    FROM `#__js_job_companies` AS comp
                    LEFT JOIN `#__users` AS user ON user.id = comp.uid
                    LEFT JOIN `#__js_job_categories` AS cat ON comp.category = cat.id
                    WHERE comp.id=".$c_id;

        $db->setQuery($query);
        $company = $db->loadObject();
        if($company!=null){
            $company->multicity = $this->getJSSiteModel('cities')->getLocationDataForView($company->city);
        }

        $status = array(
            '0' => array('value' => 0,'text' => Text::_('Pending')),
            '1' => array('value' => 1, 'text' => Text::_('Approve')),
            '2' => array('value' => -1, 'text' => Text::_('Reject')),);
        $uid = $this->getJSModel('user')->getAllEmployerListForComboBox(Text::_('Select User'));

        if (isset($company)) {
            $lists['category'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category'), ''), 'category', 'class="inputbox ' . $cat_required . '"' . '', 'value', 'text', $company->category);
            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $company->status);
            $lists['uid'] = HTMLHelper::_('select.genericList', $uid, 'uid', 'class="inputbox required" ' . '', 'value', 'text', $company->uid);
            $multi_lists = $this->getJSModel('common')->getMultiSelectEdit($c_id, 2);
        } else {
            if (!isset($this->_config)) {
                $this->_config = $this->getJSModel('configuration')->getConfig();
            }
            $lists['category'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category'), ''), 'category', 'class="inputbox ' . $cat_required . '"' . '', 'value', 'text', $defaultCategory);
            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', 1);
            $lists['uid'] = HTMLHelper::_('select.genericList', $uid, 'uid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $company;
        $result[1] = $lists;
        $result[2] = $this->getJSModel('fieldordering')->getFieldsOrderingforView(1);
        $result[3] = $companyfieldordering;
        if (isset($multi_lists))
            $result[4] = $multi_lists;

        return $result;
    }

    function getAllCompanies($datafor, $companyname, $jobcategory, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit) {

        if($js_sortby==1){
            $sortby = "cat.cat_title $sortby ";
        }elseif($js_sortby==2){
            $sortby = "company.created $sortby ";
        }elseif($js_sortby==3){
            $sortby = "company.status $sortby ";
        }elseif($js_sortby==4){
            $sortby = "cities.cityName $sortby ";
        }elseif($js_sortby==5){
            $sortby = "company.name $sortby ";
        }elseif($js_sortby==6){
            $sortby = "company.hits $sortby ";
        }else{
            $sortby = "company.created desc";
        }


        $db = Factory::getDBO();
        if($datafor==1){ // 1 for Jobs, 2 for Jobs queue
            $status_opr = (is_numeric($status)) ? ' = '.$status : ' <> 0 ';
            $fquery = " WHERE company.status".$status_opr;
        }else{ // For companys Queue
            $fquery = " WHERE company.status = 0 ";
        }

        if($companyname)
            $fquery .= " AND LOWER(company.name) LIKE ".$db->Quote('%'.$companyname.'%');
        if ($jobcategory){
            if(is_numeric($jobcategory))
                $fquery .= " AND company.category = ".$jobcategory;
        }
        if($dateto !='' AND $datefrom !=''){
            $fquery .= " AND DATE(company.created) <= ".$db->Quote(date('Y-m-d' , strtotime($dateto)))." AND DATE(company.created) >= ".$db->Quote(date('Y-m-d' , strtotime($datefrom)));
        }else{
            if($dateto)
                $fquery .= " AND DATE(company.created) <= ".$db->Quote(date('Y-m-d' , strtotime($dateto)));
            if($datefrom)
                $fquery .= " AND DATE(company.created) >= ".$db->Quote(date('Y-m-d' , strtotime($datefrom)));
        }

        $lists = array();
        $lists['companyname'] = $companyname;
        $lists['jobcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category'),'') , 'jobcategory', 'class="inputbox" ' , 'value', 'text', $jobcategory);
        $lists['dateto'] = $dateto;
        $lists['datefrom'] = $datefrom;
        if($datafor==1)
            $lists['status'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getApprove(Text::_('Select Status'),'') , 'status', 'class="inputbox" ' , 'value', 'text', $status);
        else
            $lists['status'] = $status;

        //Pagination
        $result = array();
        $query = "SELECT COUNT(company.id) FROM #__js_job_companies AS company
                LEFT JOIN #__js_job_categories AS cat ON company.category = cat.id ";
        $query .= $fquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        //Data
        $query = "SELECT company.id, company.uid, company.name, company.logofilename
                ,company.created, company.status, company.isgoldcompany, company.startgolddate
                , company.isfeaturedcompany, company.startfeatureddate, company.city
                , company.endgolddate, company.endfeatureddate, company.url,cat.cat_title
                FROM `#__js_job_companies` AS company 
                LEFT JOIN `#__js_job_categories` AS cat ON company.category = cat.id
                LEFT JOIN `#__js_job_cities` AS cities ON cities.id = (SELECT cityid FROM `#__js_job_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1 )  ";
        $query .= $fquery;
        $query .= " ORDER BY $sortby ";
        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();
        $companies = array();
        foreach ($this->_application as $d) {
            $d->location = $this->getJSModel('city')->getLocationDataForView($d->city);
            $companies[] = $d;
        }
        $this->_application = $companies;
        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function storeCompany() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        jimport('joomla.filesystem.file');
        $row = $this->getTable('company');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $filerealpath = "";

        if (!$this->_config)
            $this->_config = $this->getJSModel('configuration')->getConfig('');

        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
            if ($conf->configname == 'company_logofilezize')
                $logofilesize = $conf->configvalue;

        }

        if ($dateformat == 'm/d/Y') {
            $arr = explode('/', $data['since']);
            $data['since'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['since']);
            $data['since'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
        $data['since'] = date('Y-m-d H:i:s', strtotime($data['since']));

        $data['description'] = $this->getJSModel('common')->getHtmlInput('description');
        $returnvalue = 1;

        $logo_file = Factory::getApplication()->input->files->get('logo');
        $logo_size = $logo_file['size'];

        $smalllogo_size = 0;
        if(!empty($smalllogo_file['size'])){
            $smalllogo_file = Factory::getApplication()->input->files->get('smalllogo');
            $smalllogo_size = $smalllogo_file['size'];
        }

        $aboutcompany_size = 0;
        if(!empty($aboutcompany_file['size'])){
            $aboutcompany_file = Factory::getApplication()->input->files->get('aboutcompany');
            $aboutcompany_size = $aboutcompany_file['size'];
        }

        $file_size_increase = 0;
        if ($logo_size > 0) { // logo
            $uploadfilesize = $logo_size;
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
        $userfield = $this->getJSModel('fieldordering')->getUserfieldsfor(1);
        $params = array();
        $forfordelete = '';

        foreach ($userfield AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }else{
                    $custom_field_file = Factory::getApplication()->input->files->get($ufobj->field);
                    $vardata = File::makeSafe($custom_field_file['name']);
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
                   $unpublihsedFields = $this->getJSModel('fieldordering')->getUnpublishedFieldsFor(1);
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
        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }

        // For file upload
        $companyid = $row->id;

        if($file_size_increase != 1){

            $filetypemismatch = 0;
            if ($logo_size > 0) { // logo
                $returnvalue = $this->uploadFile($companyid, 1, 0);
                if ($returnvalue == 6)
                    $filetypemismatch = 1;
                $filerealpath = $returnvalue;
            }
            if (isset($data['deletelogo']) AND $data['deletelogo'] == 1) { // delete logo
                $returnvalue = $this->uploadFile($companyid, 1, 1);
                if ($returnvalue == 6)
                    $filetypemismatch = 1;
            }

            if ($smalllogo_size > 0) { //small logo
            $returnvalue = $this->uploadFile($companyid, 2, 0);
            if ($returnvalue == 6)
            $filetypemismatch = 1;
            }
            if (isset($data['deletesmalllogo']) AND $data['deletesmalllogo'] == 1) { //delete small logo
            $returnvalue = $this->uploadFile($companyid, 2, 1);
            if ($returnvalue == 6)
            $filetypemismatch = 1;
            }

            if ($aboutcompany_size > 0) { //about company
            $returnvalue = $this->uploadFile($companyid, 3, 0);
            if ($returnvalue == 6)
            $filetypemismatch = 1;
            }

            if (isset($data['deleteaboutcompany']) AND $data['deleteaboutcompany'] == 1) { // delete about company
            $returnvalue = $this->uploadFile($companyid, 3, 1);
            if ($returnvalue == 6)
            $filetypemismatch = 1;
            }
        }

        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesCompany($data['city'], $row->id);
        if (isset($storemulticity) AND ( $storemulticity == false))
            return false;

        

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
                $custom_field2_file = Factory::getApplication()->input->files->get($key);
                $custom_field_size = $custom_field2_file['size'];
                if ($custom_field_size > 0) { // logo
                    $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($companyid,$key ,0,1);
                }
            }
        }
        // End attachments


        if ($file_size_increase == 1) {
            return 5;
        } elseif ($filetypemismatch == 1){
            return 6;
        }
        if(!($data['id'] > 0) && $row->status == 1){ //new case
            $this->postCompanyOnJomSocial( $companyid );
        }
        return true;
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated 
        if (is_numeric($companyid) === false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT cityid FROM `#__js_job_companycities` WHERE companyid = " . $companyid;
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
                $query = "DELETE FROM `#__js_job_companycities` WHERE companyid = " . $companyid . " AND cityid=" . $oldcityid->cityid;
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

    function getUidByCompanyId($companyid) {
        if (!is_numeric($companyid))
            return false;
        $db = $this->getDbo();
        $query = "SELECT uid FROM `#__js_job_companies` WHERE id = $companyid";
        $db->setQuery($query);
        $uid = $db->loadResult();
        return $uid;
    }

    function deleteCompany() {
        if(!Factory::getSession()->checkToken('post')){
	        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        }
        $db = Factory::getDBO();
        $cids = Factory::getApplication()->input->get('cid', array(0), '', 'array');
        $row = $this->getTable('company');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if (!is_numeric($cid))
                return false;
            $servercompanyid = 0;
            if ($this->_client_auth_key != "") {
                $query = "SELECT company.serverid AS serverid FROM `#__js_job_companies` AS company  WHERE company.id = " . $cid;
                $db->setQuery($query);
                $c_s_id = $db->loadResult();
                if ($c_s_id)
                    $servercompanyid = $c_s_id;
            }
            if ($this->companyCanDelete($cid) == true) {

                $query = "SELECT company.name, company.contactname, company.contactemail,CONCAT(company.alias,'-',company.id) AS aliasid 
                    FROM `#__js_job_companies` AS company
                    WHERE company.id = " . $cid;
                $db->setQuery($query);
                $company = $db->loadObject();

                $contactname = $company->contactname;
                $contactemail = $company->contactemail;
                $name = $company->name;

                $session = Factory::getApplication()->getSession();
                $session->set('contactname' , $contactname);
                $session->set('contactemail' , $contactemail);
                $session->set('name' , $name);

                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
                $query = "DELETE FROM `#__js_job_companycities` WHERE companyid = " . $cid;
                $db->setQuery($query);
                if (!$db->execute()) {
                    return false;
                }
                
                $this->getJSModel('emailtemplate')->sendDeleteMail( $cid , 1);

                if ($servercompanyid != 0) {
                    $data = array();
                    $data['id'] = $servercompanyid;
                    $data['referenceid'] = $cid;
                    $data['uid'] = $this->_uid;
                    $data['authkey'] = $this->_client_auth_key;
                    $data['siteurl'] = $this->_siteurl;
                    $data['task'] = 'deletecompany';
                    $jsjobsharingobject = $this->getJSModel('jobsharing');
                    $return_value = $jsjobsharingobject->deleteCompanySharing($data);
                    $jsjobslogobject = $this->getJSModel('log');
                    $jsjobslogobject->logDeleteCompanySharing($return_value);
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function companyCanDelete($companyid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = $this->getDBO();

        $query = "SELECT 
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE companyid = " . $companyid . ") 
                    + ( SELECT COUNT(id) FROM `#__js_job_departments` WHERE companyid = " . $companyid . ")
                    AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function companyEnforceDelete($companyid, $uid) {
        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        if (is_numeric($companyid) == false)
            return false;
        $db = $this->getDBO();
        $servercompanyid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT company.serverid AS serverid FROM `#__js_job_companies` AS company  WHERE company.id = " . $companyid;
            $db->setQuery($query);
            $c_s_id = $db->loadResult();
            if ($c_s_id)
                $servercompanyid = $c_s_id;
        }

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

        $query = "DELETE  company,job,department,companycity
                         FROM `#__js_job_companies` AS company
                         LEFT JOIN `#__js_job_companycities` AS companycity ON company.id=companycity.companyid
                         LEFT JOIN `#__js_job_jobs` AS job ON company.id=job.companyid
                         LEFT JOIN `#__js_job_departments` AS department ON company.id=department.companyid
                         WHERE company.id = " . $companyid;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        if (!$db->execute()) {
            return 2; //error while delete company
        }

        $this->getJSModel('emailtemplate')->sendDeleteMail( $companyid , 1);
        if ($servercompanyid != 0) {
            $data = array();
            $data['id'] = $servercompanyid;
            $data['referenceid'] = $cid;
            $data['uid'] = $this->_uid;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $data['task'] = 'deletecompany';
            $data['enforcedeletecompany'] = 1;
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_value = $jsjobsharingobject->deleteCompanySharing($data);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logDeleteCompanySharingEnforce($return_value);
        }
        return 1;
    }


    function getCompany($title) {
        $db = Factory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` WHERE status = 1 ORDER BY id ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $companies = array();
        if ($title)
            $companies[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $companies[] = array('value' => $row->id, 'text' => $row->name);
        }
        return $companies;
    }

    function uploadFile($id, $action, $isdeletefile) {
        $db = Factory::getDBO();
        jimport('joomla.filesystem.file');

        $row = $this->getTable('company');
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

        $isupload = false;
        if ($action == 1) { //Company logo
            $logo_file = Factory::getApplication()->input->files->get('logo');
            $logo_filename = File::makeSafe($logo_file['name']);
            $logo_tmpname = $logo_file['tmp_name'];
            $logo_size = $logo_file['size'];

            if ($logo_size > 0) {
                $file_name = $logo_filename; // file name
                $file_tmp = $logo_tmpname; // actual location

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
            }
        } elseif ($action == 2) { //Company small logo
            $smalllogo_file = Factory::getApplication()->input->files->get('smalllogo');
            $smalllogo_filename = File::makeSafe($smalllogo_file['name']);
            $smalllogo_tmpname = $smalllogo_file['tmp_name'];
            $smalllogo_size = $smalllogo_file['size'];

            if ($smalllogo_size > 0) {
                $file_name = $smalllogo_filename; // file name
                $file_tmp = $smalllogo_tmpname; // actual location

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
            }
        } elseif ($action == 3) { //About Company
            $aboutcompany_file = Factory::getApplication()->input->files->get('aboutcompany');
            $aboutcompany_filename = File::makeSafe($aboutcompany_file['name']);
            $aboutcompany_tmpname = $aboutcompany_file['tmp_name'];
            $aboutcompany_size = $aboutcompany_file['size'];
            if ($aboutcompany_size > 0) {
                $file_name = $aboutcompany_filename; // file name
                $file_tmp = $aboutcompany_tmpname; // actual location

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
        }

        if ($isupload) {
            $files = glob($userpath . '/*.*');
            array_map('unlink', $files);  //delete all file in directory

            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
            return $userpath . '/' . $file_name;
            return 1;
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

    function companyApprove($company_id) {
        if (is_numeric($company_id) == false)
            return false;
        $db = Factory::getDBO();
        $query = "UPDATE #__js_job_companies SET status = 1 WHERE id = " . $company_id;
        $db->setQuery($query);
        if (!$db->execute())
            return false;
        $company_approve_email = $this->getJSModel('emailtemplate')->sendMail(1, 1, $company_id);
        if ($this->_client_auth_key != "") {
            $data_company_approve = array();
            $query = "SELECT serverid FROM #__js_job_companies WHERE id = " . $company_id;
            $db->setQuery($query);
            $servercompanyid = $db->loadResult();
            $data_company_approve['id'] = $servercompanyid;
            $data_company_approve['company_id'] = $company_id;
            $data_company_approve['authkey'] = $this->_client_auth_key;
            $fortask = "companyapprove";
            $server_json_data_array = json_encode($data_company_approve);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logCompanyApprove($return_value);
        }
        return true;
    }

    function companyReject($company_id) {
        if (is_numeric($company_id) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_companies SET status = -1 WHERE id = " . $company_id;
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        $company_reject_email = $this->getJSModel('emailtemplate')->sendMail(1, -1, $company_id);
        if ($this->_client_auth_key != "") {
            $data_company_reject = array();
            $query = "SELECT serverid FROM #__js_job_companies WHERE id = " . $company_id;
            $db->setQuery($query);
            $servercompanyid = $db->loadResult();
            $data_company_reject['id'] = $servercompanyid;
            $data_company_reject['company_id'] = $company_id;
            $data_company_reject['authkey'] = $this->_client_auth_key;
            $fortask = "companyreject";
            $server_json_data_array = json_encode($data_company_reject);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logCompanyReject($return_value);
        }
        return true;
    }

    function getCompanies($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = Factory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` WHERE status = 1 ORDER BY name ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $companies = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $companies[] = array('value' => $row->id, 'text' => $row->name);
            }
        } else {
            $companies[] = array('value' => '', 'text' => '');
        }
        return $companies;
    }

    function getIfSampleData() {

        $db = Factory::getDBO();
        $query = "SELECT count(id) FROM `#__js_job_companies`";
        $db->setQuery($query);
        $data = $db->loadResult();
        return $data;
    }

    function getCompaniesbyJobId($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT company.id, company.name
                FROM `#__js_job_companies` AS company
                JOIN `#__js_job_jobs` AS job ON company.uid = job.uid
                WHERE job.id = " . $jobid . " ORDER BY name ASC ";
        try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                return false;
            }
        $companies = array();
        foreach ($rows as $row) {
            $companies[] = array('value' => Text::_($row->id),
                'text' => Text::_($row->name));
        }
        return $companies;
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

    function updateCompanyDataForEraseDataRequest($companyid){
        if(!is_numeric($companyid) || $companyid == 0)
            return false;
        $db = Factory::getDbo();
        $query = "UPDATE `#__js_job_companies` SET `name`='-----' , `url`='-----',`logofilename`='',`logo`='',`smalllogofilename`='',`smalllogo`='',`aboutcompanyfilename`='',`aboutcompanyisfile`='0',`aboutcompany`='',`contactname`='',`contactphone`='',`companyfax`='',`contactemail`='-----',`since`='1970-01-01',`companysize`='',`income`='',`description`='',`country`='',`state`='',`county`='',`city`='',`address1`='-----',`address2`='-----',`hits`=0,`params`='-----' , `status` = 0 WHERE id = " . $companyid;
        $db->setQuery($query);
        $error = array();
        if (!$db->execute()) {
            $err = $this->setError($row->getError());
            $error[] = $err;
        }
        // compant attachments.
        if(empty($error)){
            $datadirectory = $this->getJSModel('configuration')->getConfigValue('data_directory');
            $mainpath = JPATH_BASE;
            if(Factory::getApplication()->isClient('administrator')){
                $mainpath = substr($mainpath, 0, strlen($mainpath) - 14); //remove administrator
            }
            $mainpath = $mainpath .'/'.$datadirectory.'/data';
            $mainpath = $mainpath . '/employer/';
            $folder = $mainpath . 'comp_'.$companyid.'';
            if(file_exists($folder)){
                $logo = $mainpath . '/comp_'.$companyid.'/logo/';
                if(file_exists($logo)){
                    $path = $logo . '*.*';
                    $files = glob($path);
                    array_map('unlink', $files);//deleting files
                    rmdir($logo);
                }
                rmdir($folder);
            }
            return true;
        }
        return false;
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
