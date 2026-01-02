<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     https://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Mar 20, 2020
 ^
 + Project:     JS Jobs
 ^
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewGdpr extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'adderasedatarequest') {        // add erase data request
            $page_title .= ' - ' . Text::_('Erase Data Request');
            $erase_data_allowed = $this->getJSModel('permissions')->checkPermissionsFor("ERASE_DATA_REQUEST");
            if ($erase_data_allowed == VALIDATE) {
                $user = Factory::getUser();
                $result = $this->getJSModel('gdpr')->getUserEraseDataRequest($uid);
                $this->erasedaatrequest = $result;
            }
            $this->erase_data_allowed = $erase_data_allowed;
        }elseif ($layout == 'adderasedatarequestjobseeker') {        // add erase data request
            $page_title .= ' - ' . Text::_('Erase Data Request');
            $erase_data_allowed = $this->getJSModel('permissions')->checkPermissionsFor("ERASE_DATA_REQUEST_JS");
            if ($erase_data_allowed == VALIDATE) {
                $user = Factory::getUser();
                $result = $this->getJSModel('gdpr')->getUserEraseDataRequest($uid);
                $this->erasedaatrequest = $result;
            }
            $this->erase_data_allowed = $erase_data_allowed;
        }
        require_once('gdpr_breadcrumbs.php');
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
