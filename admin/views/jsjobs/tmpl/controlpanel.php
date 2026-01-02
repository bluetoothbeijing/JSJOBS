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
use Joomla\CMS\Filter\OutputFilter;



Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');

?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawStackChartHorizontal);
    google.setOnLoadCallback(drawStackChartHorizontal);
    function drawStackChartHorizontal() {
      var data = google.visualization.arrayToDataTable([
        <?php
            echo $this->jobs_cp_data['stack_chart_horizontal']['title'].',';
            echo $this->jobs_cp_data['stack_chart_horizontal']['data'];
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
          curveType: 'function',
        height:400,
        legend: { position: 'top', maxLines: 3 },
        pointSize: 4,
        isStacked: true,
        focusTarget: 'category',
        chartArea: {width:'90%',top:50}
      };
      var chart = new google.visualization.LineChart(document.getElementById("stack_chart_horizontal"));
      chart.draw(view, options);
    }

    google.charts.setOnLoadCallback(drawTodayJobsChart);
    google.setOnLoadCallback(drawTodayJobsChart);
    function drawTodayJobsChart() {
      var data = google.visualization.arrayToDataTable([
        <?php
            // echo $this->jobs_cp_data['today_job_chart']['title'].',';
            // echo $this->jobs_cp_data['today_job_chart']['data'];
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        height:121,
        chartArea: { width: '70%', left: 30 },
        legend: { position: "right" },
        hAxis: { textPosition: 'none' },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("today_job_chart"));
      chart.draw(view, options);
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
                <?php echo Text::_('Dashboard'); ?>
            </h1>
            <a href="index.php?option=com_jsjobs&c=job&view=job&layout=formjob" class="jsjobs-add-link orange-bg button" title="<?php echo Text::_('Add New Job'); ?>">
                <img alt="<?php echo Text::_('Add New Job'); ?>" src="components/com_jsjobs/include/images/icon/plus.png"/>
                <?php echo Text::_('Add New Job'); ?>
            </a>
            <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs" class="jsjobs-add-link button" title="<?php echo Text::_('All Jobs'); ?>">
                <img class="add-job" alt="<?php echo Text::_('All Jobs'); ?>" src="components/com_jsjobs/include/images/icon/job-icon-white.png"/>
                <?php echo Text::_('All Jobs'); ?>
            </a>
        </div>
        <div id="jsjobs-admin-wrapper" class="p0 bg-n bs-n">
            <div class="js-cp-cnt-sec">
                <div class="count1">
                    <div class="box">
                        <div class="box1">
                            <img class="left-icon job" src="components/com_jsjobs/include/images/icon/job.png">
                            <div class="text">
                                <div class="bold-text"><?php echo $this->jobs_cp_data['jobs']; ?></div>
                                <div class="nonbold-text"><?php echo Text::_('Posted').' '.Text::_('Jobs'); ?></div>   
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs">
                                <span class="box-footer-left">
                                    <?php echo Text::_('View').' '.Text::_('Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                    <img class="arrows" src="components/com_jsjobs/include/images/icon/1.png">
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box2">
                            <img class="left-icon appliedresume" src="components/com_jsjobs/include/images/icon/job-applied.png">
                            <div class="text">
                                <div class="bold-text"><?php echo $this->jobs_cp_data['appliedjobs']; ?></div>
                                <div class="nonbold-text"><?php echo Text::_('Applied Resumes'); ?></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps">
                                <span class="box-footer-left">
                                    <?php echo Text::_('View').' '.Text::_('Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                    <img class="arrows" src="components/com_jsjobs/include/images/icon/2.png">
                                </span>
                            </a>
                        </div>  
                    </div>
                    <div class="box">
                        <div class="box3">
                            <img class="left-icon company" src="components/com_jsjobs/include/images/icon/companies.png">
                            <div class="text">
                                <div class="bold-text"><?php echo $this->jobs_cp_data['companies']; ?></div>
                                <div class="nonbold-text"><?php echo Text::_('My Companies'); ?></div>   
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="index.php?option=com_jsjobs&c=company&view=company&layout=companies">
                                <span class="box-footer-left">
                                    <?php echo Text::_('View').' '.Text::_('Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                    <img class="arrows" src="components/com_jsjobs/include/images/icon/3.png">
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box4">
                            <img class="left-icon resume" src="components/com_jsjobs/include/images/icon/reume.png">
                            <div class="text">
                                <div class="bold-text"><?php echo $this->jobs_cp_data['resumes']; ?></div>
                                <div class="nonbold-text"><?php echo Text::_('Resumes'); ?></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps">
                                <span class="box-footer-left">
                                    <?php echo Text::_('View').' '.Text::_('Details'); ?>
                                </span>
                                <span class="box-footer-right">
                                    <img class="arrows" src="components/com_jsjobs/include/images/icon/4.png">
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="js-cp-cnt-left">
                    <div class="js-cp-cnt">
                        <div class="js-cp-cnt-title">
                            <span class="js-cp-cnt-title-txt">
                                <?php echo Text::_('Statistics'); ?>
                                
                            </span>
                        </div>
                        <div id="js-pm-grapharea">
                            <div id="stack_chart_horizontal" style="width:100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="js-cp-cnt-right">
                    <div class="js-cp-cnt">
                        <div class="js-cp-cnt-title">
                            <span class="js-cp-cnt-title-txt">
                                <?php echo Text::_('Short Links'); ?>
                            </span>
                        </div>
                        <div id="js-wrapper-menus">
                            <a title="<?php echo Text::_('Configurations'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations"> <img alt="<?php echo Text::_('Configurations'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/configration.png"/><div class="jsmenu-text"><?php echo Text::_('Configurations'); ?></div>
                                <div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/orange.png"/></div></a>
                            <a title="<?php echo Text::_('Companies'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=company&view=company&layout=companies"><img alt="<?php echo Text::_('Companies'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/company.png"/><div class="jsmenu-text"><?php echo Text::_('Companies'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/green.png"/></div></a>
                            <a title="<?php echo Text::_('Departments'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=department&view=department&layout=departments"><img alt="<?php echo Text::_('Departments'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/departments.png"/><div class="jsmenu-text"><?php echo Text::_('Departments'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/orange.png"/></div></a>
                            <a title="<?php echo Text::_('Jobs'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs"><img alt="<?php echo Text::_('Jobs'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/jobs.png"/><div class="jsmenu-text"><?php echo Text::_('Jobs'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/purple.png"/></div></a>
                            <a title="<?php echo Text::_('Resume'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps"><img alt="<?php echo Text::_('Resume'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/resume.png"/><div class="jsmenu-text"><?php echo Text::_('Resume'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/green.png"/></div></a>
                            <a title="<?php echo Text::_('Packages'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages"><img alt="<?php echo Text::_('Packages'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/packages.png"/><div class="jsmenu-text"><?php echo Text::_('Packages'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/purple.png"/></div></a>
                            <a title="<?php echo Text::_('Category'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=category&view=category&layout=categories"><img alt="<?php echo Text::_('category'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/category.png"/><div class="jsmenu-text"><?php echo Text::_('Categories'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/dark-blue.png"/></div></a>
                            <a title="<?php echo Text::_('User Role'); ?>" class="js-admin-menu-link" href="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users"><img alt="<?php echo Text::_('User Role'); ?>" class="jsmenu-img" src="components/com_jsjobs/include/images/left_menu_icons/role.png"/><div class="jsmenu-text"><?php echo Text::_('User Role'); ?></div><div class="js-mnu-arrowicon"><img src="components/com_jsjobs/include/images/icon/arrows/purple.png"/></div></a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="js-cp-cnt-sec">
                <div class="js-cp-baner-left">
                    <div class="js-cp-baner">
                        <div class="js-cp-baner-cnt">
                            <div class="js-cp-banner-tit-bold">
                                <?php echo Text::_('All Jobs Listing'); ?>
                            </div>
                            <div class="js-cp-banner-desc">
                                <?php echo Text::_('Copy Job').' , '.Text::_('Delete').' '.Text::_('All Jobs'); ?>
                            </div>
                            <div class="js-cp-banner-btn-wrp">
                                <a href="index.php?option=com_jsjobs&c=job&view=job&layout=formjob" class="js-cp-banner-btn purple-bg">
                                    <img alt="All Tickets" src="components/com_jsjobs/include/images/icon/plus.png">
                                    <?php echo Text::_('Add New').' '.Text::_('Job'); ?>
                                </a>
                                <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs" class="js-cp-banner-btn">
                                    <img class="add-job" alt="All Tickets" src="components/com_jsjobs/include/images/icon/job-icon-white.png">
                                    <?php echo Text::_('Show Job Listing'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="js-cp-baner-center">
                    <div class="js-cp-baner">
                        <a class="Configuration-main-wrp" href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations">
                            <div class="Configuration-upper">
                                <img alt="star" src="components/com_jsjobs/include/images/icon/config-1.png">
                            </div>
                            <div class="Configuration-lower">
                                <?php echo Text::_("Configurations"); ?>
                            </div>
                        </a>
                        <img class="Configuration-background" alt="star" src="components/com_jsjobs/include/images/icon/config-2.png">
                    </div>
                </div>
                <div class="js-cp-baner-right">
                    <div class="js-cp-baner">
                        <a class="Configuration-main-wrp" href="index.php?option=com_jsjobs&c=department&view=department&layout=departments">
                            <div class="Configuration-upper">
                                <img alt="star" src="components/com_jsjobs/include/images/icon/department-1.png">
                            </div>
                            <div class="Configuration-lower">
                                <?php echo Text::_("Departments"); ?>
                            </div>
                        </a>
                        <img class="Configuration-background" alt="star" src="components/com_jsjobs/include/images/icon/department-2.png">
                    </div>
                </div>
            </div>
            <a id="jsjobs-joomla-freeprobanner" target="_blank" href="https://www.joomsky.com/products/js-jobs-pro.html">
                <img class="banner" src="components/com_jsjobs/include/images/pro-banner.png">
            </a>
            <!-- latest jobs start -->
            <div class="newestjobs">
                <div class="js-cp-cnt-title">
                    <span class="js-cp-cnt-title-txt"><?php echo Text::_('Newest Jobs'); ?></span>
                    <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs" class="js-cp-cnt-title-btn" title="View All Tickets">
                        <?php echo Text::_('View All Jobs')?>
                    </a>
                </div>
                <div class="newestjobtable">
                    <?php if($this->jobs_cp_data['latestjobs'] && !empty($this->jobs_cp_data['latestjobs'])){ ?>
                        <?php foreach ($this->jobs_cp_data['latestjobs'] AS $tj) { ?>
                            <div class="js-cp-job-list">
                                <div class="js-cp-job-list-header">
                                    <div class="js-cp-job-list-left">
                                        <div class="js-cp-job-image">
                                            <?php
                                            $common = $this->getJSModel('common');
                                            $path = $common->getCompanyLogo($tj->companyid, $tj->pic);
                                            ?>
                                            <img class="myfilelogoimg" src="<?php echo $path;?>"/>
                                        </div>
                                        <div class="js-cp-job-cnt">
                                            <div class="js-cp-job-info company-name">
                                                <?php echo Text::_($tj->name); ?>
                                            </div>
                                            <div class="js-cp-job-info subject">
                                                <a title="Subject" href="index.php?option=com_jsjobs&task=job.edit&cid[]=<?php echo $tj->id; ?>">
                                                    <?php echo Text::_($tj->title); ?>
                                                </a>
                                            </div>
                                            <div class="js-cp-job-info name">
                                                <span class="js-cp-job-info-label">
                                                   <?php echo Text::_($tj->cat_title); ?>
                                                </span>
                                                <span class="js-cp-job-info-label">
                                                    <?php echo Text::_($this->getJSModel('city')->getLocationDataForView($tj->city)); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="js-cp-job-list-right">
                                            <span class="js-cp-job-type">
                                                <?php echo Text::_($tj->jobtype_title); ?>
                                            </span>
                                            <div class="js-cp-job-salary">
                                                <?php 
                                                    $salaryrange = '';
                                                    if ($tj->salaryfrom)
                                                        $salaryrange =  $tj->salaryfrom;
                                                    if ($tj->salaryto)
                                                        $salaryrange .=  ' - ' . $tj->salaryto;
                                                    if($this->config['currency_align'] == 1){
                                                        $salaryrange = $tj->symbol . ' '.$salaryrange;
                                                    }elseif($this->config['currency_align'] == 2){
                                                        $salaryrange .= ' '.$tj->symbol;
                                                    }
                                                    echo $salaryrange.'/';
                                                    if ($tj->salarytype){
                                                    ?>
                                                    <span class="per-month"><?php echo Text::_($tj->salarytype);?></span>

                                                    <?php 
                                                    }
                                                ?>
                                            </div>
                                            <div class="js-cp-job-salary">
                                                <span class="js-cp-job-info-label">
                                                    <?php echo Text::_($tj->created); ?>
                                                </span>
                                            </div>
                                    </div>
                                    
                                </div>
                                <div class="js-cp-job-list-footer">
                                    <span class="js-cp-job-list-footer-btn-wrp">
                                        <?php $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=job.edit&cid[]=' . $tj->id); ?>
                                        <a class="js-cp-job-list-footer-btn"  href="<?php echo $link; ?>"><?php echo Text::_('Edit Job'); ?></a>
                                    </span>
                                    <span class="js-cp-job-list-footer-btn-wrp">
                                        <a class="js-cp-job-list-footer-btn" href="index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=jobappliedresume&oi=<?php echo $tj->id; ?>">
                                            <?php echo Text::_('Resume'); ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    <?php }else{ ?>
                        <div class="js-cp-addon-empty-data">
                            <div class="js-empty-data-upper-portion">
                                <img src="components/com_jsjobs/include/images/icon/no-record.png" alt="">
                            </div>
                            <div class="js-empty-data-lower-portion">
                                <?php echo Text::_("No Record Found"); ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
            <!-- latest jobs edn -->

            <div class="js-cp-cnt-staff-sec">
                <div class="js-cp-staff-baner-left">
                    <div class="js-cp-baner">
                        <a class="Configuration-main-wrp" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=proversion">
                            <div class="Configuration-upper">
                                <img alt="star" src="components/com_jsjobs/include/images/icon/report-1.png">
                            </div>
                            <div class="Configuration-lower">
                                <?php echo Text::_("Reports"); ?>
                            </div>
                        </a>
                        <img class="Configuration-background" alt="star" src="components/com_jsjobs/include/images/icon/report-2.png">
                    </div>
                </div>
                <div class="js-cp-staff-baner-center">
                    <div class="js-cp-baner">
                        <a class="Configuration-main-wrp" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=translation">
                            <div class="Configuration-upper">
                                <img alt="star" src="components/com_jsjobs/include/images/icon/translation.png">
                            </div>
                            <div class="Configuration-lower">
                                <?php echo Text::_("Translations"); ?>
                            </div>
                        </a>
                        <img class="Configuration-background" alt="star" src="components/com_jsjobs/include/images/icon/translation-2.png">
                    </div>
                </div>
                <div class="js-cp-staff-baner-right">
                    <div class="js-cp-baner">
                        <div class="js-cp-baner-cnt">
                            <div class="js-cp-banner-tit-bold">
                                <?php echo Text::_('Users'); ?>
                            </div>
                            <div class="js-cp-banner-desc">
                                <?php echo Text::_('Here you can view and change role of users'); ?>
                            </div>
                            <div class="js-cp-banner-btn-wrp">
                                <a href="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users" class="js-cp-banner-btn purple-bg">
                                    <img alt="All Tickets" src="components/com_jsjobs/include/images/icon/all-staff.png">
                                    <?php echo Text::_('Users Listing'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- latest resume end -->
            <!-- Ticket History End -->
            <?php /*
            <div id="jsreview-banner">
                <div class="review">
                    <div class="upper">
                        <span class="simple-text">
                            <?php echo Text::_("Your positive feedback help us to improve JS Jobs").'.'; ?>
                        </span>
                        <a class="review-link" href="https://extensions.joomla.org/extension/js-support-ticket/" target="_blank" title="WP Extension Directory">
                            <img alt="star" src="components/com_jsjobs/include/images/icon/star.png"><?php echo Text::_("Joomla Extension Directory"); ?>
                        </a>
                    </div>
                    <div class="lower">
                        <span class="simple-text"><?php echo Text::_("Spread the word"); ?>:&nbsp;</span>
                        <a class="rev-soc-link" href="https://www.facebook.com/joomsky">
                            <img alt="fb" src="components/com_jsjobs/include/images/icon/fb.png">
                        </a>
                        <a class="rev-soc-link" href="https://twitter.com/joomsky">
                            <img alt="twitter" src="components/com_jsjobs/include/images/icon/twitter.png">
                        </a>
                    </div>
                </div>
            </div>
            */?>
            <!--  -->
    </div>
    </div>
</div>  
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('div.resume').animate({left: '-100%'});
        jQuery('div.companies span.img img').click(function (e) {
            jQuery('div.companies').animate({left: '-100%'});
            jQuery('div.resume').animate({left: '0%'});
        });
        jQuery('div.resume span.img img').click(function (e) {
            jQuery('div.resume').animate({left: '-100%'});
            jQuery('div.companies').animate({left: '0%'});
        });
        jQuery('div.jobs').animate({right: '-100%'});
        jQuery('div.jobs span.img img').click(function (e) {
            jQuery('div.jobs').animate({right: '-100%'});
            jQuery('div.appliedjobs').animate({right: '0%'});
        });
        jQuery('div.appliedjobs span.img img').click(function (e) {
            jQuery('div.appliedjobs').animate({right: '-100%'});
            jQuery('div.jobs').animate({right: '0%'});
        });
        jQuery("span.dashboard-icon").find('span.download').hover(function(){
            jQuery(this).find('span').toggle("slide");
        },function(){
            jQuery(this).find('span').toggle("slide");
        });
    });
</script>
