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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewCoverLetter extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'formcoverletter') {            // form cover letter
            $page_title .= ' - ' . Text::_('Cover Letter Form');
            $jinput = Factory::getApplication()->input;
            $cd = $jinput->get('cl');
            if (isset($cd))
                $letterid = $cd;
            else
                $letterid = null;
            $letterid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('cl', ''));
            $result = $this->getJSModel('coverletter')->getCoverLetterbyId($letterid, $uid);
            if(isset($result[0])) $this->coverletter = $result[0];
            $this->canaddnewcoverletter = $result[4];
            HTMLHelper::_('behavior.formvalidator');
        } elseif ($layout == 'mycoverletters') {            // my cover letters
            $page_title .= ' - ' . Text::_('My Cover Letters');
            $mycoverletter_allowed = $this->getJSModel('permissions')->checkPermissionsFor("MY_COVER_LETTER");
            if ($mycoverletter_allowed == VALIDATE) {
                $result = $this->getJSModel('coverletter')->getMyCoverLettersbyUid($uid, $limit, $limitstart);
                $this->coverletters = $result[0];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
            }
            $this->mycoverletter_allowed = $mycoverletter_allowed;
        }elseif ($layout == 'view_coverletter') {            // view cover letter
            $page_title .= ' - ' . Text::_('View Cover Letter');
            $letterid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('cl', ''));
            $result = $this->getJSModel('coverletter')->getCoverLetterbyId($letterid, null);
            $this->coverletter = $result[0];
            $nav = Factory::getApplication()->input->get('nav', '');
            $this->nav = $nav;
            $jobaliasid = Factory::getApplication()->input->get('bd', '');
            $this->jobaliasid = $jobaliasid;
            $resumealiasid = Factory::getApplication()->input->get('rd', '');
            $this->resumealiasid = $resumealiasid;
        }
        require_once('coverletter_breadcrumbs.php');
        $document->setTitle($page_title);
        $this->userrole = $userrole;
        $this->config = $config;
        $this->option = $option;
        $this->params = $params;
        $this->viewtype = $viewtype;
        $this->employerlinks = $employerlinks;
        $this->jobseekerlinks = $jobseekerlinks;
        $this->uid = $uid;
        //$this->id = $id;
        $this->Itemid = $itemid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
