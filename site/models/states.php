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
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelStates extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobsStates($showonlystatehavejobs = 0, $theme, $noofrecord = 20) {
        $db = Factory::getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $this->getJSModel('common')->setTheme();
        $curdate = date('Y-m-d');
        $havingquery = "";
        if ($showonlystatehavejobs == 1) {
            $havingquery = " HAVING totaljobsbystate > 0 ";
        }
        $stateid = "state.id AS stateid,";
        $query = "SELECT $stateid state.name AS statename,COUNT(DISTINCT job.id) AS totaljobsbystate
                    FROM `#__js_job_states` AS state
                    LEFT JOIN `#__js_job_cities` AS city ON state.id = city.stateid 
                    LEFT JOIN `#__js_job_countries` AS country ON country.id = city.countryid 
                    LEFT JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `#__js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing>=CURDATE() AND job.startpublishing <= CURDATE() )
                    WHERE country.enabled = 1  
                    GROUP BY stateid $havingquery ORDER BY totaljobsbystate DESC, cityname ASC";
        $db->setQuery($query, 0, $noofrecord);
        $result1 = $db->loadObjectList();
        $result[0] = $result1;
        $result[2] = $dateformat;
        return $result;
    }

    function getStates($countryid, $title) {
        $db = Factory::getDBO();
        if (is_numeric($countryid) === false)
            return false;
        if (empty($countryid))
            $countryid = 0;
        $query = "SELECT * FROM `#__js_job_states` WHERE enabled = 'Y' AND countryid = " . $countryid;
        $query.=" ORDER BY name ASC ";
        try{
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        $states = array();
        if ($title)
            $states[] = array('value' => Text::_(''), 'text' => $title);
        else
            $states[] = array('value' => Text::_(''), 'text' => Text::_('Select State'));

        foreach ($rows as $row) {
            $states[] = array('value' => $row->id,
                'text' => Text::_($row->name));
        }
        return $states;
    }

    function getStateIdByName($name) { // new function coded
        $db = Factory::getDBO();

        $query = "SELECT id FROM `#__js_job_states` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER(" . $db->Quote($name) . "), ' ', '') AND enabled = 1";
        try{
            $db->setQuery($query);
            $stateid = $db->loadResult();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        return $stateid;
    }

    function storeState($data) { // new function coded
        $data = getJSJobsPHPFunctionsClass()->sanitizeData($data);  // Sanitize entire array to string
        $row = $this->getTable('state');
        $db = $this->getDBO();

        if (empty($data))
            return false;
        if (!isset($data['countryid']))
            return false;

        if (!$row->bind($data)) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            echo $row->getError();
            return false;
        }
        return true;
    }

}
?>


