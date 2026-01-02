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
use Joomla\CMS\Language\Text;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewEmployer extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'controlpanel') {
            $emcontrolpanel = $this->getJSModel('configurations')->getConfigByFor('emcontrolpanel');
            if ($uid) {
                $packagedetail = $this->getJSModel('user')->getUserPackageDetailByUid($uid);
                $this->packagedetail = $packagedetail;
            }
            $this->emcontrolpanel = $emcontrolpanel;
            if(Factory::getApplication()->getTemplate() == 'jobi'){
                $cp_data = $this->getJSModel('employer')->getControlPanelData();
                $this->cp_data = $cp_data;

                $mycompanies = $this->getJSModel('company')->getMyCompanies($uid,5,0);
                $this->cp_mycompanies=$mycompanies;

                $myjobs = $this->getJSModel('job')->getMyJobsForEmployerCP($uid);
                $this->cp_myjobs=$myjobs;
            }
            $cp_data = $this->getJSModel('employer')->getControlPanelData();
                $this->cp_data = $cp_data;

        } elseif ($layout == 'my_stats') {        // my stats
            $page_title .= ' - ' . Text::_('My Stats');
            $mystats_allowed = $this->getJSModel('permissions')->checkPermissionsFor("EMPLOYER_PURCHASE_HISTORY");
            if ($mystats_allowed == VALIDATE) {
                $result = $this->getJSModel('employer')->getMyStats_Employer($uid);
                $this->companiesallow = $result[0];
                $this->totalcompanies = $result[1];
                $this->jobsallow = $result[2];
                $this->totaljobs = $result[3];
                $this->publishedjob = $result[14];
                $this->expiredjob = $result[15];
                if (isset($result[12])) {
                    $this->package = $result[12];
                    $this->packagedetail = $result[13];
                }
                $this->ispackagerequired = $result[20];
                $this->goldcompaniesexpire = $result[21];
                $this->featurescompaniesexpire = $result[22];
            }
            $this->mystats_allowed = $mystats_allowed;
        }
        require_once('employer_breadcrumbs.php');
        $document->setTitle($page_title);
        $this->userrole = $userrole;
        $this->config = $config;
        $this->option = $option;
        $this->params = $params;
        $this->viewtype = $viewtype;
        $this->employerlinks = $employerlinks;
        $this->jobseekerlinks = $jobseekerlinks;
        $this->uid = $uid;
        //$this->id = $id; may no need it
        $this->Itemid = $itemid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
