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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
HTMLHelper::_('behavior.formvalidator');
?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
    <script language="javascript">
        function myValidate(f) {
            if (document.formvalidator.isValid(f)) {
                f.check.value = '<?php if (JVERSION < 3)
        echo JUtility::getToken();
    else
        echo Factory::getSession()->getFormToken();
    ?>';//send token
            } else {
                alert("<?php echo Text::_("Some values are not acceptable, please retry"); ?>");
                return false;
            }
            return true;
        }

    </script>
    <?php
    if(isset($this->coverletter->id)){
        $heading = Text::_("Edit Cover Letter");
    }else{
        $heading = Text::_("Form Cover Letter");
    } ?>
    <div id="jsjobs-main-wrapper">
        <span class="jsjobs-main-page-title"><?php echo $heading; ?></span>
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
                            <a href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo Text::_('My Cover Letter'); ?>">
                                <?php echo Text::_('My Cover Letter'); ?>
                            </a>
                        </li>
                        <li>
                            <?php echo Text::_($heading); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php    
    if ($this->canaddnewcoverletter == VALIDATE) {    // add new coverletter, in edit case always 1
        ?>
        	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate jsautoz_form" onSubmit="return myValidate(this);">
                <div class="jsjobs-field-main-wrapper">
	        	<div id="jsjobs-field-wrapper-title">
	        		<div class="jsjobs-field">
	        			<label id="titlemsg" for="title"><?php echo Text::_('Title'); ?><font id="font" color="red">*</font></label>
	        		</div>
	        		<div class="jsjobs-value">
	        			<input class="inputbox required" type="text" name="title" id="title" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->coverletter)) echo $this->coverletter->title; ?>" />
	        		</div>
	        	</div>
	        	<div id="jsjobs-field-wrapper-description">
	        		<div class="jsjobs-field">
	        			<label id="descriptionmsg" for="description"><?php echo Text::_('Description'); ?><font color="red">*</font></label>
	        		</div>
	        		<div class="jsjobs-value">
	        			<textarea class="inputbox required" name="description" id="description" cols="60" rows="9"><?php if (isset($this->coverletter)) echo $this->coverletter->description; ?></textarea>
	        		</div>
	        	</div>
                <div class="jsjobs-jobsalertinfo-save-btn">
                    
                     
	                       <input type="submit" id="button" class="button jsjobs_button" value="<?php echo Text::_('Save Cover Letter'); ?>"/>
	                  
                 
	        	</div>


            </div>
                <?php
                if (isset($this->coverletter)) {
                    if (($this->coverletter->created == '0000-00-00 00:00:00') || ($this->coverletter->created == ''))
                        $curdate = date('Y-m-d H:i:s');
                    else
                        $curdate = $this->coverletter->created;
                } else
                    $curdate = date('Y-m-d H:i:s');
                ?>                
                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="id" value="<?php if (isset($this->coverletter)) echo $this->coverletter->id; ?>" />
                <input type="hidden" name="layout" value="empview" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="savecoverletter" />
                <input type="hidden" name="c" value="coverletter" />
                <input type="hidden" name="check" value="" />
                <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
		        <?php if (isset($this->packagedetail[0])) echo '<input type="hidden" name="packageid" value="' . $this->packagedetail[0] . '" />'; ?>
		        <?php if (isset($this->packagedetail[1])) echo '<input type="hidden" name="paymenthistoryid" value="' . $this->packagedetail[1] . '" />'; ?>
                <?php echo HTMLHelper::_( 'form.token' ); ?>      
            </form>	        	
        <?php
    } else { // can not add new coverletter 
        switch ($this->canaddnewcoverletter) {
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
                break;
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A package is required to perform this action').','.Text::_('please get A package');
                $this->jsjobsmessages->getPackageExpireMsg('You do not have the package', $vartext, $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A A Package is required to perform this action and your current package is expired').','.Text::_('please get the new package');
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired', $vartext, $link);
                break;
            case COVER_LETTER_LIMIT_EXCEEDS:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('You can not add a new cover letter'). ',' .Text::_('Please get the package to extend your cover letter limit');
                $this->jsjobsmessages->getPackageExpireMsg('Cover letter limit exceeds',$vartext, $link);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not Logged in', 'Please log in to access the private area', 1);
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
