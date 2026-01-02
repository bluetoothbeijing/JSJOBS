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

class JSJobsViewCommon extends JSView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'html';
        if ($layout == 'successfullogin') {
            $var = $session->get('jsjobs_option');
            if(!empty($var)){
                $jsoption = $var;
                $session->clear('jsjobs_option');
            }
            $var = $session->get('jsjobs_view');
            if(!empty($var)){
                $jsview = $var;
                $session->clear('jsjobs_view');
            }
            $var = $session->get('jsjobs_red_layout');
            if(!empty($var)){
                $jslayout = $var;
                $session->clear('jsjobs_red_layout');
            }
            $var = $session->get('jsjobs_comp_url');
            if(!empty($var)){
                $compurl = $var;
                $session->clear('jsjobs_comp_url');
            }

            if ($jslayout == 'successfullogin')
                $jslayout = 'controlpanel';
            if ($jsoption == '')
                $jsoption = Factory::getApplication()->input->get('option');
            if ($jsoption == '')
                $jsoption = 'com_jsjobs';
            if ($jsoption == 'com_jsjobs') {
                if ($compurl != '') {
                    $mainframe->redirect($compurl);
                } elseif ($jsview != '') {
                    $nav = $session->get('nav');
                    $gd = $session->get('gd');
                    $bd = $session->get('bd');
                    if ($jslayout == 'package_buynow') {
                        if(!empty($nav))
                            $mainframe->redirect('index.php?option=com_jsjobs&c=&view=&layout=' . $jslayout . '&nav=' . $nav . '&gd=' . $gd . '&Itemid=' . $itemid);
                        else
                            $mainframe->redirect('index.php?option=com_jsjobs&c=&view=&layout=' . $jslayout . '&gd=' . $gd . '&Itemid=' . $itemid);
                        $session->clear('gd');
                        $session->clear('nav');
                    }elseif ($jslayout == 'job_apply') {
                        $mainframe->redirect('index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=' . $jslayout . '&nav=' . $nav . '&bd=' . $bd . '&Itemid=' . $itemid);
                        $session->clear('bd');
                        $session->clear('nav');
                    } else {
                        $mainframe->redirect('index.php?option=com_jsjobs&c=&view=&layout=' . $jslayout . '&Itemid=' . $itemid);
                    }
                } else { //get role of this user
                    if (isset($role->rolefor)) {
                        if ($role->rolefor == 1) { // employer
                            $mainframe->redirect('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $itemid);
                        } elseif ($role->rolefor == 2) { // jobseeker
                            $mainframe->redirect('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $itemid);
                        }
                    }
                }
            }
            $result = $this->getJSModel('purchasehistory')->getEmployerPurchaseHistory($uid, $limit, $limitstart);
            $this->packages = $result[0];
            if ($result[1] <= $limitstart)
                $limitstart = 0;
            $pagination = new Pagination($result[1], $limitstart, $limit);
            $this->pagination = $pagination;
        }elseif ($layout == 'new_injsjobs') {             // new in jsjobs
            $page_title .= ' - ' . Text::_('Welcome To ').$config['title'];
            $result = $this->getJSModel('userrole')->getUserType($uid);
            $this->usertype = $result[0];
            $this->lists = $result[1];
        } elseif ($layout == 'userlogin') {
            $role = Factory::getApplication()->input->get('ur');
            $return = Factory::getApplication()->input->get('return');
            $this->userrole = $role;
            $this->loginreturn = $return;
        } elseif ($layout == 'userregister') {
            if (!$uid) {
                $role = Factory::getApplication()->input->get('userrole');
                $this->userrole = $role;
                $result1 = $this->getJSModel('common')->getCaptchaForForm();
                $this->captcha = $result1;
            } else {
                $mainframe->redirect('index.php?option=com_users&view=profile&Itemid=' . $itemid);
            }
        }

        $document->setTitle($page_title);
        $this->userrole = $role;
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
