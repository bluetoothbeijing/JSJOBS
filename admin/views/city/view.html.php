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

class JSJobsViewCity extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        $session = Factory::getApplication()->getSession();
        if ($layoutName == 'formcity') {          // cities
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            $var = $session->get('js_countrycode');
            if(!empty($var))
                $countrycode = $var;
            else
                $countrycode = null;
            $var = $session->get('js_countryid');
            if(!empty($var))
                $countryid = $var;
            else
                $countryid = null;
            $var = $session->get('js_statecode');
            if(!empty($var))
                $statecode = $var;
            else
                $statecode = null;
            $var = $session->get('js_stateid');
            if(!empty($var))
                $stateid = $var;
            else
                $stateid = null;

            if (!$countryid)
                $countryid = $session->get('countryid');
            if (!$stateid)
                $stateid = $session->get('stateid');

            $result = $this->getJSModel('city')->getCitybyId($c_id, $countryid, $stateid);
            $city = $result[0];
            if (isset($city->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('City') . ': <small><small>[ ' . $text . ' ]</small></small>');
            $this->city = $city;
            $this->countrycode = $countrycode;
            $this->countryid = $countryid;
            $this->statecode = $statecode;
            $this->stateid = $stateid;
            if (isset($result[1]))
                $this->list = $result[1];
            ToolbarHelper::save('city.savecity');
            if ($isNew)
                ToolbarHelper::cancel('city.cancel');
            else
                ToolbarHelper::cancel('city.cancel', 'Close');
        }elseif ($layoutName == 'cities') {          // cities
            $stateid = Factory::getApplication()->input->get('sd');
            $countryid = Factory::getApplication()->input->get('ct');
            $session = Factory::getApplication()->getSession();
            $session->set('countryid', $countryid);
            $session->set('stateid', $stateid);

            ToolbarHelper::title(Text::_('Cities'));

            $form = 'com_jsjobs.city.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');

            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');



            $result = $this->getJSModel('city')->getAllStatesCities($searchname, $searchstatus, $stateid, $countryid,  $sortby, $js_sortby, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            $this->sd = $stateid;
            $this->ct = $countryid;

            $this->js_sortby = $js_sortby;
            $this->sort = $sortby;
            

            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            ToolbarHelper::addNew('city.editjobcity');
            ToolbarHelper::editList('city.editjobcity');
            ToolbarHelper::publishList('city.publishcities');
            ToolbarHelper::unpublishList('city.unpublishcities');
            ToolbarHelper::deleteList(Text::_("Are You Sure?"), 'city.deletecity');
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

    function getSortArg($sort) {
        if ($sort == 'asc')
            return "desc";
        else
            return "asc";
    }


}

?>
