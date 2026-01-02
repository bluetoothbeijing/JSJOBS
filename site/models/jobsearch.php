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

class JSJobsModelJobSearch extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_config = null;
    var $_searchoptions = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function deleteJobSearch($searchid, $uid) {
        $db = $this->getDBO();
        $row = $this->getTable('jobsearch');

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($searchid) == false)
            return false;

        $query = "SELECT COUNT(search.id) FROM `#__js_job_jobsearches` AS search WHERE search.id = " . $searchid . " AND search.uid = " . $uid;
        $db->setQuery($query);
        $searchtotal = $db->loadResult();

        if ($searchtotal > 0) { // this search is same user
            if (!$row->delete($searchid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
        } else
            return 2;

        return true;
    }

    function getMyJobSearchesbyUid($u_id, $limit, $limitstart) {
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return false;
        $db = $this->getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_jobsearches` WHERE uid  = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT search.* FROM `#__js_job_jobsearches` AS search WHERE search.uid  = " . $u_id;
        $db->setQuery($query);
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;

        return $result;
    }

    function getJobSearchebyId($id) {
        $db = $this->getDBO();
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT search.* FROM `#__js_job_jobsearches` AS search WHERE search.id  = " . $id;
        $db->setQuery($query);
        return $db->loadObject();
    }

    function storeJobSearch($data) {
        //$data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        global $resumedata;
        $row = $this->getTable('jobsearch');

        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        $returnvalue = $this->canAddNewJobSearch($data['uid']);
        if ($returnvalue == 0)
            return 3; //not allowed save new search
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        return true;
    }

    function canAddNewJobSearch($uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = $this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.savejobsearch
			FROM `#__js_job_paymenthistory` AS payment
			JOIN `#__js_job_jobseekerpackages` AS package ON package.id = payment.packageid
			WHERE payment.uid = " . $uid . " AND payment.packagefor = 2";
            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            if ($jobs) {
                foreach ($jobs AS $job) {
                    if ($job->savejobsearch == 1)
                        return 1;
                }
                return 0;
            }
            return 0;
        }
    }

    function getSearchOptions($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = $this->getDBO();

        $searchjobconfig = $this->getJSModel('configurations')->getConfigByFor('searchjob');

        if (!$this->_searchoptions) {
            $this->_searchoptions = array();

            $radiustype = array('0' => array('value' => 'm','text' => Text::_('Meters')), '1' => array('value' => 'km','text' => Text::_('Kilometers')), '2' => array('value' => 'mile','text' => Text::_('Miles')), '3' => array('value' => 'nacmiles','text' => Text::_('Nautical Miles')), );
    
            $defaultCategory = $this->getJSModel('common')->getDefaultValue('categories');
            $job_subcategories = $this->getJSModel('subcategory')->getSubCategoriesforCombo($defaultCategory,'', '');
            //for jobi template 
            if(Factory::getApplication()->getTemplate() == 'jobi'){
                $this->_searchoptions['jobsubcategory'] = HTMLHelper::_('select.genericList', $job_subcategories, 'jobsubcategory[]', 'class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"' . '', 'value', 'text', '');
                $this->_searchoptions['company'] = HTMLHelper::_('select.genericList', $this->getJSModel('company')->getAllCompanies(''), 'company[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['category'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(''), 'category[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(''), 'careerlevel[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender','class="inputbox jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(''), 'workpermit[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(Text::_('Currency')), 'currencyid','class="inputbox sal jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['srangestart'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRangeFromTo(Text::_('From'), 1), 'srangestart','class="inputbox sal jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['srangeend'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRangeFromTo(Text::_('To'),2), 'srangeend','class="inputbox sal jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['srangetype'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(Text::_('Type')), 'srangetype','class="inputbox sal jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'education[]','class="inputbox jsjob-multiselect jsjobi-form-select-field" multiple="true"','value','text','');
                $this->_searchoptions['experiencemin'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'experiencemin','class="inputbox exp jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['experiencemax'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'experiencemax','class="inputbox exp jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel','class="inputbox jsjobi-form-select-field"','value','text','');
                $this->_searchoptions['radiuslengthtype'] = HTMLHelper::_('select.genericList', $radiustype, 'radiuslengthtype','class="inputbox jsjobi-form-select-field"','value','text','');
            }else {
                $this->_searchoptions['jobsubcategory'] = HTMLHelper::_('select.genericList', $job_subcategories, 'jobsubcategory[]', 'class="inputbox jsjob-multiselect" multiple="true"' . '', 'value', 'text', '');
                $this->_searchoptions['company'] = HTMLHelper::_('select.genericList', $this->getJSModel('company')->getAllCompanies(''), 'company[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['category'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(''), 'category[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(''), 'careerlevel[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender','class="inputbox"','value','text','');
                $this->_searchoptions['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(''), 'workpermit[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(Text::_('Currency')), 'currencyid','class="inputbox sal"','value','text','');
                $this->_searchoptions['srangestart'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRangeFromTo(Text::_('From'), 1), 'srangestart','class="inputbox sal"','value','text','');
                $this->_searchoptions['srangeend'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRangeFromTo(Text::_('To'),2), 'srangeend','class="inputbox sal"','value','text','');
                $this->_searchoptions['srangetype'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(Text::_('Type')), 'srangetype','class="inputbox sal"','value','text','');
                $this->_searchoptions['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'education[]','class="inputbox jsjob-multiselect" multiple="true"','value','text','');
                $this->_searchoptions['experiencemin'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'experiencemin','class="inputbox exp"','value','text','');
                $this->_searchoptions['experiencemax'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'experiencemax','class="inputbox exp"','value','text','');
                $this->_searchoptions['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel','class="inputbox"','value','text','');
                $this->_searchoptions['radiuslengthtype'] = HTMLHelper::_('select.genericList', $radiustype, 'radiuslengthtype','class="inputbox"','value','text','');
            }

            $result = array();
            $result[0] = $this->_searchoptions;
            $result[1] = $searchjobconfig;
        }

        return $result;
    }

    function canJobSearch($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == ''))
                return false;
        $db = $this->getDbo();
        if ($uid == 0) { //guest
            $canview = 1;
        } else {
            $query = "SELECT package.jobsearch, package.packageexpireindays, payment.created
                    FROM `#__js_job_jobseekerpackages` AS package
                    JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
                    WHERE payment.uid = " . $uid . "
                    AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                    AND payment.transactionverified = 1 AND payment.status = 1";
            
            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            $canview = 0;
            if (empty($jobs))
                $canview = 1; // for those who not get any role

            foreach ($jobs AS $job) {
                if ($job->jobsearch == 1) {
                    $canview = 1;
                    break;
                } else {
                    $canview = 0;
                }
            }
        }
        if ($canview == 1) {
            return VALIDATE;
        } else {
            return JOB_SEARCH_NOT_ALLOWED_IN_PACKAGE;
        }
    }

}
?>
    
