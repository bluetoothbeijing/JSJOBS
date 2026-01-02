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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('jquery.framework');
?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
    <?php
    $printform = 1;
    if (isset($this->userrole))
        if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { // employer
            if ($this->config['employerview_js_controlpanel'] == 1)
                $printform = true;
            else {
                echo Text::_('You are not allowed to view this page');
                $printform = 0;
            }
        }
    if ($printform == 1) {
        if (isset($this->package)) {
            ?>
            <div id="jsjobs-main-wrapper">
                <span class="jsjobs-main-page-title"><?php echo Text::_('Package Details'); ?></span>
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
                                    <a href="index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=employerpurchasehistory" title="<?php echo Text::_('Packages'); ?>">
                                        <?php echo Text::_('Packages'); ?>
                                    </a>
                                </li>
                                <li>
                                    <?php echo Text::_('Packages Details'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <div class="jsjobs-package-data">
                <span class="js-job-title">
                  <span class="js-job-package-title">
                    <?php
                    echo $this->package->title;
                    $curdate = date('Y-m-d H:i:s');
                    if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {
                        if ($this->package->discountmessage)
                            echo $this->package->discountmessage;
                    }
                    ?>
                    </span>
                    <span class="js-job-package-price">
                    <span class="stats_data_value">
                        <?php
                        if ($this->package->price != 0) {
                            $curdate = date('Y-m-d H:i:s');
                            if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {
                                if ($this->package->discounttype == 2) {
                                    $discountamount = ($this->package->price * $this->package->discount) / 100;
                                    $discountamount = $this->package->price - $discountamount;
                                    echo $this->package->symbol . $discountamount . ' [ ' . $this->package->discount . '% ' . Text::_('Discount') . ' ]';
                                } else {
                                    $discountamount = $this->package->price - $this->package->discount;
                                    echo $this->package->symbol . $discountamount . ' [ ' . Text::_('Discount') . ' : ' . $this->package->symbol . $this->package->discount . ' ]';
                                }
                            } else
                                echo $this->package->symbol . $this->package->price;
                        }else {
                            echo Text::_('Free');
                        }
                        ?>
                    </span> 
                    </span>
                </span>
                <div class="js_listing_wrapper">
                    <span class="stats_data_title"><?php echo Text::_('Resume Allowed'); ?></span>
                    <span class="stats_data_value"><?php if ($this->package->resumeallow == -1)
                echo Text::_('Unlimited');
            else
                echo $this->package->resumeallow;
            ?></span>
                    <span class="stats_data_title"><?php echo Text::_('Cover Letters Allowed'); ?></span>
                    <span class="stats_data_value"><?php if ($this->package->coverlettersallow == -1)
                echo Text::_('Unlimited');
            else
                echo $this->package->coverlettersallow;
            ?></span>
                    <span class="stats_data_title"><?php echo Text::_('Job Search'); ?></span>
                    <span class="stats_data_value"><?php if ($this->package->jobsearch == 1)
                echo Text::_('JYES');
            else
                echo Text::_('JNO');
            ?></span>
                    <span class="stats_data_title"><?php echo Text::_('Save Job Search'); ?></span>
                    <span class="stats_data_value"><?php if ($this->package->savejobsearch == 1)
                echo Text::_('JYES');
            else
                echo Text::_('JNO');
            ?></span>
                    <span class="stats_data_title"><?php echo Text::_('Apply for jobs'); ?></span>
                    <span class="stats_data_value"><?php if ($this->package->applyjobs == -1)
                echo Text::_('Unlimited');
            else
                echo $this->package->applyjobs;
                    ?></span>
                    <span class="stats_data_title"><?php echo Text::_('Expire In Days'); ?></span>
                    <span class="stats_data_value"><?php echo $this->package->packageexpireindays; ?></span>
                    <span class="jsjobs-description">       
                    <span class="stats_data_descrptn-title"><?php echo Text::_('Description'); ?></span>
                    <span class="stats_data_descrptn-value"><?php echo $this->package->description; ?></span>
                    </span> 
                    <div class="js_job_apply_button">
                     <?php $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_buynow&nav=22&gd=' . $this->package->id . '&Itemid=' . $this->Itemid; ?>
                     <a class="js_job_button" href="<?php echo $link ?>" class="pkgLink"><?php echo Text::_('Buy Now'); ?></a>
                    </div>
                </div>
                </div>
            </div>
            <?php
        }
        ?>	
        <?php
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
</div>
