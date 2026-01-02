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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewJobSeekerPackages extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'package_buynow') {
            $page_title .= ' - ' . Text::_('Buy Now');
            $packageid = Factory::getApplication()->input->get('gd');
            $package = $this->getJSModel('jobseekerpackages')->getJobSeekerPackageById($packageid);
            $this->package = $package;
            $nav = Factory::getApplication()->input->get('nav', '');
            $layoutfor = Factory::getApplication()->input->get('jslayfor');
            if($layoutfor!='detail')
                $layoutfor = 'no';
            $this->layoutfor = $layoutfor;
            $this->nav = $nav;
        } elseif ($layout == 'package_details') {
            $page_title .= ' - ' . Text::_('Package Details');
            $packageid = Factory::getApplication()->input->get('gd');
            $package = $this->getJSModel('jobseekerpackages')->getJobSeekerPackageById($packageid);
            $this->package = $package;
        } elseif ($layout == 'packages') {            // job seeker package
            $page_title .= ' - ' . Text::_('Packages');
            $result = $this->getJSModel('jobseekerpackages')->getJobSeekerPackages($limit, $limitstart);
            $this->packages = $result[0];
            if ($result[1] <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($result[1], $limitstart, $limit);
            $this->pagination = $pagination;
        }
        require_once('jobseekerpackages_breadcrumbs.php');

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
        //$this->pdflink = $pdflink;
        //$this->printlink = $printlink;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
