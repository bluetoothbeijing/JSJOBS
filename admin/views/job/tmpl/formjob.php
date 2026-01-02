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

$document = Factory::getDocument();
$document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');



$document->addScript('../components/com_jsjobs/js/jquery.tokeninput.js');

$conf   = Factory::getConfig();
$editor = Editor::getInstance($conf->get('editor'));

//HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');


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
    #mylattitude, #mylongitude{margin-top: 15px;}
</style>

<script language="javascript">
    function check_start_stop_job_publishing_dates(forcall) {
        var date_start_make = new Array();
        var date_stop_make = new Array();
        var split_start_value = new Array();
        var split_stop_value = new Array();
        var returnvalue = true;

        var start_string = document.getElementById("job_startpublishing").value;
        var stop_string = document.getElementById("job_stoppublishing").value;
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
        f = document.adminForm;
        var date_start_make = new Array();
        var split_start_value = new Array();
        var returnvalue = true;
        var isedit = document.getElementById("id");
        if (isedit.value != "" && isedit.value != 0) {
            var return_value = check_start_stop_job_publishing_dates(1);
            return return_value;
        } else {
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

    function validate_checkstopdate() {
        var return_value = check_start_stop_job_publishing_dates(2);
        return return_value;

    }
    function validate_salaryrangefrom() {
        var optionsalr_obj = document.getElementById("salaryrangefrom");
        if (typeof optionsalr_obj !== 'undefined' && optionsalr_obj !== null) {
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
        return true;

    }
    function validate_checkageto() {
        var optionagefrom_obj = document.getElementById("agefrom");
        if (typeof optionagefrom_obj !== 'undefined' && optionagefrom_obj !== null) {
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
        document.getElementById(hideSrc).style.visibility = "hidden";
        document.getElementById(showSrc).style.visibility = "visible";
        document.getElementById(showName).value = showVal;

    }
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else if (task == 'job.cancel') {
            Joomla.submitform(task);
        } else {
            if (task == 'job.savejob') {
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
        var msg = new Array();
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
        var stoppub_required = document.getElementById('stopdate-required').value;
        if (stoppub_required != '') {
            var stoppub_value = document.getElementById('job_stoppublishing').value;
            if (stoppub_value == '') {
                jQuery("#job_stoppublishing").addClass("invalid");
                msg.push('<?php echo Text::_('Please enter job stop publish date'); ?>');
                alert(msg.join('\n'));
                return false;
            }
        }
        var desc_obj = document.getElementById("description-required");
        if (typeof desc_obj !== 'undefined' && desc_obj !== null) {
            var desc_required_val = document.getElementById("description-required").value;
            if (desc_required_val != '') {
                var jobdescription = tinyMCE.get('description').getContent();
                if (jobdescription == '') {
                    msg.push('<?php echo Text::_('Please enter the job description'); ?>');
                    alert(msg.join('\n'));
                    return false;
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
        var chksalfrom_return = validate_salaryrangefrom();
        if (chksalfrom_return == false)
            return false;
        var chkageto_return = validate_checkageto();
        if (chkageto_return == false)
            return false;
        var chksalto_return = validate_salaryrangeto();
        if (chksalto_return == false)
            return false;

        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php echo Factory::getSession()->getFormToken(); ?>';//send token
        }
        else {
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
                            <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs" title="<?php echo Text::_('Jobs'); ?>">
                                <?php echo Text::_('Jobs'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->application->id)){
                                echo Text::_('Edit Job');
                            }else{
                                echo Text::_('Form Job');
                            } ?>
                        </li>
                    </ul>
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
        <div id="jsjobs-head">
            <h1 class="jsjobs-head-text">
                <?php
                if (isset($this->application->id)){
                    echo Text::_('Edit Job');
                }else{
                    echo Text::_('Form Job');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form action="index.php" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <div class="js-form-area">
                    <?php
                        function getRow($title,$value,$labelfor,$fullwidth='',$wrp=''){
                            $html = '<div class="js-form-wrapper">
                                        <label class="jsjobs-title" for="'.$labelfor.'">'.$title.'</label>
                                        <div class="jsjobs-value '.$wrp.'">'.$value.'</div>
                                    </div>';
                            return $html;
                        }
                        $i=0;
                        $customfieldobj = getCustomFieldClass();
                        foreach ($this->fieldsordering as $field) {
                            $title = Text::_($field->fieldtitle);
                            switch ($field->field) {
                                case "jobtitle":
                                    $title_required = ($field->required ? 'required' : '');
                                    $labelfor = "title";                                            
                                    if ($field->required == 1) { 
                                        $title .= '&nbsp;:<font color="red">*</font>'; 
                                    }
                                    $fieldvalue = (isset($this->job)) ? $this->job->title : '';
                                    $value = '<input class="inputbox '.$title_required.'" type="text" name="title" id="title" size="40" maxlength="255" value="'.$fieldvalue.'" />';
                                    echo getRow($title,$value,$labelfor);
                                break;
                                case "zipcode":
                                    $title_required = ($field->required ? 'required' : '');
                                    $labelfor = "zipcode";                                            
                                    if ($field->required == 1) { 
                                        $title .= '&nbsp;:<font color="red">*</font>'; 
                                    }
                                    $fieldvalue = (isset($this->job)) ? $this->job->zipcode : '';
                                    $value = '<input class="inputbox '.$title_required.'" type="text" name="zipcode" id="zipcode" size="40" maxlength="255" value="'.$fieldvalue.'" />';
                                    echo getRow($title,$value,$labelfor);

                                    break;
                                case "company":
                                    $labelfor = "companyid";
                                    if ($field->required == 1) { 
                                        $title .= '&nbsp;:<font color="red">*</font>'; 
                                    }
                                    $value = $this->lists['companies'];
                                    echo getRow($title,$value,$labelfor);
                                    break;
                                case "department":
                                    $labelfor = "departmentid";
                                    if ($field->required == 1) { 
                                        $title .= '&nbsp;:<font color="red">*</font>'; 
                                    }
                                    $value = $this->lists['departments'];
                                    echo getRow($title,$value,$labelfor);
                                    break;
                                case "jobcategory": 
                                    $labelfor = "jobcategory";
                                    if ($field->required == 1) { 
                                        $title .= '&nbsp;:<font color="red">*</font>'; 
                                    }
                                    $value = $this->lists['jobcategory'];
                                    echo getRow($title,$value,$labelfor);
                                    break;
                                case "subcategory": 
                                    $labelfor = "subcategoryid";
                                    $value = $this->lists['subcategory'];
                                    echo getRow($title,$value,$labelfor);
                                    break;                                            
                                case "jobtype": 
                                    $labelfor = "jobtype";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['jobtype'];
                                echo getRow($title,$value,$labelfor);
                            break;                                           
                    case "jobstatus": 
                                $labelfor = "jobstatus";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['jobstatus'];
                                echo getRow($title,$value,$labelfor);
                            break;                                            
                    case "heighesteducation":
                                $labelfor = "heighesteducation";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if (isset($this->job))
                                    $iseducationminimax = $this->job->iseducationminimax;
                                else
                                    $iseducationminimax = 1;
                                if ($iseducationminimax == 1) {
                                    $educationminimaxdivstyle = "width:100%";
                                    $educationrangedivstyle = "visibility:hidden;position:absolute;";
                                } else {
                                    $educationminimaxdivstyle = "visibility:hidden;position:absolute;";
                                    $educationrangedivstyle = "width:100%";
                                }
                                $value = '<input type="hidden" name="iseducationminimax" id="iseducationminimax" value="'.$iseducationminimax.'">';
                                $value .= '<div id="educationminimaxdiv" style="'.$educationminimaxdivstyle.'">';
                                $value .= $this->lists['educationminimax'].'&nbsp;&nbsp;&nbsp;';
                                $value .= $this->lists['education'].'&nbsp;&nbsp;&nbsp;';
                                $value .= '<a onclick="hideShowRange(\'educationminimaxdiv\', \'educationrangediv\', \'iseducationminimax\', 0);">'.Text::_('Specify Range').'</a>';
                                $value .= '</div>';
                                $value .= '<div id="educationrangediv" style="'.$educationrangedivstyle.'">';
                                $value .= $this->lists['minimumeducationrange'].'&nbsp;&nbsp;&nbsp;';
                                $value .= $this->lists['maximumeducationrange'].'&nbsp;&nbsp;&nbsp;';
                                $value .= '<a onclick="hideShowRange(\'educationrangediv\', \'educationminimaxdiv\', \'iseducationminimax\', 1);">'.Text::_('Cancel Range').'</a>';
                                $value .= '</div>';
                                echo getRow($title,$value,$labelfor);
                                $labelfor = "degreetitle";
                                $title = Text::_('Degree Title');
                                $fielvalue = (isset($this->job)) ? $this->job->degreetitle : '';
                                $value = '<input class="inputbox" type="text" name="degreetitle" id="degreetitle" size="30" maxlength="40" value="'.$fielvalue.'" />';
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "jobshift":
                                $labelfor = "shift";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['shift'];
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "jobsalaryrange":
                                $labelfor = "salaryrangefrom";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['currencyid'].$this->lists['salaryrangefrom'];
                                $value .= $this->lists['salaryrangeto'].$this->lists['salaryrangetypes'];
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "heighesteducation":
                                $labelfor = "";
                                $value = $this->lists['heighesteducation:'];
                                echo getRow($title,$value,$labelfor);
                                break;
                                            
                    case "noofjobs":
                                $noofjob_required = ($field->required ? 'required' : '');
                                $labelfor = "noofjobs";                                            
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->job)) ? $this->job->noofjobs : '';
                                $value = '<input class="inputbox '.$noofjob_required.'" type="text" name="noofjobs" id="noofjobs" size="40"  value="'.$fieldvalue.'" />';
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "jobapplylink":?>
                                <div class="js-form-wrapper">
                                <?php 
                                    if(isset($this->job) && $this->job->jobapplylink==1) $check_value = 'checked="checked"'; else $check_value = '';
                                ?>
                                    <div class="fieldtitle">
                                        <label class="jsjobs-title" id="joblink" for="joblink"><?php echo Text::_('Job Apply Link'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?></label>
                                    </div>
                                <div class="check-box-joblink">
                                    <span class="jobapplylink">
                                        <label class="jobapplylink" for="jobapplylink"><input type="checkbox" value="1" name="jobapplylink" id="jobapplylink1" <?php echo $check_value; ?> /><?php echo Text::_('Set Job Apply Redirect Link'); ?></label>
                                    </span>
                                </div>
                            <div  id="input-text-joblink">
                                <div class="jsjobs-value">
                                    <input class="inputbox" type="text" name="joblink" id="joblink"  value="<?php if (isset($this->job)) echo $this->job->joblink; ?>" />
                                </div>
                            </div> 
                            </div>
                            <?php
                            break;
                    case "experience":
                                $labelfor = "experience";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if (isset($this->job))
                                $isexperienceminimax = $this->job->isexperienceminimax;
                                else
                                $isexperienceminimax = 1;
                                if ($isexperienceminimax == 1) {
                                $experienceminimaxdivstyle = "width:100%;";
                                $experiencerangedivstyle = "visibility:hidden;position:absolute;";
                                } else {
                                $experienceminimaxdivstyle = "visibility:hidden;position:absolute;";
                                $experiencerangedivstyle = "width:100%;";
                                }
                                $value = '<input type="hidden" name="isexperienceminimax" id="isexperienceminimax" value="'.$isexperienceminimax.'">';
                                $value .= '<div id="experienceminimaxdiv" style="'.$experienceminimaxdivstyle.'">';
                                $value .= $this->lists['experienceminimax'].'&nbsp;&nbsp;&nbsp;';
                                $value .= $this->lists['experience'].'&nbsp;&nbsp;&nbsp;';
                                $value .= '<a  onclick="hideShowRange(\'experienceminimaxdiv\', \'experiencerangediv\', \'isexperienceminimax\', 0);">'.Text::_('Specify Range').'</a>
                                </div>';
                                $value .= '<div id="experiencerangediv" style="'.$experiencerangedivstyle.'">';
                                $value .= $this->lists['minimumexperiencerange'].'&nbsp;&nbsp;&nbsp;';
                                $value .= $this->lists['maximumexperiencerange'].'&nbsp;&nbsp;&nbsp;';
                                $value .= ' <a onclick="hideShowRange(\'experiencerangediv\', \'experienceminimaxdiv\', \'isexperienceminimax\', 1);">'.Text::_('Cancel Range').'</a>                     
                                </div>';
                                echo getRow($title,$value,$labelfor);
                                $labelfor = "experiencetext";
                                $title = '';
                                $fielvalue = (isset($this->job)) ? $this->job->experiencetext : '';
                                $value = '<input class="inputbox" type="text" name="experiencetext" id="experiencetext" size="30" maxlength="150" value="'.$fielvalue.'" />&nbsp;&nbsp'.Text::_('If Any Other Experience');
                                echo getRow($title,$value,$labelfor);

                            break;
                    case "duration":
                                $duration_required = ($field->required ? 'required' : '');
                                $labelfor = "duration";                                            
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->job)) ? $this->job->duration : '';
                                $value = '<input class="inputbox '.$duration_required.'" type="text" name="duration" id="duration" size="10" maxlength="15" value="'.$fieldvalue.'" />';
                                $value .= '&nbsp;&nbsp;'.Text::_('I.e. 18 Months Or 3 Years');
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "map":
                        $labelfor = "map";
                        $fieldvalue = '';//(isset($this->job)) ? $this->job->longitude : '';
                        $fieldvalue1 = '';//(isset($this->job)) ? $this->job->latitude : '';
                        $value = 
                            '<div id="map_container"></div>
                            <span id="mylattitude">
                                <label id="mylongitudelabel" >'.Text::_('Latitude').'</label>
                                <input type="text" class="inputbox " id="latitude" name="latitude" value="'.$fieldvalue1.'"/>
                            </span>
                            <span id="mylongitude">
                                <label id="mylongitudelabel">'.Text::_('Longitude').'</label>
                                <input type="text" class="inputbox" id="longitude" name="longitude" value="'.$fieldvalue.'"/>
                            </span>';
                        echo getRow($title,$value,$labelfor);
                            break;
                    case "startpublishing":
                                $startpub_required = ($field->required ? 'required' : '');
                                $startdatevalue = '';
                                if (isset($this->job))
												$startdatevalue = HTMLHelper::_('date', $this->job->startpublishing, $this->config['date_format'],true, true);
                                $labelfor = "startpublishing";                                            
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if (isset($this->job)) { //edit
                                                        $value = HTMLHelper::_('calendar', $startdatevalue, 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox validate-checkstartdate', 'size' => '10', 'maxlength' => '19'));
                                                    } else {
                                                        $value = HTMLHelper::_('calendar', $startdatevalue, 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox validate-checkstartdate', 'size' => '10', 'maxlength' => '19'));
                                                     }                        
                                $value .= '<input type=\'hidden\' id=\'startdate-required\' name="startdate-required"'.$startpub_required.'"/>';
                                echo getRow($title,$value,$labelfor); 
                            break;
                    case "stoppublishing":
                                $stoppublish_required = ($field->required ? 'required' : '');
                                $stopdatevalue = '';
                                if (isset($this->job))
								$stopdatevalue = HTMLHelper::_('date', $this->job->stoppublishing, $this->config['date_format'],true, true);
                                $labelfor = "stoppublishing";                                            
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if (isset($this->job)) {
                                    $value = HTMLHelper::_('calendar', $stopdatevalue, 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox validate-checkstopdate', 'size' => '10', 'maxlength' => '19'));
                                } else {
                                    $value = HTMLHelper::_('calendar', $stopdatevalue, 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox validate-checkstopdate', 'size' => '10', 'maxlength' => '19'));
                                }
                                $value .= '<input type=\'hidden\' id=\'stopdate-required\' name="stopdate-required"'.$stoppublish_required.'"/>';
                                echo getRow($title,$value,$labelfor); 
                            break;
                    case "age":
                                $labelfor = "agefrom";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['agefrom'].$this->lists['ageto'];
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "gender":
                                $labelfor = "gender";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['gender'];
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "careerlevel":
                                $labelfor = "careerlevel";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['careerlevel'];
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "workpermit":
                                $labelfor = "workpermit";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['workpermit'];
                                echo getRow($title,$value,$labelfor);
                            break;                    
                    case "requiredtravel":
                                $labelfor = "requiredtravel";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $value = $this->lists['requiredtravel'];
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "description":
                                $des_required = ($field->required ? 'required' : '');
                                $labelfor = "description";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if ($this->config['job_editor'] == 1) {                            

                                    if (isset($this->job))
                                        $value = $editor->display('description', $this->job->description, '550', '300', '60', '20', false);
                                    else
                                        $value = $editor->display('description', '', '550', '300', '60', '20', false);                            
                                    $value .='<input type=\'hidden\' id=\'description-required\' name="description-required" value="'.$des_required.'">';
                                }else{
                                    $fieldvalue = (isset($this->job)) ? $this->job->description : '';
                                    $value .='<textarea class="inputbox "'.$des_required.'" name="description" id="description" cols="60" rows="5" >"'.$fieldvalue.'"</textarea>';
                                }
                                echo getRow($title,$value,$labelfor);
                            break;
                        
                    case "qualifications":
                                $qal_required = ($field->required ? 'required' : '');
                                $labelfor = "qualifications";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if ($this->config['job_editor'] == 1) {                            
                                    if (isset($this->job))
                                        $value = $editor->display('qualifications', $this->job->qualifications, '550', '300', '60', '20', false);
                                    else
                                        $value = $editor->display('qualifications', '', '550', '300', '60', '20', false);
                                    $value .='<input type=\'hidden\' id=\'qualification-required\' name="qualification-required" value="'.$qal_required.'">';
                                }else{
                                    $fieldvalue = (isset($this->job)) ? $this->job->qualifications : '';
                                    $value .='<textarea class="inputbox "'.$qal_required.'" name="qualifications" id="qualifications" cols="60" rows="5" >"'.$fieldvalue.'"</textarea>';
                                }
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "prefferdskills":
                                $pref_required = ($field->required ? 'required' : '');
                                $labelfor = "prefferdskills";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if ($this->config['job_editor'] == 1) {                            
                                    if (isset($this->job))
                                        $value = $editor->display('prefferdskills', $this->job->prefferdskills, '550', '300', '60', '20', false);
                                    else
                                        $value = $editor->display('prefferdskills', '', '550', '300', '60', '20', false);
                                    $value .='<input type=\'hidden\' id=\'prefferdskills-required\' name="prefferdskills-required" value="'.$qal_required.'">';
                                }else{
                                    $fieldvalue = (isset($this->job)) ? $this->job->prefferdskills : '';
                                    $value .='<textarea class="inputbox "'.$pref_required.'" name="prefferdskills" id="prefferdskills" cols="60" rows="5" >"'.$fieldvalue.'"</textarea>';
                                }
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "city":
                                $city_required = ($field->required ? 'required' : '');
                                $labelfor = "city";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->multiselectedit)) ? $this->multiselectedit : '';
                                $value = '<input class="inputbox "'.$city_required.'" type="text" name="city" id="city" size="40" maxlength="100" value="" />';
                                $value .='<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                                echo getRow($title,$value,$labelfor);
                            break;
                            /*
                              if((isset($this->lists['city'])) && ($this->lists['city']!='')){
                              echo $this->lists['city'];
                              } else{ ?>
                              <input class="inputbox" type="text" name="city" id="city" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->city; ?>" />
                              <?php } */
                                
                    case "video":
                                $video_required = ($field->required ? 'required' : '');
                                $labelfor = "video";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->job)) ? $this->job->video : '';
                                $value ='<input type="text" class="inputbox "'.$video_required.'" name="video" id="video" size="40" maxlength="255" value="'.$fieldvalue.'" />';
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "map": 
                                $labelfor = "map";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->job)) ? $this->job->map : '';
                                $value ='<input type="text" name="map" id="map" size="40" maxlength="255" value="'.$fieldvalue.'" />';
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "agreement":
                                $agr_required = ($field->required ? 'required' : '');
                                $labelfor = "agreement";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                if ($this->config['job_editor'] == 1) {                            
                                    if (isset($this->job))
                                        $value = $editor->display('agreement', $this->job->agreement, '550', '300', '60', '20', false);
                                    else
                                        $value = $editor->display('agreement', '', '550', '300', '60', '20', false);
                                    $value .='<input type=\'hidden\' id=\'agreement-required\' name="agreement-required" value="'.$agr_required.'">';
                                }else{
                                    $fieldvalue = (isset($this->job)) ? $this->job->agreement : '';
                                    $value .='<textarea class="inputbox "'.$agr_required.'" name="agreement" id="agreement" cols="60" rows="5" >'.$fieldvalue.'</textarea>';
                                }
                                echo getRow($title,$value,$labelfor);
                            break;
                    case "metadescription":
                                $mdes_required = ($field->required ? 'required' : '');
                                $labelfor = "metadescription";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->job)) ? $this->job->metadescription : '';
                                $value ='<textarea class="inputbox "'.$mdes_required.'" name="metadescription" id="metadescription" cols="60" rows="5">'.$fieldvalue.'</textarea>';
                                echo getRow($title,$value,$labelfor);
                            break;                        
                    case "metakeywords":
                                $mkyw_required = ($field->required ? 'required' : '');
                                $labelfor = "metakeywords";
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->job)) ? $this->job->metakeywords : '';
                                $value ='<textarea class="inputbox "'.$mkyw_required.'" name="metakeywords" id="metakeywords" cols="60" rows="5">'.$fieldvalue.'</textarea>';
                                echo getRow($title,$value,$labelfor);
                            break;
                    default:
                        $params = NULL;
                        $id = NULL;
                        if(isset($this->job)){
                            $id = $this->job->id; 
                            $params = $this->job->params; 
                        }
                        $array = $customfieldobj->formCustomFields($field , $id , $params , 'admin');
                        if(!empty($array)) {
                            $array['wrp']='';
                            if ($field->required == 1) { 
                                $array['title'] .= '&nbsp;:<font color="red">*</font>'; 
                            }
                            if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio' || $field->userfieldtype =='file')) { 
                                $array['wrp'] = 'js-jobs-main-wrp'; 
                            }
                            echo getRow($array['title'],$array['value'],$array['lable'],'',$array['wrp']);
                        }
                    break;                         
                    }
                }
                            echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
                            ?>
                            <?php
                            foreach ($this->fieldsordering as $field) {
                                switch ($field->field) {

                                }
                            } ?>
                       <div class="js-form-wrapper">
                            <label class="jsjobs-title" for="status"><?php echo Text::_('Status'); ?>:&nbsp;</label>
                            <div class="jsjobs-value"><?php echo $this->lists['status']; ?></div>
                        </div>

                    <?php   
                        if(isset($this->job)) {
                            $uid = $this->job->uid;
                            if (($this->job->created=='0000-00-00 00:00:00') || ($this->job->created==''))
                                $curdate = date('Y-m-d H:i:s');
                            else  
                                $curdate = $this->job->created;
                        }else{
                            $uid = $this->uid;
                            $curdate = date('Y-m-d H:i:s');
                        }?>
                        <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                        <input type="hidden" name="check" value="" />
                        <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
                        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="task" value="job.savejob" />
                        <input type="hidden" name="callfrom" value="<?php echo $this->callfrom;?>" />

                        <input type="hidden" name="id" id="id" value="<?php if(isset($this->job)) echo $this->job->id; ?>" />
                        <input type="hidden" name="default_longitude" id="default_longitude" value="<?php echo $this->config['default_longitude']; ?>" />
                        <input type="hidden" name="default_latitude" id="default_latitude" value="<?php  echo $this->config['default_latitude']; ?>" />
                        <input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php  echo $js_scriptdateformat; ?>" />        
                        <input type="hidden" id="edit_latitude" value="<?php if (isset($this->job)) echo $this->job->latitude; ?>" />
                        <input type="hidden" id="edit_longitude" value="<?php if (isset($this->job)) echo $this->job->longitude; ?>" />
                    </div>    
                    <div class="js-buttons-area">
                        <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save Job'); ?>" />
                        <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs"><?php echo Text::_('Cancel'); ?></a>
                    </div>
                <?php echo HTMLHelper::_( 'form.token' ); ?>        
            </form>
        </div>
    </div>
