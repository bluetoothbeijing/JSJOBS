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
    if (isset($this->userrole->rolefor)) {
        if ($this->userrole->rolefor == 1) // employer
            $allowed = true;
        else
            $allowed = false;
    }else {
        if ($this->config['visitorview_emp_viewpackage'] == 1)
            $allowed = true;
        else
            $allowed = false;
    } // user not logined
    if ($allowed == true) {
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
                                    <a href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo Text::_('Packages'); ?>">
                                        <?php echo Text::_('Packages'); ?>
                                    </a>
                                </li>
                                <li>
                                    <?php echo Text::_('Package Detail'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <div class="jsjobs-package-data">
                <span class="jsjobs-package-title">
                   <span class="jsjobs-package-name">
                        <?php
                        echo '[ ' . $this->package->title;
                        $curdate = date('Y-m-d H:i:s');
                        if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {
                            if ($this->package->discountmessage)
                                echo $this->package->discountmessage;
                        }
                        echo '] ' . Text::_('Package Details');
                        ?>
                    </span>
                    <span class="jsjobs-package-price-de">
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
                <div class="jsjobs-package-listing-wrapper">
                  <div class="jsjobs-listing-datawrap-details">
                       <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Companies Allowed'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->companiesallow == -1)
                                        echo Text::_('Unlimited');
                                    else
                                        echo $this->package->companiesallow;
                                    ?>
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Jobs Allowed'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->jobsallow == -1)
                                        echo Text::_('Unlimited');
                                    else
                                        echo $this->package->jobsallow;
                                    ?>
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('View Resume In Details'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->viewresumeindetails == -1)
                                        echo Text::_('Unlimited');
                                    else
                                        echo $this->package->viewresumeindetails;
                                    ?>
                               </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Resume Search'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->resumesearch == 1)
                                         $imgscr  = Uri::root().'components/com_jsjobs/images/publish-icon.png' ;
                                               else
                                            $imgscr  = Uri::root().'components/com_jsjobs/images/reject-s.png' ;
                                        ?> 
                                            <img src="<?php echo $imgscr ;?>"> 
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Featured Companies'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->featuredcompaines == -1)
                                            echo Text::_('Unlimited');
                                        else
                                            echo $this->package->featuredcompaines;
                                    ?>
                                     <span class="stats_data_values">( <?php echo $this->package->featuredcompaniesexpireindays.' '.Text::_('Days'); ?> )</span>
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Gold Companies'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->goldcompanies == -1)
                                        echo Text::_('Unlimited');
                                    else
                                        echo $this->package->goldcompanies;
                                    ?>
                                    <span class="stats_data_values">( <?php echo $this->package->goldcompaniesexpireindays.' '.Text::_('Days'); ?> )</span>
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Gold Jobs'); ?>:</span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->goldjobs == -1)
                                        echo Text::_('Unlimited');
                                    else
                                        echo $this->package->goldjobs;
                                    ?>
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-package-data-detail">
                            <span class="jsjobs-package-values"> 
                                <span class="stats_data_title"><?php echo Text::_('Resume Save Search'); ?></span>
                                <span class="stats_data_value">
                                    <?php if ($this->package->saveresumesearch == 1)
                                        $imgscr  = Uri::root().'components/com_jsjobs/images/publish-icon.png' ;
                                               else
                                            $imgscr  = Uri::root().'components/com_jsjobs/images/reject-s.png' ;
                                        ?> 
                                            <img src="<?php echo $imgscr ;?>">
                                </span>
                            </span>
                        </div>
                        <div class="jsjobs-descriptions">
                            <div class="jsjob-description-data">
                               <span class="stats_data_title"><?php echo Text::_('Description'); ?>:</span> 
                               <span class="stats_data_value">
                                    <?php echo $this->package->description; ?>
                               </span>
                            </div>
                        </div> 
                </div>
                <div class="jsjobs-btn-area">
                    <span class="jsjobs-expire-days">
                        <span class="stats_data_title"><?php echo Text::_('Expire In Days'); ?>:</span>
                        <span class="stats_data_value"><?php echo $this->package->packageexpireindays; ?></span>
                    </span>
                    <span class="jsjobs-btn-buys">
                        <?php $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=package_buynow&nav=24&gd=' . $this->package->id . '&Itemid=' . $this->Itemid; ?>
                        <a class="jsjob_button" href="<?php echo $link ?>" class="pkgLink"><img src="<?php echo Uri::root();?>components/com_jsjobs/images/buy-now.png"><span class="jsjobs-buys-title"><?php echo Text::_('Buy Now'); ?></span></a>
                    </span>
                </div>
                </div>
              </div>
            </div>
            <?php
        }
        ?>	
        <?php
    } else { // not allowed job posting 
        $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
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
