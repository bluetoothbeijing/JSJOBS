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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;    

$link = "index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=" . $this->Itemid;
$document = Factory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
$document->addStyleSheet('components/com_jsjobs/css/status_graph.css');
HTMLHelper::_('jquery.framework');

?>
<script language=Javascript>
    function confirmdeleteresume() {
        return confirm("<?php echo Text::_('Are you sure to delete the resume').'!'; ?>");
    }
</script>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
    <?php ?>
        <span class="jsjobs-main-page-title"><span class="jsjobs-title-componet"><?php echo Text::_('My Resume'); ?></span>
            <a class="jsjobs-add-cover-btn" href="index.php?option=com_jsjobs&view=resume&layout=formresume&Itemid=<?php echo $this->Itemid; ?>">+<span class="jsjobs-add-resume-btn"><?php echo Text::_('Add Resume');?></span></a></span>
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
                        <?php echo Text::_('My Resume'); ?>
                    </li>
                  </ul>
                </div>
            </div>
        <?php } ?>
        <?php 

        $fieldsordering = JSModel::getJSModel('fieldsordering')->getFieldsOrderingByFieldFor(3);
        $_field = array();
        foreach($fieldsordering AS $field){
            if($field->showonlisting == 1){
                $_field[$field->field] = $field->fieldtitle;
            }
        }
	if ($this->myresume_allowed == VALIDATE) {
        if ($this->resumes) {
            if ($this->sortlinks['sortorder'] == 'ASC')
                $img = Uri::root()."components/com_jsjobs/images/sort0.png";
            else
                $img = Uri::root()."components/com_jsjobs/images/sort1.png";
            ?>
                <form action="index.php" method="post" name="adminForm">
                    <div id="sortbylinks">
                      <ul>
                        <?php if (isset($_field['application_title'])) { ?>
                            <li><a class="<?php if ($this->sortlinks['sorton'] == 'application_title') echo 'selected' ?>" href="<?php echo $link; ?>&sortby=<?php echo $this->sortlinks['application_title']; ?>"><?php if ($this->sortlinks['sorton'] == 'application_title') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Title'); ?></a></li>
                        <?php } ?>
                        <?php if (isset($_field['jobtype'])) { ?>
                           <li><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected' ?>" href="<?php echo $link; ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Job Type'); ?></a></li>
                        <?php } ?>
                        <?php if (isset($_field['salary'])) { ?>
                            <li><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected' ?>" href="<?php echo $link; ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Salary Range'); ?></a></li>
                        <?php } ?>
                        <li><a  class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected' ?>" href="<?php echo $link; ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?><?php echo Text::_('Posted'); ?></a></li>
                        </ul>
                    </div>
                    <?php
                    $isnew = date("Y-m-d H:i:s", strtotime("-" . $this->config['newdays'] . " days")); ?>
                    <div id="js-jobs-resumelisting-wrapper">  <?php
                        foreach ($this->resumes as $resume) {
                            $resumealiasid = $this->getJSModel('common')->removeSpecialCharacter($resume->resumealiasid);
                            $link_viewresume = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=1&rd=' . $resumealiasid . '&Itemid=' . $this->Itemid;
                            $link_edit = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&nav=29&rd=' . $resume->resumealiasid . '&Itemid=' . $this->Itemid;
                             ?>
                            
                            <div class="js-resume-list">
                                <div class="jsjobs-img-area">
                                    <div id="js-drag-image-to-top">
                                    <?php if (isset($_field['photo'])) { ?>
                                        <a  class="logo_a" href="<?php echo $link_viewresume;?>">
                                            <?php
                                            if ($resume->photo != '') {
                                                    $imgsrc = Uri::root().$this->config['data_directory'] . "/data/jobseeker/resume_" . $resume->id . "/photo/" . $resume->photo;
                                                } else {
                                                    $imgsrc = Uri::root()."components/com_jsjobs/images/user_b.png";
                                                }
                                            ?>
                                            <img class="logo_img" src="<?php echo $imgsrc; ?>" />
                                        </a>
                                    <?php } ?>
                                    </div>
                                </div>                            
                                <div class="js-topresume-area">
                                    <div class="jsjobs-applyname">
                                       <span class="jsjobs-titleresume">
                                            <?php if (isset($_field['jobtype'])) { ?>
                                                <span class="jsjobs-fulltime-btn">
                                                    <?php echo htmlspecialchars(Text::_($resume->jobtypetitle)); ?>
                                                </span>
                                            <?php } ?>
                                        </span>
                                        <span class="jsjobs-titleresume">
                                            <a  class="jsjobs-anchor_resume" href="<?php echo $link_viewresume;?>">
                                            <?php if (isset($_field['first_name'])) { ?>
                                                <?php echo htmlspecialchars($resume->first_name); ?>
                                            <?php } ?>
                                            <?php if (isset($_field['last_name'])) { ?>
                                                <?php echo ' ' . htmlspecialchars($resume->last_name); ?>
                                            <?php } ?>
                                            </a>
                                        </span>
                                        <div class="jsjobs-fulltime-wrapper">
                                            <span class="jsjobs-date-created">
                                                <?php
                                                echo  Text::_('Created');?>: 
                                                <?php echo HTMLHelper::_('date', $resume->created, $this->config['date_format']) ; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if (isset($_field['application_title'])) { ?>
                                        <div class="jsjobs-application-title">
                                            <?php echo $resume->application_title; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                            <div class="jsjobs-resume-data-area">
                                                <div id="myresume-fields-area">
                                                    <?php if (isset($_field['job_category'])) { ?>
                                                            <span class="js-myresume-field-wrapper">
                                                                <span class="js-myresume-field-title"><?php echo Text::_($_field['job_category']) . ": "; ?></span>
                                                                <span class="js-myresume-field-value"><?php echo Text::_($resume->cat_title); ?></span>
                                                            </span>
                                                        <?php } ?>
                                                    <?php if (isset($_field['salary'])) { ?>
                                                        <span class="js-myresume-field-wrapper">
                                                         <span class="js-myresume-field-title"><?php echo Text::_($_field['salary']) . ": "; ?></span>
                                                         <span class="js-myresume-field-value">
                                                            <?php
                                                                $salary = $this->getJSModel('common')->getSalaryRangeView($resume->symbol,$resume->rangestart,$resume->rangeend,Text::_($resume->salarytype),$this->config['currency_align']);
                                                                echo htmlspecialchars($salary);
                                                                ?>
                                                            </span>
                                                          </span>
                                                    <?php } ?>
                                                    <?php if (isset($_field['heighestfinisheducation'])) { ?>
                                                        <span class="js-myresume-field-wrapper">
                                                        <span class="js-myresume-field-title"><?php echo Text::_($_field['heighestfinisheducation']) ?>: </span>
                                                        <span class="js-myresume-field-value">
                                                            <?php echo htmlspecialchars($resume->educationtitle); ?>
                                                        </span>
                                                        </span>
                                                    <?php } ?>
                                                     <?php if (isset($_field['total_experience'])) { ?>
                                                            <span class="js-myresume-field-wrapper">
                                                            <span class="js-myresume-field-title"><?php echo Text::_($_field['total_experience']) . ": "; ?></span>
                                                            <span class="js-myresume-field-value">
                                                                <?php
                                                                if (empty($resume->exptitle))
                                                                    echo $resume->total_experience;
                                                                else
                                                                    echo Text::_($resume->exptitle);
                                                                ?>
                                                            </span>
                                                    </span>
                                                <?php }
                                                    $customfieldobj = getCustomFieldClass();
                                                    $customfields = $customfieldobj->userFieldsData( 3 , 1 , 1);
                                                    foreach ($customfields as $field) {
                                                        echo  $customfieldobj->showCustomFields($field, 9 ,$resume , 1);
                                                    }
                                                 ?>
                                                 </div>
                                            </div>
                                        <div class="jsjobs-myresume-buttons">
                                            <span class="jsjobs-resume-loction">
                                                <?php echo $resume->location; ?>
                                            </span>
                                            <span class="jsjobs-myresumebtn">
                                                <?php
                                                if ($resume->status == 0) { ?>
                                                    <font id="jsjobs-status-btn"><?php echo Text::_('Waiting for approval');?></font>
                                                <?php
                                                } elseif ($resume->status == -1) { ?>
                                                    <font id="jsjobs-status-btn-rejected"><?php echo Text::_('Rejected');?></font>
                                                <?php
                                                } elseif ($resume->status == 1) {
                                                    ?>
                                                    <a class="jsjobs-myresumes-btn" href="<?php echo $link_edit; ?>" title="<?php echo Text::_('Edit'); ?>"><?php echo Text::_('Edit'); ?></a>
                                                    <a class="jsjobs-myresumes-btn" href="<?php echo $link_viewresume; ?>" title="<?php echo Text::_('View'); ?>"><?php echo Text::_('View'); ?></a>
                                                    <?php $link_delete = 'index.php?option=com_jsjobs&task=resume.deleteresume&rd=' . $resume->resumealiasid . '&Itemid=' . $this->Itemid.'&'.Factory::getSession()->getFormToken().'=1'; ?>
                                                        <a class="jsjobs-myresumes-btn" href="<?php echo $link_delete; ?>" onclick=" return confirmdeleteresume();" class="icon" title="<?php echo Text::_('Delete'); ?>"><?php echo Text::_('Delete'); ?></a>
                                                        <?php if( $this->getJSModel(VIRTUEMARTJSJOBS)->{VIRTUEMARTJSJOBSFUN}("UGF0dFlJam9tc29jaWFsS1ducDhi") && $this->getJSModel('configurations')->getConfigValue('jomsocial_allowpostresume') == 1 ){ ?>
                                                        <a class="jsjobs-myresumes-btn jsjobs-jomsocial-icon" title="<?php echo Text::_("Post on JomSocial"); ?>" href="index.php?option=com_jsjobs&task=resume.postresumeonjomsocial&id=<?php echo $resume->id; ?>&Itemid=<?php echo $this->Itemid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1';">
                                                            <img src="<?php echo Uri::root();?>components/com_jsjobs/images/social-share.png">
                                                        </a>
                                                        <?php } ?>
                                                <?php } ?>
                                            </span>
                                        </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deleteresume" />
                    <input type="hidden" name="c" value="resume" />
                    <input type="hidden" id="id" name="id" value="" />
                    <input type="hidden" name="boxchecked" value="0" />
                </form>
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&sortby='.$this->sortlinks['sorton']. strtolower($this->sortlinks['sortorder']) .'&Itemid=' . $this->Itemid ,false); ?>" method="post">
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
        switch ($this->myresume_allowed) {
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
</div>
 

