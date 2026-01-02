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

class JSJobsModelJob extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_application = null;
    var $_searchoptions = null;
    var $_job = null;
    var $_job_editor = null;
    var $_defaultcountry = null;
    var $_ai_job_data_array = [];

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getTopJobs() {
        $db = Factory::getDBO();
        $result = array();
        $query = "SELECT job.id,job.title AS jobtitle,company.name AS companyname,cat.cat_title AS cattile,job.stoppublishing,
        salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto,currency.symbol 
        FROM `#__js_job_jobs` AS job
        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
        JOIN `#__js_job_companies` AS company ON job.companyid = company.id
        LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid ORDER BY job.created desc LIMIT 5";
        $db->setQuery($query);
        $jobs = $db->loadObjectList();
        return $jobs;
    }

    function getMultiCityData($jobid) {
        if (!is_numeric($jobid))
            return false;
        $db = $this->getDBO();
        $query = "select mjob.*,city.id AS cityid,city.cityName AS cityname ,state.name AS statename,country.name AS countryname
                from #__js_job_jobcities AS mjob
                LEFT join #__js_job_cities AS city on mjob.cityid=city.id  
                LEFT join #__js_job_states AS state on city.stateid=state.id  
                LEFT join #__js_job_countries AS country on city.countryid=country.id 
                WHERE mjob.jobid=" . $jobid;
        $db->setQuery($query);
        $data = $db->loadObjectList();
        if (is_array($data) AND ! empty($data)) {
            $i = 0;
            $multicitydata = "";
            foreach ($data AS $multicity) {
                $last_index = count($data) - 1;
                if ($i == $last_index)
                    $multicitydata.= Text::_($multicity->cityname);
                else
                    $multicitydata.= Text::_($multicity->cityname) . " ,";
                $i++;
            }
            if ($multicitydata != "") {
                $mc = Text::_('Multi City');
                $multicity = (strlen($multicitydata) > 35) ? $mc . substr($multicitydata, 0, 35) . '...' : $multicitydata;
                return $multicity;
            } else
                return;
        }
    }

    function getJobbyIdForView($job_id) {
        $db = $this->getDBO();
        if (is_numeric($job_id) == false)
            return false;

        $query = "SELECT job.*, cat.cat_title , company.name as companyname,company.logofilename as companylogo,jobtype.title AS jobtypetitle
                , jobstatus.title AS jobstatustitle, shift.title as shifttitle
                , department.name AS departmentname
                , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype
                , education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
                , experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
                ,currency.symbol,subcat.title as subcategory,agefrom.title AS agefromtitle,ageto.title AS agetotitle,workpermit.name as workpermitcountry
                
        FROM `#__js_job_jobs` AS job
        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
        JOIN `#__js_job_companies` AS company ON job.companyid = company.id
        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
        LEFT JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
        LEFT JOIN `#__js_job_departments` AS department ON job.departmentid = department.id
        LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
        LEFT JOIN `#__js_job_heighesteducation` AS education ON job.educationid = education.id
        LEFT JOIN `#__js_job_heighesteducation` AS mineducation ON job.mineducationrange = mineducation.id
        LEFT JOIN `#__js_job_heighesteducation` AS maxeducation ON job.maxeducationrange = maxeducation.id
        LEFT JOIN `#__js_job_experiences` AS experience ON job.experienceid = experience.id
        LEFT JOIN `#__js_job_experiences` AS minexperience ON job.minexperiencerange = minexperience.id
        LEFT JOIN `#__js_job_experiences` AS maxexperience ON job.maxexperiencerange = maxexperience.id
        LEFT JOIN `#__js_job_shifts` AS shift ON job.shift = shift.id
        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid    
        LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
        LEFT JOIN `#__js_job_ages` AS agefrom ON agefrom.id = job.agefrom
        LEFT JOIN `#__js_job_ages` AS ageto ON ageto.id = job.ageto
        LEFT JOIN `#__js_job_countries` AS workpermit ON workpermit.id = job.workpermit
        WHERE  job.id = " . $job_id;
        $db->setQuery($query);
        $this->_application = $db->loadObject();
        $this->_application->multicity = $this->getJSModel('jsjobs')->getMultiCityDataForView($job_id, 1);

        $result[0] = $this->_application;
        $result[2] = '';
        $result[3] = $this->getJSModel('fieldordering')->getFieldsOrderingforForm(2); // company fields

        return $result;
    }

    function getJobbyId($c_id, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($c_id) == false)
            return false;
        $fieldsordering = $this->getJSModel('fieldordering')->getFieldsOrderingforForm(2); // job fields       
        $company_required = '';
        $department_required = '';
        $cat_required = '';
        $subcategory_required = '';
        $jobtype_required = '';
        $jobstatus_required = '';
        $education_required = '';
        $jobshift_required = '';
        $jobsalaryrange_required = '';
        $experience_required = '';
        $age_required = '';
        $gender_required = '';
        $careerlevel_required = '';
        $workpermit_required = '';
        $requiredtravel_required = '';
        $sendemail_required = '';
        foreach ($fieldsordering AS $fo) {
            switch ($fo->field) {
                case "company":
                    $company_required = ($fo->required ? 'required' : '');
                    break;
                case "department":
                    $department_required = ($fo->required ? 'required' : '');
                    break;
                case "jobcategory":
                    $cat_required = ($fo->required ? 'required' : '');
                    break;
                case "subcategory":
                    $subcategory_required = ($fo->required ? 'required' : '');
                    break;
                case "jobtype":
                    $jobtype_required = ($fo->required ? 'required' : '');
                    break;
                case "jobstatus":
                    $jobstatus_required = ($fo->required ? 'required' : '');
                    break;
                case "heighesteducation":
                    $education_required = ($fo->required ? 'required' : '');
                    break;
                case "jobshift":
                    $jobshift_required = ($fo->required ? 'required' : '');
                    break;
                case "jobsalaryrange":
                    $jobsalaryrange_required = ($fo->required ? 'required' : '');
                    break;
                case "experience":
                    $experience_required = ($fo->required ? 'required' : '');
                    break;
                case "age":
                    $age_required = ($fo->required ? 'required' : '');
                    break;
                case "gender":
                    $gender_required = ($fo->required ? 'required' : '');
                    break;
                case "careerlevel":
                    $careerlevel_required = ($fo->required ? 'required' : '');
                    break;
                case "workpermit":
                    $workpermit_required = ($fo->required ? 'required' : '');
                    break;
                case "requiredtravel":
                    $requiredtravel_required = ($fo->required ? 'required' : '');
                    break;
                case "sendemail":
                    $sendemail_required = ($fo->required ? 'required' : '');
                    break;
            }
        }
        $db = Factory::getDBO();

        $query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
            FROM `#__js_job_jobs` AS job
            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
            LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
            LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid 
            WHERE job.id = " . $c_id;

        $db->setQuery($query);
        $this->_job = $db->loadObject();

        $status = array(
            '0' => array('value' => 0, 'text' => Text::_('Pending')),
            '1' => array('value' => 1, 'text' => Text::_('Approve')),
            '2' => array('value' => -1, 'text' => Text::_('Reject')),);
        $companies = $this->getJSModel('company')->getCompanies($uid);
        $departments = $this->getJSModel('department')->getDepartment($uid);
        $categories = $this->getJSModel('category')->getCategories(Text::_('Select Category'), '');

        if (isset($this->_job)) {

            $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox ' . $company_required . '" ' . 'onChange="getdepartments(\'departmentid\', this.value)"', 'value', 'text', $this->_job->companyid);
            $lists['departments'] = HTMLHelper::_('select.genericList', $this->getJSModel('department')->getDepartmentsByCompanyId($this->_job->companyid, ''), 'departmentid', 'class="inputbox ' . $department_required . '" ' . '', 'value', 'text', $this->_job->departmentid);
            $lists['jobcategory'] = HTMLHelper::_('select.genericList', $categories, 'jobcategory', 'class="inputbox ' . $cat_required . '"' . 'onChange="fj_getsubcategories(\'subcategoryid\', this.value)"', 'value', 'text', $this->_job->jobcategory);
            $lists['subcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('subcategory')->getSubCategoriesforCombo($this->_job->jobcategory, Text::_('Sub Category'), ''), 'subcategoryid', 'class="inputbox ' . $subcategory_required . '"' . '', 'value', 'text', $this->_job->subcategoryid);
            $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype', 'class="inputbox ' . $jobtype_required . '"' . '', 'value', 'text', $this->_job->jobtype);
            $lists['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus', 'class="inputbox ' . $jobstatus_required . '"' . '', 'value', 'text', $this->_job->jobstatus);
            $lists['educationminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', $this->_job->educationminimax);
            $lists['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'educationid', 'class="inputbox ' . $education_required . '"' . '', 'value', 'text', $this->_job->educationid);
            $lists['minimumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Minimum')), 'mineducationrange', 'class="inputbox ' . $education_required . '"' . '', 'value', 'text', $this->_job->mineducationrange);
            $lists['maximumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Maximum')), 'maxeducationrange', 'class="inputbox ' . $education_required . '"' . '', 'value', 'text', $this->_job->maxeducationrange);

            $lists['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift', 'class="inputbox ' . $jobshift_required . '"' . '', 'value', 'text', $this->_job->shift);
            $lists['salaryrangefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getSalaryRange(Text::_('From')), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom ' . $jobsalaryrange_required . '"' . '', 'value', 'text', $this->_job->salaryrangefrom);
            $lists['salaryrangeto'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getSalaryRange(Text::_('To')), 'salaryrangeto', 'class="inputbox validate-salaryrangeto ' . $jobsalaryrange_required . '" ' . '', 'value', 'text', $this->_job->salaryrangeto);
            $lists['salaryrangetypes'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox " ' . '', 'value', 'text', $this->_job->salaryrangetype);

            $lists['experienceminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', $this->_job->experienceminimax);
            $lists['experience'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Select')), 'experienceid', 'class="inputbox ' . $experience_required . '" ' . '', 'value', 'text', $this->_job->experienceid);
            $lists['minimumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'minexperiencerange', 'class="inputbox ' . $experience_required . '"' . '', 'value', 'text', $this->_job->minexperiencerange);
            $lists['maximumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'maxexperiencerange', 'class="inputbox ' . $experience_required . '"' . '', 'value', 'text', $this->_job->maxexperiencerange);

            $lists['agefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('age')->getAges(Text::_('From')), 'agefrom', 'class="inputbox validate-checkagefrom ' . $age_required . '"' . '', 'value', 'text', $this->_job->agefrom);
            $lists['ageto'] = HTMLHelper::_('select.genericList', $this->getJSModel('age')->getAges(Text::_('To')), 'ageto', 'class="inputbox validate-checkageto ' . $age_required . '"' . '', 'value', 'text', $this->_job->ageto);

            $lists['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender', 'class="inputbox ' . $gender_required . '"' . '', 'value', 'text', $this->_job->gender);

            $lists['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevels(Text::_('Select')), 'careerlevel', 'class="inputbox ' . $careerlevel_required . '"' . '', 'value', 'text', $this->_job->careerlevel);
            $lists['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('country')->getCountries(Text::_('Select')), 'workpermit', 'class="inputbox ' . $workpermit_required . '" ' . '', 'value', 'text', $this->_job->workpermit);
            $lists['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel', 'class="inputbox ' . $requiredtravel_required . '" ' . '', 'value', 'text', $this->_job->requiredtravel);

            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->status);
            $lists['sendemail'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getSendEmail(), 'sendemail', 'class="inputbox ' . $sendemail_required . '" ' . '', 'value', 'text', $this->_job->sendemail);
            $lists['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox " ' . '', 'value', 'text', $this->_job->currencyid);
            $multi_lists = $this->getJSModel('common')->getMultiSelectEdit($this->_job->id, 1);
        } else {

            $defaultCategory = $this->getJSModel('common')->getDefaultValue('categories');
            $defaultJobtype = $this->getJSModel('common')->getDefaultValue('jobtypes');
            $defaultJobstatus = $this->getJSModel('common')->getDefaultValue('jobstatus');
            $defaultShifts = $this->getJSModel('common')->getDefaultValue('shifts');
            $defaultEducation = $this->getJSModel('common')->getDefaultValue('heighesteducation');
            $defaultSalaryrange = $this->getJSModel('common')->getDefaultValue('salaryrange');
            $defaultSalaryrangeType = $this->getJSModel('common')->getDefaultValue('salaryrangetypes');
            $defaultAge = $this->getJSModel('common')->getDefaultValue('ages');
            $defaultExperiences = $this->getJSModel('common')->getDefaultValue('experiences');
            $defaultCareerlevels = $this->getJSModel('common')->getDefaultValue('careerlevels');
            $defaultCurrencies = $this->getJSModel('common')->getDefaultValue('currencies');


            if (!isset($this->_config)) {
                $this->_config = $this->getJSModel('configuration')->getConfig();
            }
            $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox ' . $company_required . '" ' . 'onChange="getdepartments(\'departmentid\', this.value)"' . '', 'value', 'text', '');
            if (isset($companies[0]['value']))
                $lists['departments'] = HTMLHelper::_('select.genericList', $this->getJSModel('department')->getDepartmentsByCompanyId($companies[0]['value'], ''), 'departmentid', 'class="inputbox ' . $department_required . '"' . '', 'value', 'text', '');
            $lists['jobcategory'] = HTMLHelper::_('select.genericList', $categories, 'jobcategory', 'class="inputbox ' . $cat_required . '"' . 'onChange="fj_getsubcategories(\'subcategoryid\', this.value)"', 'value', 'text', $defaultCategory);
            $lists['subcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('subcategory')->getSubCategoriesforCombo($defaultCategory, Text::_('Sub Category'), ''), 'subcategoryid', 'class="inputbox ' . $subcategory_required . '"' . '', 'value', 'text', '');
            $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype', 'class="inputbox ' . $jobtype_required . '"' . '', 'value', 'text', $defaultJobtype);
            $lists['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus', 'class="inputbox ' . $jobstatus_required . '"' . '', 'value', 'text', $defaultJobstatus);

            $lists['educationminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'educationid', 'class="inputbox ' . $education_required . '"' . '', 'value', 'text', $defaultEducation);
            $lists['minimumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Minimum')), 'mineducationrange', 'class="inputbox ' . $education_required . '"' . '', 'value', 'text', $defaultEducation);
            $lists['maximumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Maximum')), 'maxeducationrange', 'class="inputbox ' . $education_required . '"' . '', 'value', 'text', $defaultEducation);
            $lists['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift', 'class="inputbox ' . $jobshift_required . '"' . '', 'value', 'text', $defaultShifts);

            $lists['salaryrangefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getSalaryRange(Text::_('From')), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom ' . $jobsalaryrange_required . '" ' . '', 'value', 'text', $defaultSalaryrange);
            $lists['salaryrangeto'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getSalaryRange(Text::_('To')), 'salaryrangeto', 'class="inputbox validate-salaryrangeto ' . $jobsalaryrange_required . '" ' . '', 'value', 'text', $defaultSalaryrange);
            $lists['salaryrangetypes'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', $defaultSalaryrangeType);


            $lists['experienceminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['experience'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Select')), 'experienceid', 'class="inputbox ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);
            $lists['minimumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'minexperiencerange', 'class="inputbox ' . $experience_required . '"' . '', 'value', 'text', $defaultExperiences);
            $lists['maximumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'maxexperiencerange', 'class="inputbox ' . $experience_required . '"' . '', 'value', 'text', $defaultExperiences);

            $lists['agefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('age')->getAges(Text::_('From')), 'agefrom', 'class="inputbox validate-checkagefrom ' . $age_required . '"' . '', 'value', 'text', $defaultAge);
            $lists['ageto'] = HTMLHelper::_('select.genericList', $this->getJSModel('age')->getAges(Text::_('To')), 'ageto', 'class="inputbox validate-checkageto ' . $age_required . '"' . '', 'value', 'text', $defaultAge);

            $lists['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender', 'class="inputbox ' . $gender_required . '" ' . '', 'value', 'text', '');
            $lists['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevels(Text::_('Select')), 'careerlevel', 'class="inputbox ' . $careerlevel_required . '"' . '', 'value', 'text', $defaultCareerlevels);
            $lists['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('country')->getCountries(Text::_('Select')), 'workpermit', 'class="inputbox ' . $workpermit_required . '" ' . '', 'value', 'text', $this->_defaultcountry);
            $lists['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel', 'class="inputbox ' . $requiredtravel_required . '"' . '', 'value', 'text', '');

            $lists['status'] = HTMLHelper::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', 1);
            $lists['sendemail'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getSendEmail(), 'sendemail', 'class="inputbox ' . $sendemail_required . '"' . '', 'value', 'text', '$this->_job->sendemail', '');
            $lists['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox " ' . '', 'value', 'text', $defaultCurrencies);
        }

        $result[0] = $this->_job;
        $result[1] = $lists;
        $result[2] = $this->getJSModel('fieldordering')->getFieldsOrderingforView(2); // job fields, refid
        $result[3] = $fieldsordering;
        if (isset($multi_lists) && $multi_lists != "")
            $result[4] = $multi_lists;
        return $result;
    }

    function aiJobSearchQuery($aijobsearch, $limitstart, $limit){
        $db = Factory::getDbo();
        $app = Factory::getApplication();
        $cache = Factory::getCache('com_jsjobs', '');

        $pagenum = ($limit > 0) ? floor($limitstart / $limit) + 1 : 1;

        //$offset = $pagenum * $limit;
        $offset = $limitstart;

        $curdate = Factory::getDate()->toSql();
        $job_ids_list = '';

        $unique_id = getAIFunctionsClass()->getUniqueIdForCache();
        if ($pagenum >= 1) {
            $job_ids_list = $cache->get('ai_suggested_jobs_list_' . $unique_id);
        }

        // if (empty($job_ids_list) && !empty($aijobsearch)) {
        //     $aijobsearch = $db->escape(trim($aijobsearch));
        //     $words = array_filter(explode(' ', $aijobsearch));
        //     $wordCount = count($words);

        //     $query_parts = [];

        //     if ($wordCount < 4) {
        //         $query_parts[] = "SELECT job.id AS id,job.id AS jobid, job.title, 999 AS score FROM #__js_job_jobs AS job
        //             WHERE job.status = 1
        //             AND job.aijobsearchtext LIKE '%$aijobsearch%'";
        //     }

        //     $query_parts[] = "SELECT job.id AS id,job.id AS jobid, job.title,
        //         MATCH (job.aijobsearchtext) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE) AS score
        //         FROM #__js_job_jobs AS job
        //         WHERE job.status = 1
        //         AND MATCH (job.aijobsearchtext) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE)";

        //     $query_parts[] = "SELECT job.id AS id,job.id AS jobid, job.title,
        //         MATCH (job.aijobsearchdescription) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE) AS score
        //         FROM #__js_job_jobs AS job
        //         WHERE job.status = 1
        //         AND MATCH (job.aijobsearchdescription) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE)";

        //     $union_query = implode(" UNION ", $query_parts);


        //     $db->setQuery($union_query);
        //     $results = $db->loadObjectList();

        //     $highest_score = 0;
        //     $id_list = [];

        //     foreach ($results as &$result) {
        //         $custom_score = 0;

        //         if (strtolower($aijobsearch) === strtolower(trim($result->title))) {
        //             $custom_score += ($wordCount * 10) + 10;
        //             $result->score = 0;
        //         } elseif ($result->score == 999) {
        //             $custom_score += ($wordCount * 10) + 8;
        //             $result->score = 0;
        //         } else {
        //             for ($i = 0; $i < $wordCount - 1; $i++) {
        //                 $combo = $words[$i] . ' ' . ($words[$i + 1] ?? '');
        //                 if (stripos($result->title, $combo) !== false) {
        //                     $custom_score += 10;
        //                 }
        //             }
        //         }

        //         $result->custom_score = $custom_score;
        //         $highest_score = max($highest_score, $result->score);
        //     }

        //     usort($results, function ($a, $b) {
        //         return ($b->custom_score === $a->custom_score)
        //             ? $b->score <=> $a->score
        //             : $b->custom_score <=> $a->custom_score;
        //     });

        //     $results = getAIFunctionsClass()->applyThresholdOnResults($results, $highest_score,1);

        //     foreach ($results as $job) {
        //         $id_list[] = $job->id;
        //     }


        //     if (!empty($id_list)) {
        //         $cache->store(implode(',', $id_list), 'ai_suggested_jobs_list_' . $unique_id);
        //         $job_ids_list = implode(',', $id_list);
        //     }
        // }

        $job_ids_list = getAIFunctionsClass()->aiJobFeaturesMajorQuery($aijobsearch,2);

        $data = [];
        $total = 0;
        if (!empty($job_ids_list)) {
            // all jobs id list
            $ids = explode(',', $job_ids_list);
            $total = count($ids);

            // jobs to show current page
            $ids = array_slice($ids, $offset, $limit);

            if (!empty($ids)) {
                //$query = $db->getQuery(true);
                $query = "SELECT job.id,job.title,job.created,job.status,job.isgoldjob,job.isfeaturedjob,job.city,job.startpublishing,job.stoppublishing,
                            cat.cat_title, jobtype.title AS jobtypetitle, company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo,
                            (SELECT COUNT(ja.id) FROM `#__js_job_jobapply` AS ja WHERE ja.jobid = job.id ) AS totalresume, salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype, cur.symbol
                            FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                            LEFT JOIN `#__js_job_currencies` AS cur ON job.currencyid=cur.id
                            LEFT JOIN `#__js_job_cities` AS city ON city.id = (SELECT cityid FROM `#__js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                            LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                            LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                    WHERE job.status = 1 AND job.id IN (" . implode(',', array_map('intval', $ids)) . ")";

                $db->setQuery($query);
                $data = $db->loadObjectList();
                foreach($data AS $d){
                    $d->location = $this->getJSModel('city')->getLocationDataForView($d->city);
                }
            }
        }
        $return_array = [];
        $return_array['total'] = $total;
        $return_array['jobs'] = $data;
        return $return_array;
    }


    function getAllJobs($datafor, $companyname, $jobtitle, $jobcategory, $jobtype, $location, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit, $aijobsearcch='') {
        $db = Factory::getDBO();
        $jobs_objects = [];
        $total = 0;
        $lists = array();
        if($aijobsearcch != ''){
            $jobs_array = $this->aiJobSearchQuery($aijobsearcch, $limitstart, $limit);
            $jobs_objects = $jobs_array['jobs'];
            $total = $jobs_array['total'];
            $lists['aijobsearcch'] = $aijobsearcch;
        }else{
            if($js_sortby==1){
                $sortby = " job.title $sortby ";
            }elseif($js_sortby==2){
                $sortby = " jobtype.title $sortby ";
            }elseif($js_sortby==3){
                $sortby = " company.name $sortby ";
            }elseif($js_sortby==4){
                $sortby = " cat.cat_title $sortby ";
            }elseif($js_sortby==5){
                $sortby = " city.cityname $sortby "; //Location
            }elseif($js_sortby==6){
                $sortby = " job.status $sortby ";
            }elseif($js_sortby==7){
                $sortby = " job.created $sortby ";
            }elseif($js_sortby==8){
                $sortby = " job.hits $sortby ";
            }else{
                $sortby = " job.created DESC ";
            }

            $db = Factory::getDBO();
            if($datafor == 1){ // 1 for Jobs, 2 for Jobs queue
                $status_opr = (is_numeric($status)) ? ' = '.$status : ' <> 0 ';
                $fquery = " WHERE job.status".$status_opr;
            }else{ // For Jobs Queue
                $fquery = " WHERE job.status = 0 ";
            }
            if($companyname)
                $fquery .= " AND LOWER(company.name) LIKE ".$db->Quote('%'.$companyname.'%');
            if($jobtitle)
                $fquery .= " AND LOWER(job.title) LIKE ".$db->Quote('%'.$jobtitle.'%');
            if($location)
                $fquery .= " AND LOWER(city.cityName) LIKE ".$db->Quote('%'.$location.'%');
            if ($jobcategory){
                if(is_numeric($jobcategory))
                    $fquery .= " AND job.jobcategory = ".$jobcategory;
            }
            if ($jobtype){
                if(is_numeric($jobtype))
                    $fquery .= " AND job.jobtype = ".$jobtype;
            }

            if($dateto !='' AND $datefrom !=''){
                $fquery .= " AND DATE(job.created) <= ".$db->Quote(date('Y-m-d',strtotime($dateto)))." AND DATE(job.created) >= ".$db->Quote(date('Y-m-d',strtotime($datefrom)));
            }else{
                if($dateto)
                    $fquery .= " AND DATE(job.created) <= ".$db->Quote(date('Y-m-d',strtotime($dateto)));
                if($datefrom)
                    $fquery .= " AND DATE(job.created) >= ".$db->Quote(date('Y-m-d',strtotime($datefrom)));
            }

            $lists = array();
            $lists['companyname'] = $companyname;
            $lists['jobtitle'] = $jobtitle;
            $lists['jobcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(Text::_('Select Category'), ''), 'jobcategory', 'class="inputbox" ', 'value', 'text', $jobcategory);
            $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(Text::_('Select Job Type')), 'jobtype', 'class="inputbox" ', 'value', 'text', $jobtype);
            $lists['location'] = $location;
            $lists['dateto'] = $dateto;
            $lists['datefrom'] = $datefrom;
            if($datafor == 1)
                $lists['status'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getApprove(Text::_('Select Status'),'') , 'status', 'class="inputbox" ' , 'value', 'text', $status);
            else
                $lists['status'] = $status;

            $result = array();
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
                    LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `#__js_job_cities` AS city ON city.id = (SELECT cityid FROM `#__js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1) ";
            $query .= $fquery;
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT job.id,job.title,job.created,job.status,job.isgoldjob,job.isfeaturedjob,job.city,job.startpublishing,job.stoppublishing,
                    cat.cat_title, jobtype.title AS jobtypetitle, company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo,
                    (SELECT COUNT(ja.id) FROM `#__js_job_jobapply` AS ja WHERE ja.jobid = job.id ) AS totalresume, salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype, cur.symbol
                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                    JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                    LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `#__js_job_currencies` AS cur ON job.currencyid=cur.id
                    LEFT JOIN `#__js_job_cities` AS city ON city.id = (SELECT cityid FROM `#__js_job_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                    LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                    LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                    LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id";

            $query .=$fquery;
            $query .= " ORDER BY $sortby ";
            $db->setQuery($query, $limitstart, $limit);
            $this->_application = $db->loadObjectList();

            $jobs = array();
            foreach ($this->_application as $d) {
                $d->location = $this->getJSModel('city')->getLocationDataForView($d->city);
                $jobs[] = $d;
            }
            $this->_application = $jobs;
            $jobs_objects = $this->_application;
        }
        // counter
        $curdate = date('Y-m-d');
        $query = "SELECT COUNT(id) AS activejobs FROM `#__js_job_jobs` WHERE DATE(startpublishing) <= ".$db->Quote($curdate)." AND DATE(stoppublishing) >= ".$db->Quote($curdate)." AND status =1";
        $db->setQuery($query);
        $activejobs = $db->loadResult();

        $query = "SELECT COUNT(id) AS closedjobs FROM `#__js_job_jobs` WHERE DATE(stoppublishing) < ".$db->Quote($curdate)." AND status =1";
        $db->setQuery($query);
        $closedjobs = $db->loadResult();

        $query = "SELECT COUNT(id) AS pendingjobs FROM `#__js_job_jobs` WHERE status !=1";
        $db->setQuery($query);
        $pendingjobs = $db->loadResult();

        $query = "SELECT COUNT(id) AS alljobs FROM `#__js_job_jobs`";
        $db->setQuery($query);
        $alljobs = $db->loadResult();
        $lists['activejobs'] = $activejobs;
        $lists['closedjobs'] = $closedjobs;
        $lists['pendingjobs'] = $pendingjobs;
        $lists['alljobs'] = $alljobs;

        $result[0] = $jobs_objects;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }


    function storeJob() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        jimport('joomla.filesystem.file');
        $row = $this->getTable('job');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $db = $this->getDBO();

        if (isset($this_config) == false)
            $this->_config = $this->getJSModel('configuration')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
            if ($conf->configname == 'system_timeout')
                $systemtimeout = $conf->configvalue;
        }
        if ($dateformat == 'm/d/Y') {
            $arr = explode('/', $data['startpublishing']);
            $data['startpublishing'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
            $arr = explode('/', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
        } elseif ($dateformat == 'd-m-Y' OR $dateformat == 'Y-m-d') {
            $arr = explode('-', $data['startpublishing']);
            $data['startpublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }

        //$data['startpublishing'] = HTMLHelper::_('date',strtotime($data['startpublishing']),"Y-m-d H:i:s" );
        //$data['stoppublishing'] = HTMLHelper::_('date',strtotime($data['stoppublishing']),"Y-m-d H:i:s" );
        $data['startpublishing'] = HTMLHelper::_('date',$data['startpublishing'],"Y-m-d H:i:s" ,true,true );
        $data['stoppublishing'] = HTMLHelper::_('date',$data['stoppublishing'],"Y-m-d H:i:s" ,true,true );
        
        if (!empty($data['alias']))
            $jobalias = $this->getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $jobalias = $this->getJSModel('common')->removeSpecialCharacter($data['title']);

        $jobalias = strtolower(str_replace(' ', '-', $jobalias));
        $data['alias'] = $jobalias;

        $data['description'] = $this->getJSModel('common')->getHtmlInput('description');
        $data['qualifications'] = $this->getJSModel('common')->getHtmlInput('qualifications');
        $data['prefferdskills'] = $this->getJSModel('common')->getHtmlInput('prefferdskills');
        $data['agreement'] = $this->getJSModel('common')->getHtmlInput('agreement');
        $data['uid'] = $this->getJSModel('company')->getUidByCompanyId($data['companyid']); // Uid must be the same as the company owner id
        if ($data['id'] == '') {
            $data['jobid'] = $this->getJobId();
            $data['created'] = date('Y-m-d H:i:s');
        }

        //custom field code start
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = $this->getJSModel('fieldordering')->getUserfieldsfor(2);
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
               $query = "SELECT params FROM `#__js_job_jobs` WHERE id = ".$data['id'];
               $db->setQuery($query);
               $oParams = $db->loadResult();                
               if(!empty($oParams)){
                   $oParams = json_decode($oParams,true);
                   $unpublihsedFields = $this->getJSModel('fieldordering')->getUnpublishedFieldsFor(2);
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

		if($data['uid'] == "") $data['uid'] = 0;
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return 2;
        }

        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
        if (isset($storemulticity) AND ( $storemulticity == false))
            return false;

        // new
        //removing custom field 
        if($customflagfordelete == true){
            foreach ($custom_field_namesfordelete as $key) {
                $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($row->id,$key ,1,2);
            }
        }
        //storing custom field attachments
        if($customflagforadd == true){
            foreach ($custom_field_namesforadd as $key) {
                $custom_field2_file = Factory::getApplication()->input->files->get($key);
                $custom_field_size = $custom_field2_file['size'];
                if ($custom_field_size > 0) { // logo
                    $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($row->id,$key ,0,2);
                }
            }
        }
        // End attachments


        if ($this->_client_auth_key != "") {
            $query = "SELECT job.* FROM `#__js_job_jobs` AS job  
                        WHERE job.id = " . $row->id;

            $db->setQuery($query);
            $data_job = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_job->id = $data['id']; // for edit case
            $data_job->job_id = $row->id;
            $data_job->authkey = $this->_client_auth_key;
            $data_job->task = 'storejob';
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_value = $jsjobsharingobject->storeJobSharing($data_job);
            $jobtemp = $this->getJSModel('common')->getJobtempModelFrontend();
            $jobtemp->updateJobTemp();
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logStoreJobSharing($return_value);
        }
        if(!($data['id'] > 0) && $row->status == 1){ //new case
            $this->postJobOnJomSocial( $row->id );
        }
        // save job ai string
        if(empty($data['id'])){
            $data['id'] = $row->id;
        }

        $this->storeAIStringsForJobdata($data);
        return true;
    }

    function storeAIStringsForJobdata($data){
        if (empty($data) || empty($data['id'])) {
            return;
        }


        $job_ai_string = '';

        if (!empty($data['title'])) {
            $job_ai_string .= trim($data['title']) . ' ';
        }

        if (!empty($data['companyid'])) {
            $company_name = $this->getJSModel('company')->getCompanynameById((int)$data['companyid']);
            if ($company_name) {
                $job_ai_string .= $company_name . ' ';
            }
        }

        if (!empty($data['jobcategory'])) {
            $cat_title = $this->getJSSiteModel('category')->getTitleByCategory((int)$data['jobcategory']);
            if ($cat_title) {
                $job_ai_string .= $cat_title . ' ';
            }
        }

        // new entires
        if (!empty($data['subcategoryid'])) {
            $sub_cat_title = $this->getJSSiteModel('category')->getTitleByCategory((int)$data['subcategoryid']);
            if ($sub_cat_title) {
                $job_ai_string .= $sub_cat_title . ' ';
            }
        }
        if (!empty($data['agefrom'])) {
            $age_title = $this->getJSSiteModel('ages')->getTitleById((int)$data['agefrom']);
            if ($age_title) {
                $job_ai_string .= $age_title . ' ';
            }
        }
        if (!empty($data['ageto'])) {
            $age_title = $this->getJSSiteModel('ages')->getTitleById((int)$data['ageto']);
            if ($age_title) {
                $job_ai_string .= $age_title . ' ';
            }
        }

        // education
        $education = '';
        if ($data['iseducationminimax'] == 1) {
            if ($data['educationminimax'] == 1)
                $education = Text::_('Minimum Education');
            else
                $education = Text::_('Maximum Education');
            $education .= ' '.$this->getJSSiteModel('highesteducation')->getTitleById((int)$data['educationid']);
        } else {
            $mineducation = $this->getJSSiteModel('highesteducation')->getTitleById((int)$data['mineducationrange']);
            $maxeducation = $this->getJSSiteModel('highesteducation')->getTitleById((int)$data['maxeducationrange']);
            $education = Text::_($mineducation) . ' - ' . Text::_($maxeducation);
        }

        if($data['degreetitle'] != ''){
            $education .= ' '.$data['degreetitle'];
        }
        if($education != ''){
            $job_ai_string .= ' '.$education . ' ';
        }

        // experience
        $experience = '';
        if ($data['isexperienceminimax'] == 1) {
            if ($data['experienceminimax'] == 1)
                $experience = Text::_('Minimum Experience');
            else
                $experience = Text::_('Maximum Experience');
            $experience .= ' '.$this->getJSSiteModel('experience')->getTitleById((int)$data['experienceid']);
        } else {
            $minexperience = $this->getJSSiteModel('experience')->getTitleById((int)$data['minexperiencerange']);
            $maxexperience = $this->getJSSiteModel('experience')->getTitleById((int)$data['maxexperiencerange']);
            $experience = Text::_($minexperience) . ' - ' . Text::_($maxexperience);
        }

        if($data['experiencetext'] != ''){
            $experience .= ' '.$data['experiencetext'];
        }
         if($experience != ''){
            $job_ai_string .= ' '.$experience . ' ';
        }

        if (!empty($data['jobtype'])) {
            $jobtype_title = $this->getJSSiteModel('jobtype')->getTitleById((int)$data['jobtype']);
            if ($jobtype_title) {
                $job_ai_string .= $jobtype_title . ' ';
            }
        }

        if (!empty($data['jobstatus'])) {
            $jobstatus_title = $this->getJSSiteModel('jobstatus')->getTitleById((int)$data['jobstatus']);
            if ($jobstatus_title) {
                $job_ai_string .= $jobstatus_title . ' ';
            }
        }

        if (!empty($data['shift'])) {
            $jobshift_title = $this->getJSSiteModel('jobshift')->getTitleById((int)$data['shift']);
            if ($jobshift_title) {
                $job_ai_string .= $jobshift_title . ' ';
            }
        }

        // salary fields
        $currencyid_symbol = '';
        $salaryrangefrom_title = '';
        $salaryrangeto_title = '';
        $salaryrangetype_title = '';

        if (!empty($data['currencyid'])) {
            $currencyid_symbol = $this->getJSModel('currency')->getCurrencySymbol((int)$data['currencyid']);
            // if ($currencyid_symbol) {
            //     $job_ai_string .= $currencyid_symbol . ' ';
            // }
        }

        if (!empty($data['salaryrangefrom'])) {
            $salaryrangefrom_title = $this->getJSSiteModel('salaryrange')->getTitleById((int)$data['salaryrangefrom']);
            // if ($salaryrangefrom_title) {
            //     $job_ai_string .= $salaryrangefrom_title . ' ';
            // }
        }

        if (!empty($data['salaryrangeto'])) {
            $salaryrangeto_title = $this->getJSSiteModel('salaryrange')->getTitleById((int)$data['salaryrangeto']);
            // if ($salaryrangeto_title) {
            //     $job_ai_string .= $salaryrangeto_title . ' ';
            // }
        }

        if (!empty($data['salaryrangetype'])) {
            $salaryrangetype_title = $this->getJSSiteModel('salaryrangetype')->getTitleById((int)$data['salaryrangetype']);
            // if ($salaryrangetype_title) {
            //     $job_ai_string .= $salaryrangetype_title . ' ';
            // }
        }
        $currency_align = $this->getJSModel('configuration')->getConfigValue('currency_align');
        $salary = $this->getJSModel('common')->getSalaryRangeView($currencyid_symbol, $salaryrangefrom_title, $salaryrangeto_title, $salaryrangetype_title, $currency_align);
        if($salary != ''){
                $job_ai_string .= $salary . ' ';
        }
        // salary end

        if (!empty($data['careerlevel'])) {
            $careerlevel_title = $this->getJSSiteModel('careerlevel')->getTitleById((int)$data['careerlevel']);
            if ($careerlevel_title) {
                $job_ai_string .= $careerlevel_title . ' ';
            }
        }

        if (!empty($data['city'])) {
            $location_string = $this->getJSModel('city')->getLocationDataForView($data['city']);
            if ($location_string) {
                $job_ai_string .= $location_string . ' ';
            }
        }

        if (!empty($data['duration'])) {
            $job_ai_string .= trim($data['duration']) . ' ';
        }

        if (!empty($data['zipcode'])) {
            $job_ai_string .= trim($data['zipcode']) . ' ';
        }


        $custom_fields = getCustomFieldClass()->userFieldsData(2);
        $skip_types = ['file', 'email', 'textarea'];
        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if (!in_array($single_field->userfieldtype, $skip_types)) {
                if (!empty($data[$single_field->field])) {
                    $job_ai_string .= is_array($data[$single_field->field])
                        ? implode(',', $data[$single_field->field]) . ' '
                        : $data[$single_field->field] . ' ';
                }
            } elseif ($single_field->userfieldtype == 'textarea') {
                if (!empty($data[$single_field->field])) {
                    $text_area_field_values .= $data[$single_field->field] . ' ';
                }
            }
        }

        $job_ai_string = trim($job_ai_string);
        $job_ai_desc_string = $job_ai_string;
        //$job_ai_desc_string = '';

        // not sure about this ??
        // if (!empty($data['gender'])) {
        //     $gendertitle = '';
        //     switch ($data['gender']) {
        //         case 1: $gendertitle = Text::_('Male');
        //             break;
        //         case 2: $gendertitle = Text::_('Female');
        //             break;
        //         default: $gendertitle = '';
        //             break;
        //     }
        //     $job_ai_desc_string .= $gendertitle . ' ';
        // }

        if (!empty($data['requiredtravel'])) {
            $requiredtraveltitle = '';
            switch ($data['requiredtravel']) {
                case 1: $requiredtraveltitle = Text::_('Not Required');
                    break;
                case 2: $requiredtraveltitle = "25%";
                    break;
                case 3: $requiredtraveltitle = "50%";
                    break;
                case 4: $requiredtraveltitle = "75%";
                    break;
                case 5: $requiredtraveltitle = "100%";
                    break;
                default: $requiredtraveltitle = '';
                    break;
            }
            $job_ai_desc_string .= $requiredtraveltitle . ' ';
        }

        if (!empty($data['workpermit'])) {
            $workpermit_string = $this->getJSModel('country')->getCountryNameById($data['workpermit']);
            if ($workpermit_string) {
                $job_ai_desc_string .= $workpermit_string . ' ';
            }
        }

        if (!empty($data['description'])) {
            $job_ai_desc_string .= trim($data['description']) . ' ';
        }

        if (!empty($data['qualifications'])) {
            $job_ai_desc_string .= trim($data['qualifications']) . ' ';
        }

        if (!empty($data['prefferdskills'])) {
            $job_ai_desc_string .= trim($data['prefferdskills']) . ' ';
        }

        if (!empty($data['agreement'])) {
            $job_ai_desc_string .= trim($data['agreement']) . ' ';
        }

        // if (!empty($data['tags'])) {
        //     $job_ai_desc_string .= trim($data['tags']) . ' ';
        // }

        if (!empty($data['metakeywords'])) {
            $job_ai_desc_string .= trim($data['metakeywords']) . ' ';
        }

        $dateformat = $this->getJSModel('configuration')->getConfigValue('date_format');
        if (!empty($data['startpublishing'])) {
            $start_date = HTMLHelper::_('date', $data['startpublishing'], $dateformat);
            $job_ai_desc_string .= $start_date . ' ';
        }

        if (!empty($data['stoppublishing'])) {
            $stop_date = HTMLHelper::_('date', $data['stoppublishing'], $dateformat);
            $job_ai_desc_string .= $stop_date . ' ';
        }

        if (!empty($data['metadescription'])) {
            $job_ai_desc_string .= trim($data['metadescription']) . ' ';
        }

        if (!empty($text_area_field_values)) {
            $job_ai_desc_string .= trim($text_area_field_values) . ' ';
        }

        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_jobs` SET `aijobsearchtext` = ".$db->quote($job_ai_string).", `aijobsearchdescription` = ".$db->quote($job_ai_desc_string)." WHERE id = " . $data['id'];
        $db->setQuery($query);
        if (!$db->execute()){
        }
        return;
    }

    function storeMultiCitiesJob($city_id, $jobid) { // city id comma seprated 
        if (is_numeric($jobid) === false)
            return false;
        $db = Factory::getDBO();
        $query = "SELECT cityid FROM #__js_job_jobcities WHERE jobid = " . $jobid;
        $db->setQuery($query);
        $old_cities = $db->loadObjectList();

        $id_array = explode(",", $city_id);
        $row = $this->getTable('jobcities');
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
                $query = "DELETE FROM #__js_job_jobcities WHERE jobid = " . $jobid . " AND cityid=" . $oldcityid->cityid;
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
                $row->jobid = $jobid;
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

    function deleteJob() {
        if(!Factory::getSession()->checkToken('post')){
	        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        }
        $db = Factory::getDBO();
        $cids = Factory::getApplication()->input->get('cid', array(0), '', 'array');
        $row = $this->getTable('job');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if(!is_numeric($cid)){
                continue;
            }
            $serverjodid = 0;
            if ($this->_client_auth_key != "") {
                $query = "SELECT job.serverid AS id FROM `#__js_job_jobs` AS job WHERE job.id = " . $cid;
                $db->setQuery($query);
                $s_job_id = $db->loadResult();
                $serverjodid = $s_job_id;
            }
            if ($this->jobCanDelete($cid) == true) {
                $query = "SELECT job.uid, job.title, company.name AS companyname, company.contactname, company.contactemail,CONCAT(job.alias,'-',job.id) AS aliasid
                            FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                    WHERE job.id = " . $cid;
                $db->setQuery($query);
                $job = $db->loadObject();

                $contactname = $job->contactname;
                $companyname = $job->companyname;
                $contactemail = $job->contactemail;
                $title = $job->title;

                $session = Factory::getApplication()->getSession();
                $session->set('contactname', $contactname);
                $session->set('companyname', $companyname);
                $session->set('contactemail', $contactemail);
                $session->set('title', $title);
                
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
                $query = "DELETE FROM `#__js_job_jobcities` WHERE jobid = " . $cid;
                $db->setQuery($query);
                if (!$db->execute()) {
                    return false;
                }
                
                $this->getJSModel('emailtemplate')->sendDeleteMail( $cid , 2);
                if ($serverjodid != 0) {
                    $data = array();
                    $data['id'] = $serverjodid;
                    $data['referenceid'] = $cid;
                    $data['uid'] = $this->_uid;
                    $data['authkey'] = $this->_client_auth_key;
                    $data['siteurl'] = $this->_siteurl;
                    $data['task'] = 'deletejob';
                    $jsjobsharingobject = $this->getJSModel('jobsharing');
                    $return_value = $jsjobsharingobject->deleteJobSharing($data);
                    $jobtemp = $this->getJSModel('common')->getJobtempModelFrontend();
                    $jobtemp->updateJobTemp();
                    $jsjobslogobject = $this->getJSModel('log');
                    $jsjobslogobject->logDeleteJobSharing($return_value);
                }
            } else
                $deleteall++;
        }
        return $deleteall;
    }

    function jobCanDelete($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = " . $jobid . ")
                    AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function jobEnforceDelete($jobid, $uid) {
        Factory::getSession()->checkToken('get') or jexit(Text::_('INVALID_TOKEN'));
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($jobid) == false)
            return false;
        $serverjodid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT job.serverid AS id FROM `#__js_job_jobs` AS job WHERE job.id = " . $jobid;
            $db->setQuery($query);
            $s_job_id = $db->loadResult();
            $serverjodid = $s_job_id;
        }

        $db = $this->getDBO();

        $query = "SELECT job.uid, job.title, company.name AS companyname, company.contactname, company.contactemail,CONCAT(job.alias,'-',job.id) AS aliasid
                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_companies` AS company ON job.companyid = company.id
            WHERE job.id = " . $jobid;
        $db->setQuery($query);
        $job = $db->loadObject();

        $contactname = $job->contactname;
        $companyname = $job->companyname;
        $contactemail = $job->contactemail;
        $title = $job->title;

        $session = Factory::getApplication()->getSession();
        $session->set('contactname', $contactname);
        $session->set('companyname', $companyname);
        $session->set('contactemail', $contactemail);
        $session->set('title', $title);



        $query = "DELETE  job,apply,jobcity
                    FROM `#__js_job_jobs` AS job
                    LEFT JOIN `#__js_job_jobapply` AS apply ON job.id=apply.jobid
                    LEFT JOIN `#__js_job_jobcities` AS jobcity ON job.id=jobcity.jobid
                    WHERE job.id = " . $jobid;

        $db->setQuery($query);
        if (!$db->execute()) {
            return 2; //error while delete job
        }
        
        $this->getJSModel('emailtemplate')->sendDeleteMail( $jobid , 2);
        
        if ($serverjodid != 0) {
            $data = array();
            $data['id'] = $serverjodid;
            $data['referenceid'] = $jobid;
            $data['uid'] = $this->_uid;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $data['enforcedeletejob'] = 1;
            $data['task'] = 'deletejob';
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_value = $jsjobsharingobject->deleteJobSharing($data);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logDeleteJobSharingEnforce($return_value);
        }
        return 1;
    }

    function checkCall() {
        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_config` SET configvalue = configvalue+1 WHERE configname = " . $db->quote('jsjobupdatecount');
        $db->setQuery($query);
        $db->execute();
        $query = "SELECT configvalue AS jsjobupdatecount FROM `#__js_job_config` WHERE configname = " . $db->quote('jsjobupdatecount');
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result >= 100)
            $this->getJSModel('jsjobs')->getConcurrentrequestdata();
    }

    function jobApprove($job_id) {
        if (is_numeric($job_id) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_jobs SET status = 1 WHERE id = " . $db->Quote($job_id);
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        $this->getJSModel('emailtemplate')->sendMail(2, 1, $job_id);
        
        //$this->sendJobAlertJobseeker($job_id);
        if ($this->_client_auth_key != "") {
            $data_job_approve = array();
            $query = "SELECT serverid FROM #__js_job_jobs WHERE id = " . $job_id;
            $db->setQuery($query);
            $serverjobid = $db->loadResult();
            $data_job_approve['id'] = $serverjobid;
            $data_job_approve['job_id'] = $job_id;
            $data_job_approve['authkey'] = $this->_client_auth_key;
            $fortask = "jobapprove";
            $server_json_data_array = json_encode($data_job_approve);
            $jsjobsharingobject = $this->getJSModel('jobsharing');
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return_value = json_decode($return_server_value, true);
            $jsjobslogobject = $this->getJSModel('log');
            $jsjobslogobject->logJobApprove($return_value);
        }
        return true;
    }

    function jobReject($job_id) {
        if (is_numeric($job_id) == false)
            return false;
        $db = Factory::getDBO();

        $query = "UPDATE #__js_job_jobs SET status = -1 WHERE id = " . $db->Quote($job_id);
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }
        $this->getJSModel('emailtemplate')->sendMail(2, -1, $job_id);
        if ($this->_client_auth_key != "") {
            $data_job_reject = array();
            $query = "SELECT serverid FROM #__js_job_jobs WHERE id = " . $job_id;
            $db->setQuery($query);
            $serverjobid = $db->loadResult();
            $data_job_reject['id'] = $serverjobid;
            $data_job_reject['job_id'] = $job_id;
            $data_job_reject['authkey'] = $this->_client_auth_key;
            $fortask = "jobreject";
            $server_json_data_array = json_encode($data_job_reject);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function getJobId() {
        $db = $this->getDBO();
        $query = "Select jobid from `#__js_job_jobs`";
        do {

            $jobid = "";
            $length = 9;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ!@#";
            // we refer to the length of $possible a few times, so let's grab it now
            $maxlength = strlen($possible);
            // check for length overflow and truncate if necessary
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            // set up a counter for how many characters are in the password so far
            $i = 0;
            // add random characters to $password until $length is reached
            while ($i < $length) {
                // pick a random character from the possible ones
                $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
                // have we already used this character in $password?

                if (!strstr($jobid, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $jobid .= $char;
                            $i++;
                        }
                    } else {
                        $jobid .= $char;
                        $i++;
                    }
                }
            }
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            foreach ($rows as $row) {
                if ($jobid == $row->jobid)
                    $match = 'Y';
                else
                    $match = 'N';
            }
        }while ($match == 'Y');
        return $jobid;
    }

    function postJobOnJomSocial($jobid){
        if( $this->getJSModel(VIRTUEMARTJSJOBS)->{VIRTUEMARTJSJOBSFUN}("bEVsa3ZYam9tc29jaWFsY1JCTm5X") ){
            JPluginHelper::importPlugin('community');
            $dispatcher = JEventDispatcher::getInstance();
            $res = $dispatcher->trigger('JSjobsPostJobOnJomSocial',array($jobid));
            if(!$res[0])
                return false;
        }
        return true;
    }

    function prepareJomSocial(){
        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__extensions` WHERE `type`='plugin' AND `element`='jsjobs' AND `folder`='community'";
        eval($this->getJSModel('common')->b64ForDecode($this->getJSModel('configuration')->getConfigValue("jmjsbasic")));
        $db->setQuery($query);
        if(!$db->loadResult())
            return false;
        return true;
    }

    function getJobIdsByCompanyid($companyid){
        if(!is_numeric($companyid) || $companyid == 0){
            return false;
        }
        $db = Factory::getDbo();
        $query = "SELECT job.id FROM `#__js_job_jobs` AS job WHERE job.companyid = " . $companyid;
        $db->setQuery($query);
        $jobsid = $db->loadObjectList();
        return $jobsid;
    }

    function updateJobDataForEraseDataRequest($jobid){
        if(!is_numeric($jobid) || $jobid == 0){
            return false;
        }
        $db = Factory::getDbo();
        $query = "UPDATE `#__js_job_jobs` SET `title`='-----',`description`='-----',`qualifications`= '-----',`prefferdskills`='-----',`applyinfo`='-----',`address1`='-----',`address2`='-----',`companyurl`='-----',`contactname`= '-----',`contactphone`='-----',`contactemail`='-----',`duration`='-----',`heighestfinisheducation`='-----',`hits`=0,`stoppublishing`= CURDATE(),`agreement`='-----',`joblink`= '-----',`jobapplylink`='0',`params`='-----' , `status` = 0 WHERE id = " . $jobid;
        $db->setQuery($query);
        $error = array();
        if (!$db->execute()) {
            $err = $this->setError($row->getError());
            $error[] = $err;
        }
        if(empty($error)){
            return true;
        }else{
            return false;
        }
    }

    function deleteUserJobDataById($jobid){
        if(!is_numeric($jobid) || $jobid == 0){
            return false;
        }

        // job apply data
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $condition = array($db->quoteName('jobid') . ' = ' . $jobid);
        $query->delete('#__js_job_jobapply');
        $query->where($condition);
        $db->setQuery($query);
        $db->execute();

        // job cities
        $query = $db->getQuery(true);
        $condition = array($db->quoteName('jobid') . ' = ' . $jobid);
        $query->delete('#__js_job_jobcities');
        $query->where($condition);
        $db->setQuery($query);
        $db->execute();

        $row = $this->getTable('job');
        $row->delete($jobid);
    }


    function importAIStringDataForJobs($data) {
        if (empty($data) || empty($data['id'])) {
            return;
        }

        $job_ai_string = '';

        if (!empty($data['title'])) {
            $job_ai_string .= trim($data['title']) . ' ';
        }

        if (!empty($data['companyid'])) {
            if (!isset($this->_ai_job_data_array['companies'][$data['companyid']])) {
                $this->_ai_job_data_array['companies'][$data['companyid']] = $this->getJSSiteModel('company')->getCompanynameById((int)$data['companyid']);
            }
            if ($this->_ai_job_data_array['companies'][$data['companyid']]) {
                $job_ai_string .= $this->_ai_job_data_array['companies'][$data['companyid']] . ' ';
            }
        }

        if (!empty($data['jobcategory'])) {
            if (!isset($this->_ai_job_data_array['categories'][$data['jobcategory']])) {
                $this->_ai_job_data_array['categories'][$data['jobcategory']] = $this->getJSSiteModel('category')->getTitleByCategory((int)$data['jobcategory']);
            }
            if ($this->_ai_job_data_array['categories'][$data['jobcategory']]) {
                $job_ai_string .= $this->_ai_job_data_array['categories'][$data['jobcategory']] . ' ';
            }
        }

        if (!empty($data['subcategoryid'])) {
            if (!isset($this->_ai_job_data_array['categories'][$data['subcategoryid']])) {
                $this->_ai_job_data_array['categories'][$data['subcategoryid']] = $this->getJSSiteModel('category')->getTitleByCategory((int)$data['subcategoryid']);
            }
            if ($this->_ai_job_data_array['categories'][$data['subcategoryid']]) {
                $job_ai_string .= $this->_ai_job_data_array['categories'][$data['subcategoryid']] . ' ';
            }
        }

        if (!empty($data['agefrom'])) {
            if (!isset($this->_ai_job_data_array['ages'][$data['agefrom']])) {
                $this->_ai_job_data_array['ages'][$data['agefrom']] = $this->getJSSiteModel('ages')->getTitleById((int)$data['agefrom']);
            }
            if ($this->_ai_job_data_array['ages'][$data['agefrom']]) {
                $job_ai_string .= $this->_ai_job_data_array['ages'][$data['agefrom']] . ' ';
            }
        }

        if (!empty($data['ageto'])) {
            if (!isset($this->_ai_job_data_array['ages'][$data['ageto']])) {
                $this->_ai_job_data_array['ages'][$data['ageto']] = $this->getJSSiteModel('ages')->getTitleById((int)$data['ageto']);
            }
            if ($this->_ai_job_data_array['ages'][$data['ageto']]) {
                $job_ai_string .= $this->_ai_job_data_array['ages'][$data['ageto']] . ' ';
            }
        }

        $education = '';
        if ($data['iseducationminimax'] == 1) {
            if ($data['educationminimax'] == 1)
                $education = Text::_('Minimum Education');
            else
                $education = Text::_('Maximum Education');

            if (!isset($this->_ai_job_data_array['highesteducation'][$data['educationid']])) {
                $this->_ai_job_data_array['highesteducation'][$data['educationid']] = $this->getJSSiteModel('highesteducation')->getTitleById((int)$data['educationid']);
            }
            $education .= ' '.$this->_ai_job_data_array['highesteducation'][$data['educationid']];
        } else {
            if (!isset($this->_ai_job_data_array['highesteducation'][$data['mineducationrange']])) {
                $this->_ai_job_data_array['highesteducation'][$data['mineducationrange']] = $this->getJSSiteModel('highesteducation')->getTitleById((int)$data['mineducationrange']);
            }
            $mineducation = $this->_ai_job_data_array['highesteducation'][$data['mineducationrange']];

            if (!isset($this->_ai_job_data_array['highesteducation'][$data['maxeducationrange']])) {
                $this->_ai_job_data_array['highesteducation'][$data['maxeducationrange']] = $this->getJSSiteModel('highesteducation')->getTitleById((int)$data['maxeducationrange']);
            }
            $maxeducation = $this->_ai_job_data_array['highesteducation'][$data['maxeducationrange']];

            $education = Text::_($mineducation) . ' - ' . Text::_($maxeducation);
        }

        if(!empty($data['degreetitle'])){
            $education .= ' '.$data['degreetitle'];
        }
        if($education != ''){
            $job_ai_string .= ' '.$education . ' ';
        }

        $experience = '';
        if ($data['isexperienceminimax'] == 1) {
            if ($data['experienceminimax'] == 1)
                $experience = Text::_('Minimum Experience');
            else
                $experience = Text::_('Maximum Experience');

            if (!isset($this->_ai_job_data_array['experience'][$data['experienceid']])) {
                $this->_ai_job_data_array['experience'][$data['experienceid']] = $this->getJSSiteModel('experience')->getTitleById((int)$data['experienceid']);
            }
            $experience .= ' '.$this->_ai_job_data_array['experience'][$data['experienceid']];
        } else {
            if (!isset($this->_ai_job_data_array['experience'][$data['minexperiencerange']])) {
                $this->_ai_job_data_array['experience'][$data['minexperiencerange']] = $this->getJSSiteModel('experience')->getTitleById((int)$data['minexperiencerange']);
            }
            $minexperience = $this->_ai_job_data_array['experience'][$data['minexperiencerange']];

            if (!isset($this->_ai_job_data_array['experience'][$data['maxexperiencerange']])) {
                $this->_ai_job_data_array['experience'][$data['maxexperiencerange']] = $this->getJSSiteModel('experience')->getTitleById((int)$data['maxexperiencerange']);
            }
            $maxexperience = $this->_ai_job_data_array['experience'][$data['maxexperiencerange']];

            $experience = Text::_($minexperience) . ' - ' . Text::_($maxexperience);
        }

        if(!empty($data['experiencetext'])){
            $experience .= ' '.$data['experiencetext'];
        }
        if($experience != ''){
            $job_ai_string .= ' '.$experience . ' ';
        }

        if (!empty($data['jobtype'])) {
            if (!isset($this->_ai_job_data_array['jobtypes'][$data['jobtype']])) {
                $this->_ai_job_data_array['jobtypes'][$data['jobtype']] = $this->getJSSiteModel('jobtype')->getTitleById((int)$data['jobtype']);
            }
            if ($this->_ai_job_data_array['jobtypes'][$data['jobtype']]) {
                $job_ai_string .= $this->_ai_job_data_array['jobtypes'][$data['jobtype']] . ' ';
            }
        }

        if (!empty($data['jobstatus'])) {
            if (!isset($this->_ai_job_data_array['jobstatuses'][$data['jobstatus']])) {
                $this->_ai_job_data_array['jobstatuses'][$data['jobstatus']] = $this->getJSSiteModel('jobstatus')->getTitleById((int)$data['jobstatus']);
            }
            if ($this->_ai_job_data_array['jobstatuses'][$data['jobstatus']]) {
                $job_ai_string .= $this->_ai_job_data_array['jobstatuses'][$data['jobstatus']] . ' ';
            }
        }

        if (!empty($data['shift'])) {
            if (!isset($this->_ai_job_data_array['jobshift'][$data['shift']])) {
                $this->_ai_job_data_array['jobshift'][$data['shift']] = $this->getJSSiteModel('jobshift')->getTitleById((int)$data['shift']);
            }
            if ($this->_ai_job_data_array['jobshift'][$data['shift']]) {
                $job_ai_string .= $this->_ai_job_data_array['jobshift'][$data['shift']] . ' ';
            }
        }

        // Salary fields
        $currencyid_symbol = '';
        $salaryrangefrom_title = '';
        $salaryrangeto_title = '';
        $salaryrangetype_title = '';

        if (!empty($data['currencyid'])) {
            $currencyid_symbol = $this->getJSModel('currency')->getCurrencySymbol((int)$data['currencyid']);
        }

        if (!empty($data['salaryrangefrom'])) {
            if (!isset($this->_ai_job_data_array['salaryranges'][$data['salaryrangefrom']])) {
                $this->_ai_job_data_array['salaryranges'][$data['salaryrangefrom']] = $this->getJSSiteModel('salaryrange')->getTitleById((int)$data['salaryrangefrom']);
            }
            $salaryrangefrom_title = $this->_ai_job_data_array['salaryranges'][$data['salaryrangefrom']];
        }

        if (!empty($data['salaryrangeto'])) {
            if (!isset($this->_ai_job_data_array['salaryranges'][$data['salaryrangeto']])) {
                $this->_ai_job_data_array['salaryranges'][$data['salaryrangeto']] = $this->getJSSiteModel('salaryrange')->getTitleById((int)$data['salaryrangeto']);
            }
            $salaryrangeto_title = $this->_ai_job_data_array['salaryranges'][$data['salaryrangeto']];
        }

        if (!empty($data['salaryrangetype'])) {
            if (!isset($this->_ai_job_data_array['salaryranges'][$data['salaryrangetype']])) {
                $this->_ai_job_data_array['salaryranges'][$data['salaryrangetype']] = $this->getJSSiteModel('salaryrangetype')->getTitleById((int)$data['salaryrangetype']);
            }
            $salaryrangetype_title = $this->_ai_job_data_array['salaryranges'][$data['salaryrangetype']];
        }

        $currency_align = $this->getJSSiteModel('configurations')->getConfigValue('currency_align');
        $salary = $this->getJSModel('common')->getSalaryRangeView($currencyid_symbol, $salaryrangefrom_title, $salaryrangeto_title, $salaryrangetype_title, $currency_align);
        if($salary != ''){
            $job_ai_string .= $salary . ' ';
        }

        if (!empty($data['careerlevel'])) {
            if (!isset($this->_ai_job_data_array['careerlevels'][$data['careerlevel']])) {
                $this->_ai_job_data_array['careerlevels'][$data['careerlevel']] = $this->getJSSiteModel('careerlevel')->getTitleById((int)$data['careerlevel']);
            }
            if ($this->_ai_job_data_array['careerlevels'][$data['careerlevel']]) {
                $job_ai_string .= $this->_ai_job_data_array['careerlevels'][$data['careerlevel']] . ' ';
            }
        }

        if (!empty($data['city'])) {
            if (!isset($this->_ai_job_data_array['locations'][$data['city']])) {
                $this->_ai_job_data_array['locations'][$data['city']] = $this->getJSSiteModel('cities')->getLocationDataForView($data['city']);
            }
            if ($this->_ai_job_data_array['locations'][$data['city']]) {
                $job_ai_string .= $this->_ai_job_data_array['locations'][$data['city']] . ' ';
            }
        }

        if (!empty($data['duration'])) {
            $job_ai_string .= trim($data['duration']) . ' ';
        }

        if (!empty($data['zipcode'])) {
            $job_ai_string .= trim($data['zipcode']) . ' ';
        }

        // Custom Fields
        if ($this->_ai_job_data_array['customfields_user_2'] === null) {
            $this->_ai_job_data_array['customfields_user_2'] = getCustomFieldClass()->userFieldsData(2);
        }
        $custom_fields = $this->_ai_job_data_array['customfields_user_2'];

        $skip_types = ['file', 'email', 'textarea'];
        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if (!in_array($single_field->userfieldtype, $skip_types)) {
                if (!empty($data[$single_field->field])) {
                    $job_ai_string .= is_array($data[$single_field->field])
                        ? implode(',', $data[$single_field->field]) . ' '
                        : $data[$single_field->field] . ' ';
                }
            } elseif ($single_field->userfieldtype == 'textarea') {
                if (!empty($data[$single_field->field])) {
                    $text_area_field_values .= $data[$single_field->field] . ' ';
                }
            }
        }

        $job_ai_string = trim($job_ai_string);
        $job_ai_desc_string = $job_ai_string;

        // if (!empty($data['gender'])) {
        //     $gendertitle = '';
        //     switch ($data['gender']) {
        //         case 1: $gendertitle = Text::_('Male');
        //             break;
        //         case 2: $gendertitle = Text::_('Female');
        //             break;
        //         default: $gendertitle = '';
        //             break;
        //     }
        //     $job_ai_desc_string .= $gendertitle . ' ';
        // }

        if (!empty($data['requiredtravel'])) {
            $requiredtraveltitle = '';
            switch ($data['requiredtravel']) {
                case 1: $requiredtraveltitle = Text::_('Not Required');
                    break;
                case 2: $requiredtraveltitle = "25%";
                    break;
                case 3: $requiredtraveltitle = "50%";
                    break;
                case 4: $requiredtraveltitle = "75%";
                    break;
                case 5: $requiredtraveltitle = "100%";
                    break;
                default: $requiredtraveltitle = '';
                    break;
            }
            $job_ai_desc_string .= $requiredtraveltitle . ' ';
        }

        if (!empty($data['workpermit'])) {
            if (!isset($this->_ai_job_data_array['countries'][$data['workpermit']])) {
                $this->_ai_job_data_array['countries'][$data['workpermit']] = $this->getJSSiteModel('countries')->getCountryNameById($data['workpermit']);
            }
            if ($this->_ai_job_data_array['countries'][$data['workpermit']]) {
                $job_ai_desc_string .= $this->_ai_job_data_array['countries'][$data['workpermit']] . ' ';
            }
        }

        if (!empty($data['description'])) {
            $job_ai_desc_string .= trim($data['description']) . ' ';
        }

        if (!empty($data['qualifications'])) {
            $job_ai_desc_string .= trim($data['qualifications']) . ' ';
        }

        if (!empty($data['prefferdskills'])) {
            $job_ai_desc_string .= trim($data['prefferdskills']) . ' ';
        }

        if (!empty($data['agreement'])) {
            $job_ai_desc_string .= trim($data['agreement']) . ' ';
        }

        if (!empty($data['metakeywords'])) {
            $job_ai_desc_string .= trim($data['metakeywords']) . ' ';
        }

        $dateformat = $this->getJSModel('configuration')->getConfigValue('date_format');
        if (!empty($data['startpublishing'])) {
            $start_date = HTMLHelper::_('date', $data['startpublishing'], $dateformat);
            $job_ai_desc_string .= $start_date . ' ';
        }

        if (!empty($data['stoppublishing'])) {
            $stop_date = HTMLHelper::_('date', $data['stoppublishing'], $dateformat);
            $job_ai_desc_string .= $stop_date . ' ';
        }

        if (!empty($data['metadescription'])) {
            $job_ai_desc_string .= trim($data['metadescription']) . ' ';
        }

        if (!empty($text_area_field_values)) {
            $job_ai_desc_string .= trim($text_area_field_values) . ' ';
        }
        // echo '<br/>';
        // echo '====================================================================================================================';
        // echo '<br/>';
        // echo var_dump($data['id']);
        // echo '<br/>';
        // echo var_dump($job_ai_string);
        // echo '<br/>';
        // echo var_dump($job_ai_desc_string);
        // echo '<br/>';
        // echo '<br/>';
        // echo '<br/>';

        // Update database
        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_jobs` SET `aijobsearchtext` = ".$db->quote($job_ai_string).", `aijobsearchdescription` = ".$db->quote($job_ai_desc_string)." WHERE id = " . $data['id'];
        $db->setQuery($query);
        if (!$db->execute()){
            die('store error => job model line no 2371 ');
        }
        return;
    }

    function updateRecordsForAISearchJob() {
        $db = Factory::getDBO();
        $query = "SELECT * FROM `#__js_job_jobs` ORDER BY id DESC";
        $db->setQuery($query);
        $jobs = $db->loadObjectList();

        $this->_ai_job_data_array = array(
            'companies' => array(),
            'categories' => array(),
            'jobtypes' => array(),
            'jobstatuses' => array(),
            'salaryranges' => array(),
            'careerlevels' => array(),
            'locations' => array(),
            'highesteducation' => array(),
            'experience' => array(),
            'ages' => array(),
            'jobshift' => array(),
            'countries' => array(),
            'customfields_user_2' => null
        );

        foreach ($jobs as $job) {
            $job_array = json_decode(json_encode($job), true);
            $this->importAIStringDataForJobs($job_array);
        }
        //die(' lineee noo 53133');
        return;
    }
}
?>
