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

class JSJobsViewFieldordering extends JSView { 

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        //layout start
        if ($layoutName == 'fieldsordering') {          // field ordering
            $fieldfor = Factory::getApplication()->input->get('ff', 0);
            $session = Factory::getApplication()->getSession();
            $session->set('fieldfor', $fieldfor);
            $fieldfor = $session->get('fieldfor');

            ToolbarHelper::addNew('fieldordering.editjobuserfield');
            //ToolbarHelper::editList('fieldordering.editjobuserfield');
            
            ToolbarHelper::publishlist('fieldordering.fieldpublished');
            ToolbarHelper::unpublishlist('fieldordering.fieldunpublished');
            ToolbarHelper::custom('fieldordering.visitorfieldpublished', 'publish.png', '', 'Visitor Publish', true);
            ToolbarHelper::custom('fieldordering.visitorfieldunpublished', 'delete.png', '', 'Visitor Unpublish', true);
            ToolbarHelper::custom('fieldordering.fieldrequired', 'publish.png', '', 'Required', true);
            ToolbarHelper::custom('fieldordering.fieldnotrequired', 'delete.png', '', 'Not Required', true);

            if ($fieldfor)
                $session->set('fford',$fieldfor);
            else
                $fieldfor = $session->get('fford');

            if ($fieldfor == 11 || $fieldfor == 12 || $fieldfor == 13)
                ToolbarHelper::title(Text::_('Visitor Fields'));
            else
                ToolbarHelper::title(Text::_('Fields'));
            $form = 'com_jsjobs.fieldordering.list.';
            $fieldtitle = $mainframe->getUserStateFromRequest($form . 'fieldtitle', 'fieldtitle', '', 'string');
            $userpublish = $mainframe->getUserStateFromRequest($form . 'userpublish', 'userpublish', '', 'string');            
            $visitorpublish = $mainframe->getUserStateFromRequest($form . 'visitorpublish', 'visitorpublish', '', 'string');            
            $fieldrequired = $mainframe->getUserStateFromRequest($form . 'fieldrequired', 'fieldrequired', '', 'string');            

            $result = $this->getJSModel('fieldordering')->getFieldsOrdering($fieldfor, $fieldtitle, $userpublish , $visitorpublish , $fieldrequired, $limitstart, $limit); // 1 for company
            $items = $result[0];
            $total = $result[1];
            $this->lists = $result[2];
            if ($total <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($total, $limitstart, $limit);
            $this->pagination = $pagination;
        
        }elseif($layoutName == 'formuserfield'){
            $c_id = "";
            $cid = Factory::getApplication()->input->get('cid','');
            if(is_array($cid)) $c_id = $cid[0];

            if ($c_id == '') {
                $cids = Factory::getApplication()->input->get('cid', array(0), 'post', 'array');
                $c_id = $cids[0];
            }

            $session = Factory::getApplication()->getSession();
            $fieldfor = $session->get('fieldfor');

            if (is_numeric($c_id) == true AND $c_id != 0)
                $application = $this->getJSModel('fieldordering')->getUserFieldbyId($c_id , $fieldfor);
            if (isset($application) && (!empty($application)))
                $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Job UserField') . ': <small><small>[ ' . $text . ' ]</small></small>');
            ToolbarHelper::apply('fieldordering.savejobuserfieldsave', 'Save');
            ToolbarHelper::save2new('fieldordering.savejobuserfieldandnew');
            ToolbarHelper::save('fieldordering.savejobuserfield');
            if ($isNew)
                ToolbarHelper::cancel('fieldordering.cancel');
            else
                ToolbarHelper::cancel('fieldordering.cancel', 'Close');
            $this->ff = $fieldfor;
            if(isset($application)) $this->application = $application;
            $articles = $this->getJSModel('jsjobs')->getAllJoomlaArticleContentList();
            $this->articles = $articles;
        
        }elseif($layoutName == 'searchfields'){

            $fieldfor = Factory::getApplication()->input->get('ff', 0);
            $session = Factory::getApplication()->getSession();
            $session->set('fieldfor', $fieldfor);
            $fieldfor = $session->get('fieldfor');

            $form = 'com_jsjobs.fieldordering.list.';
            $search = $mainframe->getUserStateFromRequest($form . 'search', 'search', '', 'int');
            
            $filters = array('search'=>$search);
            $result = $this->getJSModel('fieldordering')->getSearchFieldsOrdering($fieldfor,$filters);
            $items = $result[0];

            $this->lists = $result[1];
        }
//        layout end

        $this->config = $config;
        $this->ff = $fieldfor;
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
