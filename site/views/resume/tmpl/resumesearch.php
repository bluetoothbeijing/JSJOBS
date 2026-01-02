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

jimport('joomla.html.pane');
$document = Factory::getDocument();
$document->addScript(Uri::base() . '/includes/js/joomla.javascript.js');
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
//HTMLHelper::_('behavior.calendar'); 
if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    HTMLHelper::_('bootstrap.framework');
    HTMLHelper::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/combobox/chosen.jquery.js');
$document->addScript('components/com_jsjobs/js/combobox/prism.js');

$width_big = 40;
$width_med = 25;
$width_sml = 15;
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
} else { ?>
        <div id="jsjobs-main-wrapper">
            <span class="jsjobs-main-page-title"><?php echo Text::_('Resume Search'); ?></span>
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
                                <?php echo Text::_('Resume Search'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
    <?php
        if ($this->canview == VALIDATE) { ?>
            <div class="jsjobs-field-main-wrapper">
            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_searchresults&Itemid=' . $this->Itemid ,false); ?>" method="post" name="adminForm" id="adminForm" class="jsautoz_form" >
                <?php 
                    $k = 1;
                    $customfieldobj = getCustomFieldClass();
                    $fieldsordering = $this->getJSModel('fieldsordering')->getFieldsOrderingForSearchByFieldFor(3);
                    foreach ($fieldsordering as $field) {
                        switch ($field->field) {
                            case 'application_title': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'first_name': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="name" size="40" maxlength="255"  />
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'nationality': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['nationality']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'gender': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['gender']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'iamavailable': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input type='checkbox' name='iamavailable' value='1' <?php if (isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> />
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'job_category': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['jobcategory']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'job_subcategory': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue" id="fj_subcategory">
                                        <?php echo $this->searchoptions['jobsubcategory']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'jobtype': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['jobtype']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'salary': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <span class="jsjobs-currancy"><?php echo $this->searchoptions['currency']; ?></span>
                                        <span class="jsjobs-salaryrange"><?php echo $this->searchoptions['jobsalaryrange']; ?></span>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'heighestfinisheducation': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['heighestfinisheducation']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'total_experience': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['experiencemin']; ?>
                                        <?php echo $this->searchoptions['experiencemax']; ?>
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'address_city': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input type="text" name="searchcity" size="40" id="searchcity"  value="" />
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'address_zipcode': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="zipcode" size="10" maxlength="15"  />
                                    </div>
                                </div>                      
                            <?php
                            break;
                            case 'keywords': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="keywords" size="40"   />
                                    </div>
                                </div>                      
                            <?php
                            break;
                            default:
                                $params = null;
                                $f_res = $customfieldobj->formCustomFieldsForSearch($field, $k, $params , 1);
                                $cls='';
                                if($field->userfieldtype == 'radio' || $field->userfieldtype == 'checkbox')
                                {
                                    $cls='jsjobs-radiobtn-wrp';
                                }
                                else if($field->userfieldtype == 'multiple')
                                {
                                    $cls='jsjobs-multiselect-wrp';
                                }
                                else if($field->userfieldtype == 'file')
                                {
                                    $cls='jsjobs-file-wrp';
                                }   
                                if($f_res){ ?>
                                    <div class="jsjobs-fieldwrapper">
                                        <div class="jsjobs-fieldtitle">
                                            <?php echo Text::_($f_res['title']); ?>
                                        </div>
                                        <div class="jsjobs-fieldvalue <?php echo $cls; ?>">
                                            <?php echo $f_res['field']; ?>
                                        </div>
                                    </div>
                                    <?php
                                } 
                            break;
                        }
                    }
                    ?>
                <div class="fieldwrapper-btn">
                    <div class="jsjobs-folder-info-btn">
                        <span class="jsjobs-folder-btn">
                            <input type="submit" id="button" class="button jsjobs_button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo Text::_('Resume Search'); ?>" />
                        </span>
                    </div>
                </div>

                <input type="hidden" name="isresumesearch" value="1" />
                <input type="hidden" name="view" value="resume" />
                <input type="hidden" name="layout" value="resume_searchresults" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task11" value="view" />
                <script language="javascript">
                    function fj_getsubcategories(src, val) {
                        jQuery("#" + src).html("Loading ...");
                        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=listsubcategoriesForSearch', {val: val}, function (data) {
                            if (data) {
                                jQuery("#" + src).html(data);
                                jQuery("#" + src + " select.jsjobs-cbo").chosen();
                            } else {
                                jQuery("#" + src).html('<?php echo '<input type="text" name="jobsubcategory" value="">'; ?>');
                            }
                        });

                    }
                </script>


            </form>
            </div>
        <?php
    } else {
        switch ($this->canview) {
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A package is required to perform this action').', '.Text::_('please get package');
                $this->jsjobsmessages->getPackageExpireMsg('You do not have the package', $vartext, $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A Package is required to perform this action and your current package is expired').','.Text::_('please get the new package');
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired',$vartext, $link);
                break;
            case RESUME_SEARCH_NOT_ALLOWED_IN_PACAKGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $vartext = Text::_('A Package is required to perform this action and your current package is expired').','.Text::_('please get the new package');
                $this->jsjobsmessages->getPackageExpireMsg('Resume search is not allowed in the package',$vartext, $link);
                break;
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Jobseeker is not allowed', 'Jobseeker is not allowed in employer private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').', '.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
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
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>
</div>
 
<script type="text/javascript" language="javascript">
    jQuery(document).ready(function () {
        jQuery("#searchcity").tokenInput("<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
            theme: "jsjobs",
            preventDuplicates: true,
            hintText: "<?php echo Text::_('Type In A Search'); ?>",
            noResultsText: "<?php echo Text::_('No Results'); ?>",
            searchingText: "<?php echo Text::_('Searching...'); ?>",
            tokenLimit: 1
        });

    });

</script>
