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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;  


?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'controlpanel') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'controlpanel') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>
<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    $suggested_jobs_text = Text::_('Newest Jobs');
    $userrole = $this->userrole;
    $config = $this->config;
    if(isset($userrole->uid)){
      $userdetail = Factory::getUser($userrole->uid);
      $user_name = $userdetail->get('name');
      $user_email = $userdetail->get('email');
    }
    $jscontrolpanel = $this->jscontrolpanel;
    if (isset($userrole->rolefor)) {
        if ($userrole->rolefor != '') {
            if ($userrole->rolefor == 2){ // job seeker
                $allowed = true;
                $suggested_jobs_text = Text::_('Suggested Jobs');
            }elseif ($userrole->rolefor == 1) { // employer
                if ($config['employerview_js_controlpanel'] == 1)
                    $allowed = true;
                else
                    $allowed = false;
            }
        }else {
            $allowed = true;
        }
    } else{
        if($config['visitorview_js_controlpanel'] == 1)
            $allowed = true; // user not logined
        else
            $allowed = false;
    }
    if ($allowed == true) {
        $message = '';
        if ($jscontrolpanel['jsexpire_package_message'] == 1) {
            if (!empty($this->packagedetail[0]->packageexpiredays)) {
                $days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
                if ($days == 1)
                    $days = $days . ' ' . Text::_('Day');
                else
                    $days = $days . ' ' . Text::_('Days');
                $message = "<strong><font color='red'>" . Text::_('Your Package') . ' &quot;' . $this->packagedetail[0]->packagetitle . '&quot; ' . Text::_('Has Expired') . ' ' . $days . ' ' . Text::_('Ago') . " <a href='index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=$this->Itemid'>" . Text::_('Job Seeker Packages') . "</a></font></strong>";
            }
            if ($message != '') {
                ?>
                <div id="errormessage" class="errormessage">
                    <div id="message"><?php echo $message; ?></div>
                </div>
            <?php
            }
        }
        ?>
        <div id="jsjobs-main-wrapper">
            <span class="jsjobs-main-page-title"><?php echo Text::_('My Stuff'); ?></span>
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
                    </ul>
                </div>
              </div>
            <?php } ?>
            <div class="jsjobs-emp-left-menu">
              <div class="jsjobs-emp-short-links">
                <?php if(isset($userrole->uid) && $userrole->rolefor == 2){ ?>
                  <div class="jsjobs-emp-intro-wrp">
                    <div class="jsjobs-emp-intro-img">
                      <?php 
                        if ($this->cp_data['latest_resume'] != '') {
                          $image_source = Uri::root().$this->config['data_directory'] . "/data/jobseeker/resume_" . $this->cp_data['latest_resume']->id . "/photo/" . $this->cp_data['latest_resume']->photo;
                        } else {
                          $image_source = Uri::root()."components/com_jsjobs/images/user_b.png";
                        }
                      ?>
                      <img src="<?php echo $image_source?>">
                    </div>
                    <div class="jsjobs-emp-intro-name">
                      <?php echo $user_name;?>
                    </div>
                    <div class="jsjobs-emp-intro-cat">
                      <?php echo $user_email;?>
                    </div>
                    <?php 
                    if(isset($userrole->rolefor) && $userrole->rolefor == 2){
                      $link = "index.php?option=com_users&view=profile&Itemid=" . $this->Itemid;
                      $text = Text::_('Profile');?>
                      <div class="jsjobs-emp-intro-btn-wrp">
                        <a class="jsjobs-emp-intro-btn" href="<?php echo $link; ?>">
                          <?php echo Text::_('Edit Profile');?>
                        </a>
                      </div>
                    <?php } ?>
                  </div>
                <?php } ?>
                <div class="jsjobs-emp-links-heading">
                  <?php echo Text::_('Short Links'); ?>        
                </div>
                <?php 
                $showjobblock = checkBlocks('jsmystuff_area', $userrole, $config, $jscontrolpanel);
                if($showjobblock){
                ?>
                <div class="jsjobs-emp-links-wrp">
                  <div class="jsjobs-emp-links">
                    <?php
                      $print = checkLinks('jobcat', $userrole, $config, $jscontrolpanel);
                      if ($print) {
                    ?>
                    <a class="jsjobs-emp-menu" title="" href="index.php?option=com_jsjobs&c=category&view=category&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>">
                      <div class="jsjobs-cp-img-icon">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/job-category.png">
                      </div>
                      <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('Job By Category'); ?></span>
                    </a> 
                    <?php
                      }
                      $print = checkLinks('formresume', $userrole, $config, $jscontrolpanel);
                      if ($print) {
                    ?>
                    <a class="jsjobs-emp-menu" title="" class="menu_style-job-seeker color1" href="index.php?option=com_jsjobs&view=resume&layout=formresume&Itemid=<?php echo $this->Itemid; ?>">
                      <div class="jsjobs-cp-img-icon">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/resume.png">
                      </div>
                      <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('Add Resume'); ?></span>
                    </a>
                    <?php }
                      $print = checkLinks('my_jobsearches', $userrole, $config, $jscontrolpanel);
                      if ($print) {
                    ?>
                    <a class="jsjobs-emp-menu" title="" href="index.php?option=com_jsjobs&c=jobsearch&view=jobsearch&layout=my_jobsearches&Itemid=<?php echo $this->Itemid; ?>">
                      <div class="jsjobs-cp-img-icon">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/search-job.png">
                      </div>
                      <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('Job Save Search'); ?></span>
                    </a> 
                    <?php
                      }
                      $print = checkLinks('mycoverletters', $userrole, $config, $jscontrolpanel);
                      if ($print) {
                    ?>
                    <a class="jsjobs-emp-menu" title="" href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>">
                      <div class="jsjobs-cp-img-icon">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/cover-letter.png">
                      </div>
                      <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('My Cover Letter'); ?></span>
                    </a>
                    <?php
                      }
                      $print = checkLinks('userdata_jobseeker', $userrole, $config, $jscontrolpanel);
                      if ($print) {
                    ?>
                    <a class="jsjobs-emp-menu" title="" href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=adderasedatarequest&Itemid=<?php echo $this->Itemid; ?>">
                      <div class="jsjobs-cp-img-icon">
                        <img  src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/userdata.png">
                      </div>
                      <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('User Data'); ?></span>
                    </a>
                    <?php }
                      $print = checkLinks('formcoverletter', $userrole, $config, $jscontrolpanel);
                      if ($print) {
                    ?>
                    <a class="jsjobs-emp-menu" title="" href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=formcoverletter&Itemid=<?php echo $this->Itemid; ?>">
                      <div class="jsjobs-cp-img-icon">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/add-coverletter.png">
                      </div>
                      <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('Add Cover Letter'); ?></span>
                    </a>
                    <?php 
                    }
                    $showstatsblock = checkBlocks('jsmystats_area', $userrole, $config, $jscontrolpanel);
                    if($showstatsblock){ ?>
                      <?php
                        $print = checkLinks('jsmy_stats', $userrole, $config, $jscontrolpanel);
                        if ($print) {
                      ?>
                      <a class="jsjobs-emp-menu"  href="index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=my_stats&Itemid=<?php echo $this->Itemid; ?>">
                        <div class="jsjobs-cp-img-icon">
                          <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/reports.png">
                        </div>
                        <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('My Stats'); ?></span>
                      </a>
                      <?php
                        }
                    }
                        $print = checkLinks('jspackages', $userrole, $config, $jscontrolpanel);
                        if ($print) {
                      ?>
                      <a class="jsjobs-emp-menu"  href="index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=<?php echo $this->Itemid; ?>">
                        <div class="jsjobs-cp-img-icon">
                          <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/package.png">
                        </div>
                        <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('Packages'); ?></span>
                      </a> 
                      <?php
                        }
                        $print = checkLinks('jspurchasehistory', $userrole, $config, $jscontrolpanel);
                        if ($print) {
                      ?>
                      <a class="jsjobs-emp-menu"  href="index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=jobseekerpurchasehistory&Itemid=<?php echo $this->Itemid; ?>">
                        <div class="jsjobs-cp-img-icon">
                          <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/log-history.png">
                        </div>
                        <span class="jsjobs-cp-jobseeker-title"><?php echo Text::_('Purchase History'); ?></span>
                      </a>
                      <?php
                        }
                      ?> 
                    <?php
                      $print = checkCounts('jobsloginlogout', $userrole, $jscontrolpanel);
                      if ($print) {
                        $redirectUrl = Route::_('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $this->Itemid ,false);
                        if (isset($userrole->rolefor)) {//jobseeker
                          $redirect = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
                          $link = "index.php?option=com_jsjobs&task=jsjobs.logout&return=".$redirect."&Itemid=" . $this->Itemid;
                          $text = Text::_('Logout');
                          $icon = "login.png";
                        } else {
                          $redirectUrl = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
                          $redirectUrl = '&amp;return=' . $redirectUrl;
                          $default_login_from = $this->getJSModel('configurations')->getConfigValue('default_login_from');
                          if($default_login_from == 1){ // JS Jobs login
                            $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $this->Itemid . $redirectUrl;    
                          }elseif($default_login_from == 2){ // Joomla Login
                            $link = 'index.php?option=com_users&view=login&Itemid=' . $this->Itemid . $redirectUrl;
                          }else{ // custom link
                              $login_custom_link = $this->getJSModel('configurations')->getConfigValue('login_custom_link');
                              $link = $login_custom_link;
                              if (!preg_match("~^(?:f|ht)tps?://~i", $link)) { // If not exist then add http 
                                      $link = "http://" . $link; 
                              }                                         
                              if($login_custom_link == ""){
                                $link = 'index.php?option=com_jsjobs&c=common&view=common&layout=userlogin&Itemid=' . $this->Itemid . $redirectUrl;    
                              }
                          }
                          $text = Text::_('Login');
                          $icon = "login.png";
                        }
                        ?> 
                        <a class="jsjobs-emp-menu" title="" href="<?php echo $link; ?>">
                          <div class="jsjobs-cp-img-icon">
                            <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/<?php echo $icon; ?>">
                          </div>
                          <span class="jsjobs-cp-jobseeker-title"><?php echo $text; ?></span>
                        </a>
                      <?php } ?> 
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
             <div id="jsjobs-emp-cp-wrapper">
              <div class="jsjobs-cp-toprow-job-seeker">
              <?php
              $print = checkCounts('newestjobs_counts', $userrole, $jscontrolpanel);
              if ($print) {
                  ?>
                  <div class="js-menu-wrap-job-seeker">
                      <a class="menu_style-job-seeker color3">
                        <img class="jsjobs-img-job-seeker" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/newest-jobs.png"> 
                        <span class="jsjobs-title-job-seeker">
                          <div class="bold-text">
                            <?php
                              if(isset($this->cp_data['newest_jobs']))
                                { echo $this->cp_data['newest_jobs'];}
                              else{ echo '0';}
                            ?>
                          </div>
                          <div class="nonbold-text"><?php echo Text::_('Newest Jobs'); ?></div>
                        </span>
                      </a>
                      <div class="box-footer">
                        <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs&Itemid=<?php echo $this->Itemid; ?>">
                          <span class="box-footer-left">
                            <?php echo Text::_('View Details'); ?>
                          </span>
                          <span class="box-footer-right">
                            <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/1.png">
                          </span>
                        </a>
                      </div>
                  </div>
                  <?php
              }
              $print = checkCounts('appliedjobs_counts', $userrole, $jscontrolpanel);
              if ($print) {
                    ?>
                   <div class="js-menu-wrap-job-seeker">
                      <a class="menu_style-job-seeker color1">
                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/applied-job.png">
                        <span class="jsjobs-title-job-seeker">
                          <div class="bold-text">
                            <?php
                              if(isset($this->cp_data['total_appliedjob']))
                                { echo $this->cp_data['total_appliedjob'];}
                              else{ echo '0';}
                            ?>
                          </div>
                          <div class="nonbold-text"><?php echo Text::_('Applied Job'); ?></div>
                        </span>
                      </a>
                      <div class="box-footer">
                        <?php $link_applied = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $this->Itemid; ?>
                        <a href="<?php echo $link_applied;?>">
                          <span class="box-footer-left">
                            <?php echo Text::_('View Details'); ?>
                          </span>
                          <span class="box-footer-right">
                            <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/2.png">
                          </span>
                        </a>
                      </div>
                   </div>
                    <?php
              }
              $print = checkCounts('myresumes_counts', $userrole, $jscontrolpanel);
              if ($print) {
                  ?>
                  <div class="js-menu-wrap-job-seeker">
                    <a class="menu_style-job-seeker color2">
                      <img class="jsjobs-img-job-seeker" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/resume.png"> 
                      <span class="jsjobs-title-job-seeker">
                        <div class="bold-text">
                          <?php
                            if(isset($this->cp_data['total_resumes']))
                                { echo $this->cp_data['total_resumes'];}
                            else{ echo '0';}
                          ?>
                        </div>
                        <div class="nonbold-text"><?php echo Text::_('My Resume'); ?></div>
                      </span>
                    </a>
                    <div class="box-footer">
                      <a href="index.php?option=com_jsjobs&c=jobapply&view=resume&layout=myresumes&Itemid=<?php echo $this->Itemid; ?>">
                        <span class="box-footer-left">
                          <?php echo Text::_('View Details'); ?>
                        </span>
                        <span class="box-footer-right">
                          <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/3.png">
                        </span>
                      </a>
                    </div>
                  </div>
                   <?php
              }
              $print = checkCounts('myjobsearches_counts', $userrole, $jscontrolpanel);
              if ($print) {
                  ?>
                  <div class="js-menu-wrap-job-seeker">
                      <a class="menu_style-job-seeker color4">
                          <img class="jsjobs-img-job-seeker" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/search-job.png"> 
                          <span class="jsjobs-title-job-seeker">
                            <div class="bold-text">
                              <?php
                                if(isset($this->cp_data['job_save_search']))
                                  { echo $this->cp_data['job_save_search'];}
                                else{ echo '0';}
                              ?>
                            </div>
                            <div class="nonbold-text"><?php echo Text::_('Job Save Search'); ?></div>
                          </span>
                      </a>
                      <div class="box-footer">
                        <a href="index.php?option=com_jsjobs&c=jobsearch&view=jobsearch&layout=my_jobsearches&Itemid=<?php echo $this->Itemid; ?>">
                          <span class="box-footer-left">
                            <?php echo Text::_('View Details'); ?>
                          </span>
                          <span class="box-footer-right">
                            <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/jobseeker/top-box/4.png">
                          </span>
                        </a>
                      </div>
                  </div>
                  <?php
              }
              ?>
              </div>
              <?php 
              $showjobsgraph = checkBlocks('jsactivejobs_graph', $userrole, $config, $jscontrolpanel);
              if($showjobsgraph) { 
                ?>
                <div class="jsjobs-jobseeker-cp-wrapper">
                   <div class="js-col-xs-12 js-col-md-12 js-cp-graph-area">
                      <span class="js-cp-graph-title"><?php echo Text::_('Active Jobs');?></span>
                      <div class="jsjobs-cp-graph-area">
                          <div id="curve_chart" class="js_jobseeker_chart"></div>
                      </div>
                   </div>
                </div>
                <?php
              } ?>
              <div class="jsjobs-cp-jobseeker-suggested-applied-panel">
                 <?php $showapplied_box = checkBlocks('jsappliedresume_box', $userrole, $config, $jscontrolpanel);
                  if($showapplied_box && isset($userrole->rolefor) && $userrole->rolefor == 2 ){  ?>
                    <div class="js-cp-applied-resume">
                     <div class="js-cp-resume-jobs">
                         <span class="js-cp-applied-resume-title"><?php echo Text::_('Applied Resume');?></span>
                         <div class="js-col-xs-12 js-col-md-12 js-appliedresume-area">
                             <?php

                              $applied_resume = $this->cp_data['applied_resume'];

                              if(isset($applied_resume) AND (!empty($applied_resume))){ 
                                foreach ($applied_resume as $resume) {
                                  $jobaliasid = $this->getJSModel('common')->removeSpecialCharacter($resume->jobaliasid);
                                  $link_viewjob = "index.php?option=com_jsjobs&c=job&view=job&layout=view_job&bd=".$jobaliasid."&Itemid=".$this->Itemid;
                                  
                                  $imgsrc = Uri::root()."components/com_jsjobs/images/user_b.png";
                                  if (isset($resume->photo) && $resume->photo != "") {
                                      if ($this->isjobsharing) {
                                          $imgsrc = $resume->photo;
                                      } else {
                                          $imgsrc = Uri::root().$this->config['data_directory'] . "/data/jobseeker/resume_" . $resume->appid . '/photo/' . $resume->photo;
                                      }
                                  }
                                  ?>
                                 <div id="jsjobs-appliedresume-seeker">
                                   <div class="jsjobs-cp-resume-applied">
                                       <div class="js-cp-image-area">
                                        <a href="">
                                          <img class="js-cp-imge-user" src="<?php echo $imgsrc;?>">
                                        </a>
                                       </div>
                                       <div class="js-cp-content-area">
                                           <div class="js-cp-company-title">
                                            <?php
                                            if(isset($resume->jobtypetitle)){
                                                echo '<div><span class="jsjobs-status">'.Text::_($resume->jobtypetitle).'</span></div>';
                                            }
                                            ?>
                                            <a href="<?php echo $link_viewjob;?>">
                                              <?php echo $resume->jobtitle; ?>
                                            </a>
                                            <span class="jsjobs-salary">
                                             <?php echo $resume->symbol.' '.$resume->salaryfrom.' - '.$resume->salaryto.'<span> / '.$resume->salarytype.'</span>';?>
                                            </span>
                                            <?php if(isset($resume->noofjobs) && $resume->noofjobs != null){ ?>
                                              <span class="jsjobs-noofjobs">
                                               <?php echo $resume->noofjobs.' jobs'; ?>
                                              </span>
                                            <?php } ?>
                                           </div>
                                           <div class="js-cp-company-location">
                                              <?php echo $resume->application_title; ?>
                                           </div>
                                           <div class="js-cp-company-email">
                                             <span class="jsjobs-title"><?php echo Text::_('Email Address');?> : </span><span class="jsjobs-value"><?php echo $resume->email_address; ?></span>
                                           </div>
                                           <div class="js-cp-company-catagory">
                                             <span class="jsjobs-title"><?php echo Text::_('Category');?> :</span><span class="jsjobs-value"><?php echo Text::_($resume->cat_title); ?></span>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="jsjobs-cp-resume-applied-lower">
                                      <span class="jsjobs-location"><?php echo $resume->location; ?>kdkdk</span>
                                      <span class="js-cp-pending">

                                      
                                          <?php 
                                          if ($this->config['show_applied_resume_status'] == 1) { 
                                              if ($resume->action_status == 4) { ?>
                                                  <span class="js-cp-jobs-wating"><?php echo Text::_('Rejected'); ?></span>
                                              <?php } elseif ($resume->action_status == 3) { ?>
                                                 <span class="js-cp-jobs-wating"><?php echo Text::_('Hired'); ?></span>
                                              <?php } elseif ($resume->action_status == 5) { ?>
                                                  <span class="js-cp-jobs-wating"><?php echo Text::_('Shortlist'); ?></span>
                                              <?php } elseif ($resume->action_status == 2) { ?>
                                                 <span class="js-cp-jobs-wating"><?php echo Text::_('Spam');  ?></span>
                                                  <?php
                                              }
                                          } ?>

                                      
                                      </span>
                                   </div>
                                  </div>
                                <?php
                                }

                              }else{
                                $this->jsjobsmessages->getCPNoRecordFound();
                              }?>
                         </div>
                         <?php $link_applied = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $this->Itemid; ?>
                         <a class="js-cp-applied-resume-show-more-wrp" href="<?php echo $link_applied;?>"><?php if(isset($applied_resume) AND (!empty($applied_resume))){  ?><span class="js-cp-applied-resume-show-more"><?php echo Text::_('Show More');?></span><?php } ?></a>
                     </div>
                  </div>         
                  <?php
                }
                ?>
              </div>
            


            </div>
        </div>
        <?php
    } else { // not allowed job posting 
        $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view the Jobseeker control panel', 0);
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
 
<?php

function checkCounts($configname, $userrole, $jscontrolpanel) {
    $print = false;
    if (isset($userrole->rolefor)) {
        if ($userrole->rolefor == 2) {
            if ($jscontrolpanel[$configname] == 1){
                $print = true;
            }
        }elseif ($userrole->rolefor == 1) {
            $visname = 'vis_js'.$configname;
            if ($jscontrolpanel[$visname] == 1){
              $print = true;
            }
        }
    }else {
        $configname = 'vis_js'.$configname;
        if ($jscontrolpanel[$configname] == 1){
            $print = true;
        }
    }
    return $print;  
}

function checkBlocks($configname, $userrole, $config, $jscontrolpanel) {
    $print = false;
    if (isset($userrole->rolefor)) {
        if ($userrole->rolefor == 2) {
            if ($jscontrolpanel[$configname] == 1)
                $print = true;
        }elseif ($userrole->rolefor == 1) {
            if ($config['employerview_js_controlpanel'] == 1){
              $visname = 'vis_'.$configname;
                if ($jscontrolpanel[$visname] == 1){
                  $print = true;
                }
            }
        }
    }else {
        $configname = 'vis_'.$configname;
        if ($jscontrolpanel[$configname] == 1)
            $print = true;
    }
    return $print;  
}

function checkLinks($name, $userrole, $config, $jscontrolpanel) {
    $print = false;
    switch ($name) {
        case 'jspackages': $visname = 'vis_jspackages';
            break;
        case 'jspurchasehistory': $visname = 'vis_jspurchasehistory';
            break;
        case 'jsmy_stats': $visname = 'vis_jsmy_stats';
            break;
        case 'jsmessages': $visname = 'vis_jsmessages';
            break;
        case 'jsjob_rss': $visname = 'vis_job_rss';
            break;
        case 'jsregister': $visname = 'vis_jsregister';
            break;

        default:$visname = 'vis_js' . $name;
            break;
    }
    if (isset($userrole->rolefor)) {
        if ($userrole->rolefor == 2) {
            if ($name == 'jsjob_rss') {
                if ($config[$name] == 1)
                    $print = true;
            }elseif ($jscontrolpanel[$name] == 1)
                $print = true;
        }elseif ($userrole->rolefor == 1) {
            if ($config['employerview_js_controlpanel'] == 1)
                if ($config[$visname] == 1)
                    $print = true;
        }
    }else {
        if ($config[$visname] == 1)
            $print = true;
    }
    return $print;
}
?>
<script type="text/javascript" language="javascript">
    function setwidth() {
        var totalwidth = document.getElementById("cp_icon_row").offsetWidth;
        var width = totalwidth - 317;
        width = (width / 3) / 3;
        document.getElementById("cp_icon_row").style.marginLeft = width + "px";
        var totalicons = document.getElementsByName("cp_icon").length;
        for (var i = 0; i < totalicons; i++)
        {
            document.getElementsByName("cp_icon")[i].style.marginLeft = width + "px";
            document.getElementsByName("cp_icon")[i].style.marginRight = width + "px";
        }
    }
    //setwidth();
    function setwidthheadline() {
        var totalwidth = document.getElementById("tp_heading").offsetWidth;
        var textwidth = document.getElementById("tp_headingtext").offsetWidth;
        var width = totalwidth - textwidth;
        width = width / 2;
        document.getElementById("left_image").style.width = width + "px";
        document.getElementById("right_image").style.width = width + "px";
    }
    //setwidthheadline();
</script>


<script type="text/javascript" 
            src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }">
</script>

<script type="text/javascript">

    google.charts.load('current', {packages: ['corechart']});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          <?php
          $line_chart = $this->cp_data['line_chart'];
            echo $line_chart['line_chart_horizontal']['title'].',';
            echo $line_chart['line_chart_horizontal']['data'];
        ?>
        ]);

        var options = {
          title: '<?php echo $line_chart["graph"]["title"];?>'
          ,pointSize: 6
          ,colors:['#1EADD8','#179650','#D98E11','#DB624C','#5F3BBB']
          ,curveType: 'function'
          ,legend: { position: 'bottom' }
          ,focusTarget: 'category'
          ,chartArea: {width:'90%',top:50}
          ,vAxis: { format: '0'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
</script>
