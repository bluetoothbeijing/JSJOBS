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

jimport('joomla.application.component.model');
jimport('joomla.html.html');
$option = Factory::getApplication()->input->get('option', 'com_jsjobs');

class JSJobsModelUser extends JSModel {

    var $_uid = null;

    function __construct() {
        parent::__construct();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getUserPackageDetailByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $userrole = $this->getJSModel('userrole')->getUserRoleByUid($uid);
        if ($userrole == 1) { // employer
        } elseif ($userrole == 2) { // job seeker
        } else { // unknown user
        }
    }

    function getUidAndRoleByPurchasehistoryId($id) {
        if (!is_numeric($id))
            return false;
        $db = Factory::getDbo();
        $query = "SELECT purchase.uid, role.role
                    FROM `#__js_job_paymenthistory` AS purchase
                    JOIN `#__js_job_userroles` AS role ON role.uid = purchase.uid
                    WHERE purchase.id =". $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

}
?>
    
