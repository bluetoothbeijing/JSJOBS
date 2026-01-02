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
<script type="text/javascript" languge="javascript">
jQuery( document ).ready(function() {
   jQuery("span.jsjobs-package-values").last().css("border-bottom","none");
});
</script>
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
        if ($this->config['visitorview_emp_packages'] == 1)
            $allowed = true;
        else
            $allowed = false;
    } // user not logined

    if ($allowed == true) {

    function makesymbolalign($price, $symbol, $align){ if($align == 1) $p = $symbol.$price; elseif($align == 2) $p = $price.$symbol; return $p; }

        ?>
        <div id="jsjobs-main-wrapper">
            <span class="jsjobs-main-page-title"><?php echo Text::_('Packages'); ?></span>
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
                                <?php echo Text::_('Packages'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        <?php
        if (isset($this->packages) AND (!empty($this->packages))) {
			$currency_align = $this->config['currency_align'];
			?>
			<?php
            foreach ($this->packages as $package) {
                //price and disscount related code
                $discflag = 0;
                if ($package->price != 0) {
                    $curdate = date('Y-m-d H:i:s');
                    if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                        if ($package->discounttype == 2) {
                            $discflag = 1;
                            $discountamount = ($package->price * $package->discount) / 100;
                            $price = ($package->price - $discountamount);
                            $price = makesymbolalign( $price, $package->symbol, $currency_align);
                        } elseif ($package->discounttype == 1) {
                            $discflag = 1;
                            $discountamount = $package->discount;
                            $price = ($package->price - $discountamount);
                            $price = makesymbolalign( $price, $package->symbol, $currency_align);
                        }
                    } else{
                        $price = makesymbolalign( $package->price, $package->symbol, $currency_align);
                    }
                }else {
                            $price =  Text::_('Free');
                        }
                ?>

                <div class="jsjobs-package-data">
                        <span class="jsjobs-package-title">
                            <span class="jsjobs-package-name">
                                <?php
                                echo $package->title;
                                if($discflag == 1){?>
                                    <strike>
                                        <span class="total-amount"><?php echo $package->symbol.$package->price; ?></span>
                                    </strike>
                            <?php }?>
                            </span>
                            
                            <span class="stats_data_value">
                                <?php echo $price; ?>
                            </span>
                        </span>
                        <div class="jsjobs-package-listing-wrapper">
                           <div class="jsjobs-listing-datawrap">
                               <div class="jsjobs-package-data-detail">
                                   <span class="jsjobs-package-values">
                                        <span class="stats_data_title"><?php echo Text::_('Companies Allowed'); ?>:</span>
                                        <span class="stats_data_value">
                                                <?php 
                                                    if ($package->companiesallow == -1)
                                                        echo Text::_('Unlimited');
                                                    else
                                                        echo $package->companiesallow;
                                                ?>
                                        </span>
                                 </span>
                            </div>
                            <div class="jsjobs-package-data-detail">
                                <span class="jsjobs-package-values">
                                    <span class="stats_data_title"><?php echo Text::_('Jobs Allowed'); ?>:</span>
                                    <span class="stats_data_value">
                                        <?php if ($package->jobsallow == -1)
                                            echo Text::_('Unlimited');
                                        else
                                            echo $package->jobsallow;
                                        ?>
                                    </span>
                                </span>
                            </div>
                            <div class="jsjobs-package-data-detail">
                                <span class="jsjobs-package-values">
                                    <span class="stats_data_title"><?php echo Text::_('View Resume In Details'); ?>:</span>
                                    <span class="stats_data_value">
                                        <?php if ($package->viewresumeindetails == -1)
                                            echo Text::_('Unlimited');
                                        else
                                            echo $package->viewresumeindetails;
                                        ?>
                                    </span>
                                </span>
                            </div>
                            <div class="jsjobs-package-data-detail">
                                <span class="jsjobs-package-values">
                                    <span class="stats_data_title"><?php echo Text::_('Resume Search'); ?>:</span>
                                    <span class="stats_data_value">
                                        <?php if ($package->resumesearch == 1)
                                            $imgscr  = Uri::root().'components/com_jsjobs/images/publish-icon.png' ;
                                              else
                                         $imgscr  = Uri::root().'components/com_jsjobs/images/reject-s.png' ;
                                        ?> 
                                            <img src="<?php echo $imgscr ;?>">
                                    </span>
                                </span>
                            </div>
                            <div class="jsjobs-package-data-detail">
                                <span class="jsjobs-package-values bordernone">
                                    <span class="stats_data_title"><?php echo Text::_('Resume Save Search'); ?>:</span>
                                    <span class="stats_data_value">
                                        <?php if ($package->saveresumesearch == 1) $imgscr  = Uri::root().'components/com_jsjobs/images/publish-icon.png' ;
                                        else $imgscr  = Uri::root().'components/com_jsjobs/images/reject-s.png' ;
                                    ?> 
                                    <img src="<?php echo $imgscr ;?>">
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="jsjobs-apply-button">
                        <span class="jsjobs-buy-btn">
                            <?php $link = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=package_buynow&nav=23&gd=' . $package->id . '&Itemid=' . $this->Itemid; ?>
                            <a class="js_job_button" href="<?php echo $link ?>" ><img src="<?php echo Uri::root();?>components/com_jsjobs/images/buy-now.png"> <?php echo Text::_('Buy Now'); ?></a>
                        </span>
                        <span class="jsjobs-view-btn">
                            <?php  $link2 = 'index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=package_buynow&jslayfor=detail&nav=23&gd=' . $package->id . '&Itemid=' . $this->Itemid; ?>
                            <a class="js_job_button" href="<?php echo $link2 ?>" ><?php echo Text::_('Details View'); ?></a>
                        </span>
                        <span class="jsjobs-expiredays">
                            <span class="stats_data_title"><?php echo Text::_('Expire In Days'); ?>:</span>
                            <span class="stats_data_value"><?php echo $package->packageexpireindays; ?></span>
                        </span>
                    </div>
                </div>
                <?php
                    $curdate = date('Y-m-d H:i:s');
                    if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                        if ($package->discountmessage != '') { ?>
                            <div class="disc-message">
                                <?php echo $package->discountmessage;?>
                            </div>
                <?php 
                        }
                    }
                ?>
            </div>
            <?php } ?>  
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
        } ?>
        </div>
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
