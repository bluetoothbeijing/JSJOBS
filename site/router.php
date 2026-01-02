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
defined('_JEXEC') or die;


use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Factory;


/**
 * Routing class of com_phocagallery
 *
 * @since  3.3
 */


class JSJobsRouter extends RouterView
{
    protected $noIDs = false;

    /**
     * Content Component router constructor
     *
     * @param   JApplicationCms  $app   The application object
     * @param   JMenu            $menu  The menu object to work with
     */
    public function __construct($app = null, $menu = null)
    {

        $params = ComponentHelper::getParams('com_jsjobs');

        parent::__construct($app, $menu);

    }



    public function build(&$query){
        $segments = array();
        $segments = $this->JSJobsBuildRouteJ4($query);
        return $segments;
    }

    public function parse(&$segments){
        $vars = array();
        $vars = $this->JSJobsParseRouteJ4($segments);

        //print_r($vars);
        //die();
        $segments = [];
        return $vars;
    }

    public function preprocess($query){
        return $query;
    }

    function JSJobsBuildRouteJ4(&$query) {

        $segments = array();
        $router = new jsjobsRouterOld;
        if (isset($query['c'])) {
            $c = $query['c']; unset($query['c']);
        };
        if (isset($query['view'])) {
            $view = $query['view']; unset($query['view']);
        };
        if (isset($query['layout'])) {
            if(isset($query['jslayfor'])){
                $flag = true;
            }else{
                $flag = false;
            }
            // view is undefined in case of gdpr "datacomplianceactions" layout
            $value = $router->buildLayout($query['layout'], $view,$flag);
            $layout = $query['layout'];
            $segments[] = $value; unset($query['layout']);
        };
        if (isset($query['task'])) {
            $segments[] = 'task-'.$query['task']; unset($query['task']);
        };
        
        if (isset($query['jsfrom'])) {
            $segments[] = "cp-" . $router->clean($query['jsfrom']); unset($query['jsfrom']);
        };
        //job type
        if (isset($query['jt'])) {
            $segments[] = "jobtype-" . $router->clean($query['jt']); unset($query['jt']);
        };
        //category
        if (isset($query['cat'])) {
            $segments[] = "category-" . $router->clean($query['cat']); unset($query['cat']);
        };
        //sub category
        if (isset($query['jobsubcat'])) {
            $segments[] = "subcategory-" . $router->clean($query['jobsubcat']); unset($query['jobsubcat']);
        };
        //company
        if (isset($query['cd'])) {
            $jsjobs_id = $query['cd'];
            $company_seo = JSModel::getJSModel('configurations')->getConfigValue('company_seo');
            if(!empty($company_seo)){
                $company_seo = JSModel::getJSModel('company')->makeCompanySeo($company_seo , $jsjobs_id);
                if($company_seo != ''){
                    $id = $router->parseId($jsjobs_id);
                    $jsjobs_id = $company_seo.'-'.$id;
                }
                $segments[] = "company-" . $router->clean($jsjobs_id); unset($query['cd']);
            }else{
                $segments[] = "company-" . $router->clean($query['cd']); unset($query['cd']);
            }
        };
        //resume
        if (isset($query['rd'])) {
            $jsjobs_id = $query['rd'];
            $resume_seo = JSModel::getJSModel('configurations')->getConfigValue('resume_seo');
            if(!empty($resume_seo)){
                $resume_seo = JSModel::getJSModel('resume')->makeResumeSeo($resume_seo , $jsjobs_id);
                if($resume_seo != ''){
                    $id = $router->parseId($jsjobs_id);
                    $jsjobs_id = $resume_seo.'-'.$id;
                }
                $segments[] = "resume-" . $router->clean($jsjobs_id); unset($query['rd']);
            }else{
                $segments[] = "resume-" . $router->clean($query['rd']); unset($query['rd']);
            }
        };
        //job
        if (isset($query['bd'])) {
            $job_seo = '';
            $jsjobs_id = $query['bd'];
            $job_seo = JSModel::getJSModel('configurations')->getConfigValue('job_seo');
            if(!empty($job_seo)){
                $job_seo = JSModel::getJSModel('job')->makeJobSeo($job_seo , $query['bd']);
                if($job_seo != ''){
                    $id = $router->parseId($jsjobs_id);
                    $jsjobs_id = $job_seo.'-'.$id;
                }
                $segments[] = "job-" . $router->clean($jsjobs_id); unset($query['bd']);
            }else{
                $segments[] = "job-" . $router->clean($query['bd']); unset($query['bd']);
            }
        };
        //email
        if (isset($query['email'])) {
            $segments[] = "email-" . $query['email']; unset($query['email']);
        };
        //cover letter
        if (isset($query['cl'])) {
            $segments[] = "letter-" . $router->clean($query['cl']); unset($query['cl']);
        };
        //package
        if (isset($query['gd'])) {
            $segments[] = "package-" . $router->clean($query['gd']); unset($query['gd']);
        };
        //package
        if (isset($query['jslayfor'])) {
            unset($query['jslayfor']);
        };
        //package buy now
        if (isset($query['pb'])) {
            $segments[] = "pkgfrom-" . $router->getPackageFromTitle($query['pb']) . "-" . $query['pb']; unset($query['pb']);
        };
        //department
        if (isset($query['pd'])) {
            $segments[] = "dept-" . $router->clean($query['pd']); unset($query['pd']);
        };
        //view department
        if (isset($query['vp'])) {
            $segments[] = "viewdept-" . $router->clean($query['vp']); unset($query['vp']);
        };
        //resume pdf output
        if (isset($query['format'])) {
            if (isset($layout) AND $layout == "rssresumes") {
                $segments[] = "resumerssformat-" . $query['format']; unset($query['format']);
            } elseif (isset($layout) AND $layout == "rssjobs") {
                $segments[] = "jobsrssformat-" . $query['format']; unset($query['format']);
            } else {
                $segments[] = "format-".$query['format']; unset($query['format']);
            }
        };
        //folder
        if (isset($query['fd'])) {
            $segments[] = "fld-" . $query['fd']; unset($query['fd']);
        };
        //country
        if (isset($query['country'])) {
            $segments[] = "cn-" . $query['country']; unset($query['country']);
        };
        //state
        if (isset($query['state'])) {
            $segments[] = "st-" . $query['state']; unset($query['state']);
        };
        //city
        if (isset($query['city'])) {
            $segments[] = "cy-" . $query['city']; unset($query['city']);
        };
        if (isset($query['sortby'])) {
            $segments[] = "sort-" . $query['sortby']; unset($query['sortby']);
        };
        // form user registration
        if (isset($query['userrole'])) {
            if ($query['userrole'] == "2") {
                $segments[] = "jobseeker-form"; unset($query['userrole']);
            } elseif ($query['userrole'] == "3") {
                $segments[] = "employer-form-"; unset($query['userrole']);
            }
        };
        // applied application tab
        if (isset($query['ta'])) {
            $segments[] = "tab-" . $router->getAppliedApplicationTabTitle($query['ta']) . "-" . $query['ta']; unset($query['ta']);
        };
        // view resume search
        if (isset($query['rs'])) {
            $segments[] = "rsaved-" . $query['rs']; unset($query['rs']);
        };
        // view job search
        if (isset($query['js'])) {
            $segments[] = "jsaved-" . $query['js']; unset($query['js']);
        };
        //resume by subcategory
        if (isset($query['resumesubcat'])) { //subcat
            $segments[] = "subcat-" . $query['resumesubcat']; unset($query['resumesubcat']);
        };
        // nav used for the navigation
        if(isset($query['nav'])){
            $segments[] = "nav-".$query['nav']; unset($query['nav']);
        }
        // orderid used for payment method
        if(isset($query['orderid'])){
            $segments[] = "orderid-".$query['orderid']; unset($query['orderid']);
        }
        // for used for payment method
        if(isset($query['for'])){
            $segments[] = "paymentfor-".$query['for']; unset($query['for']);
        }
        // packagefor used for payment method
        if(isset($query['packagefor'])){
            $segments[] = "packagefor-".$query['packagefor']; unset($query['packagefor']);
        }
        //itemid
        $session = Factory::getApplication()->getSession();
        if (isset($query['Itemid'])) {
            $session->set('JSItemid',$query['Itemid']);
        };
        
        return $segments;
    }

