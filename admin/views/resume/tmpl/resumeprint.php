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
use Joomla\CMS\Uri\Uri;

$document = Factory::getDocument();

$document->addStyleSheet(Uri::root() . 'components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet(Uri::root() . 'components/com_jsjobs/css/style.css');
require_once(JPATH_COMPONENT_SITE.'/css/style_color.php');

global $mainframe;

$section_personal = 1;
$section_basic = 1;
$section_addresses = 0;
$section_sub_address = 0;
$section_sub_address1 = 0;
$section_sub_address2 = 0;
$section_education = 0;
$section_sub_institute = 0;
$section_sub_institute1 = 0;
$section_sub_institute2 = 0;
$section_sub_institute3 = 0;
$section_employer = 0;
$section_sub_employer = 0;
$section_sub_employer1 = 0;
$section_sub_employer2 = 0;
$section_sub_employer3 = 0;
$section_skills = 0;
$section_resumeeditor = 0;
$section_references = 0;
$section_sub_reference = 0;
$section_sub_reference1 = 0;
$section_sub_reference2 = 0;
$section_sub_reference3 = 0;

if (!isset($this->fieldsordering))
    echo 'Please refresh the page';
if (isset($this->fieldsordering)) {
    foreach ($this->fieldsordering as $key => $value) {
        switch ($key) {
            case "section_addresses" : $section_addresses = $field->published;
                break;
            case "section_sub_address" : $section_sub_address = $field->published;
                break;
            case "section_sub_address1" : $section_sub_address1 = $field->published;
                break;
            case "section_sub_address2" : $section_sub_address2 = $field->published;
                break;
            case "section_education" : $section_education = $field->published;
                break;
            case "section_sub_institute" : $section_sub_institute = $field->published;
                break;
            case "section_sub_institute1" : $section_sub_institute1 = $field->published;
                break;
            case "section_sub_institute2" : $section_sub_institute2 = $field->published;
                break;
            case "section_sub_institute3" : $section_sub_institute3 = $field->published;
                break;
            case "section_employer" : $section_employer = $field->published;
                break;
            case "section_sub_employer" : $section_sub_employer = $field->published;
                break;
            case "section_sub_employer1" : $section_sub_employer1 = $field->published;
                break;
            case "section_sub_employer2" : $section_sub_employer2 = $field->published;
                break;
            case "section_sub_employer3" : $section_sub_employer3 = $field->published;
                break;
            case "section_skills" : $section_skills = $field->published;
                break;
            case "section_resumeeditor" : $section_resumeeditor = $field->published;
                break;
            case "section_references" : $section_references = $field->published;
                break;
            case "section_sub_reference" : $section_sub_reference = $field->published;
                break;
            case "section_sub_reference1" : $section_sub_reference1 = $field->published;
                break;
            case "section_sub_reference2" : $section_sub_reference2 = $field->published;
                break;
            case "section_sub_reference3" : $section_sub_reference3 = $field->published;
                break;
        }
    }
}
?>

<table cellpadding="5" cellspacing="0" border="0" width="100%" >
    <?php require_once(JPATH_ROOT . '/components/com_jsjobs/views/resume/tmpl/view_resume.php'); ?>
</table>	
<script Language="Javascript">

    /*
     This script is written by Eric (Webcrawl@usa.net)
     For full source code, installation instructions,
     100's more DHTML scripts, and Terms Of
     Use, visit dynamicdrive.com
     */

    function printit() {
        if (window.print) {
            window.print();
        } else {
            var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
            document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
            WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";  
        }
    }
    printit();
</script>