</div>

<div id="jsjobsfooter">
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
    function getdepartments(src, val){
        jQuery("#"+src).html("Loading ...");
        jQuery.post("index.php?option=com_jsjobs&task=department.listdepartments",{val:val},function(data){
            if(data){
                var parentdiv = jQuery('label[for="'+src+'"]').parent();
                jQuery(parentdiv).find('div.jsjobs-value').html(data); //retuen value
            }
        });
    }

    function fj_getsubcategories(src, val){
        jQuery.post("index.php?option=com_jsjobs&task=subcategory.listsubcategories",{val:val},function(data){
            if(data){
                var parentdiv = jQuery('label[for="'+src+'"]').parent();
                jQuery(parentdiv).find('div.jsjobs-value').html(data); //retuen value
            }
        });
    }
</script>

<style type="text/css">
div#map_container{width:100%;height:350px;}
</style>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<script type="text/javascript">

    var latlang_marker_array = [];
    var markers = [];
    var map = null;
    var bound = new google.maps.LatLngBounds();

    jQuery(document).ready(function() {
        var cityname = jQuery("#citynameforedit").val();
        if(cityname != ""){
            jQuery("#city").tokenInput("<?php echo Uri::root()."index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname";?>", {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo Text::_('Type In A Search'); ?>",
                noResultsText: "<?php echo Text::_('No Results'); ?>",
                searchingText: "<?php echo Text::_('Searching...');?>",
                //tokenLimit: 1,
                prePopulate: <?php if(isset($this->multiselectedit)) echo $this->multiselectedit;else echo "''"; ?>,
                onAdd: function(item) {
                    if (item.id > 0) {
                        addMarkerOnMap(item);
                        return;
                    }
                },
                onDelete: function(item){
                    identifyMarkerForDelete(item);
                }
            });
        }else{
            jQuery("#city").tokenInput("<?php echo Uri::root()."index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname";?>", {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo Text::_('Type In A Search'); ?>",
                noResultsText: "<?php echo Text::_('No Results'); ?>",
                searchingText: "<?php echo Text::_('Searching...');?>",
                //tokenLimit: 1
                onAdd: function(item) {
                    if (item.id > 0) {
                        addMarkerOnMap(item);
                        return;
                    }
                },
                onDelete: function(item){
                    identifyMarkerForDelete(item);
                }
            });
        }

        jQuery('input.cf_uploadfile').change(function(){
            jobCustomUploadFileSelection(this);
        });
    });

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