    function JSJobsParseRouteJ4(&$segments) {
        $vars = array();
        $count = count($segments);
        $router = new jsjobsRouterOld;
        if(strpos($segments[0],'task') === false) {
            $lresult = $router->parseLayout($segments[0]);
            $vars['option'] = "com_jsjobs";
            $vars['c'] = $lresult["c"];
            $vars['view'] = $lresult["view"];
            $vars['layout'] = $lresult["layout"];
            if(isset($lresult['jslayfor'])){
                $vars['jslayfor'] = 'detail'; // hardcode value to remove extra variable from url
            }
        } else {
                if(JVERSION < 4)
                    $vars['task'] = str_replace('task:','',$segments[0]);
                else
                    $vars['task'] = str_replace('task-','',$segments[0]);
            
				$vars['task'] = str_replace('task-','',$segments[0]); // overwrite
        }
        $i = 0;

        foreach ($segments AS $seg) {
            if ($i >= 1) {
                
                if(JVERSION < 4)
                    $array = explode(":", $seg);
                else
                    $array = explode("-", $seg);

				$array = explode("-", $seg); //overwrite
                $index = $array[0];
                //unset the current index
                unset($array[0]);
                if (isset($array[1])) $value = implode("-", $array);
                switch ($index) {
                    case "resume": $vars['rd'] = $value; break;
                    case "job": 
                        $vars['bd'] = $value; 
                    break;
                    case "email": $vars['email'] = $value; break;
                    case "letter": $vars['cl'] = $value; break;
                    case "package": $vars['gd'] = $value; break;
                    // case "pkgdetail": $vars['jslayfor'] = $value; break;
                    case "pkgfrom": $vars['pb'] = $router->parseId($value); break;
                    case "dept": $vars['pd'] = $value; break;
                    case "viewdept": $vars['vp'] = $value; break;
                    case "resumerssformat":
                    case "jobsrssformat":
                    case "format":
                        $vars['format'] = $value; break;
                    case "fld": $vars['fd'] = $value; break;
                    case "cn": $vars['country'] = $value; break;
                    case "st": $vars['state'] = $value; break;
                    case "cy": $vars['city'] = $value; break;
                    case "sort": $vars['sortby'] = $value; break;
                    case "jobseeker": $vars['userrole'] = 2;break;
                    case "employer": $vars['userrole'] = 3; break;
                    case "tab": $vars['ta'] = $router->parseId($value); break;
                    case "rsaved": $vars['rs'] = $value; break;
                    case "jsaved": $vars['js'] = $value; break;
                    case "subcat":$vars['resumesubcat'] = $value; break;
                    //from -nav
                    case "nav" : $vars['nav'] = $value; break;
                    //from -orderid
                    case "orderid" : $vars['orderid'] = $value; break;
                    //from -paymentfor
                    case "paymentfor" : $vars['for'] = $value; break;
                    //from -packagefor
                    case "packagefor" : $vars['packagefor'] = $value; break;
                    
                    case "cp":$vars['jsfrom'] = $value; break;
                    //jobs vars
                    case "jobtype":$vars['jt'] = $value; break;
                    case "category": $vars['cat'] = $value; break;
                    case "subcategory": $vars['jobsubcat'] = $value; break;
                    case "company": $vars['cd'] = $value; break;
                }
            }
            $i++;
        }
        return $vars;
        
    }



}

/*********************************************/



$componentPath =  'components/com_jsjobs/';
require_once $componentPath.'JSApplication.php';


