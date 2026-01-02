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

$link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $this->Itemid;
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
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
?>

            <div id="jsjobs-main-wrapper">
                <span class="jsjobs-main-page-title"><?php echo Text::_('My Applied Jobs'); ?></span>
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
                                    <?php echo Text::_('My Applied Jobs'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <?php
                $days = $this->config['newdays'];
                $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                if ($this->myappliedjobs_allowed == VALIDATE) {
                    if ($this->application) {
                        if ($this->sortlinks['sortorder'] == 'ASC')
                            $img = Uri::root()."components/com_jsjobs/images/sort0.png";
                        else
                            $img = Uri::root()."components/com_jsjobs/images/sort1.png";

                $fieldsordering = JSModel::getJSModel('fieldsordering')->getFieldsOrderingByFieldFor(2);
                $_field = array();
                foreach($fieldsordering AS $field){
                    if($field->showonlisting == 1){
                        $_field[$field->field] = $field->fieldtitle;
                    }

                }
                if (isset($this->application)) {
                    foreach ($this->application as $job) {
                        $companyaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($job->companyaliasid);
                        $link_comp_information = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=34&cd=' . $companyaliasid  . '&cat=' . $job->jobcategory . '&Itemid=' . $this->Itemid;
                        $comma = "";
                        ?>
                        <div class="jsjobs-main-wrapper-listappliedjobs">
                            <div class="jsjobs-main-wrapper-appliedjobslist">
                                <div class="jsjobs-image-area">
                                    <?php if(isset($_field['company'])){ ?>
                                        <a href="<?php echo $link_comp_information;?>">
                                            <?php
                                            if (!empty($job->companylogo)) {
                                                if ($this->isjobsharing) {
                                                    $imgsrc = $job->companylogo;
                                                } else {
                                                    $imgsrc = Uri::root().$this->config['data_directory'] . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->companylogo;
                                                }
                                            } else {
                                                $imgsrc = JSModel::getJSModel('common')->getCompanyLogo($job->companyid, $job->companylogo , $this->config);
                                            }
                                            ?>
                                            <img src="<?php echo $imgsrc; ?>" />
                                        </a>
                                    <?php } ?>
                                </div>
                                <div class="jsjobs-data-area">
                                    <div class="jsjobs-data-1">
                                        <?php if (isset($_field['jobtitle'])) { ?>
                                            <span class="jsjobs-title">
                                                <?php 
                                                $jobaliasid = $this->getJSModel('common')->removeSpecialCharacter($job->jobaliasid);
                                                $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=16&bd=' . $jobaliasid . '&Itemid=' . $this->Itemid; 
                                                ?>
                                                <a href="<?php echo $link; ?>" class=''><?php echo htmlspecialchars($job->title); ?></a>
                                            </span>

                                        <?php } ?>
                                        <span class="jsjobs-posted jsjobstooltip" title="<?php echo Text::_('Applied date'); ?>">
                                        <?php 
                                            echo HTMLHelper::_('date', $job->apply_date, $this->config['date_format']); 
                                        ?>
                                        </span>
                                        <?php 
                                        if(isset($_field['jobtype'])){  ?>
                                                <span class="jsjobs-jobstypes">
                                                    <div class='js_job_data_2_wrapper'>
                                                        <span class="js_job_data_2_value"><?php echo Text::_($job->jobtypetitle); ?> </span>
                                                    </div>
                                                </span>
                                        <?php
                                        }
                                        if(isset($_field['jobsalaryrange'])){
                                            if ($job->rangestart) {
                                                $salary = $this->getJSModel('common')->getSalaryRangeView($job->symbol,$job->rangestart,$job->rangeend,$job->salaytype,$this->config['currency_align']);
                                                echo "<span class='jsjobs-salary-type-wrapper'>";
                                                echo "<span class=\"jsjobs-salary-type-value\">" . $salary . "</span></span>";
                                            }
                                        } 
                                        if(isset($_field['noofjobs'])){
                                            if (isset($this->fieldsordering['noofjobs']) && $this->fieldsordering['noofjobs'] == 1) {
                                            ?>
                                            <span class="jsjobs-noofjobs">
                                                <span class="jsjobs-noofjob-value">
                                                    <span class="jsjsobs-jobsno">
                                                        <?php
                                                        if ($job->noofjobs != 0) {
                                                            echo htmlspecialchars($job->noofjobs) . " " . Text::_('Jobs');
                                                        }?>
                                                    </span>
                                                </span>
                                            </span>
                                            <?php
                                            }
                                        }   ?>
                                    </div>

                                    <div class="jsjobs-data-2"> <?php
                                        if(isset($_field['company'])){
                                            echo "<div class='jsjobs-data-2-wrapper'>";
                                                if ($this->config['labelinlisting'] == '1') {
                                                    echo "<span class=\"jsjobs-data-2-title\">" . Text::_($_field['company']) .": ". "</span>";
                                                }
                                            ?>
                                                <span class="jsjobs-data-2-value">
                                                    <a class="jl_company_a" href="<?php echo $link_comp_information ?>"><?php echo htmlspecialchars($job->companyname); ?></a>
                                                </span>
                                            </div>
                                        <?php
                                        }
                                        if(isset($_field['jobcategory'])){
                                            echo "<div class='jsjobs-data-2-wrapper'>";
                                            if ($this->config['labelinlisting'] == '1') {
                                                echo "<span class=\"jsjobs-data-2-title\">" . Text::_($_field['jobcategory']) .": ". " </span>";
                                            }
                                            echo "<span class=\"jsjobs-data-2-value\">";
                                                if (isset($job->cat_title)) {
                                                    echo htmlspecialchars($job->cat_title);
                                                }
                                                echo "</span></div>";
                                        }   
                                        $customfields = getCustomFieldClass()->userFieldsData( 2 , 1);
                                        foreach ($customfields as $field) {
                                            echo  getCustomFieldClass()->showCustomFields($field, 3 ,$job , $this->config['labelinlisting']);
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div> 
                    
                    <div class="jsjobs-main-wrapper-appliedjobslist-btn">
                        <span class="jsjobs-main-wrapper-btn">
                            <span class="jsjobs-location">
                                <?php
                                if(isset($_field['city'])){; ?>
                                        <span class="js_job_data_location_value"><?php echo $job->location; ?></span>
                                    <?php
                                } ?>
                           </span>
                           <?php 
                           $resumealiasid = $this->getJSModel('common')->removeSpecialCharacter($job->resumealiasid);
                           $link_viewresume = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=7&rd=' . $resumealiasid . '&Itemid=' . $this->Itemid; 
                           ?>
                           <span class="jsjobs-resume-btn"> <a href="javascript:void(0)" onclick="hideShowResumeInfo('<?php echo $job->id;?>');" ><?php echo Text::_('Applied Info');?></a></span>
                                
                        </span>     
                    </div>
                    <div class="jsjobs-data-title-cover info_<?php echo $job->id;?>">
                        <span class="jsjobs-resume-data">
                            <span class="jsjobs-resume-title"><?php echo Text::_("Resume");?>:&nbsp;</span>
                            <?php $resumealiasid = JSModel::getJSModel('common')->removeSpecialCharacter($job->resumealiasid); ?>
                            <span class="jsjobs-resume-value"><a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&rd=<?php echo $resumealiasid; ?>&nav=1&Itemid=<?php echo $this->Itemid; ?>"><?php echo $job->applicationtitle;?></a></span>
                        </span>
                        <span class="jsjobs-cover-letter-data">
                            <span class="jsjobs-cover-letter-title"><?php echo Text::_("Cover Letter");?>:&nbsp;</span>
                            <span class="jsjobs-cover-letter-value"><a href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=view_coverletter&cl=<?php echo $job->coverletterid; ?>&Itemid=<?php echo $this->Itemid; ?>"><?php echo $job->coverlettertitle;?></a></span>
                        </span>
                    </div>
                    </div>
                <?php
                }
            }else { // no result found in this category 
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
                }
            ?>
            </div>
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
        <?php } else { // no result found in this category 
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
                }
    } else {
        switch ($this->myappliedjobs_allowed) {
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
                break;
        }
    }
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
<?php
$document = Factory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
?>

<script type="text/javascript">
    function hideShowResumeInfo (jobid) {
        jQuery('.info_'+jobid).toggle();
    }
</script>
