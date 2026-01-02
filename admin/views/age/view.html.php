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

class JSJobsViewAge extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout statr
        if ($layoutName == 'ages') {        //job types
            ToolbarHelper::title(Text::_('Ages'));
            ToolbarHelper::addNew('age.editjobage');
            ToolbarHelper::editList('age.editjobage');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'age.remove');
            $form = 'com_jsjobs.age.list.';
            $searchtitle = $mainframe->getUserStateFromRequest($form . 'searchtitle', 'searchtitle', '', 'string');
            $searchstatus = $mainframe->getUserStateFromRequest($form . 'searchstatus', 'searchstatus', '', 'string');            
            $result = $this->getJSModel('age')->getAllAges($searchtitle, $searchstatus, $limitstart, $limit);
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        }elseif ($layoutName == 'formages') {          // jobtypes
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }
            if (is_numeric($c_id) == true AND $c_id != 0)
                $ages = $this->getJSModel('age')->getJobAgesbyId($c_id);
            if (isset($ages->id))
                $isNew = false;
            if(isset($ages)) $this->application = $ages;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Job Ages') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('age.savejobagesave', 'Save');
            ToolbarHelper::save2new('age.savejobageandnew');
            ToolbarHelper::save('age.savejobage');
            if ($isNew)
                ToolbarHelper::cancel('age.cancel');
            else
                ToolbarHelper::cancel('age.cancel', 'Close');
        }
//        layout end

        $this->config = $config;
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