//for Joomla 3
function JSJobsBuildRoute(&$query) {

    $segments = array();
    $router = new jsjobsRouterOld;
    if (isset($query['c'])) {
        $c = $query['c']; unset($query['c']);
    };
    if (isset($query['view'])) {
        $view = $query['view']; unset($query['view']);
    };
    if (isset($query['layout'])) {
        if(isset($query['jslayfor'])){
            $flag = true;
        }else{
            $flag = false;
        }
        $value = $router->buildLayout($query['layout'], $view,$flag);
        $layout = $query['layout'];
        $segments[] = $value; unset($query['layout']);
    };
    if (isset($query['task'])) {
        $segments[] = 'task-'.$query['task']; unset($query['task']);
    };
    
    if (isset($query['jsfrom'])) {
        $segments[] = "cp-" . $router->clean($query['jsfrom']); unset($query['jsfrom']);
    };
    //job type
    if (isset($query['jt'])) {
        $segments[] = "jobtype-" . $router->clean($query['jt']); unset($query['jt']);
    };
    //category
    if (isset($query['cat'])) {
        $segments[] = "category-" . $router->clean($query['cat']); unset($query['cat']);
    };
    //sub category
    if (isset($query['jobsubcat'])) {
        $segments[] = "subcategory-" . $router->clean($query['jobsubcat']); unset($query['jobsubcat']);
    };
    //company
    if (isset($query['cd'])) {
        $jsjobs_id = $query['cd'];
        $company_seo = JSModel::getJSModel('configurations')->getConfigValue('company_seo');
        if(!empty($company_seo)){
            $company_seo = JSModel::getJSModel('company')->makeCompanySeo($company_seo , $jsjobs_id);
            if($company_seo != ''){
                $id = $router->parseId($jsjobs_id);
                $jsjobs_id = $company_seo.'-'.$id;
            }
            $segments[] = "company-" . $router->clean($jsjobs_id); unset($query['cd']);
        }else{
            $segments[] = "company-" . $router->clean($query['cd']); unset($query['cd']);
        }
    };
    //resume
    if (isset($query['rd'])) {
        $jsjobs_id = $query['rd'];
        $resume_seo = JSModel::getJSModel('configurations')->getConfigValue('resume_seo');
        if(!empty($resume_seo)){
            $resume_seo = JSModel::getJSModel('resume')->makeResumeSeo($resume_seo , $jsjobs_id);
            if($resume_seo != ''){
                $id = $router->parseId($jsjobs_id);
                $jsjobs_id = $resume_seo.'-'.$id;
            }
            $segments[] = "resume-" . $router->clean($jsjobs_id); unset($query['rd']);
        }else{
            $segments[] = "resume-" . $router->clean($query['rd']); unset($query['rd']);
        }
    };
    //job
    if (isset($query['bd'])) {
        $job_seo = '';
        $jsjobs_id = $query['bd'];
        $job_seo = JSModel::getJSModel('configurations')->getConfigValue('job_seo');
        if(!empty($job_seo)){
            $job_seo = JSModel::getJSModel('job')->makeJobSeo($job_seo , $query['bd']);
            if($job_seo != ''){
                $id = $router->parseId($jsjobs_id);
                $jsjobs_id = $job_seo.'-'.$id;
            }
            $segments[] = "job-" . $router->clean($jsjobs_id); unset($query['bd']);
        }else{
            $segments[] = "job-" . $router->clean($query['bd']); unset($query['bd']);
        }
    };
    //email
    if (isset($query['email'])) {
        $segments[] = "email-" . $query['email']; unset($query['email']);
    };
    //cover letter
    if (isset($query['cl'])) {
        $segments[] = "letter-" . $router->clean($query['cl']); unset($query['cl']);
    };
    //package
    if (isset($query['gd'])) {
        $segments[] = "package-" . $router->clean($query['gd']); unset($query['gd']);
    };
    //package
    if (isset($query['jslayfor'])) {
        unset($query['jslayfor']);
    };
    //package buy now
    if (isset($query['pb'])) {
        $segments[] = "pkgfrom-" . $router->getPackageFromTitle($query['pb']) . "-" . $query['pb']; unset($query['pb']);
    };
    //department
    if (isset($query['pd'])) {
        $segments[] = "dept-" . $router->clean($query['pd']); unset($query['pd']);
    };
    //view department
    if (isset($query['vp'])) {
        $segments[] = "viewdept-" . $router->clean($query['vp']); unset($query['vp']);
    };
    //resume pdf output
    if (isset($query['format'])) {
        if (isset($layout) AND $layout == "rssresumes") {
            $segments[] = "resumerssformat-" . $query['format']; unset($query['format']);
        } elseif (isset($layout) AND $layout == "rssjobs") {
            $segments[] = "jobsrssformat-" . $query['format']; unset($query['format']);
        } else {
            $segments[] = "format-".$query['format']; unset($query['format']);
        }
    };
    //folder
    if (isset($query['fd'])) {
        $segments[] = "fld-" . $query['fd']; unset($query['fd']);
    };
    //country
    if (isset($query['country'])) {
        $segments[] = "cn-" . $query['country']; unset($query['country']);
    };
    //state
    if (isset($query['state'])) {
        $segments[] = "st-" . $query['state']; unset($query['state']);
    };
    //city
    if (isset($query['city'])) {
        $segments[] = "cy-" . $query['city']; unset($query['city']);
    };
    if (isset($query['sortby'])) {
        $segments[] = "sort-" . $query['sortby']; unset($query['sortby']);
    };
    // form user registration
    if (isset($query['userrole'])) {
        if ($query['userrole'] == "2") {
            $segments[] = "jobseeker-form"; unset($query['userrole']);
        } elseif ($query['userrole'] == "3") {
            $segments[] = "employer-form-"; unset($query['userrole']);
        }
    };
    // applied application tab
    if (isset($query['ta'])) {
        $segments[] = "tab-" . $router->getAppliedApplicationTabTitle($query['ta']) . "-" . $query['ta']; unset($query['ta']);
    };
    // view resume search
    if (isset($query['rs'])) {
        $segments[] = "rsaved-" . $query['rs']; unset($query['rs']);
    };
    // view job search
    if (isset($query['js'])) {
        $segments[] = "jsaved-" . $query['js']; unset($query['js']);
    };
    //resume by subcategory
    if (isset($query['resumesubcat'])) { //subcat
        $segments[] = "subcat-" . $query['resumesubcat']; unset($query['resumesubcat']);
    };
    // nav used for the navigation
    if(isset($query['nav'])){
        $segments[] = "nav-".$query['nav']; unset($query['nav']);
    }
    // orderid used for payment method
    if(isset($query['orderid'])){
        $segments[] = "orderid-".$query['orderid']; unset($query['orderid']);
    }
    // for used for payment method
    if(isset($query['for'])){
        $segments[] = "paymentfor-".$query['for']; unset($query['for']);
    }
    // packagefor used for payment method
    if(isset($query['packagefor'])){
        $segments[] = "packagefor-".$query['packagefor']; unset($query['packagefor']);
    }
    //itemid
    $session = Factory::getApplication()->getSession();
    if (isset($query['Itemid'])) {
        $session->set('JSItemid',$query['Itemid']);
    };
    return $segments;
}

