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

if (isset($this->userrole->rolefor)) {
    if ($this->userrole->rolefor != '') {
        if ($this->userrole->rolefor == 2) // job seeker
            $allowed = true;
        elseif ($this->userrole->rolefor == 1) {
            if ($this->config['employerview_js_controlpanel'] == 1)
                $allowed = true;
            else
                $allowed = false;
        }
    }else {
        $allowed = true;
    }
} else
    $allowed = true; // user not logined
?>

<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>

<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    if ($allowed == true) {
        ?>

    <div id="jsjobs-main-wrapper">
        <span class="jsjobs-main-page-title"><?php echo Text::_('Job Category'); ?></span>
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
                            <?php echo Text::_('Job Category'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
        <div class="jsjobs-cat-data-wrapper">
            <?php
            $noofcols = $this->config['categories_colsperrow'];
            $colwidth = round(100 / $noofcols);
            if (isset($this->application)) {
                foreach ($this->application as $category) {
                    $categoryaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($category->categoryaliasid);
                    $lnks = 'index.php?option=com_jsjobs&c=job&view=job&layout=jobs&cat='.$categoryaliasid . '&Itemid=' . $this->Itemid;
                    ?>
                    <div id="jsjobs-cat-mainblock" style="width:<?php echo $colwidth; ?>%;">
                        <div id="jsjobs-cat-block">
                            <a id="jsjobs-cat-block-a" href="<?php echo $lnks; ?>">
                                <span class="jsjobs-cat-title"><?php echo Text::_(trim($category->cat_title)); ?></span>
                                <span class="jsjobs-cat-counter">(<?php echo htmlspecialchars($category->total_sub_jobs); ?>)</span>
                                <span class="jsjobs-cat-counter">(<?php echo htmlspecialchars($category->catinjobs); ?>)</span>
                                <input id="catid" type="hidden" name="catid" value="<?php echo $category->id;?>"/>
                            </a>
                            <div id="for_subcat"></div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
        <?php
    } else {  // not allowed job posting 
        $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
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

<div id="jsjob-popup-background" onclick="hidepopup();"></div>
<div id="jsjobs-listpopup"></div>
<script type="text/javascript">

    jQuery(document).ready(function ($) {
        jQuery('div#jsjobs-cat-block').on("touchstart", function (e) {
            'use strict'; //satisfy code inspectors
            var link = jQuery(this); //preselect the link
            if (link.hasClass('touch')) {
                return true;
            }else {
                link.addClass('touch');
                jQuery('div#jsjobs-cat-block').not(this).removeClass('touch');
                e.preventDefault();
                var catid = jQuery(this).find('input#catid').val();
                getSubCats(catid , 'false' , this , true);
                return false; //extra, and to make sure the function has consistent return points
            }
        });

        var timeout = null;
        $("div#jsjobs-cat-block").mouseenter(function() {
            if(timeout == null){
                timeout == true;
                var catid = $( this ).find('input#catid').val();
                getSubCats(catid , 'false' , this , false);
            }
        })
        .mouseleave(function() {
            $("div#for_subcat").html("");
            timeout = null;
        });
    });
    function showpopup(catid){
        jQuery("div#jsjob-popup-background").show();
        getSubCats(catid, 'true', null ,false);
        jQuery("div#jsjobs-listpopup").slideDown('slow');
    }

    function hidepopup(){
        setTimeout(function(){
            jQuery("div#jsjob-popup-background").hide();
            jQuery("div#jsjobs-listpopup").html('');
        }, 700);
        jQuery("div#jsjobs-listpopup").slideUp('slow');
    }
    function getSubCats(id, showall, pointer , touch){ 
        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=job&task=subcategoriesbycatid', {catid: id , showall: showall,itemid : '<?php echo $this->Itemid;?>'}, function (data) {
            if (data) {
                if(showall=='true'){
                    jQuery("div#jsjobs-listpopup").html(data);
                }else{
                    if(touch == false){
                        if(jQuery(pointer).is(":hover")) {
                            jQuery(pointer).find("div#for_subcat").html(data);
                        }
                    }else{
                        jQuery(pointer).find("div#for_subcat").html(data);
                    }
                }
            }
        });
    }
</script>
