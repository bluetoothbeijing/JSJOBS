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

HTMLHelper::_('jquery.framework');
$link = 'index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=' . $this->Itemid;

?>
<script language="Javascript">
    function confirmdeletejob() {
        return confirm("<?php echo Text::_('Are you sure to delete the job'); ?>");
    }
</script>
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
} else { ?>
    <div id="jsjobs-main-wrapper">
        <span class="jsjobs-main-page-title"><span class="jsjobs-title-componet"><?php echo Text::_('My Jobs'); ?></span>
        <span class="jsjobs-add-resume-btn"><a class="jsjobs-resume-a" href="index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=<?php echo $this->Itemid; ?>">+<span class="jsjobs-add-resume-btn"><?php echo Text::_('Add New Job');?></span></a></span>
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
                            <?php echo Text::_("My Jobs"); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php
    $fieldsordering = JSModel::getJSModel('fieldsordering')->getFieldsOrderingByFieldFor(2);
    $_field = array();
    foreach($fieldsordering AS $field){
        if($field->showonlisting == 1){
            $_field[$field->field] = $field->fieldtitle;
        }

    }

    if ($this->myjobs_allowed == VALIDATE) {
        //if(isset($this->jobs))
        if (isset($this->jobs) && $this->jobs) {
            if ($this->sortlinks['sortorder'] == 'ASC')
                $img = Uri::root()."components/com_jsjobs/images/sort0.png";
            else
                $img = Uri::root()."components/com_jsjobs/images/sort1.png";            
            ?>
                <form action="index.php" method="post" name="adminForm">
                    <div id="sortbylinks">
                        <ul>
                            <?php if (isset($_field['jobtitle'])) { ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['title']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'title') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Title'); ?></a></li>
                            <?php } ?>
                            <?php if (isset($_field['jobcategory'])) { ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['category']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'category') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Category'); ?></a></li>
                            <?php } ?>
                            <?php if (isset($_field['jobtype'])) { ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Job Type'); ?></a></li>
                            <?php } ?>
                            <?php //if (isset($_field['jobstatus'])) { ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'jobstatus') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Job Status'); ?></a></li>
                            <?php //}  ?>
                            <?php if (isset($_field['company'])) { ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['company']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'company') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Company'); ?></a></li>
                            <?php } ?>
                            <?php if (isset($_field['jobsalaryrange'])) { ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Salary Range'); ?></a></li>
                            <?php } ?>
                            <li class="jsjobs-sorting-bar-myjob"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>" class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected' ?>"><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Created'); ?></a></li>
                        </ul>
                    </div>
                        <?php
                            $days = $this->config['newdays'];
                            $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                            if (isset($this->jobs)) {
                                $common = JSModel::getJSModel('common');
                                foreach ($this->jobs as $job) {
                            ?>
                            <div class="jsjobs-folderinfo">
                                <div class="jsjobs-main-myjobslist">
                                    <span class="jsjobs-image-area"> 
                                        <?php
                                            $companyaliasid = ($this->isjobsharing != "") ? $job->scompanyaliasid : $job->companyaliasid;
                                            $jobcategory = ($this->isjobsharing != "") ? $job->sjobcategory : $job->jobcategory;
                                            $companyaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($companyaliasid);
                                            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=41&cd=' . $companyaliasid . '&cat=' . $jobcategory . '&Itemid=' . $this->Itemid;
                                        ?>                                    
                                        <?php if(isset($_field['company'])){ ?>
                                        <a class="jsjobs-image-area-achor" href="<?php echo $link; ?>">
                                            <?php
                                            $imgsrc = $common->getCompanyLogo($job->companyid, $job->companylogo , $this->config);
                                            ?>
                                            <img class="jsjobs-img-company" src="<?php echo $imgsrc; ?>" />  
                                        </a>
                                        <?php } ?>
                                    </span>
                                    <div class="jsjobs-content-wrap">
                                        <div class="jsjobs-data-1">
                                            <div class="jsjobs-data-1-title">
                                                <?php 
                                                    if (isset($_field['jobtitle'])) { 
                                                            $jobaliasid = ($this->isjobsharing != "") ? $job->sjobaliasid : $job->jobaliasid;
                                                            $jobaliasid = $this->getJSModel('common')->removeSpecialCharacter($jobaliasid);                                                                                                                        
                                                            $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=19&bd=' . $jobaliasid . '&Itemid=' . $this->Itemid;
                                                        if(isset($_field['jobtype'])){
                                                            echo '<span class="jsjobs-jobs-types">'.Text::_($job->jobtypetitle).'</span>';
                                                        }
                                                        if ($job->created > $isnew) {
                                                            echo '<span class=" new jsjobs_new_tag">'.Text::_('New').'</span>';
                                                        }
                                                        ?>
                                                        <a id="jsjobs-a-job-tile" href="<?php echo $link; ?>">
                                                        <span class='job-title'><?php echo $job->title; ?></span>
                                                        </a>
                                                        <?php 
                                                    } 
                                                ?>
                                            </div>
                                            <div class="jsjobs-data-1-right">
                                                <span class="jsjobs-posted">
                                                    <?php echo  HTMLHelper::_('date', $job->created, $this->config['date_format']); ?>
                                                </span>
                                                <?php {
                                                if(isset($_field['jobsalaryrange'])){
                                                    if ($job->rangestart) {
                                                        $salary = $this->getJSModel('common')->getSalaryRangeView($job->symbol,$job->rangestart,$job->rangeend,'',$this->config['currency_align']);
                                                        echo "<div class='jsjobs-data-3-wrapper'>";
                                                        
                                                        echo $salary ;
                                                        if(isset($job->salarytypetitle)){
                                                            echo ' / ';
                                                            echo '<span class="js_job_data_2_value">' . $job->salarytypetitle . "</span>";
                                                        }
                                                        echo '</div>';
                                                    } 
                                                }
                                                }?>
                                                <div class="jsjobs-data-3-myjob-no js_noof_jobs">
                                                   <?php  if(isset($_field['noofjobs'])){ ?> 
                                                    <span class="jsjobs-noof-jobs">
                                                    <?php
                                                        echo "<span class='js_job_myjob_numbers'>";
                                                        if ($job->noofjobs != 0) {
                                                            echo $job->noofjobs . " " . Text::_('Jobs');
                                                        } else {
                                                            echo '1' . " " . Text::_('Jobs');
                                                        }
                                                        echo "</span>";
                                                    ?>
                                                    </span>
                                                    <?php } ?>
                                               </div>
                                            </div>
                                        </div>
                                        <div class="jsjobs-data-area">
                                            <div class="jsjobs-data-2">
                                                <?php if(isset($_field['company'])){
                                                        echo "<div class='jsjobs-data-2-wrapper'>";
                                                        if ($this->config['labelinlisting'] == '1') {
                                                            echo "<span class=\"js_job_data_2_title\">" . Text::_($_field['company']) . ": </span>";
                                                        }
                                                        $companyaliasid = ($this->isjobsharing != "") ? $job->scompanyaliasid : $job->companyaliasid;
                                                        $jobcategory = ($this->isjobsharing != "") ? $job->sjobcategory : $job->jobcategory;
                                                        $companyaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($companyaliasid);
                                                        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=41&cd=' . $companyaliasid . '&cat=' . $jobcategory . '&Itemid=' . $this->Itemid;
                                                       
                                                    ?>
                                                     <span class="js_job_data_2_value"><a href="<?php echo $link ?>"><?php echo $job->companyname; ?></a></span>
                                                        <?php
                                                    echo '</div>';
                                                }
                                                if(isset($_field['jobcategory'])){
                                                    echo "<div class='jsjobs-data-2-wrapper js_forcat'>";
                                                    if ($this->config['labelinlisting'] == '1') {
                                                        echo "<span class=\"js_job_data_2_title\">" . Text::_($_field['jobcategory']) . " : </span>";
                                                    }
                                                    echo '<span class="js_job_data_2_value">' . Text::_($job->cat_title) . "</span>";
                                                    echo '</div>';
                                                }
                                                ?>                                                
                                            </div>
                                            <div class="jsjobs-data-2">
                                                <?php
                                                $curdate = Factory::getDate()->format('Y-m-d');
                                                $startpublishing = date('Y-m-d', strtotime($job->startpublishing));
                                                $stoppublishing = date('Y-m-d', strtotime($job->stoppublishing));
                                                ?> 
                                               <?php 
                                                    $customfields = getCustomFieldClass()->userFieldsData( 2 , 1);
                                                    foreach ($customfields as $field) {
                                                        echo  getCustomFieldClass()->showCustomFields($field, 12 ,$job , $this->config['labelinlisting']);
                                                    }
                                                ?>

                                            </div>
                                        </div>   
                                    </div>  
                                                      
                                </div>
                                <div class="jsjobs-main-myjobslist-btn">
                                        <div class="jsjobs-data-myjob-left-area"> 
                                            <?php if(isset($_field['city'])){ ?>
                                                <span class="js_job_data_location_title"><?php echo Text::_('Location').' : ';?></span>
                                                <?php
                                                    if (isset($job->city) AND ! empty($job->city)) {
                                                        echo "<span class=\"js_job_data_location_value\">" . Text::_($job->city) . "</span>";
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <div class="jsjobs-data-myjob-right-area">
                                            <?php
                                        if ($job->status == 0) { ?>
                                            <font id="jsjobs-status-btn"><?php echo Text::_('Waiting for approval');?></font>
                                        <?php
                                        } elseif ($job->status == -1) { ?>
                                            <font id="jsjobs-status-btn-rejected"><?php echo Text::_('Rejected');?></font>
                                        <?php
                                        } elseif ($job->status == 1) {
                                            $show_css = '';
                                            if($startpublishing > $curdate){ 
                                                $show_css = 'jsgivemaring';
                                                ?>
                                                <font id="jsjobs-status-btn"><?php echo Text::_('Unpublished');?></font>
                                            <?php
                                            }
                                                $show_links = false;
                                                if ($this->isjobsharing){
                                                    if ($job->serverstatus == "ok"){
                                                        $show_links = true;
                                                    }else{
                                                        $show_links = false;
                                                    }
                                                }else{
                                                    $show_links = true;
                                                }
                                                if ($show_links) {
                                                   
                                                    $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=formjob&bd=' . $job->jobaliasid . '&Itemid=' . $this->Itemid;
                                                        ?>
                                                        <a href="<?php echo $link ?>" class="company_icon" title="<?php echo Text::_('Edit'); ?>"><?php echo Text::_('Edit'); ?></a>
                                                        <?php
                                                    }
                                                    $jobaliasid = ($this->isjobsharing != "") ? $job->sjobaliasid : $job->jobaliasid;
                                                    $jobaliasid = $this->getJSModel('common')->removeSpecialCharacter($jobaliasid);
                                                    $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=19&bd=' . $jobaliasid . '&Itemid=' . $this->Itemid;
                                                    ?>
                                                    <a href="<?php echo $link ?>" class="company_icon" title="<?php echo Text::_('View'); ?>"><?php echo Text::_('View'); ?></a>
                                                    <?php
                                                        if (isset($job->visitor) && $job->visitor == 'visitor')
                                                            $link_delete = 'index.php?option=com_jsjobs&task=job.deletejob&email=' . $job->contactemail . '&bd=' . $job->jobaliasid . '&Itemid=' . $this->Itemid.'&'.Factory::getSession()->getFormToken().'=1';
                                                        else
                                                            $link_delete = 'index.php?option=com_jsjobs&task=job.deletejob&bd=' . $job->jobaliasid . '&Itemid=' . $this->Itemid.'&'.Factory::getSession()->getFormToken().'=1';
                                                        if (isset($this->uid) && $this->uid != 0) { ?>
                                                            <a href="<?php echo $link_delete ?>" class="company_icon" onclick="return confirmdeletejob();"  title="<?php echo Text::_('Delete'); ?>"><?php echo Text::_('Delete'); ?></a>
                                                            <?php $jobid = ($this->isjobsharing != "") ? $job->locajobid : $job->id; ?>
                                                            <?php $jobaliasid = ($this->isjobsharing != "") ? $job->sjobaliasid : $job->jobaliasid; ?>      
                                                        <?php } ?>
                                                        <?php $jobaliasid = ($this->isjobsharing != "") ? $job->sjobaliasid : $job->jobaliasid; ?>      
                                                        <?php $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_appliedapplications&bd=' . $jobaliasid . '&Itemid=' . $this->Itemid; ?>
                                                            <a class="applied-resume-button-no applied-resume-button-count  <?php echo $show_css; ?>" href="<?php echo $link ?>"><?php echo Text::_('Resume');
                                                                echo ' (' . $job->totalapply . ')';
                                                        ?></a>
                                                        <?php if( $this->getJSModel(JSJOBSVMS)->{JSJOBSVMSFUN}("cGVGT0dCam9tc29jaWFsakVvcnJ0") &&
                                                                  $this->getJSModel('configurations')->getConfigValue('jomsocial_allowpostjob') == 1){ ?>
                                                        <a class="company_icon jsjobs-jomsocial-icon" href="index.php?option=com_jsjobs&c=job&task=job.postjobonjomsocial&id=<?php echo $job->id; ?>&Itemid=<?php echo $this->Itemid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1" title="<?php echo Text::_("Post on JomSocial"); ?>">
                                                            <img src="<?php echo Uri::root();?>components/com_jsjobs/images/social-share.png" />
                                                        </a>
                                                        <?php } ?>
                                            <?php 
                                            } ?>
                                        </div>
                                    </div>  
                    </div> 
                        <?php
                                }
                            }
                        ?>
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deletejob" />
                    <input type="hidden" name="c" value="job" />
                    <input type="hidden" id="id" name="id" value="" />
                    <input type="hidden" name="boxchecked" value="0" />
                </form>
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&sortby='.$this->sortlinks['sorton']. strtolower($this->sortlinks['sortorder']) .'&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
            </form>
            <?php
        } else { // no result found in this category 
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
        }
    } else {
        switch ($this->myjobs_allowed) {
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Jobseeker is not allowed', 'Jobseeker is not allowed in employer private area', 0);
            break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_TO_EDIT_THEIR_JOBS:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Visitor is not allowed in the employer private area', 1);
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
<script type="text/javascript" src="<?php echo Uri::root(); ?>components/com_jsjobs/js/tinybox.js"></script>
<link media="screen" rel="stylesheet" href="<?php echo Uri::root(); ?>components/com_jsjobs/js/style.css" />
<script type="text/javascript" language="Javascript">
    function copyjob(val) {
        jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=job.getcopyjob", {val: val}, function (data) {
            if (data == true) {
                TINY.box.show({html: "<?php echo Text::_('Job has been copied'); ?>", animate: true, boxid: 'frameless', close: true, width: 250});
            } else {
                TINY.box.show({html: "<?php echo Text::_('Cannot add new job'); ?>", animate: true, boxid: 'frameless', close: true, width: 250});
            }
            setTimeout(function () {
                window.location.reload();
            }, 3000);
        });
    }
</script>
<?php
$document = Factory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
?>