function JSJobsParseRoute($segments) {
    $vars = array();
    $count = count($segments);
    $router = new jsjobsRouter;
    if(strpos($segments[0],'task') === false) {
        if($router->oldUrlController($segments[0])){ // old sef url 
            $vars = array();
            $vars['c'] = 'jsjobs';
            $layout="";
            if (isset($segments[1])) {
                $layout = $segments[1];
            }

            if($segments[0]== 'tk') {
                $vars['task'] = $segments[1];
            } else {
                $lresult = $router->parseOldLayout($layout);
                $vars['view'] = $lresult["view"];
                $vars['layout'] = $lresult["layout"];
            }
            $i = 0;
            foreach ($segments AS $seg) {
                if ($i >= 1) {
                    $array = explode(":", $seg);
                    $index = $array[0];
                    //unset the current index
                    unset($array[0]);
                    if (isset($array[1])) $value = implode("-", $array);

                    switch ($index) {
                        case "cat": $vars['cat'] = $value; break;
                        case "company": $vars['cd'] = $value; break;
                        case "resume": $vars['rd'] = $value; break;
                        case "job": $vars['bd'] = $value; break;
                        case "email": $vars['email'] = $value; break;
                        case "letter": $vars['cl'] = $value; break;
                        case "package": $vars['gd'] = $value; break;
                        case "pkgfrom": $vars['pb'] = $router->parseId($value); break;
                        case "dept": $vars['pd'] = $value; break;
                        case "viewdept": $vars['vp'] = $value; break;
                        case "subcategory": $vars['jobsubcat'] = $value; break;
                        case "resumerssformat":
                        case "jobsrssformat":
                        case "format":
                            $vars['format'] = $value; break;
                        case "fld": $vars['fd'] = $value; break;
                        case "cn": $vars['country'] = $value; break;
                        case "st": $vars['state'] = $value; break;
                        case "cy": $vars['city'] = $value; break;
                        case "sort": $vars['sortby'] = $value; break;
                        case "jobseekerregistration":
                        case "employerregistration":
                            $vars['userrole'] = $value; break;
                        case "tab": $vars['ta'] = $router->parseId($value); break;
                        case "rsaved": $vars['rs'] = $value; break;
                        case "jsaved": $vars['js'] = $value; break;
                        case "subcat":$vars['resumesubcat'] = $value; break;
                        //from -nav
                        case "nav" : $vars['nav'] = $value; break;
                        //from -orderid
                        case "orderid" : $vars['orderid'] = $value; break;
                        //from -paymentfor
                        case "paymentfor" : $vars['for'] = $value; break;
                        //from -packagefor
                        case "packagefor" : $vars['packagefor'] = $value; break;
                    }
                }
                $i++;
            }
            return $vars;
        }
        $lresult = $router->parseLayout($segments[0]);
        $vars['c'] = $lresult["c"];
        $vars['view'] = $lresult["view"];
        $vars['layout'] = $lresult["layout"];
        if(isset($lresult['jslayfor'])){
            $vars['jslayfor'] = 'detail'; // hardcode value to remove extra variable from url
        }
    } else {
        $vars['task'] = str_replace('task:','',$segments[0]);
    }
    $i = 0;
    foreach ($segments AS $seg) {
        if ($i >= 1) {
            
            $array = explode(":", $seg);
            $index = $array[0];
            //unset the current index
            unset($array[0]);
            if (isset($array[1])) $value = implode("-", $array);
            switch ($index) {
                case "resume": $vars['rd'] = $value; break;
                case "job": 
                    $vars['bd'] = $value; 
                break;
                case "email": $vars['email'] = $value; break;
                case "letter": $vars['cl'] = $value; break;
                case "package": $vars['gd'] = $value; break;
                // case "pkgdetail": $vars['jslayfor'] = $value; break;
                case "pkgfrom": $vars['pb'] = $router->parseId($value); break;
                case "dept": $vars['pd'] = $value; break;
                case "viewdept": $vars['vp'] = $value; break;
                case "resumerssformat":
                case "jobsrssformat":
                case "format":
                    $vars['format'] = $value; break;
                case "fld": $vars['fd'] = $value; break;
                case "cn": $vars['country'] = $value; break;
                case "st": $vars['state'] = $value; break;
                case "cy": $vars['city'] = $value; break;
                case "sort": $vars['sortby'] = $value; break;
                case "jobseeker": $vars['userrole'] = 2;break;
                case "employer": $vars['userrole'] = 3; break;
                case "tab": $vars['ta'] = $router->parseId($value); break;
                case "rsaved": $vars['rs'] = $value; break;
                case "jsaved": $vars['js'] = $value; break;
                case "subcat":$vars['resumesubcat'] = $value; break;
                //from -nav
                case "nav" : $vars['nav'] = $value; break;
                //from -orderid
                case "orderid" : $vars['orderid'] = $value; break;
                //from -paymentfor
                case "paymentfor" : $vars['for'] = $value; break;
                //from -packagefor
                case "packagefor" : $vars['packagefor'] = $value; break;
                
                case "cp":$vars['jsfrom'] = $value; break;
                //jobs vars
                case "jobtype":$vars['jt'] = $value; break;
                case "category": $vars['cat'] = $value; break;
                case "subcategory": $vars['jobsubcat'] = $value; break;
                case "company": $vars['cd'] = $value; break;
            }
        }
        $i++;
    }
    return $vars;
}

class jsjobsRouterOld {

    function oldUrlController($controller){
        $controllerarray = array('categroy','cities','common','company','coverletter','department','emailtemplate','employer','employerpackages','exportresume','filter','folder','job','jobalert','jobapply','jobsearch','jobseeker','jobseekerpackages','jobshortlist','jsjobs','message','payment','paymenthistory','purchasehistory','resume','resumesearch','rss','shortlistcandidate','subcategory','userrole');
        if(in_array($controller,$controllerarray)){
            return true;
        }else{
            return false;
        }
    }

