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
use Joomla\CMS\Router\Route;    

$user = Factory::getUser();
$isShowButton = 1;
$jinput = Factory::getApplication()->input;
?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
    $show_job_apply = 1;
    if ($this->isjobsharing) {
        if (empty($this->job))
            $show_job_apply = 0;
    }
    ?>
    <div id="js_main_wrapper">
        <span class="js_controlpanel_section_title"><?php echo Text::_('Apply Now'); ?></span>
    <?php
    if ($show_job_apply == 1) {
        if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 2) { // job seeker
            if ($this->totalresume > 0) { // Resume not empty
                ?>
                    <form action="index.php" method="post" name="adminForm" id="adminForm" class="jsautoz_form">
                        <div class="fieldwrapper rs_sectionheadline">
                            <?php echo Text::_('Resume Information'); ?>
                        </div>				        
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo Text::_('My Resume'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->myresumes; ?>
                            </div>
                        </div>				        
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo Text::_('My Cover Letter'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->mycoverletters; ?>
                            </div>
                        </div>				        
                        <div class="fieldwrapper rs_sectionheadline">
                            <?php echo Text::_('Job Information'); ?>
                        </div>				        
                        <div class="fieldwrapper view">
                            <div class="fieldtitle">
                                <?php echo Text::_('Title'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php
                                echo $this->job->title;
                                $days = $this->config['newdays'];
                                $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                                if ($this->job->created > $isnew)
                                    echo "<font color='red'> " . Text::_('New') . '!' . " </font>";
                                ?>
                            </div>
                        </div>				        

                        <?php if ($this->listjobconfig['lj_category'] == '1') { ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Category'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php echo $this->job->cat_title; ?>
                                </div>
                            </div>				        
                            <?php
                        }
                        if ($this->listjobconfig['lj_jobtype'] == '1') {
                            ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Job Type'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php echo $this->job->jobtypetitle; ?>
                                </div>
                            </div>				        
                            <?php
                        }
                        if ($this->listjobconfig['lj_jobstatus'] == '1') {
                            ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Job Status'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php echo "<font color='red'><strong>" . $this->job->jobstatustitle . "</strong></font>"; ?>
                                </div>
                            </div>				        
                            <?php
                        }

                        if ($this->listjobconfig['lj_company'] == '1') {
                            ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Company'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php
                                    $cat = $jinput->get('cat');
                                    if (isset($cat))
                                        $jobcat = $cat;
                                    else
                                        $jobcat = null;
                                    $companyaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($this->job->companyaliasid);
                                    $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=32&cd=' . $companyaliasid . '&cat=' . $jobcat . '&Itemid=' . $this->Itemid;
                                    ?>
                                    <span id="anchor"><a class="anchor" href="<?php echo $link ?>"><?php echo $this->job->companyname; ?></a></span>
                                </div>
                            </div>				        
                            <?php
                        }
                        if ($this->listjobconfig['lj_companysite'] == '1') {
                            ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Company Website'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <span id="anchor"><a class="anchor" href="<?php
                                        $chkprotocol = isURL($this->job->url);
                                        if ($chkprotocol == true)
                                            echo $this->job->url;
                                        else
                                            echo 'http://' . $this->job->url;
                                        ?>" target='_blank'><?php echo $this->job->url; ?></a></span>
                                </div>
                            </div>				        
                            <?php
                        }
                        if ($this->listjobconfig['lj_city'] == '1') {
                            ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Location'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php if ($this->job->multicity != '') echo $this->job->multicity; ?>
                                </div>
                            </div>				        
                        <?php } ?>	
                        <?php
                        if ($this->job->jobsalaryrange != 0) {
                            ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo Text::_('Salary Range'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php
                                    $salaryrange = $this->getJSModel('common')->getSalaryRangeView($this->config['currency'], $this->job->rangestart, $this->job->rangeend, $this->job->salarytype, $this->config['currency_align']);
                                    echo $salaryrange
                                    ?>
                                </div>
                            </div>				        
                            <?php
                        }
                        if ($this->listjobconfig['lj_noofjobs'] == '1') {
                            ?>
                            <?php if ($this->job->noofjobs != 0) {
                                ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                                        <?php echo Text::_('Number Of Jobs'); ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <?php echo $this->job->noofjobs ?>
                                    </div>
                                </div>				        
                                <?php
                            }
                        }
                        ?>
                        <div class="fieldwrapper view">
                            <div class="fieldtitle">
                                <?php echo Text::_('Posted'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo HTMLHelper::_('date', $this->job->created, $this->config['date_format']); ?>
                            </div>
                        </div>				        
                        <div class="fieldwrapper view">
                            <?php if ($isShowButton == 1) { ?>
                                <input type="submit" id="button" class="button jsjobs_button" name="submit_app" onclick="document.adminForm.submit();"  value="<?php echo Text::_('Apply Now'); ?>" /></td>
                                <?php
                            } else if ($isShowButton == 2) {
                                echo "<font color='red'><strong>" . Text::_('Job Status') . " : " . $jobstatus[$this->job->jobstatus - 1] . "</strong></font>";
                            } else if ($isShowButton == 3) {
                                echo "<font color='red'><strong>" . Text::_('Your resume waiting for approval') . "</strong></font>";
                            } else if ($isShowButton == 4) {
                                echo "<font color='red'><strong>" . Text::_('Your resume has been rejected') . "</strong></font>";
                            }
                            ?>
                        </div>				        
                        <input type="hidden" name="view" value="jobapply" />
                        <input type="hidden" name="layout" value="static" />
                        <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="task" value="jobapply" />
                        <input type="hidden" name="c" value="jobapply" />
                        <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                        <input type="hidden" name="jobid" value="<?php echo $this->job->id; ?>" />
                        <input type="hidden" name="oldcvid" value="<?php if (isset($this->myapplication->id)) echo $this->myapplication->id; ?>" />
                        <input type="hidden" name="apply_date" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                        <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    </form>
            <?php }else { // Employment application is empty 
                ?>
                <div class="js_job_error_messages_wrapper">
                    <div class="js_job_messages_image_wrapper">
                        <img class="js_job_messages_image" src="<?php echo Uri::root();?>components/com_jsjobs/images/error-icon/no-record.png"/>
                    </div>
                    <div class="js_job_messages_data_wrapper">
                        <span class="js_job_messages_main_text">
                            <?php echo Text::_('Resume is empty'); ?>
                        </span>
                        <span class="js_job_messages_block_text">
                            <?php echo Text::_('Resume is empty'); ?>
                        </span>
                    </div>
                </div>
                <?php
            }
        } elseif (!isset($this->userrole->rolefor)) {
            if ($this->config['visitor_show_login_message'] == 1) {
                $jobaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($this->bd);
                $redirectUrl = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=15&bd=' . $jobaliasid . '&Itemid=' . $this->Itemid;
                $redirectUrl = $this->getJSModel('common')->b64ForEncode($redirectUrl);

                $formresumelink = Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&Itemid='.$this->Itemid ,false);
                ?>
                <div id="jsjobs-main-wrapper">
                    <div id="js_apply_loginform_login_message">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/alert-icon.png" />
                        <?php echo Text::_('You are not login').', '.Text::_('Login or apply as a visitor'); ?>
                    </div>
                    <div id="js_apply_loginform_login">
                        <div class="js-col-md-6 js_apply_loginform">
                            <form action="<?php echo Route::_('index.php' ,false); ?>" method="post" id="login-form">
                                <div class="js_apply_loginform_title"><?php echo Text::_('User Name'); ?></div>
                                <input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
                                <div class="js_apply_loginform_title"><?php echo Text::_('Password'); ?></div>
                                <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18" />
                                <input type="submit" name="Submit" class="js_apply_button" value="<?php echo Text::_('Login') ?>" />
                                
                                <a id="forgotyour-passwd" href="<?php echo Route::_('index.php?option=com_users&view=reset' ,false); ?>"><?php echo Text::_('Forgot Your Password'); ?></a>
                                <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                                    <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
                                    <label id="modlgn-remember-remember" for="modlgn-remember"><?php echo Text::_('Keep Me Login') ?></label>
                                <?php endif; ?>
                            
                                <input type="hidden" name="option" value="com_users" />
                                <input type="hidden" name="task" value="user.login" />
                                <input type="hidden" name="return" value="<?php echo $redirectUrl; ?>" />
                                <?php echo HTMLHelper::_('form.token'); ?>
                            </form>
                        </div>
                        <div class="js-col-md-6 js_apply_visitor">
                            <div class="js-border-left"></div>
                            <div class="js-data-or">
                                <span class="jsjobs_res_or"><?php echo Text::_('OR'); ?></span>
                                <a id="jsjobs-apply-asvisitor" href="<?php echo $formresumelink; ?>"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/visitor-icon.png" /><div class="bleft"><?php echo Text::_('Job apply as a visitor'); ?></div></a>
                            </div>
                            <div class="js-border-left"></div>
                        </div>
                    </div>

                </div>
                <?php
            }
        } else { // not allowed job posting 
            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
        }
    } else {
        if (!isset($this->userrole->rolefor)) {
            if ($this->config['visitor_show_login_message'] == 1) {
                $redirectUrl = 'index.php?option=com_jsjobs&c=jsjobs&view=&layout=successfullogin&Itemid='.$this->Itemid;
                $redirectUrl = $this->getJSModel('common')->b64ForEncode($redirectUrl);
                $finalUrl = 'index.php?option=com_users&view=login' . $redirectUrl;
                $finalUrl = Route::_($finalUrl ,false);
                $formresumelink = Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&Itemid='.$this->Itemid ,false);
                echo Text::_('Please login to record your resume for future use');
                echo "<br><a href=\"" . $finalUrl . "\"><strong>" . Text::_('Login') . "</strong></a> " . Text::_('Or') . "<strong><a href=\"" . $formresumelink . "\">" . Text::_('Job apply as a visitor') . "</a></strong>";
            }
        } else {
            $this->jsjobsmessages->getAccessDeniedMsg('An error has occurred please contact the administrator', 'An error has occurred please contact the administrator', 0);
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
                Copyright &copy; 2008 - <?php echo date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
<?php

function isURL($url = NULL) {
    if ($url == NULL)
        return false;

    $protocol = '(http://|https://)';
    if (ereg($protocol, $url) == true)
        return true;
    else
        return false;
}
?>
