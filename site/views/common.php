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
use Joomla\CMS\Router\Route;    


global $mainframe, $sorton, $sortorder, $option, $_client_auth_key, $socialconfig;
$mainframe = Factory::getApplication();
$user = Factory::getUser();
$uid = $user->id;
$session = Factory::getApplication()->getSession();
$config = $session->get('jsjobconfig_dft');
$resumeid = $session->get("resumeid");
$this->resumeid = $resumeid;
$curuser = $session->get('jsjobcur_usr');
if ($curuser != $uid)
    unset($config);
$session->set('jsjobcur_usr', $uid);
if (isset($config))
    if ($config['testing_mode'] == 1)
        unset($config);
if (!isset($config)) {
    $results = $this->getJSModel('configurations')->getConfig('');
    if ($results) { //not empty
        foreach ($results as $result) {
            $config[$result->configname] = $result->configvalue;
        }
        $session->set('jsjobconfig_dft', $config);
    }
}
$layout = Factory::getApplication()->input->get('layout');
$itemid = Factory::getApplication()->input->get('Itemid');
$type = 'offl';
$option = 'com_jsjobs';
$userrole = Array();
$document = Factory::getDocument();
//Old css
$document->addStyleSheet('components/com_jsjobs/css/style.css', 'text/css');
require_once(JPATH_COMPONENT.'/css/style_color.php');
//rtl
$language = Factory::getLanguage();
if($language->isRtl()){
    $document->addStyleSheet('components/com_jsjobs/css/style_rtl.css', 'text/css');
}
if (!isset($_client_auth_key) && $_client_auth_key == "") {
    $auth_key = $this->getJSModel('common')->getClientAuthenticationKey();
    $_client_auth_key = $auth_key;
}

$needlogin = true;
$view = Factory::getApplication()->input->get('view');
switch ($layout) {
    case 'controlpanel':
        $needlogin = false;
        if($view == 'employer')
        if ($config['visitorview_emp_conrolpanel'] == 0)
            $needlogin = true;
        break;
    case 'packages':
        if ($config['visitorview_emp_packages'] == 1) {
            if (!$user->guest) {
                $role = $this->getJSModel('userrole')->getUserRole($uid);
                if (!isset($role->rolefor)) {
                    $n_i_l = Route::_('index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=' . $itemid ,false);
                    $mainframe->redirect($n_i_l);
                }
            }
            $needlogin = false;
            break;
        }
    case 'package_details': if ($config['visitorview_emp_viewpackage'] == 1)
            $needlogin = false;
        break;
    case 'resumesearch': if ($config['visitorview_emp_resumesearch'] == 1)
            $needlogin = false;
        break;
    case 'resumesearchresult': if ($config['visitorview_emp_resumesearchresult'] == 1)
            $needlogin = false;
        break;
    case 'view_company': if ($config['visitorview_emp_viewcompany'] == 1)
            $needlogin = false;
        break;
    case 'view_job': if ($config['visitorview_emp_viewjob'] == 1)
            $needlogin = false;
        break;
    case 'myjobs':
        $needlogin = true;
        break;
    case 'formjob':
        $needlogin = true;
        break;
    default : $needlogin = false;
        break;
}


if ($user->guest) { // redirect user if not login
    if ($needlogin) {
        if ($layout == 'package_buynow') {
            $nav = Factory::getApplication()->input->get('nav');
            $gd = Factory::getApplication()->input->get('gd');
            if ($nav)
                $session->set('nav',$nav);
            if ($gd)
                $session->set('gd',$gd);
        }
        $msg = Text::_('To access the private area of the site').'. '.Text::_('Please log in').'.';
        $redirectUrl = Route::_('index.php?option=com_jsjobs&c=common&view=common&layout=successfullogin&Itemid=' . $itemid ,false);
        $redirectUrl = '&amp;return=' . $this->getJSModel('common')->b64ForEncode($redirectUrl);
        $default_login_from = $this->getJSModel('configurations')->getConfigValue('default_login_from');
        
        if($default_login_from == 1){ // JS Jobs login
            $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid . $redirectUrl;    
        }elseif($default_login_from == 2){ // Joomla Login
            $link = 'index.php?option=com_users&view=login&Itemid=' . $itemid . $redirectUrl;
        }else{ // custom link
            $login_custom_link = $this->getJSModel('configurations')->getConfigValue('login_custom_link');
            $link = $login_custom_link;
            if (!preg_match("~^(?:f|ht)tps?://~i", $link)) { // If not exist then add http 
                    $link = "http://" . $link; 
            }                                         
            if($login_custom_link == ""){
                $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid . $redirectUrl;    
            }
        }
        
        $finalUrl = $link;

        $app = Factory::getApplication();
        $app->enqueueMessage($msg, 'fail');
        $app->redirect($finalUrl);
        //$mainframe->redirect($finalUrl, $msg);
    }
}



