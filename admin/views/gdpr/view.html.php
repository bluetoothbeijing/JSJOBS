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

class JSJobsViewGdpr extends JSView
{
	function display($tpl = null){
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        global $sorton,$sortorder;
        if($layoutName == 'erasedatarequests'){
            ToolbarHelper::title(Text::_('User Erase Data Request'));
            $searchemail = $mainframe->getUserStateFromRequest($option . 'filter_email', 'filter_email','','string');
            $jsresetbutton = Factory::getApplication()->input->get('jsresetbutton');
            if($jsresetbutton == 1){
                $limitstart = 0;
            }
            $result = $this->getJSModel('gdpr')->getEraseDataRequests($searchemail,$limitstart,$limit);
            $this->result = $result[0];
            $this->searchemail = $searchemail;
            $total = $result[1];
            $pagination = new Pagination($total, $limitstart, $limit);
        }elseif($layoutName == 'addgdprfield'){
            $c_id = Factory::getApplication()->input->get('cid','');
            ToolbarHelper::save('gdpr.savegdprfield');
            $articles = $this->getJSModel('jsjobs')->getAllJoomlaArticleContentList();
            $this->articles = $articles;
            
            $result = $this->getJSModel('fieldordering')->getUserFieldbyId($c_id , 14);
            if(isset($result['userfieldparams'])) $this->userfieldparams = $result['userfieldparams'];
            if(isset($result['userfield'])) $this->userfield = $result['userfield'];
            
        }elseif($layoutName == 'gdprfields'){
            ToolbarHelper::addNew('gdpr.add');
            ToolbarHelper::title(Text::_('GDPR Fields'));
            $result = $this->getJSModel('gdpr')->getGDPRFeilds();
            $this->gdprfiels = $result;
        }
        $this->config = $config;
        if(isset($pagination)) $this->pagination = $pagination;
        $this->option = $option;
        parent::display($tpl);
	}
}
?>
