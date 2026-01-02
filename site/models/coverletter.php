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

class JSJobsModelCoverletter extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_client_auth_key = $client_auth_key;
        $this->_siteurl = Uri::root();

        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function deleteCoverLetter($coverletterid, $uid) {
        $db = $this->getDBO();
        $row = $this->getTable('coverletter');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($coverletterid) == false)
            return false;
        $server_coverletter_id = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT letter.serverid AS id FROM `#__js_job_coverletters` AS letter 
						WHERE letter.id = " . $coverletterid;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $s_c_l_id = $db->loadResult();
            $server_coverletter_id = $s_c_l_id;
        }
        $query = "SELECT COUNT(letter.id) FROM `#__js_job_coverletters` AS letter WHERE letter.id = " . $coverletterid . " AND letter.uid = " . $uid;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0) { // this search is same user
            
            $query = "SELECT COUNT(jobapply.id) FROM `#__js_job_jobapply` AS jobapply WHERE jobapply.coverletterid = " . $coverletterid;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $total = $db->loadResult();
            if($total > 0){ // Cover letter in use
                return 3;
            }
            if (!$row->delete($coverletterid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            if ($server_coverletter_id != 0) {
                $data = array();
                $data['id'] = $server_coverletter_id;
                $data['referenceid'] = $coverletterid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletecoverletter';
                $jsjobsharingobject = $this->getJSModel('jobsharingsite');
                $return_value = $jsjobsharingobject->delete_CoverletterSharing($data);
                $job_log_object = $this->getJSModel('log');
                $job_log_object->log_Delete_CoverletterSharing($return_value);
            }
        } else
            return 2;

        return true;
    }

    function getCoverLetterbyId($id, $u_id) {
        $db = $this->getDBO();
        if ($u_id)
            if ($u_id)
                if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                    return false;

        if (($id != '') && ($id != 0)) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT id,title,description,created FROM `#__js_job_coverletters` WHERE id = " . $id;
            $db->setQuery($query);
            $this->_application = $db->loadObject();
            $result[0] = $this->_application;
        }
        if ($id) { // not new
            if (!defined('VALIDATE')) {
                define('VALIDATE', 'VALIDATE');
            }
            $result[4] = VALIDATE;
        } else { // new
            if (isset($u_id)) {
                if (is_numeric($u_id) == false)
                    return false;
                $result[4] = $this->getJSModel('permissions')->checkPermissionsFor("ADD_COVER_LETTER");
            }
        }
        return $result;
    }

    function getCoverLetterForAppliedJob($id) {
        $db = $this->getDBO();
        if (empty($id))
            return false;
        if (!is_numeric($id))
            return false;


        $query = "SELECT title, description FROM `#__js_job_coverletters` WHERE id = " . $id;
        $db->setQuery($query);
        $cletter[0] = $db->loadObject();
        //for jobi template 
        if(Factory::getApplication()->getTemplate() == 'jobi'){
            $result = '<div class="jsjobi-popup-cnt">
                        <div class="jsjobi-popup-heading">
                            <h6 class="jsjobi-popup-heading-text">' . Text::_('Cover Letter') . '</h6>
                            <a href="#" class="jsjobi-popup-close-btn" onclick="Javascript: closeCoverletter();">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        <div class="jsjobi-popup-body">
                            <div class="jsjobi-popup-cnt-title">' . $cletter[0]->title . '</div>
                            <div class="jsjobi-popup-cnt-desc">' . $cletter[0]->description . '</div>
                            <div class="jsjobi-popup-btn-wrp">
                                <input class="jsjobi-popup-btn jsjobi-popup-save-btn" type="button" onclick="Javascript: closeCoverletter();" value="' . Text::_("Close") . '" />
                            </div>
                        </div>
                    </div>
            ';
        } else {
            $result = '<div id="coverletter_headline">' . Text::_('Cover Letter') . '<img id="jspopup_image_close" onclick="Javascript: closeCoverletter();" src="'.Uri::root().'components/com_jsjobs/images/popup-close.png" /></div>
                        <div id="coverletter_headline_bottom_area">
                            <div id="coverletter_title">' . $cletter[0]->title . '</div>
                            <div id="coverletter_description">' . $cletter[0]->description . '</div>
                        </div>
                        <div class="fieldwrapper_fullwidth_button">
                            <input class="cletter_popup_button" type="button" onclick="Javascript: closeCoverletter();" value="' . Text::_("Close") . '" />
                        </div>
            ';
        }
        return $result;
    }

    function getMyCoverLettersbyUid($u_id, $limit, $limitstart) {
        $db = $this->getDBO();
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return false;
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_coverletters` WHERE uid  = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT  letter.id,letter.title,letter.description,letter.created ,CONCAT(letter.alias,'-',letter.id) aliasid 
                    FROM `#__js_job_coverletters` AS letter
                    WHERE letter.uid  = " . $u_id;
        $db->setQuery($query);
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function canAddNewCoverLetter($uid) {
        if (!is_numeric($uid))
            return false;
        $db = $this->getDBO();
        $query = "SELECT package.id AS packageid, package.coverlettersallow, package.packageexpireindays, payment.id AS paymentid, payment.created
                    FROM `#__js_job_jobseekerpackages` AS package
                    JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
                    WHERE payment.uid = " . $uid . "
                    AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                    AND payment.transactionverified = 1 AND payment.status = 1";
        $db->setQuery($query);
        $valid_packages = $db->loadObjectList();
        if (empty($valid_packages)) { // User have no valid package
            // check if user have any package or not
            $query = "SELECT package.id, package.resumeallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                        , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                       FROM `#__js_job_jobseekerpackages` AS package
                       JOIN `#__js_job_paymenthistory` AS payment ON ( payment.packageid = package.id AND payment.packagefor=2)
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
            // check is it allow to add new cover letter
            $unlimited = 0;
            $coverletterallow = 0;
            $valid_packages_ids = array();
            foreach ($valid_packages AS $coverletter) {
                if ($unlimited == 0) {
                    if ($coverletter->coverlettersallow != -1) {
                        $valid_packages_ids[] = $coverletter->packageid;
                        $coverletterallow = $coverletter->coverlettersallow + $coverletterallow;
                    } else {
                        $unlimited = 1;
                    }
                }
            }
            if ($unlimited == 0) { // user doesn't have unlimited resume package
                if ($coverletterallow == 0) {
                    return COVER_LETTER_LIMIT_EXCEEDS;
                }
                //get total cover letter count
                $valid_packages_ids = implode(', ', $valid_packages_ids);
                $query = "SELECT COUNT(coverletter.id) AS totalcoverletters
				FROM `#__js_job_coverletters` AS coverletter
				WHERE coverletter.packageid IN (".$valid_packages_ids.") AND coverletter.uid = " . $uid;
                $db->setQuery($query);
                $totalcoverletter = $db->loadResult();
                if ($coverletterallow <= $totalcoverletter) {
                    return COVER_LETTER_LIMIT_EXCEEDS;
                } else {
                    return VALIDATE;
                }
            } else { // user have unlimited cover letter package
                return VALIDATE;
            }
        }
    }

    function getMyCoverLetters($u_id) {

        $db = $this->getDBO();
        if ($u_id)
            if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                return false;

        $totalcoverletters = 0;

        $query = "SELECT id, title
		FROM `#__js_job_coverletters` WHERE uid = " . $u_id;

        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $resumes = array();
        foreach ($rows as $row) {
            $resumes[] = array('value' => $row->id, 'text' => $row->title);
            $totalcoverletters++;
        }

        $mycoverletters = HTMLHelper::_('select.genericList', $resumes, 'coverletterid', 'class="inputbox required jsjobi-popup-select-field" ' . '', 'value', 'text', '');
        $result[0] = $mycoverletters;
        $result[1] = $totalcoverletters;
        return $result;
    }

    function storeCoverLetter() {
        Factory::getSession()->checkToken() or die( 'Invalid Token' );
        global $resumedata;
        $row = $this->getTable('coverletter');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string

        if (!empty($data['alias']))
            $c_l_alias = $this->getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $c_l_alias = $this->getJSModel('common')->removeSpecialCharacter($data['title']);

        $c_l_alias = strtolower(str_replace(' ', '-', $c_l_alias));
        $data['alias'] = $c_l_alias;

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
        if ($this->_client_auth_key != "") {
            $db = $this->getDBO();
            $query = "SELECT cvletter.* FROM `#__js_job_coverletters` AS cvletter  
						WHERE cvletter.id = " . $row->id;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $data_cvletter = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_cvletter->id = $data['id']; // for edit case
            $data_cvletter->coverletter_id = $row->id;
            $data_cvletter->authkey = $this->_client_auth_key;
            $data_cvletter->task = 'storecoverletter';
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $return_value = $jsjobsharingobject->store_CoverLetterSharing($data_cvletter);
            $job_log_object = $this->getJSModel('log');
            $job_log_object->log_Store_CoverLetterSharing($return_value);
        }
        return true;
    }
}
?>
