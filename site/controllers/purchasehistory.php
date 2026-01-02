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
use Joomla\CMS\Router\Route;    

jimport('joomla.application.component.controller');

class JSJobsControllerPurchaseHistory extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function savejobseekerpayment() { //save job seeker payment
        $data = Factory::getApplication()->input->post->getArray();
        $packageid = $data['packageid'];
        $packageid = $data['packageid'];
        $payment_method = $data['paymentmethod'];
        $package_for = $data['packagefor'];
        ?>
        <div width="100%" align="center">
            <br><br><br><h2>Please wait</h2>
            <img src="components/com_jsjobs/images/working.gif" border="0" >
        </div>
        <?php
        $reser_med = date('misyHdmy');
        $reser_med = md5($reser_med);
        $reser_med = md5($reser_med);
        $reser_med1 = substr($reser_med, 0, 5);
        $reser_med2 = substr($reser_med, 7, 13);// was wrongly concatting varibale to this
        $string = md5(time());
        $string = md5(time());
        $reser_start = substr($string, 0, 3);
        $reser_end = substr($string, 3, 2);
        $reference = $reser_start . $reser_med1 . $reser_med2 . $reser_end;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $packagehistory = $this->getmodel('Packagehistory', 'JSJobsModel');
        $return_value = $packagehistory->storePackageHistory(0, $data);
        if ($return_value != false) {
            $paymentfor = Factory::getApplication()->input->get('paymentmethod', '');
            $packagefor = Factory::getApplication()->input->get('packagefor', '');
            if ($packagefor == 1)
                $layout = 'employerpurchasehistory';
            else
                $layout = 'jobseekerpurchasehistory';
            if ($paymentfor != 'free') {
                JSJOBSActionMessages::setMessage(SAVED, 'package','message');
                $this->setRedirect('index.php?option=com_jsjobs&task=payment.onorder&orderid=' . $return_value . '&for=' . $paymentfor . '&packagefor=' . $packagefor);
            } else {
                if ($return_value === 'cantgetpackagemorethenone') {
                    JSJOBSActionMessages::setMessage('Can not get free package more then once', 'package','message');
                    $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $Itemid;
                    $this->setRedirect(Route::_($link , false));
                } elseif ($return_value == false) {
                    JSJOBSActionMessages::setMessage('Error in saving package', 'package','message');
                    $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_buynow&Itemid=' . $Itemid;
                    $this->setRedirect(Route::_($link , false));
                } else {
                    JSJOBSActionMessages::setMessage('Package saved', 'package','message');
                    $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
                    $this->setRedirect(Route::_($link , false));
                }
            }
        } else {
            JSJOBSActionMessages::setMessage('Error in saving package', 'package','message');
            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseekerpackages&layout=packages&Itemid=' . $Itemid;
            $this->setRedirect(Route::_($link , false));
        }
    }

    function saveemployerpayment() { //save employer payment
        $data = Factory::getApplication()->input->post->getArray();
        $packageid = $data['packageid'];
        $payment_method = $data['paymentmethod'];
        $package_for = $data['packagefor'];
        ?>
        <div width="100%" align="center">
            <br><br><br><h2>Please wait</h2>
            <img src="components/com_jsjobs/images/working.gif" border="0" >
        </div>
        <?php
        $reser_med = date('misyHdmy');
        $reser_med = md5($reser_med);
        $reser_med = md5($reser_med);
        $reser_med1 = substr($reser_med, 0, 5);
        $reser_med2 = substr($reser_med, 7, 13);// was wrongly concatting varibale to this
        $string = md5(time());
        $string = md5(time());
        $reser_start = substr($string, 0, 3);
        $reser_end = substr($string, 3, 2);
        $reference = $reser_start . $reser_med1 . $reser_med2 . $reser_end;
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $packagehistory = $this->getmodel('Packagehistory', 'JSJobsModel');
        $return_value = $packagehistory->storePackageHistory(0, $data);
        if ($return_value != false) {
            $paymentfor = Factory::getApplication()->input->get('paymentmethod', '');
            $packagefor = Factory::getApplication()->input->get('packagefor', '');
            if ($packagefor == 1)
                $layout = 'employerpurchasehistory';
            else
                $layout = 'jobseekerpurchasehistory';
            if ($paymentfor != 'free') {
                JSJOBSActionMessages::setMessage(SAVED, 'package','message');
                $this->setRedirect(Route::_('index.php?option=com_jsjobs&task=payment.onorder&orderid=' . $return_value . '&for=' . $paymentfor . '&packagefor=' . $packagefor ,false));
            } else {
                if ($return_value === 'cantgetpackagemorethenone') {
                    JSJOBSActionMessages::setMessage('Can not get free package more then once', 'package','message');
                    $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=' . $Itemid;
                    $this->setRedirect(Route::_($link , false));
                } elseif ($return_value == false) {
                    JSJOBSActionMessages::setMessage('Error in saving package', 'package','message');
                    $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=package_buynow&Itemid=' . $Itemid;
                    $this->setRedirect(Route::_($link , false));
                } else {
                    JSJOBSActionMessages::setMessage(SAVED, 'package','message');
                    $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
                    $this->setRedirect(Route::_($link , false));
                }
            }
        } else {
            JSJOBSActionMessages::setMessage('Error in saving package', 'package','message');
            $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=package_buynow&Itemid=' . $Itemid;
            $this->setRedirect(Route::_($link , false));
        }
    }

    function display($cachable = false, $urlparams = false) { // correct employer controller display function manually.
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'default');
        $layoutName = Factory::getApplication()->input->get('layout', 'default');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}
?>
    
