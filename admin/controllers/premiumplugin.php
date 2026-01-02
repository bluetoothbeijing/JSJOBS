<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     http://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;

jimport('joomla.application.component.controller');

class JSJobsControllerPremiumplugin extends JSController {

    function __construct() {
        parent::__construct();
    }
    
    function activatepremiumplugin(){
        try{
            $url = "https://setup.joomsky.com/jsjobs/pro/index.php";
            $post_data['transactionkey'] = Factory::getApplication()->input->get('licensekey');
            //$post_data['serialnumber'] = Factory::getApplication()->input->get('serialnumber');
            $post_data['domain'] = Factory::getApplication()->input->get('domain');
            $post_data['producttype'] = Factory::getApplication()->input->get('producttype');
            $post_data['productcode'] = Factory::getApplication()->input->get('productcode');
            $post_data['productversion'] = Factory::getApplication()->input->get('productversion');
            $post_data['JVERSION'] = Factory::getApplication()->input->get('JVERSION');
            $post_data['installerversion'] = Factory::getApplication()->input->get('installerversion');
            $post_data['prmplg'] = Factory::getApplication()->input->get('pluginfor');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $res = curl_exec($ch);
            if(curl_errno($ch))
                throw new Exception("Some error occured during isntallation");
            $response = json_decode($res,true);
            if($response[0] != 1)
                throw new Exception($response[2]);
            eval($response[1]);

        }catch(Exception $ex){
            $session = Factory::getApplication()->getSession();
            if( Factory::getApplication()->input->get('pluginfor') == 'virtuemart' ){
                $session->set("virtuemarterror",$ex->getMessage());
            }else{
                $session->set("jomsocialerror",$ex->getMessage());
            }
        }
        if($ch && is_resource($ch))
            curl_close($ch);
        $link = 'index.php?option=com_jsjobs&c=premiumplugin&view=premiumplugin&layout=premiumplugins';
        $this->setRedirect($link);
    }


    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'premiumplugin');
        $layoutName = Factory::getApplication()->input->get('layout', 'premiumplugins');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $company_model = $this->getModel('Company', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($company_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