<script type="text/javascript">
    
    var map_obj = document.getElementById('map_container');
    if(typeof map_obj !== 'undefined' && map_obj !== null) {
        window.onload = loadMap(1);
    }

//     function addMarker(latlang){

//         //bounds.extend(latlang);
//         var marker = new google.maps.Marker({
//             position: latlang,
//             map: js_ad_map,
//             draggable: true,
//         });
//         marker.setMap(js_ad_map);
//         google.maps.event.addListener(marker, "rightclick", function (point) {delMarker(marker)});
//         // js_ad_map.fitBounds(bounds);
//         // js_ad_map.panToBounds(bounds);


//         marker.addListener("dblclick", function() {
//             var latitude = document.getElementById('latitude').value;
//             latitude = latitude.replace(','+marker.position.lat(), "");
//             latitude = latitude.replace(marker.position.lat()+',', "");
//             latitude = latitude.replace(marker.position.lat(), "");
//             document.getElementById('latitude').value = latitude;
//             var longitude = document.getElementById('longitude').value;
//             longitude = longitude.replace(','+marker.position.lng(), "");
//             longitude = longitude.replace(marker.position.lng()+',', "");
//             longitude = longitude.replace(marker.position.lng(), "");
//             document.getElementById('longitude').value = longitude;
//             marker.setMap(null);
//         });
//         if(document.getElementById('latitude').value == ''){
//             document.getElementById('latitude').value = marker.position.lat();
//         }else{
//             document.getElementById('latitude').value += ',' + marker.position.lat();
//         }
//         if(document.getElementById('longitude').value == ''){
//             document.getElementById('longitude').value = marker.position.lng();
//         }else{
//             document.getElementById('longitude').value += ',' + marker.position.lng();
//         }
//     }

