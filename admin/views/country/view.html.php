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

class JSJobsViewCountry extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formcountry') {          // countries
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0)
                $country = $this->getJSModel('country')->getCountrybyId($c_id);
            if (isset($country->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Country') . ': <small><small>[ ' . $text . ' ]</small></small>');
            if(isset($country)) $this->country = $country;
            ToolbarHelper::save('country.savecountry');
            if ($isNew)
                ToolbarHelper::cancel('country.cancel');
            else
                ToolbarHelper::cancel('country.cancel', 'Close');
        }elseif ($layoutName == 'countries') {          // countries
            ToolbarHelper::title(Text::_('Countries'));
            ToolbarHelper::addNew('country.editjobcountry');
            ToolbarHelper::editList('country.editjobcountry');
            ToolbarHelper::publishlist('country.publishcountries');
            ToolbarHelper::unpublishlist('country.unpublishcountries');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'country.deletecountry');
            $form = 'com_jsjobs.countries.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');
            $hasstates = $mainframe->getUserStateFromRequest($form . 'hasstates', 'hasstates');
            $hascities = $mainframe->getUserStateFromRequest($form . 'hascities', 'hascities');

            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');

            $result = $this->getJSModel('country')->getAllCountries($searchname, $searchstatus , $hasstates, $hascities, $sortby, $js_sortby, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            $this->js_sortby = $js_sortby;
            $this->sort = $sortby;
            
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }

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

    function getSortArg($sort) {
        if ($sort == 'asc')
            return "desc";
        else
            return "asc";
    }


}

?>