    function buildLayout($value, $view, $layfor = false) {
        $returnvalue = "";
        switch ($value) {
            case "controlpanel":
                if ($view == 'jobseeker') $returnvalue = "jobseeker-controlpanel";
                else $returnvalue = "employer-controlpanel";
            break;
            case "formjob": $returnvalue = "form-job"; break;
            case "myjobs": $returnvalue = "my-jobs"; break;
            case "mycompanies": $returnvalue = "my-companies"; break;
            case "formcompany": $returnvalue = "form-company"; break;
            case "alljobsappliedapplications": $returnvalue = "applied-resume"; break;
            case "formdepartment": $returnvalue = "form-department"; break;
            case "mydepartments": $returnvalue = "my-departments"; break;
            case "formfolder": $returnvalue = "form-folder"; break;
            case "myfolders": $returnvalue = "my-folders"; break;
            case "empmessages": $returnvalue = "employer-messages"; break;
            case "resumesearch": $returnvalue = "resume-search"; break;
            case "my_resumesearches": $returnvalue = "resume-save-search"; break;
            case "resumebycategory": $returnvalue = "resume-by-category"; break;
            case "rssresumes": $returnvalue = "rss-resumes"; break;
            case "packages":
                if ($view == 'jobseekerpackages') $returnvalue = "jobseeker-packages";
                else $returnvalue = "employer-packages";
                break;
            case "employerpurchasehistory": $returnvalue = "employer-purchase-history"; break;
            case "jobseekerpurchasehistory": $returnvalue = "jobseeker-purchase-history"; break;
            case "my_stats":
                if ($view == 'jobseeker') $returnvalue = "jobseeker-stats";
                else $returnvalue = "employer-stats";
                break;
/*shoaib            case "package_details":
                if ($view == 'jobseekerpackages') $returnvalue = "jobseeker-package-details";
                else $returnvalue = "employer-package-details";
                break; */
            case "package_buynow":
                if ($view == 'jobseekerpackages'){
                    if($layfor == true){
                        $returnvalue = "jobseeker-detail-now";
                    }else{
                        $returnvalue = "jobseeker-buy-now";
                    }
                }else{
                    if($layfor == true){
                        $returnvalue = "employer-detail-now";                        
                    }else{
                        $returnvalue = "employer-buy-now";
                    }
                }
                break;
            case "view_job": $returnvalue = "job-detail"; break;
            case "view_company": $returnvalue = "company-detail"; break;
            case "view_department": $returnvalue = "department-detail"; break;
            case "viewfolder": $returnvalue = "folder-detail"; break;
            case "folder_resumes": $returnvalue = "folder-resumes"; break;
            case "job_messages": $returnvalue = "employer-job-messages"; break;
            case "send_message": $returnvalue = "employer-send-messages"; break;
            case "job_appliedapplications": $returnvalue = "job-applied-applications"; break;
            case "resume_searchresults": $returnvalue = "resume-search-results"; break;
            case "viewresumesearch": $returnvalue = "view-resume-search"; break;
            case "resume_bycategory": $returnvalue = "resume-category"; break;
//shoaib            case "resume_bysubcategory": $returnvalue = "resume-sub-category"; break;
            case "formjob_visitor": $returnvalue = "visitor-form-job"; break;
            /* Jobseeker layout start  */
            case "formresume": $returnvalue = "form-resume"; break;
            case "myresumes": $returnvalue = "my-resumes"; break;
            case "formcoverletter": $returnvalue = "form-cover-letter"; break;
            case "mycoverletters": $returnvalue = "my-cover-letters"; break;
            case "jsmessages": $returnvalue = "jobseeker-messages"; break;
            case "jobcat": $returnvalue = "job-by-categories"; break;
            case "jobs": $returnvalue = "newest-jobs"; break;
            case "listnewestjobs": $returnvalue = "newest-jobs"; break;
            case "myappliedjobs": $returnvalue = "my-applied-jobs"; break;
            case "jobsearch": $returnvalue = "search-job"; break;
            case "my_jobsearches": $returnvalue = "job-searches"; break;
            case "jobalertsetting": $returnvalue = "job-alert"; break;
            case "rssjobs": $returnvalue = "rss-jobs"; break;
            case "view_resume": $returnvalue = "resume-detail"; break;
            case "view_coverletters": $returnvalue = "cover-letters"; break;
            case "view_coverletter": $returnvalue = "cover-letter-detail"; break;
            case "resumepdf": $returnvalue = "resume-pdf"; break;
            case "company_jobs": $returnvalue = "jobs-by-company"; break;
            case "job_apply": $returnvalue = "job-apply"; break;
            case "list_jobs": $returnvalue = "jobs"; break;
            //shoaib case "list_subcategoryjobs": $returnvalue = "jobs-by-sub-category"; break;
            case "job_searchresults": $returnvalue = "job-search-results"; break;
            case "viewjobsearch": $returnvalue = "view-job-search"; break;
            case "jobalertunsubscribe": $returnvalue = "job-alert-unsubscribe"; break;
            case "rssjobs": $returnvalue = "rss-jobs"; break;
            case "userregister": $returnvalue = "registration"; break;
            case "successfullogin": $returnvalue = "successful-login"; break;
            case "new_injsjobs": $returnvalue = "new-in-jsjobs"; break;
            case "listallcompanies": $returnvalue = "companies"; break;
            case "listjobtypes": $returnvalue = "job-by-types"; break;
            case "list_jobshortlist": $returnvalue = "job-short-list"; break;
            case "userlogin": $returnvalue = "js-user-login"; break;
            case "adderasedatarequest": $returnvalue = "datacomplianceactions"; break;
        }
        return $returnvalue;
    }

