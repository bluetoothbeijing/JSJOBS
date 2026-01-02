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

/*
 * Make sure entry is initiated by Joomla!
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_jsjobs')) {
//  return JError::raiseWarning(404, Text::_('Jerror Alertnoauthor'));
}

$version = new Version;
$joomla = $version->getShortVersion();
$jversion = substr($joomla, 0, 3);
if (!defined('JVERSION')) {
    define('JVERSION', $jversion);
}

/*
 * Require our default controller - used if 'c' is not assigned
 * - c is the controller to use (should probably rename to 'controller')
 */
require_once (JPATH_COMPONENT . '/JSApplication.php');
require_once (JPATH_COMPONENT . '/views/appconstants.php');
require_once (JPATH_COMPONENT . '/views/layout.php');
require_once (JPATH_COMPONENT . '/views/actionmessages.php');
require_once (JPATH_COMPONENT . '/controller.php');

// Language base JS text
Text::script('Are you sure ?');
// END JS text

require_once (JPATH_COMPONENT . '/include/classes/customfields.php');
function getCustomFieldClass() {
    include_once JPATH_COMPONENT_ADMINISTRATOR.'/include/classes/customfields.php';
    $obj = new customfields();
    return $obj;
}

function getAIFunctionsClass() {
    include_once JPATH_COMPONENT_ADMINISTRATOR.'/include/classes/aifunctions.php';
    $obj = new aifunctions();
    return $obj;
}

function getJSJobsPHPFunctionsClass() {
    include_once JPATH_COMPONENT_ADMINISTRATOR.'/include/classes/jsjobsphpfunctions.php';
    $obj = new jsjobsphpfunctions();
    return $obj;
}

// include admin JS
$document = Factory::getDocument();
if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/include/js/jquery.js');
} elseif (JVERSION < 4)  {
    HTMLHelper::_('bootstrap.framework');
    HTMLHelper::_('jquery.framework');
}
$document->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/common.js');
$document->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/tree.js');
// include admin css
$document->addStyleSheet('components/com_jsjobs/include/installer.css');
if(JVERSION < 4){
	$document->addStyleSheet(Uri::root().'administrator/components/com_jsjobs/include/css/bootstrap.min.css');
}
$document->addStyleSheet(Uri::root().'administrator/components/com_jsjobs/include/css/admin.css');
$document->addStyleSheet(Uri::root().'administrator/components/com_jsjobs/include/css/menu.css');
$language = Factory::getLanguage();
if($language->isRtl()){
  $document->addStyleSheet(Uri::root().'administrator/components/com_jsjobs/include/css/admin_rtl.css');
}
/*
 * Checking if a controller was set, if so let's included it
 */
$jinput = Factory::getApplication()->input;
$task = $jinput->getCmd('task');
$c = '';
if($task == "") $task = " "; // fix for php8.2
if (strstr($task, '.')) {
    $array = explode('.', $task);
    $c = $array[0];
    $task = $array[1];
} else {
    $c = $jinput->getCmd('c', 'jsjobs');
    $task = $jinput->getCmd('task', 'display');
}
if ($c != '') {
    $path = JPATH_COMPONENT . '/controllers/' . $c . '.php';
    //echo 'Path'.$path;
    jimport('joomla.filesystem.file');
    /*
     * Checking if the file exists and including it if it does
     */
    if (file_exists($path)) {
        require_once ($path);
    } else {
		throw new Exception(Text::_('Unknown Controller: <br>' . $c . ':' . $path),500);
    }
}
/*
 * Define the name of the controller class we're going to use
 * Instantiate a new instance of the controller class
 * Execute the task being called (default to 'display')
 * If it's set, redirect to the URI
 */
$c = 'JSJobsController' . $c;
$controller = new $c ();
$controller->execute($task);
$controller->redirect();
?>
