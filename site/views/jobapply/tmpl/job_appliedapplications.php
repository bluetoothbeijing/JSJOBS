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

HTMLHelper::_('behavior.formvalidator');
$document = Factory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/jsjobsrating.css');
HTMLHelper::_('jquery.framework');

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

$cf_model = $this->getJSModel('customfields');
function getjobfieldtitle($field  , $cf_model , $for){
    return Text::_($cf_model->getFieldTitleByFieldAndFieldfor($field , $for));
}


if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) {  // employer
        ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title">
                <?php echo Text::_('Job') . "&nbsp;[" . $this->jobtitle . "]" . '&nbsp;&nbsp' . Text::_('Resume Applied'); ?>
                <span class="js_apply_view_job">
                    <?php echo Text::_('Job View') . " : " . $this->stats->jobview . " / " . Text::_('Applied') . " : " . $this->stats->totalapply; ?></span>
                </span>
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
                                    <?php
                                        $dlink='index.php?option=com_jsjobs&c=job&view=job&layout=myjobs';
                                    ?>
                                    <a href="<?php echo $dlink; ?>" title="<?php echo Text::_('My Jobs'); ?>">
                                        <?php echo Text::_("My Jobs"); ?>
                                    </a>
                                </li>
                                <li>
                                    <?php echo Text::_("Resume"); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            <form action="index.php" method="post" name="adminForm" id="appliedAdminForm" >
                <?php
                if ($this->sortlinks['sortorder'] == 'ASC')
                    $img = Uri::root()."components/com_jsjobs/images/sort0.png";
                else
                    $img = Uri::root()."components/com_jsjobs/images/sort1.png";
                $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_appliedapplications&bd=' . $this->jobaliasid . '&ta=' . $this->tabaction . '&Itemid=' . $this->Itemid;
                $fieldsordering = JSModel::getJSModel('fieldsordering')->getFieldsOrderingByFieldFor(3);
                $_field = array();
                foreach($fieldsordering AS $field){
                    if($field->showonlisting == 1){
                        $_field[$field->field] = $field->fieldtitle;
                    }
                }

                ?>
                <div id="sortbylinks">
                    <?php if (isset($_field['first_name'])) { ?>
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['name']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'name') echo 'selected' ?>"><?php echo Text::_('Name'); ?><?php if ($this->sortlinks['sorton'] == 'name') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['job_category'])) { ?>                
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['category']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'category') echo 'selected' ?>"><?php echo Text::_('Category'); ?><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['gender'])) { ?>
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['gender']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'gender') echo 'selected' ?>"><?php echo Text::_('Gender'); ?><?php if ($this->sortlinks['sorton'] == 'gender') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['iamavailable'])) { ?>
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['available']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'available') echo 'selected' ?>"><?php echo Text::_('Available'); ?><?php if ($this->sortlinks['sorton'] == 'available') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['salary'])) { ?>
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobsalaryrange']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'jobsalaryrange') echo 'selected' ?>"><?php echo Text::_('Salary'); ?><?php if ($this->sortlinks['sorton'] == 'jobsalaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['heighestfinisheducation'])) { ?>                    
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['education']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'education') echo 'selected' ?>"><?php echo Text::_('Education'); ?><?php if ($this->sortlinks['sorton'] == 'education') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['total_experience'])) { ?>                
                        <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['total_experience']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'total_experience') echo 'selected' ?>"><?php echo Text::_('Experience'); ?><?php if ($this->sortlinks['sorton'] == 'total_experience') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <span class="job_applied_resume_sbl_links"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['apply_date']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'apply_date') echo 'selected' ?>"><?php echo Text::_('Applied Date'); ?><?php if ($this->sortlinks['sorton'] == 'apply_date') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                </div>            

                <?php
                if (isset($this->resume) && !empty($this->resume)) {
                    foreach ($this->resume as $app) {
                        ?>
                        <div class="js-jobs-jobs-applie" id="jsjobs_appliedresume_data_action_message_<?php echo $app->jobapplyid; ?>">
                            <div class="js_job_image_area">
                                <?php if (isset($_field['photo'])) { ?>
                                    <div class="js_job_image_wrapper mycompany">
                                        <?php
                                        $imgsrc = Uri::root()."components/com_jsjobs/images/user_b.png";
                                        if (isset($app->photo) && $app->photo != "") {
                                            if ($this->isjobsharing) {
                                                $imgsrc = $app->photo;
                                            } else {
                                                $imgsrc = Uri::root().$this->config['data_directory'] . "/data/jobseeker/resume_" . $app->appid . '/photo/' . $app->photo;
                                            }
                                        }
                                        ?>
                                        <img class="js_job_image" src="<?php echo $imgsrc; ?>" />
                                    </div>
                                <?php } ?>
                                <?php 
                                    $resumealiasid = $this->getJSModel('common')->removeSpecialCharacter($app->resumealiasid);
                                    $link = Uri::root() . 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=2&rd=' . $resumealiasid . '&bd=' . $app->jobaliasid . '&sortby=' . $this->sortby . '&ta=' . $this->tabaction . '&Itemid=' . $this->Itemid; 
                                ?> 
                                <a class="view_resume_button" href="<?php echo $link ?>"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/resume-icon.png"><?php echo Text::_('View Resume'); ?></a>
                                <?php if (isset($app->cletterid)) { ?>
                                    <div class="view_coverltr_button"  onclick="showCoverletter('coverletterPopup', <?php echo $app->cletterid; ?>)">
                                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/addnote.png" />
                                        <span id="resume_action_style" ><?php echo Text::_('Cover Letter'); ?></span>
                                    </div>
                                <?php } ?>

                            </div>
                            <div class="js_job_data_area">
                                <div class="js_job_data_1 mycompany">
                                    <?php if (isset($_field['total_experience'])) { ?>
                                        <div class="jsjobs-total-experience-wrp">
                                            <span class="jsjobs-total-experience"><?php
                                             if(empty($app->exptitle))
                                                echo $app->total_experience ;
                                             else 
                                                echo $app->exptitle; ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($_field['application_title'])) { ?>
                                        <span class="js_job_title">
                                            <?php echo $app->applicationtitle; ?>
                                        </span>
                                    <?php } ?>
                                    <span class="js_job_posted">
                                        <span class="js_jobapply_value"> 
                                            <?php echo HTMLHelper::_('date', $app->apply_date, $this->config['date_format']); ?>
                                        </span>                                
                                </div>
                                <div class="js_job_data_2 myresume first-child">
                                    <?php if (isset($_field['first_name'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo Text::_('Name') . " : "; ?>
                                            </span>
                                            <span class="jsjobs-appvalue"><?php echo $app->first_name." ".$app->last_name; ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($_field['gender'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo getjobfieldtitle('gender' , $cf_model , 3) . " : "; ?>
                                            </span>
                                            <span class="jsjobs-appvalue"><?php 
                                                $genderText = '';
                                                if($app->gender == 1){
                                                    $genderText = Text::_('Male');
                                                }elseif ($app->gender == 1) {
                                                    $genderText = Text::_('Female');
                                                }elseif ($app->gender == 3) {
                                                    $genderText = Text::_('Does not matter');
                                                }
                                                echo $genderText; ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($_field['iamavailable'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo getjobfieldtitle('iamavailable' , $cf_model , 3) . " : "; ?>
                                                
                                            </span>
                                            <span class="jsjobs-appvalue"><?php echo ($app->iamavailable == 1) ? Text::_('JYES') : Text::_('JNO'); ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($_field['job_category'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo getjobfieldtitle('job_category' , $cf_model , 3) . " : "; ?>
                                                
                                            </span>
                                            <span class="jsjobs-appvalue"><?php echo $app->cat_title; ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($_field['heighestfinisheducation'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo getjobfieldtitle('heighestfinisheducation' , $cf_model , 3) . " : "; ?>
                                                
                                            </span>
                                            <span class="jsjobs-appvalue"><?php echo $app->educationtitle; ?></span>
                                        </div>
                                    <?php } ?>

                                    <?php if (isset($_field['salary'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo getjobfieldtitle('salary' , $cf_model , 3) . " : "; ?>
                                            </span>
                                            <span class="jsjobs-appvalue">
                                                    <?php 
                                                        $salary = $this->getJSModel('common')->getSalaryRangeView($app->symbol,$app->rangestart,$app->rangeend,$app->salarytype,$this->config['currency_align']);
                                                        echo $salary;
                                                    ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php //if (isset($_field['desired_salary'])) { ?>
                                        <div class="js-col-xs-12 js-col-md-6 jsjobsapp_wrapper">
                                            <span class="jsjobs-apptitle"><?php echo getjobfieldtitle('desired_salary' , $cf_model , 3) . " : "; ?>
                                                
                                            </span>
                                            <span class="jsjobs-appvalue">
                                                    <?php 
                                                        $salary = $this->getJSModel('common')->getSalaryRangeView($app->dsymbol,$app->drangestart,$app->drangeend,$app->dsalarytype,$this->config['currency_align']);
                                                        echo $salary;
                                                    ?>
                                            </span>
                                        </div>
                                    <?php // } ?>
                                </div>
                            </div>

                            <div class="resumeaction1ton" id="resumeaction_<?php echo $app->jobapplyid; ?>"></div>
                            <div class="resumeactionmessage1ton" id="resumeactionmessage_<?php echo $app->jobapplyid; ?>"></div>

                        </div>           
                        <?php
                    }
                }else{
                    $this->jsjobsmessages->getAccessDeniedMsg('No record found', 'Could not found any matching result', 0);
                }
                ?>
                <input type="hidden" name="task" id="task" value="saveshortlistcandiate" />
                <input type="hidden" name="c"  value="jobapply" />
                <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                <input type="hidden" name="jobid" id="jobid" value="<?php echo $this->jobid; ?>" />
                <input type="hidden" name="resumeid" id="resumeid" value="<?php //echo $app->appid; ?>" />
                <input type="hidden" name="id" id="id" value="" />
                <input type="hidden" name="action" id="action" value="" />
                <input type="hidden" name="action_status" id="action_status" value="<?php echo $this->tabaction; ?>" />

                <input type="hidden" name="tab_action" id="tab_action" value="" />
                <input type="hidden" name="view" id="view" value="employer" />
                <input type="hidden" name="layout" id="layout" value="job_appliedapplications" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            </form>
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_appliedapplications&bd=' . $this->jobaliasid . '&ta=' . $this->tabaction . '&sortby='.$this->sortlinks['sorton']. strtolower($this->sortlinks['sortorder']) .'&Itemid=' . $this->Itemid ,false); ?>" method="post">
                <div id="jsjobs_jobs_pagination_wrapper">
                    <div class="jsjobs-resultscounter">
                        <?php echo $this->pagination->getResultsCounter(); ?>
                    </div>
                    <div class="jsjobs-plinks">
                        <?php echo $this->pagination->getPagesLinks(); ?>
                    </div>
                    <div class="jsjobs-lbox">
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>
                </div>

                <?php require_once( 'coverletterbox.php' ); ?>

                <script language="Javascript" type"text/javascript">

                    function fj_getsubcategories(src, val) {
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=listsubcategoriesForSearch",{val:val},function(data){
                        if(data)
                        jQuery("#"+src).html(data); //retuen value
                        });
                    }

                    function jobappliedresumesearch(jobid, action) {
                        document.getElementById('jobid').value = jobid;
                        document.getElementById('tab_action').value = action; // 6 for search 
                        document.getElementById('task').value = 'aappliedresumetabactions';
                        document.forms.appliedAdminForm.submit();

                    }
                    function tabsearch(jobid, searchtype, selected_tab) {
                        var element = jQuery("#jsjobs_appliedapplication_tab_container .jsjobs_appliedapplication_tab_selected");
                        element.removeClass("jsjobs_appliedapplication_tab_selected");
                        jQuery(selected_tab).parents('span').addClass('jsjobs_appliedapplication_tab_selected');
                        var searchhtml = '#jsjobs_appliedresume_tab_search';
                        jQuery(searchhtml).slideDown("slow");
                    }
                    function closetabsearch(src) {
                        jQuery(src).slideUp("slow");
                    }
                    function tabaction(jobid, action) {
                        document.getElementById('jobid').value = jobid;
                        document.getElementById('tab_action').value = action;
                        document.getElementById('task').value = 'aappliedresumetabactions';
                        document.forms["adminForm"].submit();
                    }
                        function actioncall(jobapplyid, jobid, resumeid, action) {
                            
                        jQuery("div#resumeaction_"+jobapplyid).css("display","inline-block");
                        
                        if (action == 3) { // folder
                        getfolders('resumeaction_' + jobapplyid, jobid, resumeid, jobapplyid);
                        } else if (action == 4) { // comments
                        getresumecomments('resumeaction_' + jobapplyid, jobapplyid);
                        } else if (action == 5) { // email candidate
                        mailtocandidate('resumeaction_' + jobapplyid, resumeid, jobapplyid);
                        } else {
                        var src = '#resumeactionmessage_' + jobapplyid;
                        var htmlsrc = '#jsjobs_appliedresume_data_action_message_' + jobapplyid;
                        jQuery(src).html("Loading ...");
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=shortlistcandidate&task=saveshortlistcandiate",{jobid:jobid,resumeid:resumeid,action:action},function(data){
                        if(data){
                        jQuery(src).html(data);
                        jQuery(htmlsrc).slideDown("slow");
                        setTimeout(function() {
                        closeresumeactiondiv(htmlsrc)
                        }, 3000);
                        }
                        });
                        }

                        }
                        function actionchangestatus(jobapplyid, jobid, resumeid, action) {
                        var src = '#resumeactionmessage_' + jobapplyid;
                        jQuery(src).html("Loading ...");
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=jobapply&task=updateactionstatus",{jobid:jobid,resumeid:resumeid,applyid:jobapplyid,action_status:action},function(data){
                        if(data){
                                jQuery(src).html('');
                                data = jQuery.parseJSON(data);
                                if(data.status == 'ok'){
                                    jQuery(src).html('<span class="resume_message_print_ok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/approve.png" />'+data.msg+'</span>');
                                }else{
                                    jQuery(src).html('<span class="resume_message_print_notok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/act_no.png" />'+data.msg+'</span>');
                                }
                                jQuery(src).slideDown("slow");
                                    setTimeout(function() {
                                    closeresumeactiondiv(src)
                                    tabaction(jobid,action);
                                }, 3000);
                            }
                        });
                        }
                        function closeresumeactiondiv(src) {
                        jQuery(src).slideUp("slow");
                        }
                        function setresumeid(resumeid, action) {
                        document.getElementById('resumeid').value = resumeid;
                        document.getElementById('action').value = document.getElementById(action).value;
                        document.forms["adminForm"].submit();
                        }
                        function saveresumecomments(jobapplyid, resumeid) {
                            var src = '#resumeactionmessage_' + jobapplyid;
                            var clearhtml = '#resumeaction_' + jobapplyid;
                            var comments = document.getElementById('comments').value;
                            jQuery(src).html("Loading ...");
                            jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumecomments",{jobapplyid:jobapplyid,resumeid:resumeid,comments:comments},function(data){
                                if(data){
                                    jQuery(clearhtml).hide("");
                                    jQuery(src).html('');
                                    data = jQuery.parseJSON(data);
                                    if(data.status == 'ok'){
                                        jQuery(src).html('<span class="resume_message_print_ok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/approve.png" />'+data.msg+'</span>');
                                    }else{
                                        jQuery(src).html('<span class="resume_message_print_notok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/act_no.png" />'+data.msg+'</span>');
                                    }
                                    jQuery(src).slideDown("slow");
                                        setTimeout(function() {
                                        closeresumeactiondiv(src)
                                    }, 3000);
                                }
                            });
                        }
                        function saveaddtofolder(jobapplyid, jobid, resumeid) {
                        var src = '#resumeactionmessage_' + jobapplyid;
                        var clearhtml = '#resumeaction_' + jobapplyid;
                        var folderid = document.getElementById('folderid').value;
                        jQuery(src).html("Loading ...");
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=folder&task=saveresumefolder",{jobid:jobid,resumeid:resumeid,applyid:jobapplyid,folderid:folderid},function(data){
                            if(data){
                                jQuery(clearhtml).hide("");
                                jQuery(src).html('');
                                data = jQuery.parseJSON(data);
                                if(data.status == 'ok'){
                                    jQuery(src).html('<span class="resume_message_print_ok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/approve.png" />'+data.msg+'</span>');
                                }else{
                                    jQuery(src).html('<span class="resume_message_print_notok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/act_no.png" />'+data.msg+'</span>');
                                }
                                jQuery(src).slideDown("slow");
                                    setTimeout(function() {
                                    closeresumeactiondiv(src)
                                }, 3000);
                            }
                        });
                            }

                        function getfolders(src, jobid, resumeid, applyid) {
                            document.getElementById(src).innerHTML = "Loading ...";
                            jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=folder&task=getmyforlders",{jobid:jobid,resumeid:resumeid,applyid:applyid},function(data){
                                if(data){
                                    jQuery("#"+src).html(data); //retuen value
                                } 
                            });
                        }

                        function mailtocandidate(src, emailaddress, jobapplyid) {
                        document.getElementById(src).innerHTML = "Loading ...";
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=job&task=mailtocandidate",{email:emailaddress,jobapplyid:jobapplyid},function(data){
                        if(data){
                        jQuery("#"+src).html(data); //retuen value
                        }
                        });
                        }
                        function sendmailtocandidate(jobapplyid) {
                        var src = 'resumeactionmessage_' + jobapplyid;
                        var arr = new Array();
                        var emmailaddress = document.getElementById('emmailaddress').value;
                        if (emmailaddress) {
                        var result = echeck(emmailaddress);
                        if (result == false) {
                        alert("<?php echo Text::_("Invalid Email"); ?>");
                        document.getElementById('emmailaddress').focus();
                        return false;
                        }
                        arr[0] = emmailaddress;
                        arr[1] = document.getElementById('jsmailaddress').value;
                        arr[2] = document.getElementById('jssubject').value;
                        arr[3] = document.getElementById('candidatemessage').value;
                        sendtocandidate(arr, jobapplyid);

                        } else {
                        alert("<?php echo Text::_("Your Email Is Required").'!'; ?>");
                        document.getElementById('emmailaddress').focus();
                        return false;
                        }
                        }
                        function sendtocandidate(arr, jobapplyid) {

                        var src = '#resumeactionmessage_' + jobapplyid;
                        var clearhtml = '#resumeaction_' + jobapplyid;
                        jQuery(src).html("Loading ...");
                            jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=job&task=sendtocandidate",{val:JSON.stringify(arr)},function(data){
                                jQuery(clearhtml).hide("");
                                jQuery(src).html('');
                                data = jQuery.parseJSON(data);
                                if(data.status == 'ok'){
                                    jQuery(src).html('<span class="resume_message_print_ok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/approve.png" />'+data.msg+'</span>');
                                }else{
                                    jQuery(src).html('<span class="resume_message_print_notok"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/act_no.png" />'+data.msg+'</span>');
                                }
                                jQuery(src).slideDown("slow");
                                    setTimeout(function() {
                                    closeresumeactiondiv(src)
                                }, 3000);

                            });
                        }
                        function getresumecomments(src, jobapplyid) {
                            document.getElementById(src).innerHTML = "Loading ...";
                            jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=getresumecomments",{jobapplyid:jobapplyid},function(data){
                                if(data){
                                    jQuery("#"+src).html(data);
                                } 
                            });
                        }
                        function getjobdetail(src, jobid, resumeid) {
                        
                        jQuery("div#"+src).css("display","inline-block");
                        
                        document.getElementById(src).innerHTML = "Loading ...";
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=getresumedetail",{jobid:jobid,resumeid:resumeid},function(data){
                        if(data){
                        jQuery("#"+src).html(data); //retuen value
                        } 
                        });
                        }
                        function clsjobdetail(src) {
                        document.getElementById(src).innerHTML = "";
                        }
                        function clsaddtofolder(src) {
                            document.getElementById(src).innerHTML = "";                        
                        }

                        function setrating(src, newrating, ratingid, jobid, resumeid) {
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumerating",{ratingid:ratingid,jobid:jobid,resumeid:resumeid,newrating:newrating},function(data){
                        if(data){
                        document.getElementById(src).style.width = parseInt(newrating * 20) + '%';
                        }
                        });
                        }
                        function echeck(str) {
                        var at = "@";
                        var dot = ".";
                        var lat = str.indexOf(at);
                        var lstr = str.length;
                        var ldot = str.indexOf(dot);

                        if (str.indexOf(at) == -1)
                        return false;
                        if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr)
                        return false;
                        if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr)
                        return false;
                        if (str.indexOf(at, (lat + 1)) != -1)
                        return false;
                        if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot)
                        return false;
                        if (str.indexOf(dot, (lat + 2)) == -1)
                        return false;
                        if (str.indexOf(" ") != -1)
                        return false;
                        return true;
                        }
                        function showCoverletter(src, cletterid) {
                        document.getElementById(src).innerHTML = "Loading ...";
                        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=coverletter&task=getcoverletter",{cletterid:cletterid},function(data){
                        if(data){
                        // console.log(data);
                        jQuery('#black_wrapper_jobshortlist').show();
                        jQuery('#'+src).slideDown();
                        jQuery('#'+src).html(data);
                        } 
                        });
                        }
                        function closeCoverletter() {
                        jQuery("div#coverletterPopup").fadeOut(300, function(){
                        jQuery('#black_wrapper_jobshortlist').fadeOut();
                        });
                        }
                        function jobsAppCloseAction(id){
                            jQuery("div#resumeaction_"+id).hide();
                        }
                        jQuery(document).ready(function() {
                        jQuery("span#jsjobs_moreoption").click(function() {
                        jQuery(this).parent().parent().next("div#jsjobs_appliedresume_actioncontainer").slideDown("slow");
                        });
                        jQuery("span#jsjobs_moreoption_close").click(function() {
                        jQuery("div#jsjobs_appliedresume_actioncontainer").slideUp("slow");
                        });
                        });
            </script>
        </form>	
        </div>
        <?php
    } else { // not allowed job posting 
        $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
    }
}
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