    function parseLayout($value) {
        switch ($value) {

            case "jobseeker-controlpanel": 
            case "jobseeker:controlpanel": 
                $returnvalue["layout"] = "controlpanel"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "employer-controlpanel": 
            case "employer:controlpanel": $returnvalue["layout"] = "controlpanel"; $returnvalue["view"] = "employer"; $returnvalue["c"] = "employer"; break;
            
            case "form-job":
            case "form:job": $returnvalue["layout"] = "formjob"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            
            case "my-jobs": 
            case "my:jobs": $returnvalue["layout"] = "myjobs"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "my-companies":
            case "my:companies": $returnvalue["layout"] = "mycompanies"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            
            case "form-company": $returnvalue["layout"] = "formcompany"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "form:company": $returnvalue["layout"] = "formcompany"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "form-department": $returnvalue["layout"] = "formdepartment"; $returnvalue["view"] = "department"; $returnvalue["c"] = "department"; break;
            case "form:department": $returnvalue["layout"] = "formdepartment"; $returnvalue["view"] = "department"; $returnvalue["c"] = "department"; break;
            case "my-departments": $returnvalue["layout"] = "mydepartments"; $returnvalue["view"] = "department"; $returnvalue["c"] = "department"; break;
            case "my:departments": $returnvalue["layout"] = "mydepartments"; $returnvalue["view"] = "department"; $returnvalue["c"] = "department"; break;
            case "form-folder": $returnvalue["layout"] = "formfolder"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "form:folder": $returnvalue["layout"] = "formfolder"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "my-folders": $returnvalue["layout"] = "myfolders"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "my:folders": $returnvalue["layout"] = "myfolders"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "employer-messages": $returnvalue["layout"] = "empmessages"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;
            case "employer:messages": $returnvalue["layout"] = "empmessages"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;

            case "resume-search": $returnvalue["layout"] = "resumesearch"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume:search": $returnvalue["layout"] = "resumesearch"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume-save-search": $returnvalue["layout"] = "my_resumesearches"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume:save-search": $returnvalue["layout"] = "my_resumesearches"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume-by-category": $returnvalue["layout"] = "resumebycategory"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume:by-category": $returnvalue["layout"] = "resumebycategory"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "rss-resumes": $returnvalue["layout"] = "rssresumes"; $returnvalue["view"] = "rss"; $returnvalue["c"] = "rss"; break;
            case "rss:resumes": $returnvalue["layout"] = "rssresumes"; $returnvalue["view"] = "rss"; $returnvalue["c"] = "rss"; break;
            case "jobseeker-packages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages"; break;
            case "jobseeker:packages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages"; break;
            case "employer-packages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages"; break;
            case "employer:packages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages"; break;

            case "jobseeker-purchase-history": $returnvalue["layout"] = "jobseekerpurchasehistory"; $returnvalue["view"] = "purchasehistory"; $returnvalue["c"] = "purchasehistory"; break;
            case "jobseeker:purchase-history": $returnvalue["layout"] = "jobseekerpurchasehistory"; $returnvalue["view"] = "purchasehistory"; $returnvalue["c"] = "purchasehistory"; break;
            case "employer-purchase-history": $returnvalue["layout"] = "employerpurchasehistory"; $returnvalue["view"] = "purchasehistory"; $returnvalue["c"] = "purchasehistory"; break;
            case "employer:purchase-history": $returnvalue["layout"] = "employerpurchasehistory"; $returnvalue["view"] = "purchasehistory"; $returnvalue["c"] = "purchasehistory"; break;
            case "jobseeker-stats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "jobseeker:stats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "employer-stats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "employer"; $returnvalue["c"] = "employer"; break;
            case "employer:stats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "employer"; $returnvalue["c"] = "employer"; break;
            case "job-detail": $returnvalue["layout"] = "view_job"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "job:detail": $returnvalue["layout"] = "view_job"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "company-detail": $returnvalue["layout"] = "view_company"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "company:detail": $returnvalue["layout"] = "view_company"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "department-detail": $returnvalue["layout"] = "view_department"; $returnvalue["view"] = "department"; $returnvalue["c"] = "department"; break;
            case "department:detail": $returnvalue["layout"] = "view_department"; $returnvalue["view"] = "department"; $returnvalue["c"] = "department"; break;
            case "folder-detail": $returnvalue["layout"] = "viewfolder"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "folder:detail": $returnvalue["layout"] = "viewfolder"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;

            case "folder-resumes": $returnvalue["layout"] = "folder_resumes"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "folder:resumes": $returnvalue["layout"] = "folder_resumes"; $returnvalue["view"] = "folder"; $returnvalue["c"] = "folder"; break;
            case "employer-job-messages": $returnvalue["layout"] = "job_messages"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;
            case "employer:job-messages": $returnvalue["layout"] = "job_messages"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;
            case "employer-send-messages": $returnvalue["layout"] = "send_message"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;
            case "employer:send-messages": $returnvalue["layout"] = "send_message"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;
            case "job-applied-applications": $returnvalue["layout"] = "job_appliedapplications"; $returnvalue["view"] = "jobapply"; $returnvalue["c"] = "jobapply"; break;
            case "job:applied-applications": $returnvalue["layout"] = "job_appliedapplications"; $returnvalue["view"] = "jobapply"; $returnvalue["c"] = "jobapply"; break;
            case "resume-search-results": $returnvalue["layout"] = "resume_searchresults"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume:search-results": $returnvalue["layout"] = "resume_searchresults"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "view-resume-search": $returnvalue["layout"] = "viewresumesearch"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "view:resume-search": $returnvalue["layout"] = "viewresumesearch"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume-category": $returnvalue["layout"] = "resume_bycategory"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume:category": $returnvalue["layout"] = "resume_bycategory"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            //shoaib case "resume:sub-category": $returnvalue["layout"] = "resume_bysubcategory"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            //shoaib case "employer:package-details": $returnvalue["layout"] = "package_details"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages"; break;
            //shoaib case "jobseeker:package-details": $returnvalue["layout"] = "package_details"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages"; break;
            case "employer-detail-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages";$returnvalue["jslayfor"] = "detail"; break;
            case "employer:detail-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages";$returnvalue["jslayfor"] = "detail"; break;
            case "jobseeker-detail-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages";$returnvalue["jslayfor"] = "detail";break;
            case "jobseeker:detail-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages";$returnvalue["jslayfor"] = "detail";break;
            case "employer-buy-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages"; break;
            case "employer:buy-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "employerpackages"; $returnvalue["c"] = "employerpackages"; break;
            case "jobseeker-buy-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages"; break;
            case "jobseeker:buy-now": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "jobseekerpackages"; $returnvalue["c"] = "jobseekerpackages"; break;
            case "visitor-form-job": $returnvalue["layout"] = "formjob_visitor"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "visitor:form-job": $returnvalue["layout"] = "formjob_visitor"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;

            /* Jobseeker layout start  */
            case "form-resume": 
            case "form:resume": $returnvalue["layout"] = "formresume"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "my-resumes": $returnvalue["layout"] = "myresumes"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "my:resumes": $returnvalue["layout"] = "myresumes"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "form-cover-letter": $returnvalue["layout"] = "formcoverletter"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "form:cover-letter": $returnvalue["layout"] = "formcoverletter"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "my-cover-letters": $returnvalue["layout"] = "mycoverletters"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "my:cover-letters": $returnvalue["layout"] = "mycoverletters"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "jobseeker-messages": $returnvalue["layout"] = "jsmessages"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;
            case "jobseeker:messages": $returnvalue["layout"] = "jsmessages"; $returnvalue["view"] = "message"; $returnvalue["c"] = "message"; break;

            case "job-by-categories": $returnvalue["layout"] = "jobcat"; $returnvalue["view"] = "category";  $returnvalue["c"] = "category";break;
            case "job:by-categories": $returnvalue["layout"] = "jobcat"; $returnvalue["view"] = "category";  $returnvalue["c"] = "category";break;
            case "newest-jobs": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job";  $returnvalue["c"] = "job";break;
            case "newest:jobs": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job";  $returnvalue["c"] = "job";break;
            case "my-applied-jobs": $returnvalue["layout"] = "myappliedjobs"; $returnvalue["view"] = "jobapply"; $returnvalue["c"] = "jobapply"; break;
            case "my:applied-jobs": $returnvalue["layout"] = "myappliedjobs"; $returnvalue["view"] = "jobapply"; $returnvalue["c"] = "jobapply"; break;
            case "search-job": $returnvalue["layout"] = "jobsearch"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "search:job": $returnvalue["layout"] = "jobsearch"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;

            case "job-searches": $returnvalue["layout"] = "my_jobsearches"; $returnvalue["view"] = "jobsearch"; $returnvalue["c"] = "jobsearch"; break;
            case "job:searches": $returnvalue["layout"] = "my_jobsearches"; $returnvalue["view"] = "jobsearch"; $returnvalue["c"] = "jobsearch"; break;
            case "job-alert": $returnvalue["layout"] = "jobalertsetting"; $returnvalue["view"] = "jobalert"; $returnvalue["c"] = "jobalert"; break;
            case "job:alert": $returnvalue["layout"] = "jobalertsetting"; $returnvalue["view"] = "jobalert"; $returnvalue["c"] = "jobalert"; break;
            case "rss-jobs": $returnvalue["layout"] = "rssjobs"; $returnvalue["view"] = "rss"; $returnvalue["c"] = "rss"; break;
            case "rss:jobs": $returnvalue["layout"] = "rssjobs"; $returnvalue["view"] = "rss"; $returnvalue["c"] = "rss"; break;
            case "jobseeker-packages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "jobseeker:packages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "jobseeker-purchase-history": $returnvalue["layout"] = "purchasehistory"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "jobseeker:purchase-history": $returnvalue["layout"] = "purchasehistory"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "jobseeker-stats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "jobseeker:stats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "jobseeker"; $returnvalue["c"] = "jobseeker"; break;
            case "resume-detail": $returnvalue["layout"] = "view_resume"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;
            case "resume:detail": $returnvalue["layout"] = "view_resume"; $returnvalue["view"] = "resume"; $returnvalue["c"] = "resume"; break;

            case "cover-letters": $returnvalue["layout"] = "view_coverletters"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "cover:letters": $returnvalue["layout"] = "view_coverletters"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "cover-letter-detail": $returnvalue["layout"] = "view_coverletter"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "cover:letter-detail": $returnvalue["layout"] = "view_coverletter"; $returnvalue["view"] = "coverletter"; $returnvalue["c"] = "coverletter"; break;
            case "resume-pdf": $returnvalue["layout"] = "resumepdf"; $returnvalue["view"] = "output"; $returnvalue["c"] = "jsjobs"; break;
            case "resume:pdf": $returnvalue["layout"] = "resumepdf"; $returnvalue["view"] = "output"; $returnvalue["c"] = "jsjobs"; break;
            case "jobs-by-company": $returnvalue["layout"] = "company_jobs"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "jobs:by-company": $returnvalue["layout"] = "company_jobs"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "job-apply": $returnvalue["layout"] = "job_apply"; $returnvalue["view"] = "jobapply"; $returnvalue["c"] = "jobapply"; break;
            case "job:apply": $returnvalue["layout"] = "job_apply"; $returnvalue["view"] = "jobapply"; $returnvalue["c"] = "jobapply"; break;
            case "jobs": $returnvalue["layout"] = "list_jobs"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "jobs": $returnvalue["layout"] = "list_jobs"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            //shoaib case "jobs:by-sub-category": $returnvalue["layout"] = "list_subcategoryjobs"; $returnvalue["view"] = "subcategory"; $returnvalue["c"] = "subcategory"; break;

            case "job-search-results": $returnvalue["layout"] = "job_searchresults"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "job:search-results": $returnvalue["layout"] = "job_searchresults"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "view-job-search": $returnvalue["layout"] = "viewjobsearch"; $returnvalue["view"] = "jobsearch"; $returnvalue["c"] = "jobsearch"; break;
            case "view:job-search": $returnvalue["layout"] = "viewjobsearch"; $returnvalue["view"] = "jobsearch"; $returnvalue["c"] = "jobsearch"; break;
            case "job-alert-unsubscribe": $returnvalue["layout"] = "jobalertunsubscribe"; $returnvalue["view"] = "jobalert"; $returnvalue["c"] = "jobalert"; break;
            case "job:alert-unsubscribe": $returnvalue["layout"] = "jobalertunsubscribe"; $returnvalue["view"] = "jobalert"; $returnvalue["c"] = "jobalert"; break;
            case "rss-jobs": $returnvalue["layout"] = "rssjobs"; $returnvalue["view"] = "rss"; $returnvalue["c"] = "rss"; break;
            case "rss:jobs": $returnvalue["layout"] = "rssjobs"; $returnvalue["view"] = "rss"; $returnvalue["c"] = "rss"; break;
            case "registration": $returnvalue["layout"] = "userregister"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "registration": $returnvalue["layout"] = "userregister"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "successful-login": $returnvalue["layout"] = "successfullogin"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "successful:login": $returnvalue["layout"] = "successfullogin"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;

            case "new-in-jsjobs": $returnvalue["layout"] = "new_injsjobs"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "new:in-jsjobs": $returnvalue["layout"] = "new_injsjobs"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "companies": $returnvalue["layout"] = "listallcompanies"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "companies": $returnvalue["layout"] = "listallcompanies"; $returnvalue["view"] = "company"; $returnvalue["c"] = "company"; break;
            case "job-by-types": $returnvalue["layout"] = "listjobtypes"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "job:by-types": $returnvalue["layout"] = "listjobtypes"; $returnvalue["view"] = "job"; $returnvalue["c"] = "job"; break;
            case "job-short-list": $returnvalue["layout"] = "list_jobshortlist"; $returnvalue["view"] = "jobshortlist"; $returnvalue["c"] = "jobshortlist"; break;
            case "job:short-list": $returnvalue["layout"] = "list_jobshortlist"; $returnvalue["view"] = "jobshortlist"; $returnvalue["c"] = "jobshortlist"; break;
            case "js-user-login": $returnvalue["layout"] = "userlogin"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "js:user-login": $returnvalue["layout"] = "userlogin"; $returnvalue["view"] = "common"; $returnvalue["c"] = "common"; break;
            case "datacomplianceactions": $returnvalue["layout"] = "adderasedatarequest"; $returnvalue["view"] = "gdpr"; $returnvalue["c"] = "gdpr"; break;
        }
        if (isset($returnvalue)){
            return $returnvalue;
        }
    }

