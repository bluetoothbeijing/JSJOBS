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

class JSJobsViewCustomfield extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'formuserfield') {      // user fields
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true)
                $result = $this->getJSModel('customfield')->getUserFieldbyId($c_id);
            $fieldfor = Factory::getApplication()->input->get('fieldfor');
            if (empty($fieldfor))
                $fieldfor = Factory::getApplication()->input->get('ff');
            if (empty($fieldfor))
                $fieldfor = $session->get('ff');
            if ($fieldfor == 3)
                $section = $this->getJSModel('fieldordering')->getResumeSections($c_id);
            if (isset($section))
                $this->resumesection = $section;
            $this->userfield = $result[0];
            $this->fieldvalues = $result[1];
            $this->fieldfor = $fieldfor;
            $isNew = true;
            if (isset($result[0]->id))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('User Field') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::save('customfield.saveuserfield');
            if ($isNew)
                ToolbarHelper::cancel('customfield.cancel');
            else
                ToolbarHelper::cancel('customfield.cancel', 'Close');
        }elseif ($layoutName == 'userfields') {
            $ff = Factory::getApplication()->input->get('ff');
            $session->set('ff',$ff);
            ToolbarHelper::addNew('customfield.add');
            ToolbarHelper::editList('customfield.add');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'customfield.remove');
            ToolbarHelper::cancel('customfield.cancel');

            if ($ff == 11 || $ff == 12 || $ff == 13)
                ToolbarHelper::title(Text::_('Visitor User Fields'));
            else
                ToolbarHelper::title(Text::_('User Fields'));
            $form = 'com_jsjobs.customfield.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchtype = $mainframe->getUserStateFromRequest($form . 'searchtype', 'searchtype', '', 'string');
            $searchrequired = $mainframe->getUserStateFromRequest($form . 'searchrequired', 'searchrequired', '', 'string');

            $result = $this->getJSModel('customfield')->getUserFields($ff, $searchtitle, $searchtype , $searchrequired, $limitstart, $limit); // 1 for company
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->fieldfor = $ff;
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
