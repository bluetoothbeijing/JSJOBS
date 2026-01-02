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

class JSJobsModelCountries extends JSModel {

    var $_uid = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_countries = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getJobsCountry($showonlycountryhavejobs, $theme, $noofrecord = 20) {
        $db = $this->getDBO();
        $dateformat = $this->getJSModel('configurations')->getConfigValue('date_format');
        $this->getJSModel('common')->setTheme();
        $havingquery = '';
        if ($showonlycountryhavejobs == 1) {
            $havingquery = " HAVING totaljobsbycountry > 0 ";
        }

        $countryid = "country.id AS countryid,";
        $query = "SELECT $countryid country.name AS countryname,COUNT(DISTINCT job.id) AS totaljobsbycountry
                    FROM `#__js_job_countries` AS country
                    LEFT JOIN `#__js_job_cities` AS city ON country.id = city.countryid 
                    LEFT JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `#__js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing>=CURDATE() )
                    WHERE country.enabled = 1 
                    GROUP BY countryname $havingquery ORDER BY totaljobsbycountry DESC, countryname ASC ";
        $db->setQuery($query, 0, $noofrecord);
        $result[0] = $db->loadObjectList();
        $result[2] = $dateformat;
        return $result;
    }

    function getCountries($title) {
        if (!$this->_countries) {
            $db = Factory::getDBO();
            $query = "SELECT id,name FROM `#__js_job_countries` WHERE enabled = 1";
            $query.=" ORDER BY name ASC ";
            try{
                $db->setQuery($query);
                $rows = $db->loadObjectList();
            }catch (RuntimeException $e){
                echo $db->stderr();
                return false;
            }
            $this->_countries = $rows;
        }
        $countries = array();
        if ($title)
            $countries[] = array('value' => Text::_(''), 'text' => $title);
        foreach ($this->_countries as $row) {
            $countries[] = array('value' => $row->id, 'text' => Text::_($row->name));
        }
        return $countries;
    }

    function getCountryIdByName($name) { // new function coded
        $db = Factory::getDBO();
        $query = "SELECT id FROM `#__js_job_countries` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER(" . $db->Quote($name) . "), ' ', '') AND enabled = 1";
        try{
            $db->setQuery($query);
            $countryid = $db->loadResult();
        }catch (RuntimeException $e){
            echo $db->stderr();
            return false;
        }
        return $countryid;
    }
    function getCurrencyComboFORMP() {
        $db = Factory::getDBO();
        $query = "SELECT id, symbol FROM `#__js_job_currencies` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $currency = array();
        $currency[] = array('value' => Text::_(''), 'text' => Text::_('Select Currency'));
        foreach ($rows as $row) {
            $currency[] = array('value' => $row->id, 'text' => $row->symbol);
        }
        $currencycombo = HTMLHelper::_('select.genericList', $currency, 'currencyid', 'class="inputbox" ' . 'style="width:40%;"', 'value', 'text', '');
        return $currencycombo;
    }


    function getCountryNameById($country_id) {
        if (is_numeric($country_id) == false)
            return false;
        $country_name = '';
        $db = Factory::getDBO();
        $query = "SELECT name AS title FROM `#__js_job_countries` WHERE id = ".$country_id;
        $db->setQuery($query);
        $country_object = $db->loadObject();
        if(!empty($country_object)){
            $country_name = $country_object->title;
        }
        //echo var_dump($country_name);die('country_model 22114');
        return $country_name;
    }
	
}
?>
