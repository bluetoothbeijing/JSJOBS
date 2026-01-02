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
} else { ?>
        <div id="jsjobs-main-wrapper">
            <span class="jsjobs-main-page-title"><span class="jsjobs-title-componet"><?php echo Text::_('My Departments'); ?></span>
            <span class="jsjobs-add-resume-btn"><a class="jsjobs-resume-a" href="index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment&Itemid=<?php echo $this->Itemid;?>">+<span class="jsjobs-add-resume-btn"><?php echo Text::_('Add New Department'); ?></span></a></span>
            </span>
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
                                <?php echo Text::_('My Departments'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
<?php
	if ($this->mydepartment_allowed == VALIDATE) {        
        if ($this->departments) {
            ?>
            <!-- Your Area starts here -->
                <form action="index.php" method="post" name="adminForm">
                    <div class="jsjobs-folderinfo">
                    <?php foreach ($this->departments as $department) { ?>
                        <div class="jsjobs-main-mydepartmentlist">  
                            <div class="jsjob-main-department">
                                <div class="jsjobs-main-department-left">
                                    <span class="jsjobs-coverletter-title">
                                      <span class="jsjobs-title-name"><strong><?php echo $department->name; ?></strong></span>
                                      <span class="jsjobs-coverletter-created"><span class="js_coverletter_created_title"><?php echo Text::_('Created'); ?>:&nbsp;</span><?php echo HTMLHelper::_('date', $department->created, $this->config['date_format']); ?></span>
                                    </span>
                                    <span class="jsjobs-category-status">
                                    <?php 
                                        $companyaliasid = JSModel::getJSModel('common')->removeSpecialCharacter($department->companyaliasid);
                                        $link_viewcomp = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=31&cd=' . $companyaliasid . '&Itemid=' . $this->Itemid;
                                    ?>
                                        <span class="jsjobs-listing-title-child"><span class="jsjobs-company"><a href="<?php echo $link_viewcomp;?>"> <?php echo $department->companyname; ?></a></span></span>
                                        <span class="jsjobs-listing-title-child">
                                            <span class="dept-status">
                                                <strong>
                                                    <?php
                                                        if ($department->status == 1){
                                                            echo '<span class="approve">' . Text::_('Approved') . '</span>';
                                                        }elseif ($department->status == 0) {
                                                            echo '<span class="pending">' . Text::_('Pending') . '</span>';
                                                        } elseif ($department->status == -1){
                                                            echo '<span class="reject">' . Text::_('Rejected') . '</span>';
                                                        }
                                                    ?>
                                                </strong>
                                            </span>
                                       </span>
                                   </span>
                                </div>
                                <?php if ($department->status == 0) { ?>
                                            <font id="jsjobs-status-btn" class="margin-top"><?php echo Text::_('Waiting for approval');?></font>
                                        <?php
                                        } elseif ($department->status == -1) { ?>
                                            <font id="jsjobs-status-btn-rejected" class="margin-top"><?php echo Text::_('Rejected');?></font>
                                        <?php
                                        } elseif ($department->status == 1) { ?>
                                 <div class="jsjobs-main-department-right">
                                    <div class="jsjobs-coverletter-button-area" >
                                        <a class="js_listing_icon" href="index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment&pd=<?php echo $department->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo Text::_('Edit'); ?>">
                                            <?php echo Text::_('Edit'); ?>
                                        </a>
                                        <a class="js_listing_icon" href="index.php?option=com_jsjobs&c=department&view=department&layout=view_department&pd=<?php echo $department->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo Text::_('View'); ?>" >
                                            <?php echo Text::_('View'); ?>
                                        </a>
                                        <a class="js_listing_icon" href="index.php?option=com_jsjobs&task=department.deletedepartment&pd=<?php echo $department->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1" title="<?php echo Text::_('Delete'); ?>">
                                            <?php echo Text::_('Delete'); ?>
                                        </a>
                                    </div> 
                                 </div>
                        <?php } ?>  
                            </div>
                        </div>
                <?php
            }
            ?>      
                    </div>            
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deletedepartment" />
                    <input type="hidden" name="department" value="deletedepartment" />
                    <input type="hidden" id="id" name="id" value="" />
                     <?php echo HTMLHelper::_( 'form.token' ); ?>    
                </form>
            <!-- End your area -->
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=department&view=department&layout=mydepartments&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
            } ?>
        </div>
    <?php
    } else { // not allowed 
        switch ($this->mydepartment_allowed) {
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Jobseeker is not allowed', 'Jobseeker is not allowed in employer private area', 0);
            break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
            break;
            case VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
</div>
<?php
$document = Factory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
?>
