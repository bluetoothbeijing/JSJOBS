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

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelExport extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function setAllExport($jobid) {
        $db = $this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;
        //for job title
        $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
        $db->setQuery($query);
        $jobtitle = $db->loadResult();

        $result = $this->getExportAllResumesByJobId($jobid);
        $result = $db->loadAssocList();
        if (!$result) {
            $this->setError($row->getError());
            return false;
        } else {
            $result = $this->makeArrayForExport($result);
            // Empty data vars
            $data = "";
            // We need tabbed data
            $sep = "\t";
            $fields = (array_keys($result[0]));
            // Count all fields(will be the collumns
            $columns = count($fields);
            $data .= "Job Title" . $sep . $jobtitle . "\n";
            // Put the name of all fields to $out.
            for ($i = 0; $i < $columns; $i++) {
                $data .= $fields[$i] . $sep;
            }
            $data .= "\n";
            // Counting rows and push them into a for loop
            for ($k = 0; $k < count($result); $k++) {
                $row = $result[$k];
                $line = '';
                // Now replace several things for MS Excel
                foreach ($row as $value) {
                    $value = str_replace('"', '""', $value);
                    $line .= '"' . $value . '"' . "\t";
                }
                $data .= trim($line) . "\n";
            }
            $data = str_replace("\r", "", $data);
            // If count rows is nothing show o records.
            if (count($result) == 0) {
                $data .= "\n(0) Records Found!\n";
            }
            return $data;
        }
    }

    function setExport($jobid, $resumeid) {
        $db = $this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;

        $result = $this->getExportResumes($jobid, $resumeid);

        $result = $db->loadAssocList();
        if (!$result) {
            $this->setError($row->getError());
            return false;
        } else {
            $result = $this->makeArrayForExport($result);
            // Empty data vars
            $data = "";
            // We need tabbed data
            $sep = "\t";
            $fields = (array_keys($result[0]));
            // Count all fields(will be the collumns
            $columns = count($fields);
            // Put the name of all fields to $out. 
            for ($i = 0; $i < $columns; $i++) {
                $data .= $fields[$i] . $sep;
            }
            $data .= "\n";
            // Counting rows and push them into a for loop
            for ($k = 0; $k < count($result); $k++) {
                $row = $result[$k];
                $line = '';
                // Now replace several things for MS Excel
                foreach ($row as $value) {
                    $value = str_replace('"', '""', $value);
                    $line .= '"' . $value . '"' . "\t";
                }
                $data .= trim($line) . "\n";
            }
            $data = str_replace("\r", "", $data);
            // If count rows is nothing show o records.
            if (count($result) == 0) {
                $data .= "\n(0) Records Found!\n";
            }
            return $data;
        }
    }

    function makeArrayForExport($result) {
        foreach ($result as $r) {
            $myarr['Application Title'] = $r['application_title'];
            $myarr['First Name'] = $r['first_name'];
            $myarr['Last Name'] = $r['last_name'];
            $myarr['Middle Name'] = $r['middle_name'];
            $myarr['Range Start'] = $r['rangestart'];
            $myarr['Heighest Education Title'] = $r['heighesteducationtitle'];
            if ($r['gender'] == 1)
                $myarr['Gender'] = Text::_('Male');
            elseif ($r['gender'] == 2)
                $myarr['Gender'] = Text::_('Female');
            else
                $myarr['Gender'] = Text::_('Does Not Matter');
            $myarr['Email Address'] = $r['email_address'];
            $myarr['Home Phone'] = $r['home_phone'];
            $myarr['Work Phone'] = $r['work_phone'];
            $myarr['Cell'] = $r['cell'];
            $myarr['I\'am Available'] = $r['iamavailable'];
            if ($r['searchable'] == 1)
                $myarr['Searchable'] = Text::_('JYES');
            else
                $myarr['Searchable'] = Text::_('JNO');
            $myarr['Job Category'] = $r['categorytitle'];
            $myarr['Job Salaryrange'] = $r['rangestart'] . '-' . $r['rangeend'];
            $myarr['Jobtype'] = $r['jobtypetitle'];
            if ($r['address_city2'])
                $myarr['Address City'] = $r['address_city2'];
            else
                $myarr['Address City'] = $r['address_city'];
            if ($r['address_state2'])
                $myarr['Address State'] = $r['address_state2'];
            else
                $myarr['Address State'] = $r['address_state'];
            if ($r['address_country2'])
                $myarr['Address Country'] = $r['address_country2'];
            else
                $myarr['Address Country'] = $r['address_country'];
            $myarr['Address Zipcode'] = $r['address_zipcode'];
            $myarr['Address'] = $r['address'];
            $myarr['Institute'] = $r['institute'];
            if ($r['institute_city2'])
                $myarr['Institute City'] = $r['institute_city2'];
            else
                $myarr['Institute City'] = $r['institute_city'];
            if ($r['institute_state2'])
                $myarr['Institute State'] = $r['institute_state2'];
            else
                $myarr['Institute State'] = $r['institute_state'];
            if ($r['institute_country2'])
                $myarr['Institute Country'] = $r['institute_country2'];
            else
                $myarr['Institute Country'] = $r['institute_country'];
            $myarr['Institute_address'] = $r['institute_address'];
            $myarr['Institute Certificate Name'] = $r['institute_certificate_name'];
            $myarr['Institute Study Area'] = $r['institute_study_area'];
            $myarr['Employer'] = $r['employer'];
            $myarr['Employer Position'] = $r['employer_position'];
            $myarr['Employer Resp'] = $r['employer_resp'];
            $myarr['Employer Pay Upon Leaving'] = $r['employer_pay_upon_leaving'];
            $myarr['Employer Supervisor'] = $r['employer_supervisor'];
            $myarr['Employer From Date'] = $r['employer_from_date'];
            $myarr['Employer To Date'] = $r['employer_to_date'];
            $myarr['Employer Leave Reason'] = $r['employer_leave_reason'];
            if ($r['employer_city2'])
                $myarr['Employer City'] = $r['employer_city2'];
            else
                $myarr['Employer City'] = $r['employer_city'];
            if ($r['employer_state2'])
                $myarr['Employer State'] = $r['employer_state2'];
            else
                $myarr['Employer State'] = $r['employer_state'];
            if ($r['employer_country2'])
                $myarr['Employer Country'] = $r['employer_country2'];
            else
                $myarr['Employer Country'] = $r['employer_country'];
            $myarr['Employer Zip'] = $r['employer_zip'];
            $myarr['Employer Phone'] = $r['employer_phone'];
            $myarr['Employer Address'] = $r['employer_address'];
            $myarr['Institute-1'] = $r['institute1'];
            if ($r['institute1_city2'])
                $myarr['Institute-1 City'] = $r['institute1_city2'];
            else
                $myarr['Institute-1 city'] = $r['institute1_city'];
            if ($r['institute1_state2'])
                $myarr['Institute-1 State'] = $r['institute1_state2'];
            else
                $myarr['Institute-1 State'] = $r['institute1_state'];
            if ($r['institute1_country2'])
                $myarr['Institute-1 Country'] = $r['institute1_country2'];
            else
                $myarr['Institute-1 Country'] = $r['institute1_country'];
            $myarr['Institute-1 Address'] = $r['institute1_address'];
            $myarr['Institute-1 Certificate Name'] = $r['institute1_certificate_name'];
            $myarr['Institute-2'] = $r['institute2'];
            if ($r['institute2_city2'])
                $myarr['Institute-2 City'] = $r['institute2_city2'];
            else
                $myarr['Institute-2 City'] = $r['institute2_city'];
            if ($r['institute2_state2'])
                $myarr['Institute-2 State'] = $r['institute2_state2'];
            else
                $myarr['Institute-2 State'] = $r['institute2_state'];
            if ($r['institute2_country2'])
                $myarr['Institute-2 Country'] = $r['institute2_country2'];
            else
                $myarr['Institute-2 Country'] = $r['institute2_country'];
            $myarr['Institute-2 Address'] = $r['institute2_address'];
            $myarr['Institute-2 Certificate Name'] = $r['institute2_certificate_name'];
            $myarr['Institute-2 Study Area'] = $r['institute2_study_area'];
            $myarr['Institute-3'] = $r['institute3'];
            if ($r['institute3_city2'])
                $myarr['Institute-3 City'] = $r['institute3_city2'];
            else
                $myarr['Institute-3 City'] = $r['institute3_city'];
            if ($r['institute3_state2'])
                $myarr['Institute-3 State'] = $r['institute3_state2'];
            else
                $myarr['Institute-3 State'] = $r['institute3_state'];
            if ($r['institute3_country2'])
                $myarr['Institute-3 Country'] = $r['institute3_country2'];
            else
                $myarr['Institute-3 Country'] = $r['institute3_country'];
            $myarr['Institute-3 Address'] = $r['institute3_address'];
            $myarr['Institute-3 Study Area'] = $r['institute3_study_area'];
            $myarr['Employer-1'] = $r['employer1'];
            $myarr['Employer-1 Position'] = $r['employer1_position'];
            $myarr['Employer-1 Resp'] = $r['employer1_resp'];
            $myarr['Employer-1 Pay Upon Leaving'] = $r['employer1_pay_upon_leaving'];
            $myarr['Employer-1 Supervisor'] = $r['employer1_supervisor'];
            $myarr['Employer-1 From Date'] = $r['employer1_from_date'];
            $myarr['Employer-1 To Date'] = $r['employer1_to_date'];
            if ($r['employer1_city2'])
                $myarr['Employer-1 City'] = $r['employer1_city2'];
            else
                $myarr['Employer-1 City'] = $r['employer1_city'];
            if ($r['employer1_state2'])
                $myarr['Employer-1 State'] = $r['employer1_state2'];
            else
                $myarr['Employer-1 State'] = $r['employer1_state'];
            if ($r['employer1_country2'])
                $myarr['Employer-1 Country'] = $r['employer1_country2'];
            else
                $myarr['Employer-1 Country'] = $r['employer1_country'];
            $myarr['Employer-1 Zip'] = $r['employer1_zip'];
            $myarr['Employer-1 Phone'] = $r['employer1_phone'];
            $myarr['Employer-1 Address'] = $r['employer1_address'];
            $myarr['Employer-2'] = $r['employer2'];
            $myarr['Employer-2 Position'] = $r['employer2_position'];
            $myarr['Employer-2 Resp'] = $r['employer2_resp'];
            $myarr['Employer-2 Pay Upon Leaving'] = $r['employer2_pay_upon_leaving'];
            $myarr['Employer-2 Supervisor'] = $r['employer2_supervisor'];
            $myarr['Employer-2 From Date'] = $r['employer2_from_date'];
            $myarr['Employer-2 To Date'] = $r['employer2_to_date'];
            $myarr['Employer-2 Leave Reason'] = $r['employer2_leave_reason'];
            if ($r['employer2_city2'])
                $myarr['Employer-2 City'] = $r['employer2_city2'];
            else
                $myarr['Employer-2 City'] = $r['employer2_city'];
            if ($r['employer2_state2'])
                $myarr['Employer-2 State'] = $r['employer2_state2'];
            else
                $myarr['Employer-2 State'] = $r['employer2_state'];
            if ($r['employer2_country2'])
                $myarr['Employer-2 Country'] = $r['employer2_country2'];
            else
                $myarr['Employer-2 Country'] = $r['employer2_country'];
            $myarr['Employer-2 Zip'] = $r['employer2_zip'];
            $myarr['Employer-2 Address'] = $r['employer2_address'];
            $myarr['Employer-2 Phone'] = $r['employer2_phone'];
            $myarr['Employer-3'] = $r['employer3'];
            $myarr['Employer-3 Position'] = $r['employer3_position'];
            $myarr['Employer-3 Resp'] = $r['employer3_resp'];
            $myarr['Employer-3 Pay Upon Leaving'] = $r['employer3_pay_upon_leaving'];
            $myarr['Employer-3 Supervisor'] = $r['employer3_supervisor'];
            $myarr['Employer-3 From Date'] = $r['employer3_from_date'];
            $myarr['Employer-3 To Date'] = $r['employer3_to_date'];
            $myarr['Employer-3 Leave Reason'] = $r['employer3_leave_reason'];
            if ($r['employer3_city2'])
                $myarr['Employer-3 City'] = $r['employer3_city2'];
            else
                $myarr['Employer-3 City'] = $r['employer3_city'];
            if ($r['employer3_state2'])
                $myarr['Employer-3 State'] = $r['employer3_state2'];
            else
                $myarr['Employer-3 State'] = $r['employer3_state'];
            if ($r['employer3_country2'])
                $myarr['Employer-3 Country'] = $r['employer3_country2'];
            else
                $myarr['Employer-3 Country'] = $r['employer3_country'];
            $myarr['Employer-3 Zip'] = $r['employer3_zip'];
            $myarr['Employer-3 Phone'] = $r['employer3_phone'];
            $myarr['Langugage Reading'] = $r['langugage_reading'];
            $myarr['Langugage Writing'] = $r['langugage_writing'];
            $myarr['Langugage Undarstanding'] = $r['langugage_undarstanding'];
            $myarr['Langugage Where Learned'] = $r['langugage_where_learned'];
            $myarr['Language-1'] = $r['language1'];
            $myarr['Langugage-1 Reading'] = $r['langugage1_reading'];
            $myarr['Langugage-1 Writing'] = $r['langugage1_writing'];
            $myarr['Langugage-1 Undarstanding'] = $r['langugage1_undarstanding'];
            $myarr['Langugage-1 Where Learned'] = $r['langugage1_where_learned'];
            $myarr['Language-2'] = $r['language2'];
            $myarr['Langugage-2 Reading'] = $r['langugage2_reading'];
            $myarr['Langugage-2 Writing'] = $r['langugage2_writing'];
            $myarr['Langugage-2 Undarstanding'] = $r['langugage2_undarstanding'];
            $myarr['Langugage-2 Where Learned'] = $r['langugage2_where_learned'];
            $myarr['Language-3'] = $r['language3'];
            $myarr['Langugage-3 Reading'] = $r['langugage3_reading'];
            $myarr['Langugage-3 Writing'] = $r['langugage3_writing'];
            $myarr['Langugage-3 Undarstanding'] = $r['langugage3_undarstanding'];
            $myarr['Langugage-3 Where Learned'] = $r['langugage3_where_learned'];
            if ($r['date_start'] != '0000-00-00 00:00:00' || $r['date_start'] != '')
                $myarr['Date Start'] = $r['date_start'];
            else
                $myarr['Date Start'] = '';
            $myarr['Desired Salary'] = $r['desired_salary'];
            $myarr['Can Work'] = $r['can_work'];
            $myarr['Available'] = $r['available'];
            $myarr['Unalailable'] = $r['unalailable'];
            $myarr['Total Experience'] = $r['totalexperience'];
            $myarr['Skills'] = $r['skills'];
            $myarr['Driving License'] = $r['driving_license'];
            $myarr['License No'] = $r['license_no'];
            $myarr['License Country'] = $r['license_country'];
            $myarr['Reference'] = $r['reference'];
            $myarr['Reference Name'] = $r['reference_name'];
            if ($r['reference_city2'])
                $myarr['Reference City'] = $r['reference_city2'];
            else
                $myarr['Reference City'] = $r['reference_city'];
            if ($r['reference_state2'])
                $myarr['Reference State'] = $r['reference_state2'];
            else
                $myarr['Reference State'] = $r['reference_state'];
            if ($r['reference_country2'])
                $myarr['Reference Country'] = $r['reference_country2'];
            else
                $myarr['Reference Country'] = $r['reference_country'];
            $myarr['Reference Zipcode'] = $r['reference_zipcode'];
            $myarr['Reference Address'] = $r['reference_address'];
            $myarr['Reference Phone'] = $r['reference_phone'];
            $myarr['Reference Relation'] = $r['reference_relation'];
            $myarr['Reference Years'] = $r['reference_years'];
            $myarr['Reference-1'] = $r['reference1'];
            $myarr['Reference-1 Name'] = $r['reference1_name'];
            if ($r['reference1_city2'])
                $myarr['Reference-1 City'] = $r['reference1_city2'];
            else
                $myarr['Reference-1 City'] = $r['reference1_city'];
            if ($r['reference1_state2'])
                $myarr['Reference-1 State'] = $r['reference1_state2'];
            else
                $myarr['Reference-1 State'] = $r['reference1_state'];
            if ($r['reference1_country2'])
                $myarr['Reference-1 Country'] = $r['reference1_country2'];
            else
                $myarr['Reference-1 Country'] = $r['reference1_country'];
            $myarr['Reference-1 Address'] = $r['reference1_address'];
            $myarr['Reference-1 Phone'] = $r['reference1_phone'];
            $myarr['Reference-1 Relation'] = $r['reference1_relation'];
            $myarr['Reference-1 Years'] = $r['reference1_years'];
            $myarr['Reference-2'] = $r['reference2'];
            $myarr['Reference-2 Name'] = $r['reference2_name'];
            $myarr['Reference-2 Country'] = $r['reference2_country'];
            $myarr['Reference-2 State'] = $r['reference2_state'];
            $myarr['Reference-2 City'] = $r['reference2_city'];
            $myarr['Reference-2 Address'] = $r['reference2_address'];
            $myarr['Reference-2 Phone'] = $r['reference2_phone'];
            $myarr['Reference-2 Relation'] = $r['reference2_relation'];
            $myarr['Reference-2 Years'] = $r['reference2_years'];
            $myarr['Reference-3'] = $r['reference3'];
            $myarr['Reference-3 Name'] = $r['reference3_name'];
            $myarr['Reference-3 Country'] = $r['reference3_country'];
            $myarr['Reference-3 State'] = $r['reference3_state'];
            $myarr['Reference-3 City'] = $r['reference3_city'];
            $myarr['Reference-3 Address'] = $r['reference3_address'];
            $myarr['Reference-3 Phone'] = $r['reference3_phone'];
            $myarr['Reference-3 Relation'] = $r['reference3_relation'];
            $myarr['Reference-3 Years'] = $r['reference3_years'];
            if ($r['address1_city2'])
                $myarr['Address-1 City'] = $r['address1_city2'];
            else
                $myarr['Address-1 City'] = $r['address1_city'];
            if ($r['address1_state2'])
                $myarr['Address-1 State'] = $r['address1_state2'];
            else
                $myarr['Address-1 State'] = $r['address1_state'];
            if ($r['address1_country2'])
                $myarr['Address-1 Country'] = $r['address1_country2'];
            else
                $myarr['Address-1 Country'] = $r['address1_country'];
            $myarr['Address-1 Zipcode'] = $r['address1_zipcode'];
            $myarr['Address-1'] = $r['address1'];
            if ($r['address2_city2'])
                $myarr['Address-2 City'] = $r['address2_city2'];
            else
                $myarr['Address-2 City'] = $r['address2_city'];
            if ($r['address2_state2'])
                $myarr['Address-2 State'] = $r['address2_state2'];
            else
                $myarr['Address-2 State'] = $r['address2_state'];
            if ($r['address2_country2'])
                $myarr['Address-2 Country'] = $r['address2_country2'];
            else
                $myarr['Address-2 Country'] = $r['address2_country'];
            $myarr['Address-2 Zipcode'] = $r['address2_zipcode'];
            $myarr['Address-2'] = $r['address2'];
            $myarr['Reference-1 Zipcode'] = $r['reference1_zipcode'];
            $myarr['Reference-2 Zipcode'] = $r['reference2_zipcode'];
            $myarr['Reference-3 Zipcode'] = $r['reference3_zipcode'];
            $myarr['Apply Date'] = $r['apply_date'];
            $myarr['Comments'] = $r['comments'];

            $returnvalue[] = $myarr;
        }
        return $returnvalue;
    }

    /* END EXPORT RESUMES */

    function getExportAllResumesByJobId($jobid) {
        if (!is_numeric($jobid))
            return false;
        $db = $this->getDBO();
        $query = "SELECT resume.*,applyjob.apply_date AS apply_date,applyjob.comments AS comments,cat.cat_title AS categorytitle,salary.rangestart AS rangestart,salary.rangeend AS rangeend,jobtype.title AS jobtypetitle,heighesteducation.title AS heighesteducationtitle,
                            nationality_country.name AS nationalitycountry,address_city.name AS address_city2,address_state.name AS address_state2,
                            address_country.name AS address_country2,address1_city.name AS address1_city2,address1_state.name AS address1_state2,
                            address1_country.name AS address1_country2,address2_city.name AS address2_city2,address2_state.name AS address2_state2,
                            address2_country.name AS address2_country2,institute_city.name AS institute_city2,institute_state.name AS institute_state2,
                            institute_country.name AS institute_country2,institute1_city.name AS institute1_city2,institute1_state.name AS institute1_state2,
                            institute1_country.name AS institute1_country2,institute2_city.name AS institute2_city2,institute2_state.name AS institute2_state2,
                            institute2_country.name AS institute2_country2,institute3_city.name AS institute3_city2,institute3_state.name AS institute3_state2,
                            institute3_country.name AS institute3_country2,employer_city.name AS employer_city2,employer_state.name AS employer_state2,
                            employer_country.name AS employer_country2,employer1_city.name AS employer1_city2,employer1_state.name AS employer1_state2,
                            employer1_country.name AS employer1_country2,employer2_city.name AS employer2_city2,employer2_state.name AS employer2_state2,
                            employer2_country.name AS employer2_country2,employer3_city.name AS employer3_city2,employer3_state.name AS employer3_state2,
                            employer3_country.name AS employer3_country2,reference_city.name AS reference_city2,reference_state.name AS reference_state2,
                            reference_country.name AS reference_country2,reference1_city.name AS reference1_city2,reference1_state.name AS reference1_state2,
                            reference1_country.name AS reference1_country2,resume.id AS id,resume.uid AS uid,resume.application_title AS application_title,resume.first_name AS first_name,
                            resume.last_name AS last_name,resume.middle_name AS middle_name,resume.gender AS gender,resume.email_address AS email_address,resume.home_phone AS home_phone,resume.work_phone AS work_phone,
                            resume.cell AS cell,resume.nationality AS iamavailable,resume.searchable AS searchable,resume.photo AS photo,resume.job_category AS job_category,resume.jobsalaryrange AS jobsalaryrange,
                            resume.jobtype AS jobtype,resume.heighestfinisheducation AS heighestfinisheducation,resume.address_country AS address_country,resume.address_state AS address_state,resume.address_city AS address_city,
                            resume.address_zipcode AS address_zipcode,resume.address AS address,resume.institute AS institute,resume.institute_country AS institute_country,resume.institute_state AS institute_state,
                            resume.institute_city AS institute_city,resume.institute_address AS institute_address,resume.institute_certificate_name AS institute_certificate_name,
                            resume.institute_study_area AS institute_study_area,resume.employer AS employer,resume.employer_position AS employer_position,resume.employer_resp AS employer_resp,resume.employer_pay_upon_leaving AS employer_pay_upon_leaving,
                            resume.employer_supervisor AS employer_supervisor,resume.employer_from_date AS employer_from_date,resume.employer_to_date AS employer_to_date,resume.employer_leave_reason AS employer_leave_reason,resume.employer_country AS employer_country,
                            resume.employer_state AS employer_state,resume.employer_city AS employer_city,resume.employer_zip AS employer_zip,resume.employer_phone AS employer_phone,resume.employer_address AS employer_address,
                            resume.institute1 AS institute1,resume.institute1_country AS institute1_country,resume.institute1_state AS institute1_state,resume.institute1_city AS institute1_city,resume.institute1_address AS institute1_address,
                            resume.institute1_certificate_name AS institute1_certificate_name,resume.institute1_study_area AS institute2,resume.institute2_country AS institute2_country,resume.institute2_state AS institute2_state,resume.institute2_city AS institute2_city,
                            resume.institute2_address AS institute2_address,resume.institute2_certificate_name AS institute2_certificate_name,resume.institute2_study_area AS institute2_study_area,resume.institute3 AS institute3,resume.institute3_country AS institute3_country,resume.institute3_state AS institute3_state,
                            resume.institute3_city AS institute3_city,resume.institute3_address AS institute3_address,resume.institute3_study_area AS institute3_study_area,resume.institute3_certificate_name AS employer1,resume.employer1_position AS employer1_position,
                            resume.employer1_resp AS employer1_resp,resume.employer1_pay_upon_leaving AS employer1_pay_upon_leaving,resume.employer1_supervisor AS employer1_supervisor,resume.employer1_from_date AS employer1_from_date,resume.employer1_to_date AS employer1_to_date,resume.employer1_leave_reason AS employer1_country,
                            resume.employer1_state AS employer1_state,resume.employer1_city AS employer1_city,resume.employer1_zip AS employer1_zip,resume.employer1_phone AS employer1_phone,resume.employer1_address AS employer1_address,resume.employer2 AS employer2,resume.employer2_position AS employer2_position,
                            resume.employer2_resp AS employer2_resp,resume.employer2_pay_upon_leaving AS employer2_pay_upon_leaving,resume.employer2_supervisor AS employer2_supervisor,resume.employer2_from_date AS employer2_from_date,resume.employer2_to_date AS employer2_to_date,resume.employer2_leave_reason AS employer2_leave_reason,resume.employer2_country AS employer2_country,
                            resume.employer2_state AS employer2_state,resume.employer2_city AS employer2_city,resume.employer2_zip AS employer2_zip,resume.employer2_address AS employer2_address,resume.employer2_phone AS employer2_phone,resume.employer3 AS employer3,resume.employer3_position AS employer3_position,resume.employer3_resp AS employer3_resp,
                            resume.employer3_pay_upon_leaving AS employer3_pay_upon_leaving,resume.employer3_supervisor AS employer3_supervisor,resume.employer3_from_date AS employer3_from_date,resume.employer3_to_date AS employer3_to_date,resume.employer3_leave_reason AS employer3_leave_reason,resume.employer3_country AS employer3_country,resume.employer3_state AS employer3_state,
                            resume.employer3_city AS employer3_city,resume.employer3_zip AS employer3_zip,resume.employer3_address AS employer3_phone,
                            resume.language_reading AS langugage_reading,
                            resume.language_writing AS langugage_writing,resume.language_understanding AS langugage_undarstanding,resume.language_where_learned AS langugage_where_learned,resume.language1 AS language1,
                            resume.language1_reading AS langugage1_reading,resume.language1_writing AS langugage1_writing,resume.language1_understanding AS langugage1_undarstanding,resume.language1_where_learned AS langugage1_where_learned,resume.language2 AS language2,resume.language2_reading AS langugage2_reading,resume.language2_writing AS langugage2_writing,resume.language2_understanding AS langugage2_undarstanding,
                            resume.language2_where_learned AS langugage2_where_learned,resume.language3 AS language3,resume.language3_reading AS langugage3_reading,resume.language3_writing AS langugage3_writing,resume.language3_understanding AS langugage3_undarstanding,resume.language3_where_learned AS langugage3_where_learned,resume.date_start AS date_start,resume.desired_salary AS desired_salary,resume.can_work AS can_work,
                            resume.available AS available,resume.unalailable AS unalailable,resume.total_experience AS total_experience,resume.skills AS skills,resume.driving_license AS driving_license,resume.license_no AS license_no,resume.license_country AS license_country,resume.reference AS reference,resume.reference_name AS reference_name,resume.reference_country AS reference_country,resume.reference_state AS reference_state,
                            resume.reference_city AS reference_city,resume.reference_zipcode AS reference_zipcode,resume.reference_address AS reference_address,resume.reference_phone AS reference_phone,resume.reference_relation AS reference_relation,resume.reference_years AS reference_years,resume.reference1 AS reference1,resume.reference1_name AS reference1_name,
                            resume.reference1_country AS reference1_country,resume.reference1_state AS reference1_state,resume.reference1_city AS reference1_city,resume.reference1_address AS reference1_address,resume.reference1_phone AS reference1_phone,resume.reference1_relation AS reference1_relation,resume.reference1_years AS reference1_years,resume.reference2 AS reference2,resume.reference2_name AS reference2_name,
                            resume.reference2_country AS reference2_country,resume.reference2_state AS reference2_state,resume.reference2_city AS reference2_city,resume.reference2_address AS reference2_address,resume.reference2_phone AS reference2_phone,resume.reference2_relation AS reference2_relation,resume.reference2_years AS reference2_years,resume.reference3 AS reference3,
                            resume.reference3_name AS reference3_name,resume.reference3_country AS reference3_country,resume.reference3_state AS reference3_state,resume.reference3_city AS reference3_city,resume.reference3_address AS reference3_address,resume.reference3_phone AS reference3_phone,
                            resume.reference3_relation AS reference3_relation,resume.reference3_years AS reference3_years,resume.address1_country AS address1_country,resume.address1_state AS address1_state,resume.address1_city AS address1_city,resume.address1_zipcode AS address1_zipcode,
                            resume.address1 AS address1,resume.address2_country AS address2_country,resume.address2_state AS address2_state,resume.address2_city AS address2_city,resume.address2_zipcode AS address2_zipcode,resume.address2 AS address2,resume.reference1_zipcode AS reference1_zipcode,resume.reference2_zipcode AS reference2_zipcode,resume.reference3_zipcode AS reference3_zipcode,resume.packageid AS packageid,resume.paymenthistoryid AS paymenthistoryid,resume.status AS status,
                            totalexperience.title AS totalexperience
                                FROM `#__js_job_resume` AS resume
                                LEFT JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
                                LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                                JOIN `#__js_job_jobapply` AS applyjob ON applyjob.cvid = resume.id
								LEFT JOIN `#__js_job_experiences` AS totalexperience ON resume.total_experience = totalexperience.id
                                LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id
                                LEFT JOIN `#__js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id
                                LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                                LEFT JOIN `#__js_job_cities` AS address_city ON resume.address_city = address_city.id
                                LEFT JOIN `#__js_job_states` AS address_state ON address_city.stateid = address_state.id
                                LEFT JOIN `#__js_job_countries` AS address_country ON address_city.countryid = address_country.id
                                LEFT JOIN `#__js_job_cities` AS address1_city ON resume.address1_city = address1_city.id
                                LEFT JOIN `#__js_job_states` AS address1_state ON address1_city.stateid = address1_state.id
                                LEFT JOIN `#__js_job_countries` AS address1_country ON address1_city.countryid = address1_country.id
                                LEFT JOIN `#__js_job_cities` AS address2_city ON resume.address2_city = address2_city.id
                                LEFT JOIN `#__js_job_states` AS address2_state ON address2_city.stateid = address2_state.id
                                LEFT JOIN `#__js_job_countries` AS address2_country ON address2_city.countryid = address2_country.id
                                LEFT JOIN `#__js_job_cities` AS institute_city ON resume.institute_city = institute_city.id
                                LEFT JOIN `#__js_job_states` AS institute_state ON institute_city.stateid = institute_state.id
                                LEFT JOIN `#__js_job_countries` AS institute_country ON institute_city.countryid = institute_country.id
                                LEFT JOIN `#__js_job_cities` AS  institute1_city ON resume.institute1_city = institute1_city.id
                                LEFT JOIN `#__js_job_states` AS institute1_state ON institute1_city.stateid = institute1_state.id
                                LEFT JOIN `#__js_job_countries` AS institute1_country ON institute1_city.countryid = institute1_country.id
                                LEFT JOIN `#__js_job_cities` AS institute2_city ON resume.institute2_city = institute2_city.id
                                
                                LEFT JOIN `#__js_job_states` AS institute2_state ON institute2_city.stateid = institute2_state.id
                                LEFT JOIN `#__js_job_countries` AS institute2_country ON institute2_city.countryid = institute2_country.id
                                LEFT JOIN `#__js_job_cities` AS institute3_city ON resume.institute3_city = institute3_city.id
                                
                                LEFT JOIN `#__js_job_states` AS institute3_state ON institute3_city.stateid = institute3_state.id
                                LEFT JOIN `#__js_job_countries` AS institute3_country ON institute3_city.countryid = institute3_country.id
                                LEFT JOIN `#__js_job_cities` AS employer_city ON resume.employer_city = employer_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer_state ON employer_city.stateid = employer_state.id
                                LEFT JOIN `#__js_job_countries` AS employer_country ON employer_city.countryid = employer_country.id
                                LEFT JOIN `#__js_job_cities` AS employer1_city ON resume.employer1_city = employer1_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer1_state ON employer1_city.stateid = employer1_state.id
                                LEFT JOIN `#__js_job_countries` AS employer1_country ON employer1_city.countryid = employer1_country.id
                                LEFT JOIN `#__js_job_cities` AS employer2_city ON resume.employer2_city = employer2_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer2_state ON employer2_city.stateid = employer2_state.id
                                LEFT JOIN `#__js_job_countries` AS employer2_country ON employer2_city.countryid = employer2_country.id
                                LEFT JOIN `#__js_job_cities` AS employer3_city ON resume.employer3_city = employer3_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer3_state ON employer3_city.stateid = employer3_state.id
                                LEFT JOIN `#__js_job_countries` AS employer3_country ON employer3_city.countryid = employer3_country.id
                                LEFT JOIN `#__js_job_cities` AS reference_city ON resume.reference_city = reference_city.id
                                
                                LEFT JOIN `#__js_job_states` AS reference_state ON reference_city.stateid = reference_state.id
                                LEFT JOIN `#__js_job_countries` AS reference_country ON reference_city.countryid = reference_country.id
                                LEFT JOIN `#__js_job_cities` AS reference1_city ON resume.reference1_city = reference1_city.id
                                
                                LEFT JOIN `#__js_job_states` AS reference1_state ON reference1_city.stateid = reference1_state.id
                                LEFT JOIN `#__js_job_countries` AS reference1_country ON reference1_city.countryid = reference1_country.id
                                WHERE applyjob.jobid =" . $jobid;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }

    function getExportResumes($jobid, $resumeid) {
        if (!is_numeric($jobid))
            return false;
        if (!is_numeric($resumeid))
            return false;
        $db = $this->getDBO();
        $query = "SELECT resume.*,applyjob.apply_date AS apply_date,applyjob.comments AS comments,cat.cat_title AS categorytitle,salary.rangestart AS rangestart,salary.rangeend AS rangeend,jobtype.title AS jobtypetitle,heighesteducation.title AS heighesteducationtitle,
						nationality_country.name AS nationalitycountry,address_city.name AS address_city2,address_state.name AS address_state2,
						address_country.name AS address_country2,address1_city.name AS address1_city2,address1_state.name AS address1_state2,
						address1_country.name AS address1_country2,address2_city.name AS address2_city2,address2_state.name AS address2_state2,
						address2_country.name AS address2_country2,institute_city.name AS institute_city2,institute_state.name AS institute_state2,
						institute_country.name AS institute_country2,institute1_city.name AS institute1_city2,institute1_state.name AS institute1_state2,
						institute1_country.name AS institute1_country2,institute2_city.name AS institute2_city2,institute2_state.name AS institute2_state2,
						institute2_country.name AS institute2_country2,institute3_city.name AS institute3_city2,institute3_state.name AS institute3_state2,
						institute3_country.name AS institute3_country2,employer_city.name AS employer_city2,employer_state.name AS employer_state2,
						employer_country.name AS employer_country2,employer1_city.name AS employer1_city2,employer1_state.name AS employer1_state2,
						employer1_country.name AS employer1_country2,employer2_city.name AS employer2_city2,employer2_state.name AS employer2_state2,
						employer2_country.name AS employer2_country2,employer3_city.name AS employer3_city2,employer3_state.name AS employer3_state2,
						employer3_country.name AS employer3_country2,reference_city.name AS reference_city2,reference_state.name AS reference_state2,
						reference_country.name AS reference_country2,reference1_city.name AS reference1_city2,reference1_state.name AS reference1_state2,
						reference1_country.name AS reference1_country2,resume.id AS id,resume.uid AS uid,resume.application_title AS application_title,resume.first_name AS first_name,
						resume.last_name AS last_name,resume.middle_name AS middle_name,resume.gender AS gender,resume.email_address AS email_address,resume.home_phone AS home_phone,resume.work_phone AS work_phone,
						resume.cell AS cell,resume.nationality AS iamavailable,resume.searchable AS searchable,resume.photo AS photo,resume.job_category AS job_category,resume.jobsalaryrange AS jobsalaryrange,
						resume.jobtype AS jobtype,resume.heighestfinisheducation AS heighestfinisheducation,resume.address_country AS address_country,resume.address_state AS address_state,resume.address_city AS address_city,
						resume.address_zipcode AS address_zipcode,resume.address AS address,resume.institute AS institute,resume.institute_country AS institute_country,resume.institute_state AS institute_state,
						resume.institute_city AS institute_city,resume.institute_address AS institute_address,resume.institute_certificate_name AS institute_certificate_name,
						resume.institute_study_area AS institute_study_area,resume.employer AS employer,resume.employer_position AS employer_position,resume.employer_resp AS employer_resp,resume.employer_pay_upon_leaving AS employer_pay_upon_leaving,
						resume.employer_supervisor AS employer_supervisor,resume.employer_from_date AS employer_from_date,resume.employer_to_date AS employer_to_date,resume.employer_leave_reason AS employer_leave_reason,resume.employer_country AS employer_country,
						resume.employer_state AS employer_state,resume.employer_city AS employer_city,resume.employer_zip AS employer_zip,resume.employer_phone AS employer_phone,resume.employer_address AS employer_address,
						resume.institute1 AS institute1,resume.institute1_country AS institute1_country,resume.institute1_state AS institute1_state,resume.institute1_city AS institute1_city,resume.institute1_address AS institute1_address,
						resume.institute1_certificate_name AS institute1_certificate_name,resume.institute1_study_area AS institute2,resume.institute2_country AS institute2_country,resume.institute2_state AS institute2_state,resume.institute2_city AS institute2_city,
						resume.institute2_address AS institute2_address,resume.institute2_certificate_name AS institute2_certificate_name,resume.institute2_study_area AS institute2_study_area,resume.institute3 AS institute3,resume.institute3_country AS institute3_country,resume.institute3_state AS institute3_state,
						resume.institute3_city AS institute3_city,resume.institute3_address AS institute3_address,resume.institute3_study_area AS institute3_study_area,resume.institute3_certificate_name AS employer1,resume.employer1_position AS employer1_position,
						resume.employer1_resp AS employer1_resp,resume.employer1_pay_upon_leaving AS employer1_pay_upon_leaving,resume.employer1_supervisor AS employer1_supervisor,resume.employer1_from_date AS employer1_from_date,resume.employer1_to_date AS employer1_to_date,resume.employer1_leave_reason AS employer1_country,
						resume.employer1_state AS employer1_state,resume.employer1_city AS employer1_city,resume.employer1_zip AS employer1_zip,resume.employer1_phone AS employer1_phone,resume.employer1_address AS employer1_address,resume.employer2 AS employer2,resume.employer2_position AS employer2_position,
						resume.employer2_resp AS employer2_resp,resume.employer2_pay_upon_leaving AS employer2_pay_upon_leaving,resume.employer2_supervisor AS employer2_supervisor,resume.employer2_from_date AS employer2_from_date,resume.employer2_to_date AS employer2_to_date,resume.employer2_leave_reason AS employer2_leave_reason,resume.employer2_country AS employer2_country,
						resume.employer2_state AS employer2_state,resume.employer2_city AS employer2_city,resume.employer2_zip AS employer2_zip,resume.employer2_address AS employer2_address,resume.employer2_phone AS employer2_phone,resume.employer3 AS employer3,resume.employer3_position AS employer3_position,resume.employer3_resp AS employer3_resp,
						resume.employer3_pay_upon_leaving AS employer3_pay_upon_leaving,resume.employer3_supervisor AS employer3_supervisor,resume.employer3_from_date AS employer3_from_date,resume.employer3_to_date AS employer3_to_date,resume.employer3_leave_reason AS employer3_leave_reason,resume.employer3_country AS employer3_country,resume.employer3_state AS employer3_state,
						resume.employer3_city AS employer3_city,resume.employer3_zip AS employer3_zip,resume.employer3_address AS employer3_phone,
						resume.language_reading AS langugage_reading,
						resume.language_writing AS langugage_writing,resume.language_understanding AS langugage_undarstanding,resume.language_where_learned AS langugage_where_learned,resume.language1 AS language1,
						resume.language1_reading AS langugage1_reading,resume.language1_writing AS langugage1_writing,resume.language1_understanding AS langugage1_undarstanding,resume.language1_where_learned AS langugage1_where_learned,resume.language2 AS language2,resume.language2_reading AS langugage2_reading,resume.language2_writing AS langugage2_writing,resume.language2_understanding AS langugage2_undarstanding,
						resume.language2_where_learned AS langugage2_where_learned,resume.language3 AS language3,resume.language3_reading AS langugage3_reading,resume.language3_writing AS langugage3_writing,resume.language3_understanding AS langugage3_undarstanding,resume.language3_where_learned AS langugage3_where_learned,resume.date_start AS date_start,resume.desired_salary AS desired_salary,resume.can_work AS can_work,
						resume.available AS available,resume.unalailable AS unalailable,resume.total_experience AS total_experience,resume.skills AS skills,resume.driving_license AS driving_license,resume.license_no AS license_no,resume.license_country AS license_country,resume.reference AS reference,resume.reference_name AS reference_name,resume.reference_country AS reference_country,resume.reference_state AS reference_state,
						resume.reference_city AS reference_city,resume.reference_zipcode AS reference_zipcode,resume.reference_address AS reference_address,resume.reference_phone AS reference_phone,resume.reference_relation AS reference_relation,resume.reference_years AS reference_years,resume.reference1 AS reference1,resume.reference1_name AS reference1_name,
						resume.reference1_country AS reference1_country,resume.reference1_state AS reference1_state,resume.reference1_city AS reference1_city,resume.reference1_address AS reference1_address,resume.reference1_phone AS reference1_phone,resume.reference1_relation AS reference1_relation,resume.reference1_years AS reference1_years,resume.reference2 AS reference2,resume.reference2_name AS reference2_name,
						resume.reference2_country AS reference2_country,resume.reference2_state AS reference2_state,resume.reference2_city AS reference2_city,resume.reference2_address AS reference2_address,resume.reference2_phone AS reference2_phone,resume.reference2_relation AS reference2_relation,resume.reference2_years AS reference2_years,resume.reference3 AS reference3,
						resume.reference3_name AS reference3_name,resume.reference3_country AS reference3_country,resume.reference3_state AS reference3_state,resume.reference3_city AS reference3_city,resume.reference3_address AS reference3_address,resume.reference3_phone AS reference3_phone,
						resume.reference3_relation AS reference3_relation,resume.reference3_years AS reference3_years,resume.address1_country AS address1_country,resume.address1_state AS address1_state,resume.address1_city AS address1_city,resume.address1_zipcode AS address1_zipcode,
						resume.address1 AS address1,resume.address2_country AS address2_country,resume.address2_state AS address2_state,resume.address2_city AS address2_city,resume.address2_zipcode AS address2_zipcode,resume.address2 AS address2,resume.reference1_zipcode AS reference1_zipcode,resume.reference2_zipcode AS reference2_zipcode,resume.reference3_zipcode AS reference3_zipcode,resume.packageid AS packageid,resume.paymenthistoryid AS paymenthistoryid,resume.status AS status,
						totalexperience.title AS totalexperience
							FROM `#__js_job_resume` AS resume
							LEFT JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
							LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
							JOIN `#__js_job_jobapply` AS applyjob ON applyjob.jobid = " . $jobid . " AND applyjob.cvid=" . $resumeid . "
							LEFT JOIN `#__js_job_experiences` AS totalexperience ON resume.total_experience = totalexperience.id
							LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id
							LEFT JOIN `#__js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id
							LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
							LEFT JOIN `#__js_job_cities` AS address_city ON resume.address_city = address_city.id
							LEFT JOIN `#__js_job_states` AS address_state ON address_city.stateid = address_state.id
							LEFT JOIN `#__js_job_countries` AS address_country ON address_city.countryid = address_country.id
							LEFT JOIN `#__js_job_cities` AS address1_city ON resume.address1_city = address1_city.id
							LEFT JOIN `#__js_job_states` AS address1_state ON address1_city.stateid = address1_state.id
							LEFT JOIN `#__js_job_countries` AS address1_country ON address1_city.countryid = address1_country.id
							LEFT JOIN `#__js_job_cities` AS address2_city ON resume.address2_city = address2_city.id
							LEFT JOIN `#__js_job_states` AS address2_state ON address2_city.stateid = address2_state.id
							LEFT JOIN `#__js_job_countries` AS address2_country ON address2_city.countryid = address2_country.id
							LEFT JOIN `#__js_job_cities` AS institute_city ON resume.institute_city = institute_city.id
							LEFT JOIN `#__js_job_states` AS institute_state ON institute_city.stateid = institute_state.id
							LEFT JOIN `#__js_job_countries` AS institute_country ON institute_city.countryid = institute_country.id
							LEFT JOIN `#__js_job_cities`  institute1_city ON resume.institute1_city = institute1_city.id
							LEFT JOIN `#__js_job_states` AS institute1_state ON institute1_city.stateid = institute1_state.id
							LEFT JOIN `#__js_job_countries` AS institute1_country ON institute1_city.countryid = institute1_country.id
							LEFT JOIN `#__js_job_cities` AS institute2_city ON resume.institute2_city = institute2_city.id
							
							LEFT JOIN `#__js_job_states` AS institute2_state ON institute2_city.stateid = institute2_state.id
							LEFT JOIN `#__js_job_countries` AS institute2_country ON institute2_city.countryid = institute2_country.id
							LEFT JOIN `#__js_job_cities` AS institute3_city ON resume.institute3_city = institute3_city.id
							
							LEFT JOIN `#__js_job_states` AS institute3_state ON institute3_city.stateid = institute3_state.id
							LEFT JOIN `#__js_job_countries` AS institute3_country ON institute3_city.countryid = institute3_country.id
							LEFT JOIN `#__js_job_cities` AS employer_city ON resume.employer_city = employer_city.id
							
							LEFT JOIN `#__js_job_states` AS employer_state ON employer_city.stateid = employer_state.id
							LEFT JOIN `#__js_job_countries` AS employer_country ON employer_city.countryid = employer_country.id
							LEFT JOIN `#__js_job_cities` AS employer1_city ON resume.employer1_city = employer1_city.id
							
							LEFT JOIN `#__js_job_states` AS employer1_state ON employer1_city.stateid = employer1_state.id
							LEFT JOIN `#__js_job_countries` AS employer1_country ON employer1_city.countryid = employer1_country.id
							LEFT JOIN `#__js_job_cities` AS employer2_city ON resume.employer2_city = employer2_city.id
							
							LEFT JOIN `#__js_job_states` AS employer2_state ON employer2_city.stateid = employer2_state.id
							LEFT JOIN `#__js_job_countries` AS employer2_country ON employer2_city.countryid = employer2_country.id
							LEFT JOIN `#__js_job_cities` AS employer3_city ON resume.employer3_city = employer3_city.id
							
							LEFT JOIN `#__js_job_states` AS employer3_state ON employer3_city.stateid = employer3_state.id
							LEFT JOIN `#__js_job_countries` AS employer3_country ON employer3_city.countryid = employer3_country.id
							LEFT JOIN `#__js_job_cities` AS reference_city ON resume.reference_city = reference_city.id
							
							LEFT JOIN `#__js_job_states` AS reference_state ON reference_city.stateid = reference_state.id
							LEFT JOIN `#__js_job_countries` AS reference_country ON reference_city.countryid = reference_country.id
							LEFT JOIN `#__js_job_cities` AS reference1_city ON resume.reference1_city = reference1_city.id
							
							LEFT JOIN `#__js_job_states` AS reference1_state ON reference1_city.stateid = reference1_state.id
							LEFT JOIN `#__js_job_countries` AS reference1_country ON reference1_city.countryid = reference1_country.id
							WHERE resume.id =" . $resumeid;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }

}

?>
