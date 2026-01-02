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

// $document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');



global $mainframe;
?>

<script language="javascript">
    jQuery(document).ready(function () {
        var value = jQuery("#showapplybutton").val();
        var divsrc = "div#showhideapplybutton";
        if (value == 2) {
            jQuery(divsrc).slideDown("slow");
        }
    });
    function showhideapplybutton(src, value) {
        var divsrc = "div#" + src;
        if (value == 2) {
            jQuery(divsrc).slideDown("slow");
        } else if (value == 1) {
            jQuery(divsrc).slideUp("slow");
            jQuery(divsrc).hide();
        }
        return true;
    }


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
            f.check.value = '<?php
if (JVERSION < '3')
    echo JUtility::getToken();
else
    echo Factory::getSession()->getFormToken();
?>';//send token
        } else {
            alert("<?php echo Text::_("Some values are not acceptable please check all tabs"); ?>");
            return false;
        }
        return true;
    }

    jQuery(document).ready(function ($) {
        jQuery('#js-setting').addClass('active');
    });
</script>

<?php
$ADMINPATH = JPATH_BASE . '\components\com_jsjobs';

$yesno = array(
    '0' => array('value' => 1,
        'text' => Text::_('JYES')),
    '1' => array('value' => 0,
        'text' => Text::_('JNO')),);

$showhide = array(
    '0' => array('value' => 1,
        'text' => Text::_('Show')),
    '1' => array('value' => 0,
        'text' => Text::_('Hide')),);

$applybutton = array(
    '0' => array('value' => 1,
        'text' => Text::_('Enable')),
    '1' => array('value' => 0,
        'text' => Text::_('Disable')),);




