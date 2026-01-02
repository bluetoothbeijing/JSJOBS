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
use Joomla\CMS\Language\Text;

     HTMLHelper::_('behavior.formvalidator');
     $document = Factory::getDocument();

     global $mainframe;
     $document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');

     $document->addScript('../components/com_jsjobs/js/jquery.tokeninput.js');
     // $document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');

     ?>

     <script language="javascript">
    // for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'configuration.save') {
                returnvalue = validate_form(document.adminForm);
            } else
            returnvalue = true;
            if (returnvalue) {
                Joomla.submitform(task);
                return true;
            } else
            return false;
        }
    }
    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php echo Factory::getSession()->getFormToken(); ?>';//send token
        } else {
            alert("<?php echo Text::_("Some values are not acceptable please check all tabs"); ?>");
            return false;
        }
        return true;
    }

    jQuery(document).ready(function ($) {

        var jsjobsconfigid = '<?php echo $this->jsjobsconfigid ?>';
        if (jsjobsconfigid == 'general-setting') {
            jQuery('#general-setting').css('display','inline-block');
            jQuery('#gen-setting').addClass('active');
        }else if (jsjobsconfigid == 'visitor-setting') {
            jQuery('#visitor-setting').css('display','inline-block');
            jQuery('#vis-setting').addClass('active');
        }else if (jsjobsconfigid == 'package-setting') {
            jQuery('#package-setting').css('display','inline-block');
            jQuery('#pack-setting').addClass('active');
        }else if (jsjobsconfigid == 'social-setting') {
            jQuery('#social-setting').css('display','inline-block');
            jQuery('#socl-setting').addClass('active');
        }else if (jsjobsconfigid == 'rss-setting') {
            jQuery('#rss-setting').css('display','inline-block');
            jQuery('#rs-setting').addClass('active');
        }else if (jsjobsconfigid == 'log-setting') {
            jQuery('#log-setting').css('display','inline-block');
            jQuery('#lr-setting').addClass('active');
        }else{
            jQuery('#general-setting').css('display','inline-block');
            jQuery('#gen-setting').addClass('active');
        }
    });
</script>

<?php
$ADMINPATH = JPATH_BASE . '\components\com_jsjobs';

$date_format = array(
    '0' => array('value' => 'd-m-Y', 'text' => Text::_('dd-mm-yyyy')),
    '1' => array('value' => 'm/d/Y', 'text' => Text::_('mm-dd-yyyy')),
    '2' => array('value' => 'Y-m-d', 'text' => Text::_('yyyy-mm-dd')),);

$leftright = array(
    '0' => array('value' => 1,
                 'text' => Text::_('Left Align')),
    '1' => array('value' => 2,
                 'text' => Text::_('Right Align')),);

$yesno = array(
    '0' => array('value' => 1,
                 'text' => Text::_('JYES')),
    '1' => array('value' => 0,
                 'text' => Text::_('JNO')),);

$yesnobackup = array(
    '0' => array('value' => 1,
                 'text' => Text::_('Yes Recommended')),
    '1' => array('value' => 0,
                 'text' => Text::_('JNO')),);

$captchalist = array(
    '0' => array('value' => 1,
                 'text' => Text::_('JS Jobs Captcha')),
    '1' => array('value' => 0,
                 'text' => Text::_('Joomla Default Recaptcha')),
);



$showhide = array(
    '0' => array('value' => 1,
                 'text' => Text::_('Show')),
    '1' => array('value' => 0,
                 'text' => Text::_('Hide')),);
$defaultradius = array(
    '0' => array('value' => 1, 'text' => Text::_('Meters')),
    '1' => array('value' => 2, 'text' => Text::_('Kilometers')),
    '2' => array('value' => 3, 'text' => Text::_('Miles')),
    '3' => array('value' => 4, 'text' => Text::_('Nautical Miles')),
);

$paymentmethodsarray = array(
    '0' => array('value' => 'paypal', 'text' => Text::_('Paypal')),
    '1' => array('value' => 'fastspring', 'text' => Text::_('Fastspring')),
    '2' => array('value' => 'authorizenet', 'text' => Text::_('Authorize,net')),
    '3' => array('value' => '2checkout', 'text' => Text::_('2checkout')),
    '4' => array('value' => 'Pagseguro', 'text' => Text::_('Pagseguro')),
    '5' => array('value' => 'other', 'text' => Text::_('Other')),
    '6' => array('value' => 'no', 'text' => Text::_('Not Use')),);

$defaultaddressdisplaytype = array(
    '0' => array('value' => 'csc', 'text' => Text::_('City, State, Country')),
    '1' => array('value' => 'cs', 'text' => Text::_('City, State')),
    '2' => array('value' => 'cc', 'text' => Text::_('City, Country')),
    '3' => array('value' => 'c', 'text' => Text::_('City')),
);
$searchjobtag = array(
    '0' => array('value' => 1, 'text' => Text::_('Top left')),
    '1' => array('value' => 2, 'text' => Text::_('Top right')), 
    '2' => array('value' => 3, 'text' => Text::_('middle left')), 
    '3' => array('value' => 4, 'text' => Text::_('middle right')), 
    '4' => array('value' => 5, 'text' => Text::_('bottom left')), 
    '5' => array('value' => 6, 'text' => Text::_('bottom right')),
    '6' => array('value' => 7, 'text' => Text::_('JNONE'))
);

$captcha = HTMLHelper::_('select.genericList', $captchalist, 'captchause', 'class="inputbox" ' . '', 'value', 'text', $this->config['captchause']);

$date_format = HTMLHelper::_('select.genericList', $date_format, 'date_format', 'class="inputbox" ' . '', 'value', 'text', $this->config['date_format']);

$defaultloginfrom = array(
    '0' => array('value' => '1', 'text' => Text::_('JS Jobs Login')),
    '1' => array('value' => '2', 'text' => Text::_('Joomla Login')),
	'2' => array('value' => '3', 'text' => Text::_('Custom Link')),
);
$defaultregisterfrom = array(
	'0' => array('value' => '1', 'text' => Text::_('JS Jobs Registration')),
	'1' => array('value' => '2', 'text' => Text::_('Joomla Registration')),
	'2' => array('value' => '3', 'text' => Text::_('Custom Link')),
);

//rss

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
// the below varible is used at many places
    $small_field_width = 15;
?>

