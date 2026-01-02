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
    ?>
    <div id="js_jobs_main_wrapper">
    <div id="js_menu_wrapper">
        <?php
        if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
            foreach ($this->jobseekerlinks as $lnk) {
                ?>                     
                <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'controlpanel') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
            foreach ($this->employerlinks as $lnk) {
                ?>
                <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'controlpanel') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        ?>
    </div>
    <?php

    if ($this->config['offline'] == '1') {
        $this->jsjobsmessages->getSystemOfflineMsg($this->config);
    } else {
        $line_chart_text = Text::_('Statistics');
        $userrole = $this->userrole;
        if(isset($userrole->uid)){
            $userdetail = Factory::getUser($userrole->uid);
            $user_name = $userdetail->get('name');
            $user_email = $userdetail->get('email');
        }
        $config = $this->config;
        $emcontrolpanel = $this->emcontrolpanel;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 1){ // employer
                $allowed = true;
                $line_chart_text = Text::_('Jobs');
            }else
                $allowed = false;
        }else {
            if ($config['visitorview_emp_conrolpanel'] == 1)
                $allowed = true;
            else
                $allowed = false;
        } // user not logined
        if ($allowed == true) {
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
                        <?php if(isset($userrole->uid) && $userrole->rolefor == 1){ ?>
                            <div class="jsjobs-emp-intro-wrp">
                                <div class="jsjobs-emp-intro-img">
                                    <?php 
                                        if ($this->cp_data['latest_company'] != '') {
                                            $common = JSModel::getJSModel('common');
                                            $image_source = $common->getCompanyLogo($this->cp_data['latest_company']->id, $this->cp_data['latest_company']->logofilename , $this->config);
                                        } else {
                                          $image_source = Uri::root()."components/com_jsjobs/images/blank_logo.png";
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
                                if(isset($userrole->rolefor) && $userrole->rolefor == 1){
                                    $link = "index.php?option=com_users&view=profile&Itemid=" . $this->Itemid;
                                    $text = Text::_('Profile');?>
                                    <div class="jsjobs-emp-intro-btn-wrp">
                                      <a class="jsjobs-emp-intro-btn" href="<?php echo $link; ?>">
                                        <?php echo Text::_('Edit Profile');?>
                                      </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php }?>
                        <div class="jsjobs-emp-links-heading">
                          <?php echo Text::_('Short Links'); ?>        
                        </div>
                        <?php
                        $showjobblock = checkBlocks('mystuff_area', $userrole, $emcontrolpanel);
                            if($showjobblock){ ?>
                                <div class="jsjobs-emp-links-wrp">
                                    <div class="jsjobs-emp-links">
                            <?php 
                            $print = checkLinks('mycompanies', $userrole, $config, $emcontrolpanel);
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('My Companies'); ?>" href="index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img  src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/companies.png">
                                    </div>
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('My Companies'); ?></span>
                                </a>
                                <?php
                            }
                            $print = checkLinks('formcompany', $userrole, $config, $emcontrolpanel);
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('New Company'); ?>" href="index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img  src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/add-company.png">
                                    </div>
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('New Company'); ?></span>
                                </a>
                            <?php
                            }
                            $print = checkLinks('mydepartment', $userrole, $config, $emcontrolpanel);
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('My Departments'); ?>" href="index.php?option=com_jsjobs&c=department&view=department&layout=mydepartments&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/department.png">
                                    </div>
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('My Departments'); ?></span>
                                </a>
                                <?php
                            }
                            $print = checkLinks('formdepartment', $userrole, $config, $emcontrolpanel);
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('New Department'); ?>" href="index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/add-departmnet.png">
                                    </div>
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('New Department'); ?></span>
                                </a>
                                <?php
                            }
                            $print = checkLinks('userdata_employer', $userrole, $config, $emcontrolpanel);
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('User Data'); ?>" href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=adderasedatarequest&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img  src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/userdata.png">
                                    </div>
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('User Data'); ?></span>
                                </a>
                            <?php }
                            if(isset($userrole->uid)){
                                $print = checkLinks('resumesearch', $userrole, $config, $emcontrolpanel);
                            } else {
                                $print = checkLinks('resumebycategory', $userrole, $config, $emcontrolpanel);
                            }
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('Resume By Category'); ?>" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=resumebycategory&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img  src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/categories.png">
                                    </div> 
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('Resume By Category'); ?></span>
                                </a>
                            <?php }
                            $print = checkLinks('my_resumesearches', $userrole, $config, $emcontrolpanel);
                            if ($print) {
                                ?>
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_('Resume Save Search'); ?>" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=my_resumesearches&Itemid=<?php echo $this->Itemid; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img  src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/save-resume.png">
                                    </div> 
                                    <span class="jsjobs-new-company-title"><?php echo Text::_('Resume Save Search'); ?></span>
                                </a>
                            <?php } ?>
                            <!--  -->
                            <?php 
                            $showstatsblock = checkBlocks('mystats_area', $userrole, $emcontrolpanel);
                            if($showstatsblock){ ?>
                                <?php
                                $print = checkLinks('my_stats', $userrole, $config, $emcontrolpanel);
                                if ($print) {
                                    ?>
                                    <a class="jsjobs-emp-menu" title="<?php echo Text::_('My Stats'); ?>" href="index.php?option=com_jsjobs&c=employer&view=employer&layout=my_stats&Itemid=<?php echo $this->Itemid; ?>">
                                        <div class="jsjobs-new-company-icon">
                                            <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/Stats.png">
                                        </div>
                                        <span class="jsjobs-new-company-title"><?php echo Text::_('My Stats'); ?></span>
                                    </a>
                                <?php }
                            }
                                ?>
                                <?php
                                $print = checkLinks('packages', $userrole, $config, $emcontrolpanel);
                                if ($print) {
                                    ?>
                                    <a class="jsjobs-emp-menu" title="<?php echo Text::_('Packages'); ?>" href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=<?php echo $this->Itemid; ?>">
                                        <div class="jsjobs-new-company-icon">
                                            <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/package.png">
                                        </div>
                                        <span class="jsjobs-new-company-title"><?php echo Text::_('Packages'); ?></span>
                                    </a>
                                <?php
                                }
                                $print = checkLinks('purchasehistory', $userrole, $config, $emcontrolpanel);
                                if ($print) {
                                    ?>
                                    <a class="jsjobs-emp-menu" title="<?php echo Text::_('Purchase History'); ?>" href="index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=employerpurchasehistory&Itemid=<?php echo $this->Itemid; ?>">
                                        <div class="jsjobs-new-company-icon">
                                            <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/log-history.png">
                                        </div>
                                        <span class="jsjobs-new-company-title"><?php echo Text::_('Purchase History'); ?></span>
                                    </a>
                                <?php }
                                ?>
                            <!--  -->
                            <?php
                            if (isset($userrole->rolefor) && $userrole->rolefor == 1) {//jobseeker
                                $link = "index.php?option=com_users&view=profile&Itemid=" . $this->Itemid;
                                $text = Text::_('Profile');
                                $icon = "profile.png";
                            } else {
                                //$link = "index.php?option=com_jsjobs&c=common&view=common&layout=userregister&userrole=3&Itemid=" . $this->Itemid;
                                $text = Text::_('Register');
                                $icon = "register.png";
                                $default_register_from = $this->getJSModel('configurations')->getConfigValue('default_register_from');
                                if($default_register_from == 1){ // JS Jobs registration
                                    $link = "index.php?option=com_jsjobs&c=common&view=common&layout=userregister&userrole=3&Itemid=" . $this->Itemid;
                                }elseif($default_register_from == 2){ // Joomla registration
                                    $link = 'index.php?option=com_users&view=registration&Itemid=' . $this->Itemid;
                                }else{ // custom link
                                    $register_custom_link = $this->getJSModel('configurations')->getConfigValue('register_custom_link');
                                    $link = $register_custom_link;
                                    if (!preg_match("~^(?:f|ht)tps?://~i", $link)) { // If not exist then add http 
                                          $link = "http://" . $link; 
                                    }                                         
                                }
                            }
                            
                            $print = checkCounts('emploginlogout', $userrole, $emcontrolpanel);
                            if ($print) {
                                $redirectUrl = Route::_('index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid=' . $this->Itemid ,false);
                                if (isset($userrole->rolefor)) {//jobseeker
                                    $redirect = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
                                    $link = "index.php?option=com_jsjobs&task=jsjobs.logout&return=".$redirect."&Itemid=" . $this->Itemid;
                                    $text = Text::_('Logout');
                                    $icon = "login.png";
                                } else {
                                    $default_login_from = $this->getJSModel('configurations')->getConfigValue('default_login_from');
                                    $redirect = JSModel::getJSModel('common')->b64ForEncode($redirectUrl);
                                    $redirect = '&amp;return=' . $redirect;
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
                                <a class="jsjobs-emp-menu" title="<?php echo Text::_($text); ?>" href="<?php echo $link; ?>">
                                    <div class="jsjobs-new-company-icon">
                                        <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/<?php echo $icon; ?>">
                                    </div>
                                    <span class="jsjobs-new-company-title"><?php echo $text; ?></span>
                                </a>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div id="jsjobs-emp-cp-wrapper">
                    <div class="jsjobs-cp-toprow-employer">
                       <?php
                        $print = checkCounts('myjobs_counts', $userrole, $emcontrolpanel);
                        if ($print) {
                        ?>
                        <div class="js-menu-wrap-employer">
                            <a class="menu_style-employer color3">
                              <img class="jsjobs-img-employer" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/jobs.png"> 
                              <span class="jsjobs-title-employer">
                                <div class="bold-text">
                                    <?php
                                        if(isset($this->cp_data['total_jobs']))
                                            { echo $this->cp_data['total_jobs'];}
                                        else{ echo '0';}
                                    ?>
                                </div>
                                <div class="nonbold-text"><?php echo Text::_('My Jobs'); ?></div>
                              </span>
                            </a>
                            <div class="box-footer">
                              <a href="index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>">
                                <span class="box-footer-left">
                                  <?php echo Text::_('View Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                  <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/1.png">
                                </span>
                              </a>
                            </div>
                        </div>
                        <?php
                        }
                        $print = checkCounts('appliedresume_counts', $userrole, $emcontrolpanel);
                        if ($print) {
                          ?>
                         <div class="js-menu-wrap-employer">
                            <a class="menu_style-employer color1">
                              <img src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/applied-resume.png">
                              <span class="jsjobs-title-employer">
                                <div class="bold-text">
                                    <?php
                                        if(isset($this->cp_data['total_appliedresumes']))
                                            { echo $this->cp_data['total_appliedresumes'];}
                                        else{ echo '0';}
                                    ?>
                                </div>
                                <div class="nonbold-text"><?php echo Text::_('Applied Resume'); ?></div>
                              </span>
                            </a>
                            <div class="box-footer">
                              <a href="index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>">
                                <span class="box-footer-left">
                                  <?php echo Text::_('View Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                  <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/2.png">
                                </span>
                              </a>
                            </div>
                         </div>
                          <?php
                        }
                        $print = checkCounts('mycompanies_counts', $userrole, $emcontrolpanel);
                        if ($print) {
                        ?>
                        <div class="js-menu-wrap-employer">
                          <a class="menu_style-employer color2">
                            <img class="jsjobs-img-employer" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/companies.png"> 
                            <span class="jsjobs-title-employer">
                                <div class="bold-text">
                                    <?php
                                            if(isset($this->cp_data['total_companies']))
                                                { echo $this->cp_data['total_companies'];}
                                            else{ echo '0';}
                                    ?>
                                </div>
                              <div class="nonbold-text"><?php echo Text::_('My Company'); ?></div>
                            </span>
                          </a>
                          <div class="box-footer">
                            <a href="index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>">
                              <span class="box-footer-left">
                                <?php echo Text::_('View Details'); ?>
                              </span>
                              <span class="box-footer-right">
                                <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/3.png">
                              </span>
                            </a>
                          </div>
                        </div>
                         <?php
                        }
                        $print = checkCounts('myresumesearches_counts', $userrole, $emcontrolpanel);
                        if ($print) {
                        ?>
                        <div class="js-menu-wrap-employer">
                            <a class="menu_style-employer color4">
                                <img class="jsjobs-img-employer" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/resme-search.png"> 
                                <span class="jsjobs-title-employer">
                                    <div class="bold-text">
                                        <?php
                                        if(isset($this->cp_data['total_save_search']))
                                            { echo $this->cp_data['total_save_search'];}
                                        else{ echo '0';}
                                        ?>
                                    </div>
                                  <div class="nonbold-text"><?php echo Text::_('Resume Save Search'); ?></div>
                                </span>
                            </a>
                            <div class="box-footer">
                              <a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=my_resumesearches&Itemid=<?php echo $this->Itemid; ?>">
                                <span class="box-footer-left">
                                  <?php echo Text::_('View Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                  <img class="arrows" src="<?php echo Uri::root();?>components/com_jsjobs/images/controlpanel/employer/top-box/4.png">
                                </span>
                              </a>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="jsjobs-cp-graph-wrap">
                        <?php 
                        $showjobgraph = checkBlocks('jobs_graph', $userrole, $emcontrolpanel);
                        if($showjobgraph){ ?>
                            <div class="js-col-xs-12 js-col-md-6 js-graph-left">
                                <div class="jsjobs-graph-wrp">
                                    <span class="jsjobs-graph-title">
                                        <span class="jsjobs-title">
                                            <?php echo $line_chart_text; ?>
                                        </span>
                                    </span>
                                    <div id="curve_chart" class="js_emp_chart1">
                                        <div id="no_message" class="linechart"><?php echo Text::_('No data'); ?></div>
                                    </div>
                                </div>
                            </div> 
                            <?php
                        } ?>                            
                    </div>

                    <div class="jsjobs-jobs-resume-panel">
                    <?php
                        $shownewestresume_box = checkBlocks('box_newestresume', $userrole, $emcontrolpanel);
                    if($shownewestresume_box){ 
                        ?>
                        <div class="js-cp-applied-resume">
                            <div class="js-cp-wrap-resume-jobs">
                                <span class="js-cp-applied-resume-title1"><?php echo Text::_('Newest Resume');?></span>
                                <div class="js-cp-resume-wrap">
                                    <?php 
                                    $resumes = $this->cp_data['newest_resume'];
                                     if(isset($resumes) AND (!empty($resumes))){ 
                                    //echo('<pre>'); print_r($resumes); echo "</pre>";
                                    foreach ($resumes as $resume) { 
                                        $resumealiasid = $this->getJSModel('common')->removeSpecialCharacter($resume->resumealiasid);
                                        $link_viewresume = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&rd='. $resumealiasid . '&Itemid=' . $this->Itemid;
                                        $imgsrc = Uri::root()."components/com_jsjobs/images/user_b.png";
                                        if (isset($resume->photo) && $resume->photo != "") {
                                            if ($this->isjobsharing) {
                                                $imgsrc = $resume->photo;
                                            } else {
                                                $imgsrc = Uri::root().$this->config['data_directory'] . "/data/jobseeker/resume_" . $resume->appid . '/photo/' . $resume->photo;
                                            }
                                        }
                                        ?>
                                        <div id="jsjsjobs-row_wrapper">
                                            <div class="js-cp-applied-resume">
                                                <a class="img-anchor" href="<?php echo $link_viewresume;?>">
                                                <div class="js-cp-image-area">
                                                    <img class="js-cp-imge-user" src="<?php echo $imgsrc;?>">
                                                </div>
                                                </a>
                                                <div class="js-cp-content-area">
                                                    <div class="js-cp-company-title">
                                                        <div class="js-cp-company-status-wrp">
                                                            <?php
                                                            if($resume->jobtypetitle){
                                                                echo '<span class="jsjobs-status">'.Text::_($resume->jobtypetitle).'</span>';
                                                            }
                                                            ?>
                                                        </div>
                                                        <a href="<?php echo $link_viewresume;?>"><?php echo $resume->first_name.' '.$resume->last_name; ?></a>
                                                    </div>
                                                    <div class="js-cp-company-location">
                                                        <?php echo $resume->application_title; ?>
                                                    </div>
                                                    <div class="js-cp-company-email-address">
                                                        <span class="jsjobs-title"><?php echo Text::_('Experience');?> : </span>
                                                        <span class="jsjobs-value">
                                                            <?php
                                                            if (isset($resume->experience_title)) {
                                                                echo getJSJobsPHPFunctionsClass()->jsjobs_htmlspecialchars($resume->experience_title);
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <div class="js-cp-company-category">
                                                        <span class="jsjobs-title"><?php echo Text::_('Category');?> : </span>
                                                        <span class="jsjobs-value">
                                                            <?php
                                                            if (isset($resume->cat_title)) {
                                                                echo getJSJobsPHPFunctionsClass()->jsjobs_htmlspecialchars($resume->cat_title);
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="js-cp-applied-resume-lower">
                                                <span class="jsjobs-loction">
                                                    <?php
                                                    if (isset($resume->location)) {
                                                        echo getJSJobsPHPFunctionsClass()->jsjobs_htmlspecialchars($resume->location);
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php
                                    } }else{
                                            $this->jsjobsmessages->getCPNoRecordFound();
                                        }     
                                    ?>
                                </div>
                                <a class="js-cp-applied-resume-show-more2-wrp" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_searchresults&jsfrom=cpbox&Itemid=<?php echo $this->Itemid;?>"><?php if(isset($resumes) AND (!empty($resumes))){  ?><span class="js-cp-applied-resume-show-more2"><?php echo Text::_('Show More'); ?></span><?php } ?></a>
                            </div>    
                        </div>
                        <?php
                    } ?>
                    </div>

                    
                </div>
            </div>

            <?php

            if ($emcontrolpanel['empexpire_package_message'] == 1) {
                $message = '';
                if (!empty($this->packagedetail[0]->packageexpiredays)) {
                    $days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
                    if ($days == 1)
                        $days = $days . ' ' . Text::_('Day');
                    else
                        $days = $days . ' ' . Text::_('Days');
                    $message = "<strong><font color='red'>" . Text::_('Your Package') . ' &quot;' . $this->packagedetail[0]->packagetitle . '&quot; ' . Text::_('Has Expired') . ' ' . $days . ' ' . Text::_('Ago') . ' <a href="index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=$this->Itemid">' . Text::_('Employer Packages') . "</a></font></strong>";
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
            <?php
        } else { // not allowed job posting 
            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view the employer control panel', 0);
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
    <?php

    function checkCounts($configname, $userrole, $emcontrolpanel) {
    $print = false;
        $print = false;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 1) {
                if ($emcontrolpanel[$configname] == 1)
                    $print = true;
            }
        }else {
            $configname = 'vis_em'.$configname;
            if ($emcontrolpanel[$configname] == 1)
                $print = true;
        }
        return $print;  
    }

    function checkBlocks($configname, $userrole, $emcontrolpanel) {
        $print = false;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 1) {
                if ($emcontrolpanel[$configname] == 1)
                    $print = true;
            }
        }else {
            $configname = 'vis_'.$configname;
            if ($emcontrolpanel[$configname] == 1)
                $print = true;
        }
        return $print;
    }

    function checkLinks($name, $userrole, $config, $emcontrolpanel) {
        $print = false;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 1) {
                if ($name == 'empresume_rss') {
                    if ($config[$name] == 1)
                        $print = true;
                }elseif ($emcontrolpanel[$name] == 1)
                    $print = true;
            }
        }else {
            if ($name == 'empmessages')
                $name = 'vis_emmessages';
            elseif ($name == 'empresume_rss')
                $name = 'vis_resume_rss';
            else
                $name = 'vis_em' . $name;

            if ($config[$name] == 1)
                $print = true;
        }
        return $print;
    }
    ?>





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
        <?php if($this->cp_data['line_chart']['line_chart_horizontal']['title'] != null){ ?>
                google.setOnLoadCallback(drawChart1);
                jQuery('div#no_message.linechart').show();
        <?php } ?>
        
        function drawChart1() {
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
        <?php if(!empty($this->cp_data['pie_chart']['pie_chart_horizontal']['data'])){ ?>
        google.setOnLoadCallback(drawChart2);
        <?php } ?>

        function drawChart2() {
            var data = google.visualization.arrayToDataTable([
                ['<?php echo Text::_("Applied Resume"); ?>', '<?php echo Text::_("Applied resume by experiences"); ?>'],
                <?php
                    if(isset($this->cp_data['pie_chart']) && !empty($this->cp_data['pie_chart'])){
                        $pie_chart = $this->cp_data['pie_chart'];
                        echo $pie_chart['pie_chart_horizontal']['data'];
                    }
                    ?>
                        ]);
            var options = {
                title: "<?php if(isset($this->cp_data['pie_chart']) && !empty($this->cp_data['pie_chart'])) echo $pie_chart["pie_chart_horizontal"]["title"];?>",
                pieHole: 0.4,
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('js_donut_chart'));
            chart.draw(data, options);
        }
    </script>
