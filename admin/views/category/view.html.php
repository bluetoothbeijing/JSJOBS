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

class JSJobsViewCategory extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php'; 
        if ($layoutName == 'categories') {        //categories
            ToolbarHelper::title(Text::_('Categories'));
            ToolbarHelper::addNew('category.add');
            ToolbarHelper::editList('category.edit');
            ToolbarHelper::cancel('category.cancel');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'category.deletecategoryandsubcategory', Text::_('Delete Cat').' & '.Text::_('Sub-Cat'));
            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');
            
            $form = 'com_jsjobs.category.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');
            $result = $this->getJSModel('category')->getAllCategories($searchname, $searchstatus, $sortby, $js_sortby , $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->lists = $lists;
            $this->js_sortby = $js_sortby;
            $this->sort = $sortby;
        }elseif ($layoutName == 'formcategory') {
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0){
                $application = $this->getJSModel('category')->getCategorybyId($c_id);
                if(isset($application)) $this->application = $application;
            }
            if (isset($application->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Category') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::save('category.savecategory');
            if ($isNew)
                ToolbarHelper::cancel('category.cancel');
            else
                ToolbarHelper::cancel('category.cancel', 'Close');
        }
// layout end
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