<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div> 
    <div id="jsjobs-content">
        <div class="dashboard">
            <div id="jsjobs-wrapper-top-left">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_('Dashboard'); ?>
                            </a>
                        </li>
                        <li>
                            <?php echo Text::_('Configurations'); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="jsjobs-wrapper-top-right">
                <div id="jsjobs-config-btn">
                    <a href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations" title="<?php echo Text::_('Configuration'); ?>">
                        <img alt="<?php echo Text::_('Configuration'); ?>" src="components/com_jsjobs/include/images/icon/config.png" />
                    </a>
                </div>
                <div id="jsjobs-help-btn" class="jsjobs-help-btn">
                    <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=help" title="<?php echo Text::_('Help'); ?>">
                        <img alt="<?php echo Text::_('Help'); ?>" src="components/com_jsjobs/include/images/help-page/help.png" />
                    </a>
                </div>
                <div id="jsjobs-vers-txt">
                    <?php echo Text::_("Version").' :'; ?>
                    <span class="jsjobs-ver">
                        <?php
                        $version1 = $this->getJSModel('configuration')->getConfigByFor('default');
                        $version = str_split($version1['version']);
                        $version = implode('', $version);
                        echo $version?>
                    </span>
                </div>
            </div>
        </div>
        <div id="jsjobs-head" class="jsjobs-config-head">
            <h1 class="jsjobs-head-text">
                <?php echo Text::_('Configurations'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('configuration.save');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Save').' '.Text::_('Configurations'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Save').' '.Text::_('Configurations'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 jsstadmin-config-data-wrp">
            <form action="index.php" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <input type="hidden" name="check" value="post"/>
                <div class="jsstadmin-configurations-toggle">
                    <img alt="<?php echo Text::_('toggle'); ?>" src="components/com_jsjobs/include/images/left_menu_icons/menu.png" />
                    <span class="jslm_text"><?php echo Text::_('Select Configuration'); ?></span>
                </div>
                <div class="jsstadmin-config-left-menu" id="jsjobs-cinfig-menu">
                    <?php echo $this->getJSModel('configuration')->getConfigLeftMenu(); ?>
                </div>
                <div class="jsstadmin-config-right-data">
                    <div id="tabs_wrapper" class="tabs_wrapper">
                    <!-- GENERAL SETTING -->
                    <div id="general-setting" class="jsstadmin-hide-config">
                        <div class="idTabs config-tabs">
                            <a class="selected" href="#site_setting"><?php echo Text::_('Site Settings'); ?></a> 
                            <a class="" href="#defaul_setting"><?php echo Text::_('Default Settings'); ?></a> 
                            <a class="" href="#defaultcaptcha"><?php echo Text::_('Default Captcha'); ?></a> 
                            <a  class="linktabclass" href="#listjobs"><?php echo Text::_('Job Listing'); ?></a>
                            <a  class="linktabclass" href="#aifeatures"><?php echo Text::_('AI Settings'); ?></a>
                            <a  class="linktabclass" href="#category_setting"><?php echo Text::_('Categories'); ?></a>
                            <?php /*<a  href="#payment"><?php echo Text::_('Payment'); ?></a> */ ?>
                            <a  class="linktabclass" href="#email"><?php echo Text::_('Email'); ?></a>
                            <a class="" href="#message_setting"><?php echo Text::_('Message'); ?></a> 
                            <a  class="linktabclass" href="#googlemap"><?php echo Text::_('Map'); ?></a>
                            <a  class="linktabclass" href="#adsense"><?php echo Text::_('Google Adsense'); ?></a>
                            <a  class="linktabclass" href="#jomsocial"><?php echo Text::_('JomSocial Settings'); ?></a>
                            <?php if ($this->isjobsharing) { ?> 
                                <a  class="linktabclass" href="#jobsharing"><?php echo Text::_('Job Sharing'); ?></a>
                            <?php } ?>  
                        </div>
                        <!-- site setting -->
                        <div id="site_setting" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Site Settings'); ?></div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="title">
                                    <?php echo Text::_('Title'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" id="title" name="title" value="<?php echo $this->config['title']; ?>" class="inputfieldsizeful inputbox required" maxlength="255" size="<?php echo $med_field_width; ?>"  />
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="data_directory">
                                    <?php echo Text::_('Data Directory'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="data_directory" id="data_directory" value="<?php echo $this->config['data_directory']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>"/>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('The system will upload all user files to this folder'); echo " (".JPATH_SITE.'/'.$this->config['data_directory'].")";?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="cur_location">
                                    <?php echo Text::_('Show Breadcrumbs'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'cur_location', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['cur_location']); ?>
                                    <div class="jobs-config-descript" >
                                        <label class=" stylelabelbottom labelcolorbottom" for="cur_location"><?php echo Text::_('Show navigation in template breadcrumbs'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="number_of_cities_for_autocomplete">
                                    <?php echo Text::_('Maximum record for city field'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="number_of_cities_for_autocomplete" id="number_of_cities_for_autocomplete" value="<?php echo $this->config['number_of_cities_for_autocomplete']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="number_of_cities_for_autocomplete"><?php echo Text::_('Set number of cities to show in the result of the location input box'); ?>.</label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="newtyped_cities">
                                    <?php echo Text::_('User can add cities in database'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'newtyped_cities', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['newtyped_cities']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="newtyped_cities"><?php echo Text::_('Users can add a new city to the system'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="document_file_type">
                                    <?php echo Text::_('Document File Extensions'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="document_file_type" id="document_file_type" value="<?php echo $this->config['document_file_type']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="document_file_type"><?php echo Text::_('Add document allowed extensions'); ?>. <?php echo Text::_('Must be comma seperated'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="image_file_type">
                                    <?php echo Text::_('Image File Extensions'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="image_file_type" id="image_file_type" value="<?php echo $this->config['image_file_type']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="image_file_type"><?php echo Text::_('Add image allowed extensions'); ?>. <?php echo Text::_('Must be comma seperated'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="company_logofilezize">
                                    <?php echo Text::_('Company Logo Maximum Size'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="company_logofilezize" id="company_logofilezize" value="<?php echo $this->config['company_logofilezize']; ?>" class="inputfieldsize inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /><label class="jobs-mini-descp" for="company_logofilezize"><?php echo Text::_('Kb'); ?></label>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="company_logofilezize"><?php echo Text::_('The system will not upload file if the company logo size exceeds a given size'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="newdays">
                                    <?php echo Text::_('Mark Job New'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="newdays" id="newdays" value="<?php echo $this->config['newdays']; ?>" class="inputbox validate-numeric inputfieldsize" maxlength="6" size="<?php echo $med_field_width; ?>" maxlength="5" /><label class="jobs-mini-descp" for="newdays"><?php echo Text::_('Days'); ?></label>
                                    <div class="jobs-config-descript">
                                        <label class="stylelabelbottom labelcolorbottom" for="newdays"><?php echo Text::_('How many days system show New tag'); ?>.</label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="document_file_size">
                                    <?php echo Text::_('Resume').' / '.Text::_('Userfield').' :  '.Text::_('File Maximum Size'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="document_file_size" id="document_file_size" value="<?php echo $this->config['document_file_size']; ?>" class="inputfieldsize inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /><label class="jobs-mini-descp" for="document_file_size"><?php echo Text::_('Kb'); ?></label>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="document_file_size"><?php echo Text::_('The system will not upload file if the resume file size exceeds the given size'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="document_max_files">
                                    <?php echo Text::_('Number of Files For Resume'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="document_max_files" id="document_max_files" value="<?php echo $this->config['document_max_files']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="document_max_files"><?php echo Text::_('Maximum number of files that job seeker can upload in resume'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="currency_align">
                                    <?php echo Text::_('Currency Symbol Position'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $leftright, 'currency_align', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['currency_align']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="currency_align"><?php echo Text::_('Show currency symbol left or right to the amount'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="offline">
                                    <?php echo Text::_('Offline'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'offline', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['offline']); ?>
                                    <textarea name="offline_text" id="offline_text" cols="25" rows="3" class="textareabox inputbox"><?php echo $this->config['offline_text']; ?></textarea> 
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- defualt setting -->
                        <div id="defaul_setting" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Default Settings'); ?></div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="defaultaddressdisplaytype">
                                    <?php echo Text::_('Default Address Display Style'); ?>
                                </label>
                                <div class="jobs-config-value">
                                   <?php echo HTMLHelper::_('select.genericList', $defaultaddressdisplaytype, 'defaultaddressdisplaytype', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['defaultaddressdisplaytype']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="defaultaddressdisplaytype"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="date_format">
                                    <?php echo Text::_('Default Date Format'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo $date_format; ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="date_format"><?php echo Text::_('Date format which is used in the whole application'); ?>.</label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="employer_defaultgroup">
                                    <?php echo Text::_('Employer Default Group'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo $this->lists['employer_group']; ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="employer_defaultgroup"><?php echo Text::_('This group will auto assign to the new employer'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="jobseeker_defaultgroup">
                                    <?php echo Text::_('Job Seeker Default Group'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo $this->lists['jobseeker_group']; ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="jobseeker_defaultgroup">
                                            <?php echo Text::_('This group will auto assign to new jobseeker'); ?>
                                        </label>     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- default captcha -->
                        <div id="defaultcaptcha" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Default Captcha'); ?></div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="captchause">
                                    <?php echo Text::_('Set Default Captcha'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo $captcha; ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="captchause"><?php echo Text::_('Select captcha for application'); ?>.</label>     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- list job -->
                        <div id="listjobs" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Job Listing Settings'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="showgoldjobsinnewestjobs">
                                        <?php echo Text::_('Show Gold Jobs'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'showgoldjobsinnewestjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['showgoldjobsinnewestjobs']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="showgoldjobsinnewestjobs"><?php echo Text::_('Show gold jobs in jobs lising page'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="showfeaturedjobsinnewestjobs">
                                        <?php echo Text::_('Show Featured Jobs'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'showfeaturedjobsinnewestjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['showfeaturedjobsinnewestjobs']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="showfeaturedjobsinnewestjobs"><?php echo Text::_('Show featured jobs in jobs lising page'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="noofgoldjobsinlisting">
                                        <?php echo Text::_('Number Of Gold Jobs'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="noofgoldjobsinlisting" id="noofgoldjobsinlisting" value="<?php echo $this->config['noofgoldjobsinlisting']; ?>" class="inputfieldsizeful inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" />
                                        <div class="jobs-config-descript" >
                                            <label class=" stylelabelbottom labelcolorbottom" for="noofgoldjobsinlisting"><?php echo Text::_('How many gold jobs show per page scroll'); ?>.</label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="nooffeaturedjobsinlisting">
                                        <?php echo Text::_('Number Of Featured Jobs'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="nooffeaturedjobsinlisting" id="nooffeaturedjobsinlisting" value="<?php echo $this->config['nooffeaturedjobsinlisting']; ?>" class="inputfieldsizeful inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="nooffeaturedjobsinlisting"><?php echo Text::_('How many featured jobs show per page scroll'); ?>.</label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="searchjobtag">
                                        <?php echo Text::_(' Refine Search Tag Position'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $searchjobtag, 'searchjobtag', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['searchjobtag']); ?>
                                        <div class="jobs-config-descript" >
                                            <label class=" stylelabelbottom labelcolorbottom" for="searchjobtag"><?php echo Text::_('Position of refine search tag'); ?>.</label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="noofgoldjobsinlisting">
                                        <?php echo Text::_('Total Number of Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'showtotalnjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['showtotalnjobs']); ?>
                                        <div class="jobs-config-descript" >
                                            <label class=" stylelabelbottom labelcolorbottom" for="noofgoldjobsinlisting"><?php echo Text::_('Show total number of jobs in job listing'); ?>.</label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="labelinlisting">
                                        <?php echo Text::_('Show Label In Jobs Listing'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'labelinlisting', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['labelinlisting']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="labelinlisting"><?php echo Text::_('Show labels in jobs listings, my jobs etc'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right closed -->
                        </div>
                        <!-- List job option -->
                        <div id="listjoboption" class="">
                            <?php /*
                             <div class="headtext"><?php echo Text::_('Members').' (' . Text::_('Field settings for login users') . ')'; ?></div>
                                <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_title">
                                        <?php echo Text::_('Job Title'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_title', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_title']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_title"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_jobtype">
                                        <?php echo Text::_('Job Type'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_jobtype', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_jobtype']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_jobtype"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_jobstatus">
                                        <?php echo Text::_('Job Status'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_jobstatus', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_jobstatus']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_jobstatus"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_company">
                                        <?php echo Text::_('Company'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_company', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_company']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_company"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                             
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_companysite">
                                        <?php echo Text::_('Company Website'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_companysite', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_companysite']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_companysite"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                             
                             </div><!-- left closed -->
                             <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_country">
                                        <?php echo Text::_('Country'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_country', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_country']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_country"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_state">
                                        <?php echo Text::_('State'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_state', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_state']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_state"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_city">
                                        <?php echo Text::_('City'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_city', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_city']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_city"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_category">
                                        <?php echo Text::_('Category'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_category', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_category']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_category"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                               <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_created">
                                        <?php echo Text::_('Created'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_created', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_created']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_created"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_salary">
                                        <?php echo Text::_('Salary'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_salary', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_salary']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_salary"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_noofjobs">
                                        <?php echo Text::_('Number Of Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_noofjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_noofjobs']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_noofjobs"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="lj_description">
                                        <?php echo Text::_('Description'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'lj_description', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['lj_description']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="lj_description"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                

                              </div><!-- right closed -->

                             <div class="headtext"><?php echo Text::_('Visitors').' (' . Text::_('Field settings for visitors') . ')';?></div>
                                <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_title">
                                        <?php echo Text::_('Job Title'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_title', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_title']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_title"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_jobtype">
                                        <?php echo Text::_('Job Type'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_jobtype', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_jobtype']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_jobtype"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_jobstatus">
                                        <?php echo Text::_('Job Status'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_jobstatus', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_jobstatus']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_jobstatus"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_company">
                                        <?php echo Text::_('Company'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_company', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_company']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_company"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_companysite">
                                        <?php echo Text::_('Company Website'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_companysite', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_companysite']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_companysite"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                            </div><!-- left closed -->
                            <div id="jsjobs_right_main">
                             
                            <?php /*
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_country">
                                        <?php echo Text::_('Country'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_country', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_country']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_country"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_state">
                                        <?php echo Text::_('State'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_state', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_state']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_state"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_city">
                                        <?php echo Text::_('City'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_city', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_city']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_city"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_category">
                                        <?php echo Text::_('Category'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_category', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_category']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_category"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_created">
                                        <?php echo Text::_('Created'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_created', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_created']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_created"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_salary">
                                        <?php echo Text::_('Salary'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_salary', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_salary']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_salary"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_description">
                                        <?php echo Text::_('Description'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_description', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_description']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_description"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_lj_noofjobs">
                                        <?php echo Text::_('Number Of Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitor_lj_noofjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_lj_noofjobs']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitor_lj_noofjobs"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                
                               
                              </div><!-- right closed -->
                               */ ?>
                        </div>

                        <!--aifeatures open-->
                        <div id="aifeatures" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('AI Settings'); ?></div>
                            <div id="jsjobs_left_main">

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="admin_list_ai_filter">
                                        <?php echo Text::_('Enable AI Search For Admin Job listing'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'admin_list_ai_filter', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['admin_list_ai_filter']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Shows AI Job search filter on admin job listing layout instead of job data filter.'); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="admin_resume_list_ai_filter">
                                        <?php echo Text::_('Enable AI Search For Admin Resume listing'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'admin_resume_list_ai_filter', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['admin_resume_list_ai_filter']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Shows AI Resume search filter on admin resume listing layout instead of resume data filter.'); ?></label>
                                        </div>
                                    </div>
                                </div>
                              <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="admin_resume_list_ai_filter">
                                        <?php echo Text::_('Enable Suggested Resumes Button On Admin Job Listing'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'admin_show_suggested_resumes_button', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['admin_show_suggested_resumes_button']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Shows suggested resumes button on admin job listing.'); ?></label>
                                        </div>
                                    </div>
                                </div>


                                <!--admin end-->

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="job_search_ai_form">
                                        <?php echo Text::_('AI Search On Job Search Page'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'job_search_ai_form', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['job_search_ai_form']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show the AI search field on job search form'); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="job_list_ai_filter">
                                        <?php echo Text::_('AI Search Filter On Job Listing'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'job_list_ai_filter', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['job_list_ai_filter']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show the AI search field on job listing'); ?></label>
                                        </div>
                                    </div>
                                </div>


                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="show_suggested_jobs_btn">
                                        <?php echo Text::_('Show AI Suggested Jobs Button'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'show_suggested_jobs_btn', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['show_suggested_jobs_btn']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show AI suggested jobs button on my resumes page to job seeker.'); ?></label>
                                        </div>
                                    </div>
                                </div>


                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="show_suggested_jobs_dashboard">
                                        <?php echo Text::_('AI Suggested Jobs On Dashboard'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'show_suggested_jobs_dashboard', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['show_suggested_jobs_dashboard']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show AI suggested jobs on job seeker dashboard.'); ?></label>
                                        </div>
                                    </div>
                                </div>


                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="resume_search_ai_form">
                                        <?php echo Text::_('AI Search On Resume Search Page'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'resume_search_ai_form', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resume_search_ai_form']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show the AI search field on resume search form'); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="resume_list_ai_filter">
                                        <?php echo Text::_('AI Search Filter On Resume Listing'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'resume_list_ai_filter', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resume_list_ai_filter']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show the AI search field on resume listing'); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="show_suggested_resumes_btn">
                                        <?php echo Text::_('Show AI Suggested Resumes Button'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'show_suggested_resumes_btn', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['show_suggested_resumes_btn']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show AI suggested resumes button on my jobs page to employer.'); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="show_suggested_resumes_dashboard">
                                        <?php echo Text::_('AI Suggested Resumes On Dashboard'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'show_suggested_resumes_dashboard', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['show_suggested_resumes_dashboard']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('Show AI suggested resumes on employer dashboard.'); ?></label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- category setting -->
                        <div id="category_setting" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Categories'); ?></div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="categories_colsperrow">
                                    <?php echo Text::_('Categories Columns Per Row'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" id="categories_colsperrow" name="categories_colsperrow" value="<?php echo $this->config['categories_colsperrow']; ?>" class="inputfieldsizeful inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="categories_colsperrow"><?php echo Text::_('Show number of categories in one row in jobs and resume');?>.</label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="captchause">
                                    <?php echo Text::_('Sub Categories Limit'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="subcategory_limit" id="subcategory_limit" value="<?php echo $this->config['subcategory_limit']; ?>" class="inputbox validate-numeric inputfieldsize" maxlength="6" size="<?php echo $med_field_width; ?>" maxlength="5" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="captchause"><?php echo Text::_('How many sub-categories show in a popup at category layout'); ?>.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- email -->
                        <div id="email" class="jsstadmin-config-gen-body">
                           <div class="headtext"><?php echo Text::_('Email Settings'); ?></div>
                           <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="mailfromaddress">
                                        <?php echo Text::_('Sender email address'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="mailfromaddress" id="mailfromaddress" value="<?php echo $this->config['mailfromaddress']; ?>" class="inputfieldsizeful inputbox validate-email" size="<?php echo $big_field_width; ?>"/>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="mailfromaddress"><?php echo Text::_('Email address that will be used to send emails'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjobs_right_main">
                                    <div id="jsjob_configuration_wrapper">
                                        <label class="jobs-config-title stylelabeltop labelcolortop" for="adminemailaddress">
                                            <?php echo Text::_('Admin E-mail Address'); ?>
                                        </label>
                                        <div class="jobs-config-value">
                                            <input type="text" name="adminemailaddress" id="adminemailaddress" value="<?php echo $this->config['adminemailaddress']; ?>" class="inputfieldsizeful inputbox validate-email" size="<?php echo $med_field_width; ?>" />
                                            <div class="jobs-config-descript">
                                                <label class=" stylelabelbottom labelcolorbottom" for="adminemailaddress"><?php echo Text::_('Admin will receive email notifications on this address'); ?></label>     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="mailfromname">
                                        <?php echo Text::_('Sender Name'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="mailfromname" id="mailfromname" value="<?php echo $this->config['mailfromname']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="mailfromname"><?php echo Text::_('Sender name that will be used in emails'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- message setting -->
                        <div id="message_setting" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Messages'); ?></div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="message_auto_approve">
                                    <?php echo Text::_('Message Auto Approve'); ?> <span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'message_auto_approve', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['message_auto_approve']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="message_auto_approve"><?php echo Text::_('Auto approve messages for job seeker and employer'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="conflict_message_auto_approve">
                                    <?php echo Text::_('Conflict Message Auto Approve'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'conflict_message_auto_approve', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['conflict_message_auto_approve']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="conflict_message_auto_approve"><?php echo Text::_('Auto approve conflicted messages for job seeker and employer'); ?>.</label>     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- map setting-->
                        <div id="googlemap" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Map'); ?></div>
                            <div id="jsjobs_left_main">
                                <?php
                                /*
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="mapheight">
                                        <?php echo Text::_('Map Height'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input class="inputfieldsize inputbox validate-numeric"  maxlength="6" type="text" id="mapheight" name="mapheight" value="<?php echo $this->config['mapheight']; ?>"/><label class="jobs-mini-descp" for="mapheight"><?php echo Text::_('Pixels'); ?></label>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="mapheight"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="mapwidth">
                                        <?php echo Text::_('Map Width'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input class="inputfieldsize inputbox validate-numeric" maxlength="6" type="text" id="mapwidth" name="mapwidth" value="<?php echo $this->config['mapwidth']; ?>"/><label class="jobs-mini-descp" for="mapwidth"><?php echo Text::_('Pixels'); ?></label>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="mapwidth"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                */?>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="">
                                        <?php echo Text::_('Map'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <a href="Javascript: showdiv();loadMap();"><?php echo Text::_('Show Map'); ?></a>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for=""><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="google_map_api_key">
                                        <?php echo Text::_('Google Map API key'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input class="inputfieldsize inputbox" type="text" id="google_map_api_key" name="google_map_api_key" value="<?php echo $this->config['google_map_api_key']; ?>"/>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="google_map_api_key"><?php echo Text::_('Get API key from').' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">'.Text::_('here').'</a>'; ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="default_latitude">
                                        <?php echo Text::_('Latitude'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" id="default_latitude" class="inputfieldsizeful" name="default_latitude" value="<?php echo $this->config['default_latitude']; ?>"/>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="default_latitude"><?php echo Text::_('Default latitude'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="default_longitude">
                                        <?php echo Text::_('Longitude'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" id="default_longitude" name="default_longitude" class="inputfieldsizeful" value="<?php echo $this->config['default_longitude']; ?>"/>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="default_longitude"><?php echo Text::_('Default longitude'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="defaultradius">
                                        <?php echo Text::_('Default Map Radius Type'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $defaultradius, 'defaultradius', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['defaultradius']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="defaultradius"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <!-- right closed -->
                            <div id="js_jobs_main_popup_area">
                                <div id="js_jobs_main_popup_head">
                                    <div id="jspopup_title"><?php echo Text::_('Map');?></div>
                                    <img id="jspopup_image_close" src="components/com_jsjobs/include/images/popup-close.png" />
                                </div>
                                <div id="jspopup_work_area">
                                    <div id="map" style="width:<?php echo $this->config['mapwidth']; ?>px; height:<?php echo $this->config['mapheight']; ?>px"><div id="map_container"></div></div>
                                </div>
                            </div>
                        </div>
                        <!-- google adsense -->
                        <div id="adsense" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Google Adsense Settings'); ?><span class="pro_version">*</span></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsenseclient">
                                        <?php echo Text::_('Google Adsense Client Id'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="googleadsenseclient" id="googleadsenseclient" value="<?php echo $this->config['googleadsenseclient']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsenseclient"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsenseslot">
                                        <?php echo Text::_('Google Adsense Slot Id'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="googleadsenseslot" id="googleadsenseslot" value="<?php echo $this->config['googleadsenseslot']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsenseslot"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsensecustomcss">
                                        <?php echo Text::_('Google Ads Custom Css'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <textarea name="googleadsensecustomcss" id="googleadsensecustomcss" cols="25" rows="3" class="inputfieldsizeful inputbox"><?php echo $this->config['googleadsensecustomcss']; ?></textarea>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsensecustomcss"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsenseshowinlistjobs">
                                        <?php echo Text::_('Show Google Ads In List Jobs'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $showhide, 'googleadsenseshowinlistjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['googleadsenseshowinlistjobs']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsenseshowinlistjobs"><?php echo Text::_('Show google adds in jobs listings'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsenseshowafter">
                                        <?php echo Text::_('Google Ads Show After Number Of Jobs'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="googleadsenseshowafter" id="googleadsenseshowafter" value="<?php echo $this->config['googleadsenseshowafter']; ?>" class="inputfieldsizeful inputbox validate-numeric" maxlength="6" size="<?php echo $small_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsenseshowafter"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsensewidth">
                                        <?php echo Text::_('Google Ads Width'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="googleadsensewidth" id="googleadsensewidth" value="<?php echo $this->config['googleadsensewidth']; ?>" class="inputfieldsizeful inputbox validate-numeric" maxlength="6" size="<?php echo $small_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsensewidth"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="googleadsenseheight">
                                        <?php echo Text::_('Google Ads Height'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="googleadsenseheight" id="googleadsenseheight" value="<?php echo $this->config['googleadsenseheight']; ?>" class="inputfieldsizeful inputbox validate-numeric" maxlength="6" size="<?php echo $small_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="googleadsenseheight"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--jomSocial open-->
                        <div id="jomsocial" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('JomSocial'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="isenabled_jomsocial">
                                        <?php echo Text::_('Enable JomSocial Integration'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'isenabled_jomsocial', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['isenabled_jomsocial']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_postcompany"><?php echo Text::_('Only enable if jomSocial is installed'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jomsocial_postcompany">
                                        <?php echo Text::_('Post New Company'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'jomsocial_postcompany', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jomsocial_postcompany']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_postcompany"><?php echo Text::_('Create post/activity on jomsocial account when new company is created'); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jomsocial_postjob">
                                        <?php echo Text::_('Post New Job'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'jomsocial_postjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jomsocial_postjob']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_postjob"><?php echo Text::_('Create post/activity on jomsocial account when new job is created'); ?></label>
                                        </div>
                                    </div>
                                </div>  
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jomsocial_postresume">
                                        <?php echo Text::_('Post New Resume'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'jomsocial_postresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jomsocial_postresume']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_postresume"><?php echo Text::_('Create post/activity on jomsocial account when new resume is created'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jomsocial_allowpostcompany">
                                        <?php echo Text::_('Allow Post Company'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'jomsocial_allowpostcompany', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jomsocial_allowpostcompany']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_allowpostcompany"><?php echo Text::_('Allow employer to post company on jomsocial from My Companies layout'); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jomsocial_allowpostjob">
                                        <?php echo Text::_('Allow Post Job'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'jomsocial_allowpostjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jomsocial_allowpostjob']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_allowpostjob"><?php echo Text::_('Allow employer to post job on jomsocial from My Jobs layout'); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jomsocial_allowpostresume">
                                        <?php echo Text::_('Allow Post Resume'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $yesno, 'jomsocial_allowpostresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jomsocial_allowpostresume']);
                                        ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jomsocial_allowpostresume"><?php echo Text::_('Allow job seeker to post resume on jomsocial from My Resumes layout'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($this->isjobsharing) { ?> 
                        <div id="jobsharing" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Job Sharing Default Location'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="default_sharing_country">
                                        <?php echo Text::_('Country'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        if ((isset($this->lists['defaultsharingcountry'])) && ($this->lists['defaultsharingcountry'] != ''))
                                            echo $this->lists['defaultsharingcountry'];
                                        ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="default_sharing_country"><?php echo Text::_(''); ?></label>     
                                        </div>   
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="default_sharing_state">
                                        <?php echo Text::_('State'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        if ((isset($this->lists['defaultsharingstate'])) && ($this->lists['defaultsharingstate'] != '')) { ?>
                                            <input class="inputfieldsizeful inputbox" type="text" name="default_sharing_state" id="default_sharing_state" size="40" maxlength="100" value="<?php echo $this->lists['defaultsharingstate']; ?>" />
                                        <?php  } else {
                                            ?>
                                            <input class="inputfieldsizeful inputbox" type="text" name="default_sharing_state" id="default_sharing_state" size="40" maxlength="100" value="" />
                                        <?php } ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="default_sharing_state"><?php echo Text::_(''); ?></label>     
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="default_sharing_city">
                                        <?php echo Text::_('City'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        if ((isset($this->lists['defaultsharingcity'])) && ($this->lists['defaultsharingcity'] != '')) {    
                                        ?> <input class="inputfieldsize inputbox" type="text" name="default_sharing_city" id="default_sharing_city" size="40" maxlength="100" value="<?php echo $this->lists['defaultsharingcity']; ?>" />
                                        <?php } else {
                                        ?>
                                        <input class="inputfieldsize inputbox" type="text" name="default_sharing_city" id="default_sharing_city" size="40" maxlength="100" value="" />
                                        <?php } ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="default_sharing_city"><?php echo Text::_(''); ?></label>     
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <!-- right closed -->
                        <?php } ?> 
                        </div>
                    </div>
                    <!-- VISITOR SETTING -->
                    <div id="visitor-setting" class="jsstadmin-hide-config">
                        <div class="idTabs config-tabs"> 
                            <a  class="linktabclass selected" href="#emp_visitor"><?php echo Text::_('Job Posting'); ?></a>
                            <a  class="linktabclass" href="#visitor_employer"><?php echo Text::_('Visitor as an employer'); ?></a> 
                            <a  class="linktabclass" href="#js_visitor"><?php echo Text::_('Visitor As A Job Seeker'); ?></a> 
                            <a  class="linktabclass" href="#emp_visitor_links"><?php echo Text::_('Employer Links'); ?></a>
                            <a  class="linktabclass" href="#js_visitor_links"><?php echo Text::_('Jobseeker Links'); ?></a> 
                        </div>
                        <!-- employer setting -->
                        <div id="emp_visitor" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Job Posting Options For Visitor'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_can_post_job">
                                        <?php echo Text::_('Visitor Post Job'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'visitor_can_post_job', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_can_post_job']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitor_can_post_job"><?php echo Text::_('Visitors can post job'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_can_edit_job">
                                        <?php echo Text::_('Edit Job Posting'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'visitor_can_edit_job', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_can_edit_job']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitor_can_edit_job"><?php echo Text::_('Visitor can edit his posted job'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="job_captcha">
                                        <?php echo Text::_('Form Captcha'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'job_captcha', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['job_captcha']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="job_captcha"><?php echo Text::_('Show captcha on visitor form job'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- visitor as an employer -->
                        <div id="visitor_employer" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Visitors Can View Employer Area'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_conrolpanel">
                                        <?php echo Text::_('Control Panel'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_conrolpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_conrolpanel']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_conrolpanel"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_packages">
                                        <?php echo Text::_('Packages'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_packages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_packages']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_packages"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_resumesearch">
                                        <?php echo Text::_('Resume Search'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_resumesearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_resumesearch']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_resumesearch"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" style="display: none;">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_resumesearchresult">
                                        <?php echo Text::_('Resume Search Results'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_resumesearchresult', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_resumesearchresult']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_resumesearchresult"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_viewpackage">
                                        <?php echo Text::_('Package Detail'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_viewpackage', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_viewpackage']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_viewpackage"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_resumesearchresult">
                                        <?php echo Text::_('View Resume'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_resume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_resume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_resume"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- jobseeker setting -->
                        <div id="js_visitor" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Visitor As A Job Seeker'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_can_apply_to_job">
                                        <?php echo Text::_('Visitor Can Apply To Job'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'visitor_can_apply_to_job', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_can_apply_to_job']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitor_can_apply_to_job"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_can_add_resume">
                                        <?php echo Text::_('Visitor Can Add New Resume'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'visitor_can_add_resume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_can_add_resume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitor_can_add_resume"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="overwrite_jobalert_settings">
                                        <?php echo Text::_('Job Alert For Visitor'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'overwrite_jobalert_settings', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['overwrite_jobalert_settings']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="overwrite_jobalert_settings"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitor_show_login_message">
                                        <?php echo Text::_('Show Login Message To Visitor'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'visitor_show_login_message', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitor_show_login_message']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitor_show_login_message"><?php echo Text::_('Show login option to visitor on job apply'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="resume_captcha">
                                        <?php echo Text::_('Resume Captcha'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'resume_captcha', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resume_captcha']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="resume_captcha"><?php echo Text::_('Show captcha on visitor form resume'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="job_alert_captcha">
                                        <?php echo Text::_('Job Alert Captcha'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'job_alert_captcha', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['job_alert_captcha']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="job_alert_captcha"><?php echo Text::_('Show captcha visitor job alert form'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                            </div>
                            <!-- right closed -->
                            <div class="headtext headtext-second"><?php echo Text::_('Visitors Can View Job Seeker'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_controlpanel">
                                        <?php echo Text::_('Control Panel'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_controlpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_controlpanel']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_controlpanel"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <?php /*
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_packages">
                                        <?php echo Text::_('Packages'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_packages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_packages']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_packages"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_jobcat">
                                        <?php echo Text::_('Job Categories'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_jobcat', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_jobcat']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_jobcat"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_viewpackage">
                                        <?php echo Text::_('Package Detail'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_viewpackage', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_viewpackage']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_viewpackage"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>   */  ?>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_jobsearch">
                                        <?php echo Text::_('Job Search'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_jobsearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_jobsearch']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_jobsearch"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->
                             
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_viewcompany">
                                        <?php echo Text::_('View Company'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_viewcompany', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_viewcompany']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_empackages"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_emp_viewjob">
                                        <?php echo Text::_('View Job'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_emp_viewjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_emp_viewjob']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="visitorview_emp_viewjob"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jslistjobshortlist">
                                        <?php echo Text::_('Visitor Job Shortlist'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jslistjobshortlist', 'class="inputfieldsizeful inputbox"' . 'onChange="showhideapplybutton(\'showhideapplybutton\', this.value)"', 'value', 'text', $this->config['vis_jslistjobshortlist']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jslistjobshortlist"><?php echo Text::_('Job short list setting effects on jobs listing page'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <?php /*
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_newestjobs">
                                        <?php echo Text::_('Newest Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_newestjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_newestjobs']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_newestjobs"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_listjob">
                                        <?php echo Text::_('List Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_listjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_listjob']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_listjob"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="visitorview_js_jobsearchresult">
                                        <?php echo Text::_('Job Search Results'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'visitorview_js_jobsearchresult', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['visitorview_js_jobsearchresult']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="visitorview_js_jobsearchresult"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                   
                                   */ ?>
                            </div>
                            <!-- right closed -->
                        </div>
                        <!-- employer links -->
                        <div id="emp_visitor_links" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Employer Top Menu Links'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_emcontrolpanel">
                                        <?php echo Text::_('Control Panel'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_emcontrolpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_emcontrolpanel']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_emcontrolpanel"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_emnewjob">
                                        <?php echo Text::_('New Job'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_emnewjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_emnewjob']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_emnewjob"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_emmyjobs">
                                        <?php echo Text::_('My Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_emmyjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_emmyjobs']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_emmyjobs"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_emmycompanies">
                                        <?php echo Text::_('My Companies'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_emmycompanies', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_emmycompanies']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_emmycompanies"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_emsearchresume">
                                        <?php echo Text::_('Resume Search'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_emsearchresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_emsearchresume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_emsearchresume"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="headtext headtext-second"><?php echo Text::_('Employer Dashboard Links For Visitor'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmyjobs_counts">
                                        <?php echo Text::_('My Jobs Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmyjobs_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmyjobs_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmyjobs_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emappliedresume_counts">
                                        <?php echo Text::_('Applied Resume Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emappliedresume_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emappliedresume_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emappliedresume_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmycompanies_counts">
                                        <?php echo Text::_('My Companies Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmycompanies_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmycompanies_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmycompanies_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmyresumesearches_counts">
                                        <?php echo Text::_('My Resume Searches Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmyresumesearches_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmyresumesearches_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmyresumesearches_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jobs_graph">
                                        <?php echo Text::_('Jobs Graph'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jobs_graph', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jobs_graph']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jobs_graph"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_resume_graph">
                                        <?php echo Text::_('Resume Graph'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_resume_graph', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_resume_graph']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_resume_graph"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_box_newestresume">
                                        <?php echo Text::_('Newest Resume Box'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_box_newestresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_box_newestresume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_box_newestresume"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_box_appliedresume">
                                        <?php echo Text::_('Applied Resume Box'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_box_appliedresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_box_appliedresume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_box_appliedresume"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_mystuff_area">
                                        <?php echo Text::_('My Stuff Area'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_mystuff_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_mystuff_area']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_mystuff_area"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_mystats_area">
                                        <?php echo Text::_('My Stats Area'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_mystats_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_mystats_area']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_mystats_area"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmycompanies">
                                        <?php echo Text::_('My Companies'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmycompanies', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmycompanies']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmycompanies"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emformcompany">
                                        <?php echo Text::_('New Company'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emformcompany', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emformcompany']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emformcompany"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <?php /*
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emalljobsappliedapplications">
                                        <?php echo Text::_('Applied Resume'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emalljobsappliedapplications', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emalljobsappliedapplications']); ?>
                                    </div>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="vis_emalljobsappliedapplications"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                                <?php /* ?>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmyjobs">
                                        <?php echo Text::_('My Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmyjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmyjobs']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmyjobs"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emformjob">
                                        <?php echo Text::_('New Job'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emformjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emformjob']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emformjob"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>*/
                                 ?>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmydepartment">
                                        <?php echo Text::_('My Departments'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmydepartment', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmydepartment']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmydepartment"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emformdepartment">
                                        <?php echo Text::_('New Department'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emformdepartment', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emformdepartment']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emformdepartment"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmyfolders">
                                        <?php echo Text::_('Folders'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmyfolders', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmyfolders']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmyfolders"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emnewfolders">
                                        <?php echo Text::_('New Folder'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emnewfolders', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emnewfolders']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emnewfolders"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmy_resumesearches">
                                        <?php echo Text::_('Resume Save Searches'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmy_resumesearches', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmy_resumesearches']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmy_resumesearches"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <?php /*
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emresumesearch">
                                        <?php echo Text::_('Resume Search'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emresumesearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emresumesearch']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emresumesearch"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div> */ ?>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_empurchasehistory">
                                        <?php echo Text::_('Purchase History'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_empurchasehistory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_empurchasehistory']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_empurchasehistory"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmessages">
                                        <?php echo Text::_('Messages'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmessages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmessages']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmessages"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emmy_stats">
                                        <?php echo Text::_('My Stats'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emmy_stats', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emmy_stats']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emmy_stats"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_resume_rss">
                                        <?php echo Text::_('Resume RSS'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_resume_rss', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_resume_rss']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_resume_rss"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emresumebycategory">
                                        <?php echo Text::_('Resume By Categories'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emresumebycategory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emresumebycategory']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emresumebycategory"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_empackages">
                                        <?php echo Text::_('Packages'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_empackages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_empackages']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_empackages"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_ememploginlogout">
                                        <?php echo Text::_('Show Login/logout Button'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'vis_ememploginlogout', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_ememploginlogout']); ?> 
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_ememploginlogout"><?php echo Text::_('Show Login/logout Button In Employer Control Panel'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emuserdata_employer">
                                        <?php echo Text::_('User data request'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emuserdata_employer', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emuserdata_employer']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emuserdata_employer"><?php echo Text::_('GDPR user erase data request'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_emempregister">
                                        <?php echo Text::_('Employer Registration'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_emempregister', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_emempregister']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_emempregister"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- jobseeker links -->
                        <div id="js_visitor_links" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Job Seeker Top Menu Links'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_jscontrolpanel">
                                        <?php echo Text::_('Control Panel'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_jscontrolpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_jscontrolpanel']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_jscontrolpanel"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_jsnewestjob">
                                        <?php echo Text::_('Newest Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_jsnewestjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_jsnewestjob']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_jsnewestjob"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_jsjobcategory">
                                        <?php echo Text::_('Job Categories'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_jsjobcategory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_jsjobcategory']);?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_jsjobcategory"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_jssearchjob">
                                        <?php echo Text::_('Search Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_jssearchjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_jssearchjob']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_jssearchjob"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_vis_jsmyresume">
                                        <?php echo Text::_('My Resumes'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_vis_jsmyresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_vis_jsmyresume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_vis_jsmyresume"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="headtext headtext-second"><?php echo Text::_('Job Seeker Dashboard Links For Visitor'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsnewestjobs_counts">
                                        <?php echo Text::_('Newest Jobs Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsnewestjobs_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsnewestjobs_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsnewestjobs_counts"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsappliedjobs_counts">
                                        <?php echo Text::_('Applied Jobs Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsappliedjobs_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsappliedjobs_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsappliedjobs_counts"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmyresumes_counts">
                                        <?php echo Text::_('My Resumes Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmyresumes_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmyresumes_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmyresumes_counts"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmyjobsearches_counts">
                                        <?php echo Text::_('Job Save Searches Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmyjobsearches_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmyjobsearches_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmyjobsearches_counts"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsactivejobs_graph">
                                        <?php echo Text::_('Active Jobs Graph'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsactivejobs_graph', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsactivejobs_graph']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsactivejobs_graph"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmystuff_area">
                                        <?php echo Text::_('My Stuff Area'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmystuff_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmystuff_area']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmystuff_area"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsnewestjobs_box">
                                        <?php echo Text::_('Newest Jobs Box'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsnewestjobs_box', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsnewestjobs_box']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsnewestjobs_box"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmystats_area">
                                        <?php echo Text::_('My Stats Area'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmystats_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmystats_area']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmystats_area"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsappliedresume_box">
                                        <?php echo Text::_('Applied Resume Box'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsappliedresume_box', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsappliedresume_box']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsappliedresume_box"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmyresumes">
                                        <?php echo Text::_('My Resumes'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmyresumes', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmyresumes']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmyresumes"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsformresume">
                                        <?php echo Text::_('Add Resume'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsformresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsformresume']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsformresume"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsjobcat">
                                        <?php echo Text::_('Jobs By Categories'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsjobcat', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsjobcat']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsjobcat"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jslistnewestjobs">
                                        <?php echo Text::_('Newest Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jslistnewestjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jslistnewestjobs']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jslistnewestjobs"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsjobsearch">
                                        <?php echo Text::_('Search Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsjobsearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsjobsearch']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsjobsearch"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jslistallcompanies">
                                        <?php echo Text::_('All Companies'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jslistallcompanies', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jslistallcompanies']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jslistallcompanies"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jslistjobbytype">
                                        <?php echo Text::_('Job By Types'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jslistjobbytype', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jslistjobbytype']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jslistjobbytype"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmyappliedjobs">
                                        <?php echo Text::_('My Applied Jobs'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmyappliedjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmyappliedjobs']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmyappliedjobs"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsuserdata_jobseeker">
                                        <?php echo Text::_('User data request'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsuserdata_jobseeker', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsuserdata_jobseeker']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsuserdata_jobseeker"><?php echo Text::_('GDPR user erase data request'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                             </div>
                             <!-- left closed -->
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmycoverletters">
                                        <?php echo Text::_('My Cover Letters'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmycoverletters', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmycoverletters']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmycoverletters"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsformcoverletter">
                                        <?php echo Text::_('Add Cover Letter'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsformcoverletter', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsformcoverletter']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsformcoverletter"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmy_jobsearches">
                                        <?php echo Text::_('Job Save Searches'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php  echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmy_jobsearches', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmy_jobsearches']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmy_jobsearches"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jspurchasehistory">
                                        <?php echo Text::_('Purchase History'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php  echo HTMLHelper::_('select.genericList', $showhide, 'vis_jspurchasehistory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jspurchasehistory']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jspurchasehistory"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmy_stats">
                                        <?php echo Text::_('My Stats'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmy_stats', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmy_stats']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmy_stats"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsjobalertsetting">
                                        <?php echo Text::_('Job Alert'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsjobalertsetting', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsjobalertsetting']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsjobalertsetting"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsmessages">
                                        <?php echo Text::_('Messages'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsmessages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsmessages']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsmessages"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="jsjob_rss">
                                        <?php echo Text::_('Jobs RSS'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsjob_rss', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsjob_rss']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="jsjob_rss"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jspackages">
                                        <?php echo Text::_('Packages'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jspackages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jspackages']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jspackages"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsregister">
                                        <?php echo Text::_('Register'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'vis_jsregister', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsregister']);?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsregister"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="vis_jsjobsloginlogout">
                                        <?php echo Text::_('Show Login/logout Button'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'vis_jsjobsloginlogout', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['vis_jsjobsloginlogout']); ?> 
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="vis_jsjobsloginlogout"><?php echo Text::_('Show Login/logout Button In Job Seeker Control Panel'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PACKAGE SETTING -->
                    <div id="package-setting" class="jsstadmin-hide-config">
                        <div class="idTabs config-tabs">
                            <a class="selected" href="#emp_package"><?php echo Text::_('Employer Packages'); ?></a>
                            <a class="" href="#jobs_package"><?php echo Text::_('Jobseeker packages'); ?></a>
                        </div>
                        <!-- employer package -->
                        <div id="emp_package" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Employer Packages'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="newlisting_requiredpackage">
                                        <?php echo Text::_('Package Required For Employer'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'newlisting_requiredpackage', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['newlisting_requiredpackage']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="newlisting_requiredpackage"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="employer_defaultpackage">
                                        <?php echo Text::_('Auto Assign Package To Employer'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo $this->lists['employer_defaultpackage']; ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="employer_defaultpackage"><?php echo Text::_('Auto assign package to').' '; ?>&nbsp;<b class="defaultmycolorclass"><?php echo Text::_('New Employer'); ?></b></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="onlyonce_employer_getfreepackage">
                                        <?php echo Text::_('Employer Get Free Package Once'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'onlyonce_employer_getfreepackage', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['onlyonce_employer_getfreepackage']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="onlyonce_employer_getfreepackage"><?php echo Text::_('Limit free package').'. '.Text::_('Employers get a free package only once'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="employer_freepackage_autoapprove">
                                        <?php echo Text::_('Employer Free Package Auto Approve'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'employer_freepackage_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['employer_freepackage_autoapprove']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="employer_freepackage_autoapprove"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- jobseeker package -->
                        <div id="jobs_package" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Jobseeker Packages'); ?></div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="js_newlisting_requiredpackage">
                                    <?php echo Text::_('Package Required For Job Seeker'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'js_newlisting_requiredpackage', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['js_newlisting_requiredpackage']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="js_newlisting_requiredpackage"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="jobseeker_defaultpackage">
                                    <?php echo Text::_('Auto Assign Package To Job Seeker'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo $this->lists['jobseeker_defaultpackage']; ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="jobseeker_defaultpackage"><?php echo Text::_('Auto assign package to').' '; ?>&nbsp;<b class="defaultmycolorclass"><?php echo Text::_('New Job Seeker'); ?></b></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="onlyonce_jobseeker_getfreepackage">
                                    <?php echo Text::_('Job Seeker Get Free Package Once'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'onlyonce_jobseeker_getfreepackage', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['onlyonce_jobseeker_getfreepackage']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="onlyonce_jobseeker_getfreepackage"><?php echo Text::_('Limit free package').'. '.Text::_('Jobseeker get a free package only once'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="jobseeker_freepackage_autoapprove">
                                    <?php echo Text::_('Job Seeker Free Package Auto Approve'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'jobseeker_freepackage_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobseeker_freepackage_autoapprove']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="jobseeker_freepackage_autoapprove"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- SOCIAL APPS -->
                    <div id="social-setting" class="jsstadmin-hide-config">
                        <div class="idTabs config-tabs">
                            <a class="selected" href="#socialsharing"><?php echo Text::_('Social Links'); ?></a>
                        </div>
                        <div id="socialsharing" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Social Links'); ?></div>
                            <div class="socialconfig_block">
                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_fb_like" id="jobseeker_share_fb_like" <?php
                                    if ($this->config['jobseeker_share_fb_like'] == 1)
                                       echo 'checked="true"';
                                   else
                                       'checked="false"';
                                   ?> /><label class="socialconfig_label" for="jobseeker_share_fb_like"><?php echo Text::_('Facebook Likes'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_fb_comments" id="jobseeker_share_fb_comments" <?php
                                    if ($this->config['jobseeker_share_fb_comments'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_fb_comments"><?php echo Text::_('Facebook Comments'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_fb_share" id="jobseeker_share_fb_share" <?php
                                    if ($this->config['jobseeker_share_fb_share'] == 1)
                                       echo 'checked="true"';
                                   else
                                       'checked="false"';
                                   ?> /><label class="socialconfig_label" for="jobseeker_share_fb_share"><?php echo Text::_('Facebook Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_google_like" id="jobseeker_share_google_like" <?php
                                    if ($this->config['jobseeker_share_google_like'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_google_like"><?php echo Text::_('Google Likes'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_blog_share" id="jobseeker_share_blog_share" <?php
                                    if ($this->config['jobseeker_share_blog_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_blog_share"><?php echo Text::_('Blogger'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_google_share" id="jobseeker_share_google_share" <?php
                                    if ($this->config['jobseeker_share_google_share'] == 1)
                                       echo 'checked="true"';
                                   else
                                       'checked="false"';
                                   ?> /><label class="socialconfig_label" for="jobseeker_share_google_share"><?php echo Text::_('Google Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_friendfeed_share" id="jobseeker_share_friendfeed_share" <?php
                                    if ($this->config['jobseeker_share_friendfeed_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_friendfeed_share"><?php echo Text::_('Friend Feed Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_linkedin_share" id="jobseeker_share_linkedin_share" <?php
                                    if ($this->config['jobseeker_share_linkedin_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_linkedin_share"><?php echo Text::_('Linked-in Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_digg_share" id="jobseeker_share_digg_share" <?php
                                    if ($this->config['jobseeker_share_digg_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_digg_share"><?php echo Text::_('Digg Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_twiiter_share" id="jobseeker_share_twiiter_share" <?php
                                    if ($this->config['jobseeker_share_twiiter_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_twiiter_share"><?php echo Text::_('Twitter Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_myspace_share" id="jobseeker_share_myspace_share" <?php
                                    if ($this->config['jobseeker_share_myspace_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_myspace_share"><?php echo Text::_('Myspace Share'); ?><span class="pro_version">*</span></label>
                                </div>

                                <div class="socialconfig_content" >
                                    <input type="checkbox" value="1" name="jobseeker_share_yahoo_share" id="jobseeker_share_yahoo_share" <?php
                                    if ($this->config['jobseeker_share_yahoo_share'] == 1)
                                        echo 'checked="true"';
                                    else
                                        'checked="false"';
                                    ?> /><label class="socialconfig_label" for="jobseeker_share_yahoo_share"><?php echo Text::_('Yahoo Share'); ?><span class="pro_version">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- RSS SETTING -->
                    <div id="rss-setting" class="jsstadmin-hide-config">
                        <div class="idTabs config-tabs">
                            <a class="selected" href="#rss_job_set"><?php echo Text::_('Job Settings'); ?></a>
                            <a class="" href="#rss_resume_set"><?php echo Text::_('Resume Settings'); ?></a>
                        </div>
                        <div id="rss_job_set" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('RSS Jobs Settings'); ?><span class="pro_version">*</span></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="job_rss">
                                        <?php echo Text::_('Jobs RSS'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $showhide, 'job_rss', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['job_rss']);
                                        ;
                                        ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="job_rss"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_title">
                                        <?php echo Text::_('Title'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_job_title" id="rss_job_title" value="<?php echo $this->config['rss_job_title']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_title"><?php echo Text::_('Must provide a title for job feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_description">
                                        <?php echo Text::_('Description'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <textarea name="rss_job_description" id="rss_job_description" cols="25" rows="3" class="inputfieldsizeful inputbox"><?php echo $this->config['rss_job_description']; ?></textarea>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_description"><?php echo Text::_('Must provide a description for job feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_copyright">
                                        <?php echo Text::_('Copyright'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_job_copyright" id="rss_job_copyright" value="<?php echo $this->config['rss_job_copyright']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_copyright"><?php echo Text::_('Leave blank if not show'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_editor">
                                        <?php echo Text::_('Editor'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_job_editor" id="rss_job_editor" value="<?php echo $this->config['rss_job_editor']; ?>" class="inputfieldsizeful inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_editor"><?php echo Text::_('Leave blank if not show editor used for feed content issue'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_ttl">
                                        <?php echo Text::_('Time To Live'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_job_ttl" id="rss_job_ttl" value="<?php echo $this->config['rss_job_ttl']; ?>" class="inputfieldsizeful inputbox validate-numeric"  maxlength="6"  size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_ttl"><?php echo Text::_('Time To Live For Job Feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_webmaster">
                                        <?php echo Text::_('Webmaster'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_job_webmaster" id="rss_job_webmaster" value="<?php echo $this->config['rss_job_webmaster']; ?>" class="inputfieldsizeful inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_webmaster"><?php echo Text::_('Leave blank if not show webmaster email address used for techincal issue'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right closed -->
                            <div class="headtext headtext-second"><?php echo Text::_('Job Block'); ?><span class="pro_version">*</span></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_categories">
                                        <?php echo Text::_('Show With Categories'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'rss_job_categories', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['rss_job_categories']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_categories"><?php echo Text::_('Use RSS categories with our job categories'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_job_image">
                                        <?php echo Text::_('Company Image'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'rss_job_image', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['rss_job_image']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_job_image"><?php echo Text::_('Show company logo with job feeds'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right closed -->
                        </div>
                        <div id="rss_resume_set" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('RSS Resume Settings'); ?><span class="pro_version">*</span></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="resume_rss">
                                        <?php echo Text::_('Resume RSS'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php
                                        echo HTMLHelper::_('select.genericList', $showhide, 'resume_rss', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resume_rss']);
                                        ;
                                        ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="resume_rss"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_title">
                                        <?php echo Text::_('Title'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_resume_title" id="rss_resume_title" value="<?php echo $this->config['rss_resume_title']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_title"><?php echo Text::_('Must provide a title for resume feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_description">
                                        <?php echo Text::_('Description'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <textarea name="rss_resume_description" id="rss_resume_description" cols="25" rows="3" class="inputfieldsizeful inputbox"><?php echo $this->config['rss_resume_description']; ?></textarea>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_description"><?php echo Text::_('Must provide description for resume feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_copyright">
                                        <?php echo Text::_('Copyright'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_resume_copyright" id="rss_resume_copyright" value="<?php echo $this->config['rss_resume_copyright']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_copyright"><?php echo Text::_('Leave Blank If Not Show'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->

                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_editor">
                                        <?php echo Text::_('Editor'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_resume_editor" id="rss_resume_editor" value="<?php echo $this->config['rss_resume_editor']; ?>" class="inputfieldsizeful inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_editor"><?php echo Text::_('Leave blank if not show editor email address used for content issue'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_ttl">
                                        <?php echo Text::_('Time To Live'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_resume_ttl" id="rss_resume_ttl" value="<?php echo $this->config['rss_resume_ttl']; ?>" class="inputfieldsizeful inputbox validate-numeric"  maxlength="6" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_ttl"><?php echo Text::_('Time to live for resume feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_webmaster">
                                        <?php echo Text::_('Webmaster'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="rss_resume_webmaster" id="rss_resume_webmaster" value="<?php echo $this->config['rss_resume_webmaster']; ?>" class="inputfieldsize inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_webmaster"><?php echo Text::_('Leave blank if not show webmaster email address used for technical issue'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right closed -->

                            <div class="headtext headtext-second"><?php echo Text::_('Resume Block'); ?><span class="pro_version">*</span></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_categories">
                                        <?php echo Text::_('Show With Categories'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'rss_resume_categories', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['rss_resume_categories']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_categories"><?php echo Text::_('Use RSS categories with our resume categories'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_file">
                                        <?php echo Text::_('Show Resume File'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'rss_resume_file', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['rss_resume_file']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_file"><?php echo Text::_('Show resume file to downloadable from feed'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- left closed -->

                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="rss_resume_image">
                                        <?php echo Text::_('Resume Image'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'rss_resume_image', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['rss_resume_image']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="rss_resume_image"><?php echo Text::_('Show resume image with job feeds'); ?></label>     
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- right closed -->
                        </div>
                    </div>
                    <!-- LOGIN-REGISTER SETTING -->
                    <div id="log-setting" class="jsstadmin-hide-config">
                        <div class="idTabs config-tabs">
                            <a class="selected" href="#login"><?php echo Text::_('Login'); ?></a>
                            <a class="" href="#register"><?php echo Text::_('Register'); ?></a>
                        </div>
                        <div id="login" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Login'); ?></div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="default_login_from">
                                    <?php echo Text::_('Default Login layout'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $defaultloginfrom, 'default_login_from', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['default_login_from']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="default_login_from"><?php echo Text::_('User login from joomla layout or jsjobs layout'); ?></label>     
                                    </div>
                                </div>
                            </div>
							<div id="jsjob_configuration_wrapper" >
								<label class="jobs-config-title stylelabeltop labelcolortop" for="login_custom_link">
									<?php echo Text::_('Login Custom Link'); ?>
								</label>
								<div class="jobs-config-value">
									<input type="text" name="login_custom_link" id="login_custom_link" value="<?php echo $this->config['login_custom_link']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>"/>
									<div class="jobs-config-descript">
										<label class=" stylelabelbottom labelcolorbottom" for="login_custom_link"><?php echo Text::_('The user will be redirected to the link when he clicks on the login button'); ?></label>     
									</div>
								</div>
							</div>
                        </div>
                        <div id="register" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Register'); ?></div>
							<div id="jsjob_configuration_wrapper">
								<label class="jobs-config-title stylelabeltop labelcolortop" for="default_register_from">
									<?php echo Text::_('Default Register Page'); ?><span class="pro_version">*</span>
								</label>
								<div class="jobs-config-value">
									<?php echo HTMLHelper::_('select.genericList', $defaultregisterfrom, 'default_register_from', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['default_register_from']); ?>
									<div class="jobs-config-descript">
										<label class=" stylelabelbottom labelcolorbottom" for="default_register_from"><?php echo Text::_('User register from Joomla, JS Jobs layout or custom register page'); ?></label>     
									</div>
								</div>
							</div>
							<div id="jsjob_configuration_wrapper" >
								<label class="jobs-config-title stylelabeltop labelcolortop" for="register_custom_link">
									<?php echo Text::_('Registration Custom Link'); ?><span class="pro_version">*</span>
								</label>
								<div class="jobs-config-value">
									<input type="text" name="register_custom_link" id="register_custom_link" value="<?php echo $this->config['register_custom_link']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>"/>
									<div class="jobs-config-descript">
										<label class=" stylelabelbottom labelcolorbottom" for="register_custom_link"><?php echo Text::_('The user will be redirected to the link when he clicks on the registration button'); ?></label>     
									</div>
								</div>
							</div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="user_registration_captcha">
                                    <?php echo Text::_('Show Captcha On Registration Form'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'user_registration_captcha', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['user_registration_captcha']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="user_registration_captcha"><?php echo Text::_('Show captcha on JS Jobs registration form'); ?>.</label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="showemployerlink">
                                <?php echo Text::_('Allow User Register As Employer'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $yesno, 'showemployerlink', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['showemployerlink']); ?>
                                <div class="jobs-config-descript">
                                    <label class=" stylelabelbottom labelcolorbottom" for="showemployerlink"><?php echo Text::_('Effects on select role page'); ?></label>     
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- job sharing wrapper -->  
                    <!-- tab wrapper -->
                    <input type="hidden" name="layout" value="configurations" />
                    <input type="hidden" name="task" value="configuration.save" />
                    <input type="hidden" name="notgeneralbuttonsubmit" value="0" />
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <?php echo HTMLHelper::_( 'form.token' ); ?>  
                    <div class="js-form-button">
                        <input type="submit" name="save" id="save" value="<?php echo Text::_('Save').' '.Text::_('Configurations'); ?>" class="button js-form-save" onclick="Joomla.submitbutton('configuration.save');">
                    </div>
                </div>    
            </form>
        </div>
    </div>
</div>
<!-- content closed -->
</div>
<!-- main wrapper closed -->
<div class="proversiononly"><span class="pro_version">*</span><?php echo Text::_('Pro version only');?></div>
<div id="bottomend"></div>
<div id="jsjobs-footer">
    <table width="100%" style="table-layout:fixed;">
        <tr><td height="15"></td></tr>
        <tr>
            <td style="vertical-align:top;" align="center">
                <a class="img" target="_blank" href="https://www.joomsky.com"><img src="https://www.joomsky.com/logo/jsjobscrlogo.png"></a>
                <br>
                Copyright &copy; 2008 - <?php echo  date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">

    function dochange(src, val) {
        if (src == 'defaultsharingstate')
            var countryid = val;
        else if (src == 'defaultsharingcity')
            var stateid = val;
        jQuery("#" + src).html("Loading...");
        jQuery.post("index.php?option=com_jsjobs&task=jobsharing.defaultaddressdatajobsharing", {data: src, val: val}, function (data) {
            if (data) {
                if (data == "") {
                    return_value = "<input class='inputbox' type='text' name='default_sharing_state' id='default_sharing_state' readonly='readonly' size='40' maxlength='100'  />";
                        jQuery("#" + src).html(return_value); //retuen value
                        getcountrycity(val);
                    } else {
                        jQuery("#" + src).html(data); //retuen value
                        if (src == 'defaultsharingstate') {
                            cityhtml = "<input class='inputbox' type='text' name='default_sharing_city' readonly='readonly' size='40' maxlength='100'  />";
                            jQuery('#defaultsharingcity').html(cityhtml); //retuen value
                        }
                    }
                }
            });
    }

    function getcountrycity(countryid) {
        var src = 'defaultsharingcity';
        jQuery("#" + src).html("Loading...");
        jQuery.post("index.php?option=com_jsjobs&task=jobsharing.defaultaddressdatajobsharing", {data: src, state: -1, val: countryid}, function (data) {
            if (data) {
                    jQuery("#" + src).html(data); //retuen value
                }
            });
    }

    function hideshowtables(table_id) {
        var obj = document.getElementById(table_id);
        var bool = obj.style.display;
        if (bool == '')
            obj.style.display = "none";
        else
            obj.style.display = "";
    }

</script>
<style type="text/css">
    div#map_container{background:#000;width:100%;height:100%;}
    div#map{width:100%;height:100%;}
</style>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<script type="text/javascript">
    function loadMap() {
        var default_latitude = document.getElementById('default_latitude').value;
        var default_longitude = document.getElementById('default_longitude').value;
        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
        zoom = 10;
        var myOptions = {
            zoom: zoom,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
        var lastmarker = new google.maps.Marker({
            postiion: latlng,
            map: map,
        });
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });
        marker.setMap(map);
        lastmarker = marker;

        google.maps.event.addListener(map, "click", function (e) {
            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latLng}, function (results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                    if (lastmarker != '')
                        lastmarker.setMap(null);
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                    });
                    marker.setMap(map);
                    lastmarker = marker;
                    document.getElementById('default_latitude').value = marker.position.lat();
                    document.getElementById('default_longitude').value = marker.position.lng();

                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        });
    }
    function showdiv() {
        jQuery("div#js_jobs_main_popup_back").show();
        jQuery("div#js_jobs_main_popup_area").slideDown('slow');
    }
    function hidediv() {
        document.getElementById('map').style.visibility = 'hidden';
    }
</script>

<div id="js_jobs_main_popup_back"></div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("img#jspopup_image_close,div#js_jobs_main_popup_back").click(function(){
            jQuery("div#js_jobs_main_popup_area").slideUp('slow');
            setTimeout(function () {
                jQuery("div#js_jobs_main_popup_back").hide();
                jQuery("div#jspopup_work_area").html('');
            }, 700);
        });
    });
</script>
