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
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Router\Route;    

?>
<script language=Javascript>
    function confirmdeletecoverletter() {
        return confirm("<?php echo Text::_('Are you sure to delete the cover letter').'!'; ?>");
    }
</script>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link'; if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
            <span class="jsjobs-main-page-title"><span class="jsjobs-title-coverletter"><?php echo Text::_('My Cover Letter');?></span><a class="jsjobs-add-cover-btn" href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=formcoverletter&Itemid=<?php echo $this->Itemid; ?>">+<span class="jsjobs-add-resume-btn"><?php echo Text::_('Add Cover Letter');?></span></a></span>
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
                                <?php echo Text::_('My Cover Letter'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
    <?php
    if ($this->mycoverletter_allowed == VALIDATE) {
        if ($this->coverletters) {
            ?>
                 <form action="index.php" method="post" name="adminForm">
                 <?php
                    jimport('joomla.filter.output');
                    foreach ($this->coverletters as $letter) {
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=edit&cid[]=' . $letter->id);
                        ?>
                        <div class="jsjobs-listing-main-wrapper">
                            <div class="jsjobs-listing-area">
                                <span class="jsjobs-coverletter-title"><?php echo htmlspecialchars($letter->title); ?></span>
                                <div class="jsjobs-coverletter-button-area" >
                                    <span class="jsjobs-coverletter-created"><span><?php echo Text::_('Created'); ?>&nbsp;:</span><?php echo HTMLHelper::_('date', $letter->created, $this->config['date_format']); ?></span>
                                    <div class="jsjobs-icon">
                                        <div class="jsjobs-icon-btn">
                                            <a href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=formcoverletter&cl=<?php echo $letter->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>"  title="<?php echo Text::_('Edit'); ?>">
                                                <?php echo Text::_('Edit'); ?>
                                            </a>
                                            <a href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=view_coverletter&nav=8&cl=<?php echo $letter->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo Text::_('View'); ?>">
                                                <?php echo Text::_('View'); ?>
                                            </a>
                                            <a href="index.php?option=com_jsjobs&task=coverletter.deletecoverletter&cl=<?php echo $letter->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1" onclick=" return confirmdeletecoverletter();" title="<?php echo Text::_('Delete'); ?>">
                                                <?php echo Text::_('Delete'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                 <?php
                    }
                    ?>      
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deletecoverletter" />
                    <input type="hidden" name="coverletter" value="deletecoverletter" />
                    <input type="hidden" id="id" name="id" value="" />
                    <input type="hidden" name="boxchecked" value="0" />  
                    <?php echo HTMLHelper::_( 'form.token' ); ?>       
                 </form>
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=mycoverletters&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
        <?php } else { // no result found in this category
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
            }
        ?>
        </div>
        <?php
    } else {
        switch ($this->mycoverletter_allowed) {
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role', $vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
                break;
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions</a></span>
            </td>
        </tr>
    </table>
</div>
</div>
