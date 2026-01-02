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

class JSJobsViewJobSeeker extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';
        if ($layout == 'controlpanel') {
            $jscontrolpanel = $this->getJSModel('configurations')->getConfigByFor('jscontrolpanel');
            if ($uid) {
                $packagedetail = $this->getJSModel('user')->getUserPackageDetailByUid($uid);
                $this->packagedetail = $packagedetail;
            }
            $this->jscontrolpanel = $jscontrolpanel;
            if(Factory::getApplication()->getTemplate() == 'jobi'){
                $cp_data = $this->getJSModel('jobseeker')->getControlPanelData();
                $this->cp_data = $cp_data;
            }
            $cp_data = $this->getJSModel('jobseeker')->getControlPanelData();
            $this->cp_data = $cp_data;
        } elseif ($layout == 'my_stats') {        // my stats
            $page_title .= ' - ' . Text::_('My Stats');
            $mystats_allowed = $this->getJSModel('permissions')->checkPermissionsFor("JOBSEEKER_STATS");
            if ($mystats_allowed == VALIDATE) {
                $result = $this->getJSModel('jobseeker')->getMyStats_JobSeeker($uid);
                $this->resumeallow = $result[0];
                $this->totalresume = $result[1];
                $this->coverlettersallow = $result[6];
                $this->totalcoverletters = $result[7];
                if (isset($result[8])) {
                    $this->package = $result[8];
                    $this->packagedetail = $result[9];
                }
                $this->ispackagerequired = $result[10];
            }
            $this->mystats_allowed = $mystats_allowed;
        }
        require_once('jobseeker_breadcrumbs.php');

        $document->setTitle($page_title);

        $this->userrole = $userrole;
        $this->config = $config;
        $this->option = $option;
        $this->params = $params;
        $this->viewtype = $viewtype;
        $this->employerlinks = $employerlinks;
        $this->jobseekerlinks = $jobseekerlinks;
        $this->uid = $uid;
        //$this->id = $id;
        $this->Itemid = $itemid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
