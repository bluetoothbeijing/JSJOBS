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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewJobsharing extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'jobshare') {        //resume search
            ToolbarHelper::title(Text::_('Job Sharing Service'));
            $session = Factory::getApplication()->getSession();
            $synchronizedata = $session->get('synchronizedatamessage');
            $session->clear('synchronizedatamessage');
            $empty = 'empty';
            $this->result = $empty;
            if ($synchronizedata != "") {
                $this->result = $synchronizedata;
            }
        } elseif ($layoutName == 'jobsharelog') {        //resume search
            ToolbarHelper::title(Text::_('Job Share Log'));
            $searchuid = $mainframe->getUserStateFromRequest($option . 'searchuid', 'searchuid', '', 'string');
            $searchusername = $mainframe->getUserStateFromRequest($option . 'searchusername', 'searchusername', '', 'string');
            $searchrefnumber = $mainframe->getUserStateFromRequest($option . 'searchrefnumber', 'searchrefnumber', '', 'string');
            $searchstartdate = $mainframe->getUserStateFromRequest($option . 'searchstartdate', 'searchstartdate', '', 'string');
            $searchenddate = $mainframe->getUserStateFromRequest($option . 'searchenddate', 'searchenddate', '', 'string');

            $result = $this->getJSModel('sharingservicelog')->getAllSharingServiceLog($searchuid, $searchusername, $searchrefnumber, $searchstartdate, $searchenddate, $limitstart, $limit);
            $this->servicelog = $result[0];
            $this->lists = $result[2];
            $total = $result[1];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }
//        layout end

        $this->config = $config;
        //$this->application = $application;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
