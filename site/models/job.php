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
use Joomla\CMS\Router\Route;    

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelJob extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_defaultcountry = null;
    var $_job = null;
    var $_applications = null;
    var $_application = null;
    var $_jinput = null;

    function __construct() {
        parent::__construct();
        $client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_client_auth_key = $client_auth_key;
        $this->_siteurl = Uri::root();

        $user = Factory::getUser();
        $this->_uid = $user->id;
        $this->_jinput = Factory::getApplication()->input;
    }

    function jobsearch($sh_category, $sh_subcategory, $sh_company, $sh_jobtype, $sh_jobstatus, $sh_shift, $sh_salaryrange, $plugin) {
        $db = Factory::getDBO();

        // Configurations *********************************************
        $query = "SELECT * FROM `#__js_job_config` WHERE configname = 'date_format' ";
        $db->setQuery($query);
        $configs = $db->loadObjectList();
        foreach ($configs AS $config) {
            if ($config->configname == 'date_format')
                $dateformat = $config->configvalue;
        }
        $firstdash = strpos($dateformat, '-', 0);
        $firstvalue = substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = strpos($dateformat, '-', $firstdash);
        $secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
        $js_dateformat = '%' . $firstvalue . '-%' . $secondvalue . '-%' . $thirdvalue;


        // Categories *********************************************
        if ($sh_category == 1) {
            $query = "SELECT * FROM `#__js_job_categories` WHERE isactive = 1 ORDER BY cat_title ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobcategories = array();
                $jobcategories[] = array('value' => Text::_(''), 'text' => Text::_('Select Category'));
                foreach ($rows as $row)
                    $jobcategories[] = array('value' => Text::_($row->id), 'text' => Text::_($row->cat_title));
            }
            if (isset($plugin) && $plugin == 1)
                $job_categories = HTMLHelper::_('select.genericList', $jobcategories, 'category', 'class="inputbox" style="width:160px;" ' . 'onChange="plgfj_getsubcategories(\'plgfj_subcategory\', this.value)"', 'value', 'text', '');
            else
                $job_categories = HTMLHelper::_('select.genericList', $jobcategories, 'category', 'class="inputbox" style="width:160px;" ' . 'onChange="modfj_getsubcategories(\'modfj_subcategory\', this.value)"', 'value', 'text', '');
        }
        // Sub Categories *********************************************
        if ($sh_subcategory == 1) {
            $jobsubcategories = array();
            $jobsubcategories[] = array('value' => Text::_(''), 'text' => Text::_('Select Sub Category'));
            $job_subcategories = HTMLHelper::_('select.genericList', $jobsubcategories, 'jobsubcategory', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        //Companies *********************************************
        if ($sh_company == 1) {
            $query = "SELECT id, name FROM `#__js_job_companies` ORDER BY name ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $companies = array();
                $companies[] = array('value' => Text::_(''), 'text' => Text::_('Select Company'));
                foreach ($rows as $row)
                    $companies[] = array('value' => $row->id, 'text' => $row->name);
            }
            $search_companies = HTMLHelper::_('select.genericList', $companies, 'company[]', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        //Job Types *********************************************
        if ($sh_jobtype == 1) {
            $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobtype = array();
                $jobtype[] = array('value' => Text::_(''), 'text' => Text::_('Select Job Type'));
                foreach ($rows as $row)
                    $jobtype[] = array('value' => Text::_($row->id), 'text' => Text::_($row->title));
            }
            $job_type = HTMLHelper::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        //Job Status *********************************************
        if ($sh_jobstatus == 1) {
            $query = "SELECT id, title FROM `#__js_job_jobstatus` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobstatus = array();
                $jobstatus[] = array('value' => Text::_(''), 'text' => Text::_('Select Job Status'));
                foreach ($rows as $row)
                    $jobstatus[] = array('value' => Text::_($row->id), 'text' => Text::_($row->title));
            }
            $job_status = HTMLHelper::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        //Shifts *********************************************
        if ($sh_shift == 1) {
            $query = "SELECT id, title FROM `#__js_job_shifts` WHERE isactive = 1 ORDER BY ordering ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $shifts = array();
                $shifts[] = array('value' => Text::_(''), 'text' => Text::_('Select Job Shift'));
                foreach ($rows as $row)
                    $shifts[] = array('value' => Text::_($row->id), 'text' => Text::_($row->title));
            }
            $search_shift = HTMLHelper::_('select.genericList', $shifts, 'shift', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        // Salary Rnage *********************************************
        if ($sh_salaryrange == 1) {
            $query = "SELECT * FROM `#__js_job_salaryrange` ORDER BY id ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $salaryrangefrom = array();
                $salaryrangeto = array();
                $salaryrangefrom[] = array('value' => Text::_(''), 'text' => Text::_('From'));
                $salaryrangeto[] = array('value' => Text::_(''), 'text' => Text::_('To'));
                foreach ($rows as $row) {
                    $salrange = $row->rangestart;
                    $salaryrangefrom[] = array('value' => Text::_($row->id), 'text' => Text::_($salrange));
                    $salaryrangeto[] = array('value' => Text::_($row->id), 'text' => Text::_($salrange));
                }
                $query = "SELECT id, title FROM `#__js_job_salaryrangetypes` WHERE status = 1 ORDER BY id ASC ";
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $types = array();
                foreach ($rows as $row) {
                    $types[] = array('value' => $row->id, 'text' => $row->title);
                }
            }
            $salaryrangefrom = HTMLHelper::_('select.genericList', $salaryrangefrom, 'srangestart', 'class="inputbox" ' . 'style="width:40%;"', 'value', 'text', '');
            $salaryrangeto = HTMLHelper::_('select.genericList', $salaryrangeto, 'srangeend', 'class="inputbox" ' . 'style="width:40%;"', 'value', 'text', '');
            $salaryrangetypes = HTMLHelper::_('select.genericList', $types, 'srangetype', 'class="inputbox" ' . 'style="width:40%;"', 'value', 'text', 2);

            // get combo of currencies
            $currencycombo = $this->getJSModel('currency')->getCurrencyComboFORMP();
        }

            $cities='    <span id="plugsearchjob_city">
                    <input class="inputbox" type="text" name="city" id="cityplug" size="27" maxlength="100"  />
                </span>';

        if (isset($js_dateformat))
            $result[0] = $js_dateformat;
        if (isset($currencycombo))
            $result[1] = $currencycombo;
        if (isset($job_categories))
            $result[2] = $job_categories;

        if (isset($search_companies))
            $result[3] = $search_companies;
        if (isset($job_type))
            $result[4] = $job_type;

        if (isset($job_status))
            $result[5] = $job_status;
        if (isset($search_shift))
            $result[6] = $search_shift;
        if (isset($salaryrangefrom))
            $result[7] = $salaryrangefrom;

        if (isset($salaryrangeto))
            $result[8] = $salaryrangeto;
        if (isset($salaryrangetypes))
            $result[9] = $salaryrangetypes;
        if (isset($job_subcategories))
            $result[10] = $job_subcategories;

        if (isset($cities))
            $result[11] = $cities;

        return $result;
    }

    function getHotJobs($noofjobs, $theme) {
        if($noofjobs)
            if(!is_numeric($noofjobs)) return false;

        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $data_directory = $this->getJSModel('configurations')->getConfigValue('data_directory');
        $this->getJSModel('common')->setTheme();
        $curdate = Factory::getDate()->format('Y-m-d');
        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $query = "SELECT job.id AS jobid, COUNT(apply.jobid) as totalapply, $id, job.title, job.jobcategory, job.created, cat.cat_title
            , company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle,subcat.title AS subcat_title,company.logofilename AS companylogo
            $alias $companyaliasid,job.city

            FROM `#__js_job_jobs` AS job
            JOIN `#__js_job_jobapply` AS apply ON job.id = apply.jobid
            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
            LEFT JOIN `#__js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
            LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
            WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
            GROUP BY apply.jobid ORDER BY totalapply DESC LIMIT {$noofjobs}";

        $db->setQuery($query);
        $datalocal=$db->loadObjectList();
        foreach ($datalocal as $d) {
            $d->cityname = $this->getJSModel('cities')->getLocationDataForView($d->city);
        }
        $result[0] = $datalocal;
        $result[2] = $dateformat;
        $result[3] = $data_directory;
        return $result;
    }


    function getFeaturedJobs($noofjobs, $theme) {
        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $datadirectory = $this->getJSModel('configurations')->getConfigValue('data_directory');
        $this->getJSModel('common')->setTheme();

        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $curdate = Factory::getDate()->format('Y-m-d');
        $query = "SELECT job.packageid
        ,$id, job.title, job.created, job.country, job.state, job.county, job.city, cat.cat_title
        , company.id AS companyid, company.name AS companyname, company.logofilename,subcat.title AS subcat_title
        ,jobtype.title AS jobtypetitle,company.logofilename AS companylogo
        $alias $companyaliasid,job.city, jobcurrency.symbol AS currency,jobsalaryfrom.rangestart AS jobsalaryfrom,jobsalaryto.rangestart AS jobsalaryto,salarytype.title AS salarytype

        FROM `#__js_job_jobs` AS job
        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype=jobtype.id
        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
        LEFT JOIN `#__js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
        LEFT JOIN `#__js_job_employerpackages` AS package ON package.id = job.packageid
        LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
        LEFT JOIN `#__js_job_companies` AS company ON company.id=job.companyid
        LEFT JOIN `#__js_job_currencies` AS jobcurrency ON job.currencyid = jobcurrency.id
        LEFT JOIN `#__js_job_salaryrange` AS jobsalaryfrom ON job.salaryrangefrom = jobsalaryfrom.id
        LEFT JOIN `#__js_job_salaryrange` AS jobsalaryto ON job.salaryrangeto = jobsalaryto.id
        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
        WHERE job.status = 1 AND job.isfeaturedjob = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
        GROUP BY job.id
        ORDER BY created DESC ";
        if ($noofjobs != -1){
            if (is_numeric($noofjobs)){
                $query .=" LIMIT " . $noofjobs;
            }
        }
        $db->setQuery($query);
        $datalocal=$db->loadObjectList();
        foreach ($datalocal as $d) {
            $d->cityname = $this->getJSModel('cities')->getLocationDataForView($d->city);
        }

        $result[0] = $datalocal;
/*        foreach ($result[0] AS $fjdata) {
            $multicitydata = $this->getJSModel('employer')->getMultiCityDataForView($fjdata->id, 1);
            if ($multicitydata != "")
                $fjdata->multicity = $multicitydata;
        }
*/
        $result[2] = $dateformat;
        $result[3] = $datadirectory;
        return $result;
    }

    function getNewestJobs($noofjobs, $theme) {
        if($noofjobs)
            if(!is_numeric($noofjobs)) return false;

        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $data_directory = $this->getJSModel('configurations')->getConfigValue('data_directory');
        $this->getJSModel('common')->setTheme();
        $curdate = Factory::getDate()->format('Y-m-d');

        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $query = "SELECT $id,job.title, job.jobcategory, job.created, cat.cat_title,subcat.title as subcat_title
            , company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo, jobtype.title AS jobtypetitle
            $alias $companyaliasid,job.city
            FROM `#__js_job_jobs` AS job
            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
            LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
            WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
            GROUP BY job.id
            ORDER BY created DESC LIMIT {$noofjobs}";
        $db->setQuery($query);
        $datalocal=$db->loadObjectList();
        foreach ($datalocal as $d) {
            $d->cityname = $this->getJSModel('cities')->getLocationDataForView($d->city);
        }

        $result[0] = $datalocal;

        $result[2] = $dateformat;
        $result[3] = $data_directory;
        return $result;
    }


    function getNewestJobsByTypes($noofjobs) {
        if($noofjobs)
            if(!is_numeric($noofjobs)) return false;

        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $data_directory = $this->getJSModel('configurations')->getConfigValue('data_directory');
        $this->getJSModel('common')->setTheme();
        $curdate = Factory::getDate()->format('Y-m-d');

        $id = "job.id AS id";
        $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
        $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";

        $jobs_array = array();
        $job_types_array = array();

        $query = "SELECT $id,job.title, job.jobcategory, job.created, cat.cat_title,subcat.title as subcat_title
            , company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo, jobtype.title AS jobtypetitle
            $alias $companyaliasid,job.city ,jobcurrency.symbol AS currency,jobsalaryfrom.rangestart AS jobsalaryfrom,jobsalaryto.rangestart AS jobsalaryto,salarytype.title AS salarytype
            FROM `#__js_job_jobs` AS job
            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
            LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
            LEFT JOIN `#__js_job_currencies` AS jobcurrency ON job.currencyid = jobcurrency.id
            LEFT JOIN `#__js_job_salaryrange` AS jobsalaryfrom ON job.salaryrangefrom = jobsalaryfrom.id
            LEFT JOIN `#__js_job_salaryrange` AS jobsalaryto ON job.salaryrangeto = jobsalaryto.id
            LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
            WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
            GROUP BY job.id
            ORDER BY created DESC LIMIT {$noofjobs}";
        $db->setQuery($query);
        $datalocal=$db->loadObjectList();
        foreach ($datalocal as $d) {
            $d->cityname = $this->getJSModel('cities')->getLocationDataForView($d->city);
        }
        $jobs_array[0] = $datalocal;

        // select job types from database
        $query = "SELECT id,title FROM `#__js_job_jobtypes` WHERE status = 1 ORDER BY ordering ASC";
        $db->setQuery($query);
        $jobtypes= $db->loadObjectList();

        foreach ($jobtypes as $jobtype) {
            $query = "SELECT $id,job.title, job.jobcategory, job.created, cat.cat_title,subcat.title as subcat_title
                , company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo, jobtype.title AS jobtypetitle
                $alias $companyaliasid,job.city ,jobcurrency.symbol AS currency,jobsalaryfrom.rangestart AS jobsalaryfrom,jobsalaryto.rangestart AS jobsalaryto,salarytype.title AS salarytype
                FROM `#__js_job_jobs` AS job
                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                LEFT JOIN `#__js_job_currencies` AS jobcurrency ON job.currencyid = jobcurrency.id
                LEFT JOIN `#__js_job_salaryrange` AS jobsalaryfrom ON job.salaryrangefrom = jobsalaryfrom.id
                LEFT JOIN `#__js_job_salaryrange` AS jobsalaryto ON job.salaryrangeto = jobsalaryto.id
                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
                AND job.jobtype = ".$jobtype->id."
                GROUP BY job.id
                ORDER BY created DESC LIMIT {$noofjobs}";
            $db->setQuery($query);
            $datalocal = $db->loadObjectList();
            foreach ($datalocal as $d) {
                $d->cityname = $this->getJSModel('cities')->getLocationDataForView($d->city);
            }

            $jobs_array[$jobtype->id] = $datalocal;
            $job_types_array[$jobtype->id] = $jobtype->title;
        }



        $result[0] = $jobs_array;
        $result[4] = $job_types_array;

        $result[2] = $dateformat;
        $result[3] = $data_directory;
        return $result;
    }



    function getTopJobs($noofjobs, $theme) {
        if($noofjobs)
            if(!is_numeric($noofjobs)) return false;

        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $data_directory = $this->getJSModel('configurations')->getConfigValue('data_directory');
        $this->getJSModel('common')->setTheme();
        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $curdate = Factory::getDate()->format('Y-m-d');
        $query = "SELECT $id, job.title, job.jobcategory, job.created, cat.cat_title
            , company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle,company.logofilename AS companylogo
            $alias $companyaliasid,subcat.title as subcat_title,job.city

            FROM `#__js_job_jobs` AS job
            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
            LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
            WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
            GROUP BY job.id
            ORDER BY job.hits DESC LIMIT {$noofjobs}";

        $db->setQuery($query);
         $datalocal=$db->loadObjectList();
        foreach ($datalocal as $d) {
            $d->cityname = $this->getJSModel('cities')->getLocationDataForView($d->city);
        }

        $result[0] = $datalocal;

        $result[2] = $dateformat;
        $result[3] = $data_directory;
        return $result;
    }


    function getJobCat() {
        $db = $this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'defaultcountry')
                $defaultcountry = $conf->configvalue;
            if ($conf->configname == 'hidecountry')
                $hidecountry = $conf->configvalue;
            if ($conf->configname == 'subcategory_limit')
                $subcategory_limit = $conf->configvalue;
        }
        if ($this->_client_auth_key != "") {
            $wherequery = "";
            $server_address = "";
            $fortask = "listjobsbycategory";
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $data['wherequery'] = $wherequery;
            $data['server_address'] = $server_address;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobsbycategory']) AND $return_server_value['jobsbycategory'] == -1) { // auth fail
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "List Jobs By Category";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
            } else {
                $parse_data = array();
                foreach ($return_server_value['listjobbycategory'] AS $data) {
                    $parse_data[] = (object) $data;
                }
                $this->_applications = $parse_data;
            }
        } else {
            $wherequery = '';
            $curdate = Factory::getDate()->format('Y-m-d');
            $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs`  AS job WHERE cat.id = job.jobcategory AND job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate);
            $inquery .= $wherequery . " ) as catinjobs";

            $query = "SELECT  DISTINCT cat.id, cat.cat_title,CONCAT(cat.alias,'-',cat.id) AS categoryaliasid, ";
            $query .= $inquery;
            $query .= " FROM `#__js_job_categories` AS cat
                        LEFT JOIN `#__js_job_jobs` AS job ON cat.id = job.jobcategory
                        WHERE cat.isactive = 1 ";
            $query .= " ORDER BY cat.cat_title ";

            $db->setQuery($query);
            $this->_applications = $db->loadObjectList();
            foreach($this->_applications as $category){
                $total = 0;
                $query = "SELECT (SELECT count(job.id) FROM `#__js_job_jobs` AS job WHERE job.subcategoryid = subcat.id AND DATE(job.startpublishing) <= CURDATE()
                    AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                    FROM `#__js_job_subcategories` AS subcat
                    WHERE subcat.status = 1 AND subcat.categoryid = ".$category->id." ORDER BY subcat.ordering ASC LIMIT ".$subcategory_limit;
                $db->setQuery($query);
                $subcats = $db->loadObjectList();
                foreach ($subcats as $scat) {
                    $total += $scat->totaljobs;
                }
                $category->total_sub_jobs = $total;
            }
        }
        $filterlists = "";
        $filtervalues = "";

        $result[0] = $this->_applications;
        $result[1] = '';
        $result[2] = $filterlists;
        $result[3] = $filtervalues;

        return $result;
    }

    function subCategoriesByCatId($catid, $showall){
        if(!is_numeric($catid))
            return false;
        $db = $this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'subcategory_limit')
                $shownoofsubcats = $conf->configvalue;
        }
        if(!is_numeric($shownoofsubcats)){
            return "";
        }

        $tempshownoofsubcats = $shownoofsubcats + 1;

        $curdate = Factory::getDate()->format('Y-m-d');
        $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs`  AS job WHERE cat.id = job.subcategoryid AND job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate)." ) AS catinjobs ";

        $query = "SELECT cat.id AS subcatid , title ,CONCAT(cat.alias,'-',cat.id) AS subcategoryaliasid , ";
        $query .= $inquery;
        $query .= "FROM `#__js_job_subcategories` AS cat
                    WHERE cat.status = 1 AND cat.categoryid = ".$catid;
        if($showall)
            $query .= " ORDER BY cat.title ";
        else
            $query .= " ORDER BY cat.title LIMIT ".$tempshownoofsubcats;

        $db->setQuery($query);
        $this->_applications = $db->loadObjectList();
        $Itemid = Factory::getApplication()->input->get('itemid');
        $html = '';
        //for jobi template
        if(Factory::getApplication()->getTemplate() == 'jobi'){
           $wrapper = "jsjobi_subcat_wrapper";
           $anchor = "jsjobi-subcat-block-a";
           $title = "jsjobi-cat-title";
           $counter = "jsjobi-cat-counter";
        }else {
            $wrapper = "jsjobs_subcat_wrapper";
            $anchor = "jsjobs-subcat-block-a";
            $title = "jsjobs-cat-title";
            $counter = "jsjobs-cat-counter";
        }
        if($showall){
            $db->setQuery("SELECT cat_title FROM `#__js_job_categories` WHERE id=$catid");
            $ct = $db->loadResult();
            $html = '<div id="jsjobs_subcatpopups">
                        <span class="popup-title">
                            <span class="title">'.$ct.'</span>
                            <img src="'.Uri::root().'components/com_jsjobs/images/popup-close.png" onClick="hidepopup();" id="popup_cross" />
                        </span>
                        <div id="jsjobs_scroll_area">';
            foreach ($this->_applications as $obj) {
                $categoryaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($obj->subcategoryaliasid);
                $lnk = "index.php?option=com_jsjobs&c=job&view=job&layout=jobs&jobsubcat=".$categoryaliasid."&Itemid=".$Itemid;
                $html .= '<a id="jsjobs-subcat-popup-a" href="'.Route::_($lnk ,false).'">
                            <span class="jsjobs-cat-title"> '.Text::_(trim($obj->title)).'</span>
                            <span class="jsjobs-cat-counter">('.$obj->catinjobs.')</span>
                           </a>';
            }
            $html .= "</div></div>";
        }else{
            $total_results = count($this->_applications);
            if($total_results==0)
                return "";
            $num = $total_results > $shownoofsubcats ? $shownoofsubcats : $total_results;
            $html = "<div class='".$wrapper."'>";
            for ($i=0; $i < $num; $i++) {
                $obj = $this->_applications[$i];
                $lnk = "index.php?option=com_jsjobs&c=job&view=job&layout=jobs&jobsubcat=".$obj->subcategoryaliasid."&Itemid=".$Itemid;
                $html .= "<a id='".$anchor."' href=\"".Route::_($lnk ,false)."\">
                            <span class='".$title."'> ".Text::_(trim($obj->title))."</span>
                            <span class='".$counter."'>(".$obj->catinjobs.")</span>
                          </a>";
            }
            if($shownoofsubcats < $total_results)
                $html .= "<span id='showmore_p' onClick='showpopup(".$catid.");'>".Text::_('Show More')."</span></div>";
            else
                $html .= "</div>";
        }

        return $html;

    }

    function getMyJobsForCombo($uid, $title) {
        if (!is_numeric($uid))
            return $uid;
        $db = Factory::getDBO();
        $query = "SELECT  id, title FROM `#__js_job_jobs` WHERE jobstatus = 1 AND uid = " . $uid . " ORDER BY title ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $jobs = array();
        if ($title)
            $jobs[] = array('value' => Text::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $jobs[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $jobs;
    }

    function getJobDetails($jobid) { // this may not use
        // if (is_numeric($jobid) == false)
        //     return false;

        // $db = $this->getDBO();

        // $query = "SELECT job.*, cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
        //         ,  shift.title as shifttitle
        //         , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto
        //         , salarytype.title AS salarytype
        //         ,mineducation.title AS mineducationtitle
        //         , minexperience.title AS minexperiencetitle,agefrom.title AS agefrom,ageto.title AS ageto
        //         , country.name AS countryname, city.name AS cityname,careerlevel.title AS careerleveltitle
        //          FROM `#__js_job_jobs` AS job
        //          JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
        //          JOIN `#__js_job_companies` AS company ON job.companyid = company.id
        //          JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
        //          LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
        //          LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
        //          LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
        //          LEFT JOIN `#__js_job_heighesteducation` AS mineducation ON job.mineducationrange = mineducation.id
        //          LEFT JOIN `#__js_job_experiences` AS minexperience ON job.minexperiencerange = minexperience.id
        //          LEFT JOIN `#__js_job_shifts` AS shift ON job.shift = shift.id
        //          LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
        //          LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
        //          LEFT JOIN `#__js_job_ages` AS ageto ON job.ageto = ageto.id
        //          LEFT JOIN `#__js_job_ages` AS agefrom ON job.agefrom = agefrom.id
        //          LEFT JOIN `#__js_job_careerlevels` AS careerlevel ON job.careerlevel = careerlevel.id
        //          WHERE  job.id = " . $jobid;
        // $db->setQuery($query);
        // $details = $db->loadObject();

        // return $details;
    }

    function getMyJobs($u_id, $sortby, $limit, $limitstart, $vis_email, $jobid) {
        $result = array();
        $db = $this->getDBO();

        if (is_numeric($u_id) == false)
            return false;
        if (($vis_email == '') || ($jobid == ''))
            if (($u_id == 0) || ($u_id == ''))
                return false; //check if not visitor

        if ($this->_client_auth_key != "") {
            $fortask = "myjobs";
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $data['vis_email'] = $vis_email;
            $data['jobid'] = $jobid;
            $data['uid'] = $u_id;
            $data['sortby'] = $sortby;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['myjobs']) AND $return_server_value['myjobs'] == -1) { // auth fail
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "My Jobs";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
                $total = 0;
            } else {
                $parse_data = array();
                if (is_array($return_server_value)) {
                    foreach ($return_server_value['relationjsondata'] AS $rel_data) {
                        $parse_data[] = (object) $rel_data;
                    }
                    $this->_applications = $parse_data;
                    $total = $return_server_value['total'];
                } elseif ($return_server_value == false)
                    return false;
            }
        } else {
            //visitor jobs
            if (isset($jobid) && ($jobid != '')) {// if the jobid and email address is valid or not
                $query = "SELECT job.companyid
                                    FROM `#__js_job_jobs` AS job
                                    WHERE job.jobid = " . $db->quote($jobid);

                $db->setQuery($query);
                $companyid = $db->loadResult();
                if (!$companyid)
                    return false;
                $query = "SELECT count(company.id)
                                    FROM `#__js_job_companies` AS company
                                    WHERE company.id = " . $companyid;
                $db->setQuery($query);
                $company = $db->loadResult();
                if ($company == 0)
                    return false; // means no company exist
            }
            if (isset($vis_email) && ($vis_email != '')) {
                $query = "SELECT count(job.id)
                                    FROM `#__js_job_companies` AS company
                                    JOIN `#__js_job_jobs` AS job ON job.companyid = company.id
                                    WHERE company.contactemail = " . $db->quote($vis_email);
            } else {
                if(!is_numeric($u_id)){
                    return false;
                }
                $query = "SELECT count(job.id)
                                    FROM `#__js_job_jobs` AS job
                                    WHERE job.uid = " . $u_id;
            }
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total <= $limitstart)
                $limitstart = 0;

            if ((isset($vis_email) && isset($jobid)) && ($vis_email != '' && $jobid != '')) {
                $select_data = ",'visitor' AS visitor,company.contactemail AS contactemail";
                $where_data = " company.contactemail = " . $db->quote($vis_email);
            } else {
                if(!is_numeric($u_id)){
                    return false;
                }
                $select_data = " ";
                $where_data = "job.uid = " . $u_id;
            }

            $query = "SELECT job.title , job.params ,job.created ,job.jobcategory
                            ,job.noofjobs ,job.startpublishing ,job.stoppublishing
                            ,job.city ,job.status ,job.serverstatus
                            ,job.contactemail ,job.id
                            , cat.cat_title
                            , jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle,salarytype.title AS salarytypetitle
                            , company.name AS companyname, company.url
                            , salaryfrom.rangestart, salaryto.rangestart AS rangeend, country.name AS countryname
                            ,currency.symbol ,salaryto.rangestart AS salaryto
                            ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                            ,CONCAT(job.alias,'-',job.serverid) AS sjobaliasid
                            ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                            ,CONCAT(company.alias,'-',company.serverid) AS scompanyaliasid
                            ,(SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = job.id) AS totalapply
                            ,company.logofilename AS companylogo,company.id AS companyid ";
            $query .= $select_data;
            $from_data = " FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                            LEFT JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                            LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                            LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
                            LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid";
            $query.=$from_data;
            $query.=" WHERE " . $where_data . " ORDER BY  " . $sortby;
            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
            foreach ($this->_applications AS $jobdata) {   // for multicity select
                $multicitydata = $this->getMultiCityData($jobdata->id);
                if ($multicitydata != "")
                    $jobdata->city = $multicitydata;
            }
        }

        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(2); // jobs fields
        $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);


        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $fieldsordering;

        return $result;
    }

    function getJobforForm($job_id, $uid, $vis_jobid, $visitor) {
        $db = $this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if ($visitor != 1) {
            $userrole = $this->getJSModel('userrole')->getUserRoleByUid($uid);
            if ($userrole == 1) {
                $query = "SELECT count(company.id)
                            FROM `#__js_job_companies` AS company
                            WHERE company.uid = " . $uid;

                $db->setQuery($query);
                $user_has_company = $db->loadResult();
                if ($user_has_company == 0) {
                    $user_not_company = 3;
                    return $user_not_company;
                }
            }
        }
        if ($visitor == 1) {
            $fieldOrdering = $this->getJSModel('customfields')->getFieldsOrdering(2, true); // job fields
        } else {
            $fieldOrdering = $this->getJSModel('customfields')->getFieldsOrdering(2); // job fields
        }
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
        foreach ($fieldOrdering AS $fo) {
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

        if (($job_id != '') && ($job_id != 0)) {
            if (is_numeric($job_id) == false)
                return false;
                    $query = "SELECT job.id ,job.params,job.title ,job.iseducationminimax ,job.degreetitle ,job.noofjobs ,job.isexperienceminimax ,job.duration ,job.joblink ,job.jobapplylink ,job.zipcode ,job.startpublishing ,job.stoppublishing ,job.description ,job.agreement ,job.qualifications ,job.prefferdskills ,job.metadescription ,job.metakeywords ,job.video ,job.longitude ,job.latitude ,job.raf_gender ,job.raf_location ,job.raf_education ,job.raf_category ,job.raf_subcategory ,job.sendemail ,job.created , cat.cat_title, salary.rangestart, salary.rangeend
                                    ,job.companyid ,job.jobcategory ,job.subcategoryid ,job.jobtype ,job.jobstatus ,job.heighestfinisheducation ,job.shift ,job.educationminimax ,job.educationid ,job.mineducationrange ,job.maxeducationrange ,job.salaryrangefrom ,job.salaryrangeto
                                    ,job.salaryrangetype ,job.agefrom ,job.ageto ,job.experienceminimax ,job.experienceid ,job.minexperiencerange ,job.maxexperiencerange
                                    ,job.gender ,job.careerlevel ,job.workpermit ,job.requiredtravel ,job.currencyid, job.experiencetext, job.departmentid,job.uid

                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
                    LEFT JOIN `#__js_job_currencies` AS currency On currency.id = job.currencyid
                    WHERE job.id = " . $job_id . " AND job.uid = " . $uid;
                    $db->setQuery($query);
                    $this->_job = $db->loadObject();
                }
            // Getting data for visitor job
            if (isset($vis_jobid) && ($vis_jobid != '')) {
                $query = "SELECT job.id ,job.params ,job.title ,job.iseducationminimax ,job.degreetitle ,job.noofjobs
                ,job.isexperienceminimax ,job.experiencetext ,job.duration ,job.startpublishing ,job.stoppublishing ,job.description
                ,job.agreement ,job.qualifications ,job.prefferdskills ,job.metadescription ,job.metakeywords ,job.video
                ,job.longitude ,job.latitude ,job.raf_gender ,job.raf_location ,job.raf_education ,job.raf_category
                ,job.raf_subcategory ,job.sendemail ,job.created ,job.jobid
                , cat.cat_title, salary.rangestart, salary.rangeend
                ,job.companyid ,job.jobcategory ,job.subcategoryid ,job.jobtype ,job.jobstatus ,job.heighestfinisheducation ,job.shift ,job.educationminimax ,job.educationid ,job.mineducationrange ,job.maxeducationrange ,job.salaryrangefrom ,job.salaryrangeto
                ,job.salaryrangetype ,job.agefrom ,job.ageto ,job.experienceminimax ,job.experienceid ,job.minexperiencerange ,job.maxexperiencerange
                ,job.gender ,job.careerlevel ,job.workpermit ,job.requiredtravel ,job.currencyid,job.uid,job.zipcode, job.departmentid


                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
                    LEFT JOIN `#__js_job_currencies` AS currency On currency.id = job.currencyid
                    WHERE job.id = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                $this->_job = $db->loadObject();
            }
        //$countries = $this->countries->getCountries('');
        if ($visitor != 1) {
            $companies = $this->getJSModel('company')->getCompanies($uid);
        }

        $categories = $this->getJSModel('category')->getCategories(Text::_('Select Category'));
        if (isset($this->_job)) {
            if (empty($visitor))
                // for jobi template
                if(Factory::getApplication()->getTemplate() == 'jobi'){
                    $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox ' . $company_required . ' jsjobs-cbo jsjobi-form-select-field" ' . 'onChange="getdepartments(\'department\', this.value)"', 'value', 'text', $this->_job->companyid);
                    $lists['departments'] = HTMLHelper::_('select.genericList', $this->getJSModel('department')->getDepartmentsByCompanyId($this->_job->companyid, ''), 'departmentid', 'class="inputbox ' . $department_required . ' jsjobs-cbo  jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->departmentid);
                    $lists['jobcategory'] = HTMLHelper::_('select.genericList', $categories, 'jobcategory', 'class="inputbox ' . $cat_required . ' jsjobs-cbo jsjobi-form-select-field" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_job->jobcategory);
                    $lists['subcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('subcategory')->getSubCategoriesforCombo($this->_job->jobcategory, Text::_('Sub Category'), ''), 'subcategoryid', 'class="inputbox ' . $subcategory_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->subcategoryid);
                    $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype', 'class="inputbox ' . $jobtype_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->jobtype);
                    $lists['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus', 'class="inputbox ' . $jobstatus_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->jobstatus);
                    $lists['heighesteducation'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox ' . $education_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->heighestfinisheducation);
                    $lists['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift', 'class="inputbox ' . $jobshift_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->shift);

                    $lists['educationminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'educationminimax', 'class="inputbox jsjobi-form-select-field" style="width:100%;"' . '', 'value', 'text', $this->_job->educationminimax);
                    $lists['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'educationid', 'class="inputbox jsjobi-form-select-field ' . $education_required . '"  style="width:100%;"' . '', 'value', 'text', $this->_job->educationid);
                    $lists['minimumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Minimum')), 'mineducationrange', 'class="inputbox jsjobi-form-select-field  ' . $education_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->mineducationrange);
                    $lists['maximumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Maximum')), 'maxeducationrange', 'class="inputbox jsjobi-form-select-field  ' . $education_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->maxeducationrange);

                    $lists['salaryrangefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('From'), 1), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom jsjobi-form-select-field style="width:29%;margin-right:1%;"' . $jobsalaryrange_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->salaryrangefrom);
                    $lists['salaryrangeto'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('To'), 1), 'salaryrangeto', 'class="inputbox validate-salaryrangeto jsjobi-form-select-field style="width:29%;margin-right:1%;"' . $jobsalaryrange_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->salaryrangeto);
                    $lists['salaryrangetypes'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox jsjobs-cbo jsjobi-form-select-field" style="width:20%;"' . '', 'value', 'text', $this->_job->salaryrangetype);

                    $lists['agefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('From')), 'agefrom', 'class="inputbox validate-checkagefrom ' . $age_required . ' jsjobs-cbo jsjobi-form-select-field" ' . 'style="width:100px;"', 'value', 'text', $this->_job->agefrom);
                    $lists['ageto'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('To')), 'ageto', 'class="inputbox validate-checkageto ' . $age_required . ' jsjobs-cbo jsjobi-form-select-field" style="width:100px;"' . '', 'value', 'text', $this->_job->ageto);
                    $lists['experienceminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'experienceminimax', 'class="inputbox jsjobi-form-select-field " style="width:100%;"' . '', 'value', 'text', $this->_job->experienceminimax);
                    $lists['experience'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Select')), 'experienceid', 'class="inputbox jsjobi-form-select-field ' . $experience_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->experienceid);
                    $lists['minimumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'minexperiencerange', 'class="inputbox jsjobi-form-select-field ' . $experience_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->minexperiencerange);
                    $lists['maximumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'maxexperiencerange', 'class="inputbox jsjobi-form-select-field ' . $experience_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->maxexperiencerange);

                    $lists['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender', 'class="inputbox ' . $gender_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->gender);
                    $lists['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(Text::_('Select')), 'careerlevel', 'class="inputbox ' . $careerlevel_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->careerlevel);
                    $lists['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(Text::_('Select')), 'workpermit', 'class="inputbox ' . $workpermit_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->workpermit);
                    $lists['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel', 'class="inputbox ' . $requiredtravel_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $this->_job->requiredtravel);
                    $lists['sendemail'] = HTMLHelper::_('select.genericList', $this->getJSModel('emailtemplate')->getSendEmail(), 'sendemail', 'class="inputbox jsjobi-form-select-field ' . $sendemail_required . '" ' . '', 'value', 'text', $this->_job->sendemail);
                    $lists['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox jsjobs-cbo jsjobi-form-select-field" style="width:50px;"' . '', 'value', 'text', $this->_job->currencyid);
                    $multi_lists = $this->getJSModel('employer')->getMultiSelectEdit($this->_job->id, 1);

                }else {

                    $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox ' . $company_required . ' jsjobs-cbo" ' . 'onChange="getdepartments(\'department\', this.value)"', 'value', 'text', $this->_job->companyid);
                    $lists['departments'] = HTMLHelper::_('select.genericList', $this->getJSModel('department')->getDepartmentsByCompanyId($this->_job->companyid, ''), 'departmentid', 'class="inputbox ' . $department_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->departmentid);
                    $lists['jobcategory'] = HTMLHelper::_('select.genericList', $categories, 'jobcategory', 'class="inputbox ' . $cat_required . ' jsjobs-cbo" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_job->jobcategory);
                    $lists['subcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('subcategory')->getSubCategoriesforCombo($this->_job->jobcategory, Text::_('Sub Category'), ''), 'subcategoryid', 'class="inputbox ' . $subcategory_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->subcategoryid);
                    $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype', 'class="inputbox ' . $jobtype_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->jobtype);
                    $lists['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus', 'class="inputbox ' . $jobstatus_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->jobstatus);
                    $lists['heighesteducation'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox ' . $education_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->heighestfinisheducation);
                    $lists['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift', 'class="inputbox ' . $jobshift_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->shift);

                    $lists['educationminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'educationminimax', 'class="inputbox" style="width:100%;"' . '', 'value', 'text', $this->_job->educationminimax);
                    $lists['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'educationid', 'class="inputbox ' . $education_required . '"  style="width:100%;"' . '', 'value', 'text', $this->_job->educationid);
                    $lists['minimumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Minimum')), 'mineducationrange', 'class="inputbox  ' . $education_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->mineducationrange);
                    $lists['maximumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Maximum')), 'maxeducationrange', 'class="inputbox  ' . $education_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->maxeducationrange);

                    $lists['salaryrangefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('From'), 1), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom style="width:29%;margin-right:1%;"' . $jobsalaryrange_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->salaryrangefrom);
                    $lists['salaryrangeto'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('To'), 1), 'salaryrangeto', 'class="inputbox validate-salaryrangeto style="width:29%;margin-right:1%;"' . $jobsalaryrange_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->salaryrangeto);
                    $lists['salaryrangetypes'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox jsjobs-cbo" style="width:20%;"' . '', 'value', 'text', $this->_job->salaryrangetype);

                    $lists['agefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('From')), 'agefrom', 'class="inputbox validate-checkagefrom ' . $age_required . ' jsjobs-cbo" ' . 'style="width:100px;"', 'value', 'text', $this->_job->agefrom);
                    $lists['ageto'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('To')), 'ageto', 'class="inputbox validate-checkageto ' . $age_required . ' jsjobs-cbo" style="width:100px;"' . '', 'value', 'text', $this->_job->ageto);
                    $lists['experienceminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'experienceminimax', 'class="inputbox " style="width:100%;"' . '', 'value', 'text', $this->_job->experienceminimax);
                    $lists['experience'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Select')), 'experienceid', 'class="inputbox ' . $experience_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->experienceid);
                    $lists['minimumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'minexperiencerange', 'class="inputbox ' . $experience_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->minexperiencerange);
                    $lists['maximumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'maxexperiencerange', 'class="inputbox ' . $experience_required . ' " style="width:100%;"' . '', 'value', 'text', $this->_job->maxexperiencerange);

                    $lists['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender', 'class="inputbox ' . $gender_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->gender);
                    $lists['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(Text::_('Select')), 'careerlevel', 'class="inputbox ' . $careerlevel_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->careerlevel);
                    $lists['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(Text::_('Select')), 'workpermit', 'class="inputbox ' . $workpermit_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->workpermit);
                    $lists['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel', 'class="inputbox ' . $requiredtravel_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_job->requiredtravel);
                    $lists['sendemail'] = HTMLHelper::_('select.genericList', $this->getJSModel('emailtemplate')->getSendEmail(), 'sendemail', 'class="inputbox ' . $sendemail_required . '" ' . '', 'value', 'text', $this->_job->sendemail);
                    $lists['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox jsjobs-cbo" style="width:50px;"' . '', 'value', 'text', $this->_job->currencyid);
                    $multi_lists = $this->getJSModel('employer')->getMultiSelectEdit($this->_job->id, 1);
                }

        }else {
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
                $this->_config = $this->getJSModel('configurations')->getConfig('');
            }
            if (isset($companies))
                $lists['companies'] = HTMLHelper::_('select.genericList', $companies, 'companyid', 'class="inputbox ' . $company_required . ' jsjobs-cbo" ' . 'onChange="getdepartments(\'department\', this.value)"' . '', 'value', 'text', '');
            if (isset($companies[0]['value']) && $companies[0]['value'] != '')
                $lists['departments'] = HTMLHelper::_('select.genericList', $this->getJSModel('department')->getDepartmentsByCompanyId($companies[0]['value'], ''), 'departmentid', 'class="inputbox ' . $department_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
            // for jobi template
            if(Factory::getApplication()->getTemplate() == 'jobi'){
                $lists['jobcategory'] = HTMLHelper::_('select.genericList', $categories, 'jobcategory', 'class="inputbox jsjobi-form-select-field ' . $cat_required . ' jsjobs-cbo" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $defaultCategory);
                $lists['subcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('subcategory')->getSubCategoriesforCombo($defaultCategory, Text::_('Sub Category'), ''), 'subcategoryid', 'class="inputbox jsjobi-form-select-field ' . $subcategory_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
                $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype', 'class="inputbox ' . $jobtype_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $defaultJobtype);
                $lists['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus', 'class="inputbox ' . $jobstatus_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $defaultJobstatus);
                $lists['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift', 'class="inputbox ' . $jobshift_required . ' jsjobs-cbo jsjobi-form-select-field" ' . '', 'value', 'text', $defaultShifts);

                $lists['educationminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'educationminimax', 'class="inputbox jsjobi-form-select-field" style="width:100%;"' . '', 'value', 'text', '');
                $lists['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'educationid', 'class="inputbox jsjobi-form-select-field  ' . $education_required . '" style="width:100%;"' . '', 'value', 'text', $defaultEducation);
                $lists['minimumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Minimum')), 'mineducationrange', 'class="inputbox jsjobi-form-select-field  ' . $education_required . '" style="width:100%;"' . '', 'value', 'text', $defaultEducation);
                $lists['maximumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Maximum')), 'maxeducationrange', 'class="inputbox jsjobi-form-select-field  ' . $education_required . '" style="width:100%;"' . '', 'value', 'text', $defaultEducation);


                $lists['salaryrangefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('From'), 1), 'salaryrangefrom', 'class="inputbox jsjobi-form-select-field validate-salaryrangefrom ' . $jobsalaryrange_required . ' jsjobs-cbo" style="width:29%;margin-right:1%;"' . '', 'value', 'text', $defaultSalaryrange);
                $lists['salaryrangeto'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('To'), 1), 'salaryrangeto', 'class="inputbox jsjobi-form-select-field validate-salaryrangeto ' . $jobsalaryrange_required . ' jsjobs-cbo" style="width:29%;margin-right:1%;"' . '', 'value', 'text', $defaultSalaryrange);
                $lists['salaryrangetypes'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox jsjobi-form-select-field jsjobs-cbo" style="width:20%;"' . '', 'value', 'text', $defaultSalaryrangeType);

                $lists['agefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('From')), 'agefrom', 'class="inputbox jsjobi-form-select-field validate-checkagefrom ' . $age_required . ' jsjobs-cbo" style="width:49%;margin-right:1%;"' . '', 'value', 'text', $defaultAge);
                $lists['ageto'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('To')), 'ageto', 'class="inputbox jsjobi-form-select-field validate-checkageto ' . $age_required . ' jsjobs-cbo" style="width:50%;"' . '', 'value', 'text', $defaultAge);
                $lists['experienceminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'experienceminimax', 'class="inputbox jsjobi-form-select-field" ' . '', 'value', 'text', '');
                $lists['experience'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Select')), 'experienceid', 'class="inputbox jsjobi-form-select-field ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);
                $lists['minimumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'minexperiencerange', 'class="inputbox jsjobi-form-select-field ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);
                $lists['maximumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'maxexperiencerange', 'class="inputbox jsjobi-form-select-field ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);

                $lists['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender', 'class="inputbox jsjobi-form-select-field' . $gender_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
                $lists['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(Text::_('Select')), 'careerlevel', 'class="inputbox jsjobi-form-select-field ' . $careerlevel_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultCareerlevels);
                $lists['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(Text::_('Select')), 'workpermit', 'class="inputbox jsjobi-form-select-field ' . $workpermit_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_defaultcountry);
                $lists['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel', 'class="inputbox jsjobi-form-select-field ' . $requiredtravel_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
                $lists['sendemail'] = HTMLHelper::_('select.genericList', $this->getJSModel('emailtemplate')->getSendEmail(), 'sendemail', 'class="inputbox jsjobi-form-select-field ' . $sendemail_required . '" ' . '', 'value', 'text', '$this->_job->sendemail', '');
                $lists['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox jsjobi-form-select-field jsjobs-cbo" style="width:19%;margin-right:1%;"' . '', 'value', 'text', $defaultCurrencies);

            } else {
                $lists['jobcategory'] = HTMLHelper::_('select.genericList', $categories, 'jobcategory', 'class="inputbox ' . $cat_required . ' jsjobs-cbo" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $defaultCategory);
                $lists['subcategory'] = HTMLHelper::_('select.genericList', $this->getJSModel('subcategory')->getSubCategoriesforCombo($defaultCategory, Text::_('Sub Category'), ''), 'subcategoryid', 'class="inputbox ' . $subcategory_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
                $lists['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype', 'class="inputbox ' . $jobtype_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultJobtype);
                $lists['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus', 'class="inputbox ' . $jobstatus_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultJobstatus);
                $lists['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift', 'class="inputbox ' . $jobshift_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultShifts);

                $lists['educationminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'educationminimax', 'class="inputbox" style="width:100%;"' . '', 'value', 'text', '');
                $lists['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'educationid', 'class="inputbox  ' . $education_required . '" style="width:100%;"' . '', 'value', 'text', $defaultEducation);
                $lists['minimumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Minimum')), 'mineducationrange', 'class="inputbox  ' . $education_required . '" style="width:100%;"' . '', 'value', 'text', $defaultEducation);
                $lists['maximumeducationrange'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Maximum')), 'maxeducationrange', 'class="inputbox  ' . $education_required . '" style="width:100%;"' . '', 'value', 'text', $defaultEducation);


                $lists['salaryrangefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('From'), 1), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom ' . $jobsalaryrange_required . ' jsjobs-cbo" style="width:29%;margin-right:1%;"' . '', 'value', 'text', $defaultSalaryrange);
                $lists['salaryrangeto'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('To'), 1), 'salaryrangeto', 'class="inputbox validate-salaryrangeto ' . $jobsalaryrange_required . ' jsjobs-cbo" style="width:29%;margin-right:1%;"' . '', 'value', 'text', $defaultSalaryrange);
                $lists['salaryrangetypes'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox jsjobs-cbo" style="width:20%;"' . '', 'value', 'text', $defaultSalaryrangeType);

                $lists['agefrom'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('From')), 'agefrom', 'class="inputbox validate-checkagefrom ' . $age_required . ' jsjobs-cbo" style="width:49%;margin-right:1%;"' . '', 'value', 'text', $defaultAge);
                $lists['ageto'] = HTMLHelper::_('select.genericList', $this->getJSModel('ages')->getAges(Text::_('To')), 'ageto', 'class="inputbox validate-checkageto ' . $age_required . ' jsjobs-cbo" style="width:50%;"' . '', 'value', 'text', $defaultAge);
                $lists['experienceminimax'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', '');
                $lists['experience'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Select')), 'experienceid', 'class="inputbox ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);
                $lists['minimumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'minexperiencerange', 'class="inputbox ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);
                $lists['maximumexperiencerange'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'maxexperiencerange', 'class="inputbox ' . $experience_required . '" ' . '', 'value', 'text', $defaultExperiences);

                $lists['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender', 'class="inputbox' . $gender_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
                $lists['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(Text::_('Select')), 'careerlevel', 'class="inputbox ' . $careerlevel_required . ' jsjobs-cbo" ' . '', 'value', 'text', $defaultCareerlevels);
                $lists['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(Text::_('Select')), 'workpermit', 'class="inputbox ' . $workpermit_required . ' jsjobs-cbo" ' . '', 'value', 'text', $this->_defaultcountry);
                $lists['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel', 'class="inputbox ' . $requiredtravel_required . ' jsjobs-cbo" ' . '', 'value', 'text', '');
                $lists['sendemail'] = HTMLHelper::_('select.genericList', $this->getJSModel('emailtemplate')->getSendEmail(), 'sendemail', 'class="inputbox ' . $sendemail_required . '" ' . '', 'value', 'text', '$this->_job->sendemail', '');
                $lists['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'currencyid', 'class="inputbox jsjobs-cbo" style="width:19%;margin-right:1%;"' . '', 'value', 'text', $defaultCurrencies);
            }
        }

        $result[0] = $this->_job;
        $result[1] = $lists;

        $result[3] = $fieldOrdering; // job fields
        if ($job_id) { // not new
            $canaddreturn = $this->getPackageDetailByUid($uid);
            if (!defined('VALIDATE')) {
                define('VALIDATE', 'VALIDATE');
            }
            $result[4] = VALIDATE;
            if (isset($canaddreturn[2]) AND ( $canaddreturn[2] == 1)) {
                $result[5] = $canaddreturn; // handle enforce publishe readonly dates
            } else {
                $result[5] = $this->getPackageDetailByUid($uid); // package id
            }
        } else { // new
            $result[4] = $this->getJSModel('permissions')->checkPermissionsFor("ADD_JOB"); // can add
            $result[5] = $this->getPackageDetailByUid($uid); // package id
        }
        if (isset($uid) && $uid != 0)
            $result[6] = $this->getJSModel('employerpackages')->getAllPackagesByUid($uid, $job_id);
        $result[7] = 1; // for company check when add job

        if (isset($multi_lists) && $multi_lists != "")
            $result[8] = $multi_lists;
        return $result;
    }

    function getPackageDetailByUid($uid) {
        if (is_numeric($uid) == false)
            return false;
        $db = $this->getDbo();
        $query = "SELECT payment.id AS paymentid, package.id AS packageid, package.jobsallow, package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype, (SELECT COUNT(id) FROM #__js_job_jobs WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS jobavail
                    FROM #__js_job_paymenthistory AS payment
                    JOIN #__js_job_employerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=1)
                    WHERE uid = " . $uid . "
                    AND payment.transactionverified = 1 AND payment.status = 1 ";

        $db->setQuery($query);
        $packages = $db->loadObjectList();
        $packagedetail = array();
        if (!empty($packages)) {
            foreach ($packages AS $package) {
                if ($package->jobsallow != -1 && ($package->jobsallow - $package->jobavail) > 0){
                    $packagedetail[0] = $package->packageid;
                    $packagedetail[1] = $package->paymentid;
                    $packagedetail[2] = $package->enforcestoppublishjob;
                    $packagedetail[3] = $package->enforcestoppublishjobvalue;
                    $packagedetail[4] = $package->enforcestoppublishjobtype;
                }
            }
        } else {
            $packagedetail[0] = 0;
            $packagedetail[1] = 0;
            $packagedetail[2] = 0;
            $packagedetail[3] = 0;
            $packagedetail[4] = 0;
        }
        return $packagedetail;
    }

    function canAddNewJob($uid) {
        $db = $this->getDBO();
        if ($uid)
            if (is_numeric($uid) == false)
                return false;
        $query = "SELECT package.id, package.jobsallow, package.packageexpireindays, payment.id AS paymentid, payment.created
                    , package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
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
        } else { // user have valid pacakge
            $unlimited = 0;
            $jobsallow = 0;
            $valid_packages_ids = array();
            foreach ($valid_packages AS $job) {
                if ($unlimited == 0) {
                    if ($job->jobsallow != -1) {
                        $valid_packages_ids[] = $job->id;
                        $jobsallow = $job->jobsallow + $jobsallow;
                    } else {
                        $unlimited = 1;
                    }
                }
            }
            if ($unlimited == 0) { // user doesnot have the unlimited job package
                if ($jobsallow == 0) {
                    return JOB_LIMIT_EXCEEDS;
                } //can not add new job
                $valid_packages_ids = implode(', ', $valid_packages_ids);
                $query = "SELECT COUNT(jobs.id) AS totaljobs
                FROM `#__js_job_jobs` AS jobs
                WHERE jobs.packageid IN (".$valid_packages_ids.") AND  jobs.uid = " . $uid;

                $db->setQuery($query);
                $totlajob = $db->loadResult();

                if ($jobsallow <= $totlajob) {
                    return JOB_LIMIT_EXCEEDS;
                } else {
                    return VALIDATE;
                }
            } else { // user have unlimited job package
                return VALIDATE;
            }
        }
    }

    function getJobbyId($job_id) {
        $db = $this->getDBO();
        if (is_numeric($job_id) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $fortask = "viewjobbyid";
            $jsjobsharingobject = $this->getJSModel('jobsharingsite');
            $data['jobid'] = $job_id;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['viewjobbyid']) AND $return_server_value['viewjobbyid'] == -1) { // auth fail
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "View Job By Id";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
                $job_userfields = array();
            } else {
                $relation_data_array = json_decode($return_server_value['relationjsondata']);
                $job_userfields_array = "";
                if (isset($return_server_value['userfields']))
                    $job_userfields_array = json_decode($return_server_value['userfields'], true);
                $parsedata = array();
                $parsedata = (object) $relation_data_array;
                $this->_application = $parsedata;
                $job_userfields = $job_userfields_array;
            }
        }else {
            $query = "SELECT job.params,job.id,job.title,job.city,job.metakeywords,job.metadescription,job.description,job.created
                        , job.jobcategory,job.duration,job.hidesalaryrange,job.zipcode,job.iseducationminimax,job.degreetitle,job.isexperienceminimax
                        , job.startpublishing,job.requiredtravel,job.noofjobs,job.hits,job.stoppublishing,job.video,job.prefferdskills,job.qualifications,job.agreement,job.experiencetext,job.longitude,job.latitude
                        , cat.cat_title, subcat.title as subcategory, company.name as companyname, jobtype.title AS jobtypetitle
                        , jobstatus.title AS jobstatustitle, shift.title as shifttitle, company.url as companywebsite, company.contactname AS companycontactname, company.contactemail AS companycontactemail,company.since AS companysince,company.logofilename AS companylogo
                        , department.name AS departmentname, company.id companyid,job.educationminimax,job.experienceminimax
                        , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype
                        , education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
                        , experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
                        , currency.symbol,CONCAT(job.alias,'-',job.id) AS jobaliasid, agefrom.title AS agefromtitle, ageto.title AS agetotitle
                        ,CONCAT(company.alias,'-',company.id) AS companyaliasid, workpermit.name as workpermitcountry
                        ,company.endgolddate,company.endfeatureddate, job.jobapplylink,job.joblink,job.gender,careerlevel.title AS careerleveltitle
                        FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                            JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                            LEFT JOIN `#__js_job_ages` AS agefrom ON agefrom.id = job.agefrom
                            LEFT JOIN `#__js_job_ages` AS ageto ON ageto.id = job.ageto
                            LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
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
                            LEFT JOIN `#__js_job_countries` AS workpermit ON workpermit.id = job.workpermit
                            LEFT JOIN `#__js_job_careerlevels` AS careerlevel ON job.careerlevel = careerlevel.id
                            WHERE  job.id = " . $job_id;
          $db->setQuery($query);
          $this->_application = $db->loadObject();
          if($this->_application){
            $this->_application->multicity = $this->getJSModel('cities')->getLocationDataForView($this->_application->city);
          }


            $query = "UPDATE `#__js_job_jobs` SET hits = hits + 1 WHERE id = " . $job_id;

            $db->setQuery($query);
            if (!$db->execute()) {
                //return false;
            }
            $job_userfields = '';
        }

        $result[0] = $this->_application;
        $result[2] = $job_userfields; // job userfields
        $user = Factory::getUser();
        $visitor = $user->guest;
        if ($visitor == 1) {
            $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(2, true); // job fields
        } else {
            $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(2); // job fields
        }
        $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);
        $result[3] = $fieldsordering;

        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(1);
        $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);
        $result[4] = $fieldsordering;
        $listjobconfig = $this->getJSModel('configurations')->getConfigByFor('listjob');
        $user = Factory::getUser();
        $islistjobforvisitor = $this->getJSModel('common')->islistjobforvisitor();
        if ($islistjobforvisitor == 1) { //package expire or user not login
            $listjobconfig['lj_title'] = $listjobconfig['visitor_lj_title'];
            $listjobconfig['lj_category'] = $listjobconfig['visitor_lj_category'];
            $listjobconfig['lj_jobtype'] = $listjobconfig['visitor_lj_jobtype'];
            $listjobconfig['lj_jobstatus'] = $listjobconfig['visitor_lj_jobstatus'];
            $listjobconfig['lj_company'] = $listjobconfig['visitor_lj_company'];
            $listjobconfig['lj_companysite'] = $listjobconfig['visitor_lj_companysite'];
            $listjobconfig['lj_country'] = $listjobconfig['visitor_lj_country'];
            $listjobconfig['lj_state'] = $listjobconfig['visitor_lj_state'];
            $listjobconfig['lj_city'] = $listjobconfig['visitor_lj_city'];
            $listjobconfig['lj_salary'] = $listjobconfig['visitor_lj_salary'];
            $listjobconfig['lj_created'] = $listjobconfig['visitor_lj_created'];
            $listjobconfig['lj_noofjobs'] = $listjobconfig['visitor_lj_noofjobs'];
            $listjobconfig['lj_description'] = $listjobconfig['visitor_lj_description'];
        }
        $result[5] = $listjobconfig;

        return $result;
    }

    function storeJob() { //store job
        Factory::getSession()->checkToken() or die( 'Invalid Token' );
        $row = $this->getTable('job');
        $data = Factory::getApplication()->input->post->getArray();
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $curdate = date('Y-m-d H:i:s');
        $db = $this->getDBO();

        if (isset($this_config) == false)
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'jobautoapprove')
                $configvalue = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;

        }
        if ($data['id'] == '') { // only for new job
            $data['status'] = $configvalue;
        }

        if (($data['enforcestoppublishjob'] == 1)) {
            if ($data['enforcestoppublishjobtype'] == 1) {
                if ($dateformat == 'm/d/Y')
                    $data['stoppublishing'] = date("m/d/Y", strtotime(date("m/d/Y", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " day"));
                else
                    $data['stoppublishing'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " day"));
            } elseif ($data['enforcestoppublishjobtype'] == 2) {
                if ($dateformat == 'm/d/Y')
                    $data['stoppublishing'] = date("m/d/Y", strtotime(date("m/d/Y", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " week"));
                else
                    $data['stoppublishing'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " week"));
            } elseif ($data['enforcestoppublishjobtype'] == 3) {
                if ($dateformat == 'm/d/Y')
                    $data['stoppublishing'] = date("m/d/Y", strtotime(date("m/d/Y", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " month"));
                else
                    $data['stoppublishing'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " month"));
            }
        }
        //echo '<br>brfore'.$data['startpublishing'];
        //echo '<br>brfore'.$data['stoppublishing'];
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
        //echo '<br>after '.$data['startpublishing'];
        //echo '<br>after'.$data['stoppublishing'];

        // $data['startpublishing'] = HTMLHelper::_('date',strtotime($data['startpublishing']),"Y-m-d H:i:s" );
        // $data['stoppublishing'] = HTMLHelper::_('date',strtotime($data['stoppublishing']),"Y-m-d H:i:s" );
        $data['startpublishing'] = HTMLHelper::_('date',$data['startpublishing'],"Y-m-d H:i:s" ,true,true );
        $data['stoppublishing'] = HTMLHelper::_('date',$data['stoppublishing'],"Y-m-d H:i:s" ,true,true );
       

        $data['description'] = $this->getJSModel('common')->getHtmlInput('description');
        $data['qualifications'] = $this->getJSModel('common')->getHtmlInput('qualifications');
        $data['prefferdskills'] = $this->getJSModel('common')->getHtmlInput('prefferdskills');
        $data['agreement'] = $this->getJSModel('common')->getHtmlInput('agreement');

        // random generated jobid
        if (!empty($data['alias']))
            $jobalias = $this->getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $jobalias = $this->getJSModel('common')->removeSpecialCharacter($data['title']);

        $jobalias = strtolower(str_replace(' ', '-', $jobalias));
        $data['alias'] = $jobalias;
        $data['jobid'] = $this->getJobId();
    //custom field code start
		$jobparams = array();
        if(isset($data['id']) && is_numeric($data['id'])){
            $db = Factory::getdbo();
            $query = "SELECT params FROM `#__js_job_jobs` WHERE id = ".$data['id'];
            $db->setQuery($query);
            $temp = $db->loadResult();                
            if(!empty($temp)){
                $jobparams = json_decode($temp, true);
            }
        }
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $userfield = $this->getJSModel('customfields')->getUserfieldsfor(2);
        $params = array();
        $forfordelete = '';

        foreach ($userfield AS $ufobj) {
            $vardata = '';
            if($ufobj->userfieldtype == 'file'){
                if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 0){
                    $vardata = $data[$ufobj->field.'_2'];
                }else{
                    $c_file = $this->_jinput->files->get($ufobj->field);
                    if (isset($c_file['name'])) {
                        $vardata = $c_file['name'];
                    }
                }
                $customflagforadd=true;
                $custom_field_namesforadd[]=$ufobj->field;
            }else{
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $customflagfordelete = true;
                $forfordelete = $ufobj->field;
                //$custom_field_namesfordelete[]= $data[$ufobj->field.'_2']; //security issue
				$custom_field_namesfordelete[]= $jobparams[$ufobj->field];
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
                    $unpublihsedFields = $this->getJSModel('customfields')->getUnpublishedFieldsFor(2);
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

        }
		$params = json_encode($params);
        $data['params'] = $params;


    //custom field code end




        if (!$row->bind($data)) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        $check_return = $row->check();
        if ($check_return != 1) {
            $this->setError($row->getError());
            echo $row->getError();
            return $check_return;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }

        if (isset($data['city'])){
            if(!empty($data['city'])){
                $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
                if (isset($storemulticity) AND ( $storemulticity == false))
                    return false;
            }
        }


        if ($data['id'] == '') { // only for new job
            $return_option = $this->getJSModel('emailtemplate')->getEmailOption('add_new_job' , 'employer');
            if($return_option == 1){
                $this->getJSModel('emailtemplate')->sendMailtoEmployerNewJob($row->id, $data['uid']);
            }
            $return_option = $this->getJSModel('emailtemplate')->getEmailOption('add_new_job' , 'admin');
            if($return_option == 1){
                $this->getJSModel('adminemail')->sendMailtoAdmin($row->id, $data['uid'], 2);
            }
            if ($data['status'] == 1) { // if job approved

            }
        }

        // new
        //removing custom field
        if($customflagfordelete == true){
            foreach ($custom_field_namesfordelete as $key) {
                $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($row->id,$key ,1,2);
            }
        }
        //storing custom field attachments
        if($customflagforadd == true){
            $filetypemismatch = 0;
            $file_size_increase = 0;
            foreach ($custom_field_namesforadd as $key) {
                $c_file = $this->_jinput->files->get($key);
                if ($c_file['size'] > 0) { // logo
                    $res = $this->getJSModel('common')->uploadOrDeleteFileCustom($row->id,$key ,0,2);
                    if ($res == 6) {
                        $filetypemismatch = 1;
                    } elseif ($res == 5) {
                        $file_size_increase = 1;
                    }
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
                $cat_title = $this->getJSModel('category')->getTitleByCategory((int)$data['jobcategory']);
                if ($cat_title) {
                    $job_ai_string .= $cat_title . ' ';
                }
            }

            // new entires
            if (!empty($data['subcategoryid'])) {
                $sub_cat_title = $this->getJSModel('category')->getTitleByCategory((int)$data['subcategoryid']);
                if ($sub_cat_title) {
                    $job_ai_string .= $sub_cat_title . ' ';
                }
            }
            if (!empty($data['agefrom'])) {
                $age_title = $this->getJSModel('ages')->getTitleById((int)$data['agefrom']);
                if ($age_title) {
                    $job_ai_string .= $age_title . ' ';
                }
            }
            if (!empty($data['ageto'])) {
                $age_title = $this->getJSModel('ages')->getTitleById((int)$data['ageto']);
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
                $education .= ' '.$this->getJSModel('highesteducation')->getTitleById((int)$data['educationid']);
            } else {
                $mineducation = $this->getJSModel('highesteducation')->getTitleById((int)$data['mineducationrange']);
                $maxeducation = $this->getJSModel('highesteducation')->getTitleById((int)$data['maxeducationrange']);
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
                $experience .= ' '.$this->getJSModel('experience')->getTitleById((int)$data['experienceid']);
            } else {
                $minexperience = $this->getJSModel('experience')->getTitleById((int)$data['minexperiencerange']);
                $maxexperience = $this->getJSModel('experience')->getTitleById((int)$data['maxexperiencerange']);
                $experience = Text::_($minexperience) . ' - ' . Text::_($maxexperience);
            }

            if($data['experiencetext'] != ''){
                $experience .= ' '.$data['experiencetext'];
            }
             if($experience != ''){
                $job_ai_string .= ' '.$experience . ' ';
            }

            if (!empty($data['jobtype'])) {
                $jobtype_title = $this->getJSModel('jobtype')->getTitleById((int)$data['jobtype']);
                if ($jobtype_title) {
                    $job_ai_string .= $jobtype_title . ' ';
                }
            }

            if (!empty($data['jobstatus'])) {
                $jobstatus_title = $this->getJSModel('jobstatus')->getTitleById((int)$data['jobstatus']);
                if ($jobstatus_title) {
                    $job_ai_string .= $jobstatus_title . ' ';
                }
            }

            if (!empty($data['shift'])) {
                $jobshift_title = $this->getJSModel('jobshift')->getTitleById((int)$data['shift']);
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
                $salaryrangefrom_title = $this->getJSModel('salaryrange')->getTitleById((int)$data['salaryrangefrom']);
                // if ($salaryrangefrom_title) {
                //     $job_ai_string .= $salaryrangefrom_title . ' ';
                // }
            }

            if (!empty($data['salaryrangeto'])) {
                $salaryrangeto_title = $this->getJSModel('salaryrange')->getTitleById((int)$data['salaryrangeto']);
                // if ($salaryrangeto_title) {
                //     $job_ai_string .= $salaryrangeto_title . ' ';
                // }
            }

            if (!empty($data['salaryrangetype'])) {
                $salaryrangetype_title = $this->getJSModel('salaryrangetype')->getTitleById((int)$data['salaryrangetype']);
                // if ($salaryrangetype_title) {
                //     $job_ai_string .= $salaryrangetype_title . ' ';
                // }
            }
            $currency_align = $this->getJSModel('configurations')->getConfigValue('currency_align');
            $salary = $this->getJSModel('common')->getSalaryRangeView($currencyid_symbol, $salaryrangefrom_title, $salaryrangeto_title, $salaryrangetype_title, $currency_align);
            if($salary != ''){
                    $job_ai_string .= $salary . ' ';
            }
            // salary end

            if (!empty($data['careerlevel'])) {
                $careerlevel_title = $this->getJSModel('careerlevel')->getTitleById((int)$data['careerlevel']);
                if ($careerlevel_title) {
                    $job_ai_string .= $careerlevel_title . ' ';
                }
            }

            if (!empty($data['city'])) {
                $location_string = $this->getJSModel('cities')->getLocationDataForView($data['city']);
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
                $workpermit_string = $this->getJSModel('countries')->getCountryNameById($data['workpermit']);
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

            $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
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
        $db = Factory::getDBO();
        if (!is_numeric($jobid))
            return false;
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

    function deleteJob($jobid, $uid, $vis_email, $vis_jobid) {
        $db = $this->getDBO();
        $row = $this->getTable('job');
        $serverjodid = 0;
        if (($vis_email == '') || ($vis_jobid == '')) { // if employer try to delete their job
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
            if (is_numeric($jobid) == false)
                return false;
            if ($this->_client_auth_key != "") {
                $query = "SELECT job.serverid AS id
                                FROM `#__js_job_jobs` AS job
                                WHERE job.id = " . $jobid;
                $db->setQuery($query);
                $s_job_id = $db->loadResult();
                $serverjodid = $s_job_id;
            }
        } else {
            if ($this->_client_auth_key != "") {
                $query = "SELECT job.serverid AS id FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
                            WHERE job.jobid = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                $s_job_id = $db->loadResult();
                $serverjodid = $s_job_id;
            }
        }
        $returnvalue = $this->jobCanDelete($jobid, $uid, $vis_email, $vis_jobid);
        if ($returnvalue == 1) {

            if (($vis_email == '') || ($vis_jobid == '')) {
                $cid = $jobid;
            } else {
                $query = "SELECT job.id AS id FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
                        WHERE job.jobid = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                $cid = $db->loadResult();
            }

                    if (is_numeric($cid) == false)
                        return false;
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


            if (($vis_email == '') || ($vis_jobid == '')) { // if jobseeker try to delete their job
                if (!$row->delete($jobid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            } else {
                $query = "SELECT job.id AS id FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
                        WHERE job.jobid = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                $jobid = $db->loadResult();
                $query = "DELETE FROM `#__js_job_jobs` WHERE jobid = " . $db->quote($jobid);
                $db->setQuery($query);
                if (!$db->execute()) {
                    return false;
                }
            }
            if (is_numeric($jobid) == false)
                        return false;
            $query = "DELETE FROM `#__js_job_jobcities` WHERE jobid = " . $jobid;
            $db->setQuery($query);
            if (!$db->execute()) {
                return false;
            }
            $this->getJSModel('emailtemplate')->sendDeleteMail( $cid , 2);
            if ($serverjodid != 0) {
                $data = array();
                $data['id'] = $serverjodid;
                $data['referenceid'] = $jobid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletejob';
                $jsjobsharingobject = $this->getJSModel('jobsharingsite');
                $return_value = $jsjobsharingobject->delete_JobSharing($data);
                $this->getJSModel('jobtemp')->updateJobTemp();
                $job_log_object = $this->getJSModel('log');
                $job_log_object->log_Delete_JobSharing($return_value);
            }
        } else
            return $returnvalue;

        return true;
    }

    function jobCanDelete($jobid, $uid, $vis_email, $vis_jobid) {
        if (is_numeric($uid) == false)
            return false;
        $db = $this->getDBO();
        if ($jobid)
            if (is_numeric($jobid) == false)
                return false;
        if ((isset($vis_email) && $vis_email != '') && (isset($vis_jobid) && $vis_jobid != '')) {
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
                                WHERE job.jobid = " . $db->quote($vis_jobid);
        } else {
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
                                WHERE job.id = " . $jobid . " AND job.uid = " . $uid;
        }
        $db->setQuery($query);
        $jobtotal = $db->loadResult();

        if ($jobtotal > 0) { // this job is same user
            $query = "SELECT COUNT(apply.id) FROM `#__js_job_jobapply` AS apply
                                    WHERE apply.jobid = " . $jobid;

            $query = "SELECT
                                    ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = " . $jobid . ")
                                    AS total ";
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        } else
            return 3; //    this job is not of this user
    }

    function getMultiCityData($jobid) {
        if (is_numeric($jobid) === false)
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
                $multicity = (strlen($multicitydata) > 35) ? substr($multicitydata, 0, 35) . '...' : $multicitydata;
                return $multicity;
            } else
                return;
        }
    }

    function getJobId() {
        $db = $this->getDBO();
        $query = "Select jobid from `#__js_job_jobs`";
        do {

            $jobid = "";
            $length = 9;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
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


    function getJobsState($defaultcountry, $theme) {

        $db = Factory::getDBO();
        $config = $this->getJSModel('configurations')->getConfig('');
        $dateformat = $config[1];
        $default_country = $config[3];
        $this->getJSModel('common')->setTheme();

        $curdate = date('Y-m-d');
        $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs` AS job WHERE state.code = job.state AND job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . " ) as jobsbystate";
        $query = "SELECT  DISTINCT state.id, state.name,state.code, state.countrycode, ";
        $query .= $inquery;
        $query .= " FROM `#__js_job_states` AS state
                    LEFT JOIN `#__js_job_jobs`  AS job ON state.code = job.state
                    WHERE state.enabled = " . $db->Quote('Y');
        if ($defaultcountry)
            $query .= " AND state.countrycode = " . $db->quote($default_country);
        $query .= " ORDER BY state.name ";
        $db->setQuery($query);

        $states = $db->loadObjectList();
        $query2 = "SELECT job.state, job.country, count(job.id) AS jobsbystate FROM `#__js_job_jobs` AS job WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . " AND job.state != '' ";
        if ($defaultcountry)
            $query2 .= " AND job.country = " . $db->quote($default_country);
        $query2 .= " AND NOT EXISTS ( SELECT id FROM `#__js_job_states` WHERE code = job.state) ";
        $query2 .= " GROUP BY job.state";

        $db->setQuery($query2);
        $states2 = $db->loadObjectList();

        $result[0] = $states;
        $result[1] = $states2;
        $result[2] = $trclass;

        return $result;
    }

    function getListNewestJobs($uid, $city_filter, $cmbfiltercountry, $filterjobcategory, $filterjobsubcategory, $filterjobtype, $txtfilterlongitude, $txtfilterlatitude, $txtfilterradius, $cmbfilterradiustype, $jobcountry, $jobstate, $limit, $limitstart) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if ($filterjobtype != '')
            if (is_numeric($filterjobtype) == false)
                return false;
        if ($filterjobcategory != '')
            if (is_numeric($filterjobcategory) == false)
                return false;
        $db = $this->getDBO();
        $result = array();
        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        }

        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'filter_cat_jobtype_fields_width')
                $cat_jobtype_fields_width = $conf->configvalue;
            if ($conf->configname == 'defaultcountry')
                $defaultcountry = $conf->configvalue;
            if ($conf->configname == 'hidecountry')
                $hidecountry = $conf->configvalue;
        }
        $radiuslength = '';
        switch ($cmbfilterradiustype) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        $curdate = date('Y-m-d H:i:s');
        $variables['txtfilterlongitude'] = $txtfilterlongitude;
        $variables['txtfilterlatitude'] = $txtfilterlatitude;
        $variables['txtfilterradius'] = $txtfilterradius;
        $variables['radiuslength'] = $radiuslength;
        $variables['city_filter'] = $city_filter;
        $variables['cmbfilter_country'] = $cmbfiltercountry;
        $variables['jobstate'] = $jobstate;
        $variables['jobcountry'] = $jobcountry;
        $variables['filterjobtype'] = $filterjobtype;
        $variables['filterjobcategory'] = $filterjobcategory;
        $variables['filterjobsubcategory'] = $filterjobsubcategory;
        $variables['limitstart'] = $limitstart;
        $variables['limit'] = $limit;

        if ($this->_client_auth_key == "") {
            $return = $this->getLocalJobs($variables);
            $this->_applications = $return['jobs'];
            $total = $return['total'];
        } else { // job sharing listing
            $session = Factory::getApplication()->getSession();
            if ($this->_jinput->getArray()) {
                $checkfilterdefaultvalue = 0;
                if ($this->_jinput->post->get('filter_jobcategory', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('filter_jobsubcategory', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('filter_jobtype', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('filter_longitude', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('filter_latitude', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('filter_radius', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('cmbfilter_country', '') != "")
                    $checkfilterdefaultvalue = 1;
                if ($this->_jinput->post->get('txtfilter_city', '') != "")
                    $checkfilterdefaultvalue = 1;
                $postfilter = ($checkfilterdefaultvalue == 0 ? "" : 1);
            } else
                $postfilter = "";

            if ((empty($postfilter)) AND ( $city_filter == "") AND ( $cmbfiltercountry == "") AND ( $filterjobcategory == "") AND ( $filterjobsubcategory == "") AND ( $filterjobtype == "") AND ( $txtfilterlongitude == "") AND ( $txtfilterlatitude == "") AND ( $txtfilterradius == "") AND ( $jobcountry == "") AND ( $jobstate == "")) {   // filter is null
                if ($limitstart < 100) { // within 100
                    $default_sharing_loc = $this->getJSModel('configurations')->getDefaultSharingLocation('', '');
                    if (isset($default_sharing_loc['defaultsharingcity']) AND ( $default_sharing_loc['defaultsharingcity'] != '')) {
                        $variables['city_filter'] = $default_sharing_loc['defaultsharingcity'];
                    } elseif (isset($default_sharing_loc['defaultsharingstate']) AND ( $default_sharing_loc['defaultsharingstate'] != '')) {
                        $variables['jobstate'] = $default_sharing_loc['defaultsharingstate'];
                    } elseif (isset($default_sharing_loc['filtersharingcountry']) AND ( $default_sharing_loc['filtersharingcountry'] != '')) {
                        $variables['jobcountry'] = $default_sharing_loc['filtersharingcountry'];
                    } elseif (isset($default_sharing_loc['defaultsharingcountry']) AND ( $default_sharing_loc['defaultsharingcountry'] != '')) {
                        $variables['jobcountry'] = $default_sharing_loc['defaultsharingcountry'];
                    }
                    $data = $this->getJSModel('server')->getJobsFromServerAndFill($variables);
                    $this->_applications = $data['jobs'];
                    $total = $data['total'];
                } else { // above 100
                    $data = $this->getJSModel('server')->getJobsFromServerFilter($variables);
                    $this->_applications = $data['jobs'];
                    $total = $data['total'];
                }
            } else { // filter is not null
                $data = $this->getJSModel('server')->getJobsFromServerFilter($variables);
                $this->_applications = $data['jobs'];
                $total = $data['total'];
            }
        }


        $jobtype = $this->getJSModel('jobtype')->getJobType(Text::_('Select Job Type'));
        $jobstatus = $this->getJSModel('jobstatus')->getJobStatus(Text::_('Select Job Status'));
        $heighesteducation = $this->getJSModel('highesteducation')->getHeighestEducation(Text::_('Select Education'));

        $job_categories = $this->getJSModel('category')->getCategories(Text::_('Select Category'));
        if ($filterjobcategory == '')
            $categoryid = 1;
        else
            $categoryid = $filterjobcategory;
        $job_subcategories = $this->getJSModel('subcategory')->getSubCategoriesforCombo($categoryid, Text::_('Sub Category'), $value = '');
        $job_salaryrange = $this->getJSModel('salaryrange')->getJobSalaryRange(Text::_('Select Salary Range'), '');
        $countries = $this->getJSModel('server')->getSharingCountries(Text::_('Select Country'));

        $filterlists['country'] = HTMLHelper::_('select.genericList', $countries, 'cmbfilter_country', 'class="inputbox"  style="width:' . $cat_jobtype_fields_width . 'px;" ' . '', 'value', 'text', $cmbfiltercountry);
        $filterlists['jobcategory'] = HTMLHelper::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" style="width:' . $cat_jobtype_fields_width . 'px;" ' . 'onChange=fj_getsubcategories(\'td_jobsubcategory\',this.value);', 'value', 'text', $filterjobcategory);
        $filterlists['jobsubcategory'] = HTMLHelper::_('select.genericList', $job_subcategories, 'filter_jobsubcategory', 'class="inputbox" style="width:' . $cat_jobtype_fields_width . 'px;" ' . '', 'value', 'text', $filterjobsubcategory);
        $filterlists['jobtype'] = HTMLHelper::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" style="width:' . $cat_jobtype_fields_width . 'px;"  ' . '', 'value', 'text', $filterjobtype);

        $location = $this->getJSModel('cities')->getAddressDataByCityName('', $city_filter);
        if (isset($location[0]->name))
            $filtervalues['location'] = $location[0]->name;
        else
            $filtervalues['location'] = "";

        $filtervalues['city'] = $city_filter;
        $filtervalues['radius'] = $txtfilterradius;
        $filtervalues['longitude'] = $txtfilterlongitude;
        $filtervalues['latitude'] = $txtfilterlatitude;

        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(2); // jobs fields
        $fieldsordering = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $filterlists;
        $result[3] = $filtervalues;
        $result[4] = $fieldsordering;
        $result[5] = false;
        $result[6] = false;

        return $result;
    }

    function getLocalJobs($variables) {
        $db = Factory::getDbo();
        $selectdistance = " ";
        if ($variables['txtfilterlongitude'] != '' && $variables['txtfilterlatitude'] != '' && $variables['txtfilterradius'] != ''  &&  (is_numeric($variables['txtfilterlongitude']) && is_numeric($variables['txtfilterlatitude']) && is_numeric($variables['txtfilterradius']) )) {
            $radiussearch = " acos((SIN( PI()* " . $variables['txtfilterlatitude'] . " /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* " . $variables['txtfilterlatitude'] . " /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* " . $variables['txtfilterlongitude'] . " /180)))* " . $variables['radiuslength'] . " <= " . $variables['txtfilterradius'];
        }

        $wherequery = '';

        if ($variables['filterjobtype'] != '' && $variables['filterjobtype'] != 0 && is_numeric($variables['filterjobtype']))
            $wherequery .= " AND job.jobtype = " . $variables['filterjobtype'];
        if ($variables['filterjobcategory'] != '' && $variables['filterjobcategory'] != 0 && is_numeric($variables['filterjobcategory']))
            $wherequery .= " AND job.jobcategory = " . $variables['filterjobcategory'];
        if ($variables['filterjobsubcategory'] != '' && $variables['filterjobsubcategory'] != 0 && is_numeric($variables['filterjobsubcategory']))
            $wherequery .= " AND job.subcategoryid = " . $variables['filterjobsubcategory'];
        if ($variables['city_filter'] != '' && $variables['city_filter'] != 0 && is_numeric($variables['city_filter']))
            $wherequery .= " AND mcity.cityid = " . $variables['city_filter'];
        if ($variables['jobcountry'] != '' && $variables['jobcountry'] != 0 && is_numeric($variables['jobcountry'])) {
            $wherequery.=" AND city.countryid=" . $variables['jobcountry'];
        }
        if ($variables['jobstate'] != '' && $variables['jobstate'] != 0 && is_numeric($variables['jobstate'])) {
            $wherequery.=" AND city.stateid=" . $variables['jobstate'];
        }
        if (isset($radiussearch))
            $wherequery .= " AND $radiussearch";

        $curdate = date('Y-m-d H:i:s');
        $query = "SELECT COUNT(DISTINCT job.id) FROM `#__js_job_jobs` AS job
                        LEFT JOIN `#__js_job_jobcities` AS mcity ON job.id = mcity.jobid
                        LEFT JOIN `#__js_job_cities` AS city ON city.id = mcity.cityid
                        WHERE job.status = 1
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $variables['limitstart'])
            $variables['limitstart'] = 0;

        $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                        , company.id AS companyid, company.name AS companyname, company.url, company.logofilename AS companylogo
                        , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salaytype
                        , currency.symbol
                        ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                        ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                        ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        LEFT JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `#__js_job_jobcities` AS mcity ON job.id = mcity.jobid
                        LEFT JOIN `#__js_job_cities` AS city ON city.id = mcity.cityid
                        LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id
                        WHERE job.status = 1
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

        $query .= $wherequery . " ORDER BY  job.startpublishing DESC";

        $db->setQuery($query, $variables['limitstart'], $variables['limit']);
        $this->_applications = $db->loadObjectList();
        foreach ($this->_applications AS $jobdata) {   // for multicity select
            $multicitydata = $this->getMultiCityData($jobdata->id);
            if ($multicitydata != "")
                $jobdata->city = $multicitydata;
        }
        $data['jobs'] = $this->_applications;
        $data['total'] = $total;
        return $data;
    }

    function getJobTypes() {
        if($this->_client_auth_key != ""){
            $result = $this->getSharingAdminModel('jobbytypes')->getJobByTypes();
        }else{
            $db = Factory::getDbo();
            $wherequery = '';
            $curdate = Factory::getDate()->format('Y-m-d');
            $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs`  AS job WHERE jobtype.id = job.jobtype AND job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate);
            $inquery .= $wherequery . " ) as typeinjobs";

            $query = "SELECT  DISTINCT jobtype.id, jobtype.title,CONCAT(REPLACE(LOWER(jobtype.title),' ','-'),'-',jobtype.id) AS jobtypealiasid, ";
            $query .= $inquery;
            $query .= " FROM `#__js_job_jobtypes` AS jobtype
                        LEFT JOIN `#__js_job_jobs` AS job ON jobtype.id = job.jobtype
                        WHERE jobtype.isactive = 1 ";
            $query .= " ORDER BY jobtype.title ";

            $db->setQuery($query);
            $result = $db->loadObjectList();
        }
        return $result;
    }

    function getNewestJobsForMap($noofjobs) {
        if($noofjobs)
            if(!is_numeric($noofjobs)) return false;

        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $data_directory = $this->getJSModel('configurations')->getConfigValue('data_directory');
        $config['default_longitude'] = $this->getJSModel('configurations')->getConfigValue('default_longitude');
        $config['default_latitude'] = $this->getJSModel('configurations')->getConfigValue('default_latitude');
        $config['google_map_api_key'] = $this->getJSModel('configurations')->getConfigValue('google_map_api_key');
        $curdate = Factory::getDate()->format('Y-m-d');

        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $query = "SELECT job.id,job.title, job.jobcategory, job.created, cat.cat_title,subcat.title as subcat_title
            , job.city, job.latitude, job.longitude
            , company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo, jobtype.title AS jobtypetitle
            $alias $companyaliasid

            FROM `#__js_job_jobs` AS job
            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
            LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
            WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
            ORDER BY created DESC LIMIT {$noofjobs}";
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        $result[2] = $dateformat;
        $result[3] = $data_directory;
        $result[4] = $config;
        foreach ($result[0] AS $job) {
            if (empty($job->latitude) || empty($job->longitude)) {
                $query = "SELECT city.cityName AS cityname, country.name AS countryname
                            FROM `#__js_job_jobcities` AS job
                            JOIN `#__js_job_cities` AS city ON city.id = job.cityid
                            LEFT JOIN `#__js_job_countries` AS country ON country.id = city.countryid
                            WHERE job.jobid = $job->id";
                $db->setQuery($query);
                $job->multicity = $db->loadObjectList();
            }
        }
        return $result;
    }

    // Newwwwwwwwwwww

    private function makeQueryFromArray($for,$array){
        if(empty($array)) return false;
        $db = Factory::getDbo();
        if(!is_array($array) && $for != 'metakeywords' && $for != 'city'){
            $newarray[] = $array;
            $array = $newarray;
        }
        $qa = array();
        switch ($for) {
            case 'metakeywords':
                $array = explode(" ", $array);
                $total = count($array);
                if($total>5) $total = 5;
                for ($i=0; $i<$total; $i++) {
                    $qa[] = "job.metakeywords LIKE ".$db->quote($array[$i]);
                }
            break;
            case 'company':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.companyid = $item";
                    }
                }
            break;
            case 'category':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.jobcategory = $item";
                    }
                }
            break;
            case 'jobsubcategory':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.subcategoryid = $item";
                    }
                }
            break;
            case 'careerlevel':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.careerlevel = $item";
                    }
                }
            break;
            case 'jobtype':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.jobtype = $item";
                    }
                }
            break;
            case 'jobstatus':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.jobstatus = $item";
                    }
                }
            break;
            case 'shift':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.shift = $item";
                    }
                }
            break;
            case 'education':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.educationid = $item";
                    }
                }
            break;
            case 'city':
                $array = explode(',', $array);
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.city LIKE ".$db->Quote('%'.$item.'%');
                    }
                }
            break;
            case 'workpermit':
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = "job.workpermit LIKE ".$db->Quote('%'.$item.'%');
                    }
                }
            break;
            default:
                return false;
            break;
        }
        $query = implode(" OR ", $qa);
        return $query;
    }

    private function getSaveSearchForView($searchid , &$filter_search){
        if(!is_numeric($searchid)) return false;
        $db = $this->getDBO();

        $query = "SELECT * FROM `#__js_job_jobsearches` WHERE id = ".$searchid;

        $db->setQuery($query);
        $save_searched_fields = $db->loadObject();
        if (isset($save_searched_fields->searchparams) && $save_searched_fields->searchparams != null) {
            $result = json_decode($save_searched_fields->searchparams);
        }
        $inquery = "";
        if( isset($result->metakeywords) && $result->metakeywords){
            $filter_search['metakeywords'] = $result->metakeywords;
            $res = $this->makeQueryFromArray('metakeywords',$result->metakeywords);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->jobtitle) && $result->jobtitle ){
            $filter_search['jobtitle'] = $result->jobtitle;
            $inquery .= " AND job.title LIKE ".$db->Quote('%'.$result->jobtitle.'%');
        }
        if( isset($result->company) && $result->company ){
            $filter_search['company'] = $result->company;
            $res = $this->makeQueryFromArray('company',$result->company);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->category) && $result->category ){
            $filter_search['category'] = $result->category;
            $res = $this->makeQueryFromArray('category',$result->category);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->jobsubcategory) && $result->jobsubcategory ){
            $filter_search['jobsubcategory'] = $result->jobsubcategory;
            $res = $this->makeQueryFromArray('jobsubcategory',$result->jobsubcategory);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->jobtype) && $result->jobtype ){
            $filter_search['jobtype'] = $result->jobtype;
            $res = $this->makeQueryFromArray('jobtype',$result->jobtype);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->careerlevel) && $result->careerlevel ){
            $filter_search['careerlevel'] = $result->careerlevel;
            $res = $this->makeQueryFromArray('careerlevel',$result->careerlevel);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->gender) && $result->gender ){
            if(is_numeric($result->gender)){
                $inquery .= " AND job.gender = ".$result->gender;
                $filter_search['gender'] = $result->gender;
            }
        }
        if( isset($result->jobstatus) && $result->jobstatus ){
            $filter_search['jobstatus'] = $result->jobstatus;
            $res = $this->makeQueryFromArray('jobstatus',$result->jobstatus);
            if($res) $inquery .= " AND ( ".$res." )";
        }
//exp
        if( isset($result->experiencemin) && $result->experiencemin AND isset($result->experiencemax) && $result->experiencemax){
            if(is_numeric($result->experiencemin) AND is_numeric($result->experiencemax)){
                $filter_search['experiencemin'] = $result->experiencemin;
                $filter_search['experiencemax'] = $result->experiencemax;
                $inquery .= " AND IF (job.isexperienceminimax = 0 , job.minexperiencerange = $result->experiencemin AND job.maxexperiencerange = $result->experiencemax , job.experienceid BETWEEN $result->experiencemin AND $result->experiencemax ) ";
            }
        }elseif(isset($result->experiencemin) && $result->experiencemin){
            if(is_numeric($result->experiencemin) ){
                $filter_search['experiencemin'] = $result->experiencemin;
                $inquery .= " AND job.minexperiencerange = $result->experiencemin ";
            }
        }elseif(isset($result->experiencemax) && $result->experiencemax){
            if(is_numeric($result->experiencemax) ){
                $filter_search['experiencemax'] = $result->experiencemax;
                $inquery .= " AND job.maxexperiencerange = $result->experiencemax ";
            }
        }
//exp

        if( isset($result->currencyid) && $result->currencyid ){
            if(is_numeric($result->currencyid)){
                $filter_search['currencyid'] = $result->currencyid;
                $inquery .= " AND job.currencyid = ".$result->currencyid;
            }
        }
        if( isset($result->srangestart) && $result->srangestart ){
            if(is_numeric($result->srangestart)){
                $filter_search['srangestart'] = $result->srangestart;
                $inquery .= " AND job.salaryrangefrom = ".$result->srangestart;
            }
        }
        if( isset($result->srangeend) && $result->srangeend ){
            if(is_numeric($result->srangeend)){
                $filter_search['srangeend'] = $result->srangeend;
                $inquery .= " AND job.salaryrangeto = ".$result->srangeend;
            }
        }
        if( isset($result->srangetype) && $result->srangetype ){
            if(is_numeric($result->srangetype)){
                $filter_search['srangetype'] = $result->srangetype;
                $inquery .= " AND job.salaryrangetype = ".$result->srangetype;
            }
        }
        if( isset($result->shift) && $result->shift ){
            $filter_search['shift'] = $result->shift;
            $res = $this->makeQueryFromArray('shift',$result->shift);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->education) && $result->education ){
            $filter_search['education'] = $result->education;
            $res = $this->makeQueryFromArray('education',$result->education);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->city) && $result->city ){
            $filter_search['city'] = $result->city;
            $res = $this->makeQueryFromArray('city',$result->city);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->workpermit) && $result->workpermit ){
            $filter_search['workpermit'] = $result->workpermit;
            $res = $this->makeQueryFromArray('workpermit',$result->workpermit);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        if( isset($result->requiredtravel) && $result->requiredtravel ){
            if(is_numeric($result->requiredtravel)){
                $filter_search['requiredtravel'] = $result->requiredtravel;
                $inquery .= " AND job.requiredtravel = ".$result->requiredtravel;
            }
        }
        if( isset($result->duration) && $result->duration ){
            $filter_search['duration'] = $result->duration;
            $inquery .= " AND job.duration = '%".$result->duration."%'";
        }
        if( isset($result->zipcode) && $result->zipcode ){
            $filter_search['zipcode'] = $result->zipcode;
			$inquery .= " AND job.zipcode = ". $db->quote($result->zipcode);
        }
        if( isset($result->startpublishing) && $result->startpublishing  && isset($result->stoppublishing )){
            if($result->startpublishing != '0000-00-00 00:00:00' && $result->stoppublishing != '0000-00-00 00:00:00'){
                $filter_search['startpublishing'] = $result->startpublishing;
                $filter_search['stoppublishing'] = $result->stoppublishing;
                $inquery .= " AND job.startpublishing >= ".$db->Quote($result->startpublishing)."
                            AND job.stoppublishing <= ".$db->Quote($result->stoppublishing);
            }
        }

//custom field code
        if (isset($save_searched_fields->params) && $save_searched_fields->params != null) {
            $data = getCustomFieldClass()->userFieldsData(2);
            if (!empty($data)) {
                $valarray = json_decode($save_searched_fields->params);
                foreach ($data as $uf) {
                    $fieldname = $uf->field;
                    if (isset($valarray->$fieldname) && $valarray->$fieldname != null) {
                        switch ($uf->userfieldtype) {
                            case 'text':
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'email':
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'file':
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'combo':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'depandant_field':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'radio':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'checkbox':
                                $finalvalue = '';
                                foreach($valarray->{$uf->field} AS $value){
                                    $finalvalue .= $value.'.*';
                                }
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($finalvalue) . '"\' ';
                                break;
                            case 'date':
                                $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                break;
                            case 'editor':
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'textarea':
                                $inquery .= ' job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                break;
                            case 'multiple':
                                $finalvalue = '';
                                $f = $uf->field;
                                $arr = $valarray->$f;
                                foreach($arr AS $value){
                                    if($value)
                                        $finalvalue .= $value.'.*';
                                }
                                if($finalvalue)
                                    $inquery .= ' AND job.params REGEXP \'%"' . $uf->field . '":"[^"]*'.htmlspecialchars($finalvalue).'"\' ';
                                break;
                        }
                        //to convert an std class object to array
                        if (!empty($valarray)) {
                            $valarray = json_encode($valarray);
                            $valarray = json_decode($valarray, true);
                        }
                        $filter_search['params'] = $valarray;
                    }
                }
            }
        }
//end
        $longitude = isset($result->longitude) ? $result->longitude : '';
        $latitude = isset($result->latitude) ? $result->latitude : '';
        $radius = isset($result->radius) ? $result->radius : '';
        $radius_length_type = isset($result->radiuslengthtype) ? $result->radiuslengthtype : '';
        //for radius search
        switch ($radius_length_type) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
            default:
                $radiuslength = '';
            break;
        }
        if ($longitude != '' && $latitude != '' && $radius != '' && $radiuslength != '') {
            $filter_search['longitude'] = $longitude;
            $filter_search['latitude'] = $latitude;
            $filter_search['radius'] = $radius;
            $filter_search['radiuslengthtype'] = $radius_length_type;
            $inquery .= " AND acos((SIN( PI()* $latitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
        }

        return $inquery;
    }

    private function getRefinedJobs($filter_search){
        $db = Factory::getDBO();
        $inquery ="";
        $keywords_a = $filter_search['metakeywords'];
        if($keywords_a){

            $res = $this->makeQueryFromArray('metakeywords',$keywords_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $jobtitle = $filter_search['jobtitle'];
        if($jobtitle){

            $inquery .= " AND job.title LIKE ".$db->Quote('%'.$jobtitle.'%');
        }
        $company_a = $filter_search['company'];
        if($company_a){

            $res = $this->makeQueryFromArray('company',$company_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $category_a = $filter_search['category'];
        if($category_a){

            $res = $this->makeQueryFromArray('category',$category_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $jobsubcategory_a = $filter_search['jobsubcategory'];
        if($jobsubcategory_a){

            $res = $this->makeQueryFromArray('jobsubcategory',$jobsubcategory_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $jobtype_a = $filter_search['jobtype'];
        if($jobtype_a){

            $res = $this->makeQueryFromArray('jobtype',$jobtype_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $careerlevel_a = $filter_search['careerlevel'];
        if($careerlevel_a){

            $res = $this->makeQueryFromArray('careerlevel',$careerlevel_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $gender = $filter_search['gender'];
        if($gender){
            if(is_numeric($gender)){
                $inquery .= " AND job.gender = $gender";

            }
        }
        $agestart = $filter_search['agestart'];
        if($agestart){
            if(is_numeric($agestart)){

                $inquery .= " AND job.agefrom =". $agestart;
            }
        }
        $ageend = $filter_search['ageend'];
        if($ageend){
            if(is_numeric($ageend)){

                $inquery .= " AND job.ageto =". $ageend;
            }
        }
        $jobstatus_a = $filter_search['jobstatus'];
        if($jobstatus_a){

            $res = $this->makeQueryFromArray('jobstatus',$jobstatus_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }

        $experiencemin = $filter_search['experiencemin'];
        $experiencemax = $filter_search['experiencemax'];
        if( $experiencemin AND $experiencemax){
            if(is_numeric($experiencemin) AND is_numeric($experiencemax)){
                $inquery .= " AND IF (job.isexperienceminimax = 0 , job.minexperiencerange = $experiencemin AND job.maxexperiencerange = $experiencemax , job.experienceid BETWEEN $experiencemin AND $experiencemax ) ";
            }
        }elseif($experiencemin){
            if(is_numeric($experiencemin) ){
                $inquery .= " AND job.minexperiencerange = $experiencemin ";
            }
        }elseif($experiencemax){
            if(is_numeric($experiencemax) ){
                $inquery .= " AND job.maxexperiencerange = $experiencemax ";
            }
        }

        $currencyid = $filter_search['currencyid'];
        if($currencyid){
            if(is_numeric($currencyid)){

                $inquery .= " AND job.currencyid = ".$currencyid;
            }
        }
        $srangestart = $filter_search['srangestart'];
        if($srangestart){
            if(is_numeric($srangestart)){

                $inquery .= " AND job.salaryrangefrom =". $srangestart;
            }
        }
        $srangeend = $filter_search['srangeend'];
        if($srangeend){
            if(is_numeric($srangeend)){

                $inquery .= " AND job.salaryrangeto =". $srangeend;
            }
        }
        $srangetype = $filter_search['srangetype'];
        if($srangetype){
            if(is_numeric($srangetype)){

                $inquery .= " AND job.salaryrangetype =". $srangetype;
            }
        }
        $shift_a = $filter_search['shift'];
        if($shift_a){

            $res = $this->makeQueryFromArray('shift',$shift_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $education_a = $filter_search['education'];
        if($education_a){

            $res = $this->makeQueryFromArray('education',$education_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $city_a = $filter_search['city'];
        if($city_a){
            $res = $this->makeQueryFromArray('city',$city_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $workpermit_a = $filter_search['workpermit']; // workpermit countries
        if($workpermit_a){

            $res = $this->makeQueryFromArray('workpermit',$workpermit_a);
            if($res) $inquery .= " AND ( ".$res." )";
        }
        $requiredtravel = $filter_search['requiredtravel'];
        if($requiredtravel){
            if(is_numeric($requiredtravel)){

                $inquery .= " AND job.requiredtravel = ".$requiredtravel;
            }
        }
        $duration = $filter_search['duration'];
        if($duration){
            $inquery .= " AND job.duration LIKE ".$db->Quote('%'.$duration.'%');
        }

        $zipcode = $filter_search['zipcode'];
        if($zipcode){
			$inquery .= " AND job.zipcode = ". $db->quote($zipcode);
        }

		$state = $filter_search['state'];
		if($state){
            if(is_numeric($state)){
			     $inquery .= " AND city.stateid = ".$state;
            }
		}
        $startpublishing = $filter_search['startpublishing'];
        $stoppublishing = $filter_search['stoppublishing'];
        if($startpublishing && $stoppublishing){
            $inquery .= " AND DATE(job.startpublishing) >= ".$db->Quote($startpublishing)."
                        AND DATE(job.stoppublishing) <= ".$db->Quote($stoppublishing);
        }

        //Custom field search
        $data = getCustomFieldClass()->userFieldsData(2);
        $valarray = array();
        if( ! empty($data) ){
            $mainframe = Factory::getApplication();
            $option = 'com_jsjobs';
            foreach ($data as $uf) {
                switch ($uf->userfieldtype) {
                    case 'text':
						$valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                        break;
                    case 'file':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                        break;
                    case 'email':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                        break;
                    case 'combo':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                        break;
                    case 'depandant_field':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                        break;
                    case 'radio':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) ){
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                        }
                        break;
                    case 'checkbox':
                        $finalvalue = '';
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','array');
                        if( ! empty($valarray[$uf->field]) ){
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($finalvalue) . '.*"\' ';
                        }
                        break;
                    case 'date':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params LIKE \'%"' . $uf->field . '":"' . htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                        break;
                    case 'textarea':
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','string');
						$valarray[$uf->field] =  $db->escape($valarray[$uf->field]);
                        if( ! empty($valarray[$uf->field]) )
                            $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*' . htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                        break;
                    case 'multiple':
                        $finalvalue = '';
                        $valarray[$uf->field] = $mainframe->getUserStateFromRequest($option . $uf->field,$uf->field,'','array');
                        if( ! empty($valarray[$uf->field]) ){
                            foreach($valarray[$uf->field] AS $value){
                                if($value){
                                    $finalvalue .= $value.'.*';
                                }
                            }
                            if($finalvalue){
                                $inquery .= ' AND job.params REGEXP \'"' . $uf->field . '":"[^"]*'.htmlspecialchars($finalvalue).'[^"]*"\' ';
                            }
                        }
                    break;
                }
            }
        }

        // End customfiled

        $longitude = $filter_search['longitude'];
        $latitude = $filter_search['latitude'];
        $radius = $filter_search['radius'];
        $radius_length_type = $filter_search['radiuslengthtype'];
        //for radius search
        switch ($radius_length_type) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        if(!is_numeric($radius)){
            $radius = '';
        }
        if(!is_numeric($longitude)){
            $longitude = '';
        }
        if(!is_numeric($latitude)){
            $latitude = '';
        }
        if ($longitude != '' && $latitude != '' && $radius != '' && $radiuslength != '' && (is_numeric($longitude) && is_numeric($latitude) && is_numeric($radius))) {

            $inquery .= " AND acos((SIN( PI()* $latitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
        }

        $array = array();
        $array['inquery'] = $inquery;
        $array['params'] = $valarray;

        return $array;
    }

    function getJobs() {

        $vars = $this->getjobsvar();

        //Get pagination
        $pagination = $this->getjobsPagination();
        $limit = $pagination['limit'];
        $pagenum = $pagination['pagenum'];
        $limitstart = $pagination['limitstart'];
        //Get filters Vars
        $filter_search = $this->getJobPopupSearch();

        $db = $this->getDBO();
        $_result = array();
        $inquery = '';
        $search = Factory::getApplication()->input->get('search',null,'get');
        if($search != null){
            $array = explode('-',$search);
            $search_id = $array[count($array)-1];
            $inquery = $this->getSaveSearchForView($search_id , $filter_search);
            $filter_search['search'] = $search_id;
        }elseif(empty($vars)){
            $ret_data = $this->getRefinedJobs($filter_search);
            $inquery = $ret_data['inquery'];
            $filter_search['params'] = $ret_data['params'];
        }else{

            if(isset($vars['company']) && is_numeric($vars['company'])){ // if action form a <link> defined in cp
                $filter_search['company'] = $vars['company'];
                $inquery .= " AND job.companyid=".$vars['company'];
            }
            if(isset($vars['category']) && is_numeric($vars['category'])){ // if action form a <link> defined in cp
                $filter_search['category'] = $vars['category'];
                $inquery .= " AND job.jobcategory=".$vars['category'];
            }
            if(isset($vars['jobsubcategory']) && is_numeric($vars['jobsubcategory'])){ // if action form a <link> defined in cp
                $filter_search['jobsubcategory'] = $vars['jobsubcategory'];
                $inquery .= " AND job.subcategoryid=".$vars['jobsubcategory'];
            }
            if(isset($vars['jobtype']) && is_numeric($vars['jobtype'])){ // if action form a <link> defined in cp
                $filter_search['jobtype'] = $vars['jobtype'];
                $inquery .= " AND job.jobtype=".$vars['jobtype'];
            }

            // For jobi theme
            if(Factory::getApplication()->getTemplate() == 'jobi'){
                $ret_data = $this->getRefinedJobs($filter_search);
                $inquery .= $ret_data['inquery'];
            }
        }

        //local vars
        $simplejobs=$featuredjobs=$goldjobs=array();
        $curdate = Factory::getDate()->format('Y-m-d');
        //Pagination
        $query = "SELECT COUNT(DISTINCT job.id) FROM `#__js_job_jobs` AS job
                JOIN `#__js_job_companies` AS company ON company.id = job.companyid
				LEFT JOIN `#__js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
				LEFT JOIN `#__js_job_cities` AS city ON city.id = jobcity.cityid AND jobcity.jobid = job.id
                WHERE DATE(job.startpublishing) <= ".$db->Quote($curdate)." AND DATE(job.stoppublishing) >= ".$db->Quote($curdate)."
                AND job.status = 1";

        $query.=$inquery;
        $db->setQuery($query);
        $total = $db->loadResult();

        // $limitstart = $pagenum * $limit;

        if (!isset($this->_config)) {
            $this->_config = $this->getJSModel('configurations')->getConfig('');
        }
        foreach ($this->_config as $conf) {

            if ($conf->configname == 'noofgoldjobsinlisting')
                $noofgoldjobs = $conf->configvalue;
            if ($conf->configname == 'nooffeaturedjobsinlisting')
                $nooffeaturedjobs = $conf->configvalue;
            if ($conf->configname == 'showgoldjobsinnewestjobs')
                $showgoldjobs = $conf->configvalue;
            if ($conf->configname == 'showfeaturedjobsinnewestjobs')
                $showfeaturedjobs = $conf->configvalue;
            if ($conf->configname == 'currency_align')
                $currency_align = $conf->configvalue;
        }

        //Data
        $query = "SELECT DISTINCT job.id AS jobid,job.params,job.title,job.created,CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,job.isgoldjob,job.isfeaturedjob,job.jobapplylink,job.joblink,
                 cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, CONCAT(company.alias,'-',company.id) AS companyaliasid,
                 rstart.rangestart,rend.rangestart As rangeend,srtype.title AS rangetype, jobtype.title AS jobtypetitle,currency.symbol
                 FROM `#__js_job_jobs` AS job
                 JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                 LEFT JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
                 LEFT JOIN `#__js_job_salaryrange` AS rstart ON rstart.id = job.salaryrangefrom
                 LEFT JOIN `#__js_job_salaryrange` AS rend ON rend.id = job.salaryrangeto
                 LEFT JOIN `#__js_job_salaryrangetypes` AS srtype ON srtype.id = job.salaryrangetype
                 LEFT JOIN `#__js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                 LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
				LEFT JOIN `#__js_job_jobcities` AS jobcity ON jobcity.jobid = job.id
				LEFT JOIN `#__js_job_cities` AS city ON city.id = jobcity.cityid AND jobcity.jobid = job.id
                 WHERE DATE(job.startpublishing) <= ".$db->Quote($curdate)." AND DATE(job.stoppublishing) >= ".$db->Quote($curdate)."
                 AND job.status = 1";

        $query.=$inquery;

        $query.= " ORDER BY job.created DESC ";
        $db->setQuery($query , $limitstart , $limit);
        $results = $db->loadObjectList();
        foreach($results AS $d){
            $d->location = $this->getJSModel('cities')->getLocationDataForView($this->getJobCities($d->jobid));
            $d->salary = $this->getJSModel('common')->getSalaryRangeView($d->symbol,$d->rangestart,$d->rangeend,$d->rangetype , $currency_align);
            $d->simplejob = 1;
            $simplejobs[] = $d;
        }


        $search_combo = $this->makeJobsSearchList($filter_search);

        // if do not show seperate gold and featured jobs and show unique jobs
        // in array function may not work in this case
        $jobs_array = array();
        $jobs_array = $goldjobs;
        foreach ($featuredjobs AS $featured) {
            $matched = 0;
            foreach ($jobs_array AS $job) {
                if($featured->jobid == $job->jobid){
                    $matched = 1;
                }
            }
            if($matched == 0){
                $jobs_array[] = $featured;
            }
        }

        foreach ($simplejobs AS $simple) {
            $matched = 0;
            foreach ($jobs_array AS $job) {
                if($simple->jobid == $job->jobid){
                    $matched = 1;
                }
            }
            if($matched == 0){
                $jobs_array[] = $simple;
            }
        }

        //if show all jobs
        //$_result[0] = array_merge($goldjobs,$featuredjobs,$simplejobs);

 
        $_result[0] = $jobs_array;
        $_result[1] = $total;

        $fieldsordering = $this->getJSModel('customfields')->getFieldsOrdering(2);
        $_result[2] = $fieldsordering;
        $fieldsforview = $this->getJSModel('customfields')->parseFieldsOrderingForView($fieldsordering);
        $_result[3] = $filter_search;
        $_result[4] = $search_combo;
        $_result[5] = $this->getCitiesForFilter($filter_search['city']); // multicites data
        $_result[6] = $fieldsforview;

        return $_result;
    }

    public function getCitiesForFilter($cities){
        if(empty($cities))
            return "";

        $db = $this->getDBO();
        $cities = explode(',', $cities);
        $result = array();

        $config = $this->getJSModel('configurations')->getConfigByFor('default');

        foreach ($cities as $city) {
            if(is_numeric($city)){
                $query = "SELECT city.id AS id, city.cityName AS cname, state.name AS statename, country.name AS countryname ";
                $query .= " FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_countries` AS country on city.countryid=country.id
                            LEFT JOIN `#__js_job_states` AS state on city.stateid=state.id
                            WHERE country.enabled = 1 AND city.enabled = 1";
                $query .= " AND city.id =".$city;

                $db->setQuery($query);
                $result[] = $db->loadObject();
            }
        }
        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    if($city->statename != ''){
                        $cityname .= ','. Text::_($city->statename);
                    }
                    $cityname .= ','. Text::_($city->countryname);
                    $city->name = $cityname;
                }
                break;
            case 'cs'://City, State
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    if($city->statename != ''){
                        $cityname .= ','. Text::_($city->statename);
                    }
                    $city->name = $cityname;
                }

                break;
            case 'cc'://City, Country
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    $cityname .= ','. Text::_($city->countryname);
                    $city->name = $cityname;
                }

                break;
            case 'c'://city by default select for each case
                foreach ($result as $city) {
                    $cityname = Text::_($city->cname);
                    if($city->statename != ''){
                        $cityname .= ','. Text::_($city->statename);
                    }
                    $cityname .= ','. Text::_($city->countryname);
                    $city->name = $cityname;
                }
                break;
        }
        if(!empty($result)){
            return json_encode($result);
        }else{
            return '';
        }
    }

    private function getJobCities($jobid){
        if(!is_numeric($jobid)) return false;
        $db = $this->getDBO();
        $query = "SELECT cityid FROM `#__js_job_jobcities` WHERE jobid = $jobid";
        $db->setQuery($query);
        $cities = $db->loadObjectList();
        $str = '';
        foreach ($cities as $key) {
            if($str) $str .= ",";
            $str .= $key->cityid;
        }
        return $str;
    }

    private function makeJobsSearchList($jobs_filters){

        $radiustype = array('0' => array('value' => 'm','text' => Text::_('Meters')), '1' => array('value' => 'km','text' => Text::_('Kilometers')), '2' => array('value' => 'mile','text' => Text::_('Miles')), '3' => array('value' => 'nacmiles','text' => Text::_('Nautical Miles')), );

        $defaultCategory = $this->getJSModel('common')->getDefaultValue('categories');

        if(!empty($jobs_filters['category'])){
            $job_subcategories = $this->getJSModel('subcategory')->getSubCategoriesforCombo($jobs_filters['category'],'', '');
        }else{
            $job_subcategories = $this->getJSModel('subcategory')->getSubCategoriesforCombo($defaultCategory,'', '');
        }

        $search_combo['jobsubcategory'] = HTMLHelper::_('select.genericList', $job_subcategories, 'jobsubcategory[]', 'class="inputbox jsjob-multiselect" multiple="true"' . '', 'value', 'text', $jobs_filters['jobsubcategory']);
        $search_combo['company'] = HTMLHelper::_('select.genericList', $this->getJSModel('company')->getAllCompanies(''), 'company[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['company']);
        $search_combo['category'] = HTMLHelper::_('select.genericList', $this->getJSModel('category')->getCategories(''), 'category[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['category']);


        $search_combo['careerlevel'] = HTMLHelper::_('select.genericList', $this->getJSModel('careerlevel')->getCareerLevel(''), 'careerlevel[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['careerlevel']);
        $search_combo['shift'] = HTMLHelper::_('select.genericList', $this->getJSModel('shift')->getShift(''), 'shift[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['shift']);
        $search_combo['gender'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getGender(Text::_('Does Not Matter')), 'gender','class="inputbox"','value','text', $jobs_filters['gender']);
        $search_combo['jobtype'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobtype')->getJobType(''), 'jobtype[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['jobtype']);
        $search_combo['jobstatus'] = HTMLHelper::_('select.genericList', $this->getJSModel('jobstatus')->getJobStatus(''), 'jobstatus[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['jobstatus']);
        $search_combo['workpermit'] = HTMLHelper::_('select.genericList', $this->getJSModel('countries')->getCountries(''), 'workpermit[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['workpermit']);
        $search_combo['currencyid'] = HTMLHelper::_('select.genericList', $this->getJSModel('currency')->getCurrency(Text::_('Currency')), 'currencyid','class="inputbox sal"','value','text', $jobs_filters['currencyid']);
        $search_combo['srangestart'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRangeFromTo(Text::_('From'), 1), 'srangestart','class="inputbox sal"','value','text', $jobs_filters['srangestart']);
        $search_combo['srangeend'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrange')->getJobSalaryRangeFromTo(Text::_('To'),2), 'srangeend','class="inputbox sal"','value','text', $jobs_filters['srangeend']);
        $search_combo['srangetype'] = HTMLHelper::_('select.genericList', $this->getJSModel('salaryrangetype')->getSalaryRangeTypes(Text::_('Type')), 'srangetype','class="inputbox sal"','value','text', $jobs_filters['srangetype']);
        $search_combo['education'] = HTMLHelper::_('select.genericList', $this->getJSModel('highesteducation')->getHeighestEducation(''), 'education[]','class="inputbox jsjob-multiselect" multiple="true"','value','text', $jobs_filters['education']);
        $search_combo['experiencemin'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Minimum')), 'experiencemin','class="inputbox exp"','value','text', $jobs_filters['experiencemin']);
        $search_combo['experiencemax'] = HTMLHelper::_('select.genericList', $this->getJSModel('experience')->getExperiences(Text::_('Maximum')), 'experiencemax','class="inputbox exp"','value','text', $jobs_filters['experiencemax']);
        $search_combo['requiredtravel'] = HTMLHelper::_('select.genericList', $this->getJSModel('common')->getRequiredTravel(Text::_('Select')), 'requiredtravel','class="inputbox"','value','text', $jobs_filters['requiredtravel']);
        $search_combo['radiuslengthtype'] = HTMLHelper::_('select.genericList', $radiustype, 'radiuslengthtype','class="inputbox"','value','text',$jobs_filters['radiuslengthtype']);

        return $search_combo;
    }

    private function getJobPopupSearch(){

        $mainframe = Factory::getApplication();
        $option = 'com_jsjobs';
        $filter_search = array();

        $popup_reset = Factory::getApplication()->input->get('popresetbtn',null,'post');

        if($popup_reset !== null){

            $mainframe->setUserState($option.'company',array());
            $mainframe->setUserState($option.'category',array());
            $mainframe->setUserState($option.'jobsubcategory',array());
            $mainframe->setUserState($option.'careerlevel',array());
            $mainframe->setUserState($option.'shift',array());
            $mainframe->setUserState($option.'jobtype',array());
            $mainframe->setUserState($option.'jobstatus',array());
            $mainframe->setUserState($option.'workpermit',array());
            $mainframe->setUserState($option.'education',array());

            //Custom field search
            $data = getCustomFieldClass()->userFieldsData(2);
            foreach ($data as $uf) {
                switch ($uf->userfieldtype) {
                    case 'multiple':
                    case 'checkbox':
                        $mainframe->setUserState($option.$uf->field,array());
                    break;
                }
            }
            // End customfiled
        }

        $filter_search['metakeywords'] = $mainframe->getUserStateFromRequest($option . 'metakeywords','metakeywords','','string');
        $filter_search['jobtitle'] = $mainframe->getUserStateFromRequest($option . 'jobtitle','jobtitle','','string');
        $filter_search['company'] = $mainframe->getUserStateFromRequest($option . 'company','company','','array');
        $filter_search['category'] = $mainframe->getUserStateFromRequest($option . 'category','category','','array');
        $filter_search['jobsubcategory'] = $mainframe->getUserStateFromRequest($option . 'jobsubcategory','jobsubcategory','','array');
        $filter_search['jobtype'] = $mainframe->getUserStateFromRequest($option . 'jobtype','jobtype','','array');
        $filter_search['careerlevel'] = $mainframe->getUserStateFromRequest($option . 'careerlevel','careerlevel','','array');
        $filter_search['gender'] = $mainframe->getUserStateFromRequest($option . 'gender','gender','','string');
        $filter_search['agestart'] = $mainframe->getUserStateFromRequest($option . 'agestart','agestart','','string');
        $filter_search['ageend'] = $mainframe->getUserStateFromRequest($option . 'ageend','ageend','','string');
        $filter_search['jobstatus'] = $mainframe->getUserStateFromRequest($option . 'jobstatus','jobstatus','','array');
        $filter_search['currencyid'] = $mainframe->getUserStateFromRequest($option . 'currencyid','currencyid','','string');
        $filter_search['srangestart'] = $mainframe->getUserStateFromRequest($option . 'srangestart','srangestart','','string');
        $filter_search['srangeend'] = $mainframe->getUserStateFromRequest($option . 'srangeend','srangeend','','string');
        $filter_search['srangetype'] = $mainframe->getUserStateFromRequest($option . 'srangetype','srangetype','','string');
        $filter_search['shift'] = $mainframe->getUserStateFromRequest($option . 'shift','shift','','array');
        $filter_search['education'] = $mainframe->getUserStateFromRequest($option . 'education','education','','array');
        $filter_search['experiencemin'] = $mainframe->getUserStateFromRequest($option . 'experiencemin','experiencemin','','string');
        $filter_search['experiencemax'] = $mainframe->getUserStateFromRequest($option . 'experiencemax','experiencemax','','string');
        $filter_search['city'] = $mainframe->getUserStateFromRequest($option . 'city','city','','string');
		$filter_search['state'] = $mainframe->getUserStateFromRequest($option . 'state','state','','string');
        $filter_search['workpermit'] = $mainframe->getUserStateFromRequest($option . 'workpermit','workpermit','','array');
        $filter_search['requiredtravel'] = $mainframe->getUserStateFromRequest($option . 'requiredtravel','requiredtravel','','string');
        $filter_search['duration'] = $mainframe->getUserStateFromRequest($option . 'duration','duration','','string');
        $filter_search['zipcode'] = $mainframe->getUserStateFromRequest($option . 'zipcode','zipcode','','string');
        $filter_search['startpublishing'] = $mainframe->getUserStateFromRequest($option . 'startpublishing','startpublishing','','string');
        $filter_search['stoppublishing'] = $mainframe->getUserStateFromRequest($option . 'stoppublishing','stoppublishing','','string');
        $filter_search['longitude'] = $mainframe->getUserStateFromRequest($option . 'longitude','longitude','','string');
        $filter_search['latitude'] = $mainframe->getUserStateFromRequest($option . 'latitude','latitude','','string');
        $filter_search['radius'] = $mainframe->getUserStateFromRequest($option . 'radius','radius','','string');
        $filter_search['radiuslengthtype'] = $mainframe->getUserStateFromRequest($option . 'radiuslengthtype','radiuslengthtype','','string');

        return $filter_search;
    }

    private function getjobsPagination(){
        $pagination = array();
        $mainframe = Factory::getApplication();
        $option = 'com_jsjobs';
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        //$limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

        $limitstart = Factory::getApplication()->input->get('limitstart', 0);
        $pagination['limitstart'] = $limitstart;

        $pagenum = ($limit > 0) ? floor($limitstart / $limit) + 1 : 1;
        // $pagenum = Factory::getApplication()->input->get('pagenum', 0);

        $pagination['limit'] = $limit;
        $pagination['pagenum'] = $pagenum;

        return $pagination;
    }

    function getNextJobs(){
        $results = $this->getJSModel('configurations')->getConfig('');
        $itemid = Factory::getApplication()->input->get('Itemid');
        $config = array();
        if ($results) {
            foreach ($results as $result) {
                $config[$result->configname] = $result->configvalue;
            }
        }

        $jobs = $this->getJobs();

        require_once( JPATH_COMPONENT.'/views/job/jobslisting.php' );
        $obj = new jobslist();

        $jobshtml = $obj->printjobs($jobs[0],$config,$jobs[6],$itemid);
        return $jobshtml;
    }

    private function getjobsvar(){
        $vars = array();

        $mainframe = Factory::getApplication();
        $option = 'com_jsjobs';
        $id = $mainframe->getUserStateFromRequest($option . 'cat', 'cat', '', 'string');
        if ($id) {
            $vars['category'] = $this->parseid($id);
        }

        $id = $mainframe->getUserStateFromRequest($option . 'jobsubcat', 'jobsubcat', '', 'string');
        if ($id) {
            $vars['jobsubcategory'] = $this->parseid($id);
        }

        $id = $mainframe->getUserStateFromRequest($option . 'jt', 'jt', '', 'string');
        if ($id) {
            $vars['jobtype'] = $this->parseid($id);
        }

        $id = $mainframe->getUserStateFromRequest($option . 'cd', 'cd', '', 'string');
        if ($id) {
            $vars['company'] = $this->parseid($id);
        }

        return $vars;
    }

    private function parseid($value){
        $arr = explode('-', $value);
        $id = $arr[count($arr)-1];
        return $id;
    }

    function postJobOnJomSocial($jobid){
        if( $this->getJSModel(JSJOBSVMS)->{JSJOBSVMSFUN}("TEVsa3ZYam9tc29jaWFsY1JCTm5X") ){
            JPluginHelper::importPlugin('community');
            $dispatcher = JEventDispatcher::getInstance();
            $res = $dispatcher->trigger('JSjobsPostJobOnJomSocial',array($jobid));
            if(!$res[0])
                return false;
        }
        return true;
    }

    private function getjobfieldtitle($field){
        $cf_model = $this->getJSModel('customfields');
        return Text::_($cf_model->getFieldTitleByFieldAndFieldfor($field , 2));
    }

    function prepareJomSocial(){
        $db = Factory::getDbo();
        $query = "SELECT * FROM `#__extensions` WHERE `type`='plugin' AND `element`='jsjobs' AND `folder`='community'";
        eval($this->getJSModel('common')->b64ForDecode($this->getJSModel('configurations')->getConfigValue("jmjsbasic")));
        $db->setQuery($query);
        if(!$db->loadResult())
            return false;
        return true;
    }

    function makeJobSeo($job_seo , $jsjobid){
        if(empty($job_seo))
            return '';
        $db = Factory::getDBO();
        $common = $this->getJSModel('common');
        $id = $common->parseId($jsjobid);
        if(! is_numeric($id)) return '';
        $result = '';
        $job_seo = str_replace( ' ', '', $job_seo);
        $job_seo = str_replace( '[', '', $job_seo);
        $array = explode(']', $job_seo);

        $total = count($array);
        if($total > 5)
            $total = 5;
        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'title':
                    $query = "SELECT title AS col FROM `#__js_job_jobs` WHERE id = " . $id;
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS category ON category.id = job.jobcategory
                        WHERE job.id = " . $id;
                break;
                case 'company':
                    $query = "SELECT company.name AS col
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                        WHERE job.id = " . $id;
                break;
                case 'jobtype':
                    $query = "SELECT jt.title AS col
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_jobtypes` AS jt ON jt.id = job.jobtype
                        WHERE job.id = " . $id;
                break;
                case 'location':
                    $query = "SELECT job.city AS col
                        FROM `#__js_job_jobs` AS job WHERE job.id = " . $id;
                break;
            }
            if(!empty($query)){
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

    function getMyJobsForEmployerCP($uid){
        if(!is_numeric($uid))
            return false;
        $db = Factory::getDBO();
        $query = "SELECT job.title , job.params ,job.created ,job.jobcategory
                    ,job.noofjobs ,job.startpublishing ,job.stoppublishing
                    ,job.city ,job.status ,job.serverstatus
                    ,job.contactemail ,job.id
                    , cat.cat_title
                    , jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle,salarytype.title AS salarytypetitle
                    , company.name AS companyname, company.url
                    , salaryfrom.rangestart, salaryto.rangestart AS rangeend, country.name AS countryname
                    ,currency.symbol ,salaryto.rangestart AS salaryto
                    ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                    ,CONCAT(job.alias,'-',job.serverid) AS sjobaliasid
                    ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                    ,CONCAT(company.alias,'-',company.serverid) AS scompanyaliasid
                    ,(SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = job.id) AS totalapply
                    ,company.logofilename AS companylogo,company.id AS companyid
                FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                    JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                    LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                    LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                    LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                    LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
                    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
                WHERE job.uid = " . $uid . " ORDER BY job.created DESC LIMIT 0,5";
        $db->setQuery($query);
        $myjobs = $db->loadObjectList();
        foreach ($myjobs AS $jobdata) {   // for multicity select
            $jobdata->city = $this->getJSModel('cities')->getLocationDataForView($this->getJobCities($jobdata->id));
        }
        return $myjobs;
    }
}
?>
