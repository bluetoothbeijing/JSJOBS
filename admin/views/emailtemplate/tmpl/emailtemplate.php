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
use Joomla\CMS\Editor\Editor;

jimport('joomla.html.pane');


//HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');
$document = Factory::getDocument();

?>


<script language="javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'emailtemplate.save') {
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
        }
        else {
            alert("<?php echo Text::_("Some values are not acceptable.  Please retry."); ?>");
            return false;
        }
        return true;
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
                            <?php echo Text::_('Email Template'); ?>
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
        <div id="jsjobs-head">
            <h1 class="jsjobs-head-text">
                <?php echo Text::_('Email Template'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
        <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
            <input type="hidden" name="check" value="post"/>
            <div id="main_emailcontent_wrapper">
            <span class="upperblueline"></span>
            <div class="main_datacontent">
              <div class="emailleft_main">
                    <a class="<?php if($this->templatefor == 'ew-cm') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-cm"> <?php echo Text::_('New Company'); ?></a>
                    <a class="<?php if($this->templatefor == 'cm-ap') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=cm-ap"> <?php echo Text::_('Company Approval'); ?></a>
                    <a class="<?php if($this->templatefor == 'cm-rj') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=cm-rj"> <?php echo Text::_('Company Rejection'); ?></a>
                    <a class="<?php if($this->templatefor == 'cm-dl') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=cm-dl"> <?php echo Text::_('Company').' '.Text::_('Delete'); ?></a>
                    <a class="<?php if($this->templatefor == 'ew-ob') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-ob"> <?php echo Text::_('New Job').' ( '.Text::_('Admin').' )'; ?></a>
                    <a class="<?php if($this->templatefor == 'ew-ob-em') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-ob-em"> <?php echo Text::_('New Job').' ( '.Text::_('Employer').' )'; ?></a>
                    <a class="<?php if($this->templatefor == 'ob-ap') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ob-ap"> <?php echo Text::_('Job Approval'); ?></a>
                    <a class="<?php if($this->templatefor == 'ob-rj') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ob-rj"> <?php echo Text::_('Job Rejecting'); ?></a>
                    <a class="<?php if($this->templatefor == 'ob-dl') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ob-dl"> <?php echo Text::_('Job Delete'); ?></a>
                    <a class="<?php if($this->templatefor == 'ap-rs') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ap-rs"> <?php echo Text::_('Applied Resume Status'); ?></a>
                    <a class="<?php if($this->templatefor == 'ew-rm') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-rm"> <?php echo Text::_('New Resume'); ?></a>
                    <a class="<?php if($this->templatefor == 'ew-rm-vis') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-rm-vis"> <?php echo Text::_('New Resume Visitor'); ?></a>
                    <a class="<?php if($this->templatefor == 'rm-ap') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=rm-ap"> <?php echo Text::_('Resume Approval'); ?></a>
                    <a class="<?php if($this->templatefor == 'rm-rj') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=rm-rj"> <?php echo Text::_('Resume Rejecting'); ?></a>
                    <a class="<?php if($this->templatefor == 'rm-dl') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=rm-dl"> <?php echo Text::_('Resume').' '.Text::_('Delete'); ?></a>
                    <a class="<?php if($this->templatefor == 'ba-ja') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ba-ja"> <?php echo Text::_('Job Apply'); ?></a>
                    <a class="<?php if($this->templatefor == 'js-ja') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=js-ja"> <?php echo Text::_('Job Apply for jobseeker'); ?></a>
                    <a class="<?php if($this->templatefor == 'ew-md') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-md"> <?php echo Text::_('New Department'); ?></a>
                    <a class="<?php if($this->templatefor == 'ew-rp') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-rp"> <?php echo Text::_('Employer Purchase'); ?></a>
                    <a class="<?php if($this->templatefor == 'ew-js') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ew-js"> <?php echo Text::_('Job Seeker Purchase'); ?></a>
                    <a class="<?php if($this->templatefor == 'ms-sy') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=ms-sy"> <?php echo Text::_('Message'); ?><span class="pro_version">*</span></a>
                    <a class="<?php if($this->templatefor == 'jb-at') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-at"> <?php echo Text::_('Job Alert'); ?><span class="pro_version">*</span></a>
                    <a class="<?php if($this->templatefor == 'jb-at-vis') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-at-vis"> <?php echo Text::_('Employer').' ( '.Text::_('visitor').' ) '.Text::_('Job'); ?></a>
                    <a class="<?php if($this->templatefor == 'jb-to-fri') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-to-fri"> <?php echo Text::_('Job To Friend'); ?><span class="pro_version">*</span></a>
                    <a class="<?php if($this->templatefor == 'jb-pkg-pur') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=jb-pkg-pur"> <?php echo Text::_('Job Seeker Package Purchased'); ?></a>
                    <a class="<?php if($this->templatefor == 'emp-pkg-pur') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=emp-pkg-pur"> <?php echo Text::_('Employer Package Purchased'); ?></a>
                    <a class="<?php if($this->templatefor == 'u-da-de') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=u-da-de"> <?php echo Text::_('User Data Delete'); ?></a>
                    <a class="<?php if($this->templatefor == 'u-da-re') echo 'selected_link'; ?>" href="index.php?option=com_jsjobs&c=emailtemplate&view=emailtemplate&layout=emailtemplate&tf=u-da-re"> <?php echo Text::_('Erase Data Request'); ?></a>
              </div><!-- leftmain-closed -->
              <div class="emailright_main">
                  <label for="subject"><?php echo Text::_('Subject'); ?>&nbsp;<font color="red">*</font></label>
                  <input class="inputfieldsizeful inputbox required" type="text" name="subject" id="subject" size="135" maxlength="255" value="<?php if (isset($this->template)) echo $this->template->subject; ?>" />
                  <label><?php echo Text::_('Body'); ?>&nbsp;<font color="red">*</font></label>
                  <div class="email_editor">
                      <?php
                            $conf   = Factory::getConfig();
                            $editor = Editor::getInstance($conf->get('editor'));

                            if (isset($this->template))
                                echo $editor->display('body', $this->template->body, '550', '300', '60', '20', false);
                            else
                                echo $editor->display('body', '', '550', '300', '60', '20', false);
                            ?>  
                  </div><!-- email editor closed -->
                  <label class="parameter_email param_font"><?php echo Text::_('Parameters'); ?></label>
                    <?php if (($this->template->templatefor == 'company-approval' ) || ($this->template->templatefor == 'company-rejecting' )) { ?>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                        <span class="bottomdata_email">{COMPANY_LINK} :  <?php echo Text::_('View Company'); ?></span>  
                    <?php } elseif (($this->template->templatefor == 'job-approval' ) || ($this->template->templatefor == 'job-rejecting' )) { ?>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                        <span class="bottomdata_email">{JOB_LINK} :  <?php echo Text::_('Job Link'); ?></span>  
                    <?php } elseif (($this->template->templatefor == 'resume-approval' ) || ($this->template->templatefor == 'resume-rejecting' )) { ?>                                     
                        <span class="bottomdata_email">{RESUME_TITLE} :  <?php echo Text::_('Resume Title'); ?></span>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span> 
                        <span class="bottomdata_email">{RESUME_LINK} :  <?php echo Text::_('View Resume'); ?></span>    
                    <?php } elseif ($this->template->templatefor == 'company-new') { ?>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                        <span class="bottomdata_email">{COMPANY_LINK} :  <?php echo Text::_('View Company'); ?></span>  
                    <?php } elseif ($this->template->templatefor == 'job-new') { ?>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                        <span class="bottomdata_email">{JOB_LINK} :  <?php echo Text::_('Job Link'); ?></span>  
                    <?php } elseif ($this->template->templatefor == 'job-new-employer') { ?>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>    
                        <span class="bottomdata_email">{DEPARTMENT_NAME} :  <?php echo Text::_('Department Name'); ?></span>
                        <span class="bottomdata_email">{CATEGORGY_TITLE} :  <?php echo Text::_('Category Title'); ?></span>    
                        <span class="bottomdata_email">{JOB_TYPE_TITLE} :  <?php echo Text::_('Job Type Title'); ?></span>    
                        <span class="bottomdata_email">{JOB_STATUS} :  <?php echo Text::_('Job Status'); ?></span>    
                        <span class="bottomdata_email">{JOB_LINK} :  <?php echo Text::_('Job Link'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'resume-new') { ?>                                      
                        <span class="bottomdata_email">{RESUME_TITLE} :  <?php echo Text::_('Resume Title'); ?></span>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span> 
                        <span class="bottomdata_email">{RESUME_LINK} :  <?php echo Text::_('View Resume'); ?></span>    
                    <?php } elseif ($this->template->templatefor == 'department-new') { ?>                                      
                        <span class="bottomdata_email">{DEPARTMENT_TITLE} :  <?php echo Text::_('Department Title'); ?></span>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                    <?php } elseif ($this->template->templatefor == 'employer-buypackage') { ?>                                     
                        <span class="bottomdata_email">{PACKAGE_NAME} :  <?php echo Text::_('Package Title'); ?></span>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                        <span class="bottomdata_email">{PACKAGE_PRICE} :  <?php echo Text::_('Package Price'); ?></span>    
                        <span class="bottomdata_email">{PACKAGE_LINK} :  <?php echo Text::_('View Package'); ?></span>   
                        <span class="bottomdata_email">{PAYMENT_STATUS} :  <?php echo Text::_('Payment Status'); ?></span>  
                    <?php } elseif ($this->template->templatefor == 'jobseeker-buypackage') { ?>                                        
                        <span class="bottomdata_email">{PACKAGE_NAME} :  <?php echo Text::_('Package Title'); ?></span>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span> 
                        <span class="bottomdata_email">{PACKAGE_PRICE} :  <?php echo Text::_('Package Price'); ?></span>    
                        <span class="bottomdata_email">{PACKAGE_LINK} :  <?php echo Text::_('View Package'); ?></span>  
                        <span class="bottomdata_email">{PAYMENT_STATUS} :  <?php echo Text::_('Payment Status'); ?></span>  
                    <?php } elseif ($this->template->templatefor == 'jobapply-jobapply') { ?>                                       
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>    
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span> 
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{RESUME_LINK} :  <?php echo Text::_('View Resume'); ?></span>    
                        <span class="bottomdata_email">{RESUME_DATA} :  <?php echo Text::_('Resume'); ?></span> 
                    <?php } elseif ($this->template->templatefor == 'message-email') { ?>
                        <span class="bottomdata_email">{NAME} :  <?php echo Text::_('Name'); ?></span>  
                        <span class="bottomdata_email">{SENDER_NAME} :  <?php echo Text::_('Sender Name'); ?></span>    
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{RESUME_TITLE} :  <?php echo Text::_('Resume Title'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'job-alert') { ?>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span> 
                        <span class="bottomdata_email">{JOBS_INFO} :  <?php echo Text::_('Show Jobs In Tabular'); ?></span> 
                    <?php } elseif ($this->template->templatefor == 'job-alert-visitor') { ?>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>  
                        <span class="bottomdata_email">{JOB_CATEGORY} :  <?php echo Text::_('Job Category'); ?></span>  
                        <span class="bottomdata_email">{JOB_STATUS} :  <?php echo Text::_('Job Status'); ?></span>  
                        <span class="bottomdata_email">{CONTACT_NAME} :  <?php echo Text::_('Visitor Contact Name'); ?></span>  
                        <span class="bottomdata_email">{JOB_LINK} :  <?php echo Text::_('Job Link'); ?></span>  
                    <?php } elseif ($this->template->templatefor == 'job-to-friend') { ?>
                        <span class="bottomdata_email">{SENDER_NAME} :  <?php echo Text::_('Sender Name'); ?></span>
                        <span class="bottomdata_email">{SITE_NAME} :  <?php echo Text::_('Site Name'); ?></span>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{JOB_CATEGORY} :  <?php echo Text::_('Job Category'); ?></span>  
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>  
                        <span class="bottomdata_email">{CLICK_HERE TO_VISIT} :  <?php echo Text::_('Click Here To Visit'); ?></span>    
                        <span class="bottomdata_email">{SENDER_MESSAGE} :  <?php echo Text::_('Sender Message'); ?></span>  
                    <?php } elseif ($this->template->templatefor == 'applied-resume_status') { ?>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span>
                        <span class="bottomdata_email">{RESUME_STATUS} :  <?php echo Text::_('Applied Resume Status'); ?></span>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                        <span class="bottomdata_email">{STATUS} :  <?php echo Text::_('Resume Apply Status'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'jobseeker-packagepurchase') { ?>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Job Seeker Name'); ?></span>
                        <span class="bottomdata_email">{PACKAGE_TITLE} :  <?php echo Text::_('Package Title'); ?></span>
                        <span class="bottomdata_email">{LINK} :  <?php echo Text::_('Url To Check The Status'); ?></span>
                        <span class="bottomdata_email">{PAYMENT_STATUS} :  <?php echo Text::_('Payment Status'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'employer-packagepurchase') { ?>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>
                        <span class="bottomdata_email">{PACKAGE_TITLE} :  <?php echo Text::_('Package Title'); ?></span>
                        <span class="bottomdata_email">{LINK} :  <?php echo Text::_('Url To Check The Status'); ?></span>
                        <span class="bottomdata_email">{PAYMENT_STATUS} :  <?php echo Text::_('Payment Status'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'company-delete') { ?>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{COMPANY_OWNER_NAME} :  <?php echo Text::_('Company Owner Name'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'job-delete') { ?>
                        <span class="bottomdata_email">{EMPLOYER_NAME} :  <?php echo Text::_('Employer Name'); ?></span>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{JOB_TITLE} :  <?php echo Text::_('Job Title'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'resume-delete') { ?>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Jobseeker Name'); ?></span>
                        <span class="bottomdata_email">{RESUME_TITLE} :  <?php echo Text::_('Resume Title'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'resume-new-vis') { ?>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Jobseeker Name'); ?></span>
                        <span class="bottomdata_email">{RESUME_TITLE} :  <?php echo Text::_('Resume Title'); ?></span>
                        <span class="bottomdata_email">{RESUME_STATUS} :  <?php echo Text::_('Resume Status'); ?></span>
                    <?php } elseif ($this->template->templatefor == 'jobapply-jobseeker') { ?>
                        <span class="bottomdata_email">{JOBSEEKER_NAME} :  <?php echo Text::_('Jobseeker Name'); ?></span>
                        <span class="bottomdata_email">{RESUME_TITLE} :  <?php echo Text::_('Resume Title'); ?></span>
                        <span class="bottomdata_email">{COMPANY_NAME} :  <?php echo Text::_('Company Name'); ?></span>
                        <span class="bottomdata_email">{RESUME_APPLIED_STATUS} :  <?php echo Text::_('Resume Applied Status'); ?></span>
                    <?php }elseif ($this->template->templatefor == 'erase-user-data') { ?>
                        <span class="bottomdata_email">{USERNAME} :  <?php echo Text::_('User Name'); ?></span>
                    <?php }elseif ($this->template->templatefor == 'erase-date-request-receive') { ?>
                        <span class="bottomdata_email">{NAME} :  <?php echo Text::_('Name'); ?></span>
                        <span class="bottomdata_email">{SUBJECT} :  <?php echo Text::_('Request Subject'); ?></span>
                    <?php } ?>

              </div><!-- rightmain closed -->
              <?php
                if (isset($this->template)) {
                    if (($this->template->created == '0000-00-00 00:00:00') || ($this->template->created == ''))
                        $curdate = date('Y-m-d H:i:s');
                    else
                        $curdate = $this->template->created;
                } else
                    $curdate = date('Y-m-d H:i:s');
                ?>
          </div> <!-- maindatacontent closed -->
          <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
          <input type="hidden" name="view" value="jobposting" />
          <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
          <input type="hidden" name="id" value="<?php echo $this->template->id; ?>" />
          <input type="hidden" name="templatefor" value="<?php echo $this->template->templatefor; ?>" />
          <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
          <input type="hidden" name="task" value="emailtemplate.saveemailtemplate" />
          <?php
                // admin side forms dont have itemid was showing error for unfined variable
                /*
          <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
          */?>
        <?php echo HTMLHelper::_( 'form.token' ); ?>        
        </form> 
        </div>
        </div><!-- email main content wrapper closed -->

    </div><!-- content closed -->
</div><!-- main wrapper closed -->
	
<div id="jsjobsfooter">
    <div class="proversiononly"><span class="pro_version">*</span><?php echo Text::_('Pro version only');?></div>			
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
