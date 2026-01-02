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

class JSJobsViewResume extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        if($layoutName == 'formresume') { //form resume
            require_once (JPATH_ROOT.'/components/com_jsjobs/models/resume.php');
            $resume_model = new JSJobsModelResume();
            
            $resumeid = Factory::getApplication()->input->get('cid');
            $resumeid = $resumeid[0];
            $fieldsordering = $this->getJSModel('fieldordering')->getFieldsOrderingforForm(3);
            $validresume = true;
            if(is_numeric($resumeid)){
                $validresume = $resume_model->checkResumeExists($resumeid);
            }
            $this->resume_model = $resume_model;
            $this->validresume = $validresume;
            $this->fieldsordering = $fieldsordering;
            $this->resumeid = $resumeid;
            $isadmin = 1;
            $this->isadmin = $isadmin;
            $callfrom = Factory::getApplication()->input->get('callfrom');
            $this->callfrom = $callfrom;
            if (isset($resumeid)) $isNew = false;
            $text = $isNew ? Text::_('Add') : Text::_('Edit');
            ToolbarHelper::title(Text::_('Resume') . ': <small><small>[ ' . $text . ' ]</small></small>');
        }elseif ($layoutName == 'appqueue') {  //app queue
            ToolbarHelper::title(Text::_('Resume Approval Queue'));
            $form = 'com_jsjobs.appqueue.list.';
            $datafor = 2;
            $resumetitle = $mainframe->getUserStateFromRequest($form.'resumetitle','resumetitle','','string');
            $resumename = $mainframe->getUserStateFromRequest($form.'resumename','resumename','','string');
            $resumecategory = $mainframe->getUserStateFromRequest($form.'resumecategory','resumecategory','','string');
            $resumetype = $mainframe->getUserStateFromRequest($form.'resumetype','resumetype','','string');
            $desiredsalary = $mainframe->getUserStateFromRequest($form.'desiredsalary','desiredsalary','','string');
            $location = $mainframe->getUserStateFromRequest($form.'location','location','','string');
            $dateto = $mainframe->getUserStateFromRequest($form.'dateto','dateto','','string');
            $datefrom = $mainframe->getUserStateFromRequest($form.'datefrom','datefrom','','string');
            $status = '';
            $isgfcombo = $mainframe->getUserStateFromRequest($form.'isgfcombo','isgfcombo','','string');
            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');

            $result = $this->getJSModel('resume')->getAllEmpApps($datafor, $resumetitle, $resumename, $resumecategory, $resumetype, $desiredsalary, $location, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit);
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
        }elseif ($layoutName == 'empapps') { //resumes
            ToolbarHelper::title(Text::_('Resume'));
            ToolbarHelper::editList('resume.edit');
            ToolbarHelper::deleteList(Text::_('Are You Sure?'), 'resume.remove');
            ToolbarHelper::cancel('resume.cancel');
            $form = 'com_jsjobs.empapps.list.';
            $datafor = 1;
            $resumetitle = $mainframe->getUserStateFromRequest($form.'resumetitle','resumetitle','','string');
            $resumename = $mainframe->getUserStateFromRequest($form.'resumename','resumename','','string');
            $resumecategory = $mainframe->getUserStateFromRequest($form.'resumecategory','resumecategory','','string');
            $resumetype = $mainframe->getUserStateFromRequest($form.'resumetype','resumetype','','string');
            $desiredsalary = $mainframe->getUserStateFromRequest($form.'desiredsalary','desiredsalary','','string');
            $location = $mainframe->getUserStateFromRequest($form.'location','location','','string');
            $dateto = $mainframe->getUserStateFromRequest($form.'dateto','dateto','','string');
            $datefrom = $mainframe->getUserStateFromRequest($form.'datefrom','datefrom','','string');
            $status = $mainframe->getUserStateFromRequest($form.'status','status','','string');
            $isgfcombo = $mainframe->getUserStateFromRequest($form.'isgfcombo','isgfcombo','','string');
            $sortby = Factory::getApplication()->input->get('sortby','asc');
            $my_click = Factory::getApplication()->input->get('my_click');
            if($my_click==1){
               $sortby = $this->getSortArg($sortby);
            }else{
                $sortby = $this->getJSModel('common')->checkSortByValue($sortby);
            }
            $js_sortby = Factory::getApplication()->input->get('js_sortby');

            $result = $this->getJSModel('resume')->getAllEmpApps($datafor, $resumetitle, $resumename, $resumecategory, $resumetype, $desiredsalary, $location, $dateto, $datefrom, $status, $isgfcombo, $sortby, $js_sortby, $limitstart, $limit);
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
        }elseif (($layoutName == 'resumeprint')) {// view resume
            require_once(JPATH_ROOT . '/components/com_jsjobs/models/resume.php');
            $resumeid = Factory::getApplication()->input->get('id', '');
            if (!$resumeid)
                $resumeid = Factory::getApplication()->input->get('rd', '');
            $myresume = Factory::getApplication()->input->get('nav', '2');
            $folderid = Factory::getApplication()->input->get('fd', '');
            $sortvalue = $sort = Factory::getApplication()->input->get('sortby', false);
            if ($sort != false)
                $sort = $this->getEmpListOrdering($sort);
            $tabaction = Factory::getApplication()->input->get('ta', false);
            $model = $this->getModel();
            $show_only_section_that_have_value = $this->getJSModel('configuration')->getConfigValue('show_only_section_that_have_value');

            $resume_object = new JSJobsModelResume();
            $result = $resume_object->getResumeViewbyId($uid, false, $resumeid, $myresume, $sort, $tabaction);
            $canview = 1;
            $validresume = true;
            if(is_numeric($resumeid)){
                $validresume = $resume_object->checkResumeExists($resumeid);
            }
            $this->validresume = $validresume;

            $this->resume = $result['personal'];
            $this->addresses = $result['addresses'];
            $this->institutes = $result['institutes'];
            $this->employers = $result['employers'];
            $this->references = $result['references'];
            $this->languages = $result['languages'];
            $this->fieldsordering = $result['fieldsordering'];
            $this->userfields = $result['userfields'];
            $this->show_only_section_that_have_value = $show_only_section_that_have_value;
            $this->canview = $canview;
            $this->cvids = $result['cvids']; // for employer resumes navigations
            $nav = Factory::getApplication()->input->get('nav', '');
            $this->nav = $nav;
            $jobaliasid = Factory::getApplication()->input->get('bd', '');
            $this->jobaliasid = $jobaliasid;
            $this->resumeid = $resumeid;
            $this->sortby = $sortvalue;
            $this->fd = $folderid;
            $this->ms = $myresume;

            $user = Factory::getUser();
            if (isset($user->groups[8]) OR isset($user->groups[7])) {
                $isadmin = 1;
            }
            $this->isadmin = $isadmin;
            $printresume = Factory::getApplication()->input->get('print', '');
            $this->printresume = $printresume;
            ToolbarHelper::title(Text::_('View Resumes'));
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
