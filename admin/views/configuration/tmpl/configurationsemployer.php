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
            f.check.value = '<?php if (JVERSION < '3')
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
        jQuery('#emp-setting').addClass('active');
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

$resumealert = array(
    '0' => array('value' => '', 'text' => Text::_('Select Option')),
    '1' => array('value' => 1, 'text' => Text::_('All Fields')),
    '2' => array('value' => 2, 'text' => Text::_('Only Filled Fields')),
);



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
                            <?php echo Text::_('Employer Configurations'); ?>
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
                <?php echo Text::_('Employer Configurations'); ?>
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
                    <a class="selected" href="#emp_generalsetting"><?php echo Text::_('General Settings'); ?></a>
                    <a  href="#emp_listresume"><?php echo Text::_('Search Resume'); ?></a>
                    <a  href="#email"><?php echo Text::_('Resume Data'); ?></a>
                    <a  href="#emp_approve"><?php echo Text::_('Auto Approve'); ?></a>
                    <a  href="#emp_company"><?php echo Text::_('Company'); ?></a>
                    <a  href="#emp_memberlinks"><?php echo Text::_('Members Links'); ?></a>
                </div>
                <!-- emp general setting -->
                <div id="emp_generalsetting" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('General Settings'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="employerview_js_controlpanel">
                                <?php echo Text::_('Employer Can View Job Seeker Area'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $yesno, 'employerview_js_controlpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['employerview_js_controlpanel']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="employerview_js_controlpanel"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="jsjobs_right_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="companygoldexpiryindays">
                                <?php echo Text::_('Gold company Expire In Days'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <input type="text" name="companygoldexpiryindays" id="companygoldexpiryindays" value="<?php echo $this->config['companygoldexpiryindays']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="companygoldexpiryindays"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="companyfeaturedexpiryindays">
                                <?php echo Text::_('Featured company Expire In Days'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <input type="text" name="companyfeaturedexpiryindays" id="companyfeaturedexpiryindays" value="<?php echo $this->config['companyfeaturedexpiryindays']; ?>" class="inputfieldsizeful inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="companyfeaturedexpiryindays"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- emp list resume -->
                <div id="emp_listresume" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Search Resume Form Settings'); ?></div>
                    <div id="jsjobs_left_main">
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_showsave">
                            <?php echo Text::_('Allow Save Search'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'search_resume_showsave', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_showsave']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="search_resume_showsave"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <?php /*
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_salaryrange">
                            <?php echo Text::_('Salary Range'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_salaryrange', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_salaryrange']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_salaryrange"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_title">
                            <?php echo Text::_('Title'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_title', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_title']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_title"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_gender">
                            <?php echo Text::_('Gender'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_gender', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_gender']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_gender"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_name">
                            <?php echo Text::_('Name'); ?>
                        </label>
                        <div class="jobs-config-value">
                           <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_name', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_name']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_name"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_type">
                            <?php echo Text::_('Type'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_type', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_type']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_type"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_category">
                            <?php echo Text::_('Category'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_category', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_category']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_category"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                     <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_available">
                            <?php echo Text::_('Available'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_available', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_available']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_available"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>
                    */ ?>
                    </div>
                    <!-- left closed -->
                    <?php /*
                    <div id="jsjobs_right_main">
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_experience">
                            <?php echo Text::_('Experience'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_experience', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_experience']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_experience"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_nationality">
                            <?php echo Text::_('Nationality'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_nationality', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_nationality']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_nationality"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_heighesteducation">
                            <?php echo Text::_('Highest Education'); ?>
                        </label>
                        <div class="jobs-config-value">
                           <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_heighesteducation', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_heighesteducation']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_heighesteducation"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_subcategory">
                            <?php echo Text::_('Sub Category'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_subcategory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_subcategory']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_subcategory"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_keywords">
                            <?php echo Text::_('Keywords'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_keywords', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_keywords']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_keywords"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_zipcode">
                            <?php echo Text::_('Zip Code'); ?>
                        </label>
                        <div class="jobs-config-value">
                           <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_zipcode', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_zipcode']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_zipcode"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>

                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="search_resume_location">
                            <?php echo Text::_('Location'); ?>
                        </label>
                        <div class="jobs-config-value">
                           <?php echo HTMLHelper::_('select.genericList', $showhide, 'search_resume_location', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['search_resume_location']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="search_resume_location"><?php echo Text::_(''); ?></label>     
                        </div>
                    </div>
     
                    </div>
                    <!-- right closed -->  
                  */ ?>
                </div>
                <!-- emp email -->
                <div id="email" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Email Alert To Employer On Resume Apply'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="employer_resume_alert_fields">
                                <?php echo Text::_('What Include In Email'); ?><span class="pro_version">*</span>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $resumealert, 'employer_resume_alert_fields', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['employer_resume_alert_fields']); ?>
                                <div class="jobs-config-descript">
                                    <label class=" stylelabelbottom labelcolorbottom" for="employer_resume_alert_fields"><?php echo Text::_('All fields are included in employer email content or only filled fields').'. '. Text::_('This option is valid if the employer select send resume data in email settings from the job'); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- emp auto approve -->
                <div id="emp_approve" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Auto Approve'); ?></div>
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="companyautoapprove">
                            <?php echo Text::_('Company Auto Approve'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'companyautoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['companyautoapprove']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="companyautoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper">
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="jobautoapprove">
                            <?php echo Text::_('Job Auto Approve'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'jobautoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobautoapprove']); ?>
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="jobautoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="department_auto_approve">
                            <?php echo Text::_('Department Auto Approve'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'department_auto_approve', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['department_auto_approve']); ?> 
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="department_auto_approve"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="folder_auto_approve">
                            <?php echo Text::_('Folder Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'folder_auto_approve', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['folder_auto_approve']); ?> 
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="folder_auto_approve"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="goldcompany_autoapprove">
                            <?php echo Text::_('Gold Company Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'goldcompany_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['goldcompany_autoapprove']); ?> 
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="goldcompany_autoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="featuredcompany_autoapprove">
                            <?php echo Text::_('Featured Company Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'featuredcompany_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['featuredcompany_autoapprove']); ?> 
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="featuredcompany_autoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="goldjob_autoapprove">
                            <?php echo Text::_('Gold Job Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'goldjob_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['goldjob_autoapprove']); ?> 
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="goldjob_autoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                    <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="featuredjob_autoapprove">
                            <?php echo Text::_('Featured job Auto Approve'); ?><span class="pro_version">*</span>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $yesno, 'featuredjob_autoapprove', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['featuredjob_autoapprove']); ?> 
                            <div class="jobs-config-descript empty-desc">
                                <label class=" stylelabelbottom labelcolorbottom" for="featuredjob_autoapprove"><?php echo Text::_(''); ?></label>     
                            </div>
                        </div>
                    </div>
                </div>
                <!-- emp company -->
                <div id="emp_company" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Company Settings'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="comp_name">
                                <?php echo Text::_('Company Name'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'comp_name', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['comp_name']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="comp_name"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="comp_email_address">
                                <?php echo Text::_('Company Email Address'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'comp_email_address', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['comp_email_address']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="comp_email_address"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="comp_city">
                                <?php echo Text::_('City'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'comp_city', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['comp_city']); ?></td>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="comp_city"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="jsjobs_right_main">
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="comp_show_url">
                                <?php echo Text::_('Company Url'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'comp_show_url', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['comp_show_url']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="comp_show_url"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="comp_zipcode">
                                <?php echo Text::_('Zip Code'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'comp_zipcode', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['comp_zipcode']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="comp_zipcode"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <!-- emp visitor links -->
                <div id="emp_memberlinks" class="jsstadmin-config-gen-body">
                    <div class="headtext"><?php echo Text::_('Employer Top Menu Links'); ?></div>
                    <div id="jsjobs_left_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emcontrolpanel">
                                <?php echo Text::_('Control Panel'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emcontrolpanel', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emcontrolpanel']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emcontrolpanel"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emnewjob">
                                <?php echo Text::_('New Job'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emnewjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emnewjob']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emnewjob"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emmyjobs">
                                <?php echo Text::_('My Jobs'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emmyjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emmyjobs']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emmyjobs"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="jsjobs_right_main">
                        <div id="jsjob_configuration_wrapper">
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emmycompanies">
                                <?php echo Text::_('My Companies'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emmycompanies', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emmycompanies']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emmycompanies"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <div id="jsjob_configuration_wrapper" >
                            <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emsearchresume">
                                <?php echo Text::_('Resume Search'); ?>
                            </label>
                            <div class="jobs-config-value">
                                <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emsearchresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emsearchresume']); ?>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emsearchresume"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                        </div>
                        <?php /*
                        <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emnewcompany">
                            <?php echo Text::_('New Company'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emnewcompany', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emnewcompany']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emnewcompany"><?php echo Text::_(''); ?></label>     
                        </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emnewdepartment">
                            <?php echo Text::_('New Department'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emnewdepartment', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emnewdepartment']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emnewdepartment"><?php echo Text::_(''); ?></label>     
                        </div>
                        </div>

                        <div id="jsjob_configuration_wrapper" >
                        <label class="jobs-config-title stylelabeltop labelcolortop" for="tmenu_emnewfolder">
                            <?php echo Text::_('New Folder'); ?>
                        </label>
                        <div class="jobs-config-value">
                            <?php echo HTMLHelper::_('select.genericList', $showhide, 'tmenu_emnewfolder', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['tmenu_emnewfolder']); ?>
                        </div>
                        <div class="jobs-config-descript">
                            <label class=" stylelabelbottom labelcolorbottom" for="tmenu_emnewfolder"><?php echo Text::_(''); ?></label>     
                        </div>
                        </div> */ ?>
                    </div>
                    <div id="emp_visitorlinks" class="">
                        <div class="headtext headtext-second"><?php echo Text::_('Employer Control Panel Links'); ?></div>
                        <div id="jsjobs_left_main">
                            <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="myjobs_counts">
                                        <?php echo Text::_('My Jobs Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'myjobs_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['myjobs_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="myjobs_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="appliedresume_counts">
                                        <?php echo Text::_('Applied Resume Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'appliedresume_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['appliedresume_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="appliedresume_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="mycompanies_counts">
                                        <?php echo Text::_('My Companies Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'mycompanies_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['mycompanies_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="mycompanies_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                    <label class="jobs-config-title stylelabeltop labelcolortop" for="myresumesearches_counts">
                                        <?php echo Text::_('Resume Save Searches Counts'); ?>
                                    </label>
                                    <div class="jobs-config-value">
                                        <?php echo HTMLHelper::_('select.genericList', $showhide, 'myresumesearches_counts', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['myresumesearches_counts']); ?>
                                        <div class="jobs-config-descript empty-desc">
                                            <label class=" stylelabelbottom labelcolorbottom" for="myresumesearches_counts"><?php echo Text::_(''); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="jobs_graph">
                                    <?php echo Text::_('Jobs Graph'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'jobs_graph', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['jobs_graph']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="jobs_graph"><?php echo Text::_(''); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="resume_graph">
                                    <?php echo Text::_('Resume Graph'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'resume_graph', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resume_graph']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="resume_graph"><?php echo Text::_(''); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="box_newestresume">
                                    <?php echo Text::_('Newest Resume Box'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'box_newestresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['box_newestresume']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="box_newestresume"><?php echo Text::_(''); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="box_appliedresume">
                                    <?php echo Text::_('Applied Resume Box'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'box_appliedresume', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['box_appliedresume']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="box_appliedresume"><?php echo Text::_(''); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="mystuff_area">
                                    <?php echo Text::_('My Stuff Area'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'mystuff_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['mystuff_area']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="mystuff_area"><?php echo Text::_(''); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="mystats_area">
                                    <?php echo Text::_('My Stats Area'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'mystats_area', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['mystats_area']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="mystats_area"><?php echo Text::_(''); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="mycompanies">
                                    <?php echo Text::_('My Companies'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'mycompanies', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['mycompanies']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="mycompanies"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="formcompany">
                                    <?php echo Text::_('New Company'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'formcompany', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['formcompany']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="formcompany"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <?php /*
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="alljobsappliedapplications">
                                    <?php echo Text::_('Applied Resume'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'alljobsappliedapplications', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['alljobsappliedapplications']); ?>
                                </div>
                                <div class="jobs-config-descript">
                                    <label class=" stylelabelbottom labelcolorbottom" for="alljobsappliedapplications"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="myjobs">
                                    <?php echo Text::_('My Jobs'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'myjobs', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['myjobs']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="myjobs"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="formjob">
                                    <?php echo Text::_('New Job'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'formjob', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['formjob']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="formjob"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            */ ?>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="mydepartment">
                                    <?php echo Text::_('My Departments'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'mydepartment', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['mydepartment']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="mydepartment"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="myfolders">
                                    <?php echo Text::_('Folders'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'myfolders', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['myfolders']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="myfolders"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="newfolders">
                                    <?php echo Text::_('New Folder'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'newfolders', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['newfolders']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="newfolders"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <?php /*
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="resumesearch">
                                    <?php echo Text::_('Resume Search'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'resumesearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resumesearch']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="resumesearch"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div> */ ?>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="my_resumesearches">
                                    <?php echo Text::_('Resume Save Searches'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'my_resumesearches', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['my_resumesearches']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="my_resumesearches"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="jsjobs_right_main">
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="resumesearch">
                                    <?php echo Text::_('Resume By Categories'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'resumesearch', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['resumesearch']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="resumesearch"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="empexpire_package_message">
                                    <?php echo Text::_('Expire Package Message'); ?>
                                </label>
                                <div class="jobs-config-value">
                                   <?php echo HTMLHelper::_('select.genericList', $showhide, 'empexpire_package_message', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['empexpire_package_message']); ?>
                                </div>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="empexpire_package_message"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="empregister">
                                    <?php echo Text::_('Employer Profile'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'empregister', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['empregister']); ?>
                                </div>
                                <div class="jobs-config-descript empty-desc">
                                    <label class=" stylelabelbottom labelcolorbottom" for="empregister"><?php echo Text::_(''); ?></label>     
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="packages">
                                    <?php echo Text::_('Packages'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'packages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['packages']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="packages"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="formdepartment">
                                    <?php echo Text::_('New Department'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'formdepartment', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['formdepartment']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="formdepartment"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="purchasehistory">
                                    <?php echo Text::_('Purchase History'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'purchasehistory', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['purchasehistory']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="purchasehistory"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="empmessages">
                                    <?php echo Text::_('Messages'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'empmessages', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['empmessages']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="empmessages"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="my_stats">
                                    <?php echo Text::_('My Stats'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'my_stats', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['my_stats']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="my_stats"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="empresume_rss">
                                    <?php echo Text::_('Resume RSS'); ?><span class="pro_version">*</span>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'empresume_rss', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['empresume_rss']); ?>
                                    <div class="jobs-config-descript empty-desc">
                                        <label class=" stylelabelbottom labelcolorbottom" for="empresume_rss"><?php echo Text::_(''); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper" >
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="emploginlogout">
                                    <?php echo Text::_('Show Login/logout Button'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $yesno, 'emploginlogout', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['emploginlogout']); ?> 
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="emploginlogout"><?php echo Text::_('Show Login/logout Button In Employer Control Panel'); ?></label>     
                                    </div>
                                </div>
                            </div>
                            <div id="jsjob_configuration_wrapper">
                                <label class="jobs-config-title stylelabeltop labelcolortop" for="userdata_employer">
                                    <?php echo Text::_('User data request'); ?>
                                </label>
                                <div class="jobs-config-value">
                                    <?php echo HTMLHelper::_('select.genericList', $showhide, 'userdata_employer', 'class="inputfieldsizeful inputbox" ' . '', 'value', 'text', $this->config['userdata_employer']); ?>
                                    <div class="jobs-config-descript">
                                        <label class=" stylelabelbottom labelcolorbottom" for="userdata_employer"><?php echo Text::_('GDPR user erase data request'); ?></label>     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- wrapper closed -->
        <input type="hidden" name="layout" value="configurationsemployer" />
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

