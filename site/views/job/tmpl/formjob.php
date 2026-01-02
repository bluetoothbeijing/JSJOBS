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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Editor\Editor;

HTMLHelper::_('behavior.formvalidator');
//HTMLHelper::_('behavior.calendar'); 
HTMLHelper::_('jquery.framework');
$document = Factory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/combobox/prism.js');

//$document->addScript('components/com_jsjobs/js/chosen/chosen.jquery.min.js');
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/combobox/chosen.jquery.js');

$conf   = Factory::getConfig();
$editor = Editor::getInstance($conf->get('editor'));

if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';
$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;

?>
<style type="text/css">
    #coordinatebutton{ float:right; clear:both; }
    #coordinatebutton .cbutton{ padding:1; font-size: 1.08em; }
    #coordinatebutton .cbutton:hover{ font-size: 1.08em; padding:1; }
</style>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>
<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    ?>
    <script type="text/javascript">
        function check_start_stop_job_publishing_dates(forcall) {
            var date_start_make = new Array();
            var date_stop_make = new Array();
            var split_start_value = new Array();
            var split_stop_value = new Array();
            var returnvalue = true;

            var start_string = document.getElementById("job_startpublishing").value;
            var stoppublishing_obj = document.getElementById("stoppublishing"); // when job exipry in days ,week or month  
            if (typeof stoppublishing_obj !== 'undefined' && stoppublishing_obj !== null) {
                var stoppublishing_value = document.getElementById("stoppublishing").value;
                if (stoppublishing_value)
                    return true; // no comparision with dates because stop date is enforce unpublished form the package 
            } else {
                var stop_string = document.getElementById("job_stoppublishing").value;
            }
            var format_type = document.getElementById("j_dateformat").value;
            if (format_type == 'd-m-Y') {
                split_start_value = start_string.split('-');

                date_start_make['year'] = split_start_value[2];
                date_start_make['month'] = split_start_value[1];
                date_start_make['day'] = split_start_value[0];

                split_stop_value = stop_string.split('-');

                date_stop_make['year'] = split_stop_value[2];
                date_stop_make['month'] = split_stop_value[1];
                date_stop_make['day'] = split_stop_value[0];

            } else if (format_type == 'm/d/Y') {
                split_start_value = start_string.split('/');
                date_start_make['year'] = split_start_value[2];
                date_start_make['month'] = split_start_value[0];
                date_start_make['day'] = split_start_value[1];

                split_stop_value = stop_string.split('/');

                date_stop_make['year'] = split_stop_value[2];
                date_stop_make['month'] = split_stop_value[0];
                date_stop_make['day'] = split_stop_value[1];

            } else if (format_type == 'Y-m-d') {

                split_start_value = start_string.split('-');

                date_start_make['year'] = split_start_value[0];
                date_start_make['month'] = split_start_value[1];
                date_start_make['day'] = split_start_value[2];

                split_stop_value = stop_string.split('-');

                date_stop_make['year'] = split_stop_value[0];
                date_stop_make['month'] = split_stop_value[1];
                date_stop_make['day'] = split_stop_value[2];

            }
            var start = new Date(date_start_make['year'], date_start_make['month'] - 1, date_start_make['day']);
            var stop = new Date(date_stop_make['year'], date_stop_make['month'] - 1, date_stop_make['day']);
            if (start >= stop) {
                if (forcall == 1) {
                    jQuery("#job_startpublishing").addClass("invalid");
                    var isedit = document.getElementById("id");
                    if (isedit.value != "" && isedit.value != 0) { // for edit case
                        alert("<?php echo Text::_("Start publishing date must be less than stop publishing date"); ?>");
                    } else { // for add case
                        alert("<?php echo Text::_("Start publishing date must be greater than today date"); ?>");

                    }
                } else if (forcall == 2) {
                    jQuery("#job_stoppublishing").addClass("invalid");
                    alert("<?php echo Text::_("Stop publishing date must be greater than start publishing date"); ?>");
                }
                returnvalue = false;
            }
            return returnvalue;
        }

        function validate_checkstartdate() {
            var date_start_make = new Array();
            var split_start_value = new Array();
            f = document.adminForm;
            var isstartpubreadonly = document.getElementById("startpubreadonly");
            if (typeof isstartpubreadonly !== 'undefined' && isstartpubreadonly !== null) {
                if (isstartpubreadonly.value != "" && isstartpubreadonly.value != 0) {
                    return true;
                }
            } else {
                var isedit = document.getElementById("id");
                if (isedit.value != "" && isedit.value != 0) {
                    var return_value = check_start_stop_job_publishing_dates(1);
                    return return_value;
                } else {
                    var returnvalue = true;
                    var today = new Date()
                    today.setHours(0, 0, 0, 0);

                    var start_string = document.getElementById("job_startpublishing").value;
                    var format_type = document.getElementById("j_dateformat").value;
                    if (format_type == 'd-m-Y') {
                        split_start_value = start_string.split('-');

                        date_start_make['year'] = split_start_value[2];
                        date_start_make['month'] = split_start_value[1];
                        date_start_make['day'] = split_start_value[0];


                    } else if (format_type == 'm/d/Y') {
                        split_start_value = start_string.split('/');
                        date_start_make['year'] = split_start_value[2];
                        date_start_make['month'] = split_start_value[0];
                        date_start_make['day'] = split_start_value[1];


                    } else if (format_type == 'Y-m-d') {

                        split_start_value = start_string.split('-');

                        date_start_make['year'] = split_start_value[0];
                        date_start_make['month'] = split_start_value[1];
                        date_start_make['day'] = split_start_value[2];
                    }

                    var startpublishingdate = new Date(date_start_make['year'], date_start_make['month'] - 1, date_start_make['day']);

                    if (today > startpublishingdate) {
                        jQuery("#job_startpublishing").addClass("invalid");
                        var isedit = document.getElementById("id");
                        if (isedit.value != "" && isedit.value != 0) { // for edit case
                            alert("<?php echo Text::_("Start publishing date must be less than stop publishing date"); ?>");
                        } else { // for add case
                            alert("<?php echo Text::_("Start publishing date must be greater than today date"); ?>");

                        }
                        returnvalue = false;
                    }
                    return returnvalue;


                }
            }

        }

        function validate_checkstopdate() {
            var isstoppubreadonly = document.getElementById("stoppubreadonly");
            if (typeof isstoppubreadonly !== 'undefined' && isstoppubreadonly !== null) {
                if (isstoppubreadonly.value != "" && isstoppubreadonly.value != 0) {
                    return true;
                }
            } else {
                var return_value = check_start_stop_job_publishing_dates(2);
                return return_value;
            }

        }

        function validate_checkagefrom() {
            var optionagefrom = document.getElementById("agefrom");
            var strUser = optionagefrom.options[optionagefrom.selectedIndex].text;
            var range_from_value = parseInt(strUser, 10);

            var optionageto = document.getElementById("ageto");
            var strUserTo = optionageto.options[optionageto.selectedIndex].text;
            var range_from_to = parseInt(strUserTo, 10);
            if (range_from_value > range_from_to) {
                jQuery("#agefrom").addClass("invalid");
                alert("<?php echo Text::_("Age from must be less than age to"); ?>");
                return false;
            } else if (range_from_value == range_from_to) {
                return true;
            }
            return true;
        }


        function validate_salaryrangefrom() {
            var optionsalaryrangefrom = document.getElementById("salaryrangefrom");
            var strUser = optionsalaryrangefrom.options[optionsalaryrangefrom.selectedIndex].text;
            var salaryrange_from_value = parseInt(strUser, 10);

            var optionsalaryrangeto = document.getElementById("salaryrangeto");
            var strUserTo = optionsalaryrangeto.options[optionsalaryrangeto.selectedIndex].text;
            var salaryrangerange_from_to = parseInt(strUserTo, 10);
            if (salaryrange_from_value > salaryrangerange_from_to) {
                jQuery("#salaryrangefrom").addClass("invalid");
                alert("<?php echo Text::_("Salary range from must be less than salary range to"); ?>");
                return false;
            } else if (salaryrange_from_value == salaryrangerange_from_to) {
                return true;
            }
            return true;

        }

        function validate_checkageto() {
            var optionagefrom = document.getElementById("agefrom");
            var strUser = optionagefrom.options[optionagefrom.selectedIndex].text;
            var range_from_value = parseInt(strUser, 10);

            var optionageto = document.getElementById("ageto");
            var strUserTo = optionageto.options[optionageto.selectedIndex].text;
            var range_from_to = parseInt(strUserTo, 10);
            if (range_from_to < range_from_value) {
                jQuery("#ageto").addClass("invalid");
                alert("<?php echo Text::_("Age to must be greater than age from"); ?>");
                return false;
            } else if (range_from_to == range_from_value) {
                return true;
            }
            return true;
        }
        function validate_salaryrangeto() {
            var optionsrangefrom_obj = document.getElementById("salaryrangefrom");
            if (typeof optionsrangefrom_obj !== 'undefined' && optionsrangefrom_obj !== null) {
                var optionsalaryrangefrom = document.getElementById("salaryrangefrom");
                var strUser = optionsalaryrangefrom.options[optionsalaryrangefrom.selectedIndex].text;
                var salaryrange_from_value = parseInt(strUser, 10);

                var optionsalaryrangeto = document.getElementById("salaryrangeto");
                var strUserTo = optionsalaryrangeto.options[optionsalaryrangeto.selectedIndex].text;
                var salaryrangerange_from_to = parseInt(strUserTo, 10);
                if (salaryrangerange_from_to < salaryrange_from_value) {
                    jQuery("#salaryrangeto").addClass("invalid");
                    alert("<?php echo Text::_("Salary range to must be greater than salary range from"); ?>");
                    return false;
                } else if (salaryrangerange_from_to == salaryrange_from_value) {
                    return true;
                }
                return true;
            }
            return true;
        }

        function hideShowRange(hideSrc, showSrc, showName, showVal) {
            jQuery('#'+hideSrc).hide();
            jQuery('#'+showSrc).show();
            jQuery('#'+showName).val(showVal);
        }

        function myValidate(f) {
            var msg = new Array();
            var multiselectpackage = document.getElementById("multipackage");
            if (typeof multiselectpackage !== 'undefined' && multiselectpackage !== null) {
                var m_p_value = multiselectpackage.options[multiselectpackage.selectedIndex].value;
                if (m_p_value == "") {
                    jQuery("#multipackage").addClass("invalid");
                    msg.push('<?php echo Text::_('Please select a package for a new job'); ?>');
                    alert(msg.join('\n'));
                    return false;
                }
            }
            var startpub_required = document.getElementById('startdate-required').value;
            if (startpub_required != '') {
                var startpub_value = document.getElementById('job_startpublishing').value;
                if (startpub_value == '') {
                    jQuery("#job_startpublishing").addClass("invalid");
                    msg.push('<?php echo Text::_('Please enter the job publish date'); ?>');
                    alert(msg.join('\n'));
                    return false;
                }
            }
            var stoppub_required = document.getElementById("stopdate-required");
            if (typeof stoppub_required !== 'undefined' && stoppub_required !== null) {
                var stoppub_required = document.getElementById('stopdate-required').value;
                if (stoppub_required != '') {
                    var stop_obj = document.getElementById("job_stoppublishing");
                    if (typeof stop_obj !== 'undefined' && stop_obj !== null) {
                        var stoppub_value = document.getElementById('job_stoppublishing').value;
                        if (stoppub_value == '') {
                            jQuery("#job_stoppublishing").addClass("invalid");
                            msg.push('<?php echo Text::_('Please enter job stop publish date'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
            }
            var desc_obj = document.getElementById("description-required");
            if (typeof desc_obj !== 'undefined' && desc_obj !== null) {
                var desc_required_val = document.getElementById("description-required").value;
                if (desc_required_val != '') {
                    is_tinyMCE_active = false;
                    if (typeof(tinyMCE) != "undefined") {
                        if(tinyMCE.editors.length > 0){
                            is_tinyMCE_active = true;
                        }
                    }
                    if(is_tinyMCE_active == true){
                        var jobdescription = tinyMCE.get('description').getContent();
                        if (jobdescription == '') {
                            msg.push('<?php echo Text::_('Please enter the job description'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
            }
            var qal_obj = document.getElementById("qualification-required");
            if (typeof qal_obj !== 'undefined' && qal_obj !== null) {
                var qal_required_val = document.getElementById("qualification-required").value;
                if (qal_required_val != '') {
                    var jobqal = tinyMCE.get('qualifications').getContent();
                    if (jobqal == '') {
                        msg.push('<?php echo Text::_('Please enter job qualifications'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var pref_obj = document.getElementById("prefferdskills-required");
            if (typeof pref_obj !== 'undefined' && pref_obj !== null) {
                var pref_required_val = document.getElementById("prefferdskills-required").value;
                if (pref_required_val != '') {
                    var jobpref = tinyMCE.get('prefferdskills').getContent();
                    if (jobpref == '') {
                        msg.push('<?php echo Text::_('Please enter job preferred skills'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var agr_obj = document.getElementById("agreement-required");
            if (typeof agr_obj !== 'undefined' && agr_obj !== null) {
                var agr_required_val = document.getElementById("agreement-required").value;
                if (agr_required_val != '') {
                    var jobagr = tinyMCE.get('agreement').getContent();
                    if (jobagr == '') {
                        msg.push('<?php echo Text::_('Please enter the job agreement'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var fil_obj = document.getElementById("filter-required");
            if (typeof fil_obj !== 'undefined' && fil_obj !== null) {
                var fil_required_val = document.getElementById("filter-required").value;
                if (fil_required_val != '') {
                    var atLeastOneIsChecked = jQuery("input:checked").length;
                    if (atLeastOneIsChecked == 0) {
                        msg.push('<?php echo Text::_('Please check the filter apply the setting'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var emailsetting_obj = document.getElementById("email-required");
            if (typeof emailsetting_obj !== 'undefined' && emailsetting_obj !== null) {
                var email_required_val = document.getElementById("email-required").value;
                if (email_required_val != '') {
                    var checkedradio = jQuery('input[name=sendemail]:radio:checked').length;
                    if (checkedradio == 0) {
                        msg.push('<?php echo Text::_('Please check the email setting'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var chkst_return = validate_checkstartdate();
            if (chkst_return == false)
                return false;
            var chkstop_return = validate_checkstopdate();
            if (chkstop_return == false)
                return false;
            var call_agefrom = jQuery("#agefrom").lenght;
            if (typeof call_agefrom != 'undefined') {
                var chkagefrom_return = validate_checkagefrom();
                if (chkagefrom_return == false)
                    return false;
            }
            var call_salaryrangefrom = jQuery("#salaryrangefrom").lenght;
            if (typeof call_salaryrangefrom != 'undefined') {
                var chksalfrom_return = validate_salaryrangefrom();
                if (chksalfrom_return == false)
                    return false;
            }
            var call_ageto = jQuery("#ageto").lenght;
            if (typeof call_ageto != 'undefined') {
                var chkageto_return = validate_checkageto();
                if (chkageto_return == false)
                    return false;
            }
            var call_salaryrangeto = jQuery("#salaryrangeto").lenght;
            if (typeof call_salaryrangeto != 'undefined') {
                var chksalto_return = validate_salaryrangeto();
                if (chksalto_return == false)
                    return false;
            }

            if (document.formvalidator.isValid(f)) {
                f.check.value = '<?php
                    if (JVERSION < 3)
                        echo JUtility::getToken();
                    else
                        echo Factory::getSession()->getFormToken();
                    ?>';//send token
            } else {
                msg.push('<?php echo Text::_('Some values are not acceptable, please retry'); ?>');
                alert(msg.join('\n'));
                return false;
            }
            return true;
        }


        function hasClass(el, selector) {
            var className = " " + selector + " ";

            if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
                return true;
            }
            return false;
        }

    </script>

    <?php
    if (isset($this->job->id)) {
        $heading = "Edit Job Information";
    }else{
        $heading = "Job Information";
    }
?>
    <div id="jsjobs-main-wrapper">
        <span class="jsjobs-main-page-title"><?php echo Text::_("$heading"); ?></span>
        <?php if ($this->config['cur_location'] == '1') { ?>
            <div class="jsjobs-breadcrunbs-wrp">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <?php
                                if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
                                    $dlink='index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid='.$this->Itemid;
                                }else{
                                    $dlink='index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid='.$this->Itemid;
                                }
                            ?>
                            <a href="<?php echo $dlink; ?>" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_("Dashboard"); ?>
                            </a>
                        </li>
                        <li>
                            <?php echo Text::_("New Job"); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
<?php
    $hidemapscript = false;
    if ($this->canaddnewjob == VALIDATE) { // add new job, in edit case always VAlidate
        if ($this->isuserhascompany != 3) {
            ?>
                 <div class="jsjobs-folderinfo">
                <form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="jsautoz_form" onSubmit="return myValidate(this);">
            <?php if ($this->packagecombo != false && !empty($this->packagecombo[0])) { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <label id="durationmsg" for="duration"><?php echo Text::_('Select Package'); ?></label>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->packagecombo[0]; ?>
                            </div>
                        </div>      
            <?php } ?>
                    <div class="fieldwrapper" id="markasgold" style="display:none;">
                        <div class="fieldtitle">
                            <label id="goldjobmsg" for="goldjob"><?php echo Text::_('Gold Job'); ?></label>
                        </div>
                        <div class="fieldvalue">
                            <input name="goldjob" id="goldjob" type="checkbox" value="1"/><?php echo Text::_('Gold Job'); ?>
                        </div>
                    </div>        
                    <div class="fieldwrapper" id="markasfeatured" style="display:none;">
                        <div class="fieldtitle">
                            <label id="featuredjobmsg" for="featuredjob"><?php echo Text::_('Featured Job'); ?></label>
                        </div>
                        <div class="fieldvalue">
                            <input name="featuredjob" id="featuredjob" type="checkbox" value="1"/><?php echo Text::_('Featured Job'); ?>
                        </div>
                    </div>        
                    <?php
                    $i = 0;
                    $customfieldobj = getCustomFieldClass();
                    foreach ($this->fieldsordering as $field) {
                        switch ($field->field) {
                            case "jobtitle": $title_required = ($field->required ? 'required' : '');
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="titlemsg" for="title"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?></label>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox-required <?php echo $title_required; ?>" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if (isset($this->job)) echo $this->job->title; ?>" />
                                    </div>
                                </div>                      
                                <?php
                                break;
                            case "company":
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="companymsg" for="company"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?></label>
                                    </div>
                                    <div class="fieldvalue">
                                         <?php echo $this->lists['companies']; ?>
                                    </div>
                                </div>                      
                                <?php
                                break;
                            case "department":
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="departmentmsg" for="department"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?>&nbsp;<font color="red">*</font><?php } ?></label>
                                    </div>
                                    <div class="fieldvalue" id="department">
                                        <?php if (isset($this->lists['departments'])) echo $this->lists['departments']; ?>
                                    </div>
                                </div>        
                                <?php
                                break;
                            case "jobcategory":
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                              <?php echo $this->lists['jobcategory']; ?>
                                    </div>
                                </div>        
                                <?php
                                break;
                            case "subcategory":
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                            <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue" id="fj_subcategory">
                                           <?php echo $this->lists['subcategory']; ?>
                                    </div>
                                </div>        
                                <?php
                                break;
                            case "jobtype":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                               <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                               <?php echo $this->lists['jobtype']; ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "jobstatus":
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                            <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                          <?php echo $this->lists['jobstatus']; ?>
                                    </div>
                                </div>        
                                <?php
                                break;
                            case "jobshift":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                            <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                            <?php echo $this->lists['shift']; ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "jobsalaryrange":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php echo $this->lists['currencyid']; ?>
                                            <?php echo $this->lists['salaryrangefrom']; ?>
                                            <?php echo $this->lists['salaryrangeto']; ?>
                                            <?php echo $this->lists['salaryrangetypes']; ?>
                                        </div>
                                    </div>      
                                <?php } ?>
                                <?php
                                break;
                            case "heighesteducation":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                            <?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php
                                            if (isset($this->job))
                                                $iseducationminimax = $this->job->iseducationminimax;
                                            else
                                                $iseducationminimax = 1;
                                            if ($iseducationminimax == 1) {
                                                $educationminimaxdivstyle = "display:block;";
                                                $educationrangedivstyle = "display:none;";
                                            } else {
                                                $educationminimaxdivstyle = "display:none;";
                                                $educationrangedivstyle = "display:block;";
                                            }
                                            ?>
                                            <input type="hidden" name="iseducationminimax" id="iseducationminimax" value="<?php echo $iseducationminimax; ?>">
                                            <div id="educationminimaxdiv" style="<?php echo $educationminimaxdivstyle; ?>">
                                                <span class="jsjobs-cbobox" style="float:left;width:50%;"><?php echo $this->lists['educationminimax']; ?></span>
                                                <span class="jsjobs-cbobox" style="float:left;width:50%;"><?php echo $this->lists['education']; ?></span>
                                                <a  onclick="hideShowRange('educationminimaxdiv', 'educationrangediv', 'iseducationminimax', 0);" style="cursor:pointer;"><?php echo Text::_('Specify Range'); ?></a>
                                            </div>
                                            <div id="educationrangediv" style="<?php echo $educationrangedivstyle; ?>">
                                               <span class="jsjobs-cbobox" style="float:left;width:50%;"> <?php echo $this->lists['minimumeducationrange']; ?></span>
                                               <span class="jsjobs-cbobox" style="float:left;width:50%;"> <?php echo $this->lists['maximumeducationrange']; ?></span>
                                               <a onclick="hideShowRange('educationrangediv', 'educationminimaxdiv', 'iseducationminimax', 1);" style="cursor:pointer;margin:0 10px 0 0;"><?php echo Text::_('Cancel Range'); ?></a>
                                            </div>
                                        </div>
                                    </div>        
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="degreetitlesmsg" for="degreetitle"><?php echo Text::_('Degree Title'); ?></label>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox-required" type="text" name="degreetitle" id="degreetitle" size="30" maxlength="40" value="<?php if (isset($this->job)) echo $this->job->degreetitle; ?>" />
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "noofjobs":
                                if ($field->published == 1) {
                                    $noofjob_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="noofjobsmsg" for="noofjobs"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox-required  <?php echo $noofjob_required; ?> validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if (isset($this->job)) echo $this->job->noofjobs; ?>" />
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "experience":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="experiencesmsg" for="experience"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue"> 
                                            
                                            <?php
                                            if (isset($this->job))
                                                $isexperienceminimax = $this->job->isexperienceminimax;
                                            else
                                                $isexperienceminimax = 1;
                                            if ($isexperienceminimax == 1) {
                                                $experienceminimaxdivstyle = "display:block;";
                                                $experiencerangedivstyle = "display:none;";
                                            } else {
                                                $experienceminimaxdivstyle = "display:none;";
                                                $experiencerangedivstyle = "display:block;";
                                            }
                                            ?>
                                            
                                            <input type="hidden" name="isexperienceminimax" id="isexperienceminimax" value="<?php echo $isexperienceminimax; ?>">
                                             
                                            <div id="experienceminimaxdiv" style="<?php echo $experienceminimaxdivstyle; ?>">
                                               <span  class="jsjobs-cbobox" style="float:left;width:50%;"> <?php echo $this->lists['experienceminimax']; ?></span>
                                               <span  class="jsjobs-cbobox" style="float:left;width:50%;"> <?php echo $this->lists['experience']; ?></span>
                                                <a  onclick="hideShowRange('experienceminimaxdiv', 'experiencerangediv', 'isexperienceminimax', 0);" style="cursor:pointer;"><?php echo Text::_('Specify Range'); ?></a>
                                            </div> 
                                              
                                              
                                            <div id="experiencerangediv" style="<?php echo $experiencerangedivstyle; ?>">
                                                <span  class="jsjobs-cbobox" style="float:left;width:50%;"><?php echo $this->lists['minimumexperiencerange']; ?></span>
                                                <span  class="jsjobs-cbobox" style="float:left;width:50%;"><?php echo $this->lists['maximumexperiencerange']; ?></span>
                                                <a onclick="hideShowRange('experiencerangediv', 'experienceminimaxdiv', 'isexperienceminimax', 1);" style="cursor:pointer;"><?php echo Text::_('Cancel Range'); ?></a>
                                            </div>
                                            <span style="float:left; width:100%; display:inline-block;">
                                               <input class="" type="text" name="experiencetext" id="experiencetext" size="30" maxlength="150" value="<?php if (isset($this->job)) echo $this->job->experiencetext; ?>" />
                                               <span> <?php echo Text::_('If Any Other Experience'); ?></span>
                                            </span>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "duration":
                                ?>
                                <?php
                                if ($field->published == 1) {
                                    $duration_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="durationmsg" for="duration"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox-required <?php echo $duration_required; ?>" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if (isset($this->job)) echo $this->job->duration; ?>" /><?php echo Text::_('I.e. 18 Months Or 3 Years'); ?>
                                        </div>
                                    </div>                      
                                <?php } ?>
                                <?php
                                break;
                            case "zipcode":
                                ?>
                                <?php
                                if ($field->published == 1) {
                                    $zipcode_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="zipcodemsg" for="zipcode"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox-required <?php echo $zipcode_required; ?>" type="text" name="zipcode" id="zipcode" size="10" maxlength="25" value="<?php if (isset($this->job)) echo $this->job->zipcode; ?>" />
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "startpublishing":
                                ?>
                                <?php
                                $startdatevalue = '';
                                $startpub_required = ($field->required ? 'required' : '');
                                if (isset($this->job))
                                    $startdatevalue = date($this->config['date_format'], strtotime($this->job->startpublishing));
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="startpublishingmsg" for="startpublishing"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?></label>
                                    </div>
                                    <div class="fieldvalue">
                                        <?php
                                        if (isset($this->job)) { //edit
                                            if (isset($this->packagedetail[2]) AND $this->packagedetail[2] == 1) {
												echo HTMLHelper::_('calendar', HTMLHelper::_('date', $this->job->startpublishing, $this->config['date_format'],true, true), 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox validate-checkstartdate', 'size' => '10', 'maxlength' => '19', 'readonly' => 'readonly'));
                                                echo '<input  type="hidden" name="startpubreadonly" id="startpubreadonly" value="1" size=""/>';
                                            } else
												echo HTMLHelper::_('calendar', HTMLHelper::_('date', $this->job->startpublishing, $this->config['date_format'],true, true), 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox validate-checkstartdate', 'size' => '10', 'maxlength' => '19'));
                                            ?>
                                            <?php
                                        }else {
                                            echo HTMLHelper::_('calendar', '', 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox required validate-checkstartdate', 'size' => '10', 'maxlength' => '19'));
                                        }
                                        ?>
                                        <input type='hidden' id='startdate-required' name="startdate-required" value="<?php echo $startpub_required; ?>">
                                    </div>
                                </div>        
                                <?php
                                break;
                            case "stoppublishing":
                                ?>
                                <?php
                                $stopdatevalue = '';
                                $stoppublish_required = ($field->required ? 'required' : '');
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="stoppublishingmsg" for="stoppublishing"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?></label>
                                    </div>
                                    <div class="fieldvalue">
                                        <?php
                                        if (isset($this->packagedetail[2]) AND $this->packagedetail[2] == 1) {
                                            if (isset($this->job)) {
												echo HTMLHelper::_('calendar', HTMLHelper::_('date', $this->job->stoppublishing, $this->config['date_format'],true, true), 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox required validate-checkstopdate', 'size' => '10', 'maxlength' => '19', 'readonly' => 'readonly'));
                                                echo '<input type="hidden" name="stoppubreadonly" id="stoppubreadonly" value="1" size=""/>';
                                            } else {
                                                $enforcetype = '';
                                                if ($this->packagedetail[4] == 1)
                                                    $enforcetype = Text::_('Days');
                                                elseif ($this->packagedetail[4] == 2)
                                                    $enforcetype = Text::_('Weeks');
                                                elseif ($this->packagedetail[4] == 3)
                                                    $enforcetype = Text::_('Months');
                                                echo $this->packagedetail[3] . ' ' . $enforcetype;
                                                ?>
                                                <input type="hidden" name="stoppublishing" id="stoppublishing" value="<?php echo $this->packagedetail[3]; ?>" size=""/>
                                                <?php
                                            }
                                        }else {
                                            if (isset($this->job->stoppublishing)) {
												echo HTMLHelper::_('calendar', HTMLHelper::_('date', $this->job->stoppublishing, $this->config['date_format'],true, true), 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox validate-checkstopdate', 'size' => '10', 'maxlength' => '19'));
                                            } else {
                                                echo HTMLHelper::_('calendar', '', 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox validate-checkstopdate', 'size' => '10', 'maxlength' => '19'));
                                            }
                                            ?>
                                            <input type='hidden' id='stopdate-required' name="stopdate-required" value="<?php echo $stoppublish_required; ?>">
                                <?php } ?>
                                    </div>
                                </div>       
                                <?php
                                break;
                            case "age":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="agefrommsg" for="agefrom"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?>
                                        </div>
                                        <div class="fieldvalue">
                            <?php echo $this->lists['agefrom']; ?>
                                    <?php echo $this->lists['ageto']; ?>
                                        </div>
                                    </div>     
                                <?php } ?>
                                <?php
                                break;
                            case "gender":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="gendermsg" for="gender"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></label>
                                        </div>
                                        <div class="fieldvalue">
                                    <?php echo $this->lists['gender']; ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "careerlevel":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="careerlevelmsg" for="careerlevel"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                    <?php echo $this->lists['careerlevel']; ?>
                                        </div>
                                    </div>        
                                <?php } ?>

                                <?php
                                break;
                            case "workpermit":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="workpermitmsg" for="workpermit"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                    <?php echo $this->lists['workpermit']; ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "requiredtravel":
                                ?>
                        <?php if ($field->published == 1) { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="requiredtravelmsg" for="requiredtravel"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                    <?php echo $this->lists['requiredtravel']; ?>
                                        </div>
                                    </div>        
                                <?php } ?>

                                <?php
                                break;
                            case "description":
                                ?>
                                <?php
                                if ($field->published == 1) {
                                    $des_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="descriptionmsg" for="description"><?php echo Text::_($field->fieldtitle); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?></label>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php if ($this->config['job_editor'] == 1) { ?>
                                                <?php
                                                if (isset($this->job))
                                                    echo $editor->display('description', $this->job->description, '100%', '100%', '60', '20', false);
                                                else
                                                    echo $editor->display('description', '', '100%', '100%', '60', '20', false);
                                                ?>
                                                <input type='hidden' id='description-required' name="description-required" value="<?php echo $des_required; ?>">
                            <?php }else { ?>
                                                <textarea class="inputbox <?php echo $des_required; ?>" name="description" id="description" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->description; ?></textarea>
                                    <?php } ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "agreement":
                                ?>
                        <?php
                        if ($field->published == 1) {
                            $agr_required = ($field->required ? 'required' : '');
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="agreementmsg" for="agreement"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php if ($this->config['job_editor'] == 1) { ?>
                                                <?php
                                                if (isset($this->job))
                                                    echo $editor->display('agreement', $this->job->agreement, '100%', '100%', '60', '20', false);
                                                else
                                                    echo $editor->display('agreement', '', '100%', '100%', '60', '20', false);
                                                ?>
                                                <input type='hidden' id='agreement-required' name="agreement-required" value="<?php echo $agr_required; ?>">
                                    <?php }else { ?>
                                                <textarea class="inputbox <?php echo $agr_required; ?>" name="agreement" id="agreement" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->agreement; ?></textarea>
                                    <?php } ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "qualifications":
                                ?>
                        <?php
                        if ($field->published == 1) {
                            $qal_required = ($field->required ? 'required' : '');
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="qualificationsmsg" for="qualifications"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php if ($this->config['job_editor'] == 1) { ?>
                                                <?php
                                                if (isset($this->job))
                                                    echo $editor->display('qualifications', $this->job->qualifications, '100%', '100%', '60', '20', false);
                                                else
                                                    echo $editor->display('qualifications', '', '100%', '100%', '60', '20', false);
                                                ?>
                                                <input type='hidden' id='qualification-required' name="qualification-required" value="<?php echo $qal_required; ?>">
                                    <?php }else { ?>
                                                <textarea class="inputbox <?php echo $qal_required; ?>" name="qualifications" id="qualifications" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->qualifications; ?></textarea>
                                    <?php } ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "prefferdskills":
                                ?>
                        <?php
                        if ($field->published == 1) {
                            $pref_required = ($field->required ? 'required' : '');
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="prefferdskillsmsg" for="prefferdskills"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php if ($this->config['job_editor'] == 1) { ?>
                                                <?php
                                                if (isset($this->job))
                                                    echo $editor->display('prefferdskills', $this->job->prefferdskills, '100%', '100%', '60', '20', false);
                                                else
                                                    echo $editor->display('prefferdskills', '', '100%', '100%', '60', '20', false);
                                                ?>
                                                <input type='hidden' id='prefferdskills-required' name="prefferdskills-required" value="<?php echo $pref_required; ?>">
                                    <?php }else { ?>
                                                <textarea class="inputbox <?php echo $pref_required; ?>" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->prefferdskills; ?></textarea>
                                    <?php } ?>
                                        </div>
                                    </div>        
                                <?php } ?>
                                <?php
                                break;
                            case "city":
                                ?>
                                        <?php
                                        if ($field->published == 1) {
                                            $city_required = ($field->required ? 'required' : '');
                                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="citymsg" for="city"><?php echo Text::_($field->fieldtitle); ?></label>&nbsp;<?php
                            if ($field->required == 1) {
                                echo '&nbsp;<font color="red">*</font>';
                            }
                                            ?>
                                        </div>
                                        <div class="fieldvalue" id="job_city">
                                            <input class="inputbox <?php echo $city_required; ?>" type="text" name="city" id="city" size="40" maxlength="100" value="" />
                                            <input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if (isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
                                        </div>
                                    </div>
                        <?php } ?>
                        <?php
                        break;
                    case "metadescription":
                        if ($field->published == 1) {
                            $mdes_required = ($field->required ? 'required' : '');
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="metadescriptionmsg" for="metadescription"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <textarea cols="45" rows="5" class="inputbox <?php echo $mdes_required; ?> " name="metadescription" id="metadescription" ><?php if (isset($this->job)) echo $this->job->metadescription; ?></textarea>
                                        </div>
                                    </div>        
                        <?php } ?>  
                        <?php
                        break;
                    case "metakeywords":
                        if ($field->published == 1) {
                            $mkyw_required = ($field->required ? 'required' : '');
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="metakeywordsmsg" for="metakeywords"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <textarea cols="45" rows="5" class="inputbox <?php echo $mkyw_required; ?>" name="metakeywords" id="metakeywords" ><?php if (isset($this->job)) echo $this->job->metakeywords; ?></textarea>
                                        </div>
                                    </div>        
                                <?php } ?>
                        <?php
                        break;
                    case "video":
                        ?>
                        <?php
                        if ($field->published == 1) {
                            $video_required = ($field->required ? 'required' : '');
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="videomsg" for="video"><?php echo Text::_($field->fieldtitle); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox-required <?php echo $video_required; ?>" type="text" name="video" id="video" size="40" maxlength="255" value="<?php if (isset($this->job)) echo $this->job->video; ?>" /><?php echo Text::_('Youtube Video Id'); ?>
                                        </div>
                                    </div>        
                        <?php } ?>  
                        <?php
                        break;
                    case "map":
                        ?>
                        <?php if ($field->published == 1) { 
                            $hidemapscript = true;
                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="mapmsg" for="map"><?php echo Text::_($field->fieldtitle); ?></label>
                                        </div>
                                        <div class="fieldvalue">
                                            <div id="map" ><div id="map_container"></div></div>
                                             <span class="jsjobs-mapvalue">
                                            <span class="jsjobs-latitude">
                                                <?php echo Text::_('Latitude'); ?>
                                                <input type="text" class="inputbox-required" id="latitude" name="latitude" value="<?php //if (isset($this->job)) echo $this->job->latitude; ?>" />
                                            </span>
                                            <span class="jsjobs-longitude">
                                                <?php echo Text::_('Longitude'); ?>
                                                <input type="text" class="inputbox-required" id="longitude" name="longitude" value="<?php //if (isset($this->job)) echo $this->job->longitude; ?>" />
                                            </span>
                                            </span>
                                        </div>
                                    </div>        
                                <?php } ?>  
                                <?php
                                break;
                            default:
                                $params = NULL;
                                $id = NULL;
                                if(isset($this->job)){
                                    $id = $this->job->id; 
                                    $params = $this->job->params; 
                                }
                                $array = $customfieldobj->formCustomFields($field , $id , $params , 'f_company');
                                if(empty($array))
                                    break;
                                $cls='';
                                if($field->userfieldtype == 'radio' || $field->userfieldtype == 'checkbox')
                                {
                                    $cls='jsjobs-radiobtn-wrp';
                                }
                                else if($field->userfieldtype == 'multiple')
                                {
                                    $cls='jsjobs-multiselect-wrp';
                                }
                                else if($field->userfieldtype == 'file')
                                {
                                    $cls='jsjobs-file-wrp';
                                }
                                $var = '
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="address1msg" for="'.$array['lable'].'"> '.Text::_($array['title']);
                                        if ($field->required == 1) {
                                            $var .= '&nbsp;<font color="red">*</font>';
                                        }
                                    $var .= '</label></div>
                                    <div class="fieldvalue '.$cls.'">
                                        '.$array['value'].'
                                    </div>
                                </div>';
                                echo $var;
                        break;

                        }
                    }
                    echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
                    ?>
                    <div class="fieldwrapper-btn">
                        <div class="jsjobs-folder-info-btn">
                            <span class="jsjobs-folder-btn">
                                <input id="button" class="button jsjobs_button" type="submit" name="submit_app" value="<?php echo Text::_('Save Job'); ?>" />
                            </span>
                        </div>
                    </div>
                    <?php
                    if (isset($this->job)) {
                        if (($this->job->created == '0000-00-00 00:00:00') || ($this->job->created == ''))
                            $curdate = date('Y-m-d H:i:s');
                        else
                            $curdate = $this->job->created;
                    } else
                        $curdate = date('Y-m-d H:i:s');
                    ?>
                    <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                    <input type="hidden" name="view" value="jobposting" />
                    <input type="hidden" name="layout" value="viewjob" />
                    <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="savejob" />
                    <input type="hidden" name="c" value="job" />
                    <input type="hidden" name="check" value="" />
                    <?php if (isset($this->packagedetail[0])) { ?>
                        <input type="hidden" id="packageid" name="packageid" value="<?php echo $this->packagedetail[0]; ?>" />
                    <?php } ?>
                    <input type="hidden" name="paymenthistoryid" id="paymenthistoryid" value="<?php echo $this->packagedetail[1]; ?>" />
                    <input type="hidden" name="enforcestoppublishjob" id="enforcestoppublishjob" value="<?php echo $this->packagedetail[2]; ?>" />
                    <input type="hidden" name="enforcestoppublishjobvalue" id="enforcestoppublishjobvalue" value="<?php echo $this->packagedetail[3]; ?>" />
                    <input type="hidden" name="enforcestoppublishjobtype" id="enforcestoppublishjobtype" value="<?php echo $this->packagedetail[4]; ?>" />
                    <input type="hidden" name="packagearray" id="packagearray" value="" />
                    <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <input type="hidden" name="id" id="id" value="<?php if (isset($this->job)) echo $this->job->id; ?>" />
                    <input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php echo $js_scriptdateformat; ?>" />

                    <input type="hidden" name="default_longitude" id="default_longitude" value="<?php echo $this->config['default_longitude']; ?>" />
                    <input type="hidden" name="default_latitude" id="default_latitude" value="<?php echo $this->config['default_latitude']; ?>" />
                    <input type="hidden" id="edit_latitude" value="<?php if (isset($this->job)) echo $this->job->latitude; ?>" />
                    <input type="hidden" id="edit_longitude" value="<?php if (isset($this->job)) echo $this->job->longitude; ?>" />
                    <?php echo HTMLHelper::_( 'form.token' ); ?>
                    <script language=Javascript>
                        jQuery(document).ready(function ($) {
                            /*job apply link start*/
                            if(jQuery("input#jobapplylink1").is(":checked")){
                                jQuery("div#input-text-joblink").show();
                            }else{
                                jQuery("div#input-text-joblink").hide();
                            }
                            jQuery("input#jobapplylink1").click(function(){
                                if(jQuery(this).is(":checked")){
                                    jQuery("div#input-text-joblink").show();
                                }else{
                                    jQuery("div#input-text-joblink").hide();
                                }
                            });

                            jQuery('input.cf_uploadfile').change(function(){
                                jobCustomUploadFileSelection(this);
                            });
                        });
                        function getdepartments(src, val) {
                            jQuery("#" + src).html("Loading ...");
                            jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=department.listdepartments', {val: val}, function (data) {
                                if (data) {
                                    jQuery("#" + src).html(data);
                                    jQuery("#" + src + " select.jsjobs-cbo").chosen();
                                }
                            });
                        }
                        function fj_getsubcategories(src, val) {
                            jQuery("#" + src).html("Loading ...");
                            jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=listsubcategories', {val: val}, function (data) {
                                if (data) {
                                    jQuery("div#" + src).html(data);
                                    jQuery("#" + src + " select.jsjobs-cbo").chosen();
                                } else {
                                    alert("<?php echo Text::_("Error while getting subcategories"); ?>");
                                }
                            });
                        }
                        
                        function jobCustomUploadFileSelection(input ) {
                            var photoValidated = 1;
                            var photo = input.files[0];

                            var filesize = <?php echo $this->config['document_file_size']; ?>;

                            var fileTypes_image = "<?php echo strtolower($this->config['image_file_type']); ?>";
                            var fileTypes_file = "<?php echo strtolower($this->config['document_file_type']); ?>";
                            var fileTypes = fileTypes_image + ','+fileTypes_file;
                            var fileTypesArray = fileTypes.split(",");

                            var photoExt = photo.name.split(".").pop();
                            var photoSize = (photo.size / 1024);

                            if (jQuery.inArray(photoExt, fileTypesArray) < 0) {
                                alert("<?php echo Text::_('File extension mismatched'); ?>");
                                jQuery(input).val("");
                            }
                            if (photoSize > filesize) {
                                alert("<?php echo Text::_('File size exceeded'); ?>");
                                jQuery(input).val("");
                            }
                        }
                    </script>


                </form>
                </div>
        <?php } else { // user has no company  
                $link = "index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&Itemid=".$this->Itemid;
                $linktitle = "Add Company";
                $this->jsjobsmessages->getPackageExpireMsg('Company is required for add a job', 'Please add a company before add a job', $link, $linktitle);
            }
    } else { // can not add new job
        switch ($this->canaddnewjob) {
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A package is required to perform this action').', '.Text::_('please get a package');
                $this->jsjobsmessages->getPackageExpireMsg('You do not have the package',$vartext, $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A Package is required to perform this action and your current package is expired').','.Text::_('please get the new package');
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired', $vartext, $link);
                break;
            case JOB_LIMIT_EXCEEDS:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('You can not add a new job').', '.Text::_('Please get package to extend your job limit');
                $this->jsjobsmessages->getPackageExpireMsg('job limit exceeds', $vartext, $link);
                break;
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Jobseeker is not allowed', 'Jobseeker is not allowed in employer private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role', $vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
                break;
        }
    } ?>
    </div>
<?php    
}//ol
?>
<div id="jsjobsfooter">
    <table width="100%" style="table-layout:fixed;">
        <tr><td height="15"></td></tr>
        <tr>
            <td style="vertical-align:top;" align="center">
                <a class="img" target="_blank" href="https://www.joomsky.com"><img src="https://www.joomsky.com/logo/jsjobscrlogo.png"></a>
                <br>
                Copyright &copy; 2008 - <?php echo  date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions</a></span>
            </td>
        </tr>
    </table>
</div>
</div>



<div id="black_wrapper_jobapply" style="display:none;"></div>

<div id="warn-message" style="display: none;">
    <span class="close-warnmessage"><img src="<?php echo Uri::root(); ?>components/com_jsjobs/images/close-icon.png" /></span>
    <img src="<?php echo Uri::root(); ?>components/com_jsjobs/images/warning-icon.png" />
    <span class="text"></span>
</div>
<script type="text/javascript">
    jQuery("div#black_wrapper_jobapply,div#warn-message span.close-warnmessage").click(function () {
        jQuery("div#warn-message").fadeOut();
        jQuery("div#black_wrapper_jobapply").fadeOut();
    });
</script>
<style type="text/css">
    div#map_container{
        width:100%;
        height:350px;
    }
</style>


<?php 
if($hidemapscript){
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    ?>    
    <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<?php
}
?>

<script type="text/javascript">

    <?php 
    if($hidemapscript){ ?>
        var latlang_marker_array = [];
        var markers = [];
        var map = null;
        var bound = new google.maps.LatLngBounds();        
    <?php
    } ?>

    // This code is changed for multicity selection of cities in form

            jQuery(document).ready(function () {
                var cityname = jQuery("#citynameforedit").val();
                if (cityname != "") {
                    jQuery("#city").tokenInput("<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                        theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "<?php echo Text::_('Type In A Search'); ?>",
                        noResultsText: "<?php echo Text::_('No Results'); ?>",
                        searchingText: "<?php echo Text::_('Searching...'); ?>",
                        //tokenLimit: 1,
                        prePopulate: <?php if (isset($this->multiselectedit)) echo $this->multiselectedit; else echo "''"; ?>,
                        <?php if($this->config['newtyped_cities'] == 1){ ?>
                        onResult: function (item) {
                            if (jQuery.isEmptyObject(item)) {
                                return [{id: 0, name: jQuery("tester").text()}];
                            } else {
                                //add the item at the top of the dropdown
                                item.unshift({id: 0, name: jQuery("tester").text()});
                                return item;
                            }
                        },
                        onAdd: function (item) {
                            if (item.id > 0) {
                                addMarkerOnMap(item);
                                return false;
                            }
                            if (item.name.search(",") == -1) {
                                var input = jQuery("tester").text();
                                msg = '<?php echo Text::_("The location format is not correct please enter the city in this  format")." <br/>".Text::_("City Name").", ".Text::_("Country Name")." <br/>".Text::_("or")." <br/>".Text::_("City Name").", ".Text::_("State Name").", ".Text::_("Country Name"); ?>';
                                jQuery('#city').tokenInput('remove', item);
                                jQuery("div#warn-message").find("span.text").html(msg).show();
                                jQuery("div#warn-message").show();
                                jQuery("div#black_wrapper_jobapply").show();
                                return false;
                            } else {
                                var location_data =  jQuery("tester").text();
                                var geocoder =  new google.maps.Geocoder();
                                geocoder.geocode( { 'address': location_data}, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        var n_latitude = results[0].geometry.location.lat();
                                        var n_longitude = results[0].geometry.location.lng();
                                        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=cities.savecity&<?php echo Factory::getSession()->getFormToken(); ?>=1', {citydata: location_data,latitude: n_latitude,longitude: n_longitude}, function (data) {
                                            if (data) {
                                                try {
                                                    var value = jQuery.parseJSON(data);
                                                    jQuery('#city').tokenInput('remove', item);
                                                    jQuery('#city').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude,longitude:value.longitude});
                                                } catch (e) { // string is not the json its the message come from server
                                                    msg = data;
                                                    jQuery("div#warn-message").find("span.text").html(msg).show();
                                                    jQuery("div#warn-message").show();
                                                    jQuery("div#black_wrapper_jobapply").show();
                                                    jQuery('#city').tokenInput('remove', item);
                                                }
                                            }
                                        });
                                    } else {
                                        alert("Something got wrong"+status);
                                    }
                                });
                            }
                        },
                        onDelete: function(item){
                            identifyMarkerForDelete(item);// delete marker associted with this token input value.
                        }
                        <?php } ?>
                    });
                } else {
                    jQuery("#city").tokenInput("<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                        theme: "jsjobs",
                        preventDuplicates: false,
                        hintText: "<?php echo Text::_('Type In A Search'); ?>",
                        noResultsText: "<?php echo Text::_('No Results'); ?>",
                        searchingText: "<?php echo Text::_('Searching...'); ?>",
                        //tokenLimit: 1
                        <?php if($this->config['newtyped_cities'] == 1){ ?>
                        onResult: function (item) {
                            if (jQuery.isEmptyObject(item)) {
                                return [{id: 0, name: jQuery("tester").text()}];
                            } else {
                                //add the item at the top of the dropdown
                                item.unshift({id: 0, name: jQuery("tester").text()});
                                return item;
                            }
                        },
                        onAdd: function (item) {
                            if (item.id > 0) {
                                addMarkerOnMap(item);
                                return;
                            }
                            if (item.name.search(",") == -1) {
                                var input = jQuery("tester").text();
                                msg = '<?php echo Text::_("The location format is not correct please enter the city in this  format")." <br/>".Text::_("City Name").", ".Text::_("Country Name")." <br/>".Text::_("or")." <br/>".Text::_("City Name").", ".Text::_("State Name").", ".Text::_("Country Name"); ?>';
                                jQuery('#city').tokenInput('remove', item);
                                jQuery("div#warn-message").find("span.text").html(msg).show();
                                jQuery("div#warn-message").show();
                                jQuery("div#black_wrapper_jobapply").show();
                                return false;
                            } else {
                                var location_data =  jQuery("tester").text();
                                var geocoder =  new google.maps.Geocoder();
                                geocoder.geocode( { 'address': location_data}, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        var n_latitude = results[0].geometry.location.lat();
                                        var n_longitude = results[0].geometry.location.lng();
                                        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=cities.savecity&<?php echo Factory::getSession()->getFormToken(); ?>=1', {citydata: location_data,latitude: n_latitude,longitude: n_longitude}, function (data) {
                                            if (data) {
                                                try {
                                                    var value = jQuery.parseJSON(data);
                                                    jQuery('#city').tokenInput('remove', item);
                                                    jQuery('#city').tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude,longitude:value.longitude});
                                                } catch (e) { // string is not the json its the message come from server
                                                    msg = data;
                                                    jQuery("div#warn-message").find("span.text").html(msg).show();
                                                    jQuery("div#warn-message").show();
                                                    jQuery("div#black_wrapper_jobapply").show();
                                                    jQuery('#city').tokenInput('remove', item);
                                                }
                                            }
                                        });
                                    } else {
                                        alert("Something got wrong"+status);
                                    }
                                });
                            }
                        },
                        onDelete: function(item){
                            identifyMarkerForDelete(item);// delete marker associted with this token input value.
                        }
                        <?php } ?>
                    });
                }

                jQuery("select.jsjobs-cbo").chosen();
                /*jQuery("input.jsjobs-inputbox").button()
                        .css({
                            'width': '192px',
                            'border': '1px solid #A9ABAE',
                            'cursor': 'text',
                            'margin': '0',
                            'padding': '4px'
                        });*/

            });

            function changeDate(id) {
                <?php
                if(is_array($this->packagecombo)){
                    $packages_array =$this->packagecombo;
                }
                ?>
                var packagearray = '<?php echo isset($packages_array) ? json_encode($packages_array[1]) : ""; ?>';
                var objarray = eval('(' + packagearray + ')');
                var currentpackage = objarray[id];
                if (currentpackage.availgoldjobs < currentpackage.goldjobs || currentpackage.goldjobs == "-1") {
                    jQuery("div#markasgold").slideDown("slow");
                } else {
                    jQuery("div#markasgold").slideUp("slow");
                    document.getElementById('goldjob').checked = false;
                }
                if (currentpackage.availfeaturedjobs < currentpackage.featuredjobs || currentpackage.featuredjobs == "-1") {
                    jQuery('div#markasfeatured').slideDown('slow');
                } else {
                    jQuery('div#markasfeatured').slideUp('slow');
                    document.getElementById('featuredjob').checked = false;
                }
                document.getElementById('packageid').value = currentpackage.packageid;
                if (currentpackage.enforcestoppublishjob == 1) {
                    document.getElementById('paymenthistoryid').value = currentpackage.paymentid;
                    document.getElementById('enforcestoppublishjob').value = currentpackage.enforcestoppublishjob;
                    document.getElementById('enforcestoppublishjobvalue').value = currentpackage.enforcestoppublishjobvalue;
                    document.getElementById('enforcestoppublishjobtype').value = currentpackage.enforcestoppublishjobtype;
                    switch (currentpackage.enforcestoppublishjobtype) {
                        case "1":
                            var durationtype = 'Days';
                            break;
                        case "2":
                            var durationtype = 'Week';
                            break;
                        case "3":
                            var durationtype = 'Month';
                            break;
                    }
                    var days = currentpackage.enforcestoppublishjobvalue.toString();
                    var duration = days + ' ' + durationtype;
                    var stoppublishing = document.getElementById('stoppublishingdate').innerHTML;
                    document.getElementById('stoppublishingdate').innerHTML = duration;
                } else {
                    var jversion = '<?php echo JVERSION; ?>';
                    var oInput, oImg;
                    oInput = document.createElement("INPUT");
                    oInput.name = "stoppublishing";
                    oInput.setAttribute('id', "job_stoppublishing");
                    oInput.setAttribute('size', "10");
                    oInput.setAttribute('class', "inputbox required validate-checkstopdate");

                    oImg = document.createElement("IMG");
                    oImg.src = "<?php echo $link = Uri::root(); ?>/media/system/images/calendar.png";
                    oImg.setAttribute('id', "job_stoppublishing_img");

                    document.getElementById('stoppublishingdate').innerHTML = '';
                    document.getElementById('stoppublishingdate').appendChild(oInput);
                    document.getElementById('stoppublishingdate').appendChild(oImg);
                    Calendar.setup({
                        inputField: "job_stoppublishing", // id of the input field
                        ifFormat: "<?php echo $js_dateformat; ?>", // format of the input field
                        button: "job_stoppublishing_img", // trigger for the calendar (button ID)
                        align: "Tl", // alignment (defaults to "Bl")
                        singleClick: true,
                        firstDay: 1
                    });
                }
            }

            <?php 
            if($hidemapscript){ ?>

                var map_obj = document.getElementById('map_container');
                if (typeof map_obj !== 'undefined' && map_obj !== null) {
                    window.onload = loadMap(1, '', '', '');
                }

                // function addMarker(latlang){

                //     //bounds.extend(latlang);                

                //     var marker = new google.maps.Marker({
                //         position: latlang,
                //         map: jsadmap,
                //         draggable: true,
                //     });
                    
                //     marker.setMap(jsadmap);
                //     // jsadmap.fitBounds(bounds);
                //     // jsadmap.panToBounds(bounds);


                //     marker.addListener("dblclick", function() {
                //         var latitude = document.getElementById('latitude').value;
                //         latitude = latitude.replace(','+marker.position.lat(), "");
                //         latitude = latitude.replace(marker.position.lat()+',', "");
                //         latitude = latitude.replace(marker.position.lat(), "");
                //         document.getElementById('latitude').value = latitude;
                //         var longitude = document.getElementById('longitude').value;
                //         longitude = longitude.replace(','+marker.position.lng(), "");
                //         longitude = longitude.replace(marker.position.lng()+',', "");
                //         longitude = longitude.replace(marker.position.lng(), "");
                //         document.getElementById('longitude').value = longitude;
                //         marker.setMap(null);
                //     });
                //     if(document.getElementById('latitude').value == ''){
                //         document.getElementById('latitude').value = marker.position.lat();
                //     }else{
                //         document.getElementById('latitude').value += ',' + marker.position.lat();
                //     }
                //     if(document.getElementById('longitude').value == ''){
                //         document.getElementById('longitude').value = marker.position.lng();
                //     }else{
                //         document.getElementById('longitude').value += ',' + marker.position.lng();
                //     }
                // }

                // function addMarkerOnMap(location){
                //     var geocoder =  new google.maps.Geocoder();
                //     geocoder.geocode( { 'address': location}, function(results, status) {
                //         var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                //         if (status == google.maps.GeocoderStatus.OK) {
                //             if(jsadmap != null){
                //                 addMarker(latlng);
                //             }
                //         } else {
                //             alert("<?php echo Text::_('Something got wrong');?>:"+status);
                //         }
                //     });    
                // }

                // function loadMap(callfrom, country, state, city) {
                //     var values_longitude = [];
                //     var values_latitude = [];
                //     var latedit = [];
                //     var longedit = [];
                //     var longitude = '';
                //     var latitude = '';

                //     var long_obj = document.getElementById('longitude');
                //     if (typeof long_obj !== 'undefined' && long_obj !== null) {
                //         longitude = document.getElementById('longitude').value;
                //         if (longitude != '')
                //             longedit = longitude.split(",");
                //     }
                //     var lat_obj = document.getElementById('latitude');
                //     if (typeof long_obj !== 'undefined' && long_obj !== null) {
                //         latitude = document.getElementById('latitude').value;
                //         if (latitude != '')
                //             latedit = latitude.split(",");
                //     }

                //     var default_latitude = document.getElementById('default_latitude').value;
                //     var default_longitude = document.getElementById('default_longitude').value;
                //     if (latedit != '' && longedit != '') {
                //         for (var i = 0; i < latedit.length; i++) {
                //             var latlng = new google.maps.LatLng(latedit[i], longedit[i]);
                //             zoom = 4;
                //             var myOptions = {
                //                 zoom: zoom,
                //                 center: latlng,
                //                 scrollwheel: false,
                //                 mapTypeId: google.maps.MapTypeId.ROADMAP
                //             };
                //             if (i == 0)
                //                 jsadmap = new google.maps.Map(document.getElementById("map_container"), myOptions);
                //             if (callfrom == 1) {
                //                 var marker = new google.maps.Marker({
                //                     position: latlng,
                //                     map: jsadmap,
                //                     visible: true,
                //                 });

                //                 document.getElementById('longitude').value = marker.position.lng();
                //                 document.getElementById('latitude').value = marker.position.lat();
                //                 marker.setMap(jsadmap);
                //                 values_longitude.push(longedit[i]);
                //                 values_latitude.push(latedit[i]);
                //                 document.getElementById('latitude').value = values_latitude;
                //                 document.getElementById('longitude').value = values_longitude;
                //             }
                //             bounds.extend(latlng);
                //         }

                //         jsadmap.fitBounds(bounds);
                //         jsadmap.panToBounds(bounds);
                //     } else {
                //         var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                //         zoom = 4;
                //         var myOptions = {
                //             zoom: zoom,
                //             center: latlng,
                //             mapTypeId: google.maps.MapTypeId.ROADMAP
                //         };
                //         jsadmap = new google.maps.Map(document.getElementById("map_container"), myOptions);
                //     }

                //     google.maps.event.addListener(jsadmap, "click", function (e) {
                //         var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                //         geocoder = new google.maps.Geocoder();
                //         geocoder.geocode({'latLng': latLng}, function (results, status) {
                //             if (status == google.maps.GeocoderStatus.OK) {
                //                 //lastmarker.setMap(null);
                //                 var marker = new google.maps.Marker({
                //                     position: results[0].geometry.location,
                //                     map: jsadmap,
                //                 });
                //                 marker.setMap(jsadmap);
                //                 //lastmarker = marker;
                //                 document.getElementById('latitude').value = marker.position.lat();
                //                 document.getElementById('longitude').value = marker.position.lng();
                //                 values_longitude.push(document.getElementById('longitude').value);
                //                 values_latitude.push(document.getElementById('latitude').value);
                //                 document.getElementById('latitude').value = values_latitude;
                //                 document.getElementById('longitude').value = values_longitude;

                //             } else {
                //                 alert("Geocode was not successful for the following reason: " + status);
                //             }
                //         });
                //     });

                //     if (callfrom == 3) {
                //         var value = '';
                //         var zoom = 4;
                //         jQuery("div#job_city > ul > li > p").each(function () {
                //             value = jQuery(this).html();
                //             if (value != '') {
                //                 geocoder = new google.maps.Geocoder();
                //                 geocoder.geocode({'address': value}, function (results, status) {
                //                     if (status == google.maps.GeocoderStatus.OK) {
                //                         jsadmap.setCenter(results[0].geometry.location);
                //                         document.getElementById('latitude').value = results[0].geometry.location.lat();
                //                         document.getElementById('longitude').value = results[0].geometry.location.lng();
                //                         jsadmap.setZoom(zoom);
                //                         //lastmarker.setMap(null);
                //                         var marker = new google.maps.Marker({
                //                             position: results[0].geometry.location,
                //                             map: jsadmap,
                //                         });
                //                         //marker.setMap(jsadmap);
                //                         values_longitude.push(document.getElementById('longitude').value);
                //                         values_latitude.push(document.getElementById('latitude').value);
                //                         document.getElementById('latitude').value = values_latitude;
                //                         document.getElementById('longitude').value = values_longitude;
                //                         //lastmarker = marker;
                //                     } else {
                //                         alert("Geocode was not successful for the following reason: " + status);
                //                     }
                //                 });
                //             }
                //         });
                //     }
                // }

                function loadMap() {
                    var default_latitude = document.getElementById('default_latitude').value;
                    var default_longitude = document.getElementById('default_longitude').value;
                    var latitude = document.getElementById('edit_latitude').value;
                    var longitude = document.getElementById('edit_longitude').value;
                    var isdefaultvalue = true;
                    if (latitude != '' && longitude != '') {
                        default_latitude = latitude;
                        default_longitude = longitude;
                        isdefaultvalue = false;
                    }

                    var latlng = new google.maps.LatLng(document.getElementById('default_latitude').value, document.getElementById('default_longitude').value);
                    zoom = 8;
                    var myOptions = {
                        zoom: zoom,
                        center: latlng,
                        scrollwheel: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                    default_latitude = default_latitude.split(',');
                    if(default_latitude instanceof Array){
                        default_longitude = default_longitude.split(',');
                        for (i = 0; i < default_latitude.length; i++) {
                            var latlng = new google.maps.LatLng(default_latitude[i], default_longitude[i]);
                            if(isdefaultvalue == false)
                                addMarker(latlng);
                        }                            
                    }else{
                        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                        if(isdefaultvalue == false)
                            addMarker(latlng);
                    }
                    google.maps.event.addListener(map, "click", function (e) {
                        var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'latLng': latLng}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            addMarker(results[0].geometry.location);
                        } else {
                            alert("Geocode was not successful for the following reason: " + status);
                        }
                        });
                    });
                }

                function addMarker(latlang,cityid){
                    if (cityid === undefined) {
                        cityid = 0;
                    }
                    var marker = new google.maps.Marker({
                        position: latlang,
                        map: map,
                        draggable: true,
                    });
                    marker.setMap(map);
                    map.setCenter(latlang);
                    // cityid is to identify the marker neds to be removed.
                    if(cityid != 0){
                        marker.cityid = cityid;
                        markers.push(marker);
                    }
                    // this array is for newly added city whoose marker may need to be removed.
                    latlang_marker_array[latlang] = marker;
                    //..

                    marker.addListener("rightclick", function() {
                        deleteMarker(marker);
                    });
                    if(document.getElementById('latitude').value == ''){
                        document.getElementById('latitude').value = marker.position.lat();
                    }else{
                        document.getElementById('latitude').value += ',' + marker.position.lat();
                    }
                    if(document.getElementById('longitude').value == ''){
                        document.getElementById('longitude').value = marker.position.lng();
                    }else{
                        document.getElementById('longitude').value += ',' + marker.position.lng();
                    }
                    bound.extend(marker.getPosition());
                    map.fitBounds(bound);
                }

                function deleteMarker(marker){ // this fucntion completely remves markr and thier lat lang values from text field
                    var latitude = document.getElementById('latitude').value;
                    latitude = latitude.replace(','+marker.position.lat(), "");
                    latitude = latitude.replace(marker.position.lat()+',', "");
                    latitude = latitude.replace(marker.position.lat(), "");
                    document.getElementById('latitude').value = latitude;
                    var longitude = document.getElementById('longitude').value;
                    longitude = longitude.replace(','+marker.position.lng(), "");
                    longitude = longitude.replace(marker.position.lng()+',', "");
                    longitude = longitude.replace(marker.position.lng(), "");
                    document.getElementById('longitude').value = longitude;
                    marker.setMap(null);
                    return;
                }

                function identifyMarkerForDelete(t_item){// this fucntion identifies the marker assiciated with token input value that has been removed.
                    var id = t_item.id;
                    // this code is when lat lang are added from data base cities
                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].cityid == id) {
                            //Remove the marker from Map                  
                            //markers[i].setMap(null);
                            deleteMarker(markers[i]);
                            //Remove the marker from array.
                            markers.splice(i, 1);
                            return;
                        }
                    }
                    // this code is for when lat lang belonged to newely added city
                    if( t_item.latitude != undefined && t_item.latitude != '' && t_item.latitude != 0){
                        var markerLatlng = new google.maps.LatLng(t_item.latitude, t_item.longitude);
                        deleteMarker(latlang_marker_array[markerLatlng]);
                        markers.splice(markerLatlng, 1);
                    }
                }

                function addMarkerOnMap(location){
                    if( location.latitude != undefined && location.latitude != '' && location.latitude != 0){// code is for adding a marker from data base lat lang.
                        var latlng = new google.maps.LatLng(String(location.latitude), String(location.longitude));
                        if(map != null){
                            addMarker(latlng,location.id);
                        } else {
                            alert("Something got wrong 1:");
                        } 
                    }else{ // this code for adding a marker from location name. // this code is redundant but leaving it here 
                        var geocoder =  new google.maps.Geocoder();
                        geocoder.geocode( { 'address': location.name}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                                if(map != null){
                                    addMarker(latlng,location.id);
                                }
                            } else {
                                //alert("<?php //echo __('Something got wrong','js-jobs');?>:"+status);
                            }
                        }); 
                    }
                    return;
                }

            <?php
            }
        ?>
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('div[data-jquery="hideit"]').each(function () {
            jQuery(this).hide()
        });
    });
</script>
