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
use Joomla\CMS\HTML\HTMLHelper;

global $_client_auth_key;
if (Factory::getApplication()->isClient('site')) {
    $_M_configuration = $this->getmodel('configurations');
} else {
    //$_M_configuration = $this->getmodel('configuration');
    $_M_configuration = $this->getModel('Configuration', 'JSJobsModel');
}
$msg = Factory::getApplication()->input->get('msg');
$option = 'com_jsjobs';
$mainframe = Factory::getApplication();
HTMLHelper::_('jquery.framework');



$layoutName = Factory::getApplication()->input->get('layout', '');
if ($layoutName == '')
    $layoutName = $this->getLayout();
$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
$limitstart = $mainframe->getUserStateFromRequest('global.list.limitstart', 'limitstart', '', 'int');

$isNew = true;
$user = Factory::getUser();
$uid = $user->id;
// get configurations
$session = Factory::getApplication()->getSession();
$config = Array();
if ($_M_configuration->isTestMode()) {
    $config = null;
} else {
  $var = $session->get('jsjobconfig');
  if(!empty($var))
        $config = $var;
    else
        $config = null;
}
if (is_array($config) && sizeof($config) == 0) {
    $results = $_M_configuration->getConfig();
    foreach ($results as $result) {
        $config[$result->configname] = $result->configvalue;
    }
    $session->set('jsjobconfig',$config);
}

//this code is included at time of resuse of resume form code of site at admin site
$results = $_M_configuration->getConfig();
foreach ($results as $result) {
    $config[$result->configname] = $result->configvalue;
}
$session->set('jsjobconfig', $config);
//message system
require_once(JPATH_ROOT.'/components/com_jsjobs/views/messages.php');
$jsjobsmessages = new JSJobsMessages();
$this->jsjobsmessages = $jsjobsmessages;

$theme['title'] = 'jppagetitle';
$theme['heading'] = 'pageheadline';
$theme['sectionheading'] = 'sectionheadline';
$theme['sortlinks'] = 'sortlnks';
$theme['odd'] = 'odd';
$theme['even'] = 'even';
?>