$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
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
                            <?php echo Text::_('Job Seeker Configurations'); ?>
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
                <?php echo Text::_('Job Seeker Configurations'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('configuration.save');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Save').' '.Text::_('Configurations'); ?>">
                <img alt="<?php echo Text::_('All Jobs'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Save').' '.Text::_('Configurations'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 jsstadmin-config-data-wrp">
         <form action="index.php" method="POST" name="adminForm" id="adminForm">
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
                    <!-- tab id div -->
                        <div class="idTabs config-tabs">
                            <a class="selected" href="#js_generalsetting"><?php echo Text::_('General Settings'); ?></a>
                            <a  href="#js_defualtsetting"><?php echo Text::_('Resume Settings'); ?></a>
                            <a  href="#js_jobsearch"><?php echo Text::_('Job Search'); ?></a>
                            <a  href="#js_approve"><?php echo Text::_('Auto Approve'); ?></a>
                            <a  href="#js_memberlinks"><?php echo Text::_('Member Links'); ?></a>
                        </div>
                        <!-- js general setting-->
                        <div id="js_generalsetting" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('General Settings'); ?></div>
                            <div id="jsjobs_left_main">
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="resumegoldexpiryindays">
                                        <?php echo Text::_('Gold Resume Expire In Days'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="resumegoldexpiryindays" id="resumegoldexpiryindays" value="<?php echo $this->config['resumegoldexpiryindays']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="resumegoldexpiryindays"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="resumefeaturedexpiryindays">
                                        <?php echo Text::_('Featured Resume Expire In Days'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <input type="text" name="resumefeaturedexpiryindays" id="resumefeaturedexpiryindays" value="<?php echo $this->config['resumefeaturedexpiryindays']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="resumefeaturedexpiryindays"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="show_applied_resume_status">
                                        <?php echo Text::_('Show Applied Resume Status'); ?><span class="pro_version">*</span>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'show_applied_resume_status', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['show_applied_resume_status']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="show_applied_resume_status"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div id="jsjobs_right_main">
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="showapplybutton">
                                        <?php echo Text::_('Show Apply Now Button'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $applybutton, 'showapplybutton', 'class="inputfieldsizeful inputbox"' . 'onChange="showhideapplybutton(\'showhideapplybutton\', this.value)"', 'value', 'text', $this->config['showapplybutton']); ?>
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="showapplybutton"><?php echo Text::_('Controls the visibilty of apply now button in plugin'); ?></label>     
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="apllybuttonshow">
                                        <?php echo Text::_('Apply Now Redirect Link'); ?><span class="pro_version">*</span>
                                    </label>                            
                                    <div class="jobs-config-value" id="defhiden" for="apllybuttonshow">
                                        <input type="text" id="apllybuttonshow" name="applybuttonredirecturl" class="inputfieldsizeful inputbox" value="<?php echo $this->config['applybuttonredirecturl']; ?>" size="<?php echo $big_field_width; ?>" >
                                        <div class="jobs-config-descript">
                                            <label class=" stylelabelbottom labelcolorbottom" for="apllybuttonshow"><?php echo Text::_('Click on Apply Now button will be redirect to given url'); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper" >
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="show_only_section_that_have_value">
                                        <?php echo Text::_('Show Only The Sections That Have Value'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $yesno, 'show_only_section_that_have_value', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['show_only_section_that_have_value']); ?> 
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="show_only_section_that_have_value"><?php echo Text::_(''); ?></label>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- default setting -->
                        <div id="js_defualtsetting" class="jsstadmin-config-gen-body">
                            <div class="headtext"><?php echo Text::_('Resume Settings'); ?></div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="resume_photofilesize">
                                    <?php echo Text::_('Resume Photo Size Allowed'); ?>
                                </label>
                                <div class="jobs-config-value">
                                   <input type="text" name="resume_photofilesize" id="resume_photofilesize" value="<?php echo $this->config['resume_photofilesize']; ?>" class="inputfieldsize inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /><label class="jobs-mini-descp" for="resume_photofilesize"><?php echo Text::_('Kb'); ?></label>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="resume_photofilesize"><?php echo Text::_('Max file size allowed'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="max_resume_employers">
                                    <?php echo Text::_('Number Of Employers Allowed'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="max_resume_employers" id="max_resume_employers" value="<?php echo $this->config['max_resume_employers']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="max_resume_employers"><?php echo Text::_('Maximum number of employers allow in resume'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="max_resume_addresses">
                                    <?php echo Text::_('Number Of Addresses Allowed'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="max_resume_addresses" id="max_resume_addresses" value="<?php echo $this->config['max_resume_addresses']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="max_resume_addresses"><?php echo Text::_('Maximum number of addresses allows in resume'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="max_resume_institutes">
                                    <?php echo Text::_('Number Of Institute Allowed'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="max_resume_institutes" id="max_resume_institutes" value="<?php echo $this->config['max_resume_institutes']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="max_resume_institutes"><?php echo Text::_('Maximum number of institutes allow in resume'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="max_resume_languages">
                                    <?php echo Text::_('Number Of Languages Allowed'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <input type="text" name="max_resume_languages" id="max_resume_languages" value="<?php echo $this->config['max_resume_languages']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="max_resume_languages"><?php echo Text::_('Maximum number of languages allow in resume'); ?></label>     
                                    </div>
                                </div>
                            </div>
                                    <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="max_resume_references">
                                    <?php echo Text::_('Number Of References Allowed'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <input type="text" name="max_resume_references" id="max_resume_references" value="<?php echo $this->config['max_resume_references']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="max_resume_references"><?php echo Text::_('Maximum number of references allow in resume'); ?></label>     
                            </div>
                        </div>
                    </div>
                </div>
                <!-- js job search -->
                <div id="js_jobsearch" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Search Job Settings'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_showsave">
                                <?php echo Text::_('Allow Save Search'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $yesno, 'search_job_showsave', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_showsave']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="search_job_showsave"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php /*
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_title">
                                <?php echo Text::_('Title'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_title', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_title']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_title"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_category">
                                <?php echo Text::_('Category'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_category', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_category']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_category"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_type">
                                <?php echo Text::_('Job Type'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_type', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_type']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_type"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_shift">
                                <?php echo Text::_('Shifts'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_shift', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_shift']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_shift"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>


                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_durration">
                                <?php echo Text::_('Duration'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_durration', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_durration']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_durration"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_startpublishing">
                                <?php echo Text::_('Start Publishing'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_startpublishing', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_startpublishing']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_startpublishing"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_stoppublishing">
                                <?php echo Text::_('Stop Publishing'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_stoppublishing', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_stoppublishing']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_stoppublishing"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_status">
                                <?php echo Text::_('Job Status'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_status', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_status']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_status"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>  
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_careerlevel">
                                <?php echo Text::_('Career Level'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_careerlevel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_careerlevel']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_careerlevel"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_workpermit">
                                <?php echo Text::_('Work Permit'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_workpermit', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_workpermit']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_workpermit"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_reqiuredtravel">
                                <?php echo Text::_('Required Travel'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_reqiuredtravel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_reqiuredtravel']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_reqiuredtravel"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        */ ?>
                    </div>
                    <!-- left closed -->
                    <?php /* 
                    <div id="jsjobs_right_main">

                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_company">
                                <?php echo Text::_('Company'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_company', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_company']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_company"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_companysite">
                                <?php echo Text::_('Company Site'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_companysite', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_companysite']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_companysite"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                     
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_subcategory">
                                <?php echo Text::_('Sub Category'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_subcategory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_subcategory']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_subcategory"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_salaryrange">
                                <?php echo Text::_('Salary Range'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_salaryrange', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_salaryrange']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_salaryrange"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_city">
                                <?php echo Text::_('City'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_city', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_city']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_city"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        
                           
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_zipcode">
                                <?php echo Text::_('Zip Code'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_zipcode', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_zipcode']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_zipcode"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_coordinates">
                                <?php echo Text::_('Map Coordinates'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_coordinates', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_coordinates']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_coordinates"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_keywords">
                                <?php echo Text::_('Keywords'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_keywords', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_keywords']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_keywords"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_gender">
                                <?php echo Text::_('Gender'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_gender', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_gender']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_gender"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_education">
                                <?php echo Text::_('Highest Education'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_education', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_education']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_education"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="search_job_experience">
                                <?php echo Text::_('Experience'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_job_experience', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_job_experience']); ?>
                            </div>
                            <div class="jobs-config-descript">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_job_experience"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                        
         
                      </div>
                      <!-- right closed --> 
                      */ ?> 
                    </div>
                <!-- js auto approve -->
                <div id="js_approve" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Resume Auto Approve'); ?></div>
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="empautoapprove">
                            <?php echo Text::_('Resume Auto Approve'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'empautoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['empautoapprove']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="empautoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="goldresume_autoapprove">
                            <?php echo Text::_('Gold Resume Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'goldresume_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['goldresume_autoapprove']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="goldresume_autoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="featuredresume_autoapprove">
                            <?php echo Text::_('Featured Resume Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'featuredresume_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['featuredresume_autoapprove']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="featuredresume_autoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="jobalert_auto_approve">
                            <?php echo Text::_('Job Alert Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'jobalert_auto_approve', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobalert_auto_approve']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="jobalert_auto_approve"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                </div>
                <!-- js member links -->
                <div id="js_memberlinks" class="jsstadmin-config-gen-body"> 
                    <div class="headtext"><?php echo Text::_('Job Seeker Top Menu Links'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_jscontrolpanel">
                                <?php echo Text::_('Control Panel'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_jscontrolpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_jscontrolpanel']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_jscontrolpanel"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_jsnewestjob">
                                <?php echo Text::_('Newest Jobs'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_jsnewestjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_jsnewestjob']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_jsnewestjob"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_jsjobcategory">
                                <?php echo Text::_('Job Categories'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_jsjobcategory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_jsjobcategory']);?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_jsjobcategory"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="jsjobs_right_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_jssearchjob">
                                <?php echo Text::_('Search Jobs'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_jssearchjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_jssearchjob']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_jssearchjob"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_jsmyresume">
                                <?php echo Text::_('My Resumes'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_jsmyresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_jsmyresume']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_jsmyresume"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div><div class="headtext"><?php echo Text::_('Job Seeker Control Panel Links'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="newestjobs_counts">
                                <?php echo Text::_('Newest Jobs Counts'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'newestjobs_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['newestjobs_counts']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="newestjobs_counts"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="appliedjobs_counts">
                                <?php echo Text::_('Applied Jobs Counts'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'appliedjobs_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['appliedjobs_counts']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="appliedjobs_counts"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="myresumes_counts">
                                <?php echo Text::_('My Resumes Counts'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'myresumes_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['myresumes_counts']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="myresumes_counts"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="myjobsearches_counts">
                                <?php echo Text::_('Job Save Searches Counts'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'myjobsearches_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['myjobsearches_counts']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="myjobsearches_counts"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsactivejobs_graph">
                                <?php echo Text::_('Active Jobs Graph'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsactivejobs_graph', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsactivejobs_graph']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsactivejobs_graph"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsmystuff_area">
                                <?php echo Text::_('My Stuff Area'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsmystuff_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsmystuff_area']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsmystuff_area"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsappliedresume_box">
                                <?php echo Text::_('Applied Resume Box'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsappliedresume_box', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsappliedresume_box']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsappliedresume_box"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsmyresumes">
                                <?php echo Text::_('My Resumes'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsmyresumes', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsmyresumes']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsmyresumes"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php */ ?>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jobshortlist">
                                <?php echo Text::_('Short Listed Jobs'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jobshortlist', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobshortlist']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jobshortlist"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="formresume">
                                <?php echo Text::_('Add Resume'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'formresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['formresume']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="formresume"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jobcat">
                                <?php echo Text::_('Jobs By Categories'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jobcat', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobcat']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jobcat"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jslistnewestjobs">
                                <?php echo Text::_('Newest Jobs'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jslistnewestjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jslistnewestjobs']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jslistnewestjobs"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jslistallcompanies">
                                <?php echo Text::_('All Companies'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jslistallcompanies', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jslistallcompanies']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jslistallcompanies"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jslistjobbytype">
                                <?php echo Text::_('Job By Types'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jslistjobbytype', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jslistjobbytype']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jslistjobbytype"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php /* ?>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsmyappliedjobs">
                                <?php echo Text::_('My Applied Jobs'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsmyappliedjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsmyappliedjobs']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsmyappliedjobs"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php */ ?>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsmystats_area">
                                <?php echo Text::_('My Stats Area'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsmystats_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsmystats_area']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsmystats_area"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsnewestjobs_box">
                                <?php echo Text::_('Suggested Jobs Box'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsnewestjobs_box', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsnewestjobs_box']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsnewestjobs_box"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="userdata_jobseeker">
                                <?php echo Text::_('User data request'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'userdata_jobseeker', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['userdata_jobseeker']); ?>
                                <div class="jobs-config-descript">
                                    <label class=" stylelabelbottom labelcolorbottom" for="userdata_jobseeker"><?php echo Text::_('GDPR user erase data request'); ?></label>     
                                </div>
                            </div>
                        </div>       
                    </div>
                    <div id="jsjobs_right_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jobsearch">
                                <?php echo Text::_('Search Jobs'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jobsearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobsearch']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jobsearch"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="my_jobsearches">
                                <?php echo Text::_('Job Save Searches'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'my_jobsearches', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['my_jobsearches']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="my_jobsearches"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="mycoverletters">
                                <?php echo Text::_('My Cover Letters'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'mycoverletters', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['mycoverletters']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="mycoverletters"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="formcoverletter">
                                <?php echo Text::_('Add Cover Letter'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'formcoverletter', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['formcoverletter']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="formcoverletter"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jspurchasehistory">
                                <?php echo Text::_('Purchase History'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jspurchasehistory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jspurchasehistory']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jspurchasehistory"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jspackages">
                                <?php echo Text::_('Packages'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jspackages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jspackages']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jspackages"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsexpire_package_message">
                                <?php echo Text::_('Expire Package Message'); ?>
                            </label>
                            <div class="jobs-config-value">
                               <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsexpire_package_message', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsexpire_package_message']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsexpire_package_message"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsregister">
                                <?php echo Text::_('Job Seeker Profile'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsregister', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsregister']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsregister"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="listjobshortlist">
                                <?php echo Text::_('Short Listed Jobs'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'listjobshortlist', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['listjobshortlist']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="listjobshortlist"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsmy_stats">
                                <?php echo Text::_('My Stats'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsmy_stats', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsmy_stats']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsmy_stats"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jobalertsetting">
                                <?php echo Text::_('Job Alert'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jobalertsetting', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobalertsetting']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jobalertsetting"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jsmessages">
                                <?php echo Text::_('Messages'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'jsmessages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jsmessages']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jsmessages"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="job_rss">
                                <?php echo Text::_('Jobs RSS'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'job_rss', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['job_rss']);?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="job_rss"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>                                           
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="jobsloginlogout">
                                <?php echo Text::_('Show Login/logout Button'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $yesno, 'jobsloginlogout', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobsloginlogout']); ?> 
                                <div class="jobs-config-descript">
                                    <label class=" stylelabelbottom labelcolorbottom" for="jobsloginlogout"><?php echo Text::_('Show Login/logout Button In Job Seeker Control Panel'); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div><!-- wrapper closed -->
    <input type="hidden" name="layout" value="configurationsjobseeker" />
    <input type="hidden" name="task" value="configuration.save" />
    <input type="hidden" name="notgeneralbuttonsubmit" value="1" />
    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        <?php echo HTMLHelper::_( 'form.token' ); ?>
        <div class="js-form-button">
            <input type="submit" name="save" id="save" value="<?php echo Text::_('Save').' '.Text::_('Configurations') ?>" class="button js-form-save" onclick="Joomla.submitbutton('configuration.save');">
        </div>        
    </form>
</div>
    </div><!-- jsjobs content -->
</div><!-- jsjobs wrapper -->
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









