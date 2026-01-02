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

class JSJobsViewCurrency extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formcurrency') {
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            //if (is_numeric($c_id) == true)
            if (is_numeric($c_id) == true AND $c_id != 0)
                $currency = $this->getJSModel('currency')->getCurrencybyId($c_id);
            if(isset($currency)) $this->currency = $currency;
            if (isset($currency->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Currency') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('currency.savejobcurrencysave', 'Save');
            ToolbarHelper::save2new('currency.savejobcurrencyandnew');
            ToolbarHelper::save('currency.savejobcurrency');
            if ($isNew)
                ToolbarHelper::cancel('currency.cancel');
            else
                ToolbarHelper::cancel('currency.cancel', 'Close');
        }elseif ($layoutName == 'currency') {

            ToolbarHelper::title(Text::_('Currencies'));
            ToolbarHelper::addNew('currency.editjobcurrency');
            ToolbarHelper::editList('currency.editjobcurrency');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'currency.remove');
            $form = 'com_jsjobs.currency.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');            
            $searchcode = $mainframe->getUserStateFromRequest($form . 'searchcode', 'searchcode', '', 'string');            

            $result = $this->getJSModel('currency')->getAllCurrencies($searchtitle, $searchstatus, $searchcode, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
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