    function parseOldLayout($value) {
        //  $returnvalue = "";
        switch ($value) {
            case "controlpanel": $returnvalue["layout"] = "controlpanel"; $returnvalue["view"] = "jobseeker"; break;
            case "controlpannel": $returnvalue["layout"] = "controlpanel"; $returnvalue["view"] = "employer"; break;
            case "formjob": $returnvalue["layout"] = "formjob"; $returnvalue["view"] = "job"; break;
            case "myjobs": $returnvalue["layout"] = "myjobs"; $returnvalue["view"] = "job"; break;
            case "mycompanies": $returnvalue["layout"] = "mycompanies"; $returnvalue["view"] = "company"; break;
            case "formcompany": $returnvalue["layout"] = "formcompany"; $returnvalue["view"] = "company"; break;
            case "formdepartment": $returnvalue["layout"] = "formdepartment"; $returnvalue["view"] = "department"; break;
            case "mydepartments": $returnvalue["layout"] = "mydepartments"; $returnvalue["view"] = "department"; break;
            case "formfolder": $returnvalue["layout"] = "formfolder"; $returnvalue["view"] = "folder"; break;
            case "myfolders": $returnvalue["layout"] = "myfolders"; $returnvalue["view"] = "folder"; break;
            case "employermessages": $returnvalue["layout"] = "empmessages"; $returnvalue["view"] = "message"; break;
            case "resumesearch": $returnvalue["layout"] = "resumesearch"; $returnvalue["view"] = "resume"; break;
            case "resumesavesearch": $returnvalue["layout"] = "my_resumesearches"; $returnvalue["view"] = "resume"; break;
            case "resumebycategory": $returnvalue["layout"] = "resumebycategory"; $returnvalue["view"] = "resume"; break;
            case "rssresumes": $returnvalue["layout"] = "rssresumes"; $returnvalue["view"] = "rss"; break;
            case "jobseekerpackages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "jobseekerpackages"; break;
            case "employerpackages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "employerpackages"; break;
            case "jobseekerpurchasehistory": $returnvalue["layout"] = "jobseekerpurchasehistory"; $returnvalue["view"] = "purchasehistory"; break;
            case "employerpurchasehistory": $returnvalue["layout"] = "employerpurchasehistory"; $returnvalue["view"] = "purchasehistory"; break;
            case "jobseekerstats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "jobseeker"; break;
            case "employerstats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "employer"; break;
            case "viewjob": $returnvalue["layout"] = "view_job"; $returnvalue["view"] = "job"; break;
            case "viewcompany": $returnvalue["layout"] = "view_company"; $returnvalue["view"] = "company"; break;
            case "viewdepartment": $returnvalue["layout"] = "view_department"; $returnvalue["view"] = "department"; break;
            case "viewfolder": $returnvalue["layout"] = "viewfolder"; $returnvalue["view"] = "folder"; break;
            case "folderresumes": $returnvalue["layout"] = "folder_resumes"; $returnvalue["view"] = "folder"; break;
            case "employerjobmessages": $returnvalue["layout"] = "job_messages"; $returnvalue["view"] = "message"; break;
            case "employersendmessages": $returnvalue["layout"] = "send_message"; $returnvalue["view"] = "message"; break;
            case "jobappliedapplications": $returnvalue["layout"] = "job_appliedapplications"; $returnvalue["view"] = "jobapply"; break;
            case "resumesearchresults": $returnvalue["layout"] = "resume_searchresults"; $returnvalue["view"] = "resume"; break;
            case "viewresumesearch": $returnvalue["layout"] = "viewresumesearch"; $returnvalue["view"] = "resume"; break;
            case "resumecategory": $returnvalue["layout"] = "resume_bycategory"; $returnvalue["view"] = "resume"; break;
            case "resumesubcategory": $returnvalue["layout"] = "resume_bycategory"; $returnvalue["view"] = "resume"; break;
            case "employerpackagedetails": $returnvalue["layout"] = "package_details"; $returnvalue["view"] = "employerpackages"; break;
            case "jobseekerpackagedetails": $returnvalue["layout"] = "package_details"; $returnvalue["view"] = "jobseekerpackages"; break;
            case "employerbuynow": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "employerpackages"; break;
            case "jobseekerbuynow": $returnvalue["layout"] = "package_buynow"; $returnvalue["view"] = "jobseekerpackages"; break;
            case "formjobvisitor": $returnvalue["layout"] = "formjob_visitor"; $returnvalue["view"] = "job"; break;

            /* Jobseeker layout start  */
            case "formresume": $returnvalue["layout"] = "formresume"; $returnvalue["view"] = "resume"; break;
            case "myresumes": $returnvalue["layout"] = "myresumes"; $returnvalue["view"] = "resume"; break;
            case "formcoverletter": $returnvalue["layout"] = "formcoverletter"; $returnvalue["view"] = "coverletter"; break;
            case "mycoverletters": $returnvalue["layout"] = "mycoverletters"; $returnvalue["view"] = "coverletter"; break;
            case "jobseekermessages": $returnvalue["layout"] = "jsmessages"; $returnvalue["view"] = "message"; break;
            case "jobcategory": $returnvalue["layout"] = "jobcat"; $returnvalue["view"] = "category"; break;
            case "newestjobs": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job"; break;
            case "myappliedjobs": $returnvalue["layout"] = "myappliedjobs"; $returnvalue["view"] = "jobapply"; break;
            case "searchjob": $returnvalue["layout"] = "jobsearch"; $returnvalue["view"] = "job"; break;
            case "jobsearches": $returnvalue["layout"] = "my_jobsearches"; $returnvalue["view"] = "jobsearch"; break;
            case "jobalert": $returnvalue["layout"] = "jobalertsetting"; $returnvalue["view"] = "jobalert"; break;
            case "rssjobs": $returnvalue["layout"] = "rssjobs"; $returnvalue["view"] = "rss"; break;
            case "jobseekerpackages": $returnvalue["layout"] = "packages"; $returnvalue["view"] = "jobseeker"; break;
            case "jobseekerpurchasehistory": $returnvalue["layout"] = "purchasehistory"; $returnvalue["view"] = "jobseeker"; break;
            case "jobseekerstats": $returnvalue["layout"] = "my_stats"; $returnvalue["view"] = "jobseeker"; break;
            case "viewresume": $returnvalue["layout"] = "view_resume"; $returnvalue["view"] = "resume"; break;
            case "viewcoverletters": $returnvalue["layout"] = "view_coverletters"; $returnvalue["view"] = "coverletter"; break;
            case "viewcoverletter": $returnvalue["layout"] = "view_coverletter"; $returnvalue["view"] = "coverletter"; break;
            case "resumepdf": $returnvalue["layout"] = "resumepdf"; $returnvalue["view"] = "output"; break;
            case "companyjobs": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job"; break;
            case "jobapply": $returnvalue["layout"] = "job_apply"; $returnvalue["view"] = "jobapply"; break;
            case "listjobs": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job"; break;
            case "listsubcategoryjobs": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job"; break;
            case "jobsearchresults": $returnvalue["layout"] = "jobs"; $returnvalue["view"] = "job"; break;
            case "viewjobsearch": $returnvalue["layout"] = "viewjobsearch"; $returnvalue["view"] = "jobsearch"; break;
            case "jobalertunsubscribe": $returnvalue["layout"] = "jobalertunsubscribe"; $returnvalue["view"] = "jobalert"; break;
            case "rssjobs": $returnvalue["layout"] = "rssjobs"; $returnvalue["view"] = "rss"; break;
            case "registration": $returnvalue["layout"] = "userregister"; $returnvalue["view"] = "common"; break;
            case "successfullogin": $returnvalue["layout"] = "successfullogin"; $returnvalue["view"] = "common"; break;
            case "newinjsjobs": $returnvalue["layout"] = "new_injsjobs"; $returnvalue["view"] = "common"; break;
            case "userlogin": $returnvalue["layout"] = "userlogin"; $returnvalue["view"] = "common"; break;
            case "datacomplianceactions": $returnvalue["layout"] = "adderasedatarequest"; $returnvalue["controller"] = "gdpr"; break;
        }
        if (isset($returnvalue))
            return $returnvalue;
    }

