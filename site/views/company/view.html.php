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

class JSJobsViewCompany extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'mycompanies') {        // my companies

            $page_title .= ' - ' . Text::_('My Companies');
            $mycompany_allowed = $this->getJSModel('permissions')->checkPermissionsFor("MY_COMPANY");
            if ($mycompany_allowed == VALIDATE) {
                $result = $this->getJSModel('company')->getMyCompanies($uid, $limit, $limitstart);
                $companies = $result[0];
                $this->companies = $companies;
                $this->fieldsordering = $result[2];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
                $companyconfig = $this->getJSModel('configurations')->getConfigByFor('company');
                $this->companyconfig = $companyconfig;

            }
            $this->mycompany_allowed = $mycompany_allowed;
        }elseif ($layout == 'view_company') {                // view company
            $companyid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('cd', ''));
            $result = $this->getJSModel('company')->getCompanybyId($companyid);
            $company = $result[0];
            $company_title = isset($company->name) ? $company->name : '';
            $company_description = isset($company->description) ? $company->description : '';
            $document->setMetaData('title', $company_title, true);
            $document->setDescription($company_description);
            $this->company = $company;
            if(isset($result[2])) $this->userfields = $result[2];
            $this->fieldsordering = $result[3];
            $nav = Factory::getApplication()->input->get('nav', '');
            $this->nav = $nav;
            $jobcat = Factory::getApplication()->input->get('cat', '');
            $this->jobcat = $jobcat;
            if (isset($company)) {
                $page_title .= ' - ' . $company->name;
            }
        } elseif ($layout == 'formcompany') {           // form company

			$page_title .= ' - ' . Text::_('Company Information');
			$companyid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('cd', ''));
			$showform = true;
            $this->getJSModel('permissions');
			if($uid == 0){
				$param = VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA;
				$this->canaddnewcompany = $param;
				$avoid_breadcrumbs = 1;
				unset($result);
				$result[0] = false;
			}else{
				if($companyid){
					if(!$this->getJSModel('company')->getIfCompanyOwner($companyid, $uid)){
						$param = JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA;
						$this->canaddnewcompany = $param;
						$avoid_breadcrumbs = 1;
						unset($result);
						$result[0] = false;
						$showform = false;
					}
				}
				if($showform == true){
					$page_title .= ' - ' . Text::_('Company Information');
					if (!isset($companyid))
						$companyid = '';
					$result = $this->getJSModel('company')->getCompanybyIdforForm($companyid, $uid, '', '', '');
					$this->company = $result[0];
					$this->lists = $result[1];
					if(isset($result[2])) $this->userfields = $result[2]; else $this->userfields = array();
					$this->fieldsordering = $result[3];
					$this->canaddnewcompany = $result[4];
					if(isset($result[5])) $this->packagedetail = $result[5]; else $this->packagedetail = array();
					if (isset($result[6]))
						$this->multiselectedit = $result[6];
					HTMLHelper::_('behavior.formvalidator');
				}
			}
        } elseif ($layout == 'company_info') {            // job Details
            //--//
            $companyid = Factory::getApplication()->input->get('cd');
            $result = $this->getJSModel('company')->getCompanyInfoById($companyid);
            $this->info = $result[0];
            $this->jobs = $result[1];
            $this->company = $result[2];
        } elseif ($layout == 'listallcompanies') {            // List all companies
            $page_title .= ' - ' . Text::_('All Companies');
            $sort = Factory::getApplication()->input->get('sortby', '');
            if (isset($sort)) {
                if ($sort == '')
                    $sort = 'createddesc';
            } else {
                $sort = 'createddesc';
            }
            if(Factory::getApplication()->input->get('sortby',null) == null && Factory::getApplication()->input->get('limitstart',null) == null){
                $mainframe->setUserState($option.'companycity','');
                $mainframe->setUserState($option.'companyname','');
                if(Factory::getApplication()->getTemplate() == 'jobi'){
                    $mainframe->setUserState($option.'category','');
                }
            }
            $sortby = $this->getJobListOrdering($sort);
            $filter_search = array();
            $filter_search['companycity'] = $mainframe->getUserStateFromRequest($option . 'companycity', 'companycity', '', 'string');
            $filter_search['companyname'] = $mainframe->getUserStateFromRequest($option . 'companyname', 'companyname', '', 'string');
            if(Factory::getApplication()->getTemplate() == 'jobi'){
                $filter_search['category'] = $mainframe->getUserStateFromRequest($option . 'category', 'category', '', 'string');
            }
            $result = $this->getJSModel('company')->getAllCompaniesList($sortby, $limit, $limitstart,$filter_search);
            $this->companies = $result[0];
            $this->multicities = $result[2];
            $this->fieldsordering = $result[3];
            if ($result[1] <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($result[1], $limitstart, $limit);
            $this->pagination = $pagination;
            $sortlinks = $this->getJobListSorting($sort);
            $sortlinks['sorton'] = $sorton;
            $sortlinks['sortorder'] = $sortorder;
            $this->sortlinks = $sortlinks;
            $this->filter_search = $filter_search;
        }
        require_once('company_breadcrumbs.php');
        $document->setTitle($page_title);
        $this->userrole = $userrole;
        $this->config = $config;
        $this->socailsharing = $socialconfig;
        $this->option = $option;
        $this->params = $params;
        $this->viewtype = $viewtype;
        $this->employerlinks = $employerlinks;
        $this->jobseekerlinks = $jobseekerlinks;
        $this->uid = $uid;
        //$this->id = $id; may no need it
        $this->Itemid = $itemid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

    function getJobListSorting($sort) {
        $sortlinks['name'] = $this->getSortArg("name", $sort);
        $sortlinks['category'] = $this->getSortArg("category", $sort);
        $sortlinks['city'] = $this->getSortArg("city", $sort);
        $sortlinks['created'] = $this->getSortArg("created", $sort);
        return $sortlinks;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        return "createddesc";
    }

    function getJobListOrdering($sort) {
        global $sorton, $sortorder;
        switch ($sort) {
            case "namedesc": $ordering = "company.name DESC";
                $sorton = "name";
                $sortorder = "DESC";
                break;
            case "nameasc": $ordering = "company.name ASC";
                $sorton = "name";
                $sortorder = "ASC";
                break;
            case "categorydesc": $ordering = "cat.cat_title DESC";
                $sorton = "category";
                $sortorder = "DESC";
                break;
            case "categoryasc": $ordering = "cat.cat_title ASC";
                $sorton = "category";
                $sortorder = "ASC";
                break;
            case "citydesc": $ordering = "city.name DESC";
                $sorton = "city";
                $sortorder = "DESC";
                break;
            case "cityasc": $ordering = "city.name ASC";
                $sorton = "city";
                $sortorder = "ASC";
                break;
            case "createddesc": $ordering = "company.created DESC";
                $sorton = "created";
                $sortorder = "DESC";
                break;
            case "createdasc": $ordering = "company.created ASC";
                $sorton = "created";
                $sortorder = "ASC";
                break;
            default: $ordering = "company.created DESC";
                $sorton = "created";
                $sortorder = "DESC";
                break;
        }
        return $ordering;
    }

}

?>
