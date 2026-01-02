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

class JSJobsViewDepartment extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'mydepartments') {        // my departments
            $page_title .= ' - ' . Text::_('My Departments');
            $mydepartment_allowed = $this->getJSModel('permissions')->checkPermissionsFor("MY_DEPARTMENT");
            if ($mydepartment_allowed == VALIDATE) {
                $result = $this->getJSModel('department')->getMyDepartments($uid, $limit, $limitstart);
                $departments = $result[0];
                $totalresults = $result[1];
                $this->departments = $departments;
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
            }
            $this->mydepartment_allowed = $mydepartment_allowed;
        }elseif ($layout == 'view_department') {                // view company
            $departmentid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('pd', ''));
            $department = $this->getJSModel('department')->getDepartmentbyId($departmentid);
            $this->department = $department;
            if (isset($department)) {
                $page_title .= ' - ' . $department->name;
            }
        } elseif ($layout == 'formdepartment') {         //form department
            $page_title .= ' - ' . Text::_('Department Information');
            $formdepartment_allowed = $this->getJSModel('permissions')->checkPermissionsFor("ADD_DEPARTMENT");
            if ($formdepartment_allowed == VALIDATE) {
                $departmentid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('pd', ''));
                $result = $this->getJSModel('department')->getDepartmentByIdForForm($departmentid, $uid);
                if(isset($result[0])) $this->department = $result[0];
                $this->lists = $result[1];
                HTMLHelper::_('behavior.formvalidator');
            }
            $this->formdepartment_allowed = $formdepartment_allowed;
        }
        require_once('department_breadcrumbs.php');
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
