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

class JSJobsViewPaymenthistory extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'assignpackage') {       // users - change role
            ToolbarHelper::title(Text::_('Assign Package'));
        } elseif ($layoutName == 'employerpaymentdetails') {          // employer package info
            ToolbarHelper::cancel('paymenthistory.cancelemployerpaymenthistory');
            $paymentid = Factory::getApplication()->input->get('pk','');
            $session->set('js_paymentid',$paymentid);
            ToolbarHelper::title(Text::_('Payment History Details'));
            $result = $this->getJSModel('paymenthistory')->getEmployerPaymentHistorybyId($paymentid);
            $items = $result[0];
        } elseif ($layoutName == 'employerpaymenthistory') {
            $packagefor = 1;
            ToolbarHelper::title(Text::_('Employer Payment History'));
            ToolbarHelper::addNew('paymenthistory.edit');
            $form = 'com_jsjobs.jobs.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchprice = $mainframe->getUserStateFromRequest($form . 'searchprice', 'searchprice', '', 'string');
            $searchpaymentstatus = $mainframe->getUserStateFromRequest($form . 'searchpaymentstatus', 'searchpaymentstatus', '', 'string');
            $searchempname = $mainframe->getUserStateFromRequest($form . 'searchempname', 'searchempname', '', 'string');
            $searchdatestart = $mainframe->getUserStateFromRequest($form . 'searchdatestart', 'searchdatestart', '', 'string');
            $searchdateend = $mainframe->getUserStateFromRequest($form . 'searchdateend', 'searchdateend', '', 'string');

            $result = $this->getJSModel('paymenthistory')->getEmployerPaymentHistory($searchtitle, $searchprice, $searchpaymentstatus, $searchempname, $searchdatestart, $searchdateend, $packagefor, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            $this->lists = $lists;
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        } elseif ($layoutName == 'jobseekerpaymentdetails') {          // employer package info
            $packageid = Factory::getApplication()->input->get('pk','');
            $session->set('js_packageid',$packageid);
            ToolbarHelper::title(Text::_('Payment History Details'));
            ToolbarHelper::cancel('paymenthistory.canceljobseekerpaymenthistory');
            $result = $this->getJSModel('paymenthistory')->getJobseekerPaymentHistorybyId($packageid);
            $items = $result[0];
        } elseif ($layoutName == 'jobseekerpaymenthistory') {        //employer packages
            ToolbarHelper::title(Text::_('Job Seeker Payment History'));
            ToolbarHelper::addNew('paymenthistory.edit');
            $form = 'com_jsjobs.jobs.list.';
            $packagefor = 2;

            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchprice = $mainframe->getUserStateFromRequest($form . 'searchprice', 'searchprice', '', 'string');
            $searchpaymentstatus = $mainframe->getUserStateFromRequest($form . 'searchpaymentstatus', 'searchpaymentstatus', '', 'string');
            $searchempname = $mainframe->getUserStateFromRequest($form . 'searchempname', 'searchempname', '', 'string');
            $searchdatestart = $mainframe->getUserStateFromRequest($form . 'searchdatestart', 'searchdatestart', '', 'string');
            $searchdateend = $mainframe->getUserStateFromRequest($form . 'searchdateend', 'searchdateend', '', 'string');

            $result = $this->getJSModel('paymenthistory')->getJobseekerPaymentHistory($searchtitle, $searchprice, $searchpaymentstatus, $searchempname, $searchdatestart, $searchdateend, $packagefor, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            $this->lists = $lists;
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }elseif ($layoutName == 'package_paymentreport') {
            $packageid = Factory::getApplication()->input->get('pk');
            if ($packageid)
                $session->set('pk',$packageid);
            else
                $packageid = $session->get('pk');
            $paymentfor = Factory::getApplication()->input->get('pf');
            if ($paymentfor)
                $session->set('pf',$paymentfor);
            else
                $paymentfor = $session->get('pf');
            $form = 'com_jsjobs.jobs.list.';
            $searchpaymentstatus = $mainframe->getUserStateFromRequest($form . 'searchpaymentstatus', 'searchpaymentstatus', '', 'string');

            $searchstartdate = $mainframe->getUserStateFromRequest($form . 'searchstartdate', 'searchstartdate', '', 'string');
            $searchenddate = $mainframe->getUserStateFromRequest($form . 'searchenddate', 'searchenddate', '', 'string');
            $result = $this->getJSModel('paymenthistory')->getPackagePaymentReport($packageid, $paymentfor, $searchpaymentstatus, $searchstartdate, $searchenddate, $limitstart, $limit);
            ToolbarHelper::title($result[0][0]->packagetitle . ' ' . Text::_('Report'));
            ToolbarHelper::cancel('paymenthistory.cancel');
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            $this->lists = $lists;
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }elseif ($layoutName == 'payment_report') {
            ToolbarHelper::title(Text::_('Payment Report'));
            $form = 'com_jsjobs.jobs.list.';
            $buyername = $mainframe->getUserStateFromRequest($form . 'buyername', 'buyername', '', 'string');
            $paymentfor = $mainframe->getUserStateFromRequest($form . 'paymentfor', 'paymentfor', '', 'string');
            $searchpaymentstatus = $mainframe->getUserStateFromRequest($form . 'searchpaymentstatus', 'searchpaymentstatus', '', 'string');
            $searchstartdate = $mainframe->getUserStateFromRequest($form . 'prsearchstartdate', 'prsearchstartdate', '', 'string');
            $searchenddate = $mainframe->getUserStateFromRequest($form . 'prsearchenddate', 'prsearchenddate', '', 'string');
            $result = $this->getJSModel('paymenthistory')->getPaymentReport($buyername, $paymentfor, $searchpaymentstatus, $searchstartdate, $searchenddate, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            $this->lists = $lists;
            $this->paymentfor = $result[3];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }
//        layout end

        $this->config = $config;
        //$this->application = $application;
        if(isset($items)) $this->items = $items;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;
        parent::display($tpl);
    }

}

?>