//     function addMarkerOnMap(location){
//         var geocoder =  new google.maps.Geocoder();
//         geocoder.geocode( { 'address': location}, function(results, status) {
//             var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
//             if (status == google.maps.GeocoderStatus.OK) {
//                 if(js_ad_map != null){
//                     addMarker(latlng);
//                 }
//             } else {
//                 alert("<?php echo Text::_('Something got wrong');?>:"+status);
//             }
//         });    
//     }

  
// function loadMap(callfrom) {
//         var values_longitude = [];
//         var values_latitude = [];
//         var latedit=[];
//         var longedit=[];
//         var longitude='';       
//         var latitude='';
        
//         var long_obj = document.getElementById('longitude');
//         if(typeof long_obj !== 'undefined' && long_obj !== null) {  
//             longitude = document.getElementById('longitude').value;
//             if(longitude!='') longedit=longitude.split(",");
//         }
//         var lat_obj=document.getElementById('latitude');
//         if(typeof long_obj !== 'undefined' && long_obj !== null) {  
//              latitude = document.getElementById('latitude').value;
//              if(latitude!='') latedit = latitude.split(",");
//         }
//         var default_latitude = document.getElementById('default_latitude').value;
//         var default_longitude = document.getElementById('default_longitude').value;
        

//         //var bounds = new google.maps.LatLngBounds();

