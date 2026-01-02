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
use Joomla\Filesystem\File;
use Joomla\CMS\Language\Text;

if (!defined('JCONST')) {
  define('JCONST', base64_decode("aHR0cDovL3d3dy5qb29tc2t5LmNvbS9pbmRleC5waHA/b3B0aW9uPWNvbV9qc3Byb2R1Y3RsaXN0aW5nJnRhc2s9YWFnamM="));
}
$language = Factory::getLanguage();
$language->load('com_jsjobs', JPATH_ADMINISTRATOR, null, true);
  // requires the default controller 
require_once (JPATH_COMPONENT.'/JSApplication.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_jsjobs/views/appconstants.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_jsjobs/views/actionmessages.php');
require_once (JPATH_COMPONENT.'/controller.php');

// Language base JS text
Text::script('Are you sure ?');
// END JS text
$document = Factory::getDocument();
$document->addScript('components/com_jsjobs/js/common.js');
function getCustomFieldClass() {
    include_once JPATH_COMPONENT_ADMINISTRATOR.'/include/classes/customfields.php';
    $obj = new customfields();
    return $obj;
}
function getJSJobsPHPFunctionsClass() {
    include_once JPATH_COMPONENT_ADMINISTRATOR.'/include/classes/jsjobsphpfunctions.php';
    $obj = new jsjobsphpfunctions();
    return $obj;
}

/*
  Checking if a controller was set, if so let's included it
 */
$task = Factory::getApplication()->input->getCmd('task');
$c = '';
if($task == "") $task = " "; // fix for php8.2
if (strstr($task, '.')) {
    $array = explode('.', $task);
    $c = $array[0];
    $task = $array[1];
} else {
    $c = Factory::getApplication()->input->getCmd('c', 'jsjobs');
    $task = Factory::getApplication()->input->getCmd('task', 'display');
}

if ($c != '') {
    $path = JPATH_COMPONENT.'/controllers/' . $c.'.php';
    jimport('joomla.filesystem.file');

    if (file_exists($path)) {
        require_once ($path);
    } else {
        //JError::raiseError('500', Text::_('Unknown Controller: <br>' . $c.':' . $path));
        throw new Exception(Text::_('Unknown Controller: <br>' . $c . ':' . $path),500);
    }
}
$controllername = 'JSJobsController'.$c;
$controller = new $controllername();
$controller->execute($task);
$controller->redirect();
?>
