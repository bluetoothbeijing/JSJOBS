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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewJsjobs extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        if ($layoutName == 'controlpanel') {
            ToolbarHelper::title('JS Jobs');
            $ck = $this->getJSModel('configuration')->getCheckCronKey();
            if ($ck == false) {
                $this->getJSModel('configuration')->genearateCronKey();
            }
            $ck = $this->getJSModel('configuration')->getCronKey(md5(date('Y-m-d')));
            $this->ck = $ck;
            $jobs_cp_data = $this->getJSModel('jsjobs')->getTodayStats();
            $this->jobs_cp_data = $jobs_cp_data;
            if(isset($topjobs)) $this->topjobs = $topjobs;
            $this->getJSModel('jsjobs')->removeOldSqlFiles();
        } elseif ($layoutName == 'info') {
            ToolbarHelper::title(Text::_('Information'));
        } elseif ($layoutName == 'translation') {
            ToolbarHelper::title(Text::_('Translations'));
        } elseif ($layoutName == 'updates') {          // roles
            ToolbarHelper::title(Text::_('JS Jobs').' '.Text::_('Update'));
            $configur = $this->getJSModel('configuration')->getConfigur();
            $this->configur = $configur;
            $count_config = $this->getJSModel('configuration')->getCountConfig();
            $this->count_config = $count_config;
        }if ($layoutName == 'stepone') { //Installation
            $array = explode('.', phpversion());
            $phpversion = $array[0] . '.' . $array[1];
            $curlexist = function_exists('curl_version');
            //$curlversion = curl_version()['version'];
            if (extension_loaded('gd') && function_exists('gd_info')) {
                $gd_lib = 1;
            } else {
                $gd_lib = 0;
            }
            $zip_lib = 0;
            if (file_exists('components/com_jsjobs/include/lib/pclzip.lib.php')) {
                $zip_lib = 1;
            }
            $this->phpversion = $phpversion;
            //$this->curlversion = $curlversion;
            $this->gd_lib = $gd_lib;
            $this->zip_lib = $zip_lib;
            $this->curlexist = $curlexist;

            $returnvalue = $this->getJSModel('jsjobs')->getStepTwoValidate();
            $this->result = $returnvalue;

            $versioncode = $this->getJSModel('jsjobs')->getConfigByConfigName('version');
            $this->versioncode = $versioncode;
            $versiontype = $this->getJSModel('jsjobs')->getConfigByConfigName('versiontype');
            $this->versiontype = $versiontype;
            $count_config = $this->getJSModel('jsjobs')->getConfigCount();
            $this->count_config = $count_config;
            
            ToolbarHelper :: title(Text :: _('INSTALLATION'));
        } elseif ($layoutName == 'steptwo') {
            $returnvalue = $this->getJSModel('jsjobs')->getStepTwoValidate();
            $this->result = $returnvalue;
            ToolbarHelper :: title(Text :: _('INSTALLATION'));
        } elseif ($layoutName == 'stepthree') {
            $versioncode = $this->getJSModel('jsjobs')->getConfigByConfigName('version');
            $this->versioncode = $versioncode;
            $versiontype = $this->getJSModel('jsjobs')->getConfigByConfigName('versiontype');
            $this->versiontype = $versiontype;
            $count_config = $this->getJSModel('jsjobs')->getConfigCount();
            $this->count_config = $count_config;
            ToolbarHelper :: title(Text :: _('INSTALLATION'));
        }

//        layout end
        $this->config = $config;
        if(isset($application)) //$this->application = $application;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