//         if(latedit != '' && longedit != ''){ 
//             for (var i = 0; i < latedit.length; i++) {
//                 var latlng = new google.maps.LatLng(latedit[i], longedit[i]); 
//                 var myOptions = {
//                     center: latlng,
//                     mapTypeId: google.maps.MapTypeId.ROADMAP,
//                     scrollwheel: false,
//                 };
//                 if(i == 0) 
//                     js_ad_map = new google.maps.Map(document.getElementById("map_container"),myOptions);
//                 if(callfrom == 1){
//                     var marker = new google.maps.Marker({
//                       position: latlng, 
//                       map: js_ad_map, 
//                       visible: true,
//                       draggable : true,                  
//                     });
//                     google.maps.event.addListener(marker, "rightclick", function (point) {delMarker(marker)});
//                     document.getElementById('longitude').value = marker.position.lng();
//                     document.getElementById('latitude').value = marker.position.lat();
//                     marker.setMap(js_ad_map);
//                     values_longitude.push(longedit[i]);
//                     values_latitude.push(latedit[i]);
//                     document.getElementById('latitude').value = values_latitude;
//                     document.getElementById('longitude').value = values_longitude;

//                 }
//                 bounds.extend(latlng);
//             }      
//             js_ad_map.fitBounds(bounds);
//             js_ad_map.panToBounds(bounds);    
     
