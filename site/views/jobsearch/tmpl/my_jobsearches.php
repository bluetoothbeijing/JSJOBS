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
use Joomla\CMS\Router\Route;    

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
    <div id="jsjobs-main-wrapper">
        <span class="jsjobs-main-page-title"><?php echo Text::_('Job Save Searches'); ?></span>
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
                            <?php echo Text::_("Job Save Searches"); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php
    if ($this->myjobsavesearch_allowed == VALIDATE) {
        if ($this->jobsearches) {
            ?>
                <form action="index.php" method="post" name="adminForm">
                    <?php
                    jimport('joomla.filter.output');
                    foreach ($this->jobsearches as $search) {
                        ?>
                        <div class="jsjobs-listing-wrapper">
                          <div class="jsjobs-jobs-save">
                            <span class="jsjobs-coverletter-title"><?php echo htmlspecialchars($search->searchname); ?></span>
                            <div class="jsjobs-cover-button-area" >
                                <span class="jsjobs-coverletter-created"><span class="jsjobs-coverletter-created-title"><span><?php echo Text::_('Created'); ?>&nbsp;:</span><?php echo HTMLHelper::_('date', $search->created, $this->config['date_format']); ?></span></span>
                                <span class="jsjobs-btn-save">
                                <a class="js_listing_icon" href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs&search=<?php echo $search->id; ?>&Itemid=<?php echo $this->Itemid; ?>">
                                   <?php echo Text::_("View"); ?>
                                </a>
                                <a class="js_listing_icon" href="index.php?option=com_jsjobs&task=jobsearch.deletejobsearch&js=<?php echo $search->id; ?>&Itemid=<?php echo $this->Itemid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1">
                                    <?php echo Text::_("Force Delete"); ?>
                                </a>
                                </span>
                            </div>
                            </div>
                        </div>
                    <?php } ?>		
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deletejobsearch" />
                    <input type="hidden" name="jobsearch" value="deletejobsearch" />
                    <input type="hidden" id="id" name="id" value="" />
                    <input type="hidden" name="boxchecked" value="0" />
                </form>
                <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=jobsearch&view=jobsearch&layout=my_jobsearches&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
    } else {
        switch ($this->myjobsavesearch_allowed) {
            case JOB_SAVE_SEARCH_NOT_ALLOWED_IN_PACKAGE:
                $vartext = Text::_('Save search is not allowed in your package').','.Text::_('please get the package which has this option');
                $this->jsjobsmessages->getAccessDeniedMsg('Save search not allowed',$vartext, 0);
            break;
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
            break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role', $vartext, $link);
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
</div> 
