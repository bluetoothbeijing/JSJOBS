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

class JSJobsViewSubcategory extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formsubcategory') {          // categories
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];


            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            $session = Factory::getApplication()->getSession();
            $categoryid = $session->get('sub_categoryid');
            $subcategory = $this->getJSModel('subcategory')->getSubCategorybyId($c_id, $categoryid);

            if (isset($subcategory->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Sub Category') . ': <small><small>[ ' . $text . ' ]</small></small>');
            $this->subcategory = $subcategory;
            $this->categoryid = $categoryid;
            ToolbarHelper::save('subcategory.savesubcategory');
            if ($isNew)
                ToolbarHelper::cancel('subcategory.cancelsubcategories');
            else
                ToolbarHelper::cancel('subcategory.cancelsubcategories', 'Close');
        }elseif ($layoutName == 'subcategories') {        //sub categories
            $categoryid = Factory::getApplication()->input->get('cd', '');
            $session = Factory::getApplication()->getSession();
            $session->set('sub_categoryid', $categoryid);
            $form = 'com_jsjobs.category.list.';
            $searchname = $mainframe->getUserStateFromRequest($form . 'searchname', 'searchname', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');            
            $result = $this->getJSModel('subcategory')->getSubCategories($categoryid, $searchname, $searchstatus, $limitstart, $limit);
            ToolbarHelper::title(Text::_('Sub Categories') . ' [' . $result[2]->cat_title . ']');
            ToolbarHelper::addNew('subcategory.editsubcategories');
            ToolbarHelper::editList('subcategory.editsubcategories');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'subcategory.removesubcategory');
            $items = $result[0];
            $total = $result[1];
            $this->lists=$result[3];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
            $this->items = $items;
        }
//        layout end

        $this->config = $config;
        //$this->application = $application;
        if(isset($theme)) $this->theme = $theme;
        if(isset($option)) $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
