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
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelAddressdata extends JSModel {

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

    function loadAddressData() {
        Factory::getSession()->checkToken('post') or jexit(Text::_('INVALID_TOKEN'));
        jimport('joomla.filesystem.file');
        $db = Factory::getDBO();
        $data = Factory::getApplication()->input->post->getArray();
        $str = JPATH_BASE;
        $base = substr($str, 0, strlen($str) - 14); //remove administrator
        $returncode = 1;
        $loadaddressdata_file = Factory::getApplication()->input->files->get('loadaddressdata');
        $loadaddressdata_filename = File::makeSafe($loadaddressdata_file['name']);
        $loadaddressdata_tmpname = $loadaddressdatafile['tmp_name'];
        $loadaddressdata_size = $loadaddressdata_file['size'];

        if ($loadaddressdata_size > 0) {
            $file_name = $loadaddressdata_filename; // file name
            $file_tmp = $loadaddressdata_tmpname; // actual location
            $file_size = $loadaddressdata_size; // file size
            $file_type = strtolower(File::getExt($loadaddressdata_filename)); // mime type of file determined by php
            if (!empty($file_tmp)) { // only MS office and text file is accepted.
                $ext = $this->getJSModel('common')->getExtension($file_name);
                if (($ext != "zip") && ($ext != "sql"))
                    return 3; //file type mistmathc
            }
            $path = $base . '/components/com_jsjobs/data';
            if (!file_exists($path)) { // creating data directory
                $this->getJSModel('common')->makeDir($path);
            }
            $path = $base . '/components/com_jsjobs/data/temp';
            if (!file_exists($path)) { // creating temp directory
                $this->getJSModel('common')->makeDir($path);
            }
            $comp_filename = $path . '/' . $file_name;
            move_uploaded_file($file_tmp, $path . '/' . $file_name);
            if ($ext == 'zip') {
                require_once 'components/com_jsjobs/include/lib/pclzip.lib.php';
                $archive = new PclZip($comp_filename);
                $list = $archive->listContent();
                if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0) {
                    die("Error : " . $archive->errorInfo(true));
                }
                $comp_filename = $path . '/' . $list[0]['filename'];
            }
            $filestring = file_get_contents($comp_filename);
            $resultstates = strpos($filestring, '#__js_job_states');
            $resultcities = strpos($filestring, '#__js_job_cities');
            if (($resultstates) || ($resultcities)) {
                $queries = $db->splitSql($filestring);
                $queries = array_filter($queries);
                $queries = array_map('trim', $queries);
                $totalnumberofqueries = count($queries) - 1;
                $percentageperquery = round(100 / $totalnumberofqueries, 1);
                $perquery = 0;
                $option = Factory::getApplication()->input->get('datakept','');
                $fileowner = Factory::getApplication()->input->get('fileowner','');
                $datacontain = Factory::getApplication()->input->get('datacontain','');
                echo "<style type=\"text/css\">
                            div#progressbar{display:block;width:275px;height:20px;position:relative;padding:2px;border:1px solid #E0E1E0;}
                            span#backgroundtext{position:absolute;width:275px;height:20px;top:0px;left:0px;text-align:center;}
                            span#backgroundcolour{display:block;height:20px;background:#D8E8ED;width:1%;}
                            h1{color:1A5E80;}
                        </style>";
                echo str_pad('<html><h1>' . Text::_('Address Data Updating') . '</h1><div id="progressbar"><span id="backgroundtext">0% complete.</span><span id="backgroundcolour" style="width:1%;"></span></div></html>', 5120);
                echo str_pad(Text::_('Loading ...'), 5120) . "<br />\n";
                flush();
                ob_flush();

                if ($option == 1) {// kept data
                    $city_insert = 0;
                    $state_insert = 0;
                    echo str_pad(Text::_('Backup..'), 5120) . "<br />\n";
                    flush();
                    ob_flush();

                    if ($fileowner == 1) { // myfile
                    } elseif ($fileowner == 2) { // joomsky file
                        if ($datacontain == 1) { // states
                            $state_insert = 1;
                        } elseif ($datacontain == 2) { // cities
                            $city_insert = 1;
                        } elseif ($datacontain == 3) { // states and cities
                            $city_insert = 1;
                            $state_insert = 1;
                        }
                    }
                    if ($city_insert == 1) {
                        $drop_city = "DROP TABLE IF EXISTS `#__js_job_cities_new`";
                        $db->setQuery($drop_city);
                        $db->execute();
                        $create_cities = " CREATE TABLE `#__js_job_cities_new` (
                              `id` mediumint(6) NOT NULL AUTO_INCREMENT,
                              `cityName` varchar(70) DEFAULT NULL,
                              `name` varchar(60) DEFAULT NULL,
                              `stateid` smallint(8) DEFAULT NULL,
                              `countryid` smallint(9) DEFAULT NULL,
                              `isedit` tinyint(1) DEFAULT '0',
                              `enabled` tinyint(1) NOT NULL DEFAULT '0',
                              `serverid` int(11) DEFAULT NULL,
                              `latitude` int(11) DEFAULT NULL,
                              `longitude` int(11) DEFAULT NULL,
                              PRIMARY KEY (`id`),
                              KEY `countryid` (`countryid`),
                              KEY `stateid` (`stateid`),
                              FULLTEXT KEY `name` (`name`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_cities);
                        $db->execute();

                        $query = "INSERT INTO `#__js_job_cities_new`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                            FROM `#__js_job_cities` AS city";
                        $db->setQuery($query);
                        $db->execute();
                        $query = "TRUNCATE `#__js_job_cities`";
                        $db->setQuery($query);
                        $db->execute();
                    }
                    if ($state_insert == 1) {
                        $drop_state = "DROP TABLE IF EXISTS `#__js_job_states_new`";
                        $db->setQuery($drop_state);
                        $db->execute();
                        $create_state = "CREATE TABLE `#__js_job_states_new` (
                          `id` smallint(8) NOT NULL AUTO_INCREMENT,
                          `name` varchar(35) DEFAULT NULL,
                          `shortRegion` varchar(25) DEFAULT NULL,
                          `countryid` smallint(9) DEFAULT NULL,
                          `enabled` tinyint(1) NOT NULL DEFAULT '0',
                          `serverid` int(11) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `countryid` (`countryid`),
                          FULLTEXT KEY `name` (`name`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_state);
                        $db->execute();
                        $query = "INSERT INTO `#__js_job_states_new`(id,name,shortRegion,countryid,enabled,serverid)
                            SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
                            FROM `#__js_job_states` AS state
                            ";
                        $db->setQuery($query);
                        $db->execute();
                        $query = "TRUNCATE `#__js_job_states`";
                        $db->setQuery($query);
                        $db->execute();
                    }
                } elseif ($option == 2) {// Discard old data;
                    $discaed_city = 0;
                    $discaed_state = 0;
                    echo str_pad(Text::_('Delete..'), 5120) . "<br />\n";
                    flush();
                    ob_flush();
                    if ($fileowner == 1) { // myfile
                        $discaed_city = 1;
                        $discaed_state = 1;
                    } elseif ($fileowner == 2) { // joomsky file
                        if ($datacontain == 1) { // states
                            $discaed_state = 1;
                        } elseif ($datacontain == 2) { // cities
                            $discaed_city = 1;
                        } elseif ($datacontain == 3) { // states and cities
                            $discaed_city = 1;
                            $discaed_state = 1;
                        }
                    }
                    if ($discaed_city == 1) {
                        $drop_city = "DROP TABLE IF EXISTS `#__js_job_cities_new`";
                        $db->setQuery($drop_city);
                        $db->execute();
                        $create_cities = " CREATE TABLE `#__js_job_cities_new` (
                              `id` mediumint(6) NOT NULL AUTO_INCREMENT,
                              `cityName` varchar(70) DEFAULT NULL,
                              `name` varchar(60) DEFAULT NULL,
                              `stateid` smallint(8) DEFAULT NULL,
                              `countryid` smallint(9) DEFAULT NULL,
                              `isedit` tinyint(1) DEFAULT '0',
                              `enabled` tinyint(1) NOT NULL DEFAULT '0',
                              `serverid` int(11) DEFAULT NULL,
                              `latitude` int(11) DEFAULT NULL,
                              `longitude` int(11) DEFAULT NULL,
                              PRIMARY KEY (`id`),
                              KEY `countryid` (`countryid`),
                              KEY `stateid` (`stateid`),
                              FULLTEXT KEY `name` (`name`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_cities);
                        $db->execute();
                        $q = "TRUNCATE `#__js_job_cities`";
                        $db->setQuery($q);
                        $db->execute();
                    }
                    if ($discaed_state == 1) {
                        $drop_state = "DROP TABLE IF EXISTS `#__js_job_states_new`";
                        $db->setQuery($drop_state);
                        $db->execute();
                        $create_state = "CREATE TABLE `#__js_job_states_new` (
                          `id` smallint(8) NOT NULL AUTO_INCREMENT,
                          `name` varchar(35) DEFAULT NULL,
                          `shortRegion` varchar(25) DEFAULT NULL,
                          `countryid` smallint(9) DEFAULT NULL,
                          `enabled` tinyint(1) NOT NULL DEFAULT '0',
                          `serverid` int(11) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `countryid` (`countryid`),
                          FULLTEXT KEY `name` (`name`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_state);
                        $db->execute();
                        $q = "TRUNCATE `#__js_job_states`";
                        $db->setQuery($q);
                        $db->execute();
                    }
                }
                echo str_pad(Text::_('Import New Data'), 5120) . "<br />\n";
                flush();
                ob_flush();

                foreach ($queries AS $query) {
                    if (!empty($query)) {
                        $db->setQuery($query);
                        $db->execute();
                    }
                    $perquery += $percentageperquery;
                    //This div will show loading percents
                    echo str_pad('<script type="text/javascript">document.getElementById("backgroundcolour").style.width = "' . $perquery . '%";</script>', 50120);
                    echo str_pad('<script type="text/javascript">document.getElementById("backgroundtext").innerHTML = "' . $perquery . '% complete.";</script>', 50120);
                    flush();
                    ob_flush();
                }
                if ($option == 1) {// kept data
                    if ($city_insert == 1) {
                        $removeduplicationofcities = $this->correctCityData();
                        if ($removeduplicationofcities == 0)
                            return 0;
                        $q = "DROP TABLE `#__js_job_cities_new`";
                        $db->setQuery($q);
                        $db->execute();
                    }
                    if ($state_insert == 1) {
                        $removeduplicationofstates = $this->correctStateData();
                        if ($removeduplicationofstates == 0)
                            return 0;
                        $q = "DROP TABLE `#__js_job_states_new`";
                        $db->setQuery($q);
                        $db->execute();
                    }
                }elseif ($option == 2) { // discard data
                    if ($discaed_city == 1) {
                        $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                                    SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                                    FROM `#__js_job_cities_new` AS city ";
                        $db->setQuery($query);
                        $db->execute();
                        $query = "DROP TABLE `#__js_job_cities_new`";
                        $db->setQuery($query);
                        $db->execute();
                    }

                    if ($discaed_state == 1) {
                        $query = "INSERT INTO `#__js_job_states`(id,name,shortRegion,countryid,enabled,serverid)
                                    SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
                                    FROM `#__js_job_states_new` AS state ";
                        $db->setQuery($query);
                        $db->execute();
                        $query = "DROP TABLE `#__js_job_states_new`";
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
                $perquery = 100;
                //This div will show loading percents
                echo str_pad('<script type="text/javascript">document.getElementById("backgroundcolour").style.width = "' . $perquery . '%";</script>', 50120);
                echo str_pad('<script type="text/javascript">document.getElementById("backgroundtext").innerHTML = "' . $perquery . '% complete.";</script>', 50120);
                flush();
                ob_flush();

                echo str_pad(Text::_('Redirecting...'), 5120) . "<br />\n";
                flush();
                ob_flush();
                return 1;
            }
        }
        return 0; //return 0 if any error occured
    }

    function correctCityData() {
        $db = Factory::getDBO();
        $query = "SELECT country.id AS countryid FROM `#__js_job_countries` AS country ";
        $db->setQuery($query);
        $country_data = $db->loadObjectList();
        $query = "TRUNCATE `#__js_job_cities`";
        $db->setQuery($query);
        $db->execute();
        foreach ($country_data AS $d) {
            switch ($d->countryid) {
                case 1:// United States Country
                    $query = "SELECT state.id AS stateid FROM `#__js_job_states` AS state WHERE countryid=" . $d->countryid;
                    $db->setQuery($query);
                    $us_state_by_id = $db->loadObjectList();
                    if (is_array($us_state_by_id) AND ( !empty($us_state_by_id))) {
                        foreach ($us_state_by_id AS $sid) {
                            if ($sid->stateid) {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                                            FROM `#__js_job_cities_new` AS city WHERE stateid=" . $sid->stateid . " AND countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->execute())
                                    return 0;
                            }else {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                                            FROM `#__js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->execute())
                                    return 0;
                            }
                        }
                    }
                    break;
                case 2:
                    $query = "SELECT state.id AS stateid FROM `#__js_job_states` AS state WHERE countryid=" . $d->countryid;
                    $db->setQuery($query);
                    $ca_state_by_id = $db->loadObjectList();
                    if (is_array($ca_state_by_id) AND ( !empty($ca_state_by_id))) {
                        foreach ($ca_state_by_id AS $sid) {
                            if ($sid->stateid) {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                                            FROM `#__js_job_cities_new` AS city WHERE stateid=" . $sid->stateid . " AND countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->execute())
                                    return 0;
                            }else {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                                            FROM `#__js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->execute())
                                    return 0;
                            }
                        }
                    }
                    break;
                default:
                    $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid,latitude,longitude)
                                SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid,city.latitude,city.longitude 
                                FROM `#__js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";
                    $db->setQuery($query);
                    if (!$db->execute())
                        return 0;
                    break;
            }
        }
        return true;
    }

    function correctStateData() {
        $db = Factory::getDBO();
        $query = "SELECT country.id AS countryid FROM `#__js_job_countries` AS country ";
        $db->setQuery($query);
        $country_data = $db->loadObjectList();
        $query = "TRUNCATE  `#__js_job_states`";
        $db->setQuery($query);
        $db->execute();
        foreach ($country_data AS $d) {
            $query = "INSERT INTO `#__js_job_states`(id,name,shortRegion,countryid,enabled,serverid)
                    SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
                    FROM `#__js_job_states_new` AS state WHERE countryid=" . $d->countryid . " group by name ";
            $db->setQuery($query);
            if (!$db->execute())
                return 0;
        }
        return true;
    }

}

?>
