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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;    

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
    <div id="js_main_wrapper">
        <span class="js_controlpanel_section_title"><?php echo Text::_('Resume By Categories') . ' ' . $this->categoryname; ?></span>
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
                            <a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=resumebycategory&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo Text::_('Resume By Categories'); ?>">
                                <?php echo Text::_("Resume By Categories"); ?>
                            </a>
                        </li>
                        <li>
                            <?php echo Text::_('Resume By Categories'). ' ' . $this->categoryname; ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php
    if ($this->resumes) {
        if ($this->userrole->rolefor == 1) { // employer
            $fieldsordering = JSModel::getJSModel('fieldsordering')->getFieldsOrderingByFieldFor(3);
            $_field = array();
            foreach($fieldsordering AS $field){
                if($field->showonlisting == 1){
                    $_field[$field->field] = $field->fieldtitle;
                }
            }
            $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bycategory&'.$this->catorsubcat.'=' . $this->resumes[0]->aliasid . '&Itemid=' . $this->Itemid;
            if ($this->sortlinks['sortorder'] == 'ASC')
                $img = Uri::root()."components/com_jsjobs/images/sort0.png";
            else
                $img = Uri::root()."components/com_jsjobs/images/sort1.png";
            ?>
                <div id="sortbylinks">
                    <?php if (isset($_field['application_title'])) { ?>
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'application_title') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['application_title']; ?>"><?php echo Text::_('Title'); ?><?php if ($this->sortlinks['sorton'] == 'application_title') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['jobtype'])) { ?>
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo Text::_('Job Type'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <?php if (isset($_field['salary'])) { ?>
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo Text::_('Salary Range'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                    <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo Text::_('Posted'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                </div>
                <div id="jsjobs-main-wrapper">
                <?php
                $isnew = date("Y-m-d H:i:s", strtotime("-" . $this->config['newdays'] . " days"));
                foreach ($this->resumes as $resume) {
                        $comma = "";
                        ?>
                        <div class="jsjobs-main-wrapper-resume-searchresults">
                          <div class="jsjobs-resume-searchresults">
                          <div class="jsjobs-resume-search">
                            <?php if (isset($_field['photo'])) { ?>
                                <div class="jsjobs-image-area">
                                  <div class="jsjobs-img-border">
                                    <div class="jsjobs-image-wrapper">
                                        <?php
                                        if ($resume->photo != '') {
                                            $imgsrc = Uri::root().$this->config['data_directory'] . "/data/jobseeker/resume_" . $resume->id . "/photo/" . $resume->photo;
                                        } else {
                                            $imgsrc = Uri::root()."components/com_jsjobs/images/user_b.png";
                                        }
                                        ?>
                                        <img class="js_job_image" src="<?php echo $imgsrc; ?>" />
                                    </div>
                                  </div>
                                </div>
                            <?php } ?>
                            <div class="jsjobs-data-area">
                                    <div class='jsjobs-data-2-wrapper-title'>
                                        <?php if (isset($_field['jobtype'])) { ?>
                                            <div class="jsjobs-jobs-types-wrp">
                                                <span class="jsjobs-jobs-types">
                                                  <span class="js_job_data_2_value"><?php echo Text::_($resume->jobtypetitle); ?></span>
                                                </span>
                                            </div>
                                          <?php } ?>
                                        <span class="jsjobs-name-title">
                                        <?php if (isset($_field['first_name'])) { ?>
                                            <?php echo $resume->first_name; ?>
                                        <?php } ?>
                                        <?php if (isset($_field['last_name'])) { ?>
                                            <?php echo ' ' . $resume->last_name; ?>
                                        <?php } ?>
                                        </span>
                                        <span class="jsjobs-posted jsjobs-by-category-posted">
                                          <?php
                                           echo "<span class='js_job_data_2_created_myresume'>" . Text::_('Created').": ";
                                           echo HTMLHelper::_('date', $resume->created, $this->config['date_format']) . "</span>";
                                         ?>
                                        </span>
                                    </div>
                                    <div class='jsjobs-data-2-wrapper font'>
                                        <?php if (isset($_field['application_title'])) { ?>
                                            <?php echo $resume->application_title; ?>
                                        <?php } ?>
                                    </div>
                                    <div class='jsjobs-data-2-wrapper cat'>
                                            <?php if (isset($_field['job_category'])) { ?>
                                                <span class="jsjobs-main-wrap">
                                                    <span class="js_job_data_2_title"><?php echo Text::_($_field['job_category']); ?>: </span>
                                                    <span class="js_job_data_2_value"><?php echo Text::_($resume->categorytitle); ?></span>
                                                </span>
                                            <?php } ?>
                                        <span class="jsjobs-main-wrap">
                                            <?php if (isset($_field['salary'])) { ?>
                                            <span class="js_job_data_2_title"><?php echo Text::_($_field['salary']); ?> : </span>
                                            <span class="js_job_data_2_value">
                                                <?php
                                                    $salary = $this->getJSModel('common')->getSalaryRangeView($resume->symbol,$resume->rangestart,$resume->rangeend,Text::_($resume->salarytype),$this->config['currency_align']);
                                                    echo $salary;
                                                ?>
                                            </span>
                                        </span>
                                    <?php } ?>
                                    </div>
                                        <div class="jsjobs-data-2-wrapper cat">
                                           <?php if (isset($_field['heighestfinisheducation'])) { ?>
                                                 <span class="jsjobs-main-wrap">
                                                 <span class="js_job_data_2_title"><?php echo Text::_($_field['heighestfinisheducation'])?> : </span>
                                                 <span class="js_job_data_2_value">
                                                    <?php echo $resume->educationtitle; ?>
                                                  </span>
                                                </span>
                                           <?php } ?>
                                            <?php if (isset($_field['total_experience'])) { ?>
                                               <span class="jsjobs-main-wrap">
                                                <span class="js_job_data_2_title"><?php echo Text::_($_field['total_experience']); ?>: </span>
                                                <span class="js_job_data_2_value">
                                                    <?php
                                                    if (empty($resume->exptitle))
                                                        echo $resume->total_experience;
                                                    else
                                                        echo Text::_($resume->exptitle);
                                                    ?>
                                                </span>
                                               </span> 
                                            <?php } 
                                                $customfieldobj = getCustomFieldClass();
                                                $customfields = $customfieldobj->userFieldsData( 3 , 1 , 1);
                                                foreach ($customfields as $field) {
                                                    echo  $customfieldobj->showCustomFields($field, 10 ,$resume);
                                                }

                                            ?>
                                        </div>
                                    
                                </div>
                                </div>
                            </div>
                            <div class="jsjobs-data-3-myresume">
                                    <?php
                                    $address = '';
                                    $comma = '';
                                    if ($resume->cityname != '') {
                                        $address = $comma . Text::_($resume->cityname);
                                        $comma = " ,";
                                    }
                                    switch ($this->config['defaultaddressdisplaytype']){
                                        case 'csc':
                                            if ($resume->statename != '') {
                                                $address .= $comma . Text::_($resume->statename);
                                                $comma = " ,";
                                            }
                                            if ($resume->countryname != '')
                                                $address .= $comma . Text::_($resume->countryname);
                                        break;
                                        case 'cs':
                                            if ($resume->statename != '') {
                                                $address .= $comma . Text::_($resume->statename);
                                                $comma = " ,";
                                            }
                                        break;
                                        case 'cc':
                                            if ($resume->countryname != '')
                                                $address .= $comma . Text::_($resume->countryname);
                                        break;
                                    }
                                    ?> 
                                    <span class="jsjobs-location">
                                        <span class="js_job_data_2_value"><?php echo $address; ?></span>
                                    </span>
                                    <span class="jsjobs-view-resume">
                                        <?php 
                                        $resumealiasid = $this->getJSModel('common')->removeSpecialCharacter($resume->resumealiasid);
                                        $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=3&rd=' . $resumealiasid . '&Itemid=' . $this->Itemid; 
                                        ?>
                                        <a class="js_job_data_area_button" href="<?php echo $link ?>"><?php echo Text::_('View Resume'); ?></a>
                                    </span>
                                </div>
                        </div> 
                    <?php
                }
                $querystring = '&'.$this->catorsubcat.'=' . $this->resumes[0]->aliasid . '&Itemid=' . $this->Itemid;
                ?>
                <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bycategory' . $querystring ,false); ?>" method="post">
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
                    <?php /*  if ($this->config['resume_rss'] == 1) { ?>
                        <div id="rss">
                            <a href="index.php?option=com_jsjobs&c=rss&view=rss&layout=rssresumes&format=rss" target="_blank"><img width="24" height="24" src="<?php echo Uri::root();?>components/com_jsjobs/images/rss.png" text="Resume RSS" alt="Resume RSS" /></a>
                        </div>
                    <?php }  */ ?>
                </form>	
                <?php
            } else { // not allowed job posting 
                $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
            }
        } else { // no result found in this category 
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
        }
        ?>	
        <script language="javascript">
            //showsavesearch(0); 
        </script>
                </div>
        <?php
    }//ol
    ?>
</div> 
    <script type="text/javascript" language="javascript">
        function setLayoutSize() {
            var totalwidth = document.getElementById("rl_maindiv").offsetWidth;
            var per_width = (totalwidth * 0.23) - 10;
            var totalimagesdiv = document.getElementsByName("rl_imagediv").length;
            for (var i = 0; i < totalimagesdiv; i++) {
                document.getElementsByName("rl_imagediv")[i].style.minWidth = per_width + "px";
                document.getElementsByName("rl_imagediv")[i].style.width = per_width + "px";
            }
            var totalimages = document.getElementsByName("rl_image").length;
            for (var i = 0; i < totalimages; i++) {
                //document.getElementsByName("rl_image")[i].style.minWidth = per_width+"px";
                document.getElementsByName("rl_image")[i].style.width = per_width + "px";
                document.getElementsByName("rl_image")[i].style.maxWidth = per_width + "px";
            }
        }
        setLayoutSize();
    </script>
