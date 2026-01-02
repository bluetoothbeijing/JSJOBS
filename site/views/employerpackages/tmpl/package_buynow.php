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

jimport('joomla.application.component.model');
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

function makesymbolalign($price, $symbol, $align){ if($align == 1) $p = $symbol.$price; elseif($align == 2) $p = $price.$symbol; return $p; }
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { // employer
        $currency_align = $this->config['currency_align'];
        ?>
        <form action="index.php" method="post" name="adminForm">
            <?php if (isset($this->package)) {
                $layoutfor = $this->layoutfor;
                if($layoutfor=='detail'){
                    $style = "style=display:none;";
                    $layout_title = Text::_('Package Detail');
                }
                else{
                    $style = "";
                    $layout_title = Text::_('Buy Now');
                }
                //price and disscount related code
                    $discflag = 0;
                    $discountamount = 0;
                    if ($this->package->price != 0) {
                        $curdate = date('Y-m-d H:i:s');
                        if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {
                            if ($this->package->discounttype == 2) {
                                $discflag = 1;
                                $discountamount = ($this->package->price * $this->package->discount) / 100;
                                $price = $this->package->price - $discountamount;
                                if($price > 0){
                                    $price = makesymbolalign($price, $this->package->symbol, $currency_align);
                                }else{
                                    $price = Text::_('Free');
                                }
                            } elseif ($this->package->discounttype == 1) {
                                $discflag = 1;
                                $discountamount = $this->package->discount;
                                $price = $this->package->price - $discountamount;
                                if($price > 0){
                                    $price = makesymbolalign($price, $this->package->symbol, $currency_align);
                                }else{
                                    $price = Text::_('Free');
                                }
                            }
                        } else
                            $price = makesymbolalign($this->package->price, $this->package->symbol, $currency_align);
                    }else {
                        $price =  Text::_('Free');
                    }
                ?>
                <div id="jsjobs-main-wrapper">
                    <span class="jsjobs-main-page-title"><?php echo $layout_title; ?></span>
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
                                        <?php echo Text::_($layout_title); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="jsjobs-package-data">
                        <span class="jsjobs-package-title">
                            <span class="jsjobs-package-name">
                                <?php
                                echo $this->package->title;
                                if($discflag == 1){ ?>
                                    <strike>
                                        <span class="total-amount"><?php echo makesymbolalign($this->package->price, $this->package->symbol, $currency_align); ?></span>
                                    </strike>
                                <?php }?>
                            </span>	
                            <span class="jsjobs-package-price-details">
                                <span class="stats_data_value">
                                     <?php
                                        echo $price;
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
                                    <span class="stats_data_title"><?php echo Text::_('Resume Save Search'); ?>:</span>
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
                        </div>
                        <?php
                            // to handle if 100% discount or discount amount equals to price
                            if ($price == Text::_('Free')) {
                                $showpaymentmethod = false;
                            } else {
                                $showpaymentmethod = true;
                            }
                        ?>
                        <div id="" class="jsjobs-package-listing-wrapper">
                            <div class="js_listing_wrapper">
                                <div class="jsjobs-expireindays"> 
                                    <?php echo Text::_('Expire In Days'); ?>:
                                    <?php echo $this->package->packageexpireindays; ?>
                                </div>
                                <?php
                                //this code handles buy now button 
                                if($layoutfor=='detail'){ 
                                    if ($showpaymentmethod == false) { ?>
                                        <input class="js_job_button" type="button" rel="button" onclick="setpaymentmethods('free')" name="submit_app" value="<?php echo Text::_('Buy Now'); ?>" />
                                    <?php }else{?>
                                        <a id="jsjobs_buy_nowbtn_a" href="#"><?php echo Text::_('Buy Now');?></a>
                                    <?php } 
                                }else{ ?>
                                    <div class="paymentmethod text-right">  
                                        <input class="js_job_button" type="button" rel="button" onclick="setpaymentmethods('free')" name="submit_app" value="<?php echo Text::_('Buy Now'); ?>" />
                                    </div> 
                                <?php } // end of buy now button`s code ?>
                            </div>
                        </div>
                        <?php
                            $curdate = date('Y-m-d H:i:s');
                            if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {?>
                                <div class="disc-message">
                                    <?php if ($this->package->discountmessage != '') echo $this->package->discountmessage; ?>
                                </div>
                        <?php } ?>
                 <?php } ?>

                <input type="hidden" name="task" value="saveemployerpayment" />
                <input type="hidden" name="c" value="purchasehistory" />
                <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                <input type="hidden" name="packageid" value="<?php if (isset($this->package)) echo $this->package->id; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />

                <input type="hidden" name="packagefor" id="packagefor" value="1"  />
                <input type="hidden" name="paymentmethod" id="paymentmethod"  />
                <input type="hidden" name="paymentmethodid" id="paymentmethodid"  />
            </div>
        </div>
        </form>

        <?php
    } else { // not allowed job posting 
        if($this->uid < 1){ // not logind
            $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
        }else{    
            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function(){
        jQuery('#jsjobs_buy_nowbtn_a').click(function(e){
            e.preventDefault();
            jQuery('div#jsjobs-show_buynow_div').slideToggle();
        });
    });

    function setpaymentmethods(paymethod) {
        var paymethodvalue = document.getElementById('paymentmethod').value = paymethod;
        document.adminForm.submit();
    }
</script>