    function getPackageTitle($id, $view) {
        $db = Factory::getDBO();
        if ($view == 'employer')
            $tablename = '#__js_job_employerpackages';
        else
            $tablename = '#__js_job_jobseekerpackages';
        $query = "SELECT LOWER(REPLACE(title,' ','-')) FROM `" . $tablename . "` WHERE id = " . (int) $id;
        $db->setQuery($query);
        $result = $db->loadResult();
        $result = strtolower(str_replace(' ', '-', $result));
        return $result;
    }

    function getPackageFromTitle($id) {
        if ($id == 1) $result = "packages";
        else $result = "package-detail";
        return $result;
    }

    function getAppliedApplicationTabTitle($id) {
        $result = '';
        switch ($id) {
            case 1: $result = "inbox"; break;
            case 2: $result = "spam"; break;
            case 3: $result = "hired"; break;
            case 4: $result = "rejected"; break;
            case 5: $result = "shortlist"; break;
        }
        return $result;
    }

    function parseId($value) {
        $id = explode("-", $value);
        $count = count($id);
        $id = (int) $id[($count - 1)];
        return $id;
    }

    function clean($string) {
        if($string == ''){
            return $string;
        }
        $string = strip_tags($string, "");
        $string = preg_replace("/[@!*%^(){}?&$\\\\#\\/]+/", "", $string);        
        $string = preg_replace("/[@!*&$\\\\#\\/]+/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }    
}