$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
$limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');
$limitstart = Factory::getApplication()->input->get('limitstart', 0);
$params = $mainframe->getParams('com_jsjobs');
$jobseekerlinks = array();
$employerlinks = array();

if ($curuser != $uid)
    unset($role);

if (!isset($role)) {
    $role = $this->getJSModel('userrole')->getUserRole($uid);

    $userrole = $role;
    if (isset($role)) { //not empty

    } else {
        if ($layout != 'view_job' && $layout != 'new_injsjobs') {
            if (!$user->guest) {
                $mainframe->redirect('index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid='.$itemid);
            }
        }
    }
}
if (isset($role->rolefor)) {
    if ($role->rolefor == 1) { // employer
        if ($config['tmenu_emcontrolpanel'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('Control Panel'), 'controlpanel');
        }if ($config['tmenu_emnewjob'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('New Job'), 'new_job');
        }if ($config['tmenu_emmyjobs'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('My Jobs'), 'my_job');
        }if ($config['tmenu_emmycompanies'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('My Companies'), 'my_company');
        }if ($config['tmenu_emsearchresume'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=jobapply&view=resume&layout=resumesearch&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('Resume Search'), 'applied_resume');
        }
        
        $redirectUrl = Route::_('index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid=' . $itemid ,false);
        if (isset($role->rolefor)) {//jobseeker
            $redirect = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
            $link = "index.php?option=com_jsjobs&task=jsjobs.logout&return=".$redirect."&Itemid=" . $itemid;
            $text = Text::_('Logout');
            $employerlinks [] = array($link, $text, 'logout');
        }
    } else {
        if ($config['tmenu_jscontrolpanel'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('Control Panel'), 'controlpanel');
        }if ($config['tmenu_jsnewestjob'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobs&Itemid=' . $itemid.'&jsfrom=reset-user-state';
            $jobseekerlinks [] = array($link, Text::_('Newest Jobs'), 'newest_job');
        }if ($config['tmenu_jsjobcategory'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=jobcat&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('Job Categories'), 'job_categories');
        }if ($config['tmenu_jssearchjob'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobsearch&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('Search Job'), 'job_search');
        }if ($config['tmenu_jsmyresume'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('My Resumes'), 'my_resume');
        }

        $redirectUrl = Route::_('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $itemid ,false);
        if (isset($role->rolefor)) {//jobseeker
            $redirect = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);

            $link = "index.php?option=com_jsjobs&task=jsjobs.logout&return=".$redirect."&Itemid=" . $itemid;
            $text = Text::_('Logout');
            $employerlinks [] = array($link, $text, 'logout');
        }
    }
} else { // user not logined
    $layout = $this->getLayout();
    $view = Factory::getApplication()->input->get('view');
    if ($layout == 'jobcat' || $layout == 'userlogin' || $layout == 'userregister' || $layout == 'view_company' || $layout == 'company_jobs' || $layout == 'formcoverletter' || $layout == 'mycoverletters' || $layout == 'view_coverletter' || $layout == 'job_searchresults' || $layout == 'jobapply' || $layout == 'jobsearch' || $layout == 'list_jobs' || $layout == 'list_jobshortlist' || $layout == 'listjob_subcategories' || $layout == 'listnewestjobs' || $layout == 'view_job' || $layout == 'jobalertsetting' || $layout == 'jobalertunsubscribe' || $layout == 'myappliedjobs' || $layout == 'my_jobsearches' || ($layout == 'controlpanel' && $view == 'jobseeker') || ($layout == 'my_stats' && $view == 'jobseeker') || ($layout == 'package_buynow' && $view == 'jobseekerpackages') || ($layout == 'package_details' && $view == 'jobseekerpacakges') || ($layout == 'packages' && $view == 'jobseekerpacakges') || $layout == 'send_message' || $layout == 'jsmessages' || $layout == 'norecordfound' || $layout == 'jobseekerpurchasehistory' || $layout == 'formresume' || $layout == 'myresumes' || $layout == 'view_resume' || $layout == 'list_subcategoryjobs' || $layout == 'listallcompanies' || $layout == 'listjobtypes' || ($layout == 'packages' && $view == 'jobseekerpackages') || ($layout == 'jobs' && $view == 'job') ) { // Job seeker
        if ($config['tmenu_vis_jscontrolpanel'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('Control Panel'), 'controlpanel');
        }if ($config['tmenu_vis_jsnewestjob'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobs&Itemid=' . $itemid.'&jsfrom=reset-user-state';
            $jobseekerlinks [] = array($link, Text::_('Newest Jobs'), 'newest_job');
        }if ($config['tmenu_vis_jsjobcategory'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=category&view=category&layout=jobcat&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('Job Categories'), 'job_categories');
        }if ($config['tmenu_vis_jssearchjob'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobsearch&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('Search Job'), 'job_search');
        }if ($config['tmenu_vis_jsmyresume'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=' . $itemid;
            $jobseekerlinks [] = array($link, Text::_('My Resumes'), 'my_resume');
        }

        $redirectUrl = Route::_('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $itemid ,false);
        $redirectUrl = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
        $redirectUrl = '&amp;return=' . $redirectUrl;
        $default_login_from = $this->getJSModel('configurations')->getConfigValue('default_login_from');
        
        if($default_login_from == 1){ // JS Jobs login
            $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid . $redirectUrl;    
        }elseif($default_login_from == 2){ // Joomla Login
            $link = 'index.php?option=com_users&view=login&Itemid=' . $itemid . $redirectUrl;
        }else{ // custom link
            $login_custom_link = $this->getJSModel('configurations')->getConfigValue('login_custom_link');
            $link = $login_custom_link;
            if (!preg_match("~^(?:f|ht)tps?://~i", $link)) { // If not exist then add http 
                    $link = "http://" . $link; 
            }                                         
            if($login_custom_link == ""){
                $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid . $redirectUrl;    
            }
        }
        
        $text = Text::_('Login');
        $jobseekerlinks [] = array($link, $text, 'login');
    } elseif ($layout == 'new_injsjobs' || $layout == 'formdepartment' || $layout == 'mydepartments' || $layout == 'view_department' || $layout == 'formcompany' || $layout == 'mycompanies' || ($layout == 'controlpanel' && $view == 'employer') || ($layout == 'my_stats' && $view == 'employer') || ($layout == 'package_buynow' && $view == 'employerpackages') || ($layout == 'package_details' && $view == 'employerpackages') || ($layout == 'packages' && $view == 'employerpackages') || $layout == 'folder_resumes' || $layout == 'formfolder' || $layout == 'myfolders' || $layout == 'viewfolder' || $layout == 'formjob' || $layout == 'formjob_visitor' || $layout == 'myjobs' || $layout == 'job_appliedapplications' || $layout == 'job_apply' || $layout == 'empmessages' || $layout == 'job_messages' || $layout == 'employerpurchasehistory' || $layout == 'listresume_subcategories' || $layout == 'my_resumesearches' || $layout == 'resume_bycategory' || $layout == 'resume_bysubcategory' || $layout == 'resume_print' || $layout == 'resume_searchresults' || $layout == 'resumebycategory' || $layout == 'resumesearch') { // employer
        if ($config['tmenu_vis_emcontrolpanel'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('Control Panel'), 'controlpanel');
        }if ($config['tmenu_vis_emnewjob'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('New Job'), 'new_job');
        }if ($config['tmenu_vis_emmyjobs'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('My Jobs'), 'my_job');
        }if ($config['tmenu_vis_emmycompanies'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('My Companies'), 'my_company');
        }if ($config['tmenu_vis_emsearchresume'] == 1) {
            $link = 'index.php?option=com_jsjobs&c=jobapply&view=resume&layout=resumesearch&Itemid=' . $itemid;
            $employerlinks [] = array($link, Text::_('Resume Search'), 'applied_resume');
        }
        $redirectUrl = Route::_('index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid=' . $itemid ,false);
        $default_login_from = $this->getJSModel('configurations')->getConfigValue('default_login_from');
        $redirectUrl = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
        $redirectUrl = '&amp;return=' . $redirectUrl;
        if($default_login_from == 1){ // JS Jobs login
            $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid . $redirectUrl;    
        }elseif($default_login_from == 2){ // Joomla Login
            $link = 'index.php?option=com_users&view=login&Itemid=' . $itemid . $redirectUrl;
        }else{ // custom link
            $login_custom_link = $this->getJSModel('configurations')->getConfigValue('login_custom_link');
            $link = $login_custom_link;
            if (!preg_match("~^(?:f|ht)tps?://~i", $link)) { // If not exist then add http 
                    $link = "http://" . $link; 
            }                                         
            if($login_custom_link == ""){
                $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $itemid . $redirectUrl;    
            }
        }
        $text = Text::_('Login');
        $employerlinks [] = array($link, $text, 'login');
    }
}

//message system
require_once('messages.php');
$jsjobsmessages = new JSJobsMessages();
$this->jsjobsmessages = $jsjobsmessages;

$page_title = $params->get('page_title');

// get save filter
if ($uid != '') {
    $var = $session->get('jsuserfilter');
    if(!empty($var)){
        $userfilter = $var;
    } else {
        $userfilter = null;
    }

    if (is_array($userfilter) && sizeof($userfilter) == 0) {
        $result = $this->getJSModel('filter')->getUserFilter($uid);
        if (isset($result)) { //not empty
            $userfilter[0] = 1;
            $userfilter[1] = $result;
            $session->set('jsuserfilter',$userfilter);
            $filterid = $result->id;
        } else {
            $userfilter[0] = 1;
            $userfilter[1] = null;
            $session->set('jsuserfilter',$userfilter);
        }
    } else {
        $userfilter = $session->get('jsuserfilter','');
        if (isset($userfilter[1]))
            $filterid = $userfilter[1]->id;
    }
}
else {
    $userfilter = '';
}
?>
