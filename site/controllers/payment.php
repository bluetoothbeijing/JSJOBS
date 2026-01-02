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

class JSJobsControllerPayment extends JSController {

    var $_jinput = null;
    var $classobject;

    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        $this->_jinput = Factory::getApplication()->input;
        parent::__construct();
    }

    function redirectforpayment($packagefor, $packageid, $reference) {

        $employer_model = $this->getModel('Employer', 'JSJobsModel');
        $Itemid = Factory::getApplication()->input->get('Itemid');

        $host = $_SERVER['HTTP_HOST'];
        $self = $_SERVER['PHP_SELF'];
        $url = "http://$host$self";

        if ($packagefor == 1)
            $layout = 'employerpurchasehistory';
        else
            $layout = 'jobseekerpurchasehistory';

        $configuration = $this->getmodel('Configurations', 'JSJobsModel');
        $result = $configuration->getConfigByFor('payment');
        $employerpackages = $this->getmodel('Employerpackages', 'JSJobsModel');
        $packagehistory = $this->getmodel('Packagehistory', 'JSJobsModel');
        if ($packagefor == 1)
            $package = $employerpackages->getEmployerPackageInfoById($packageid); // employer        
        elseif ($packagefor == 2)
            $package = $packagehistory->getJobSeekerPackageInfoById($packageid); // jobseeker
        if (isset($package) == false) {
            JSJOBSActionMessages::setMessage(SAVE_ERROR, 'package','error');
            if ($packagefor == 1)
                $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_buynow&Itemid=' . $Itemid;
            elseif ($packagefor == 2)
                $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_buynow&Itemid=' . $Itemid;
            $this->setRedirect(Route::_($link , false));
        }
        if ($packagefor == 1)
            $defaultlink = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
        elseif ($packagefor == 2)
            $defaultlink = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;

        $purpose = $package->name;
        if ($package->price != 0) {
            $curdate = date('Y-m-d H:i:s');
            if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                if ($package->discounttype == 1) {
                    $discountamount = ($package->price * $package->discount) / 100;
                    $amount = $package->price - $discountamount;
                } else {
                    $amount = $package->price - $package->discount;
                }
            } else
                $amount = $package->price;
        }else {
            JSJOBSActionMessages::setMessage(SAVED, 'package','message');
            if ($packagefor == 1)
                $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
            elseif ($packagefor == 2)
                $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;

            $this->setRedirect(Route::_($link , false));
        }

        if ($packagefor == 1)
            $sopping_url = $url . '?option=com_jsjobs&view=employer&layout=packages&Itemid=' . $Itemid;
        elseif ($packagefor == 2)
            $sopping_url = $url . '?option=com_jsjobs&view=jobseeker&layout=packages&Itemid=' . $Itemid;

        if ($result['payment_method'] == 'paypal') { //paypal
            $paypal_account = $result['payment_paypalaccount'];
            $currency_code = $result['payment_currency'];
            $successeful_url = $url . '?option=com_jsjobs&task=payment.confirmpaymnt&for=' . $packagefor . '&fr=' . $reference . '&Itemid=' . $Itemid;
            $cancel_url = $result['payment_cancelurl'];
            $show_description = $result['payment_showdescription'];
            $description = $result['payment_description'];
            $testmode = $result['payment_test_mode'];
            if ($result['payment_showfooter'] == '1')
                $show_footer = 'show_footer';
            else
                $show_footer = 'hide_footer';

            if ($testmode == '1')
                $act = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            else
                $act = "https://www.paypal.com/cgi-bin/webscr";
            ?>
            <form action="<?php echo $act; ?>" method="post" name="adminForm" >
                <input type="hidden" name="business" value="<?php echo $paypal_account; ?>">
                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="add" value="1">
                <input type="hidden" name="item_name" value="<?php echo $purpose; ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
                <input type="hidden" name="return" value="<?php echo $successeful_url; ?>">
                <input type="hidden" name="notify_url" value="<?php echo $successeful_url; ?>">
                <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
                <input type="hidden" name="rm" value="2">
                <input type="hidden" name="shopping_url" value="<?php echo $sopping_url; ?>"><!-- Display the payment button. -->
                <script language=Javascript>
                    //document.adminForm.shopping_url.value = window.location.href;
                    document.adminForm.submit();
                </script>
            </form>
            <?php
        }elseif ($result['payment_method'] == 'fastspring') { //fast spring
            if ($package->fastspringlink)
                $this->setRedirect($package->fastspringlink); // not empty
            else{
                JSJOBSActionMessages::setMessage(SAVED, 'package','message');
                $this->setRedirect(Route::_($defaultlink ,false));
            }
        }elseif ($result['payment_method'] == 'authorizenet') { //authorize.net
            //<form name="PrePage" method = "post" action = "https://scotest.authorize.net/payment/CatalogPayment.aspx">
            ?>
            <form name="PrePage" method = "post" action = "https://Simplecheckout.authorize.net/payment/CatalogPayment.aspx">
                <input type = "hidden" name = "LinkId" value ="<?php echo $package->otherpaymentlink; ?>" />
                <script language=Javascript>
                    document.PrePage.submit();
                </script>
            </form>
        <?php } elseif ($result['payment_method'] == 'pagseguro') { //pagseguro 
            ?>
            <form name="pagseguro"  method="post" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml">
                <input type="hidden" name="email_cobranca" value="<?php echo $result['pagseguro_email']; ?>">
                <input type="hidden" name="tipo" value="CP">
                <input type="hidden" name="moeda" value="BRL">

                <input type="hidden" name="item_id_1" value="1">
                <input type="hidden" name="item_descr_1" value="<?php echo $package->title; ?>">
                <input type="hidden" name="item_quant_1" value="1">
                <input type="hidden" name="item_valor_1" value="<?php echo number_format($amount, 2); ?>">
                <input type="hidden" name="item_frete_1" value="0">
                <input type="hidden" name="item_peso_1" value="0">


                <input type="hidden" name="tipo_frete" value="EN">
                <script language=Javascript>
                    document.pagseguro.submit();
                </script>
            </form>
            <?php
        } elseif ($result['payment_method'] == '2checkout') { //2checkout
            if ($package->otherpaymentlink)
                $this->setRedirect($package->otherpaymentlink); // not empty
            else{
                JSJOBSActionMessages::setMessage(SAVED, 'package','message');
                $this->setRedirect(Route::_($defaultlink ,false));
            }
        }elseif ($result['payment_method'] == 'other') { //other
            if ($package->otherpaymentlink)
                $this->setRedirect($package->otherpaymentlink); // not empty
            else{
                JSJOBSActionMessages::setMessage(SAVED, 'package','message');
                $this->setRedirect(Route::_($defaultlink ,false));
            }
        }else {
            JSJOBSActionMessages::setMessage(SAVED, 'package','message');
            $this->setRedirect(Route::_($defaultlink ,false));
        }
    }

    function confirmpaymnt() { //confirm paypal payment
        $common_model = $this->getModel('Common', 'JSJobsModel');
        $employer_model = $this->getModel('Employer', 'JSJobsModel');
        $jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
        $Itemid = Factory::getApplication()->input->get('Itemid');
        $configuration = $this->getmodel('Configurations', 'JSJobsModel');
        $result = $configuration->getConfigByFor('payment');
        $result = $common_model->getConfigByFor('payment');
        // paypal code

        if ($this->_jinput->get('fr','') != "") {
            $referenceid = $this->_jinput->get('fr');
        }
        if ($this->_jinput->get('for','') != "")
            $for = $this->_jinput->get('for','');
        $req = 'cmd=_notify-synch';

        if ($this->_jinput->get('tx' , '') != "")
            $tx_token = $this->_jinput->get('tx' , '');

        $auth_token = $result['payment_authtoken'];
        $req .= "&tx=$tx_token&at=$auth_token";

        // post back to PayPal system to validate
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $testmode = $result['payment_test_mode'];
        if ($testmode == '1')
            $act = "www.sandbox.paypal.com";
        else
            $act = "www.paypal.com";
        //$fp = fsockopen ("$act", 80, $errno, $errstr, 30);
        $fp = fsockopen('ssl://' . $act, "443", $err_num, $err_str, 30);

        // If possible, securely post back to paypal using HTTPS
        // Your PHP server will need to be SSL enabled
        // $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        if (!$fp) {
            // HTTP ERROR
        } else {
            fputs($fp, $header . $req);
            // read the body data
            $res = '';
            $headerdone = false;
            while (!feof($fp)) {
                $line = fgets($fp, 1024);
                if (strcmp($line, "\r\n") == 0) {
                    // read the header
                    $headerdone = true;
                } else if ($headerdone) {
                    // header has been read. now read the contents
                    $res .= $line;
                }
            }

            // parse the data
            $lines = explode("\n", $res);
            $keyarray = array();
            $paypalstatus = $lines[0];
            $date = date('Y-m-d H:i:s');
            $status = 1;
            if (strcmp($lines[0], "SUCCESS") == 0) {
                for ($i = 1; $i < count($lines); $i++) {
                    list($key, $val) = explode("=", $lines[$i]);
                    $keyarray[urldecode($key)] = urldecode($val);
                }
                // check the payment_status is Completed
                // check that txn_id has not been previously processed
                // check that receiver_email is your Primary PayPal email
                // check that payment_amount/payment_currency are correct
                // process payment
                $firstname = $keyarray['first_name'];
                $lastname = $keyarray['last_name'];
                $itemname = $keyarray['item_name'];
                $amount = $keyarray['payment_gross'];
                $email = $keyarray['payer_email'];

                $itemname = $keyarray['item_name1'];

                if ($for == 1)
                    $return_value = $employer_model->updateEmployerPackageHistory($firstname, $lastname, $email, $amount, $referenceid, $tx_token, $date, $paypalstatus, $status);
                elseif ($for == 2)
                    $return_value = $jobseeker_model->updateJobSeekerPackageHistory($firstname, $lastname, $email, $amount, $referenceid, $tx_token, $date, $paypalstatus, $status);

                JSJOBSActionMessages::setMessage('Thank you For buying pacakge', 'package','message');
                if ($for == 1)
                    $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
                elseif ($for == 2)
                    $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
            }
            else if (strcmp($lines[0], "FAIL") == 0) {
                JSJOBSActionMessages::setMessage('We are unable to verify your payment', 'package','message');
                if ($for == 1)
                    $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
                elseif ($for == 2)
                    $link = 'index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=' . $layout . '&Itemid=' . $Itemid;
            }
        }

        fclose($fp);
        $this->setRedirect(Route::_($link , false));
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
     

