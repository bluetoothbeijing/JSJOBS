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

jimport('joomla.application.component.controller');

class JSJobsControllerPaymenthistory extends JSController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function jobseekerpaymentapprove() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $packageid = $cid[0];
        $paymenthistory_model = $this->getmodel('Paymenthistory', 'JSJobsModel');
        $return_value = $paymenthistory_model->jobseekerPaymentApprove($packageid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(APPROVED, 'payment','message');
        } else
            JSJOBSActionMessages::setMessage(APPROVE_ERROR, 'payment','error');

        $link = 'index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymenthistory';
        $this->setRedirect($link);
    }

    function jobseekerpaymentereject() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $packageid = $cid[0];
        $paymenthistory_model = $this->getmodel('Paymenthistory', 'JSJobsModel');
        $return_value = $paymenthistory_model->jobseekerPaymentReject($packageid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(REJECTED, 'payment','message');
        } else
            JSJOBSActionMessages::setMessage(REJECT_ERROR, 'payment','error');

        $link = 'index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymenthistory';
        $this->setRedirect($link);
    }

    function employerpaymentapprove() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $packageid = $cid[0];
        $paymenthistory_model = $this->getmodel('Paymenthistory', 'JSJobsModel');
        $return_value = $paymenthistory_model->employerPaymentApprove($packageid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(APPROVED, 'payment','message');
        } else {
            JSJOBSActionMessages::setMessage(APPROVE_ERROR, 'payment','error');
        }

        $link = 'index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory';
        $this->setRedirect($link);
    }

    function employerpaymentreject() {
        $cid = Factory::getApplication()->input->get('cid', array(), '', 'array');
        $packageid = $cid[0];
        $paymenthistory_model = $this->getmodel('Paymenthistory', 'JSJobsModel');
        $return_value = $paymenthistory_model->employerPaymentReject($packageid);
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage(REJECTED, 'payment','message');
        } else
            JSJOBSActionMessages::setMessage(REJECT_ERROR, 'payment','error');

        $link = 'index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory';
        $this->setRedirect($link);
    }

    function saveuserpackage() {
        $userrole = Factory::getApplication()->input->get('userrole');
        $paymenthistory_model = $this->getmodel('Paymenthistory', 'JSJobsModel');
        $return_value = $paymenthistory_model->storeUserPackage();
        if ($return_value == 1) {
            JSJOBSActionMessages::setMessage('Package assigned to user', 'payment','message');
        } elseif ($return_value == 5) {
            JSJOBSActionMessages::setMessage('Cannot assign free package more than once', 'payment','warning');
        } else {
            JSJOBSActionMessages::setMessage('Error While assigning package to user', 'payment','error');
        }
        if ($userrole == 1)
            $link = 'index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory';
        else
            $link = 'index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymenthistory';

        $this->setRedirect($link);
    }

    function edit() {
        Factory::getApplication()->input->set('layout', 'assignpackage');
        Factory::getApplication()->input->set('view', 'paymenthistory');
        Factory::getApplication()->input->set('c', 'paymenthistory');
        $this->display();
    }

    function cancelemployerpaymenthistory() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'payment','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory');
    }

    function canceljobseekerpaymenthistory() {
        JSJOBSActionMessages::setMessage(OPERATION_CANCELLED, 'payment','notice');
        $this->setRedirect('index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymenthistory');
    }

    function display($cachable = false, $urlparams = false) {
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'paymenthistory');
        $layoutName = Factory::getApplication()->input->get('layout', 'paymenthistory');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $jobsharing_model = $this->getModel('Jobsharing', 'JSJobsModel');
        $configuration_model = $this->getModel('Configuration', 'JSJobsModel');
        $paymenthistory_model = $this->getModel('Paymenthistory', 'JSJobsModel');
        $view->setModel($jobsharing_model, true);
        $view->setModel($configuration_model);
        $view->setModel($paymenthistory_model);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