//         }else {
//             var latlng = new google.maps.LatLng(default_latitude, default_longitude); 
//             zoom = 4;
//             var myOptions = {
//                     zoom: zoom,
//                     center: latlng,
//                     mapTypeId: google.maps.MapTypeId.ROADMAP
//             };
//             js_ad_map = new google.maps.Map(document.getElementById("map_container"), myOptions);
//         }

//         google.maps.event.addListener(js_ad_map,"click", function(e){
//             var latLng = new google.maps.LatLng(e.latLng.lat(),e.latLng.lng());
//             geocoder = new google.maps.Geocoder();
//             geocoder.geocode( { 'latLng': latLng}, function(results, status) {
//                 if (status == google.maps.GeocoderStatus.OK) {
//                     var marker = new google.maps.Marker({
//                         position: results[0].geometry.location, 
//                         map: js_ad_map, 
//                     });
//                     google.maps.event.addListener(marker, "rightclick", function (point) {delMarker(marker)});
//                     document.getElementById('latitude').value = marker.position.lat();
//                     document.getElementById('longitude').value = marker.position.lng();
//                     values_longitude.push(document.getElementById('longitude').value);
//                     values_latitude.push(document.getElementById('latitude').value);
//                     document.getElementById('latitude').value = values_latitude;
//                     document.getElementById('longitude').value = values_longitude;
//                 } else {
//                     alert("Geocode was not successful for the following reason: " + status);
//                 }
//             });
//         });
//     }

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
</script>
<script>
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
    });
</script>
