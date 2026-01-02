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

class JSJobsModelJsjobs extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        //$this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function storeServerSerailNumber($data) {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        $db = Factory::getDBO();
        if ($data['server_serialnumber']) {
            $query = "UPDATE  `#__js_job_config` SET configvalue=" . $db->Quote($data['server_serialnumber']) . " WHERE configname='server_serial_number'";
            $db->setQuery($query);
            if (!$db->execute())
                return false;
            else
                return true;
        } else
            return false;
    }

    function getGraphData() {
        $db = Factory::getDBO();
        $d = array();
        for ($i = 0; $i <= 14; $i++) {
            $d[] = date("Y-m-d", strtotime('-' . $i . ' days'));
        }
        foreach ($d AS $day) {
            $datevar = date('Y-m-d', strtotime($day));
            $query = "SELECT count(id) AS id FROM #__js_job_jobs where DATE(created) =".$db->Quote($datevar);
            $db->setQuery($query);
            $total_jobs_per_day = $db->loadObject();

            $query = "SELECT count(id) AS id FROM `#__js_job_resume` where DATE(created) =".$db->Quote($datevar);
            $db->setQuery($query);
            $total_resume_per_day = $db->loadObject();
            $time_format = strtotime($day);
            $json_format_data[] = array(array($time_format . '000', $total_jobs_per_day->id), array($time_format . '000', $total_resume_per_day->id));
        }

        $json_data = json_encode($json_format_data);
        return $json_data;
    }

    function getTodayStats() {
        $db = Factory::getDBO();
        $result = array();
        // Total stats
        $query = 'SELECT count(id) AS totalcompanies FROM #__js_job_companies';
        $db->setQuery($query);
        $companies = $db->loadResult();
        $query = 'SELECT count(id) AS totaljobs FROM #__js_job_jobs';
        $db->setQuery($query);
        $jobs = $db->loadResult();
        $query = 'SELECT count(id) AS totalresume FROM #__js_job_resume';
        $db->setQuery($query);
        $resumes = $db->loadResult();
        $curdate = date('Y-m-d');
        $query = "SELECT COUNT(id) AS activejobs FROM `#__js_job_jobs` WHERE DATE(startpublishing) <= ".$db->Quote($curdate)." AND DATE(stoppublishing) >= ".$db->Quote($curdate);
        $db->setQuery($query);
        $activejobs = $db->loadResult();
        $query = 'SELECT count(id) AS appliedjobs FROM `#__js_job_jobapply`';
        $db->setQuery($query);
        $appliedjobs = $db->loadResult();

        $result['companies'] = $companies;
        $result['jobs'] = $jobs;
        $result['resumes'] = $resumes;
        $result['activejobs'] = $activejobs;
        $result['appliedjobs'] = $appliedjobs;
        // Today Stats
        
        $config = $this->getJSModel('configuration')->getConfigByFor('default');
        $lastdate = date('Y-m-d', strtotime("now -1 month"));

        $query = "SELECT COUNT(id) FROM `#__js_job_jobs` WHERE DATE(created) BETWEEN ".$db->Quote($lastdate)." AND ".$db->Quote($curdate)." AND status = 1";
        $db->setQuery($query);
        $result['totalnewjobs'] = $db->loadResult();
        $query = "SELECT COUNT(id) FROM `#__js_job_companies` WHERE DATE(created) BETWEEN ".$db->Quote($lastdate)." AND ".$db->Quote($curdate)." AND status = 1";
        $db->setQuery($query);
        $result['totalnewcompanies'] = $db->loadResult();
        $query = "SELECT COUNT(id) FROM `#__js_job_resume` WHERE DATE(created) BETWEEN ".$db->Quote($lastdate)." AND ".$db->Quote($curdate)." AND status = 1";
        $db->setQuery($query);
        $result['totalnewresume'] = $db->loadResult();
        $query = "SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE DATE(apply_date) BETWEEN ".$db->Quote($lastdate)." AND ".$db->Quote($curdate);
        $db->setQuery($query);
        $result['totalnewjobapply'] = $db->loadResult();

        //Listings Data
        $curdate = date('Y-m-d');
        $fromdate = date('Y-m-d', strtotime("now -1 month"));
        //$fromdate = date('Y-m-d', strtotime("now -30 days"));
        $result['curdate'] = $curdate;
        $result['fromdate'] = $fromdate;
        $query = "SELECT job.startpublishing AS created
                    FROM `#__js_job_jobs` AS job WHERE DATE(job.startpublishing) >= ".$db->Quote($fromdate)." AND DATE(job.startpublishing) <= ".$db->Quote($curdate)." ORDER BY job.startpublishing";
        $db->setQuery($query);
        $alljobs = $db->loadObjectList();
        $jobs = array();
        foreach($alljobs AS $job){
            $date = date('Y-m-d',strtotime($job->created));
            $jobs[$date] = isset($jobs[$date]) ? ($jobs[$date] + 1) : 1;
        }
        $query = "SELECT company.created
                    FROM `#__js_job_companies` AS company WHERE date(company.created) >= ".$db->Quote($fromdate)." AND date(company.created) <= ".$db->Quote($curdate)." ORDER BY company.created";
        $db->setQuery($query);
        $allcompanies = $db->loadObjectList();
        $companies = array();
        foreach($allcompanies AS $company){
            $date = date('Y-m-d',strtotime($company->created));
            $companies[$date] = isset($companies[$date]) ? ($companies[$date] + 1) : 1;
        }
        $query = "SELECT resume.created
                    FROM `#__js_job_resume` AS resume WHERE date(resume.created) >= ".$db->Quote($fromdate)." AND date(resume.created) <= ".$db->Quote($curdate)."  ORDER BY resume.created";
        $db->setQuery($query);
        $allresume = $db->loadObjectList();
        $resumes = array();
        foreach($allresume AS $resume){
            $date = date('Y-m-d',strtotime($resume->created));
            $resumes[$date] = isset($resumes[$date]) ? ($resumes[$date] + 1) : 1;
        }
        $query = "SELECT job.startpublishing AS created
                    FROM `#__js_job_jobs` AS job WHERE date(job.startpublishing) >= ".$db->Quote($fromdate)." AND date(job.startpublishing) <= ".$db->Quote($curdate)." AND job.status = 1 ORDER BY job.created";
        $db->setQuery($query);
        $allactivejob = $db->loadObjectList();
        $activejobs = array();
        foreach($allactivejob AS $ajob){
            $date = date('Y-m-d',strtotime($ajob->created));
            $activejobs[$date] = isset($activejobs[$date]) ? ($activejobs[$date] + 1) : 1;
        }

        $result['stack_chart_horizontal']['title'] = "['".Text::_('Dates')."','".Text::_('Jobs')."','".Text::_('Companies')."','".Text::_('Resume')."','".Text::_('Active Jobs')."']";
        $result['stack_chart_horizontal']['data'] = '';
        for($i = 30; $i >= 0; $i--){
            $checkdate = HTMLHelper::_('date', strtotime($curdate." -$i days"), $config['date_format']);
            $datadate = date('Y-m-d',strtotime($curdate." -$i days"));
            if($i != 30){
                $result['stack_chart_horizontal']['data'] .= ',';    
            }
            $result['stack_chart_horizontal']['data'] .= "['".$checkdate."',";
            $job = isset($jobs[$datadate]) ? $jobs[$datadate] : 0;
            $company = isset($companies[$datadate]) ? $companies[$datadate] : 0;
            $resume = isset($resumes[$datadate]) ? $resumes[$datadate] : 0;
            $ajob = isset($activejobs[$datadate]) ? $activejobs[$datadate] : 0;
            $result['stack_chart_horizontal']['data'] .= "$job,$company,$resume,$ajob]";
        }

        $result['latestjobs'] = $this->getNewestJobs();

        return $result;
    }

    function getNewestJobs() {
        $db = Factory::getDBO();
        $query="SELECT job.id,job.created,job.isgoldjob,job.isfeaturedjob,job.title,job.companyid,job.startpublishing,job.stoppublishing,company.name,company.logofilename as pic, job.city , cate.cat_title, job_type.title as jobtype_title
                , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype, cur.symbol
                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_companies` AS company ON job.companyid=company.id
                    JOIN `#__js_job_categories` AS cate ON job.jobcategory=cate.id
                    JOIN `#__js_job_jobtypes` AS job_type ON job.jobtype=job_type.id
                    JOIN `#__js_job_currencies` AS cur ON job.currencyid=cur.id
                    LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                    LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                    LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        ORDER BY job.created DESC
                        LIMIT 5";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        return $data;
    }

    function removeOldSqlFiles() {
        $path = JPATH_ADMINISTRATOR.'/components/com_jsjobs/sql/updates/mysql/';
        $filenames[] = $path.'1.0.9.0.sql';
        $filenames[] = $path.'1.0.9.1.sql';
        $filenames[] = $path.'1.1.0.0.sql';
        $filenames[] = $path.'1.1.1.sql';
        $filenames[] = $path.'1.1.2.sql';
        $filenames[] = $path.'1.1.3.sql';
        $filenames[] = $path.'1.1.4.sql';
        $filenames[] = $path.'1.1.5.sql';
        $filenames[] = $path.'1.1.6.sql';
        $filenames[] = $path.'1.1.7.sql';
        $filenames[] = $path.'1.1.8.sql';
        $filenames[] = $path.'1.1.9.sql';
        $filenames[] = $path.'1.2.0.sql';
        $filenames[] = $path.'1.2.1.sql';
        $filenames[] = $path.'1.2.2.sql';
        $filenames[] = $path.'1.2.3.sql';
        $filenames[] = $path.'1.2.4.sql';
        $filenames[] = $path.'1.2.5.sql';
        $filenames[] = $path.'1.2.6.sql';
        $filenames[] = $path.'1.2.7.sql';
        $filenames[] = $path.'1.2.8.sql';
        $filenames[] = $path.'1.2.9.sql';
        $filenames[] = $path.'1.3.0.sql';
        $filenames[] = $path.'1.3.1.sql';
        $filenames[] = $path.'1.3.2.sql';
        $filenames[] = $path.'1.3.3.sql';
        $filenames[] = $path.'1.3.4.sql';
        foreach ($filenames AS $filename) {
            if (file_exists($filename)) {
                @unlink($filename);
            }
        }
        return;
    }


    function getConcurrentRequestData() {
        $db = Factory::getDBO();
        $query = "SELECT configname,configvalue FROM `#__js_job_config` WHERE configfor = " . $db->quote('hostdata');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        foreach ($result AS $res) {
            $return[$res->configname] = $res->configvalue;
        }
        return $return;
    }

    function getMultiCityDataForView($id, $for) {
        if (!is_numeric($id))
            return false;
        $db = $this->getDBO();
        $query = "select mcity.id AS id,country.name AS countryName,city.cityName AS cityName,state.name AS stateName";
        switch ($for) {
            case 1:
                $query.=" FROM `#__js_job_jobcities` AS mcity";
                $query.=" LEFT JOIN `#__js_job_jobs` AS job ON mcity.jobid=job.id";
                break;
            case 2:
                $query.=" FROM `#__js_job_companycities` AS mcity";
                $query.=" LEFT JOIN `#__js_job_companies` AS company ON mcity.companyid=company.id";
                break;
        }
        $query.=" LEFT JOIN `#__js_job_cities` AS city ON mcity.cityid=city.id
				  LEFT JOIN `#__js_job_states` AS state ON city.stateid=state.id
				  LEFT JOIN `#__js_job_countries` AS country ON city.countryid=country.id";
        switch ($for) {
            case 1:
                $query.=" where mcity.jobid=" . $id;
                break;
            case 2:
                $query.=" where mcity.companyid=" . $id;
                break;
        }
        $query.=" ORDER BY country.name";
        $db->setQuery($query);
        $cities = $db->loadObjectList();
        $mloc = array();
        $mcountry = array();
        $finalloc = "";
        foreach ($cities AS $city) {
            $mcountry[] = $city->countryName;
        }
        $country_total = array_count_values($mcountry);
        $i = 0;
        foreach ($country_total AS $key => $val) {
            foreach ($cities AS $city) {
                if ($key == $city->countryName) {
                    $i++;
                    if ($val == 1) {
                        $finalloc.="[" . $city->cityName . "," . $key . " ] ";
                        $i = 0;
                    } elseif ($i == $val) {
                        $finalloc.=$city->cityName . "," . $key . " ] ";
                        $i = 0;
                    } elseif ($i == 1)
                        $finalloc.= "[" . $city->cityName . ",";
                    else
                        $finalloc.=$city->cityName . ",";
                }
            }
        }
        return $finalloc;
    }

    function getUserGroups() {
        $db = Factory::getDBO();
        $query = "SELECT id,title AS name FROM #__usergroups";
        $db->setQuery($query);
        $usergroup = $db->loadObjectList();
        $groups = array();
        $groups[] = array('value' => '', 'text' => Text::_('Select Group'));
        foreach ($usergroup as $row) {
            $groups[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $groups;
    }


    function getStepTwoValidate() {
        $return['admin_dir'] = substr(sprintf('%o', fileperms('components/com_jsjobs')), -3);
        $return['site_dir'] = substr(sprintf('%o', fileperms('../components/com_jsjobs')), -3);
        $return['tmp_dir'] = substr(sprintf('%o', fileperms('../tmp')), -3);
        $db = $this->getDbo();
        $query = 'CREATE TABLE js_test_table(
                    id int,
                    name varchar(255)
                );';
        $db->setQuery($query);
        $return['create_table'] = 0;
        if ($db->execute()) {
            $return['create_table'] = 1;
        }
        $query = 'INSERT INTO js_test_table(id,name) VALUES (1,\'Joom\'),(2,\'Sky\');';
        $db->setQuery($query);
        $return['insert_record'] = 0;
        if ($db->execute()) {
            $return['insert_record'] = 1;
        }
        $query = 'UPDATE js_test_table SET name = \'JoomskyTest\' WHERE id = 1;';
        $db->setQuery($query);
        $return['update_record'] = 0;
        if ($db->execute()) {
            $return['update_record'] = 1;
        }
        $query = 'DELETE FROM js_test_table;';
        $db->setQuery($query);
        $return['delete_record'] = 0;
        if ($db->execute()) {
            $return['delete_record'] = 1;
        }
        $query = 'DROP TABLE js_test_table;';
        $db->setQuery($query);
        $return['drop_table'] = 0;
        if ($db->execute()) {
            $return['drop_table'] = 1;
        }
        if($return['tmp_dir'] >= 755){
            if(function_exists('curl_init')){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_URL, 'http://test.setup.joomsky.com/logo.png');
                $fp = fopen('../tmp/logo.png', 'w+');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                curl_exec ($ch);
                curl_close ($ch);
                fclose($fp);
                $return['file_downloaded'] = 0;
                if(file_exists('../tmp/logo.png')){
                    $return['file_downloaded'] = 1;
                }
            }else{
                $return['file_downloaded'] = 0;
            }
        }else $return['file_downloaded'] = 0;
        return $return;
    }

    function getConfigByConfigName($configname) {
        $db = Factory::getDBO();
        $query = "SELECT * FROM `#__js_job_config` WHERE configname = " . $db->quote($configname);
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

    function getConfigCount() {
        $db = Factory::getDBO();
        $query = "SELECT COUNT(*) FROM `#__js_job_config` ";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getListTranslations() {
        
        $result = array();
        $result['error'] = false;

        $path = JPATH_ADMINISTRATOR.'/language';

        if( ! is_writeable($path)){
            $result['error'] = Text::_('Dir is not writeable').' '.$path;

        }else{

            if($this->isConnected()){

                $versiontype = $this->getJSModel('jsjobs')->getConfigByConfigName('versiontype');
                $version = $this->getJSModel('jsjobs')->getConfigByConfigName('version');

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $post_data['product'] ='js-jobs-joomla';
                $post_data['domain'] = Uri::root();
                $post_data['producttype'] = $versiontype->configvalue;
                $post_data['productcode'] = 'jsjobs';
                $post_data['productversion'] = $version->configvalue;
                $post_data['JVERSION'] = JVERSION;
                $post_data['method'] = 'getTranslations';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                $response = curl_exec($ch);
                curl_close($ch);

                $result['data'] = $response;
            }else{
                $result['error'] = Text::_('Unable to connect to server');
            }
        }

        $result = json_encode($result);

        return $result;
    }

    function makeLanguageCode($lang_name , $path){

        if( strpos($lang_name, '_') !== false ) {
            $lang_name = str_replace('_', '-', $lang_name);
        }else{
            if($lang_name == 'en'){
                $lang_name = $lang_name.'-'.strtoupper('gb');
            }elseif($lang_name == 'sv'){
                $lang_name = $lang_name.'-'.strtoupper('se');
            }elseif($lang_name == 'ar'){
                $lang_folders = scandir($path);
                $n = count($lang_folders);
                for ($i = 0; $i < $n; $i++) { 
                    if($lang_folders[$i] == 'ar-SA'){
                        $lang_name = $lang_folders[$i];
                        $i = $n;
                    }elseif ($lang_folders[$i] == 'ar-EG'){
                        $lang_name = $lang_folders[$i];
                        $i = $n;
                    }elseif ($lang_folders[$i] == 'ar-AA'){
                        $lang_name = $lang_folders[$i];
                        $i = $n;
                    }
                }
            }else{
                $lang_name = $lang_name.'-'.strtoupper($lang_name);
            }
        }
        return $lang_name;
    }

    function validateAndShowDownloadFileName( $lang_name ){

        if($lang_name == '')
            return '';
        $result = array();
        $path = JPATH_ADMINISTRATOR.'/language';

        $final_name = $this->makeLanguageCode($lang_name , $path);

        $result['error'] = false;
        if( ! file_exists($path)){
            $result['error'] = $lang_name. ' ' . Text::_('Language is not installed');
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . Text::_('Language directory is not writeable').': '.$path;
        }else{
            $result['input'] = '<input id="languagecode" class="text_area" type="text" value="'.$final_name.'" name="languagecode">';
            $result['path'] = Text::_('Language code');
        }
        $result = json_encode($result);
        return $result;
    }

    function getLanguageTranslation($lang_name , $language_code){

        $result = array();
        $result['error'] = false;
        $path = JPATH_ADMINISTRATOR.'/language';

        if($lang_name == '' || $language_code == ''){
            $result['error'] = Text::_('Empty values');
            return json_encode($result);
        }
        
        $path = $path.'/'.$language_code;
        $final_path = $path.'/'.$language_code.'.com_jsjobs.ini';


        if( ! file_exists($path)){
            $result['error'] = $lang_name. ' ' . Text::_('Language is not installed');
            return json_encode($result);
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . Text::_('Language directory is not writeable').': '.$path;
            return json_encode($result);
        }

        if( ! file_exists($final_path)){
            touch($final_path);
        }

        if( ! is_writeable($final_path)){
            $result['error'] = Text::_('File is not writeable').': '.$final_path;
        }else{

            if($this->isConnected()){

                $version = $this->getJSModel('configuration')->getConfigByFor('version');

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $post_data['product'] ='js-jobs-joomla';
                $post_data['domain'] = Uri::root();
                $post_data['producttype'] = $version['versiontype'];
                $post_data['productcode'] = 'jsjobs';
                $post_data['productversion'] = $version['version'];
                $post_data['JVERSION'] = JVERSION;
                $post_data['translationcode'] = $lang_name;
                $post_data['method'] = 'getTranslationFile';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                $response = curl_exec($ch);
                curl_close($ch);
                ob_clean();
                $array = json_decode($response, true);

                $ret = $this->writeLanguageFile( $final_path , $array['file']);

                if($ret != false){
                    $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                    $post_data['product'] ='js-jobs-joomla';
                    $post_data['domain'] = Uri::root();
                    $post_data['producttype'] = $version['versiontype'];
                    $post_data['productcode'] = 'jsjobs';
                    $post_data['productversion'] = $version['version'];
                    $post_data['JVERSION'] = JVERSION;
                    $post_data['folder'] = $array['foldername'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    ob_clean();
                }
                $result['data'] = Text::_('File Downloaded Successfully');
            }else{
                $result['error'] = Text::_('Unable to connect to server');
            }
        }

        $result = json_encode($result);

        return $result;

    }

    function writeLanguageFile( $path , $url ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_URL, $url);
        $fp = fopen( $path , 'w');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        if( filesize($path) > 0 ){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    function isConnected(){
        
        $connected = @fsockopen("www.google.com", 80); 
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    function getAllJoomlaArticleContentList(){

        $query = "SELECT id AS value,title AS text FROM `#__content`";
        $db = Factory::getDBO();
        $db->setQuery($query); 
        $articles = $db->loadObjectList(); 
        return $articles;
    }

    function updateDataBaseForAI(){
        $result = $this->getJSModel('job')->updateRecordsForAISearchJob();
        $result = $this->getJSModel('resume')->importResumesAIData();

        // update the config to hide message
        $db = Factory::getDBO();
        $query = "UPDATE `#__js_job_config` SET `configvalue` = 0 WHERE `configname` = 'admin_show_update_data_base_message' ";
        $db->setQuery($query);
        $db->execute();
        return 1;
    }
}
?>
