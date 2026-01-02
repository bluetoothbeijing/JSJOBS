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
use Joomla\CMS\Pagination\Pagination;

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewPurchaseHistory extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';

        if ($layout == 'employerpurchasehistory') {// my resume searches 
            $mypurchasehistory_allowed = $this->getJSModel('permissions')->checkPermissionsFor("EMPLOYER_PURCHASE_HISTORY");
            if ($mypurchasehistory_allowed == VALIDATE) {
                $result = $this->getJSModel('purchasehistory')->getEmployerPurchaseHistory($uid, $limit, $limitstart);
                $this->packages = $result[0];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
            }
            $this->mypurchasehistory_allowed = $mypurchasehistory_allowed;
        }elseif ($layout == 'jobseekerpurchasehistory') {// my resume searches
            $mypurchasehistory_allowed = $this->getJSModel('permissions')->checkPermissionsFor("JOBSEEKER_PURCHASE_HISTORY");
            if ($mypurchasehistory_allowed == VALIDATE) {
                $result = $this->getJSModel('purchasehistory')->getJobSeekerPurchaseHistory($uid, $limit, $limitstart);
                $this->packages = $result[0];
                if ($result[1] <= $limitstart)
                    $limitstart = 0;
                $pagination = new Pagination($result[1], $limitstart, $limit);
                $this->pagination = $pagination;
            }
            $this->mypurchasehistory_allowed = $mypurchasehistory_allowed;
        }
        require_once('purchasehistory_breadcrumbs.php');
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
