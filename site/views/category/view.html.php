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
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewCategory extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'jobcat') {//job cat
            $page_title .= ' - ' . Text::_('Job Categories');
            $result = $this->getJSModel('job')->getJobCat();
            $application = $result[0];
            $filterlists = $result[2];
            $filtervalues = $result[3];
            $this->application = $application;
            $this->filterlists = $filterlists;
            $this->filtervalues = $filtervalues;
            if(isset($filterid)) $this->filterid = $filterid;
        }

        require_once('category_breadcrumbs.php');

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
        //$this->id = $id;
        $this->Itemid = $itemid;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
