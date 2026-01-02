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

class JSJobsModelJobapply extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_application = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }




    function getJobAppliedResume($needle_array, $tab_action, $jobid, $limitstart, $limit) {
        if (is_numeric($jobid) == false)
            return false;
        if($tab_action)
            if(!is_numeric($tab_action)) return false;

        $db = Factory::getDBO();
        $result = array();
        if (!empty($needle_array)) {
            $needle_array = json_decode($needle_array, true);
            $tab_action = "";
        }
        $query = "SELECT COUNT(job.id)
		FROM `#__js_job_jobs` AS job
		   , `#__js_job_jobapply` AS apply  
		   , `#__js_job_resume` AS app  
		   
		WHERE apply.jobid = job.id AND apply.cvid = app.id AND apply.jobid = " . $jobid;
        if ($tab_action)
            $query.=" AND apply.action_status=" . $tab_action;
        if (isset($needle_array['title']) AND $needle_array['title'] != '')
            $query.=" AND app.application_title LIKE '%" . str_replace("'", "", $db->Quote($needle_array['title'])) . "%'";
        if (isset($needle_array['name']) AND $needle_array['name'] != '')
            $query.=" AND LOWER(app.first_name) LIKE " . $db->Quote('%' . $needle_array['name'] . '%');
        if (isset($needle_array['nationality']) AND is_numeric($needle_array['nationality']))
            $query .= " AND app.nationality = " . $needle_array['nationality'];
        if (isset($needle_array['gender']) AND is_numeric($needle_array['gender']))
            $query .= " AND app.gender = " . $needle_array['gender'];
        if (isset($needle_array['jobtype']) AND is_numeric($needle_array['jobtype']))
            $query .= " AND app.jobtype = " . $needle_array['jobtype'];
        if (isset($needle_array['currency']) AND is_numeric($needle_array['currency']))
            $query .= " AND app.currencyid = " . $needle_array['currency'];
        if (isset($needle_array['jobsalaryrange']) AND is_numeric($needle_array['jobsalaryrange']))
            $query .= " AND app.jobsalaryrange = " . $needle_array['jobsalaryrange'];
        if (isset($needle_array['heighestfinisheducation']) AND is_numeric($needle_array['heighestfinisheducation']))
            $query .= " AND app.heighestfinisheducation = " . $needle_array['heighestfinisheducation'];
        if (isset($needle_array['iamavailable']) AND $needle_array['iamavailable'] != '') {
            $available = ($needle_array['iamavailable'] == "yes") ? 1 : 0;
            $query .= " AND app.iamavailable = " . $available;
        }
        if (isset($needle_array['jobcategory']) AND is_numeric($needle_array['jobcategory']))
            $query .= " AND app.job_category = " . $needle_array['jobcategory'];
        if (isset($needle_array['jobsubcategory']) AND is_numeric($needle_array['jobsubcategory']))
            $query .= " AND app.job_subcategory = " . $needle_array['jobsubcategory'];
        if (isset($needle_array['experience']) AND $needle_array['experience'] != '')
            $query .= " AND app.total_experience LIKE " . $db->Quote($needle_array['experience']);


        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT DISTINCT apply.comments,apply.id AS jobapplyid , job.title AS jobtitle, job.id,job.agefrom,job.ageto, cat.cat_title ,apply.apply_date, apply.resumeview, jobtype.title AS jobtypetitle,app.iamavailable
                        , app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype,app.gender
                        , app.total_experience, app.jobsalaryrange
                        , app.id as resumeid
                        ,city.id AS cityid,job.hits AS jobview
                        ,(SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = job.id) AS totalapply
                        , salary.rangestart, salary.rangeend,education.title AS educationtitle
                        , currency.symbol AS symbol
                        ,dcurrency.symbol AS dsymbol ,dsalary.rangestart AS drangestart, dsalary.rangeend AS drangeend  
                        ,institutes.institute_study_area AS education
                        ,app.photo AS photo,app.application_title AS applicationtitle
                        ,CONCAT(app.alias,'-',app.id) resumealiasid, CONCAT(job.alias,'-',job.id) AS jobaliasid
                        ,cletter.id AS cletterid, cletter.title AS clettertitle,  cletter.description AS cletterdescription 
                        ,exp.title AS exptitle,saltype.title AS rangetype,dsaltype.title AS drangetype
                        
                        FROM `#__js_job_jobapply` AS apply
                        JOIN `#__js_job_jobs` AS job  ON job.id = apply.jobid
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_resume` AS app ON apply.cvid = app.id 
                        LEFT JOIN `#__js_job_resumeinstitutes` AS institutes ON app.id = institutes.resumeid
                        LEFT JOIN `#__js_job_resumeemployers` AS employers ON app.id = employers.resumeid
                        LEFT JOIN `#__js_job_resumereferences` AS reference ON app.id = reference.resumeid
                        LEFT JOIN `#__js_job_resumelanguages` AS languages ON app.id = languages.resumeid
                        LEFT JOIN `#__js_job_heighesteducation` AS  education  ON app.heighestfinisheducation=education.id
                        LEFT JOIN  `#__js_job_salaryrange` AS salary  ON  app.jobsalaryrange=salary.id
                        LEFT JOIN  `#__js_job_salaryrange` AS dsalary ON app.desired_salary=dsalary.id 
                        LEFT JOIN `#__js_job_cities` AS city ON city.id = (SELECT address_city FROM `#__js_job_resumeaddresses` WHERE resumeid = app.id ORDER BY id DESC LIMIT 1) 
                        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = app.currencyid
                        LEFT JOIN `#__js_job_currencies` AS dcurrency ON dcurrency.id = app.dcurrencyid 
                        LEFT JOIN `#__js_job_coverletters` AS cletter ON apply.coverletterid = cletter.id 
                        LEFT JOIN `#__js_job_experiences` AS exp ON exp.id = app.experienceid 
                        LEFT JOIN `#__js_job_salaryrangetypes` AS dsaltype ON dsaltype.id = app.djobsalaryrangetype 
                        LEFT JOIN `#__js_job_salaryrangetypes` AS saltype ON saltype.id = app.jobsalaryrangetype 
                        WHERE apply.jobid = " . $jobid;
        if (is_numeric($tab_action))
            $query.=" AND apply.action_status=" . $tab_action;
        if (isset($needle_array['title']) AND $needle_array['title'] != '')
            $query.=" AND app.application_title LIKE '%" . str_replace("'", "", $db->Quote($needle_array['title'])) . "%'";
        if (isset($needle_array['name']) AND $needle_array['name'] != '')
            $query.=" AND LOWER(app.first_name) LIKE " . $db->Quote('%' . $needle_array['name'] . '%');
        if (isset($needle_array['nationality']) AND is_numeric($needle_array['nationality']))
            $query .= " AND app.nationality = " . $needle_array['nationality'];
        if (isset($needle_array['gender']) AND is_numeric($needle_array['gender']))
            $query .= " AND app.gender = " . $needle_array['gender'];
        if (isset($needle_array['jobtype']) AND is_numeric($needle_array['jobtype']))
            $query .= " AND app.jobtype = " . $needle_array['jobtype'];
        if (isset($needle_array['currency']) AND is_numeric($needle_array['currency']))
            $query .= " AND app.currencyid = " . $needle_array['currency'];
        if (isset($needle_array['jobsalaryrange']) AND is_numeric($needle_array['jobsalaryrange']))
            $query .= " AND app.jobsalaryrange = " . $needle_array['jobsalaryrange'];
        if (isset($needle_array['heighestfinisheducation']) AND is_numeric($needle_array['heighestfinisheducation']))
            $query .= " AND app.heighestfinisheducation = " . $needle_array['heighestfinisheducation'];
        if (isset($needle_array['iamavailable']) AND $needle_array['iamavailable'] != '' ) {
            $available = ($needle_array['iamavailable'] == "yes") ? 1 : 0;
            $query .= " AND app.iamavailable = " . $available;
        }
        if (isset($needle_array['jobcategory']) AND is_numeric($needle_array['jobcategory']))
            $query .= " AND app.job_category = " . $needle_array['jobcategory'];
        if (isset($needle_array['jobsubcategory']) AND is_numeric($needle_array['jobsubcategory']))
            $query .= " AND app.job_subcategory = " . $needle_array['jobsubcategory'];
        if (isset($needle_array['experience']) AND $needle_array['experience'] != '')
            $query .= " AND app.total_experience LIKE " . $db->Quote($needle_array['experience']);

        $query .= " GROUP BY app.id ORDER BY apply.apply_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }


}

?>