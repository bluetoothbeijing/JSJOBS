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
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
        <span class="jsjobs-main-page-title"><?php echo Text::_('Resume By Categories'); ?></span>
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
                            <?php echo Text::_('Resume By Categories'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php
    if ($this->canview == VALIDATE) {
        ?>
        <div class="jsjobs-cat-data-wrapper">
            <?php
            $noofcols = $this->config['categories_colsperrow'];
            $colwidth = round(100 / $noofcols);
            if (isset($this->categories)) {
                foreach ($this->categories as $category) {
                    $lnks = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bycategory&cat=' . $category->aliasid . '&Itemid=' . $this->Itemid;
                    ?>
                    <div id="jsjobs-cat-mainblock" style="width:<?php echo $colwidth; ?>%;">
                        <div id="jsjobs-cat-block">
                            <a id="jsjobs-cat-block-a" href="<?php echo $lnks; ?>">
                                <span class="jsjobs-cat-title"><?php echo Text::_(trim($category->cattitle)); ?></span>
                                <span class="jsjobs-cat-counter">(<?php echo htmlspecialchars($category->total_sub_jobs); ?>)</span>
                                <span class="jsjobs-cat-counter">(<?php echo htmlspecialchars($category->total); ?>)</span>
                                <input id="catid" type="hidden" name="catid" value="<?php echo $category->catid;?>"/>
                            </a>
                            <div id="for_subcat"></div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

<?php     
    } else { // not allowed job posting 
        switch ($this->canview) {
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A package is required to perform this action').', '.Text::_('please get package');
                $this->jsjobsmessages->getPackageExpireMsg('You do not have the package', $vartext, $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A package is required to perform this action').', '.Text::_('please get the new package');
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired',$vartext, $link);
                break;
            case RESUME_SEARCH_NOT_ALLOWED_IN_PACAKGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('Resume search is not allowed in your package'). ', '. Text::_('please get the new package');
                $this->jsjobsmessages->getPackageExpireMsg('Resume search is not allowed in the package',$vartext, $link);
                break;
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Jobseeker is not allowed', 'Jobseeker is not allowed in employer private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role', $vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
                break;
        }
    } ?>
    </div>
    <?php
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
        getSubCats(catid, 'true', null );
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
        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=subcategoriesbycatidresume', {catid: id , showall: showall,itemid : '<?php echo $this->Itemid;?>'}, function (data) {
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
 
